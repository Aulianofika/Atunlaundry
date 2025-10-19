@extends('layouts.admin')

@section('title', 'Kelola Layanan')
@section('page-title', 'Kelola Layanan')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold text-secondary mb-0">
            <i class=""></i>
        </h2>
        <a href="{{ route('services.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Tambah Layanan
        </a>
    </div>

    <!-- Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Card Tabel -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-purple-soft text-purple fw-semibold rounded-top-4">
            <i class=""></i>
        </div>
        <div class="card-body p-0">
            @if($services->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-muted small text-uppercase">
                            <th>Nama</th>
                            <th>Harga per Kg</th>
                            <th>Estimasi Hari</th>
                            <th>Status</th>
                            <th class="text-center" width="180">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($services as $service)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $service->name }}</td>
                            <td class="text-dark">Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}</td>
                            <td class="text-muted">{{ $service->estimated_days }} hari</td>
                            <td>
                                @if($service->is_active)
                                    <span class="badge rounded-pill bg-light-success text-success px-3 py-2">
                                        <i class="bi bi-check-circle me-1"></i>Aktif
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-light-secondary text-secondary px-3 py-2">
                                        <i class="bi bi-x-circle me-1"></i>Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('services.edit', $service) }}" 
                                   class="btn btn-outline-primary btn-sm rounded-pill me-2">
                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                </a>
                                <form action="{{ route('services.destroy', $service) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus layanan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill">
                                        <i class="bi bi-trash me-1"></i>Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4 mb-3">
                {{ $services->links() }}
            </div>

            @else
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted mb-3"></i>
                <h5 class="text-muted">Belum ada layanan</h5>
                <p class="text-muted small">Silakan tambahkan layanan baru.</p>
                <a href="{{ route('services.create') }}" class="btn btn-purple-soft px-4 py-2">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Layanan
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
