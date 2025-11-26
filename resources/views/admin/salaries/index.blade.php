@extends('layouts.admin')

@section('title', 'Kelola Gaji Karyawan')
@section('page-title', 'Kelola Gaji Karyawan')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <h2 class="mb-0 fw-bold">Daftar Gaji Karyawan</h2>
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
                <div class="stats-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <div class="stats-number">{{ number_format($totalSalaries, 0, ',', '.') }}</div>
                <div class="stats-label">Total Gaji Karyawan</div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light">
            <h6 class="mb-0 fw-semibold">Riwayat Gaji</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Bulan/Tahun</th>
                        <th>Nama Karyawan</th>
                        <th>Posisi</th>
                        <th>Gaji Pokok</th>
                        <th>Total Gaji</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salaries as $salary)
                        <tr>
                            <td><strong>{{ $salary->month }}/{{ $salary->year }}</strong></td>
                            <td>{{ $salary->employee_name }}</td>
                            <td>
                                <span class="badge badge-light-info">{{ $salary->position }}</span>
                            </td>
                            <td>Rp {{ number_format($salary->base_salary, 0, ',', '.') }}</td>
                            <td>
                                <strong>Rp {{ number_format($salary->total_salary, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                @if($salary->status === 'paid')
                                    <span class="badge bg-success">Sudah Dibayar</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.salaries.edit', $salary) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.salaries.destroy', $salary) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
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
</div>
@endsection
