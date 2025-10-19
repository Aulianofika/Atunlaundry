<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;

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
        $services = Service::where('is_active', true)->get();
        return view('orders.create', compact('services'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'pickup_method' => 'required|in:pickup,delivery',
            'notes' => 'nullable|string',
        ]);

        $order = Order::create([
            'order_code' => Order::generateOrderCode(),
            'user_id' => Auth::id(),
            'service_id' => $request->service_id,
            'order_type' => 'login',
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'pickup_method' => $request->pickup_method,
            'notes' => $request->notes,
        ]);

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
}
