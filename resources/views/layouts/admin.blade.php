<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - Atun Laundry</title>
    <meta name="description" content="Panel administrasi Atun Laundry">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
      <!-- Tailwind / Bootstrap -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --primary-purple: #A8D8C9; /* mint */
            --secondary-purple: #FFFFFF; /* white */
            --accent-purple: #2E8B57; /* forest green */
            --dark-purple: #224F34; /* dark forest green */
            --light-purple: #F0FFF0; /* honeydew */
            --text-dark: #224F34; /* dark forest green */
            --text-light: #2E8B57; /* forest green */
        }

        body {
            background-color: var(--light-purple);
            color: var(--text-dark);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .hover\:shadow-md:hover {
        box-shadow: 0 6px 12px rgba(0,0,0,0.08) !important;
        transform: translateY(-3px);
        transition: all 0.2s ease-in-out;
        }
        .text-pink-600 { color: #db2777 !important; }
        .bg-pink-100 { background-color: #fce7f3 !important; }
        .bg-green-100 { background-color: #dcfce7 !important; }
        .text-green-600 { color: #16a34a !important; }
        .bg-yellow-100 { background-color: #fef9c3 !important; }
        .text-yellow-600 { color: #ca8a04 !important; }
        .bg-sky-100 { background-color: #e0f2fe !important; }
        .text-sky-600 { color: #0284c7 !important; }
        .bg-green-50 { background-color: #f0fdf4 !important; }
        .bg-yellow-50 { background-color: #fefce8 !important; }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: var(--dark-purple);
            color: white;
            transition: all 0.3s ease;
            z-index: 1000;
            box-shadow: 2px 0 10px rgba(0,0,0,0.08);
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            text-align: center;
        }

        .sidebar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .sidebar-brand i {
            font-size: 1.8rem;
        }

        .sidebar-nav {
            padding: 20px 0;
        }

        .nav-item {
            margin-bottom: 5px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: all 0.3s ease;
            border-radius: 0 25px 25px 0;
            margin-right: 10px;
        }

        .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }

        .nav-link.active {
            background-color: rgba(255,255,255,0.2);
            color: white;
            font-weight: 600;
        }

        .nav-link i {
            width: 20px;
            margin-right: 15px;
            text-align: center;
        }

        .sidebar.collapsed .nav-link span {
            display: none;
        }

        .sidebar.collapsed .nav-link {
            justify-content: center;
            margin-right: 0;
        }

        .sidebar.collapsed .nav-link i {
            margin-right: 0;
        }
        /* === Updated Theme (Mint / Dark Blue / Dark Green) === */
        .bg-gradient-primary {
            background: var(--primary-purple);
        }
        .text-accent {
            color: var(--accent-purple) !important;
        }

        /* Tombol utama gradient */
        .btn-gradient {
            background: var(--accent-purple);
            color: #fff !important;
            border: none;
            border-radius: 40px;
            font-weight: 600;
            transition: 0.3s ease;
            box-shadow: 0 4px 12px rgba(26,35,126,0.08);
        }
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(26,35,126,0.12);
        }

        /* Tombol batal */
        .btn-cancel {
            background-color: #f3f4f6;
            color: #6b7280 !important;
            border-radius: 40px;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-cancel:hover {
            background-color: #e5e7eb;
            transform: translateY(-2px);
        }

        /* Input & select */
        .form-control, .form-select {
            border-radius: 10px;
            border: 1px solid rgba(26,35,126,0.06);
            transition: 0.3s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--accent-purple);
            box-shadow: 0 0 6px rgba(46,125,50,0.12);
        }

        .btn-action {
            display: inline-block;
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 600;
            border: none;
            transition: 0.3s ease;
            color: white !important;
            text-decoration: none;
        }
        .btn-action.edit {
            background: var(--accent-purple);
        }
        .btn-action.edit:hover {
            background: var(--dark-purple);
            transform: translateY(-2px);
        }

        .btn-action.delete {
            background: #E53E3E;
            box-shadow: 0 3px 8px rgba(229, 62, 62, 0.3);
        }
        .btn-action.delete:hover {
            background: #C53030;
            transform: translateY(-2px);
        }


        .main-content {
            margin-left: 250px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .main-content.expanded {
            margin-left: 70px;
        }

        .topbar {
            background: white;
            padding: 15px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .sidebar-toggle {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: var(--text-dark);
            cursor: pointer;
            padding: 8px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .sidebar-toggle:hover {
            background-color: var(--light-purple);
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-dark);
            margin: 0;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        /* Warna tema lembut agar selaras dengan Kelola Pesanan */
        .bg-primary-soft {
            background-color: var(--light-purple) !important;
        }
        .text-accent { color: var(--accent-purple) !important; }
        .btn-primary-soft {
            background-color: rgba(46,125,50,0.06);
            color: var(--accent-purple);
            border: 1px solid rgba(46,125,50,0.12);
            transition: all 0.2s ease;
        }
        .btn-primary-soft:hover {
            background-color: rgba(46,125,50,0.12);
            color: var(--dark-purple);
        }

        /* Badge lembut */
        .bg-light-success {
            background-color: #e6f8ed !important;
        }
        .bg-light-secondary {
            background-color: #f0f0f0 !important;
        }

        /* Shadow dan table halus */
        .table > :not(caption) > * > * {
            padding: 1rem 1.2rem;
        }
        .card { border-radius: 1rem; }
        /* Warna dasar */
        .text-accent { color: var(--accent-purple) !important; }
        .bg-primary-soft { background-color: var(--light-purple) !important; }

        /* Badge lembut */
    .badge-light-warning { background-color: #fff8e1; color: #b28704; }
    .badge-light-info { background-color: #e9f0ff; color: var(--dark-purple); }
    .badge-light-secondary { background-color: #f5f5f5; color: #616161; }
    .badge-light-primary { background-color: #e9f5ee; color: var(--accent-purple); }
    .badge-light-success { background-color: #e8f5e9; color: #2e7d32; }

        /* Table */
        table.table td, table.table th {
            vertical-align: middle;
            padding: 0.9rem 1rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 15px;
            background-color: var(--light-purple);
            border-radius: 25px;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            background: var(--accent-purple);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .content-area {
            padding: 30px;
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(26,35,126,0.04);
            background: white;
        }

        .card-header {
            background: var(--primary-purple);
            border-radius: 15px 15px 0 0 !important;
            border: none;
            color: var(--text-dark);
            font-weight: 600;
        }

        .btn-primary {
            background: var(--accent-purple);
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background: var(--dark-purple);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(34, 79, 52, 0.3);
        }

        .stats-card {
            background: var(--primary-purple);
            color: var(--text-dark);
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(46, 139, 87, 0.2);
        }

        .stats-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            opacity: 0.9;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stats-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            /* === Profil User Dropdown === */
        .user-info {
            background-color: var(--light-purple);
            border-radius: 30px;
            padding: 6px 14px;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        .user-info:hover {
            background-color: #e0f2f1;
            transform: translateY(-1px);
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            background: var(--accent-purple);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            box-shadow: 0 2px 6px rgba(46, 139, 87, 0.3);
        }

        .dropdown-menu {
            min-width: 180px;
            border-radius: 12px !important;
            font-size: 0.95rem;
        }
        .dropdown-item {
            transition: all 0.2s ease;
        }
        .dropdown-item:hover {
            background-color: var(--light-purple);
            color: var(--text-dark);
        }

        }
    </style>
    
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.dashboard') }}" class="sidebar-brand">
                <i class="fas fa-sparkles"></i>
                <span>Atun Admin</span>
            </a>
        </div>
        
        <nav class="sidebar-nav">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('services.index') }}" class="nav-link {{ request()->routeIs('services.*') ? 'active' : '' }}">
                        <i class="fas fa-cogs"></i>
                        <span>Kelola Layanan</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('admin.data.index') }}" class="nav-link {{ request()->routeIs('admin.data.*') ? 'active' : '' }}">
                        <i class="fas fa-user-shield"></i>
                        <span>Data Admin</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.orders') }}" class="nav-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
                        <i class="fas fa-list"></i>
                        <span>Kelola Pesanan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.orders.create-manual') }}" class="nav-link {{ request()->routeIs('admin.orders.create-manual') ? 'active' : '' }}">
                        <i class="fas fa-plus-circle"></i>
                        <span>Pesan Manual</span>
                    </a>
                </li>
                
                <!-- <li class="nav-item">
                    <a href="{{ route('admin.data.expenses') }}" class="nav-link {{ request()->routeIs('admin.data.expenses') ? 'active' : '' }}">
                        <i class="fas fa-receipt"></i>
                        <span>Pengeluaran</span>
                    </a>
                </li> -->
                <li class="nav-item">
                    <a href="{{ route('admin.data.promotions') }}" class="nav-link {{ request()->routeIs('admin.data.promotions') ? 'active' : '' }}">
                        <i class="fas fa-gift"></i>
                        <span>Promosi</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.expenses.index') }}" class="nav-link {{ request()->routeIs('admin.expenses.*') ? 'active' : '' }}">
                        <i class="fas fa-receipt"></i>
                        <span>Pengeluaran</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.salaries.index') }}" class="nav-link {{ request()->routeIs('admin.salaries.*') ? 'active' : '' }}">
                        <i class="fas fa-wallet"></i>
                        <span>Gaji Karyawan</span>
                    </a>
                </li>
                <!-- <li class="nav-item">
                    <a href="{{ route('admin.data.reports') }}" class="nav-link {{ request()->routeIs('admin.data.reports') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <span>Laporan</span>
                    </a>
                </li> -->
                <li class="nav-item mt-4">
                    <a href="{{ route('home') }}" class="nav-link">
                        <i class="fas fa-home"></i>
                        <span>Kembali ke Website</span>
                    </a>
                </li>
               
            </ul>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <!-- Top Bar -->
<div class="topbar d-flex justify-content-between align-items-center">
    <div class="topbar-left d-flex align-items-center gap-3">
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <h1 class="page-title mb-0">@yield('page-title', 'Dashboard Admin')</h1>
    </div>

    <div class="topbar-right dropdown">
        <button class="btn d-flex align-items-center user-info dropdown-toggle border-0 bg-transparent shadow-none"
                type="button" id="userMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="user-avatar me-2">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="text-start d-none d-md-block">
                <div class="fw-semibold">{{ Auth::user()->name }}</div>
                <small class="text-muted">Administrator</small>
            </div>
        </button>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3 p-2 mt-2"
            aria-labelledby="userMenuButton">
            <li>
                <a class="dropdown-item rounded-3 py-2" href="#">
                    <i class="fas fa-user me-2 text-purple"></i>Profil
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item rounded-3 py-2 text-danger">
                        <i class="fas fa-sign-out-alt me-2"></i>Keluar
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>

        <!-- Content Area -->
        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarToggle = document.getElementById('sidebarToggle');
            
            // Toggle sidebar
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
            });
            
            // Mobile sidebar toggle
            if (window.innerWidth <= 768) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>

