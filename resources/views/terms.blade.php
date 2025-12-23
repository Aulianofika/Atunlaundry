@extends('layouts.app')

@section('title', 'Syarat & Ketentuan - Atun Laundry')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="text-center mb-5">
                <h1 class="display-5 fw-bold" style="color: var(--primary-green);">
                    <i class="fas fa-file-contract me-2"></i>Syarat & Ketentuan
                </h1>
                <p class="lead text-muted">Atun Laundry - Layanan Laundry Profesional</p>
            </div>

            <!-- Terms Card -->
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header py-4 text-white text-center" style="background: linear-gradient(135deg, #2E7D32, #1B5E20);">
                    <h4 class="mb-0 fw-bold">
                        <i class="fas fa-scroll me-2"></i>Ketentuan Layanan
                    </h4>
                </div>
                <div class="card-body p-4 p-md-5">
                    
                    <!-- Term 1 -->
                    <div class="term-item mb-4">
                        <div class="d-flex align-items-start">
                            <div class="term-number me-3">1</div>
                            <div>
                                <h5 class="fw-bold mb-2">Penyerahan & Pengambilan Barang</h5>
                                <p class="mb-0">Penyerahan dan pengambilan barang harus disertai <strong class="text-success">NOTA</strong>. Mintalah Nota penyerahan barang kepada petugas kami sebagai bukti transaksi.</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Term 2 -->
                    <div class="term-item mb-4">
                        <div class="d-flex align-items-start">
                            <div class="term-number me-3">2</div>
                            <div>
                                <h5 class="fw-bold mb-2">Bahan Pakaian Beresiko Luntur</h5>
                                <p class="mb-0">Mohon diberitahukan kepada petugas bahan pakaian yang beresiko luntur. Kami <strong class="text-danger">tidak bertanggung jawab</strong> atas kelunturan/kerusakan yang disebabkan bahan dasar kain.</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Term 3 -->
                    <div class="term-item mb-4">
                        <div class="d-flex align-items-start">
                            <div class="term-number me-3">3</div>
                            <div>
                                <h5 class="fw-bold mb-2">Batas Waktu Pengambilan</h5>
                                <p class="mb-0">Barang yang tidak diambil lebih dari <strong class="text-warning">30 hari</strong>, jika hilang atau rusak <strong class="text-danger">bukan menjadi tanggung jawab</strong> laundry.</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Term 4 -->
                    <div class="term-item mb-4">
                        <div class="d-flex align-items-start">
                            <div class="term-number me-3">4</div>
                            <div>
                                <h5 class="fw-bold mb-2">Keluhan Pelanggan</h5>
                                <p class="mb-0">Keluhan pelanggan kami terima selama <strong class="text-primary">1 x 24 Jam</strong> setelah barang diterima. Segera hubungi kami jika ada kendala.</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Term 5 -->
                    <div class="term-item mb-4">
                        <div class="d-flex align-items-start">
                            <div class="term-number me-3">5</div>
                            <div>
                                <h5 class="fw-bold mb-2">Metode Pembayaran</h5>
                                <p class="mb-0">Pembayaran adalah <strong class="text-success">TUNAI</strong> pada saat barang diserahkan atau paling lambat saat barang diterima.</p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Term 6 -->
                    <div class="term-item mb-4">
                        <div class="d-flex align-items-start">
                            <div class="term-number me-3">6</div>
                            <div>
                                <h5 class="fw-bold mb-2">Ganti Rugi Kerusakan</h5>
                                <p class="mb-0">Kerusakan oleh pihak laundry akan diganti <strong class="text-info">maksimal 10x</strong> dari jasa cucian yang dibayarkan.</p>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer bg-light py-4 text-center">
                    <p class="mb-2 text-muted">Dengan menggunakan layanan kami, Anda dianggap telah menyetujui syarat dan ketentuan di atas.</p>
                    <a href="{{ route('home') }}" class="btn btn-success">
                        <i class="fas fa-home me-2"></i>Kembali ke Beranda
                    </a>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="text-center mt-5">
                <p class="text-muted mb-2">Butuh bantuan? Hubungi kami:</p>
                <a href="https://wa.me/6281275667418" target="_blank" class="btn btn-outline-success">
                    <i class="fab fa-whatsapp me-2"></i>0812-7566-7418
                </a>
            </div>
        </div>
    </div>
</div>

<style>
.term-number {
    width: 40px;
    height: 40px;
    min-width: 40px;
    background: linear-gradient(135deg, #2E7D32, #1B5E20);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.1rem;
}

.term-item:hover .term-number {
    transform: scale(1.1);
    transition: transform 0.2s ease;
}

.term-item h5 {
    color: #1A237E;
}

.card {
    overflow: hidden;
}

.card-header {
    border-bottom: none;
}

.card-footer {
    border-top: 2px solid #E8F5E9;
}
</style>
@endsection
