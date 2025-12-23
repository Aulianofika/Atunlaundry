@extends('layouts.admin')

@section('title', 'Kelola Gaji Karyawan')
@section('page-title', 'Kelola Gaji Karyawan')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <h2 class="mb-0 fw-bold">Daftar Gaji Karyawan</h2>
                <span class="badge bg-info">Sistem Berbasis Kg</span>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('admin.salaries.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Gaji
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-icon bg-primary">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="stats-number">Rp {{ number_format($totalSalaries, 0, ',', '.') }}</div>
                <div class="stats-label">Total Gaji Dibayarkan</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <div class="stats-icon bg-success">
                    <i class="fas fa-weight"></i>
                </div>
                <div class="stats-number">{{ number_format($totalKgCompleted, 2, ',', '.') }} Kg</div>
                <div class="stats-label">Total Kg Diselesaikan</div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light">
            <h6 class="mb-0 fw-semibold">
                <i class="fas fa-history me-2"></i>Riwayat Gaji
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Bulan/Tahun</th>
                        <th>Nama Karyawan</th>
                        <th>Posisi</th>
                        <th class="text-center">Kg Selesai</th>
                        <th class="text-end">Tarif/Kg</th>
                        <th class="text-end">Bonus</th>
                        <th class="text-end">Total Gaji</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salaries as $salary)
                        <tr>
                            <td><strong>{{ $salary->month_name }} {{ $salary->year }}</strong></td>
                            <td>{{ $salary->employee_name }}</td>
                            <td>
                                <span class="badge badge-light-info">{{ $salary->position }}</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary">{{ number_format($salary->total_kg_completed, 2, ',', '.') }} Kg</span>
                            </td>
                            <td class="text-end">Rp {{ number_format($salary->rate_per_kg, 0, ',', '.') }}</td>
                            <td class="text-end">
                                @if($salary->bonus > 0)
                                    <span class="text-success">+Rp {{ number_format($salary->bonus, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <strong class="text-primary">Rp {{ number_format($salary->total_salary, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                @if($salary->status === 'paid')
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Sudah Dibayar</span>
                                @else
                                    <span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Pending</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.salaries.edit', $salary) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.salaries.destroy', $salary) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus data gaji ini?')" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @if($salary->notes)
                        <tr class="table-secondary">
                            <td colspan="9" class="py-1 px-4">
                                <small class="text-muted"><i class="fas fa-sticky-note me-1"></i>Catatan: {{ $salary->notes }}</small>
                            </td>
                        </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox me-2"></i>Belum ada data gaji
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $salaries->links('pagination::bootstrap-4') }}
    </div>

    <!-- Info Card -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <h6 class="fw-bold text-primary"><i class="fas fa-info-circle me-2"></i>Cara Perhitungan Gaji</h6>
            <p class="mb-0 text-muted">
                <strong>Total Gaji = (Kg Selesai × Tarif/Kg) + Bonus</strong><br>
                Sistem penggajian berdasarkan jumlah kilogram laundry yang berhasil diselesaikan oleh karyawan.
            </p>
        </div>
    </div>
</div>
@endsection
