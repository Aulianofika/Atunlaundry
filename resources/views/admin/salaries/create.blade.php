@extends('layouts.admin')

@section('title', 'Tambah Gaji Karyawan')
@section('page-title', 'Tambah Gaji Karyawan')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-user-plus me-2"></i>Form Tambah Gaji Karyawan
                    </h5>
                    <small class="text-muted">Sistem gaji berdasarkan kilogram laundry yang diselesaikan</small>
                </div>
                <div class="card-body p-4">
                    <!-- Info Alert -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-lg me-3"></i>
                            <div>
                                <strong>Info:</strong> Total Kg laundry selesai bulan 
                                {{ ['', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'][$currentMonth] }} 
                                {{ $currentYear }}: <strong>{{ number_format($monthlyKgCompleted, 2, ',', '.') }} Kg</strong>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('admin.salaries.store') }}" id="salaryForm">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="employee_name" class="form-label fw-semibold">Nama Karyawan <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('employee_name') is-invalid @enderror" 
                                       id="employee_name" name="employee_name" value="{{ old('employee_name') }}" 
                                       placeholder="Nama lengkap karyawan" required>
                                @error('employee_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="position" class="form-label fw-semibold">Posisi <span class="text-danger">*</span></label>
                                <select class="form-select @error('position') is-invalid @enderror" id="position" name="position" required>
                                    <option value="">Pilih Posisi</option>
                                    <option value="Operator Cuci" {{ old('position') == 'Operator Cuci' ? 'selected' : '' }}>Operator Cuci</option>
                                    <option value="Operator Setrika" {{ old('position') == 'Operator Setrika' ? 'selected' : '' }}>Operator Setrika</option>
                                    <option value="Operator Lipat" {{ old('position') == 'Operator Lipat' ? 'selected' : '' }}>Operator Lipat</option>
                                    <option value="Kurir" {{ old('position') == 'Kurir' ? 'selected' : '' }}>Kurir</option>
                                    <option value="Staff" {{ old('position') == 'Staff' ? 'selected' : '' }}>Staff</option>
                                    <option value="Supervisor" {{ old('position') == 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                                </select>
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
                                        <option value="{{ $i }}" {{ old('month', $currentMonth) == $i ? 'selected' : '' }}>
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

                        <hr class="my-4">
                        <h6 class="fw-bold text-primary mb-3"><i class="fas fa-calculator me-2"></i>Perhitungan Gaji</h6>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="total_kg_completed" class="form-label fw-semibold">
                                    Total Kg Diselesaikan <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('total_kg_completed') is-invalid @enderror" 
                                           id="total_kg_completed" name="total_kg_completed" 
                                           value="{{ old('total_kg_completed', 0) }}" 
                                           placeholder="0" min="0" step="0.01" required>
                                    <span class="input-group-text">Kg</span>
                                </div>
                                @error('total_kg_completed')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Jumlah kg laundry yang diselesaikan karyawan</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="rate_per_kg" class="form-label fw-semibold">
                                    Tarif per Kg <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('rate_per_kg') is-invalid @enderror" 
                                           id="rate_per_kg" name="rate_per_kg" 
                                           value="{{ old('rate_per_kg', 500) }}" 
                                           placeholder="500" min="0" step="50" required>
                                </div>
                                @error('rate_per_kg')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Upah per kilogram laundry</small>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="bonus" class="form-label fw-semibold">Bonus</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('bonus') is-invalid @enderror" 
                                           id="bonus" name="bonus" value="{{ old('bonus', 0) }}" 
                                           placeholder="0" min="0" step="1000">
                                </div>
                                @error('bonus')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Bonus tambahan (opsional)</small>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label fw-semibold">Catatan</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="2" 
                                      placeholder="Catatan tambahan (opsional)">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Calculation Preview -->
                        <div class="card bg-light border-0 mb-4">
                            <div class="card-body">
                                <h6 class="fw-bold mb-3"><i class="fas fa-receipt me-2"></i>Preview Perhitungan</h6>
                                <div class="row text-center">
                                    <div class="col-md-3">
                                        <div class="border rounded p-3 bg-white">
                                            <small class="text-muted d-block">Kg Selesai</small>
                                            <strong id="previewKg">0</strong> Kg
                                        </div>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-center justify-content-center">
                                        <span class="fs-4">×</span>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="border rounded p-3 bg-white">
                                            <small class="text-muted d-block">Tarif/Kg</small>
                                            Rp <strong id="previewRate">0</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-1 d-flex align-items-center justify-content-center">
                                        <span class="fs-4">+</span>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="border rounded p-3 bg-white">
                                            <small class="text-muted d-block">Bonus</small>
                                            Rp <strong id="previewBonus">0</strong>
                                        </div>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-center">
                                        <div class="border rounded p-3 bg-success text-white w-100">
                                            <small class="d-block">Total</small>
                                            <strong>Rp <span id="previewTotal">0</span></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kgInput = document.getElementById('total_kg_completed');
    const rateInput = document.getElementById('rate_per_kg');
    const bonusInput = document.getElementById('bonus');
    
    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }
    
    function updatePreview() {
        const kg = parseFloat(kgInput.value) || 0;
        const rate = parseFloat(rateInput.value) || 0;
        const bonus = parseFloat(bonusInput.value) || 0;
        const total = (kg * rate) + bonus;
        
        document.getElementById('previewKg').textContent = formatNumber(kg);
        document.getElementById('previewRate').textContent = formatNumber(rate);
        document.getElementById('previewBonus').textContent = formatNumber(bonus);
        document.getElementById('previewTotal').textContent = formatNumber(total);
    }
    
    kgInput.addEventListener('input', updatePreview);
    rateInput.addEventListener('input', updatePreview);
    bonusInput.addEventListener('input', updatePreview);
    
    // Initial update
    updatePreview();
});
</script>
@endpush
@endsection
