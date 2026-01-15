@extends('user.layouts.app')

@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                {{-- Judul Halaman --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0"><i class="bi bi-receipt me-2"></i>Invoice Penyewaan</h4>
                    <a href="{{ route('user.riwayat') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>

                <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                    <div class="card-body p-4">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <p class="text-muted mb-1 small">Kode Invoice</p>
                                <h5 class="fw-bold text-primary">{{ $invoice->kode_invoice }}</h5>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <p class="text-muted mb-1 small">Status Pembayaran</p>
                                <span
                                    class="badge {{ $invoice->status === 'lunas' ? 'bg-success' : 'bg-warning text-dark' }} py-2 px-3">
                                    <i
                                        class="bi {{ $invoice->status === 'lunas' ? 'bi-check-circle' : 'bi-clock-history' }} me-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                                </span>
                            </div>
                        </div>

                        <div class="bg-light p-3 rounded-3 mb-4">
                            <div class="row g-3">
                                <div class="col-6 col-md-3">
                                    <small class="text-muted d-block">Mobil</small>
                                    <span class="fw-semibold">{{ $invoice->rental->mobil->nama_mobil }}</span>
                                </div>
                                <div class="col-6 col-md-3 text-md-center">
                                    <small class="text-muted d-block">Lama Sewa</small>
                                    <span class="fw-semibold">{{ $invoice->rental->lama_sewa }} Hari</span>
                                </div>
                                <div class="col-6 col-md-3 text-md-center">
                                    <small class="text-muted d-block">Mulai</small>
                                    <span
                                        class="fw-semibold">{{ \Carbon\Carbon::parse($invoice->rental->tgl_sewa)->format('d/m/Y') }}</span>
                                </div>
                                <div class="col-6 col-md-3 text-md-end">
                                    <small class="text-muted d-block">Kembali</small>
                                    <span
                                        class="fw-semibold">{{ \Carbon\Carbon::parse($invoice->rental->tgl_kembali)->format('d/m/Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Metode Pembayaran:</span>
                            <span class="fw-bold text-uppercase">{{ $invoice->metode_pembayaran }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="mb-0">Total Tagihan:</h5>
                            <h3 class="text-success fw-bold mb-0">
                                Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}
                            </h3>
                        </div>

                        <hr class="my-4">

                        <div class="row g-3">
                            {{-- TOMBOL CETAK (Pilihan Pertama) --}}
                            <div class="col-md-6">
                                <a href="{{ route('user.rental.cetak', $invoice->id) }}" target="_blank"
                                    class="btn btn-dark w-100 py-2 fw-bold">
                                    <i class="bi bi-printer me-2"></i> Cetak Struk Tagihan
                                </a>
                            </div>

                            @if ($invoice->status !== 'lunas')
                                {{-- TOMBOL LANJUT PEMBAYARAN (Collapse Toggle) --}}
                                <div class="col-md-6">
                                    @if (!$invoice->bukti_bayar)
                                        <button class="btn btn-primary w-100 py-2 fw-bold" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseBayar">
                                            Lanjut Pembayaran <i class="bi bi-chevron-down ms-1"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-success w-100 py-2 fw-bold" disabled>
                                            <i class="bi bi-check-all me-1"></i> Sudah Upload Bukti
                                        </button>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- PANEL UPLOAD PEMBAYARAN --}}
                        <div class="collapse {{ $invoice->bukti_bayar ? 'show' : '' }} mt-4" id="collapseBayar">
                            @if ($invoice->bukti_bayar)
                                <div class="alert alert-info border-0 shadow-sm">
                                    <div class="d-flex">
                                        <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                                        <div>
                                            <h6 class="fw-bold mb-1">Bukti Sudah Dikirim</h6>
                                            <p class="small mb-0">Admin akan memverifikasi pembayaran Anda. Silakan cek
                                                riwayat secara berkala.</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="card card-body bg-light border-0 shadow-sm">
                                    @if ($invoice->metode_pembayaran !== 'office')
                                        <h6 class="fw-bold mb-3"><i class="bi bi-cloud-arrow-up me-2"></i>Upload Bukti
                                            Transfer</h6>
                                        <form action="{{ route('user.invoice.upload', $invoice->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="mb-3">
                                                <input type="file" name="bukti_bayar" class="form-control"
                                                    accept="image/*" required>
                                                <div class="form-text">Format file: JPG, PNG, JPEG (Maks. 2MB)</div>
                                            </div>
                                            <button class="btn btn-primary w-100 fw-bold">Kirim Konfirmasi
                                                Pembayaran</button>
                                        </form>
                                    @else
                                        <div class="text-center py-2">
                                            <i class="bi bi-geo-alt-fill text-danger fs-2 mb-2"></i>
                                            <h6 class="fw-bold">Bayar di Kantor</h6>
                                            <p class="small text-muted mb-0">Silakan kunjungi kantor kami untuk melakukan
                                                pembayaran tunai.</p>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        {{-- NAVIGASI SETELAH LUNAS --}}
                        @if ($invoice->status === 'lunas')
                            <div class="alert alert-success border-0 shadow-sm mt-4 text-center">
                                <i class="bi bi-check-circle-fill fs-3 d-block mb-2"></i>
                                <h6 class="fw-bold">Pembayaran Selesai</h6>
                                <p class="small mb-1">Terima kasih! Silakan ambil kunci mobil di lokasi RentsBill.</p>

                                {{-- INFORMASI KONTAK KARYAWAN --}}
                                <p class="small">Atau bisa menghubungi sobat RentsBill yang kalian pilih: <br>
                                    <a href="https://wa.me/{{ $invoice->rental->karyawan->no_telp }}" target="_blank"
                                        class="fw-bold text-decoration-none">
                                        <i class="bi bi-whatsapp"></i> {{ $invoice->rental->karyawan->no_telp }}
                                        ({{ $invoice->rental->karyawan->nama }})
                                    </a>
                                </p>

                                <div class="d-flex gap-2 mt-3">
                                    <a href="{{ route('user.dashboard') }}"
                                        class="btn btn-sm btn-outline-success w-100 text-decoration-none">Dashboard</a>
                                    <a href="{{ route('user.riwayat') }}"
                                        class="btn btn-sm btn-success w-100 text-decoration-none">Lihat Riwayat</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
