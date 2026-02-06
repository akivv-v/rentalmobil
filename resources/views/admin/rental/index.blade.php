@extends('admin.layouts.app')

@section('content')
    <style>
        /* Reset & Clean Background */
        body {
            background-color: #f8f9fa;
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

        /* Status Badges */
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

        .bg-light-danger {
            background: #fef2f2;
            color: #991b1b;
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

        .btn-overdue {
            background: #dc3545 !important;
            color: white !important;
            border-color: #dc3545 !important;
        }

        .btn-overdue:hover {
            background: #b02a37 !important;
        }
    </style>

    <div class="container-fluid py-4">
        <div class="d-flex justify-content-between align-items-end mb-4">
            <div>
                <h3 class="fw-bold text-dark mb-1">Transaksi Rental</h3>
                <p class="text-muted small mb-0">Monitor pembayaran sewa (Lunas di Awal) dan penagihan denda.</p>
            </div>

            <div style="width: 300px;">
                <form method="GET" action="{{ route('admin.rental.index') }}">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari penyewa/mobil..."
                            value="{{ request('search') }}" style="border-radius: 8px 0 0 8px;">
                        <button class="btn btn-dark" type="submit"><i class="bi bi-search"></i></button>
                        @if (request('search'))
                            <a href="{{ route('admin.rental.index') }}" class="btn btn-light border"><i
                                    class="bi bi-x"></i></a>
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
                            <th width="50">No</th>
                            <th>Penyewa & Kendaraan</th>
                            <th>Periode & Deadline</th>
                            <th>Biaya Sewa</th>
                            <th>Denda</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rentals as $item)
                            @php
                                $tglKembali = \Carbon\Carbon::parse($item->tgl_kembali)->startOfDay();
                                $hariIni = \Carbon\Carbon::now()->startOfDay();

                                $isLate = $item->status == 'disewa' && $hariIni->gt($tglKembali);
                                $isToday = $item->status == 'disewa' && $hariIni->equalTo($tglKembali);
                                $isFinished = $item->status == 'selesai';

                                // Row Highlight Logic
                                $rowStyle = '';
                                if ($isLate) {
                                    $rowStyle = 'border-left: 4px solid #dc3545; background-color: #fef2f2;';
                                } elseif ($isToday) {
                                    $rowStyle = 'border-left: 4px solid #ffc107; background-color: #fffbeb;';
                                } elseif ($isFinished) {
                                    $rowStyle = 'background-color: #f8fafc; opacity: 0.9;';
                                }
                            @endphp

                            <tr style="{{ $rowStyle }}">
                                <td class="text-muted small">
                                    {{ ($rentals->currentPage() - 1) * $rentals->perPage() + $loop->iteration }}
                                </td>

                                {{-- PENYEWA & MOBIL --}}
                                <td>
                                    <div class="fw-bold {{ $isLate ? 'text-danger' : 'text-dark' }}">
                                        {{ $item->penyewa->nama }}
                                    </div>
                                    <div class="text-muted small">{{ $item->mobil->nama_mobil }}
                                        ({{ $item->mobil->plat_nomor }})
                                    </div>
                                </td>

                                {{-- PERIODE & LOGIKA DEADLINE --}}
                                <td>
                                    <div class="small text-dark">Sewa: {{ date('d M Y', strtotime($item->tgl_sewa)) }}</div>
                                    <div class="small fw-bold {{ $isLate ? 'text-danger' : 'text-muted' }}">
                                        Sampai: {{ date('d M Y', strtotime($item->tgl_kembali)) }}
                                    </div>
                                    <div class="mt-1">
                                        @if ($isFinished)
                                            <span class="text-success small"><i class="bi bi-check-circle"></i>
                                                Dikembalikan</span>
                                        @elseif($isLate)
                                            <span class="badge bg-danger" style="font-size: 10px;">
                                                Terlambat {{ $hariIni->diffInDays($tglKembali) }} Hari
                                            </span>
                                        @elseif($isToday)
                                            <span class="badge bg-warning text-dark" style="font-size: 10px;">
                                                Deadline Hari Ini
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- BIAYA SEWA (LUNAS DI AWAL) --}}
                                <td>
                                    <div class="fw-bold text-success">Rp
                                        {{ number_format($item->total_harga, 0, ',', '.') }}</div>
                                    <div class="text-muted text-uppercase" style="font-size: 10px;">
                                        {{ $item->invoice->metode_pembayaran ?? '-' }}
                                    </div>
                                </td>

                                {{-- KOLOM DENDA --}}
                                <td>
                                    @if ($item->denda > 0)
                                        <div class="fw-bold text-danger">Rp {{ number_format($item->denda, 0, ',', '.') }}
                                        </div>
                                        <span
                                            class="badge {{ $isFinished ? 'bg-light-success text-success' : 'bg-light-danger text-danger' }}"
                                            style="font-size: 10px;">
                                            {{ $isFinished ? 'Denda Lunas' : 'Denda Berjalan' }}
                                        </span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>

                                {{-- STATUS --}}
                                <td class="text-center">
                                    @php
                                        $statusClass =
                                            [
                                                'booking' => 'bg-light-warning',
                                                'disewa' => $isLate ? 'bg-light-danger' : 'bg-light-info',
                                                'selesai' => 'bg-light-success',
                                            ][$item->status] ?? 'bg-light';
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">
                                        {{ $isLate ? 'Overdue' : ucfirst($item->status) }}
                                    </span>
                                </td>

                                {{-- AKSI --}}
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.rental.show', $item->id) }}" class="btn-icon"
                                            title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if ($item->status == 'booking' && $item->invoice)
                                            @if ($item->invoice->metode_pembayaran !== 'office' && $item->invoice->bukti_bayar)
                                                <form action="{{ route('admin.rental.konfirmasi', $item->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button class="btn-icon btn-icon-success"
                                                        onclick="return confirm('Konfirmasi pembayaran sewa?')">
                                                        <i class="bi bi-check2"></i>
                                                    </button>
                                                </form>
                                            @elseif($item->invoice->metode_pembayaran == 'office')
                                                <form
                                                    action="{{ route('admin.invoice.bayar_kantor', $item->invoice->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button class="btn-icon btn-icon-success" title="Bayar Sewa di Kantor">
                                                        <i class="bi bi-cash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @elseif ($item->status == 'disewa')
                                            <form action="{{ route('admin.rental.set_kembali', $item->id) }}"
                                                method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="btn-icon {{ $isLate ? 'btn-overdue' : 'btn-icon-success' }}"
                                                    onclick="return confirm('{{ $isLate ? 'Penyewa terlambat. Sistem akan menghitung denda otomatis. Lanjutkan?' : 'Konfirmasi pengembalian mobil?' }}')">
                                                    <i class="bi bi-arrow-left-right"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <form action="{{ route('admin.rental.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Hapus transaksi?')">
                                            @csrf @method('DELETE')
                                            <button class="btn-icon btn-icon-danger"><i class="bi bi-trash3"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">Data rental tidak ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted small">
                Menampilkan {{ $rentals->firstItem() }} - {{ $rentals->lastItem() }} dari {{ $rentals->total() }} data
            </div>
            <div>{{ $rentals->links() }}</div>
        </div>
    </div>
@endsection
