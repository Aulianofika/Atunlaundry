<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Atun Laundry') - Layanan Laundry Profesional</title>
    <meta name="description" content="Layanan laundry profesional dengan antar jemput. Cuci, kering, dan setrika berkualitas untuk kenyamanan Anda.">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts: Poppins for footer and headings -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        :root {
            /* New palette requested by user */
            --primary-purple: #E8F5E9; /* Mint green (primary) */
            --secondary-purple: #FFFFFF; /* White (secondary) */
            --accent-purple: #2E7D32; /* Dark green (accent) */
            --dark-purple: #1A237E; /* Dark blue (neutral / text) */
            --light-purple: #F4FBF6; /* Very light mint (background) */
            --text-dark: #1A237E; /* Dark blue for text */
            --text-light: #2E7D32; /* Accent green for highlights */
        }

        body {
            background-color: var(--light-purple);
            color: var(--text-dark);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
            box-shadow: 0 2px 10px rgba(26, 35, 126, 0.06);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--text-dark) !important;
            font-size: 1.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent-purple), var(--dark-purple));
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            color: #fff;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, var(--dark-purple), var(--accent-purple));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26,35,126,0.12);
        }

        .btn-outline-primary {
            color: var(--dark-purple);
            border-color: var(--dark-purple);
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
            background: transparent;
        }

        .btn-outline-primary:hover {
            background-color: rgba(26,37,126,0.06);
            border-color: var(--dark-purple);
            transform: translateY(-2px);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(26, 35, 126, 0.04);
            background: white;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-purple), var(--secondary-purple));
            border-radius: 15px 15px 0 0 !important;
            border: none;
            color: var(--text-dark);
            font-weight: 600;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid rgba(46, 125, 50, 0.08);
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--accent-purple);
            box-shadow: 0 0 0 0.12rem rgba(46,125,50,0.12);
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        .badge {
            border-radius: 20px;
            padding: 8px 15px;
            font-weight: 500;
        }

    .status-waiting { background: linear-gradient(45deg, #F0FFF4, #E6FAEF); color: #2E7D32; border: 1px solid #DFF3E3; }
    .status-picked { background: linear-gradient(45deg, #EEF1FF, #E3E9FF); color: #1A237E; border: 1px solid #D6DEFF; }
    .status-payment { background: linear-gradient(45deg, #FFF9F0, #FFF4E6); color: #8B4513; border: 1px solid #FFEDD5; }
    .status-verification { background: linear-gradient(45deg, #F0FFF4, #E6FAEF); color: #2E7D32; border: 1px solid #DFF3E3; }
    .status-processed { background: linear-gradient(45deg, #F6F7FA, #EEF0F6); color: #1A237E; border: 1px solid #E6E9F4; }
    .status-completed { background: linear-gradient(45deg, #EEF1FF, #E3E9FF); color: #1A237E; border: 1px solid #D6DEFF; }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-purple), rgba(232,245,233,0.6));
            padding: 80px 0;
            margin-bottom: 50px;
        }

        .service-card {
            transition: all 0.3s ease;
            border-radius: 15px;
            overflow: hidden;
        }

        .service-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(26,35,126,0.08);
        }

        /* Footer styles using Poppins for a clean modern look */
        .footer {
            background: #A8D8C9;
            color: var(--text-dark);
            padding: 48px 0;
            margin-top: 50px;
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 0.95rem;
            line-height: 1.6;
        }

        .footer h5 {
            font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-weight: 600;
            letter-spacing: 0.2px;
            color: var(--text-dark);
            margin-bottom: 12px;
        }

        .footer p {
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        .footer a {
            color: var(--text-dark);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer a:hover {
            color: var(--accent-purple);
        }

        .footer .contact-item i {
            color: var(--text-dark);
            width: 22px;
        }

        .footer .copyright {
            opacity: 0.92;
            font-size: 0.9rem;
        }
        /* Responsive map container */
        .map-responsive {
            position: relative;
            overflow: hidden;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
            height: 0;
        }

        .map-responsive iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        /* Hero Animation */
        .hero-icon-container {
            position: relative;
            display: inline-block;
        }

        .hero-main-icon {
            font-size: 200px;
            color: var(--accent-purple);
            animation: bounce 2s infinite;
        }

        .floating-icons {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
        }

        .floating-icon-1, .floating-icon-2, .floating-icon-3 {
            position: absolute;
            font-size: 30px;
            color: var(--primary-purple);
            animation: float 3s ease-in-out infinite;
        }

        .floating-icon-1 {
            top: 20%;
            right: 10%;
            animation-delay: 0s;
        }

        .floating-icon-2 {
            bottom: 30%;
            left: 5%;
            animation-delay: 1s;
        }

        .floating-icon-3 {
            top: 60%;
            right: 20%;
            animation-delay: 2s;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-30px);
            }
            60% {
                transform: translateY(-15px);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        /* Service Cards Enhancement */
        .service-card {
            position: relative;
            overflow: hidden;
        }

        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .service-card:hover::before {
            left: 100%;
        }

        /* Status Badges Enhancement */
        .status-waiting { 
            background: linear-gradient(45deg, #FFF3CD, #FFE69C); 
            color: #856404; 
            border: 1px solid #FFE69C;
        }
        .status-picked { 
            background: linear-gradient(45deg, #D1ECF1, #B8DAFF); 
            color: #0C5460; 
            border: 1px solid #B8DAFF;
        }
        .status-payment { 
            background: linear-gradient(45deg, #F8D7DA, #FFB3BA); 
            color: #721C24; 
            border: 1px solid #FFB3BA;
        }
        .status-verification { 
            background: linear-gradient(45deg, #D4EDDA, #C3E6CB); 
            color: #155724; 
            border: 1px solid #C3E6CB;
        }
        .status-processed { 
            background: linear-gradient(45deg, #E2E3E5, #D6D8DB); 
            color: #383D41; 
            border: 1px solid #D6D8DB;
        }
        .status-completed { 
            background: linear-gradient(45deg, #D1ECF1, #B8DAFF); 
            color: #0C5460; 
            border: 1px solid #B8DAFF;
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-sparkles me-2 text-warning"></i>Atun Laundry
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i>Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('orders.check') }}">
                            <i class="fas fa-search me-1"></i>Cek Pesanan
                        </a>
                    </li>
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('orders.create') }}">
                                <i class="fas fa-plus-circle me-1"></i>Pesan Baru
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('orders.index') }}">
                                <i class="fas fa-list me-1"></i>Pesanan Saya
                            </a>
                        </li>
                        @if(Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-cog me-1"></i>Panel Admin
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
                
                <ul class="navbar-nav">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt me-1"></i>Masuk
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary ms-2" href="{{ route('register') }}">
                                <i class="fas fa-user-plus me-1"></i>Daftar
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-sparkles me-2 text-warning"></i>Atun Laundry</h5>
                    <p>Layanan laundry profesional dengan antar jemput. Cuci, kering, dan setrika berkualitas untuk kenyamanan Anda.</p>
                </div>
                <div class="col-md-4">
                    <h5><i class="fas fa-address-book me-2"></i>Informasi Kontak</h5>
                    <p class="contact-item"><i class="fas fa-phone me-2"></i>+62 812-3456-7890</p>
                    <p class="contact-item"><i class="fas fa-envelope me-2"></i>info@atunlaundry.com</p>
                    <p class="contact-item"><i class="fas fa-map-marker-alt me-2"></i>Kota Anda, Indonesia</p>
                </div>
                <div class="col-md-4">
                    <h5><i class="fas fa-map-marked-alt me-2"></i>Lokasi Kami</h5>
                    <div class="map-responsive rounded">
                        <!-- Replace the src with your Google Maps embed URL or change coordinates -->
                        <iframe src="https://www.google.com/maps?q=-6.200000,106.816666&hl=es;z=14&output=embed" width="600" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                    <small class="text-white-50 d-block mt-2">Klik peta untuk membuka di Google Maps. Ganti koordinat di template jika perlu.</small>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} Atun Laundry. Semua hak dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @yield('scripts')
</body>
</html>
