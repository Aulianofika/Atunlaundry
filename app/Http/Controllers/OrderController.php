<?php

namespace App\Http\Controllers;


use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


use Illuminate\Support\Facades\Notification;
use App\Notifications\NewOrderNotification;
use App\Models\User;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['user', 'service'])
            ->when(!Auth::user()?->isAdmin(), function($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        // Auto-seed services if database is empty (fallback for hosting environments)
        if (Service::count() === 0) {
            $this->seedDefaultServices();
        }

        // ensure services are returned in creation order so newly added
        // services show at the bottom of the selection list
        $services = Service::where('is_active', true)->orderBy('id')->get();
        return view('orders.create', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'pickup_method' => 'required|in:pickup,delivery',
            'customer_address' => 'required|string',
            'items_description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $order = Order::create([
            'order_code' => Order::generateOrderCode(),
            'user_id' => Auth::id(),
            'service_id' => $request->service_ids[0],
            'service_ids' => $request->service_ids,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'pickup_method' => $request->pickup_method,
            'items_description' => $request->items_description,
            'notes' => $request->notes,
            'status' => 'waiting_for_pickup',
            'order_type' => 'login',
        ]);

        // Send notification to admins
        try {
            $admins = User::where('role', 'admin')->get();
            if ($admins->count() > 0) {
                Notification::send($admins, new NewOrderNotification($order));
            }
        } catch (\Exception $e) {
            // Continue even if notification fails
        }

        return redirect()->route('orders.show', $order)
            ->with('success', 'Pesanan berhasil dibuat! Mohon tunggu konfirmasi admin.');
    }

// ...

    public function show(Order $order)
    {
        // Use loose comparison != to avoid issues with string/int types
        if (!Auth::user()->isAdmin() && $order->user_id != Auth::id()) {
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
