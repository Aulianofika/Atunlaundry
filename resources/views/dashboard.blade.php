@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="display-6 fw-bold">
                    <i class="fas fa-tachometer-alt me-2"></i>Welcome back, {{ Auth::user()->name }}!
                </h2>
                <a href="{{ route('orders.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>New Order
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-tshirt fa-2x text-primary mb-3"></i>
                    <h5 class="card-title">Total Orders</h5>
                    <h3 class="text-primary">{{ $orders->total() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-clock fa-2x text-warning mb-3"></i>
                    <h5 class="card-title">Pending Orders</h5>
                    <h3 class="text-warning">{{ $orders->whereIn('status', ['waiting_for_pickup', 'picked_and_weighed', 'waiting_for_payment', 'waiting_for_admin_verification'])->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-check-circle fa-2x text-success mb-3"></i>
                    <h5 class="card-title">Completed Orders</h5>
                    <h3 class="text-success">{{ $orders->where('status', 'completed')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card text-center">
                <div class="card-body">
                    <i class="fas fa-dollar-sign fa-2x text-info mb-3"></i>
                    <h5 class="card-title">Total Spent</h5>
                    <h3 class="text-info">Rp {{ number_format($orders->where('status', 'completed')->sum('price'), 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2"></i>Recent Orders
                    </h5>
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-primary btn-sm">
                        View All Orders
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order Code</th>
                                        <th>Service</th>
                                        <th>Status</th>
                                        <th>Price</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders->take(5) as $order)
                                    <tr>
                                        <td>
                                            <strong>{{ $order->order_code }}</strong>
                                        </td>
                                        <td>{{ $order->service->name }}</td>
                                        <td>
                                            <span class="badge status-{{ str_replace('_', '-', $order->status) }}">
                                                {{ $order->status_display }}
                                            </span>
                                        </td>
                                        @if($order->price)
                                                Rp {{ number_format($order->price, 0, ',', '.') }}
                                        <td>
                                            @else
                                                <span class="text-muted">TBD</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-tshirt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No orders yet</h5>
                            <p class="text-muted">Create your first order to get started!</p>
                            <a href="{{ route('orders.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus me-2"></i>Create Order
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
