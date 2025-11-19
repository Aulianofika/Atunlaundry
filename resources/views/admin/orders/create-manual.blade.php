@extends('layouts.admin')

@section('title', 'Buat Pesanan Manual')
@section('page-title', 'Buat Pesanan Manual')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header text-center rounded-top-4 py-4 shadow-sm" 
                    style="background: linear-gradient(135deg, #A8D8C9, color: #fff;">
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
                            <div class="col-md-6 mb-3">
                                <label for="service_id" class="form-label fw-semibold">Jenis Layanan <span class="text-danger">*</span></label>
                                <select class="form-select shadow-sm @error('service_id') is-invalid @enderror" 
                                        id="service_id" name="service_id" required>
                                    <option value="">Pilih layanan</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" 
                                                data-price="{{ $service->price_per_kg }}"
                                                data-days="{{ $service->estimated_days }}"
                                                {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                            {{ $service->name }} - Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}/kg
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
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
    const serviceSelect = document.getElementById('service_id');
    const priceInfo = document.getElementById('price-info');

    serviceSelect.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        if (selected.value) {
            const price = selected.dataset.price;
            const days = selected.dataset.days;
            priceInfo.classList.remove('d-none');
            priceInfo.innerHTML = `
                <i class="bi bi-cash-coin me-2 text-purple"></i>
                <strong>Harga:</strong> Rp ${parseInt(price).toLocaleString()} / kg |
                <strong>Estimasi:</strong> ${days} hari
            `;
        } else {
            priceInfo.classList.add('d-none');
            priceInfo.innerHTML = '';
        }
    });
});
</script>
@endsection
