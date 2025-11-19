@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')

<!-- Statistik -->
<div class="row g-4 mb-5">
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card gradient-purple">
            <div class="icon-circle">
                <i class="fas fa-tshirt"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">{{ $stats['total_orders'] }}</h4>
                <p class="text-muted mb-0 small">Total Pesanan</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card gradient-yellow">
            <div class="icon-circle">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">{{ $stats['pending_orders'] }}</h4>
                <p class="text-muted mb-0 small">Pesanan Pending</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card gradient-green">
            <div class="icon-circle">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">{{ $stats['completed_orders'] }}</h4>
                <p class="text-muted mb-0 small">Pesanan Selesai</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="dashboard-card gradient-blue">
            <div class="icon-circle">
                <i class="fas fa-wallet"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h4>
                <p class="text-muted mb-0 small">Total Pendapatan</p>
            </div>
        </div>
    </div>
</div>

<!-- Pesanan Terbaru -->
<div class="card border-0 shadow-sm rounded-4 mb-5">
    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
        <h5 class="fw-semibold mb-0 text-purple">
            Pesanan Terbaru
        </h5>
        <a href="{{ route('admin.orders') }}" class="btn btn-purple-soft btn-sm">
            <i class="fas fa-eye me-1"></i>Lihat Semua
        </a>
    </div>
    <div class="card-body p-0">
        @if($stats['recent_orders']->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th>Kode</th>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Status</th>
                            <th>Tipe</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats['recent_orders'] as $order)
                        <tr>
                            <td class="fw-semibold">{{ $order->order_code }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->service->name }}</td>
                            <td>
                                @php
                                    $statusClass = match($order->status) {
                                        'pending' => 'status-badge-pending',
                                        'in_progress' => 'status-badge-progress',
                                        'completed' => 'status-badge-completed',
                                        'cancelled' => 'status-badge-cancelled',
                                        'waiting_for_admin_verification' => 'status-badge-verify',
                                        default => 'status-badge-default'
                                    };
                                @endphp
                                <span class="badge {{ $statusClass }}">
                                    {{ $order->status_display }}
                                </span>
                            </td>
                            <td>
                                <span class="badge rounded-pill {{ $order->order_type === 'login' ? 'bg-light text-primary border' : 'bg-light text-secondary border' }}">
                                    {{ ucfirst($order->order_type) }}
                                </span>
                            </td>
                            <td class="text-muted small">{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.orders') }}?search={{ $order->order_code }}" class="btn btn-light border btn-sm text-purple">
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
                <h6 class="text-muted">Belum ada pesanan</h6>
                <p class="text-muted small">Pesanan akan muncul di sini setelah pelanggan membuat pesanan.</p>
            </div>
        @endif
    </div>
</div>

<!-- Daftar Pelanggan -->
<div class="card border-0 shadow-sm rounded-4 mb-5">
    <div class="card-header bg-white border-0">
        <h5 class="fw-semibold mb-0 text-purple">
            Daftar Pelanggan
        </h5>
    </div>
    <div class="card-body p-0">
        @if($customers->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th>Nama</th>
                            <th>Kontak</th>
                            <th>Tipe</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            <td class="fw-semibold">{{ $customer->name }}</td>
                            <td>{{ $customer->contact }}</td>
                            <td>
                                <span class="badge rounded-pill {{ $customer->type === 'Online' ? 'bg-light text-primary border' : 'bg-light text-secondary border' }}">
                                    {{ $customer->type }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h6 class="text-muted">Belum ada pelanggan</h6>
                <p class="text-muted small">Data pelanggan akan muncul di sini.</p>
            </div>
        @endif
    </div>
</div>

<!-- Aksi Cepat -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-0">
        <h5 class="fw-semibold mb-0 text-purple">
            
        </h5>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <a href="{{ route('admin.orders.create-manual') }}" class="btn btn-purple-soft w-100">
                    <i class="fas fa-plus me-2"></i>Buat Pesanan Manual
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.orders') }}" class="btn btn-outline-success w-100">
                    <i class="fas fa-list me-2"></i>Kelola Pesanan
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.orders') }}?status=pending" class="btn btn-outline-warning w-100">
                    <i class="fas fa-clock me-2"></i>Pesanan Pending
                </a>
            </div>
            <div class="col-md-3">
                <a href="{{ route('admin.orders') }}?status=waiting_for_admin_verification" class="btn btn-outline-info w-100">
                    <i class="fas fa-user-check me-2"></i>Verifikasi Pembayaran
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* === DASHBOARD CARD === */
    .dashboard-card {
        display: flex;
        align-items: center;
        gap: 1rem;
        background: #fff;
        border-radius: 16px;
        padding: 1.2rem 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
    }
    .dashboard-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    }
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: #fff;
        box-shadow: inset 0 0 10px rgba(255,255,255,0.3);
    }

    /* Gradient background */
    .gradient-purple { background: linear-gradient(135deg, #9c27b0, #ce93d8); color: #fff; }
    .gradient-yellow { background: linear-gradient(135deg, #ffb300, #ffe082); color: #fff; }
    .gradient-green { background: linear-gradient(135deg, #43a047, #a5d6a7); color: #fff; }
    .gradient-blue { background: linear-gradient(135deg, #1e88e5, #90caf9); color: #fff; }

    /* Tombol lembut */
    .btn-purple-soft {
        background-color: #ede7f6;
        color: #6a1b9a;
        border: 1px solid #d1c4e9;
        transition: 0.3s ease;
    }
    .btn-purple-soft:hover {
        background-color: #d1c4e9;
        color: #4a148c;
    }

    .text-purple { color: #6a1b9a !important; }

    /* === STATUS BADGE === */
    .badge {
        border-radius: 30px;
        padding: 6px 14px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-badge-pending {
        background: #fff8e1;
        color: #f57c00;
        border: 1px solid #ffe0b2;
    }
    .status-badge-progress {
        background: #e3f2fd;
        color: #1565c0;
        border: 1px solid #bbdefb;
    }
    .status-badge-completed {
        background: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #c8e6c9;
    }
    .status-badge-cancelled {
        background: #ffebee;
        color: #c62828;
        border: 1px solid #ffcdd2;
    }
    .status-badge-verify {
        background: #f3e5f5;
        color: #6a1b9a;
        border: 1px solid #e1bee7;
    }
    .status-badge-default {
        background: #f5f5f5;
        color: #757575;
        border: 1px solid #e0e0e0;
    }
</style>

@endsection
