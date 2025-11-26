@extends('layouts.admin')

@section('title', 'Tambah Gaji Karyawan')
@section('page-title', 'Tambah Gaji Karyawan')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-bold">Form Tambah Gaji Karyawan</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.salaries.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="employee_name" class="form-label fw-semibold">Nama Karyawan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('employee_name') is-invalid @enderror" 
                                       id="employee_name" name="employee_name" value="{{ old('employee_name') }}" 
                                       placeholder="Nama lengkap" required>
                                @error('employee_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="position" class="form-label fw-semibold">Posisi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('position') is-invalid @enderror" 
                                       id="position" name="position" value="{{ old('position') }}" 
                                       placeholder="Contoh: Manager, Staff, Operator" required>
                                @error('position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="month" class="form-label fw-semibold">Bulan <span class="text-danger">*</span></label>
                                <select class="form-select @error('month') is-invalid @enderror" id="month" name="month" required>
                                    <option value="">Pilih Bulan</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ old('month') == $i ? 'selected' : '' }}>
                                            {{ ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$i] }}
                                        </option>
                                    @endfor
                                </select>
                                @error('month')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="year" class="form-label fw-semibold">Tahun <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('year') is-invalid @enderror" 
                                       id="year" name="year" value="{{ old('year', date('Y')) }}" 
                                       min="2020" max="2100" required>
                                @error('year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="status" class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ old('status') === 'paid' ? 'selected' : '' }}>Sudah Dibayar</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="base_salary" class="form-label fw-semibold">Gaji Pokok (Rp) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('base_salary') is-invalid @enderror" 
                                       id="base_salary" name="base_salary" value="{{ old('base_salary') }}" 
                                       placeholder="0" min="0" step="1000" required>
                                @error('base_salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="allowance" class="form-label fw-semibold">Tunjangan (Rp)</label>
                                <input type="number" class="form-control @error('allowance') is-invalid @enderror" 
                                       id="allowance" name="allowance" value="{{ old('allowance', 0) }}" 
                                       placeholder="0" min="0" step="1000">
                                @error('allowance')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="deduction" class="form-label fw-semibold">Potongan (Rp)</label>
                            <input type="number" class="form-control @error('deduction') is-invalid @enderror" 
                                   id="deduction" name="deduction" value="{{ old('deduction', 0) }}" 
                                   placeholder="0" min="0" step="1000">
                            @error('deduction')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-light">
                            <small><strong>Total Gaji = Gaji Pokok + Tunjangan - Potongan</strong></small>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('admin.salaries.index') }}" class="btn btn-outline-secondary">
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
