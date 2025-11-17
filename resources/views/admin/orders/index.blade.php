@extends('layouts.admin')

@section('title', 'Kelola Pesanan')
@section('page-title', 'Kelola Pesanan')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold text-secondary mb-0">
            <i class=""></i>
        </h2>
        <a href="{{ route('admin.orders.create-manual') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Tambah pesanan
        </a>
    </div>

    <!-- Filter Card
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-purple-soft text-purple fw-semibold rounded-top-4">
            <i class="bi bi-funnel me-2"></i>Filter Pesanan
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.orders') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label for="search" class="form-label small text-muted">Kode Pesanan</label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}" placeholder="Cari kode...">
                    </div>
                    <div class="col-md-3">
                        <label for="status" class="form-label small text-muted">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua</option>
                            <option value="waiting_for_pickup" {{ request('status')==='waiting_for_pickup'?'selected':'' }}>Menunggu Penjemputan</option>
                            <option value="picked_and_weighed" {{ request('status')==='picked_and_weighed'?'selected':'' }}>Sudah Ditimbang</option>
                            <option value="waiting_for_payment" {{ request('status')==='waiting_for_payment'?'selected':'' }}>Menunggu Pembayaran</option>
                            <option value="processed" {{ request('status')==='processed'?'selected':'' }}>Diproses</option>
                            <option value="completed" {{ request('status')==='completed'?'selected':'' }}>Selesai</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="order_type" class="form-label small text-muted">Jenis Pesanan</label>
                        <select class="form-select" id="order_type" name="order_type">
                            <option value="">Semua Jenis</option>
                            <option value="login" {{ request('order_type')==='login'?'selected':'' }}>Login</option>
                            <option value="manual" {{ request('order_type')==='manual'?'selected':'' }}>Manual</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex">
                        <button type="submit" class="btn btn-purple-soft me-2 flex-fill">
                            <i class="bi bi-x-circle me-1"></i>Reset
                            <i class="bi bi-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary flex-fill">
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div> -->

    <!-- Table Card -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            @if($orders->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-muted small text-uppercase">
                            <th>Kode</th>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Status</th>
                            <th>Jenis</th>
                            <th>Berat / Harga</th>
                            <th>Tanggal</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $order->order_code }}</td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $order->customer_name }}</div>
                                <div class="text-muted small">{{ $order->customer_phone }}</div>
                            </td>
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

                            <!-- Jenis -->
                            <td>
                                <span class="badge rounded-pill {{ $order->order_type==='login'?'bg-light text-primary border':'bg-light text-secondary border' }}">
                                    {{ ucfirst($order->order_type) }}
                                </span>
                            </td>

                            <!-- Berat / Harga -->
                            <td>
                                @if($order->weight && $order->price)
                                    <div class="text-dark">{{ $order->weight }} kg</div>
                                    <div class="text-success fw-semibold small">Rp {{ number_format($order->price, 0, ',', '.') }}</div>
                                @else
                                    <span class="text-muted small">Belum ditentukan</span>
                                @endif
                            </td>

                            <td class="text-muted small">{{ $order->created_at->format('d M Y') }}</td>

                            <!-- Aksi -->
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-primary btn-sm" title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($order->status === 'waiting_for_admin_verification' && $order->payment_proof)
                                        <form action="{{ route('admin.orders.verify-payment', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-success btn-sm" title="Verifikasi Pembayaran"
                                                    onclick="return confirm('Verifikasi pembayaran ini?')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="d-inline" onsubmit="return confirm('Anda yakin ingin menghapus pesanan ini? Aksi ini tidak dapat dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $orders->links() }}
            </div>

            @else
            <div class="text-center py-5">
                <i class="bi bi-bag-x fs-1 text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada pesanan</h5>
                <p class="text-muted small">Silakan buat pesanan baru atau ubah filter pencarian.</p>
                <a href="{{ route('admin.orders.create-manual') }}" class="btn btn-purple-soft px-4 py-2">
                    <i class="bi bi-plus-circle me-1"></i>Buat Pesanan 
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    
</style>
@endsection
