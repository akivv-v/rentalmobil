@extends('admin.layouts.app')

@section('content')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card-report {
            background: white;
            border-radius: 15px;
            border: 1px solid #e9ecef;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
        }

        .stat-label {
            font-size: 0.8rem;
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
        }

        .icon-box {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        /* CSS KHUSUS UNTUK CETAK */
        @media print {

            /* Sembunyikan Sidebar, Navbar, Form Filter, dan Tombol Cetak */
            .sidebar,
            .form-filter{
                display: none !important;
            }

            /* Hilangkan margin/padding layout admin jika ada */
            .main-content,
            .content-wrapper,
            .container-fluid {
                margin: 0 !important;
                padding: 0 !important;
                width: 100% !important;
                position: absolute;
                left: 0;
                top: 0;
            }

            /* Pastikan tabel lebar penuh */
            .card-report {
                box-shadow: none;
                border: none;
            }

            body {
                background-color: white;
            }
        }
    </style>

    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <div>
                <h3 class="fw-bold text-dark mb-0">Laporan Transaksi</h3>
                <p class="text-muted small">
                    @if (request('tgl_mulai'))
                        Periode: {{ date('d/m/Y', strtotime($tgl_mulai)) }} - {{ date('d/m/Y', strtotime($tgl_selesai)) }}
                    @else
                        Periode: {{ Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}
                    @endif
                </p>
            </div>

            {{-- Form Filter --}}
            <form action="{{ route('admin.laporan.index') }}" method="GET" class="form-filter d-flex gap-2 align-items-end">
                <div class="group">
                    <label class="small fw-bold">Dari Tanggal</label>
                    <input type="date" name="tgl_mulai" class="form-control border-0 shadow-sm"
                        value="{{ $tgl_mulai }}">
                </div>
                <div class="group">
                    <label class="small fw-bold">Sampai Tanggal</label>
                    <input type="date" name="tgl_selesai" class="form-control border-0 shadow-sm"
                        value="{{ $tgl_selesai }}">
                </div>
                <div class="ms-2 border-start ps-2">
                    <label class="small fw-bold">Pilih Bulan</label>
                    <div class="d-flex gap-1">
                        <select name="bulan" class="form-select border-0 shadow-sm" style="width: 150px;">
                            @foreach (range(1, 12) as $m)
                                <option value="{{ sprintf('%02d', $m) }}" {{ $bulan == $m ? 'selected' : '' }}>
                                    {{ Carbon\Carbon::create()->month((int) $m)->translatedFormat('F') }}
                                </option>
                            @endforeach
                        </select>
                        <select name="tahun" class="form-select border-0 shadow-sm" style="width: 90px;">
                            @foreach (range(date('Y') - 2, date('Y')) as $y)
                                <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>
                                    {{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary px-4 shadow-sm">Filter</button>
                <a href="{{ route('admin.laporan.index') }}" class="btn btn-light shadow-sm"><i
                        class="bi bi-arrow-clockwise"></i></a>
            </form>
        </div>

        {{-- Widget Ringkasan --}}
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card-report d-flex align-items-center border-start border-success border-4">
                    <div class="icon-box bg-success text-white me-3"><i class="bi bi-wallet2"></i></div>
                    <div>
                        <div class="stat-label">Total Pendapatan</div>
                        <div class="stat-value text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-report d-flex align-items-center border-start border-primary border-4">
                    <div class="icon-box bg-primary text-white me-3"><i class="bi bi-cart-check"></i></div>
                    <div>
                        <div class="stat-label">Total Transaksi</div>
                        <div class="stat-value">{{ $totalTransaksi }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-report d-flex align-items-center border-start border-info border-4">
                    <div class="icon-box bg-info text-white me-3"><i class="bi bi-arrow-repeat"></i></div>
                    <div>
                        <div class="stat-label">Sedang Disewa</div>
                        <div class="stat-value">{{ $transaksiAktif }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-report d-flex align-items-center border-start border-danger border-4">
                    <div class="icon-box bg-danger text-white me-3"><i class="bi bi-exclamation-circle"></i></div>
                    <div>
                        <div class="stat-label">Total Denda</div>
                        <div class="stat-value text-danger">Rp {{ number_format($totalDenda, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Detail --}}
        <div class="card-report border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Detail Transaksi</h5>
                <button onclick="window.print()" class="btn btn-dark btn-sm rounded-pill no-print">
                    <i class="bi bi-printer me-1"></i> Cetak Laporan
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">No</th>
                            <th class="py-3">Tanggal Sewa</th>
                            <th class="py-3">Penyewa</th>
                            <th class="py-3">Mobil</th>
                            <th class="py-3">Durasi</th>
                            <th class="py-3 text-end">Biaya Sewa</th>
                            <th class="py-3 text-end">Denda</th>
                            <th class="py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rentals as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ date('d/m/Y', strtotime($item->tgl_sewa)) }}</td>
                                <td class="fw-bold">{{ $item->penyewa->nama }}</td>
                                <td>{{ $item->mobil->nama_mobil }}</td>
                                <td>{{ $item->lama_sewa }} Hari</td>
                                <td class="text-end">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                <td class="text-end text-danger">Rp {{ number_format($item->denda, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $item->status == 'selesai' ? 'bg-success' : 'bg-primary' }}">
                                        {{ strtoupper($item->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">Data tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
