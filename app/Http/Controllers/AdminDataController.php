<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Service;
use App\Models\Promotion;
use App\Models\Expense;
use Illuminate\Support\Facades\Auth;

class AdminDataController extends Controller
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

    public function index()
    {
        $this->checkAdmin();
        
        // Get all admin data
        $admin = Auth::user();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', 'completed')->sum('price');
        $totalExpenses = Expense::sum('amount');
        $netProfit = $totalRevenue - $totalExpenses;
        
        // Recent orders
        $recentOrders = Order::with(['user', 'service'])
            ->latest()
            ->take(10)
            ->get();
            
        // Monthly revenue data
        $monthlyRevenue = Order::where('status', 'completed')
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('price');
            
        // Monthly expenses
        $monthlyExpenses = Expense::whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->sum('amount');
            
        // Order status counts
        $orderStatusCounts = [
            'waiting_for_pickup' => Order::where('status', 'waiting_for_pickup')->count(),
            'picked_and_weighed' => Order::where('status', 'picked_and_weighed')->count(),
            'waiting_for_payment' => Order::where('status', 'waiting_for_payment')->count(),
            'waiting_for_admin_verification' => Order::where('status', 'waiting_for_admin_verification')->count(),
            'processed' => Order::where('status', 'processed')->count(),
            'completed' => Order::where('status', 'completed')->count(),
        ];
        
        // Service performance
        $servicePerformance = Service::withCount('orders')
            ->with(['orders' => function($query) {
                $query->where('status', 'completed');
            }])
            ->get()
            ->map(function($service) {
                $completedOrders = $service->orders->count();
                $revenue = $service->orders->sum('price');
                return [
                    'name' => $service->name,
                    'total_orders' => $service->orders_count,
                    'completed_orders' => $completedOrders,
                    'revenue' => $revenue,
                ];
            });

        return view('admin.data.index', compact(
            'admin',
            'totalOrders',
            'totalRevenue',
            'totalExpenses',
            'netProfit',
            'recentOrders',
            'monthlyRevenue',
            'monthlyExpenses',
            'servicePerformance'
        ));
    }

    public function orders()
    {
        $this->checkAdmin();
        
        $orders = Order::with(['user', 'service'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.data.orders', compact('orders'));
    }

    public function expenses()
    {
        $this->checkAdmin();
        
        $expenses = Expense::orderBy('expense_date', 'desc')
            ->paginate(20);
            
        $totalExpenses = Expense::sum('amount');
        $monthlyExpenses = Expense::whereYear('expense_date', now()->year)
            ->whereMonth('expense_date', now()->month)
            ->sum('amount');

        return view('admin.data.expenses', compact('expenses', 'totalExpenses', 'monthlyExpenses'));
    }

    public function promotions()
    {
        $this->checkAdmin();
        
        $promotions = Promotion::orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.promo.index', compact('promotions'));
    }

    public function reports()
    {
        $this->checkAdmin();
        
        // Revenue report
        $revenueData = Order::where('status', 'completed')
            ->selectRaw('DATE(created_at) as date, SUM(price) as revenue')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Expense report
        $expenseData = Expense::selectRaw('DATE(expense_date) as date, SUM(amount) as expenses')
            ->where('expense_date', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Service performance
        $serviceData = Service::withCount(['orders' => function($query) {
            $query->where('status', 'completed');
        }])
        ->with(['orders' => function($query) {
            $query->where('status', 'completed');
        }])
        ->get()
        ->map(function($service) {
            return [
                'name' => $service->name,
                'orders' => $service->orders_count,
                'revenue' => $service->orders->sum('price'),
            ];
        });

        return view('admin.data.reports', compact('revenueData', 'expenseData', 'serviceData'));
    }
    
}