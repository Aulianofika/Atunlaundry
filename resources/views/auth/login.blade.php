@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header text-center" style="background: linear-gradient(135deg, #A8D8C9, rgba(232,245,233,0.6)); color: var(--text-dark);">
                    <h4 class="mb-0">
                       Masuk ke Akun Anda
                    </h4>
                </div>
                <div class="card-body p-4">
                    
                    @if(session('error'))
                        <div class="alert alert-danger text-center mb-4">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">
                                <i class="fas fa-envelope me-1 text-accent"></i>Alamat Email
                            </label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" 
                                   value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">
                                <i class="fas fa-lock me-1 text-accent"></i>Kata Sandi
                            </label>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Ingat Saya
                            </label>
                        </div>

                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-arrow-right-to-bracket me-2"></i>Masuk
                            </button>
                        </div>
                    </form>

                    <div class="text-center">
                        <a href="{{ route('password.request') }}" class="text-decoration-none fw-semibold text-accent">
                            <i class="fas fa-unlock-alt me-1"></i>Lupa kata sandi?
                        </a>
                    </div>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-0">Belum punya akun? 
                            <a href="{{ route('register') }}" class="fw-semibold text-decoration-none text-accent">
                                <i class="fas fa-user-plus me-1"></i>Daftar di sini
                            </a>
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
