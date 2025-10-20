@extends('layouts.admin')

@section('title', 'Daftar Promosi')

@section('content')
<div class="container mt-4">
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-header bg-purple text-white rounded-top-4 d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold"><i class="bi bi-tags me-2"></i> Daftar Promosi</h4>
            <a href="{{ route('promotions.create') }}" class="btn btn-light text-purple fw-semibold">
                <i class="bi bi-plus-circle"></i> Tambah Promosi
            </a>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Judul</th>
                            <th>Deskripsi</th>
                            <th>Diskon (%)</th>
                            <th>Nominal (Rp)</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($promotions as $promo)
                            <tr>
                                <td>{{ $promo->title }}</td>
                                <td>{{ $promo->description }}</td>
                                <td>{{ $promo->discount_percentage ?? '-' }}</td>
                                <td>{{ number_format($promo->discount_amount ?? 0, 0, ',', '.') }}</td>
                                <td>{{ $promo->start_date }} - {{ $promo->end_date }}</td>
                                <td>
                                    @if($promo->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('promotions.edit', $promo->id) }}" class="btn btn-sm btn-warning text-white">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('promotions.destroy', $promo->id) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus promosi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center text-muted">Belum ada promosi</td></tr>
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
