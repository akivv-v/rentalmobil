@extends('admin.layouts.app')

@section('content')
    <style>
        /* Reset & Clean Background */
        body {
            background-color: #f8f9fa;
            /* Abu-abu sangat muda agar elemen putih terlihat pop */
        }

        /* Card Styling */
        .main-card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e9ecef;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.02);
            padding: 24px;
        }

        /* Table Styling */
        .table {
            color: #334155;
            border-color: #f1f5f9;
        }

        .table thead th {
            background-color: #f8fafc;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            border-top: none;
            padding: 15px;
        }

        .table tbody td {
            padding: 18px 15px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
        }

        /* Search & Input */
        .search-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 16px;
            font-size: 14px;
            transition: all 0.2s;
        }

        .search-box:focus {
            border-color: #94a3b8;
            box-shadow: none;
            background: #fff;
        }

        /* Status Badges (Minimalist) */
        .status-badge {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
        }

        .bg-light-warning {
            background: #fffbeb;
            color: #92400e;
        }

        .bg-light-info {
            background: #f0f9ff;
            color: #075985;
        }

        .bg-light-success {
            background: #f0fdf4;
            color: #166534;
        }

        /* Action Buttons */
        .btn-icon {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: #fff;
            border: 1px solid #e2e8f0;
            color: #64748b;
            transition: all 0.2s;
            text-decoration: none;
        }

        .btn-icon:hover {
            background: #f8fafc;
            color: #1e293b;
            border-color: #cbd5e1;
        }

        .btn-icon-danger:hover {
            background: #fef2f2;
            color: #dc2626;
            border-color: #fecaca;
        }

        .btn-icon-success:hover {
            background: #f0fdf4;
            color: #16a34a;
            border-color: #bbf7d0;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Transaksi Rental</h3>
                <p class="text-muted small mb-0">Kelola operasional penyewaan kendaraan Anda.</p>
            </div>

            {{-- SEARCH MINIMALIS --}}
            <div style="width: 300px;">
                <form method="GET" action="{{ route('admin.rental.index') }}">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control search-box" placeholder="Cari penyewa..."
                            value="{{ request('search') }}">
                        @if (request('search'))
                            <a href="{{ route('admin.rental.index') }}" class="btn btn-light border-start-0 border"
                                style="border-color: #e2e8f0;">
                                <i class="bi bi-x"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        @include('components.alerts')

        <div class="main-card">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th width="50">ID</th>
                            <th>Informasi Penyewa</th>
                            <th>Kendaraan</th>
                            <th>Periode & Durasi</th>
                            <th>Total Pembayaran</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rentals as $item)
                            @php
                                $tglKembali = \Carbon\Carbon::parse($item->tgl_kembali);
                                $hariIni = \Carbon\Carbon::today();

                                // Logika Status
                                $isLate = $item->status == 'disewa' && $hariIni->greaterThan($tglKembali);
                                $isToday = $item->status == 'disewa' && $hariIni->equalTo($tglKembali);
                                $isFinished = $item->status == 'selesai';

                                // Styling Baris
                                $rowStyle = '';
                                $nameColor = 'text-dark'; // Default hitam

                                if ($isLate) {
                                    $rowStyle = 'border-left: 4px solid #dc3545; background-color: #fffafb;';
                                    $nameColor = 'text-danger'; // Nama jadi merah jika telat
                                } elseif ($isToday) {
                                    $rowStyle = 'border-left: 4px solid #ffc107; background-color: #fffdf5;';
                                    $nameColor = 'text-danger'; // Nama jadi merah jika hari ini deadline
                                } elseif ($isFinished) {
                                    $rowStyle = 'opacity: 0.7;';
                                    $nameColor = 'text-muted';
                                }
                            @endphp

                            <tr style="{{ $rowStyle }} transition: all 0.3s ease;">
                                <td class="text-muted">
                                    {{ ($rentals->currentPage() - 1) * $rentals->perPage() + $loop->iteration }}
                                </td>

                                {{-- INFORMASI PENYEWA --}}
                                <td>
                                    <div class="fw-bold {{ $nameColor }} d-flex align-items-center gap-1">
                                        @if ($isLate || $isToday)
                                            <i class="bi bi-exclamation-circle-fill" style="font-size: 12px;"></i>
                                        @endif
                                        {{ $item->penyewa->nama }}
                                    </div>
                                    <div class="text-muted" style="font-size: 12px;">{{ $item->penyewa->no_telp }}</div>
                                </td>
                                <td>
                                    <div class="{{ $isFinished ? 'text-muted' : 'text-dark' }}">
                                        {{ $item->mobil->nama_mobil }}</div>
                                    <div class="badge bg-light text-dark fw-normal border" style="font-size: 10px;">
                                        {{ $item->mobil->plat_nomor }}
                                    </div>
                                </td>
                                <td>
                                    <div class="{{ $isFinished ? 'text-muted' : 'text-dark' }}">
                                        {{ date('d M Y', strtotime($item->tgl_sewa)) }}</div>
                                    <div class="small">
                                        @if ($isFinished)
                                            <span class="text-success"><i class="bi bi-check-circle"></i> Selesai</span>
                                        @elseif($isLate)
                                            <span class="text-danger fw-bold"><i class="bi bi-exclamation-octagon"></i>
                                                Terlambat {{ $hariIni->diffInDays($tglKembali) }} Hari</span>
                                        @elseif($isToday)
                                            <span class="text-warning fw-bold"><i class="bi bi-clock-history"></i> Harus
                                                Kembali Hari Ini</span>
                                        @else
                                            <span class="text-muted">{{ $item->lama_sewa }} Hari</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold {{ $isFinished ? 'text-muted' : 'text-dark' }}">Rp
                                        {{ number_format($item->total_harga, 0, ',', '.') }}</div>
                                    <div class="text-muted text-uppercase" style="font-size: 10px;">
                                        {{ $item->invoice->metode_pembayaran ?? '-' }}</div>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusStyle =
                                            [
                                                'booking' => 'bg-light-warning',
                                                'disewa' => 'bg-light-info',
                                                'selesai' => 'bg-light-success',
                                            ][$item->status] ?? 'bg-light';
                                    @endphp
                                    <span class="status-badge {{ $statusStyle }}">
                                        @if ($isLate && $item->status == 'disewa')
                                            Overdue
                                        @else
                                            {{ ucfirst($item->status) }}
                                        @endif
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.rental.show', $item->id) }}" class="btn-icon"
                                            title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if ($item->status == 'booking' && $item->invoice)
                                            {{-- Logika konfirmasi pembayaran tetap sama --}}
                                            @if ($item->invoice->metode_pembayaran !== 'office' && $item->invoice->bukti_bayar)
                                                <form action="{{ route('admin.rental.konfirmasi', $item->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button class="btn-icon btn-icon-success"
                                                        onclick="return confirm('Konfirmasi pembayaran?')">
                                                        <i class="bi bi-check2"></i>
                                                    </button>
                                                </form>
                                            @elseif($item->invoice->metode_pembayaran == 'office')
                                                <form
                                                    action="{{ route('admin.invoice.bayar_kantor', $item->invoice->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button class="btn-icon btn-icon-success" title="Bayar di Kantor">
                                                        <i class="bi bi-cash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @elseif ($item->status == 'disewa')
                                            <form action="{{ route('admin.rental.set_kembali', $item->id) }}"
                                                method="POST">
                                                @csrf
                                                <button
                                                    class="btn-icon {{ $isLate ? 'btn-danger text-white' : 'btn-icon-success' }}"
                                                    title="Selesaikan Pengembalian">
                                                    <i class="bi bi-arrow-left-right"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.rental.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus?')">
                                            @csrf @method('DELETE')
                                            <button class="btn-icon btn-icon-danger">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">Belum ada transaksi rental.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted small">
                Menampilkan {{ $rentals->firstItem() }} sampai {{ $rentals->lastItem() }} dari {{ $rentals->total() }}
                transaksi
            </div>
            <div>
                {{ $rentals->links() }}
            </div>
        </div>
    </div>
@endsection
