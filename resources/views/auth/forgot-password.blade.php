@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-header text-center" style="background: #A8D8C9; color: white;">
                    <h4 class="mb-0">
                        <i class="fas fa-unlock-alt me-2"></i>Reset Password
                    </h4>
                </div>
                <div class="card-body p-4">
                    
                    @if (session('success'))
                        <div class="alert alert-success text-center mb-4">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.direct.reset') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">
                                <i class="fas fa-envelope me-1 text-purple"></i>Alamat Email
                            </label>
                            <input type="email" name="email" id="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   value="{{ old('email') }}" required>
                            @error('email') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-semibold">
                                <i class="fas fa-lock me-1 text-purple"></i>Password Baru
                            </label>
                            <input type="password" name="password" id="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   required>
                            @error('password') 
                                <div class="invalid-feedback">{{ $message }}</div> 
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label fw-semibold">
                                <i class="fas fa-lock me-1 text-purple"></i>Konfirmasi Password Baru
                            </label>
                            <input type="password" name="password_confirmation" id="password_confirmation" 
                                   class="form-control" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg" style="background-color: #A8D8C9; border: none;">
                                <i class="fas fa-sync-alt me-2"></i>Perbarui Password
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="text-decoration-none fw-semibold" style="color: #A8D8C9;">
                            <i class="fas fa-arrow-left me-1"></i>Kembali ke Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
