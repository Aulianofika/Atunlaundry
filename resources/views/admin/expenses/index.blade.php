@extends('layouts.admin')

@section('title', 'Kelola Pengeluaran')
@section('page-title', 'Kelola Pengeluaran')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="d-flex align-items-center gap-3">
                <h2 class="mb-0 fw-bold">Daftar Pengeluaran</h2>
            </div>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('admin.expenses.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Tambah Pengeluaran
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
                    <i class="fas fa-receipt"></i>
                </div>
                <div class="stats-number">{{ number_format($totalExpenses, 0, ',', '.') }}</div>
                <div class="stats-label">Total Pengeluaran</div>
            </div>
        </div>
    </div>

    {{-- Filter/Search Form --}}
    <form method="GET" class="row g-2 mb-3 align-items-end">
        <div class="col-md-3">
            <label class="form-label">Kategori</label>
            <select name="category" class="form-select">
                <option value="">Semua</option>
                <option value="Bahan Baku" {{ request('category')=='Bahan Baku'?'selected':'' }}>Bahan Baku</option>
                <option value="Utilitas" {{ request('category')=='Utilitas'?'selected':'' }}>Utilitas</option>
                <option value="Operasional" {{ request('category')=='Operasional'?'selected':'' }}>Operasional</option>
                <option value="Maintenance" {{ request('category')=='Maintenance'?'selected':'' }}>Maintenance</option>
                <option value="Lainnya" {{ request('category')=='Lainnya'?'selected':'' }}>Lainnya</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Bulan</label>
            <select name="month" class="form-select">
                <option value="">Semua</option>
                @for($m=1;$m<=12;$m++)
                    <option value="{{ $m }}" {{ request('month')==$m?'selected':'' }}>{{ ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'][$m] }}</option>
                @endfor
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Tahun</label>
            <input type="number" name="year" class="form-control" value="{{ request('year') }}" min="2020" max="2100" placeholder="Tahun">
        </div>
        <div class="col-md-3">
            <label class="form-label">Cari</label>
            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Judul/Deskripsi">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i>Filter</button>
        </div>
    </form>

    {{-- Monthly/Yearly Summary --}}
    <div class="mb-4">
        <h6 class="fw-semibold mb-2">Ringkasan Bulanan/Tahunan</h6>
        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Bulan</th>
                        <th>Tahun</th>
                        <th>Total Pengeluaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($monthlySummary as $row)
                        <tr>
                            <td>{{ ['','Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'][$row->month] }}</td>
                            <td>{{ $row->year }}</td>
                            <td>Rp {{ number_format($row->total, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-light">
            <h6 class="mb-0 fw-semibold">Riwayat Pengeluaran</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expenses as $expense)
                        <tr>
                            <td>{{ $expense->expense_date->format('d/m/Y') }}</td>
                            <td><strong>{{ $expense->title }}</strong></td>
                            <td>
                                <span class="badge badge-light-primary">{{ $expense->category }}</span>
                            </td>
                            <td>{{ Str::limit($expense->description, 30) }}</td>
                            <td>
                                <strong>Rp {{ number_format($expense->amount, 0, ',', '.') }}</strong>
                            </td>
                            <td>
                                <a href="{{ route('admin.expenses.edit', $expense) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.expenses.destroy', $expense) }}" method="POST" style="display:inline;">
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
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox me-2"></i>Belum ada pengeluaran
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $expenses->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
