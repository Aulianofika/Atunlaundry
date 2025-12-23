@extends('layouts.app')

@section('title', 'Buat Pesanan Baru')

@section('styles')
<style>
/* Service Selection Styles */
.category-tabs {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
    margin-bottom: 16px;
}

.category-tab {
    padding: 5px 12px;
    border-radius: 16px;
    border: 1.5px solid #e0e0e0;
    background: white;
    color: #666;
    font-size: 0.75rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.category-tab:hover {
    border-color: #2E7D32;
    color: #2E7D32;
}

.category-tab.active {
    background: linear-gradient(135deg, #2E7D32, #1A237E);
    border-color: transparent;
    color: white;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 8px;
}

.service-card-item {
    position: relative;
    border: 1.5px solid #e8e8e8;
    border-radius: 8px;
    padding: 10px 6px;
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
    top: 4px;
    right: 4px;
    width: 14px;
    height: 14px;
    background: #2E7D32;
    color: white;
    border-radius: 50%;
    font-size: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.service-card-item input {
    display: none;
}

.service-card-item .service-name {
    font-weight: 600;
    font-size: 0.75rem;
    color: #1A237E;
    margin-bottom: 2px;
    line-height: 1.2;
}

.service-card-item .service-price {
    font-size: 0.8rem;
    font-weight: 700;
    color: #2E7D32;
}

.service-card-item .service-unit {
    font-size: 0.65rem;
    color: #888;
}

.service-card-item .service-days {
    font-size: 0.6rem;
    color: #aaa;
    margin-top: 2px;
}

/* Summary Box */
.order-summary {
    background: linear-gradient(135deg, #f8f9fa, #fff);
    border: 1px solid #e0e0e0;
    border-radius: 12px;
    padding: 16px;
    margin-top: 16px;
}

.order-summary h6 {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 12px;
}

.selected-services-list {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.selected-service-tag {
    background: #E8F5E9;
    color: #2E7D32;
    padding: 4px 12px;
    border-radius: 16px;
    font-size: 0.8rem;
    font-weight: 500;
}

/* Compact Form */
.compact-form .form-label {
    font-size: 0.85rem;
    font-weight: 500;
    color: #555;
    margin-bottom: 4px;
}

.compact-form .form-control,
.compact-form .form-select {
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 0.9rem;
}

.compact-form textarea.form-control {
    resize: none;
}

/* Hide services initially for filter */
.service-card-item[data-category] {
    display: block;
}

.service-card-item.hidden {
    display: none;
}
</style>
@endsection

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold text-center">
                        <i class="fas fa-plus-circle me-2 text-success"></i>Buat Pesanan Baru
                    </h5>
                </div>
                <div class="card-body p-4 compact-form">
                    <form method="POST" action="{{ route('orders.store') }}" id="orderForm">
                        @csrf
                        
                        <!-- Service Selection -->
                        <div class="mb-4">
                            <label class="form-label">Pilih Layanan <span class="text-danger">*</span></label>
                            
                            <!-- Category Tabs -->
                            <div class="category-tabs">
                                <button type="button" class="category-tab active" data-category="all">Semua</button>
                                <button type="button" class="category-tab" data-category="reguler">Reguler</button>
                                <button type="button" class="category-tab" data-category="selimut">Selimut</button>
                                <button type="button" class="category-tab" data-category="bedcover">Bed Cover</button>
                                <button type="button" class="category-tab" data-category="rumah">Peralatan Rumah</button>
                            </div>
                            
                            <!-- Services Grid -->
                            <div class="services-grid">
                                @foreach($services as $service)
                                @php
                                    // Determine category
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

                        <hr class="my-4">

                        <!-- Customer Info - Compact 2 columns -->
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="customer_name" class="form-label">Nama <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('customer_name') is-invalid @enderror" 
                                       id="customer_name" name="customer_name" 
                                       value="{{ old('customer_name', Auth::user()->name) }}" 
                                       placeholder="Nama lengkap" required>
                                @error('customer_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="customer_phone" class="form-label">Telepon <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror" 
                                       id="customer_phone" name="customer_phone" 
                                       value="{{ old('customer_phone', Auth::user()->phone) }}" 
                                       placeholder="08xxxxxxxxxx" required>
                                @error('customer_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="pickup_method" class="form-label">Metode <span class="text-danger">*</span></label>
                                <select class="form-select @error('pickup_method') is-invalid @enderror" 
                                        id="pickup_method" name="pickup_method" required>
                                    <option value="">Pilih...</option>
                                    <option value="pickup" {{ old('pickup_method') === 'pickup' ? 'selected' : '' }}>🏪 Ambil di Toko</option>
                                    <option value="delivery" {{ old('pickup_method') === 'delivery' ? 'selected' : '' }}>🚚 Antar ke Rumah</option>
                                </select>
                                @error('pickup_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="items_description" class="form-label">Deskripsi Barang</label>
                                <input type="text" class="form-control @error('items_description') is-invalid @enderror" 
                                       id="items_description" name="items_description" 
                                       value="{{ old('items_description') }}" 
                                       placeholder="3 kemeja, 2 celana...">
                                @error('items_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="customer_address" class="form-label">Alamat <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('customer_address') is-invalid @enderror" 
                                          id="customer_address" name="customer_address" rows="2" 
                                          placeholder="Alamat lengkap untuk penjemputan/pengantaran" required>{{ old('customer_address') }}</textarea>
                                @error('customer_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="notes" class="form-label">Catatan Khusus</label>
                                <input type="text" class="form-control @error('notes') is-invalid @enderror" 
                                       id="notes" name="notes" 
                                       value="{{ old('notes') }}" 
                                       placeholder="Instruksi khusus (opsional)">
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div class="alert alert-light border mt-4 mb-4">
                            <small>
                                <i class="fas fa-info-circle text-primary me-1"></i>
                                Harga akhir akan dihitung berdasarkan berat cucian sebenarnya.
                            </small>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-check me-1"></i>Buat Pesanan
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
    // Category filter
    const categoryTabs = document.querySelectorAll('.category-tab');
    const serviceCards = document.querySelectorAll('.service-card-item');
    
    categoryTabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const category = this.dataset.category;
            
            // Update active tab
            categoryTabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            // Filter services
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
        
        // Update summary
        if (selected.length > 0) {
            summaryBox.style.display = 'block';
            selectedList.innerHTML = selected.map(name => 
                `<span class="selected-service-tag">${name}</span>`
            ).join('');
        } else {
            summaryBox.style.display = 'none';
        }
    }
    
    checkboxes.forEach(cb => {
        cb.addEventListener('change', updateSelection);
    });
    
    // Initial state
    updateSelection();
});
</script>
@endsection
