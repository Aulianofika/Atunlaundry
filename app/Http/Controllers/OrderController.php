<?php

namespace App\Http\Controllers;


use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'service'])
            ->when(!Auth::user()?->is_admin, function($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        // ensure services are returned in creation order so newly added
        // services show at the bottom of the selection list
        $services = Service::where('is_active', true)->orderBy('id')->get();
        return view('orders.create', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'service_ids' => 'nullable|array',
            'service_ids.*' => 'nullable|exists:services,id',
            'items_description' => 'nullable|string',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'pickup_method' => 'required|in:pickup,delivery',
            'notes' => 'nullable|string',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $orderData = [
            'order_code' => Order::generateOrderCode(),
            'user_id' => Auth::id(),
            'order_type' => 'login',
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'pickup_method' => $request->pickup_method,
            'notes' => $request->notes,
        ];

        // handle services: either single service_id or multiple service_ids
        if ($request->filled('service_ids')) {
            $ids = array_values(array_filter($request->input('service_ids')));
            $orderData['service_ids'] = json_encode($ids);
            // keep first as service_id for compatibility
            $orderData['service_id'] = $ids[0] ?? null;
        } elseif ($request->filled('service_id')) {
            $orderData['service_id'] = $request->service_id;
        }

        // items description
        if ($request->filled('items_description')) {
            $orderData['items_description'] = $request->items_description;
        }

        $order = Order::create($orderData);

        // Handle optional payment proof uploaded during order creation
        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->storeAs('payment_proofs', $filename, 'public');

            $order->update([
                'payment_proof' => $filename,
                'status' => 'waiting_for_admin_verification'
            ]);
        }

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order created successfully! Your order code is: ' . $order->order_code);
    }

    public function show(Order $order)
    {
        if (!Auth::user()->is_admin && $order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['user', 'service']);
        return view('orders.show', compact('order'));
    }

    public function checkStatus(Request $request)
    {
        $order = null;

        if ($request->filled('order_code')) {
            $order = Order::where('order_code', $request->order_code)
                ->with(['user', 'service'])
                ->first();
        }

        return view('orders.check-status', compact('order'));
    }

    public function uploadPaymentProof(Request $request, Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->storeAs('payment_proofs', $filename, 'public');

            $order->update([
                'payment_proof' => $filename,
                'status' => 'waiting_for_admin_verification'
            ]);

            return back()->with('success', 'Payment proof uploaded successfully!');
        }

        return back()->with('error', 'Failed to upload payment proof.');
    }
    public function uploadViewProof(Request $request, Order $order)
{
    $request->validate([
        'weight' => 'required|numeric',
        'view_proof' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
    ]);

    // Simpan berat
    $order->weight = $request->weight;

    // Jika admin upload bukti timbangan
    if ($request->hasFile('view_proof')) {

        // Pastikan folder ada
        if (!Storage::exists('public/scale_proofs')) {
            Storage::makeDirectory('public/scale_proofs');
        }

        // Hapus file lama jika ada
        if ($order->view_proof && Storage::exists('public/scale_proofs/' . $order->view_proof)) {
            Storage::delete('public/scale_proofs/' . $order->view_proof);
        }

        // Upload file baru
        $file = $request->file('view_proof');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $file->storeAs('public/scale_proofs', $filename);

        $order->view_proof = $filename;
    }

    $order->save();

    return back()->with('success', 'Berat & bukti timbangan berhasil diperbarui!');
}

}
