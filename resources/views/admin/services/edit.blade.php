@extends('layouts.admin')

@section('title', 'Edit Layanan')
@section('page-title', 'Edit Layanan')

@section('content')
<div class="container-fluid mt-3">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold text-accent mb-0">
            <i class="bi bi-pencil-square me-2"></i> Edit Layanan
        </h2>
        <a href="{{ route('services.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <!-- Card Form -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-primary-soft text-accent fw-semibold">
            <i class="bi bi-gear me-2"></i> Form Edit Layanan
        </div>
        <div class="card-body p-4">
            <form action="{{ route('services.update', $service->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Layanan</label>
                    <input type="text" name="name" class="form-control" value="{{ $service->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Harga (Rp)</label>
                    <input type="number" name="price" class="form-control" value="{{ $service->price }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Keterangan</label>
                    <textarea 
                        name="description" 
                        class="form-control @error('description') is-invalid @enderror" 
                        rows="3"
                        placeholder="Keterangan tambahan (opsional)">{{ old('description', $service->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="is_active" class="form-select @error('is_active') is-invalid @enderror">
                        <option value="1" {{ old('is_active', $service->is_active) ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ !old('is_active', $service->is_active) ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('is_active')
                        <div class="invalid-feedback small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary-soft px-4 py-2 fw-semibold rounded-pill">
                        <i class="bi bi-save me-2"></i> Update Layanan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
