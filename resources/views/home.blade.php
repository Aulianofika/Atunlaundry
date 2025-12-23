@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
@section('styles')
<style>
/* Hero Section with Background Image */
.hero-section {
    background-image: url('{{ asset('storage/background/background.jpg') }}');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    position: relative;
    min-height: 500px;
    display: flex;
    align-items: center;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(240, 248, 245, 0.85) 50%, rgba(255, 255, 255, 0.8) 100%);
    z-index: 1;
}

.hero-section .container {
    position: relative;
    z-index: 2;
}

/* Hero tweaks */
.hero-section .display-4 {
    font-weight: 800;
    letter-spacing: -0.015em;
}
.hero-section .lead {
    max-width: 520px;
}

/* Services cards polishing - smaller cards */
.service-card .card-body {
    display: flex;
    flex-direction: column;
    padding: 1rem 1.2rem;
}
.service-icon {
    width: 50px;
    height: 50px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    margin: 0 auto 8px auto;
    background: linear-gradient(135deg, #E8F5E9, #C8E6C9);
}
.service-icon i { font-size: 22px !important; color: #2E7D32; }

.service-card .card-title { margin-top: 4px; margin-bottom: 4px; font-size: 0.95rem; }
.service-card .card-text { margin-bottom: 8px; color: #A8D8C9; font-size: 0.8rem; }
.service-card .service-footer { 
    margin-top: auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.85rem;
}
.service-price { color: #2E7D32; font-weight: 700; }
.service-card:hover { transform: translateY(-5px); box-shadow: 0 12px 30px rgba(46,125,50,0.12); }
.service-card { min-height: auto; }

@media (max-width: 767px) {
    .hero-section { 
        padding: 40px 0; 
        min-height: 400px;
    }
    .service-icon { width: 40px; height: 40px; }
    .service-icon i { font-size: 18px !important; }
}
</style>
@endsection
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10 text-center">
                <h1 class="display-4 fw-bold mb-4">
                    Layanan Laundry Atun
                </h1>
                <p class="lead mb-4 mx-auto" style="max-width: 600px;">Kerapihan Anda Tanggung Jawab Kami!</p>
                <div class="d-flex gap-3 justify-content-center">
                    @auth
                        <a href="{{ route('orders.create') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-plus-circle me-2"></i>Pesan Sekarang
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-rocket me-2"></i>Mulai Sekarang
                        </a>
                    @endauth
                    <a href="{{ route('orders.check') }}" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-search me-2"></i>Cek Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold">
                    Layanan Kami
                </h2>
                <p class="lead">Layanan laundry profesional yang disesuaikan dengan kebutuhan Anda</p>
            </div>
        </div>
        
        <div class="row g-4">
            @foreach($services as $service)
            <div class="col-lg-3 col-md-4 col-6">
                <div class="card service-card h-100">
                    <div class="card-body text-center">
                        <div class="service-icon">
                            @if(str_contains(strtolower($service->name), 'selimut'))
                                <i class="fas fa-cloud"></i>
                            @elseif(str_contains(strtolower($service->name), 'bed cover'))
                                <i class="fas fa-bed"></i>
                            @elseif(str_contains(strtolower($service->name), 'seprei'))
                                <i class="fas fa-layer-group"></i>
                            @elseif(str_contains(strtolower($service->name), 'gorden'))
                                <i class="fas fa-window-maximize"></i>
                            @elseif(str_contains(strtolower($service->name), 'handuk'))
                                <i class="fas fa-bath"></i>
                            @elseif(str_contains(strtolower($service->name), 'setrika'))
                                <i class="fas fa-fire-alt"></i>
                            @elseif(str_contains(strtolower($service->name), 'cuci'))
                                <i class="fas fa-water"></i>
                            @elseif(str_contains(strtolower($service->name), 'jaket'))
                                <i class="fas fa-user-tie"></i>
                            @elseif(str_contains(strtolower($service->name), 'family'))
                                <i class="fas fa-users"></i>
                            @else
                                <i class="fas fa-tshirt"></i>
                            @endif
                        </div>
                        <h5 class="card-title fw-bold">{{ $service->name }}</h5>
                        <p class="card-text">{{ $service->description }}</p>
                        <div class="service-footer">
                            <span class="service-price">
                                <i class="fas fa-tag me-1"></i>Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}/{{ $service->unit }}
                            </span>
                            <small class="text-muted">
                                <i class="fas fa-clock me-1"></i>{{ $service->estimated_days }} hari
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Promotions Section -->
@if($promotions->count() > 0)
<section class="py-5" style="background-color: var(--light-purple);">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold">
                    Promosi Spesial
                </h2>
                <p class="lead">Jangan lewatkan penawaran menarik dari kami!</p>
            </div>
        </div>
        
        <div class="row g-4">
            @foreach($promotions as $promotion)
            <div class="col-lg-6">
                <div class="card border-warning shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-percentage me-2"></i>{{ $promotion->title }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="card-text">{{ $promotion->description }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            @if($promotion->discount_percentage)
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-tag me-1"></i>{{ $promotion->discount_percentage }}% OFF
                                </span>
                            @elseif($promotion->discount_amount)
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-tag me-1"></i>Rp {{ number_format($promotion->discount_amount, 0, ',', '.') }} OFF
                                </span>
                            @endif
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>Berlaku hingga {{ $promotion->end_date->format('d M Y') }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="display-5 fw-bold">
                    <i class=""></i>Mengapa Memilih Kami?
                </h2>
                <p class="lead">Kami memberikan pengalaman laundry terbaik</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6 text-center">
                <div class="mb-3">
                    <i class="fas fa-truck fa-3x text-primary"></i>
                </div>
                <h5>Antar Jemput</h5>
                <p class="text-muted">Layanan antar jemput yang nyaman langsung ke depan pintu Anda</p>
            </div>
            <div class="col-lg-4 col-md-6 text-center">
                <div class="mb-3">
                    <i class="fas fa-clock fa-3x text-success"></i>
                </div>
                <h5>Layanan Cepat</h5>
                <p class="text-muted">Waktu pengerjaan cepat dengan opsi layanan ekspres tersedia</p>
            </div>
            <div class="col-lg-4 col-md-6 text-center">
                <div class="mb-3">
                    <i class="fas fa-shield-alt fa-3x text-info"></i>
                </div>
                <h5>Jaminan Kualitas</h5>
                <p class="text-muted">Perawatan profesional dengan jaminan kualitas untuk semua layanan</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-5 text-center" style="background: var(--secondary-purple); color: var(--text-dark);">
    <div class="container">
        <h3 class="fw-bold mb-3">Mulai Laundry Sekarang!</h3>
        <p class="mb-4">Pesan layanan laundry Anda dengan cepat dan mudah, hanya dengan beberapa klik.</p>
        <a href="{{ route('orders.create') }}" class="btn btn-outline-primary btn-lg">
            <i class="fas fa-shopping-cart me-2"></i>Pesan Sekarang
        </a>
    </div>
</section>
@endsection
