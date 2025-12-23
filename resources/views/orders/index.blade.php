@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="display-6 fw-bold">
                    <i class="fas fa-list me-2"></i>Pesanan Saya
                </h2>
                <a href="{{ route('orders.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Pesan Baru
                </a>
            </div>
        </div>
    </div>

    @if($orders->count() > 0)
        <div class="row">
            @foreach($orders as $order)
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="fas fa-receipt me-2"></i>{{ $order->order_code }}
                        </h6>
                        <span class="badge status-{{ str_replace('_', '-', $order->status) }}">
                            {{ $order->status_display }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="fw-bold">Detail Layanan</h6>
                                <p class="mb-1"><strong>Service:</strong> {{ $order->service->name ?? 'Layanan tidak tersedia' }}</p>
                                <p class="mb-1"><strong>Pickup:</strong> {{ ucfirst($order->pickup_method) }}</p>
                                @if($order->weight)
                                    <p class="mb-1"><strong>Weight:</strong> {{ $order->weight }} kg</p>
                                @endif
                                @if($order->view_proof)
                                    <p class="mb-1">
                                        <strong>Bukti Timbangan:</strong><br>
                                        <a href="{{ asset('storage/scale_proofs/' . $order->view_proof) }}" target="_blank">
                                            <img src="{{ asset('storage/scale_proofs/' . $order->view_proof) }}" alt="Weighing Proof" class="img-thumbnail mt-1" width="150">
                                        </a>
                                    </p>
                                @endif
                                @if($order->price)
                                    <p class="mb-1"><strong>Price:</strong> Rp {{ number_format($order->price, 0, ',', '.') }}</p>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <h6 class="fw-bold">Info Pesanan</h6>
                                <p class="mb-1"><strong>Pelanggan:</strong> {{ $order->customer_name }}</p>
                                <p class="mb-1"><strong>Telepon:</strong> {{ $order->customer_phone }}</p>
                                <p class="mb-1"><strong>Tanggal:</strong> <span class="order-date" data-datetime="{{ $order->created_at->toIsoString() }}">{{ $order->created_at->format('M d, Y H:i') }}</span></p>
                                @if($order->estimated_completion)
                                    <p class="mb-1"><strong>Estimasi Selesai:</strong> 
                                        {{ $order->estimated_completion->format('M d, Y H:i') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        @if($order->notes)
                            <div class="mt-3">
                                <h6 class="fw-bold">Catatan</h6>
                                <p class="text-muted">{{ $order->notes }}</p>
                            </div>
                        @endif

                        @if($order->status === 'waiting_for_payment' && $order->user_id)
                            <div class="mt-3">
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Silakan unggah bukti pembayaran untuk melanjutkan pemrosesan pesanan.
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Lihat Detail
                            </a>
                            @if($order->status === 'waiting_for_payment' && $order->user_id)
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#uploadModal{{ $order->id }}">
                                    <i class="fas fa-upload me-1"></i>Unggah Pembayaran
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Bukti Pembayaran Modal -->
            @if($order->status === 'waiting_for_payment' && $order->user_id)
            <div class="modal fade" id="uploadModal{{ $order->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Unggah Bukti Pembayaran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ route('orders.upload-payment', $order) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="payment_proof" class="form-label">Gambar Bukti Pembayaran</label>
                                    <input type="file" class="form-control" id="payment_proof" name="payment_proof" 
                                           accept="image/*" required>
                                    <div class="form-text">Please upload a clear image of your receipt or transfer proof.</div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Unggah Bukti Pembayaran</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
        <script>
            (function(){
                function formatOrderDates(){
                    document.querySelectorAll('.order-date').forEach(function(el){
                        var iso = el.getAttribute('data-datetime');
                        if(!iso) return;
                        var d = new Date(iso);
                        if(isNaN(d)) return;
                        var datePart = d.toLocaleString('en-US', { month: 'short', day: '2-digit', year: 'numeric' });
                        var timePart = d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                        el.textContent = datePart + ' ' + timePart;
                    });
                }
                if(document.readyState === 'loading'){
                    document.addEventListener('DOMContentLoaded', formatOrderDates);
                } else {
                    formatOrderDates();
                }
            })();
        </script>
    @else
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-tshirt fa-4x text-muted mb-4"></i>
                        <h4 class="text-muted">Belum ada pesanan</h4>
                        <p class="text-muted mb-4">Buat pesanan pertama untuk memulai layanan laundry kami!</p>
                        <a href="{{ route('orders.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus me-2"></i>Buat Pesanan Pertama
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
