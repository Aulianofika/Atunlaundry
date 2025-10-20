@extends('layouts.admin')

@section('title', 'Data Admin - Atun')
@section('page-title', 'Data Admin - Atun')

@section('content')

 <!-- Business Statistics -->
<div class="row g-4 mb-4">
    <!-- Total Orders -->
    <div class="col-lg-3 col-md-6">
        <div class="card bg-white shadow-sm rounded-4 border-0 text-center p-4 hover:shadow-md transition">
            <div class="d-flex justify-content-center align-items-center bg-pink-100 text-pink-600 rounded-circle mb-3" style="width:55px;height:55px;">
                <i class="bi bi-basket-fill fs-4"></i>
            </div>
            <h6 class="text-secondary fw-semibold mb-1">Total Pesanan</h6>
            <h4 class="fw-bold text-pink-600 mb-0">{{ number_format($totalOrders) }}</h4>
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="col-lg-3 col-md-6">
        <div class="card bg-white shadow-sm rounded-4 border-0 text-center p-4 hover:shadow-md transition">
            <div class="d-flex justify-content-center align-items-center bg-green-100 text-green-600 rounded-circle mb-3" style="width:55px;height:55px;">
                <i class="bi bi-cash-stack fs-4"></i>
            </div>
            <h6 class="text-secondary fw-semibold mb-1">Total Pendapatan</h6>
            <h4 class="fw-bold text-green-600 mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
        </div>
    </div>

    <!-- Total Expenses -->
    <div class="col-lg-3 col-md-6">
        <div class="card bg-white shadow-sm rounded-4 border-0 text-center p-4 hover:shadow-md transition">
            <div class="d-flex justify-content-center align-items-center bg-yellow-100 text-yellow-600 rounded-circle mb-3" style="width:55px;height:55px;">
                <i class="bi bi-receipt fs-4"></i>
            </div>
            <h6 class="text-secondary fw-semibold mb-1">Total Pengeluaran</h6>
            <h4 class="fw-bold text-yellow-600 mb-0">Rp {{ number_format($totalExpenses, 0, ',', '.') }}</h4>
        </div>
    </div>

    <!-- Net Profit -->
    <div class="col-lg-3 col-md-6">
        <div class="card bg-white shadow-sm rounded-4 border-0 text-center p-4 hover:shadow-md transition">
            <div class="d-flex justify-content-center align-items-center bg-sky-100 text-sky-600 rounded-circle mb-3" style="width:55px;height:55px;">
                <i class="bi bi-graph-up-arrow fs-4"></i>
            </div>
            <h6 class="text-secondary fw-semibold mb-1">Laba Bersih</h6>
            <h4 class="fw-bold text-sky-600 mb-0">Rp {{ number_format($netProfit, 0, ',', '.') }}</h4>
        </div>
    </div>
</div>

<!-- Monthly Overview -->
<div class="row g-4 mb-5">
    <div class="col-lg-6">
        <div class="card bg-white border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex align-items-center mb-3">
                <i class="bi bi-calendar-event text-pink-600 fs-5 me-2"></i>
                <h5 class="fw-bold text-secondary mb-0">Ringkasan Bulan Ini</h5>
            </div>
            <div class="row text-center">
                <div class="col-6">
                    <div class="p-3 rounded-4 bg-green-50">
                        <i class="bi bi-wallet2 text-green-600 fs-5 mb-2"></i>
                        <h4 class="fw-semibold text-green-600 mb-0">
                            Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}
                        </h4>
                        <p class="text-muted small mb-0">Pendapatan</p>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-3 rounded-4 bg-yellow-50">
                        <i class="bi bi-coin text-yellow-600 fs-5 mb-2"></i>
                        <h4 class="fw-semibold text-yellow-600 mb-0">
                            Rp {{ number_format($monthlyExpenses, 0, ',', '.') }}
                        </h4>
                        <p class="text-muted small mb-0">Pengeluaran</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Service Performance -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>layanan 
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Service Name</th>
                                    <th>Total Orders</th>
                                    <th>Completed Orders</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($servicePerformance as $service)
                                <tr>
                                    <td>{{ $service['name'] }}</td>
                                    <td>{{ $service['total_orders'] }}</td>
                                    <td>{{ $service['completed_orders'] }}</td>
                                    <td>Rp {{ number_format($service['revenue'], 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
                        <i class="fas fa-clock me-2"></i>Pesanan Terbaru
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order Code</th>
                                        <th>Customer</th>
                                        <th>Service</th>
                                        <th>Status</th>
                                        <th>Price</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td><strong>{{ $order->order_code }}</strong></td>
                                        <td>{{ $order->customer_name }}</td>
                                        <td>{{ $order->service->name }}</td>
                                        <!-- Status Soft -->
                                        <td>
                                            @php
                                                $statusMap = [
                                                    'waiting_for_pickup' => ['Menunggu Penjemputan', 'bi-truck', 'badge-light-warning'],
                                                    'picked_and_weighed' => ['Sudah Ditimbang', 'bi-scale', 'badge-light-info'],
                                                    'waiting_for_payment' => ['Menunggu Pembayaran', 'bi-wallet2', 'badge-light-secondary'],
                                                    'processed' => ['Diproses', 'bi-gear', 'badge-light-primary'],
                                                    'completed' => ['Selesai', 'bi-check-circle', 'badge-light-success'],
                                                ];
                                                $statusData = $statusMap[$order->status] ?? ['Tidak Diketahui', 'bi-question-circle', 'badge-light-secondary'];
                                            @endphp
                                            <span class="badge rounded-pill {{ $statusData[2] }} px-3 py-2">
                                                <i class="bi {{ $statusData[1] }} me-1"></i>{{ $statusData[0] }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($order->price)
                                                Rp {{ number_format($order->price, 0, ',', '.') }}
                                            @else
                                                <span class="text-muted">TBD</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-tshirt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No orders yet</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
