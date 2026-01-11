@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold" style="color:#1e3038;">
            Selamat datang, {{ auth()->user()->name }}
        </h2>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow border-0 rounded-4 p-4 text-white" style="background:#49628c;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1">Total Mobil</h6>
                        <h3 class="fw-bold">{{ $totalMobil }}</h3>
                    </div>
                    <i class="bi bi-car-front fs-1 opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow border-0 rounded-4 p-4 text-white" style="background:#1e3038;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1">Total Penyewa</h6>
                        <h3 class="fw-bold">{{ $totalPenyewa }}</h3>
                    </div>
                    <i class="bi bi-people-fill fs-1 opacity-75"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow border-0 rounded-4 p-4 text-white" style="background:#64748b;">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1">Pendapatan</h6>
                        <h3 class="fw-bold">Rp {{ number_format($pendapatan, 0, ',', '.') }}</h3>
                    </div>
                    <i class="bi bi-cash-coin fs-1 opacity-75"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12 mt-4">
            <div class="card shadow border-0">
                <div class="card-header text-white py-3" style="background:#49628c;">
                    <h5 class="fw-bold mb-0">Mobil Terbaru</h5>
                </div>
                <div class="card-body">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mobil</th>
                                <th>Plat Nomor</th>
                                <th>Harga Sewa/Hari</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mobilTerbaru as $i => $m)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $m->nama_mobil }}</td>
                                    <td>{{ $m->plat_nomor }}</td>
                                    <td>Rp {{ number_format($m->harga_sewa, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('admin.mobil.index') }}" class="btn w-100 mt-2 text-white"
                        style="background:#1e3038; border:none;">
                        Lihat Semua Mobil
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-12 mt-4">
            <div class="card shadow border-0">
                <div class="card-header text-white py-3" style="background:#49628c;">
                    <h5 class="fw-bold mb-0">Penyewa Terbaru</h5>
                </div>
                <div class="card-body">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>No Telp</th>
                                <th>Alamat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($penyewaTerbaru as $i => $p)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $p->nama }}</td>
                                    <td>{{ $p->no_telp }}</td>
                                    <td>{{ $p->alamat }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('admin.penyewa.index') }}" class="btn w-100 mt-2 text-white"
                        style="background:#1e3038; border:none;">
                        Lihat Semua Penyewa
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-12 mt-4 mb-5">
            <div class="card shadow border-0">
                <div class="card-header text-white py-3" style="background:#49628c;">
                    <h5 class="fw-bold mb-0">Transaksi Rental Terbaru</h5>
                </div>
                <div class="card-body">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Mobil</th>
                                <th>Penyewa</th>
                                <th>Lama Sewa</th>
                                <th>Total Bayar</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pemasukanTerbaru as $i => $pm)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    {{-- Perbaikan: Langsung panggil mobil dan penyewa dari objek $pm --}}
                                    <td>{{ $pm->mobil->nama_mobil ?? 'N/A' }}</td>
                                    <td>{{ $pm->penyewa->nama ?? 'N/A' }}</td>
                                    <td>{{ $pm->lama_sewa }} Hari</td>
                                    <td class="fw-bold text-success">Rp {{ number_format($pm->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if ($pm->status == 'booking')
                                            <span class="badge bg-warning text-dark small">Konfirmasi</span>
                                        @else
                                            <span class="badge bg-success small">Lunas</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <a href="{{ route('admin.rental.index') }}" class="btn w-100 mt-2 text-white"
                        style="background:#1e3038; border:none;">
                        Lihat Semua Transaksi
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
