@extends('layouts.admin')

@section('title', 'Detail Pesanan - ' . $order->order_code)
@section('page-title', 'Kelola Pesanan')

@section('content')
<div class="container-fluid mt-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-semibold">Detail Pesanan #{{ $order->order_code }}</h3>
        <div>
            <a href="{{ route('admin.orders') }}" class="btn btn-cancel me-2">Kembali</a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-body"> 
                    <h5 class="fw-semibold">Informasi Pelanggan</h5>
                    <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Alamat:</strong> {{ $order->customer_address }}</p>
                    <p><strong>Telepon:</strong> {{ $order->customer_phone }}</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold">Daftar Layanan</h5>

                    @php
                        $selectedIds = [];
                        if (!empty($order->service_ids)) {
                            $decoded = json_decode($order->service_ids, true);
                            if (is_array($decoded)) $selectedIds = $decoded;
                        } elseif (!empty($order->service_id)) {
                            $selectedIds = [$order->service_id];
                        }
                    @endphp

                    <form id="servicesForm" action="{{ route('admin.orders.update-services', $order) }}" method="POST">
                        @csrf
                        <div class="list-group">
                            @foreach($services as $s)
                                <label class="list-group-item d-flex justify-content-between align-items-center service-item border-bottom py-2 px-3" data-service-id="{{ $s->id }}" style="cursor:pointer;">
                                    <div class="d-flex align-items-center">
                                        <input class="form-check-input me-3 service-checkbox" type="checkbox" name="service_ids[]" value="{{ $s->id }}" id="service_{{ $s->id }}" {{ in_array($s->id, $selectedIds) ? 'checked' : '' }} />
                                        <div>
                                            <div class="small fw-semibold mb-0">{{ $s->name }}</div>
                                            <div class="small text-muted">Rp {{ number_format($s->price_per_kg ?? 0, 0, ',', '.') }} / {{ $s->unit ?? 'satuan' }} · {{ $s->estimated_days }} hari</div>
                                        </div>
                                    </div>

                                    <div class="text-end">
                                        <div class="small text-muted mb-0">Rp {{ number_format($s->price_per_kg ?? 0, 0, ',', '.') }}</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <div class="mt-3">
                            <label class="form-label">Deskripsi Item (opsional)</label>
                            <textarea name="items_description" class="form-control" rows="2">{{ old('items_description', $order->items_description) }}</textarea>
                        </div>

                        <div class="mt-3 d-flex gap-2">
                            <button type="submit" class="btn btn-gradient">Simpan Layanan</button>
                            <button type="button" id="resetServicesBtn" class="btn btn-outline-secondary">Reset Pilihan</button>
                        </div>
                    </form>
                </div>
            </div>

                <!-- Weigh & Upload Proof -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="fw-semibold">Informasi Timbangan & Bukti</h5>

                    <form action="{{ route('admin.orders.upload-view-proof', $order) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Berat (KG)</label>
                            <input type="number" step="0.01" name="weight" class="form-control" value="{{ old('weight', $order->weight) }}" placeholder="Contoh: 5" />
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Upload Bukti Timbangan</label>
                            <input type="file" name="view_proof" id="view_proof_input" accept="image/*" class="form-control" />
                            <div class="form-text">Format: JPG/PNG/GIF. Maks 4MB.</div>
                        </div>

                      <div id="viewPreview" class="mb-3 {{ $order->view_proof ? '' : 'd-none' }}">
                            @if($order->view_proof)
                                <div class="mb-2">Pratinjau saat ini:</div>
                                <img src="{{ asset('storage/scale_proofs/' . $order->view_proof) }}" 
                                     alt="Bukti Timbangan" 
                                     style="max-width:200px; cursor:pointer;" 
                                     class="img-thumbnail"
                                     data-bs-toggle="modal" 
                                     data-bs-target="#adminScaleProofModal"
                                     title="Klik untuk memperbesar">
                                <div class="mt-2">
                                    <button type="button" class="btn btn-outline-primary btn-sm me-2" 
                                            data-bs-toggle="modal" data-bs-target="#adminScaleProofModal">
                                        <i class="fas fa-search-plus me-1"></i>Lihat Detail
                                    </button>
                                    <button type="button" id="changeViewBtn" class="btn btn-outline-secondary btn-sm">Ganti Gambar</button>
                                </div>
                            @endif
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-gradient">Upload Bukti Timbangan</button>
                            <a href="{{ route('admin.orders') }}" class="btn btn-cancel">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
            
                <!-- Payment Proof (from user) -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="fw-semibold">Bukti Pembayaran Pengguna</h5>

                        @if($order->payment_proof)
                            <div class="mb-3">
                                <p class="mb-1"><strong>Berkas:</strong></p>
                                <img src="{{ asset('storage/payment_proofs/' . $order->payment_proof) }}" 
                                     alt="Bukti Pembayaran" 
                                     class="img-thumbnail" 
                                     style="max-width:200px; cursor:pointer;" 
                                     data-bs-toggle="modal" 
                                     data-bs-target="#adminPaymentProofModal"
                                     title="Klik untuk memperbesar" />
                            </div>

                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-outline-primary btn-sm" 
                                        data-bs-toggle="modal" data-bs-target="#adminPaymentProofModal">
                                    <i class="fas fa-search-plus me-1"></i>Lihat Detail
                                </button>

                                @if(!$order->payment_verified)
                                    <form action="{{ route('admin.orders.verify-payment', $order) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Verifikasi pembayaran ini?')">
                                            <i class="fas fa-check me-1"></i>Verifikasi Pembayaran
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-success align-self-center">Sudah Diverifikasi</span>
                                @endif
                            </div>
                        @else
                            <div class="alert alert-secondary mb-0">Belum ada bukti pembayaran yang diunggah oleh pengguna.</div>
                        @endif
                    </div>
                </div>

            <div class="d-flex gap-2 mb-4 align-items-center">
                <div>
                    <label class="form-label small text-muted mb-1">Ubah Status</label>
                    <select id="statusSelect" class="form-select">
                        <option value="waiting_for_pickup" {{ $order->status==='waiting_for_pickup' ? 'selected' : '' }}>Menunggu Penjemputan</option>
                        <option value="picked_and_weighed" {{ $order->status==='picked_and_weighed' ? 'selected' : '' }}>Sudah Ditimbang</option>
                        <option value="waiting_for_payment" {{ $order->status==='waiting_for_payment' ? 'selected' : '' }}>Menunggu Pembayaran</option>
                        <option value="waiting_for_admin_verification" {{ $order->status==='waiting_for_admin_verification' ? 'selected' : '' }}>Menunggu Verifikasi Admin</option>
                        <option value="processed" {{ $order->status==='processed' ? 'selected' : '' }}>Diproses</option>
                        <option value="completed" {{ $order->status==='completed' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                <button id="updateStatusBtn" class="btn btn-gradient mt-4">Perbarui Status</button>

                <a href="{{ route('admin.orders.print', $order) }}" target="_blank" class="btn btn-outline-secondary mt-4">Cetak Struk</a>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                    <div class="card-body">
                    <h6 class="fw-semibold">Ringkasan</h6>
                    <p class="mb-1"><strong>Kode Pesanan:</strong> {{ $order->order_code }}</p>
                    <p class="mb-1"><strong>Metode Pickup:</strong> {{ ucfirst($order->pickup_method) }}</p>
                    <p class="mb-1"><strong>Waktu Pesanan:</strong> <span class="order-date" data-datetime="{{ $order->created_at->toIsoString() }}">{{ $order->created_at->format('d M Y H:i:s') }}</span></p>

                    <hr>

                    <h6 class="fw-semibold">Informasi Service</h6>
                    <p class="mb-1"><strong>{{ $order->service->name ?? 'Layanan tidak tersedia' }}</strong></p>
                    <p class="text-muted mb-0">Rp {{ number_format($order->service->price_per_kg ?? 0, 0, ',', '.') }} / satuan</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Admin Scale Proof Modal -->
@if($order->view_proof)
<div class="modal fade" id="adminScaleProofModal" tabindex="-1" aria-labelledby="adminScaleProofModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #43a047, #2e7d32);">
                <h5 class="modal-title text-white" id="adminScaleProofModalLabel">
                    <i class="fas fa-weight-hanging me-2"></i>Bukti Timbangan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center" style="background: #f8f9fa;">
                <img src="{{ asset('storage/scale_proofs/' . $order->view_proof) }}" 
                     alt="Bukti Timbangan" 
                     class="img-fluid rounded shadow"
                     style="max-height: 70vh; cursor: zoom-in;"
                     onclick="this.classList.toggle('zoomed-img')">
                <div class="mt-3">
                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Klik gambar untuk zoom</small>
                </div>
                @if($order->weight)
                <div class="alert alert-light border mt-3 mb-0">
                    <strong>Berat Tercatat:</strong> {{ $order->weight }} kg
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <a href="{{ asset('storage/scale_proofs/' . $order->view_proof) }}" target="_blank" class="btn btn-outline-success">
                    <i class="fas fa-external-link-alt me-1"></i>Buka Tab Baru
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Admin Payment Proof Modal -->
@if($order->payment_proof)
<div class="modal fade" id="adminPaymentProofModal" tabindex="-1" aria-labelledby="adminPaymentProofModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #1976d2, #1565c0);">
                <h5 class="modal-title text-white" id="adminPaymentProofModalLabel">
                    <i class="fas fa-file-invoice-dollar me-2"></i>Bukti Pembayaran
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center" style="background: #f8f9fa;">
                <img src="{{ asset('storage/payment_proofs/' . $order->payment_proof) }}" 
                     alt="Bukti Pembayaran" 
                     class="img-fluid rounded shadow"
                     style="max-height: 70vh; cursor: zoom-in;"
                     onclick="this.classList.toggle('zoomed-img')">
                <div class="mt-3">
                    <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Klik gambar untuk zoom</small>
                </div>
                <div class="alert alert-light border mt-3 mb-0">
                    <strong>Status:</strong> 
                    @if($order->payment_verified)
                        <span class="badge bg-success">Terverifikasi</span>
                    @else
                        <span class="badge bg-warning">Menunggu Verifikasi</span>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                @if(!$order->payment_verified)
                <form action="{{ route('admin.orders.verify-payment', $order) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success" onclick="return confirm('Verifikasi pembayaran ini?')">
                        <i class="fas fa-check me-1"></i>Verifikasi
                    </button>
                </form>
                @endif
                <a href="{{ asset('storage/payment_proofs/' . $order->payment_proof) }}" target="_blank" class="btn btn-outline-primary">
                    <i class="fas fa-external-link-alt me-1"></i>Buka Tab Baru
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endif

<style>
.zoomed-img {
    transform: scale(1.5);
    cursor: zoom-out !important;
    transition: transform 0.3s ease;
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Script for view proof image preview
    const input = document.getElementById('view_proof_input');
    const preview = document.getElementById('viewPreview');
    const changeViewBtn = document.getElementById('changeViewBtn');

    if (input) {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const img = document.createElement('img');
            img.className = 'img-thumbnail';
            img.style.maxWidth = '200px';
            img.alt = 'Pratinjau';

            const reader = new FileReader();
            reader.onload = function(ev) {
                preview.innerHTML = '<div class="mb-2">Pratinjau baru:</div>';
                img.src = ev.target.result;
                preview.appendChild(img);
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
    }

    if (changeViewBtn) {
        changeViewBtn.addEventListener('click', function() {
            input.click();
        });
    }

    // Script for updating order status
    const updateBtn = document.getElementById('updateStatusBtn');
    const statusSelect = document.getElementById('statusSelect');
    const statusLabel = document.getElementById('currentStatusLabel');

    if (updateBtn && statusSelect) {
        updateBtn.addEventListener('click', function() {
            const newStatus = statusSelect.value;
            updateBtn.disabled = true;
            updateBtn.textContent = 'Menyimpan...';
            
            const url = '{{ route("admin.orders.update-status", $order) }}';
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const fd = new FormData();
            fd.append('_token', token);
            fd.append('status', newStatus);

            fetch(url, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: fd
            }).then(async (response) => {
                const data = await response.json();
                if (response.ok && data.success) {
                    statusLabel.textContent = data.status_display || newStatus;
                    alert('Status diperbarui: ' + (data.status_display || newStatus));
                } else {
                    // Handle validation errors or other server errors
                    const errorMessage = data.message || (data.errors ? Object.values(data.errors).join(', ') : 'Terjadi kesalahan saat mengupdate status.');
                    alert('Error: ' + errorMessage);
                }
            // }).catch(err => {
            //     console.error('Fetch Error:', err);
            //     alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
            }).finally(() => {
                updateBtn.disabled = false;
                updateBtn.textContent = 'Perbarui Status';
            });
        });
    }

    // Service selection interactions
    const serviceItems = document.querySelectorAll('.service-item');
    const resetBtn = document.getElementById('resetServicesBtn');
    const servicesForm = document.getElementById('servicesForm');

    serviceItems.forEach(item => {
        item.addEventListener('click', function(e) {
            // If click was on the checkbox, let it handle itself
            if (e.target.classList.contains('service-checkbox')) return;
            const checkbox = this.querySelector('.service-checkbox');
            if (checkbox) checkbox.checked = !checkbox.checked;
        });
    });

    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            document.querySelectorAll('.service-checkbox').forEach(cb => cb.checked = false);
        });
    }

    if (servicesForm) {
        servicesForm.addEventListener('submit', function() {
            // disable submit to avoid double posts
            servicesForm.querySelector('button[type="submit"]').disabled = true;
        });
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function formatRelative(d) {
        const now = new Date();
        let diff = Math.floor((now - d) / 1000);
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