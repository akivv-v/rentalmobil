@extends('admin.layouts.app')

@section('content')
<style>
    /* Ubah ke White Elegant Theme */
    body { background-color: #f8f9fa; }
    
    .card-custom {
        background: #ffffff;
        border-radius: 15px;
        padding: 25px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05); /* Shadow lebih halus */
        border: 1px solid #e9ecef;
        color: #334155;
    }

    .card-title-icon {
        width: 40px;
        height: 40px;
        background: rgba(13, 110, 253, 0.1); /* Biru muda transparan */
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        color: #0d6efd;
    }

    .info-label {
        color: #64748b; /* Abu-abu profesional */
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 2px;
        font-weight: 700;
    }

    .info-value {
        font-weight: 600;
        font-size: 1.05rem;
        margin-bottom: 15px;
        color: #1e293b;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.8rem;
    }

    /* Badge Warna Soft */
    .bg-warning-soft { background-color: #fffbeb; color: #92400e; }
    .bg-info-soft { background-color: #f0f9ff; color: #075985; }
    .bg-success-soft { background-color: #f0fdf4; color: #166534; }
    .bg-danger-soft { background-color: #fef2f2; color: #991b1b; }

    .btn-action {
        background: #1e293b; /* Hitam elegan */
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 600;
        color: white;
        transition: .3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-action:hover {
        background: #334155;
        transform: translateY(-2px);
        color: white;
    }

    .img-preview {
        border: 1px solid #e2e8f0;
        transition: .3s;
        cursor: pointer;
        border-radius: 10px;
    }

    .img-preview:hover {
        transform: scale(1.02);
        border-color: #0d6efd;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    
    .text-info-custom { color: #0d6efd; font-weight: 700; }
    hr.border-secondary-light { border-top: 1px solid #f1f5f9; opacity: 1; }
</style>

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-dark fw-bold mb-0">Detail Transaksi <span class="text-info-custom">#{{ $rental->id }}</span></h2>
        <a href="{{ route('admin.rental.index') }}" class="btn btn-outline-dark rounded-pill px-4 shadow-sm bg-white">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="row g-4">
        {{-- ================= RINGKASAN STATUS ================= --}}
        <div class="col-12">
            <div class="card-custom">
                <div class="row text-center align-items-center">
                    <div class="col-md-3 border-end">
                        <div class="info-label">Status Rental</div>
                        <span class="badge status-badge {{ $rental->status == 'selesai' ? 'bg-success-soft' : ($rental->status == 'disewa' ? 'bg-info-soft' : 'bg-warning-soft') }}">
                            {{ strtoupper($rental->status) }}
                        </span>
                    </div>
                    <div class="col-md-3 border-end">
                        <div class="info-label">Metode Pembayaran</div>
                        <div class="info-value text-uppercase mb-0">{{ $rental->invoice->metode_pembayaran ?? 'OFFICE' }}</div>
                    </div>
                    <div class="col-md-3 border-end">
                        <div class="info-label">Total Tagihan</div>
                        <div class="info-value text-success mb-0">Rp {{ number_format($rental->total_harga, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-md-3">
                        <div class="info-label">Status Invoice</div>
                        <span class="badge status-badge {{ ($rental->invoice->status ?? '') == 'lunas' ? 'bg-success-soft' : 'bg-danger-soft' }}">
                            {{ strtoupper($rental->invoice->status ?? 'PENDING') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= DATA PENYEWA & MOBIL ================= --}}
        <div class="col-lg-7">
            <div class="card-custom mb-4">
                <div class="d-flex align-items-center mb-4">
                    <div class="card-title-icon"><i class="bi bi-person-badge"></i></div>
                    <h5 class="mb-0 fw-bold">Profil Penyewa</h5>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-label">Nama Lengkap</div>
                        <div class="info-value">{{ $rental->penyewa->nama }}</div>
                        
                        <div class="info-label">Kontak / WhatsApp</div>
                        <div class="info-value">{{ $rental->penyewa->no_telp }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Email</div>
                        <div class="info-value text-lowercase">{{ $rental->penyewa->email }}</div>
                        
                        <div class="info-label">Pekerjaan</div>
                        <div class="info-value">{{ $rental->penyewa->pekerjaan }}</div>
                    </div>
                    <div class="col-12">
                        <div class="info-label">Alamat</div>
                        <div class="info-value mb-0">{{ $rental->penyewa->alamat }}</div>
                    </div>
                </div>
            </div>

            <div class="card-custom">
                <div class="d-flex align-items-center mb-4">
                    <div class="card-title-icon"><i class="bi bi-car-front"></i></div>
                    <h5 class="mb-0 fw-bold">Informasi Unit & Waktu</h5>
                </div>
                <div class="row">
                    <div class="col-md-6 border-end">
                        <div class="info-label">Unit Mobil</div>
                        <div class="info-value">{{ $rental->mobil->nama_mobil }} ({{ $rental->mobil->plat_nomor }})</div>
                        
                        <div class="info-label">Penanggungjawab</div>
                        <div class="info-value text-primary">{{ $rental->karyawan->nama_karyawan ?? $rental->karyawan->nama }}</div>
                    </div>
                    <div class="col-md-6 ps-md-4">
                        <div class="info-label">Durasi Sewa</div>
                        <div class="info-value">{{ $rental->lama_sewa }} Hari</div>
                        
                        <div class="info-label">Periode</div>
                        <div class="info-value mb-0">
                            {{ date('d M Y', strtotime($rental->tgl_sewa)) }} - {{ date('d M Y', strtotime($rental->tgl_kembali)) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= LAMPIRAN & KONFIRMASI ================= --}}
        <div class="col-lg-5">
            <div class="card-custom h-100">
                <div class="d-flex align-items-center mb-4">
                    <div class="card-title-icon"><i class="bi bi-image"></i></div>
                    <h5 class="mb-0 fw-bold">Verifikasi Dokumen</h5>
                </div>

                <div class="mb-4">
                    <div class="info-label mb-2">Foto KTP Penyewa</div>
                    <img src="{{ asset('uploads/ktp/' . $rental->penyewa->foto_ktp) }}" 
                         class="img-fluid img-preview w-100" 
                         style="height: 180px; object-fit: cover;" 
                         onclick="window.open(this.src)">
                </div>

                <div class="mb-4">
                    <div class="info-label mb-2">Bukti Pembayaran</div>
                    @if($rental->invoice && $rental->invoice->bukti_bayar)
                        <img src="{{ asset('uploads/bukti/' . $rental->invoice->bukti_bayar) }}" 
                             class="img-fluid img-preview w-100" 
                             style="height: 180px; object-fit: cover;"
                             onclick="window.open(this.src)">
                    @else
                        <div class="bg-light text-center py-4 rounded border border-dashed">
                            <i class="bi bi-slash-circle d-block mb-2 fs-3 text-muted"></i>
                            <span class="text-muted small">Belum ada bukti upload</span>
                        </div>
                    @endif
                </div>

                @if($rental->status == 'booking' || ($rental->invoice && $rental->invoice->status == 'pending'))
                <div class="mt-auto">
                    <hr class="border-secondary-light">
                    <h6 class="text-dark mb-3 small fw-bold text-uppercase">Tindakan Admin</h6>
                    <form action="{{ route('admin.rental.konfirmasi', $rental->id) }}" method="POST">
                        @csrf
                        @if($rental->invoice && $rental->invoice->metode_pembayaran == 'office')
                            <div class="mb-3">
                                <label class="info-label">Input Uang Diterima (Rp)</label>
                                <input type="number" name="nominal" class="form-control border-light-subtle bg-light" required placeholder="Contoh: 500000">
                            </div>
                        @endif
                        <button type="submit" class="btn-action w-100 text-center shadow-sm">
                            <i class="bi bi-check2-circle me-2"></i>Konfirmasi & Aktifkan
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection