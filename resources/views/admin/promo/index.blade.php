@extends('layouts.admin')

@section('title', 'Daftar Promosi')

@section('content')
<div class="container-fluid mt-3">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-purple-soft text-purple rounded-top-4 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-tags fs-4"></i>
                <h4 class="mb-0 fw-semibold">Daftar Promosi</h4>
            </div>
            <a href="{{ route('promotions.create') }}" class="btn btn-gradient d-flex align-items-center">
                <i class="bi bi-plus-lg me-2"></i> Tambah Promosi
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light small text-uppercase text-muted">
                        <tr>
                            <th>Judul</th>
                            <th style="min-width:320px;">Deskripsi</th>
                            <th>Diskon (%)</th>
                            <th>Nominal</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($promotions as $promo)
                            <tr>
                                <td class="fw-semibold">{{ $promo->title }}</td>
                                <td>
                                    <div class="text-muted small text-truncate" style="max-width:360px;">{{ $promo->description }}</div>
                                </td>
                                <td>{{ $promo->discount_percentage ?? '-' }}</td>
                                <td>Rp {{ number_format($promo->discount_amount ?? 0, 0, ',', '.') }}</td>
                                <td class="small text-muted">{{ $promo->start_date }}<br>— {{ $promo->end_date }}</td>
                                <td>
                                    @if($promo->is_active)
                                        <span class="badge rounded-pill bg-light-success text-success px-3 py-2">
                                            <i class="bi bi-check-circle-fill me-1"></i>Aktif
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-light-secondary text-secondary px-3 py-2">
                                            <i class="bi bi-x-circle-fill me-1"></i>Nonaktif
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('promotions.edit', $promo->id) }}" class="btn-action edit me-2">
                                        <i class="bi bi-pencil-square me-1"></i>Edit
                                    </a>
                                    <form action="{{ route('promotions.destroy', $promo->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus promosi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn-action delete">
                                            <i class="bi bi-trash me-1"></i>Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-4">
                                <div class="mb-3">Belum ada promosi</div>
                                <a href="{{ route('promotions.create') }}" class="btn btn-gradient">Tambah Promosi</a>
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $promotions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
