@extends('layouts.admin')

@section('title', 'Tambah Promosi')

@section('content')
<div class="container mt-4">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header text-center bg-purple text-white rounded-top-4 py-3">
            <h4 class="mb-0 fw-bold">
                <i class="bi bi-plus-circle me-2"></i> Tambah Promosi Baru
            </h4>
            <p class="mb-0 small text-light opacity-75">
                Buat promosi diskon untuk pelanggan
            </p>
        </div>

        <div class="card-body p-4">
            <form action="{{ route('promotions.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Judul Promosi</label>
                        <input type="text" name="title" class="form-control" placeholder="Contoh: Diskon Akhir Tahun" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Deskripsi</label>
                        <input type="text" name="description" class="form-control" placeholder="Tuliskan deskripsi singkat">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Diskon (%)</label>
                        <input type="number" step="0.01" name="discount_percentage" class="form-control" placeholder="Misal: 15">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nominal Diskon (Rp)</label>
                        <input type="number" step="0.01" name="discount_amount" class="form-control" placeholder="Misal: 5000">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Mulai</label>
                        <input type="date" name="start_date" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Berakhir</label>
                        <input type="date" name="end_date" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <a href="{{ route('promotions.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-purple-soft px-4">
                        <i class="bi bi-save me-1"></i> Simpan Promosi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
