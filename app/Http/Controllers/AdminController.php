<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Service;
use App\Models\Promotion;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        // Authentication will be handled by route middleware
    }

    private function checkAdmin()
    {
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            abort(403);
        }
    }

    public function dashboard()
    {
        $this->checkAdmin();
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::whereIn('status', ['waiting_for_pickup', 'picked_and_weighed', 'waiting_for_payment', 'waiting_for_admin_verification'])->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'total_revenue' => Order::where('status', 'completed')->sum('price'),
            'recent_orders' => Order::with(['user', 'service'])->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function orders()
    {
        $this->checkAdmin();
        $orders = Order::with(['user', 'service'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $this->checkAdmin();
        $request->validate([
            'status' => 'required|in:waiting_for_pickup,picked_and_weighed,waiting_for_payment,waiting_for_admin_verification,processed,completed',
            'weight' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $order->update($request->only(['status', 'weight', 'price', 'notes']));

        return back()->with('success', 'Order updated successfully!');
    }

    public function verifyPayment(Request $request, Order $order)
    {
        $this->checkAdmin();
        $order->update([
            'payment_verified' => true,
            'status' => 'processed'
        ]);

        return back()->with('success', 'Payment verified successfully!');
    }

    public function createManualOrder()
    {
        $this->checkAdmin();
        $services = Service::where('is_active', true)->get();
        return view('admin.orders.create-manual', compact('services'));
    }

    public function storeManualOrder(Request $request)
    {
        $this->checkAdmin();
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
            'service_id' => $request->service_id,
            'order_type' => 'manual',
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'pickup_method' => $request->pickup_method,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.orders')->with('success', 'Manual order created successfully! Order code: ' . $order->order_code);
    }
}
