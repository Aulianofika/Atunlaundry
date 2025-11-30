@extends('layouts.admin')

@section('title', 'Buat Pesanan Manual')
@section('page-title', 'Buat Pesanan Manual')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header text-center rounded-top-4 py-4 shadow-sm" 
                    style="background: linear-gradient(135deg, #A8D8C9, color #fff);">
                    <h2 class="mb-1 fw-bold text-uppercase" style="font-size: 1.9rem; letter-spacing: 0.5px;">
                        Buat Pesanan 
                    </h2>
                    <p class="mb-0 text-light fw-semibold" style="font-size: 1.1rem; opacity: 0.9;">
                        Buat pesanan pelanggan yang datang langsung ke outlet
                    </p>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.orders.store-manual') }}">
                        @csrf

                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label fw-semibold">Pilih Jenis Layanan <span class="text-danger">*</span></label>
                                <div class="list-group service-list">
                                    @foreach($services as $service)
                                        <label class="list-group-item d-flex justify-content-between align-items-start">
                                            <div class="d-flex align-items-start">
                                                <input class="form-check-input me-2 service-checkbox" type="checkbox"
                                                       name="service_ids[]" id="service_{{ $service->id }}"
                                                       value="{{ $service->id }}"
                                                       data-price="{{ $service->price_per_kg }}"
                                                       data-days="{{ $service->estimated_days }}"
                                                       {{ (is_array(old('service_ids')) && in_array($service->id, old('service_ids'))) ? 'checked' : '' }}>
                                                <div>
                                                    <strong>{{ $service->name }}</strong>
                                                    <div class="small text-muted">Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}/kg · {{ $service->estimated_days }} hari</div>
                                                </div>
                                            </div>
                                            <div class="ms-2 text-end small text-muted">Rp {{ number_format($service->price_per_kg,0,',','.') }}</div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('service_ids')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                                @error('service_ids.*')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="pickup_method" class="form-label fw-semibold">Metode Pengambilan <span class="text-danger">*</span></label>
                                <select class="form-select shadow-sm @error('pickup_method') is-invalid @enderror" 
                                        id="pickup_method" name="pickup_method" required>
                                    <option value="">Pilih metode</option>
                                    <option value="pickup" {{ old('pickup_method') === 'pickup' ? 'selected' : '' }}>Ambil di Toko</option>
                                    <option value="delivery" {{ old('pickup_method') === 'delivery' ? 'selected' : '' }}>Antar ke Rumah</option>
                                </select>
                                @error('pickup_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div id="price-info" class="alert alert-light py-2 small d-none"></div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_name" class="form-label fw-semibold">Nama Pelanggan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control shadow-sm @error('customer_name') is-invalid @enderror" 
                                       id="customer_name" name="customer_name" 
                                       value="{{ old('customer_name') }}" placeholder="Masukkan nama pelanggan" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="customer_phone" class="form-label fw-semibold">Nomor Telepon <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control shadow-sm @error('customer_phone') is-invalid @enderror" 
                                       id="customer_phone" name="customer_phone" 
                                       value="{{ old('customer_phone') }}" placeholder="08xxxxxxxxxx" required>
                                @error('customer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="customer_address" class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                            <textarea class="form-control shadow-sm @error('customer_address') is-invalid @enderror" 
                                      id="customer_address" name="customer_address" rows="3" required
                                      placeholder="Masukkan alamat lengkap pelanggan">{{ old('customer_address') }}</textarea>
                            @error('customer_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="items_description" class="form-label fw-semibold">Deskripsi Barang (Opsional)</label>
                            <textarea class="form-control shadow-sm @error('items_description') is-invalid @enderror" 
                                      id="items_description" name="items_description" rows="2" 
                                      placeholder="Contoh: 3 kemeja, 2 celana, 5 kaos">{{ old('items_description') }}</textarea>
                            @error('items_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label fw-semibold">Catatan Khusus (Opsional)</label>
                            <textarea class="form-control shadow-sm @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Tuliskan catatan tambahan (jika ada)">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info shadow-sm">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Catatan:</strong> Pesanan ini dibuat secara manual. Pelanggan akan mendapatkan kode pesanan untuk melacak statusnya. Berat dan total harga dapat diperbarui saat pencucian selesai.
                        </div>

                        <div class="d-flex justify-content-end gap-3 mt-4">
                            <a href="{{ route('admin.orders') }}" class="btn-cancel px-4 py-2">
                                <i class="bi bi-arrow-left me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn-gradient px-4 py-2">
                                <i class="bi bi-check-circle me-2"></i>Buat Pesanan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JS untuk info harga layanan --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.service-list .service-checkbox');
    const priceInfo = document.getElementById('price-info');

    function updatePriceInfo() {
        const firstChecked = Array.from(checkboxes).find(cb => cb.checked);
        if (firstChecked) {
            const price = firstChecked.dataset.price;
            const days = firstChecked.dataset.days;
            priceInfo.classList.remove('d-none');
            priceInfo.innerHTML = `
                <i class="bi bi-cash-coin me-2 text-purple"></i>
                <strong>Harga (contoh - pertama dipilih):</strong> Rp ${parseInt(price).toLocaleString()} / kg |
                <strong>Estimasi:</strong> ${days} hari
            `;
        } else {
            priceInfo.classList.add('d-none');
            priceInfo.innerHTML = '';
        }
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updatePriceInfo));
    updatePriceInfo();
});
</script>
@endsection
