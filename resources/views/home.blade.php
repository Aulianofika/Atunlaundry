@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
@section('styles')
<style>
/* Hero tweaks */
.hero-section .display-4 {
    font-weight: 800;
    letter-spacing: -0.015em;
}
.hero-section .lead {
    max-width: 520px;
}

/* Services cards polishing */
.service-card .card-body {
    display: flex;
    flex-direction: column;
    padding: 2rem;
}
.service-icon {
    width: 84px;
    height: 84px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 18px;
    margin: 0 auto 16px auto;
}
.service-icon i { font-size: 34px !important; }

.service-card .card-title { margin-top: 6px; margin-bottom: 6px; }
.service-card .card-text { margin-bottom: 12px; color: #A8D8C9; }
.service-card .service-footer { 
    margin-top: auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.service-price { color: #ded300ff; font-weight: 700; }
.service-card:hover { transform: translateY(-8px); box-shadow: 0 18px 40px rgba(139,95,191,0.12); }

@media (max-width: 767px) {
    .hero-section { padding: 40px 0; }
    .service-icon { width: 64px; height: 64px; }
}
</style>
@endsection
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-4">
                    Layanan Laundry Profesional
                </h1>
                <p class="lead mb-4">Cuci, kering, dan setrika berkualitas dengan layanan antar jemput yang nyaman. Pakaian Anda layak mendapat perawatan terbaik!</p>
                <div class="d-flex gap-3">
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
            <!-- <div class="col-lg-6 text-center">
                <div class="hero-icon-container">
                    <i class="fas fa-tshirt hero-main-icon"></i>
                    <div class="floating-icons">
                        
                    </div>
                </div>
            </div> -->
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
            <div class="col-lg-4 col-md-6">
                <div class="card service-card h-100">
                    <div class="card-body text-center p-4">
                        <div class="service-icon">
                            @switch($service->name)
                                @case('Regular Laundry')
                                    <i class="fas fa-tshirt text-primary"></i>
                                    @break
                                @case('Express Laundry')
                                    <i class="fas fa-bolt text-warning"></i>
                                    @break
                                @case('Ironing Only')
                                    <i class="fas fa-iron text-info"></i>
                                    @break
                                @case('Dry Clean')
                                    <i class="fas fa-gem text-success"></i>
                                    @break
                                @case('Wash & Iron')
                                    <i class="fas fa-sparkles text-purple"></i>
                                    @break
                                @default
                                    <i class="fas fa-tshirt text-primary"></i>
                            @endswitch
                        </div>
                        <h5 class="card-title fw-bold">{{ $service->name }}</h5>
                        <p class="card-text">{{ $service->description }}</p>
                        <div class="service-footer">
                            <span class="service-price">
                                <i class="fas fa-tag me-1"></i>Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}/kg
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
