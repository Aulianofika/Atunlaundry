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
        <div class="card-header bg-primary-soft text-accent fw-semibold rounded-top-4"></div>
        <div class="card-body p-0">
            @if($services->count() > 0)
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr class="text-muted small text-uppercase">
                            <th>NAMA</th>
                            <th>SATUAN</th>
                            <th>HARGA</th>
                            <th>ESTIMASI HARI</th>
                            <th>STATUS</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($services as $service)
                        <tr>
                            <td class="fw-semibold text-dark">{{ $service->name }}</td>
                            <td class="text-dark">{{ $service->unit ?? 'Per KG' }}</td>
                            <td class="text-dark">Rp {{ number_format($service->price_per_kg, 0, ',', '.') }}</td>
                            <td class="text-muted">{{ $service->estimated_days }} hari</td>
                            <td>
                                @if($service->is_active)
                                    <span class="badge rounded-pill bg-light-success text-success px-3 py-2">
                                        <i class="bi bi-check-circle-fill me-1 text-accent"></i>Aktif
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-light-secondary text-secondary px-3 py-2">
                                        <i class="bi bi-x-circle-fill me-1"></i>Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('services.edit', $service) }}" 
                                   class="btn-action edit me-2">
                                    <i class="bi bi-pencil-square me-1"></i>Edit
                                </a>
                                <form action="{{ route('services.destroy', $service) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus layanan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action delete">
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
                <button class="btn btn-gradient px-4 py-2" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                    <i class="bi bi-plus-circle me-1"></i>Tambah Layanan
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Modal: Tambah Layanan -->
    <div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="addServiceModalLabel">Tambah Layanan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('services.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Layanan</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Satuan</label>
                            <select name="unit" id="unitSelect" class="form-select" required>
                                <option value="Per KG">Per KG</option>
                                <option value="Per Helai">Per Helai</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Harga</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="price_per_kg" class="form-control" min="0" step="1" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Estimasi Hari</label>
                            <input type="number" name="estimated_days" class="form-control" min="1" value="1" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1" selected>Aktif</option>
                                <option value="0">Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-gradient">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Optional: adjust placeholder or label based on unit selection
    document.addEventListener('DOMContentLoaded', function() {
        const unitSelect = document.getElementById('unitSelect');
        if (!unitSelect) return;
        const priceInput = document.querySelector('input[name="price_per_kg"]');

        unitSelect.addEventListener('change', function() {
            if (!priceInput) return;
            // No automatic conversion needed; this is just to allow UI hints later
            if (this.value === 'Per Helai') {
                priceInput.setAttribute('placeholder', 'Harga per helai');
            } else {
                priceInput.setAttribute('placeholder', 'Harga per kg');
            }
        });
    });
</script>
@endsection
