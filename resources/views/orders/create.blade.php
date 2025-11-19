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
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="service_id" class="form-label">Service Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('service_id') is-invalid @enderror" 
                                            id="service_id" name="service_id" required>
                                        <option value="">Select a service</option>
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
                            <label for="notes" class="form-label">Special Instructions (Optional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3" 
                                      placeholder="Any special instructions for your laundry order">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                      
                        <div class="mb-3">
                            <label for="payment_proof" class="form-label">Upload Payment Proof (Optional)</label>
                            <input type="file" class="form-control @error('payment_proof') is-invalid @enderror" id="payment_proof" name="payment_proof" accept="image/*">
                            @error('payment_proof')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">You can optionally upload your payment receipt now. Accepted: JPG, PNG, GIF. Max 2MB.</div>
                        </div>
                        
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
    const serviceSelect = document.getElementById('service_id');
    const priceInfo = document.getElementById('price-info');
    
    serviceSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const price = selectedOption.dataset.price;
            const days = selectedOption.dataset.days;
            
            // Create or update price info display
            let infoDiv = document.getElementById('price-info');
            if (!infoDiv) {
                infoDiv = document.createElement('div');
                infoDiv.id = 'price-info';
                infoDiv.className = 'alert alert-light mt-3';
                serviceSelect.parentNode.appendChild(infoDiv);
            }
            
            infoDiv.innerHTML = `
                <i class="fas fa-info-circle me-2"></i>
                <strong>Price:</strong> Rp ${parseInt(price).toLocaleString()}/kg | 
                <strong>Estimated Time:</strong> ${days} day(s)
            `;
        } else {
            const infoDiv = document.getElementById('price-info');
            if (infoDiv) {
                infoDiv.remove();
            }
        }
    });
});
</script>
@endsection
