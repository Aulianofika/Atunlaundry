@extends('layouts.app')

@section('title', 'Detail Pesanan - ' . $order->order_code)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="display-6 fw-bold">
                    <i class="fas fa-receipt me-2"></i>Detail Pesanan
                </h2>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Pesanan
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>Informasi Pesanan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Detail Pesanan</h6>
                            <p><strong>Kode Pesanan:</strong> {{ $order->order_code }}</p>
                            <p><strong>Layanan:</strong> {{ $order->service->name ?? 'Layanan tidak tersedia' }}</p>
                            <p><strong>Metode Penjemputan:</strong> {{ ucfirst($order->pickup_method) }}</p>
                            <p><strong>Status:</strong> 
                                <span class="badge status-{{ str_replace('_', '-', $order->status) }} fs-6">
                                    {{ $order->status_display }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Informasi Pelanggan</h6>
                            <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
                            <p><strong>Telepon:</strong> {{ $order->customer_phone }}</p>
                            <p><strong>Alamat:</strong> {{ $order->customer_address }}</p>
                            <p><strong>Tanggal Pesanan:</strong>
                                <span class="order-date" data-datetime="{{ $order->created_at->toIsoString() }}">{{ $order->created_at->format('M d, Y H:i:s') }}</span>
                            </p>
                            @if($order->estimated_completion)
                                <p><strong>Est. Completion:</strong> 
                                    {{ $order->estimated_completion->format('M d, Y H:i') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    @if($order->notes)
                        <div class="mt-4">
                                <h6 class="fw-bold">Instruksi Khusus</h6>
                                <p class="text-muted">{{ $order->notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Information -->
            @if($order->weight && $order->price)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-dollar-sign me-2"></i>Informasi Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Berat:</strong> {{ $order->weight }} kg</p>
                            <p><strong>Harga per kg:</strong> Rp {{ number_format($order->service->price_per_kg ?? 0, 0, ',', '.') }}</p>
                            <p><strong>Total Harga:</strong> Rp {{ number_format($order->price, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status Pembayaran:</strong> 
                                @if($order->payment_verified)
                                    <span class="badge bg-success">Terverifikasi</span>
                                @elseif($order->payment_proof)
                                    <span class="badge bg-warning">Menunggu Verifikasi</span>
                                @else
                                    <span class="badge bg-danger">Belum Dibayar</span>
                                @endif
                            </p>
                            @if($order->payment_proof)
                                <p><strong>Bukti Pembayaran:</strong> 
                                    <button type="button" class="btn btn-outline-primary btn-sm" 
                                            data-bs-toggle="modal" data-bs-target="#paymentProofModal">
                                        <i class="fas fa-eye me-1"></i>Lihat Bukti
                                    </button>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Hasil Timbangan (admin uploaded) -->
            @if($order->view_proof || $order->weight)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-weight-hanging me-2"></i>Hasil Timbangan
                    </h5>
                </div>
                <div class="card-body">
                    @if($order->weight)
                        <p><strong>Berat (ditetapkan admin):</strong> {{ $order->weight }} kg</p>
                    @endif

                    @if($order->view_proof)
                        <p class="mb-2"><strong>Bukti Timbangan:</strong></p>
                        <button type="button" class="btn btn-outline-success rounded-pill px-4 py-2" 
                                data-bs-toggle="modal" data-bs-target="#scaleProofModal">
                            <i class="fas fa-eye me-2"></i>Lihat Bukti Timbangan
                        </button>
                    @else
                        <div class="alert alert-secondary mb-0 rounded-3">
                            Bukti timbangan belum tersedia.
                        </div>
                    @endif

                </div>
            </div>
            @endif

            <!-- Upload Payment Proof -->
            @if($order->status === 'waiting_for_payment' && $order->user_id)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-upload me-2"></i>Unggah Bukti Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Silakan unggah gambar bukti pembayaran (struk/transfer) yang jelas untuk melanjutkan proses pesanan.
                    </div>
                    
                    <form action="{{ route('orders.upload-payment', $order) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="payment_proof" class="form-label">Gambar Bukti Pembayaran</label>
                            <input type="file" class="form-control @error('payment_proof') is-invalid @enderror" 
                                id="payment_proof" name="payment_proof" accept="image/*" required>
                            @error('payment_proof')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Accepted formats: JPG, PNG, GIF. Maximum size: 2MB</div>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>Unggah Bukti
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Order Status Timeline -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-timeline me-2"></i>Order Progress
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @php
                            $statuses = [
                                'waiting_for_pickup' => ['Waiting for Pickup', 'fas fa-clock'],
                                'picked_and_weighed' => ['Picked & Weighed', 'fas fa-weight-hanging'],
                                'waiting_for_payment' => ['Waiting for Payment', 'fas fa-credit-card'],
                                'waiting_for_admin_verification' => ['Waiting for Verification', 'fas fa-user-check'],
                                'processed' => ['Processed', 'fas fa-cog'],
                                'completed' => ['Completed', 'fas fa-check-circle']
                            ];
                        @endphp
                        
                        @foreach($statuses as $status => $info)
                            <div class="timeline-item d-flex align-items-center mb-3">
                                <div class="timeline-marker me-3">
                                    @if(array_search($status, array_keys($statuses)) <= array_search($order->status, array_keys($statuses)))
                                        <i class="{{ $info[1] }} text-primary"></i>
                                    @else
                                        <i class="{{ $info[1] }} text-muted"></i>
                                    @endif
                                </div>
                                <div class="timeline-content">
                                    <h6 class="mb-0 {{ array_search($status, array_keys($statuses)) <= array_search($order->status, array_keys($statuses)) ? 'text-primary' : 'text-muted' }}">
                                        {{ $info[0] }}
                                    </h6>
                                    @if($status === $order->status)
                                        <small class="text-muted">Current status</small>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Service Information -->
            @if($order->service)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-tshirt me-2"></i>Service Details
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="fw-bold">{{ $order->service->name }}</h6>
                    <p class="text-muted">{{ $order->service->description }}</p>
                    <p><strong>Price:</strong> Rp {{ number_format($order->service->price_per_kg, 0, ',', '.') }}/{{ $order->service->unit }}</p>
                    <p><strong>Estimated Time:</strong> {{ $order->service->estimated_days }} day(s)</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.timeline-item {
    position: relative;
}

.timeline-marker {
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--light-purple);
    border-radius: 50%;
    border: 2px solid var(--primary-purple);
}

.timeline-content {
    flex: 1;
}

/* Modal Image Styles */
.proof-image-container {
    text-align: center;
    background: #f8f9fa;
    border-radius: 8px;
    padding: 16px;
}

.proof-image {
    max-width: 100%;
    max-height: 70vh;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    cursor: zoom-in;
    transition: transform 0.3s ease;
}

.proof-image.zoomed {
    cursor: zoom-out;
    transform: scale(1.5);
}

.modal-header {
    border-bottom: 1px solid #e0e0e0;
}

.modal-footer {
    border-top: 1px solid #e0e0e0;
}

/* Status Badge Styles */
.badge.status-waiting-for-pickup {
    background: linear-gradient(135deg, #FFC107, #FFB300) !important;
    color: #212529 !important;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
}

.badge.status-picked-and-weighed {
    background: linear-gradient(135deg, #2196F3, #1976D2) !important;
    color: #fff !important;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
}

.badge.status-waiting-for-payment {
    background: linear-gradient(135deg, #FF9800, #F57C00) !important;
    color: #fff !important;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
}

.badge.status-waiting-for-admin-verification {
    background: linear-gradient(135deg, #9C27B0, #7B1FA2) !important;
    color: #fff !important;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
}

.badge.status-processed {
    background: linear-gradient(135deg, #3F51B5, #303F9F) !important;
    color: #fff !important;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
}

.badge.status-completed {
    background: linear-gradient(135deg, #009688, #00796B) !important;
    color: #fff !important;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
}
</style>

<!-- Payment Proof Modal -->
@if($order->payment_proof)
<div class="modal fade" id="paymentProofModal" tabindex="-1" aria-labelledby="paymentProofModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="paymentProofModalLabel">
                    <i class="fas fa-file-invoice-dollar me-2"></i>Bukti Pembayaran
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="proof-image-container">
                    <img src="{{ asset('storage/payment_proofs/' . $order->payment_proof) }}" 
                         alt="Bukti Pembayaran" 
                         class="proof-image"
                         onclick="this.classList.toggle('zoomed')">
                </div>
                <div class="text-center mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>Klik gambar untuk zoom in/out
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ asset('storage/payment_proofs/' . $order->payment_proof) }}" 
                   target="_blank" class="btn btn-outline-primary">
                    <i class="fas fa-external-link-alt me-1"></i>Buka di Tab Baru
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Scale Proof Modal -->
@if($order->view_proof)
<div class="modal fade" id="scaleProofModal" tabindex="-1" aria-labelledby="scaleProofModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="scaleProofModalLabel">
                    <i class="fas fa-weight-hanging me-2"></i>Bukti Timbangan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="proof-image-container">
                    <img src="{{ asset('storage/scale_proofs/' . $order->view_proof) }}" 
                         alt="Bukti Timbangan" 
                         class="proof-image"
                         onclick="this.classList.toggle('zoomed')">
                </div>
                <div class="text-center mt-3">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>Klik gambar untuk zoom in/out
                    </small>
                </div>
                @if($order->weight)
                <div class="alert alert-light border mt-3 mb-0 text-center">
                    <strong>Berat Tercatat:</strong> {{ $order->weight }} kg
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <a href="{{ asset('storage/scale_proofs/' . $order->view_proof) }}" 
                   target="_blank" class="btn btn-outline-success">
                    <i class="fas fa-external-link-alt me-1"></i>Buka di Tab Baru
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function formatRelative(d) {
        const now = new Date();
        let diff = Math.floor((now - d) / 1000); // seconds
        if (diff < 0) diff = 0;
        const days = Math.floor(diff / 86400); diff %= 86400;
        const hours = Math.floor(diff / 3600); diff %= 3600;
        const minutes = Math.floor(diff / 60); const seconds = diff % 60;
        if (days > 0) return `${days}d ${String(hours).padStart(2,'0')}:${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')} ago`;
        if (hours > 0) return `${hours}h ${String(minutes).padStart(2,'0')}m ${String(seconds).padStart(2,'0')}s ago`;
        if (minutes > 0) return `${minutes}m ${String(seconds).padStart(2,'0')}s ago`;
        return `${seconds}s ago`;
    }

    function updateOrderDates() {
        document.querySelectorAll('.order-date').forEach(function(el) {
            const dt = el.getAttribute('data-datetime');
            if (!dt) return;
            const d = new Date(dt);
            if (isNaN(d)) return;
            const opts = { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
            const ts = d.toLocaleString(undefined, opts);
            const rel = formatRelative(d);
            el.innerHTML = `${ts} <small class="text-muted">(${rel})</small>`;
        });
    }
    updateOrderDates();
    setInterval(updateOrderDates, 1000);
});
</script>
@endsection
