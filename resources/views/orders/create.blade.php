@extends('layouts.app')

@section('title', 'Create New Order')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h4 class="mb-0">
                        <i class="fas fa-plus me-2"></i>Create New Order
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('orders.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label class="form-label">Pilih Jenis Layanan <span class="text-danger">*</span></label>
                                <div class="list-group service-list">
                                    @foreach($services as $service)
                                        <label class="list-group-item d-flex justify-content-between align-items-center border-bottom py-2 px-3">
                                            <div class="d-flex align-items-center">
                                                <input class="form-check-input me-3 service-checkbox" type="checkbox"
                                                       name="service_ids[]" id="service_{{ $service->id }}"
                                                       value="{{ $service->id }}"
                                                       data-price="{{ $service->price_per_kg }}"
                                                       data-days="{{ $service->estimated_days }}"
                                                       {{ (is_array(old('service_ids')) && in_array($service->id, old('service_ids'))) ? 'checked' : '' }}>
                                                <div>
                                                    <div class="small fw-semibold mb-0">{{ $service->name }}</div>
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
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pickup_method" class="form-label">Pickup Method <span class="text-danger">*</span></label>
                                    <select class="form-select @error('pickup_method') is-invalid @enderror" 
                                            id="pickup_method" name="pickup_method" required>
                                        <option value="">Select pickup method</option>
                                        <option value="pickup" {{ old('pickup_method') === 'pickup' ? 'selected' : '' }}>Pickup at Store</option>
                                        <option value="delivery" {{ old('pickup_method') === 'delivery' ? 'selected' : '' }}>Home Delivery</option>
                                    </select>
                                    @error('pickup_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">Your Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                           id="customer_name" name="customer_name" value="{{ old('customer_name', Auth::user()->name) }}" required>
                                    @error('customer_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror" 
                                           id="customer_phone" name="customer_phone" value="{{ old('customer_phone', Auth::user()->phone) }}" required>
                                    @error('customer_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="customer_address" class="form-label">Address <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('customer_address') is-invalid @enderror" 
                                      id="customer_address" name="customer_address" rows="3" required 
                                      placeholder="Enter your complete address">{{ old('customer_address') }}</textarea>
                            @error('customer_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="items_description" class="form-label">Deskripsi Barang (Opsional)</label>
                            <textarea class="form-control @error('items_description') is-invalid @enderror" 
                                      id="items_description" name="items_description" rows="2" 
                                      placeholder="Contoh: 3 kemeja, 2 celana, 5 kaos">{{ old('items_description') }}</textarea>
                            @error('items_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Special Instructions (Optional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Any special instructions for your laundry order">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                      
                        <!-- <div class="mb-3">
                            <label for="payment_proof" class="form-label">Upload Payment Proof (Optional)</label>
                            <input type="file" class="form-control @error('payment_proof') is-invalid @enderror" id="payment_proof" name="payment_proof" accept="image/*">
                            @error('payment_proof')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">You can optionally upload your payment receipt now. Accepted: JPG, PNG, GIF. Max 2MB.</div>
                        </div> -->
                        
                          <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Important:</strong> Setelah membuat pesanan, Anda akan menerima kode pesanan.
Harap simpan kode ini dengan aman karena Anda akan membutuhkannya untuk melacak status pesanan.
Harga akhir akan dihitung berdasarkan berat cucian Anda yang sebenarnya.
                        </div>


                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary me-md-2">
                                <i class="fas fa-arrow-left me-2"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-check me-2"></i>Create Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.service-list .service-checkbox');

    function updatePriceInfo() {
        const firstChecked = Array.from(checkboxes).find(cb => cb.checked);
        let infoDiv = document.getElementById('price-info');

        if (firstChecked) {
            const price = firstChecked.dataset.price;
            const days = firstChecked.dataset.days;
            if (!infoDiv) {
                infoDiv = document.createElement('div');
                infoDiv.id = 'price-info';
                infoDiv.className = 'alert alert-light mt-3';
                const container = document.querySelector('.service-list');
                if (container) container.parentNode.insertBefore(infoDiv, container.nextSibling);
            }
            infoDiv.innerHTML = `
                <i class="fas fa-info-circle me-2"></i>
                <strong>Harga contoh (pertama dipilih):</strong> Rp ${parseInt(price).toLocaleString()}/kg | 
                <strong>Estimasi:</strong> ${days} hari
            `;
        } else if (infoDiv) {
            infoDiv.remove();
        }
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updatePriceInfo));
    updatePriceInfo();
});
</script>
@endsection
