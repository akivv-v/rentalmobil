@extends('admin.layouts.app')

@section('content')
<style>
    /* Sinkronisasi dengan Elegant White Theme */
    body { background-color: #f8f9fa; }
    
    .card-stats {
        background: #ffffff;
        border-radius: 20px;
        padding: 25px;
        border: 1px solid #eef2f7;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        transition: transform 0.3s ease;
    }

    .card-stats:hover {
        transform: translateY(-5px);
    }

    .icon-shape {
        width: 60px;
        height: 60px;
        background: #f1f5f9;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .card-table {
        background: #ffffff;
        border-radius: 20px;
        border: 1px solid #eef2f7;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }

    .card-table .card-header {
        background: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        padding: 20px 25px;
    }

    .table thead th {
        background: #f8fafc;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        color: #64748b;
        border: none;
        padding: 15px 25px;
    }

    .table tbody td {
        padding: 18px 25px;
        color: #334155;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    .badge-soft {
        padding: 8px 16px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 0.75rem;
    }
    
    .bg-soft-blue { background: #eff6ff; color: #2563eb; }
    .bg-soft-green { background: #f0fdf4; color: #16a34a; }
    .bg-soft-orange { background: #fff7ed; color: #ea580c; }

    .btn-view-all {
        background: #f8fafc;
        color: #475569;
        font-weight: 600;
        font-size: 0.85rem;
        border: 1px solid #e2e8f0;
        padding: 10px 20px;
        border-radius: 12px;
        transition: 0.3s;
    }

    .btn-view-all:hover {
        background: #1e293b;
        color: #ffffff;
    }
</style>

<div class="container-fluid py-4">
    {{-- Header --}}
    <div class="mb-5">
        <h2 class="fw-bold text-dark mb-1">Dashboard Admin</h2>
        <p class="text-muted">Selamat datang kembali, <span class="fw-semibold text-primary">{{ auth()->user()->name }}</span>.</p>
    </div>

    {{-- Stats Cards --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card-stats d-flex align-items-center">
                <div class="icon-shape bg-soft-blue text-primary me-3">
                    <i class="bi bi-car-front"></i>
                </div>
                <div>
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Total Mobil</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalMobil }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-stats d-flex align-items-center">
                <div class="icon-shape bg-soft-orange text-warning me-3">
                    <i class="bi bi-people"></i>
                </div>
                <div>
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Total Pelanggan</h6>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalPenyewa }}</h3>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-stats d-flex align-items-center">
                <div class="icon-shape bg-soft-green text-success me-3">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div>
                    <h6 class="text-muted small text-uppercase fw-bold mb-1">Total Pendapatan</h6>
                    <h3 class="fw-bold mb-0 text-dark">Rp {{ number_format($pendapatan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Tabel Transaksi Terbaru (Dahulukan yang paling penting) --}}
        <div class="col-12 mb-5">
            <div class="card-table">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">Aktivitas Rental Terbaru</h5>
                    <a href="{{ route('admin.rental.index') }}" class="btn btn-view-all">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Mobil</th>
                                <th>Penyewa</th>
                                <th>Durasi</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pemasukanTerbaru as $pm)
                            <tr>
                                <td class="fw-semibold text-dark">{{ $pm->mobil->nama_mobil ?? 'N/A' }}</td>
                                <td>{{ $pm->penyewa->nama ?? 'N/A' }}</td>
                                <td><span class="text-muted">{{ $pm->lama_sewa }} Hari</span></td>
                                <td class="fw-bold text-dark">Rp {{ number_format($pm->total_harga, 0, ',', '.') }}</td>
                                <td>
                                    @if ($pm->status == 'booking')
                                        <span class="badge-soft bg-soft-orange">Menunggu</span>
                                    @else
                                        <span class="badge-soft bg-soft-green">Lunas</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Row Kedua: Mobil & Penyewa Terbaru --}}
        <div class="col-lg-7 mb-4">
            <div class="card-table">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">Mobil Baru Ditambahkan</h5>
                    <a href="{{ route('admin.mobil.index') }}" class="btn btn-view-all">Daftar Mobil</a>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0 text-nowrap">
                        <thead>
                            <tr>
                                <th>Nama Unit</th>
                                <th>Plat Nomor</th>
                                <th>Harga/Hari</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mobilTerbaru as $m)
                            <tr>
                                <td class="fw-semibold text-dark">{{ $m->nama_mobil }}</td>
                                <td><span class="badge bg-light text-dark border">{{ $m->plat_nomor }}</span></td>
                                <td class="fw-bold">Rp {{ number_format($m->harga_sewa, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-4">
            <div class="card-table">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">Customer Baru</h5>
                    <a href="{{ route('admin.penyewa.index') }}" class="btn btn-view-all">Detail</a>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Kontak</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penyewaTerbaru as $p)
                            <tr>
                                <td class="fw-semibold text-dark">{{ $p->nama }}</td>
                                <td class="text-muted small">{{ $p->no_telp }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection