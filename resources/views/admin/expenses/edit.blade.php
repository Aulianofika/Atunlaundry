@extends('layouts.admin')

@section('title', 'Edit Pengeluaran')
@section('page-title', 'Edit Pengeluaran')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-bold">Edit Pengeluaran</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.expenses.update', $expense) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">Judul Pengeluaran <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                   id="title" name="title" value="{{ old('title', $expense->title) }}" 
                                   placeholder="Contoh: Pembelian Sabun" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="category" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                                <select class="form-select @error('category') is-invalid @enderror" 
                                        id="category" name="category" required>
                                    <option value="">Pilih Kategori</option>
                                    <option value="Bahan Baku" {{ old('category', $expense->category) === 'Bahan Baku' ? 'selected' : '' }}>Bahan Baku</option>
                                    <option value="Utilitas" {{ old('category', $expense->category) === 'Utilitas' ? 'selected' : '' }}>Utilitas (Listrik, Air, Internet)</option>
                                    <option value="Operasional" {{ old('category', $expense->category) === 'Operasional' ? 'selected' : '' }}>Operasional</option>
                                    <option value="Maintenance" {{ old('category', $expense->category) === 'Maintenance' ? 'selected' : '' }}>Maintenance & Perbaikan</option>
                                    <option value="Lainnya" {{ old('category', $expense->category) === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="amount" class="form-label fw-semibold">Jumlah (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('amount') is-invalid @enderror" 
                                       id="amount" name="amount" value="{{ old('amount', $expense->amount) }}" 
                                       placeholder="0" min="0" step="1000" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="expense_date" class="form-label fw-semibold">Tanggal Pengeluaran <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('expense_date') is-invalid @enderror" 
                                   id="expense_date" name="expense_date" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required>
                            @error('expense_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Deskripsi (Opsional)</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Tuliskan deskripsi pengeluaran...">{{ old('description', $expense->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.expenses.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
