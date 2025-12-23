@extends('layouts.admin')

@section('title', 'Buat Pesanan Manual')
@section('page-title', 'Buat Pesanan Manual')

@section('content')
<style>
/* Service Selection Styles */
.category-tabs {
    display: flex;
    gap: 5px;
    flex-wrap: wrap;
    margin-bottom: 12px;
}

.category-tab {
    padding: 4px 10px;
    border-radius: 14px;
    border: 1.5px solid #e0e0e0;
    background: white;
    color: #666;
    font-size: 0.7rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.category-tab:hover {
    border-color: #2E7D32;
    color: #2E7D32;
}

.category-tab.active {
    background: linear-gradient(135deg, #2E7D32, #1B5E20);
    border-color: transparent;
    color: white;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
    gap: 6px;
}

.service-card-item {
    position: relative;
    border: 1.5px solid #e8e8e8;
    border-radius: 6px;
    padding: 8px 4px;
    text-align: center;
    cursor: pointer;
    transition: all 0.2s ease;
    background: white;
}

.service-card-item:hover {
    border-color: #2E7D32;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.service-card-item.selected {
    border-color: #2E7D32;
    background: linear-gradient(135deg, #E8F5E9, #F1F8E9);
}

.service-card-item.selected::after {
    content: '✓';
    position: absolute;
    top: 3px;
    right: 3px;
    width: 12px;
    height: 12px;
    background: #2E7D32;
    color: white;
    border-radius: 50%;
    font-size: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.service-card-item input {
    display: none;
}

.service-card-item .service-name {
    font-weight: 600;
    font-size: 0.7rem;
    color: #1A237E;
    margin-bottom: 2px;
    line-height: 1.2;
}

.service-card-item .service-price {
    font-size: 0.75rem;
    font-weight: 700;
    color: #2E7D32;
}

.service-card-item .service-unit {
    font-size: 0.6rem;
    color: #888;
}

.service-card-item .service-days {
    font-size: 0.55rem;
    color: #aaa;
    margin-top: 1px;
}

.service-card-item.hidden {
    display: none;
}

/* Summary Box */
.order-summary {
    background: linear-gradient(135deg, #E8F5E9, #fff);
    border: 1px solid #C8E6C9;
    border-radius: 10px;
    padding: 12px;
    margin-top: 12px;
}

.order-summary h6 {
    font-size: 0.8rem;
    color: #2E7D32;
    margin-bottom: 8px;
}

.selected-services-list {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.selected-service-tag {
    background: #E8F5E9;
    color: #2E7D32;
    padding: 3px 10px;
    border-radius: 14px;
    font-size: 0.75rem;
    font-weight: 500;
}
</style>

<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header text-center rounded-top-4 py-3" 
                    style="background: linear-gradient(135deg, #2E7D32, #1B5E20);">
                    <h5 class="mb-0 fw-bold text-white">
                        <i class="fas fa-plus-circle me-2"></i>Buat Pesanan Manual
                    </h5>
                    <small class="text-white-50">Pesanan pelanggan yang datang langsung ke outlet</small>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.orders.store-manual') }}" id="orderForm">
                        @csrf
                        
                        <div class="row">
                            <!-- Left Column: Services -->
                            <div class="col-lg-7">
                                <label class="form-label fw-semibold mb-2">Pilih Layanan <span class="text-danger">*</span></label>
                                
                                <!-- Category Tabs -->
                                <div class="category-tabs">
                                    <button type="button" class="category-tab active" data-category="all">Semua</button>
                                    <button type="button" class="category-tab" data-category="reguler">Reguler</button>
                                    <button type="button" class="category-tab" data-category="selimut">Selimut</button>
                                    <button type="button" class="category-tab" data-category="bedcover">Bed Cover</button>
                                    <button type="button" class="category-tab" data-category="rumah">Rumah</button>
                                </div>
                                
                                <!-- Services Grid -->
                                <div class="services-grid">
                                    @foreach($services as $service)
                                    @php
                                        $category = 'reguler';
                                        $name = strtolower($service->name);
                                        if (str_contains($name, 'selimut')) $category = 'selimut';
                                        elseif (str_contains($name, 'bed cover')) $category = 'bedcover';
                                        elseif (str_contains($name, 'seprei') || str_contains($name, 'gorden') || str_contains($name, 'handuk')) $category = 'rumah';
                                    @endphp
                                    <label class="service-card-item" data-category="{{ $category }}">
                                        <input type="checkbox" name="service_ids[]" value="{{ $service->id }}"
                                               data-price="{{ $service->price_per_kg }}"
                                               data-name="{{ $service->name }}"
                                               data-days="{{ $service->estimated_days }}"
                                               {{ (is_array(old('service_ids')) && in_array($service->id, old('service_ids'))) ? 'checked' : '' }}>
                                        <div class="service-name">{{ $service->name }}</div>
                                        <div class="service-price">Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}</div>
                                        <div class="service-unit">/{{ $service->unit }}</div>
                                        <div class="service-days">{{ $service->estimated_days }} hari</div>
                                    </label>
                                    @endforeach
                                </div>
                                
                                @error('service_ids')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                                
                                <!-- Order Summary -->
                                <div class="order-summary" id="orderSummary" style="display: none;">
                                    <h6><i class="fas fa-check-circle me-1"></i>Layanan Dipilih:</h6>
                                    <div class="selected-services-list" id="selectedServicesList"></div>
                                </div>
                            </div>
                            
                            <!-- Right Column: Customer Info -->
                            <div class="col-lg-5">
                                <div class="ps-lg-3">
                                    <label class="form-label fw-semibold mb-2">Data Pelanggan</label>
                                    
                                    <div class="mb-2">
                                        <input type="text" class="form-control form-control-sm @error('customer_name') is-invalid @enderror" 
                                               id="customer_name" name="customer_name" 
                                               value="{{ old('customer_name') }}" placeholder="Nama pelanggan *" required>
                                        @error('customer_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-2">
                                        <input type="tel" class="form-control form-control-sm @error('customer_phone') is-invalid @enderror" 
                                               id="customer_phone" name="customer_phone" 
                                               value="{{ old('customer_phone') }}" placeholder="No. Telepon *" required>
                                        @error('customer_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-2">
                                        <select class="form-select form-select-sm @error('pickup_method') is-invalid @enderror" 
                                                id="pickup_method" name="pickup_method" required>
                                            <option value="">Metode Pengambilan *</option>
                                            <option value="pickup" {{ old('pickup_method') === 'pickup' ? 'selected' : '' }}>🏪 Ambil di Toko</option>
                                            <option value="delivery" {{ old('pickup_method') === 'delivery' ? 'selected' : '' }}>🚚 Antar ke Rumah</option>
                                        </select>
                                        @error('pickup_method')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-2">
                                        <textarea class="form-control form-control-sm @error('customer_address') is-invalid @enderror" 
                                                  id="customer_address" name="customer_address" rows="2" 
                                                  placeholder="Alamat lengkap *" required>{{ old('customer_address') }}</textarea>
                                        @error('customer_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-2">
                                        <input type="text" class="form-control form-control-sm @error('items_description') is-invalid @enderror" 
                                               id="items_description" name="items_description" 
                                               value="{{ old('items_description') }}" placeholder="Deskripsi barang (opsional)">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <input type="text" class="form-control form-control-sm @error('notes') is-invalid @enderror" 
                                               id="notes" name="notes" 
                                               value="{{ old('notes') }}" placeholder="Catatan khusus (opsional)">
                                    </div>
                                    
                                    <!-- Info -->
                                    <div class="alert alert-light border py-2 mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle me-1 text-purple"></i>
                                            Berat & harga dapat diupdate saat cucian selesai.
                                        </small>
                                    </div>
                                    
                                    <!-- Buttons -->
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary btn-sm flex-fill">
                                            <i class="fas fa-arrow-left me-1"></i>Batal
                                        </a>
                                        <button type="submit" class="btn btn-purple btn-sm flex-fill">
                                            <i class="fas fa-check me-1"></i>Buat Pesanan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Category filter
    const categoryTabs = document.querySelectorAll('.category-tab');
    const serviceCards = document.querySelectorAll('.service-card-item');
    
    categoryTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const category = this.dataset.category;
            
            categoryTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            serviceCards.forEach(card => {
                if (category === 'all' || card.dataset.category === category) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        });
    });
    
    // Service selection
    const checkboxes = document.querySelectorAll('.service-card-item input[type="checkbox"]');
    const summaryBox = document.getElementById('orderSummary');
    const selectedList = document.getElementById('selectedServicesList');
    
    function updateSelection() {
        const selected = [];
        
        serviceCards.forEach(card => {
            const checkbox = card.querySelector('input[type="checkbox"]');
            if (checkbox.checked) {
                card.classList.add('selected');
                selected.push(checkbox.dataset.name);
            } else {
                card.classList.remove('selected');
            }
        });
        
        if (selected.length > 0) {
            summaryBox.style.display = 'block';
            selectedList.innerHTML = selected.map(name => 
                `<span class="selected-service-tag">${name}</span>`
            ).join('');
        } else {
            summaryBox.style.display = 'none';
        }
    }
    
    checkboxes.forEach(cb => cb.addEventListener('change', updateSelection));
    updateSelection();
});
</script>
@endsection
