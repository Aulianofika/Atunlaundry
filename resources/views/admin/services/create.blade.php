@extends('layouts.admin')

@section('title', 'Tambah Layanan')
@section('page-title', 'Tambah Layanan')

@section('content')
<div class="container-fluid mt-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold text-accent mb-0">
            <i class="bi bi-plus-circle-fill me-2"></i> Tambah Layanan Baru
        </h2>
        <a href="{{ route('services.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <!-- Card Form -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-primary-soft text-accent fw-semibold">
            <i class="bi bi-gear me-2"></i> Form Tambah Layanan
        </div>
        <div class="card-body p-4">
            <form action="{{ route('services.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Layanan</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Cuci Kering" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Harga (Rp)</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="price_per_kg" class="form-control" placeholder="Contoh: 10000" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Satuan</label>
                    <select name="unit" class="form-select">
                        <option value="Per KG">Per KG</option>
                        <option value="Per Helai">Per Helai</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Estimasi Hari</label>
                    <input type="number" name="estimated_days" class="form-control" placeholder="Contoh: 2" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Keterangan</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Keterangan tambahan (opsional)"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="is_active" class="form-select">
                        <option value="1" selected>Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary-soft px-4 py-2 fw-semibold rounded-pill">
                        <i class="bi bi-save me-2"></i> Simpan Layanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
