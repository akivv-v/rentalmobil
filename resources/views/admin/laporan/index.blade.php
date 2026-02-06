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
    </style>

    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-0">Laporan Bulanan</h3>
                <p class="text-muted small">Pantau performa bisnis rental Anda</p>
            </div>

            {{-- Filter Bulan & Tahun --}}
            <form action="{{ route('admin.laporan.index') }}" method="GET" class="d-flex gap-2">
                <select name="bulan" class="form-select border-0 shadow-sm" style="width: 150px;">
                    @foreach (range(1, 12) as $m)
                        <option value="{{ sprintf('%02d', $m) }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endforeach
                </select>
                <select name="tahun" class="form-select border-0 shadow-sm" style="width: 100px;">
                    @foreach (range(date('Y') - 2, date('Y')) as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary px-4 shadow-sm">Filter</button>
            </form>
        </div>

        {{-- Widget Ringkasan --}}
        <div class="row g-4 mb-5">
            <div class="col-md-3">
                <div class="card-report d-flex align-items-center">
                    <div class="icon-box bg-success text-white me-3"><i class="bi bi-wallet2"></i></div>
                    <div>
                        <div class="stat-label">Pendapatan Lunas</div>
                        <div class="stat-value text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-report d-flex align-items-center">
                    <div class="icon-box bg-primary text-white me-3"><i class="bi bi-cart-check"></i></div>
                    <div>
                        <div class="stat-label">Total Transaksi</div>
                        <div class="stat-value">{{ $totalTransaksi }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-report d-flex align-items-center">
                    <div class="icon-box bg-info text-white me-3"><i class="bi bi-arrow-repeat"></i></div>
                    <div>
                        <div class="stat-label">Sedang Disewa</div>
                        <div class="stat-value">{{ $transaksiAktif }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card-report d-flex align-items-center">
                    <div class="icon-box bg-secondary text-white me-3"><i class="bi bi-check-all"></i></div>
                    <div>
                        <div class="stat-label">Selesai Kembali</div>
                        <div class="stat-value">{{ $transaksiSelesai }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Detail --}}
        <div class="card-report border-0 shadow-sm">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold mb-0">Detail Transaksi Bulan Ini</h5>
                <button onclick="window.print()" class="btn btn-outline-dark btn-sm rounded-pill">
                    <i class="bi bi-printer me-1"></i> Cetak Laporan
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="py-3">No</th>
                            <th class="py-3">Tanggal</th>
                            <th class="py-3">Penyewa</th>
                            <th class="py-3">Mobil</th>
                            <th class="py-3">Lama</th>
                            <th class="py-3 text-end">Total Biaya</th>
                             <th class="py-3 text-end">Denda</th>
                            <th class="py-3 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rentals as $item)
                            <tr>
                                <td class="text-muted">{{ $loop->iteration }}</td>
                                <td>{{ date('d M Y', strtotime($item->tgl_sewa)) }}</td>
                                <td class="fw-bold">{{ $item->penyewa->nama }}</td>
                                <td>{{ $item->mobil->nama_mobil }}</td>
                                <td>{{ $item->lama_sewa }} Hari</td>
                                <td class="text-end fw-bold">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                                <td class="text-end text-danger">Rp {{ number_format($item->denda, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <span
                                        class="badge rounded-pill {{ $item->status == 'selesai' ? 'bg-light-success text-success' : 'bg-light-primary text-primary' }} p-2 px-3">
                                        {{ strtoupper($item->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">Tidak ada data transaksi pada periode
                                    ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
