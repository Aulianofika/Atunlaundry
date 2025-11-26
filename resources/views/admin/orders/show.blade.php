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
                        <div class="mb-2">
                            <label for="serviceSelect" class="form-label small fw-semibold">Pilih Layanan (Ctrl/Cmd+Click untuk multi)</label>
                            <select id="serviceSelect" name="service_ids[]" class="form-select form-select-sm" multiple size="6">
                                @foreach($services as $s)
                                    <option value="{{ $s->id }}" data-price="{{ $s->price_per_kg ?? 0 }}" data-days="{{ $s->estimated_days }}" {{ in_array($s->id, $selectedIds) ? 'selected' : '' }}>
                                        {{ $s->name }} — Rp {{ number_format($s->price_per_kg ?? 0, 0, ',', '.') }} / {{ $s->unit ?? 'satuan' }} · {{ $s->estimated_days }} hari
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-2">
                            <label class="form-label">Deskripsi Item (opsional)</label>
                            <textarea name="items_description" class="form-control form-control-sm" rows="2">{{ old('items_description', $order->items_description) }}</textarea>
                        </div>

                        <div class="mt-3 d-flex gap-2">
                            <button type="submit" class="btn btn-gradient btn-sm">Simpan Layanan</button>
                            <button type="button" id="resetServicesBtn" class="btn btn-outline-secondary btn-sm">Reset Pilihan</button>
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
                                <div class="mb-2">Preview saat ini:</div>
                                <img src="{{ asset('storage/scale_proofs/' . $order->view_proof) }}" alt="View Proof" style="max-width:200px;" class="img-thumbnail">
                                <div class="mt-2">
                                    <a href="{{ asset('storage/scale_proofs/' . $order->view_proof) }}" target="_blank" class="btn btn-outline-primary btn-sm me-2">Lihat Gambar</a>
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
                                <p class="mb-1"><strong>File:</strong></p>
                                <img src="{{ asset('storage/payment_proofs/' . $order->payment_proof) }}" alt="Payment Proof" class="img-thumbnail" style="max-width:300px;" />
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ asset('storage/payment_proofs/' . $order->payment_proof) }}" target="_blank" class="btn btn-outline-primary btn-sm">Lihat Gambar</a>

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
                    <p class="mb-1"><strong>Order Code:</strong> {{ $order->order_code }}</p>
                    <p class="mb-1"><strong>Metode Pickup:</strong> {{ ucfirst($order->pickup_method) }}</p>
                    <p class="mb-1"><strong>Waktu Pesanan:</strong> {{ $order->created_at->format('d M Y H:i') }}</p>

                    <hr>

                    <h6 class="fw-semibold">Informasi Service</h6>
                    <p class="mb-1"><strong>{{ $order->service->name }}</strong></p>
                    <p class="text-muted mb-0">Rp {{ number_format($order->service->price_per_kg ?? 0, 0, ',', '.') }} / satuan</p>
                </div>
            </div>
        </div>
    </div>
</div>
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
            img.alt = 'Preview';

            const reader = new FileReader();
            reader.onload = function(ev) {
                preview.innerHTML = '<div class="mb-2">Preview baru:</div>';
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
            }).catch(err => {
                console.error('Fetch Error:', err);
                alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
            }).finally(() => {
                updateBtn.disabled = false;
                updateBtn.textContent = 'Perbarui Status';
            });
        });
    }

    // Service selection interactions (native multi-select)
    const serviceSelect = document.getElementById('serviceSelect');
    const resetBtn = document.getElementById('resetServicesBtn');
    const servicesForm = document.getElementById('servicesForm');

    if (resetBtn && serviceSelect) {
        resetBtn.addEventListener('click', function() {
            Array.from(serviceSelect.options).forEach(opt => opt.selected = false);
            // trigger change to update any UI
            serviceSelect.dispatchEvent(new Event('change'));
        });
    }

    if (servicesForm) {
        servicesForm.addEventListener('submit', function() {
            servicesForm.querySelector('button[type="submit"]').disabled = true;
        });
    }
});
</script>
@endsection