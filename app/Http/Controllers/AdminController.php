<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Service;
use App\Models\Promotion;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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

        // Get online customers (users with role 'customer')
        $onlineCustomers = User::where('role', 'customer')->get()->map(function ($user) {
            return (object) [
                'name' => $user->name,
                'contact' => $user->email,
                'type' => 'Online',
            ];
        });

        // Get manual customers (distinct by phone number)
        $manualCustomers = Order::where('order_type', 'manual')
            ->select('customer_name', 'customer_phone')
            ->distinct('customer_phone')
            ->get()
            ->map(function ($order) {
                return (object) [
                    'name' => $order->customer_name,
                    'contact' => $order->customer_phone,
                    'type' => 'Manual',
                ];
            });

        // Merge the two collections
        $customers = $onlineCustomers->merge($manualCustomers);

        return view('admin.dashboard', compact('stats', 'customers'));
    }

    public function orders()
    {
        $this->checkAdmin();
        $orders = Order::with(['user', 'service'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function showOrder(Order $order)
    {
        $this->checkAdmin();

        $order->load(['service', 'user']);

        // also load active services so admin can change service selection
        $services = Service::where('is_active', true)->orderBy('id')->get();

        return view('admin.orders.show', compact('order', 'services'));
    }

    public function updateOrderServices(Request $request, Order $order)
    {
        $this->checkAdmin();

        $request->validate([
            'service_ids' => 'nullable|array',
            'service_ids.*' => 'exists:services,id',
            'items_description' => 'nullable|string',
        ]);

        $ids = array_values(array_filter($request->input('service_ids', [])));

        $order->service_ids = $ids ? json_encode($ids) : null;
        $order->service_id = $ids[0] ?? $order->service_id;
        if ($request->filled('items_description')) {
            $order->items_description = $request->input('items_description');
        }

        $order->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'service_ids' => $ids]);
        }

        return back()->with('success', 'Layanan pada pesanan diperbarui.');
    }

    public function printOrder(Order $order)
    {
        $this->checkAdmin();

        $order->load(['service', 'user']);

        return view('admin.orders.print', compact('order'));
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

        // Apply provided updates. Admin can set status directly.
        $order->fill($request->only(['status', 'weight', 'price', 'notes']));

        // If weight provided and service has a price, optionally compute price when appropriate
        if ($request->filled('weight') && $order->service && $order->service->price_per_kg) {
            $w = (float) $request->input('weight');
            $order->price = $request->input('price', $w * (float) $order->service->price_per_kg);
        }

        $order->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'status' => $order->status, 'status_display' => $order->status_display]);
        }

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

    public function uploadViewProof(Request $request, Order $order)
    {
        $this->checkAdmin();

        $request->validate([
            'view_proof' => 'required|image|mimes:jpg,jpeg,png,gif|max:4096',
            'weight' => 'nullable|numeric|min:0',
        ]);

        if ($request->hasFile('view_proof')) {
            $file = $request->file('view_proof');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('scale_proofs', $filename, 'public');

            $order->view_proof = $filename;
        }

        if ($request->filled('weight')) {
            $order->weight = $request->input('weight');
            // If weight provided, mark as picked_and_weighed
            $order->status = 'picked_and_weighed';
            // Optionally compute price if service price exists
            if ($order->service && $order->service->price_per_kg) {
                $order->price = $order->weight * $order->service->price_per_kg;
            }
        }

        $order->save();

        return back()->with('success', 'Bukti timbangan berhasil diunggah.');
    }

    public function createManualOrder()
    {
        $this->checkAdmin();
        // return services in creation order so newest services appear last
        $services = Service::where('is_active', true)->orderBy('id')->get();
        return view('admin.orders.create-manual', compact('services'));
    }

    public function storeManualOrder(Request $request)
    {
        $this->checkAdmin();
        $request->validate([
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
            'pickup_method' => 'required|in:pickup,delivery',
            'notes' => 'nullable|string',
            'items_description' => 'nullable|string',
        ]);

        $ids = $request->input('service_ids', []);
        $firstServiceId = count($ids) ? $ids[0] : null;

        $order = Order::create([
            'order_code' => Order::generateOrderCode(),
            'service_id' => $firstServiceId,
            'service_ids' => json_encode($ids),
            'items_description' => $request->input('items_description'),
            'order_type' => 'manual',
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'pickup_method' => $request->pickup_method,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.orders')->with('success', 'Manual order created successfully! Order code: ' . $order->order_code);
    }

    public function destroyOrder(Order $order)
    {
        $this->checkAdmin();
        $order->delete();

        return redirect()->route('admin.orders')->with('success', 'Order deleted successfully!');
    }
}
