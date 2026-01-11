@extends('admin.layouts.app')

@section('content')
    <style>
        .card-custom {
            background: #1e3038;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .25);
            border: none;
            color: #fff;
            height: 100%;
        }

        .card-custom h4 {
            font-weight: 600;
            margin-bottom: 20px;
        }

        .table-custom th {
            width: 40%;
            color: #b4dbff;
        }

        .badge {
            padding: 6px 14px;
            font-size: 14px;
        }

        .btn-action {
            background: #49628c;
            border: none;
            padding: 10px 28px;
            border-radius: 10px;
            font-weight: 600;
            color: white;
            transition: .25s;
            text-decoration: none;
        }

        .btn-action:hover {
            background: #435a7a;
        }
    </style>

    <div class="container-fluid">

        <h2 class="text-white mb-4 text-center">Detail Rental</h2>

        <div class="row g-4">

            <!-- Card Informasi Rental -->
            <div class="col-lg-6">
                <div class="card-custom">
                    <h4>Informasi Rental</h4>
                    <table class="table table-dark table-bordered table-custom">
                        <tr>
                            <th>Penyewa</th>
                            <td>{{ $rental->penyewa->nama }}</td>
                        </tr>
                        <tr>
                            <th>Mobil</th>
                            <td>{{ $rental->mobil->nama_mobil }} ({{ $rental->mobil->plat_nomor }})</td>
                        </tr>
                        <tr>
                            <th>Penanggungjawab</th>
                            <td>{{ $rental->karyawan->nama }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Sewa</th>
                            <td>{{ $rental->tgl_sewa }}</td>
                        </tr>
                        <tr>
                            <th>Tanggal Kembali</th>
                            <td>{{ $rental->tgl_kembali }}</td>
                        </tr>
                        <tr>
                            <th>Lama Sewa</th>
                            <td>{{ $rental->lama_sewa }} Hari</td>
                        </tr>
                        <tr>
                            <th>Status Rental</th>
                            <td>
                                <span class="badge {{ $rental->status == 'selesai' ? 'bg-success' : 'bg-warning' }}">
                                    {{ ucfirst($rental->status) }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Card Informasi Pembayaran -->
            <div class="col-lg-6">
                <div class="card-custom">
                    <h4>Informasi Pembayaran</h4>

                    @if ($rental->pembayaran)
                        <table class="table table-dark table-bordered table-custom">
                            <tr>
                                <th>Total Harga</th>
                                <td>Rp {{ number_format($rental->pembayaran->total_harga, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>DP</th>
                                <td>Rp {{ number_format($rental->pembayaran->dp, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Sisa Bayar</th>
                                <td>Rp {{ number_format($rental->pembayaran->sisa_bayar, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Metode</th>
                                <td>{{ strtoupper($rental->pembayaran->metode) }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <span
                                        class="badge {{ $rental->pembayaran->status_pembayaran == 'lunas' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($rental->pembayaran->status_pembayaran) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Denda</th>
                                <td>Rp {{ number_format($rental->pembayaran->denda ?? 0, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <th>Aksi</th>
                                <td>
                                    <a href="{{ route('admin.pembayaran.edit', $rental->pembayaran->id) }}"
                                        class="btn-action btn-sm">
                                        Update Pembayaran
                                    </a>
                                </td>
                            </tr>
                        </table>
                    @else
                        <div class="alert alert-warning">
                            Pembayaran belum dibuat.
                            <a href="{{ route('admin.pembayaran.create', ['rental_id' => $rental->id]) }}"
                                class="btn-action btn-sm">
                                Tambah Pembayaran / DP
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>

        <!-- Button Kembali di Bawah Tengah -->
        <div class="text-center mt-5 mb-4">
            <a href="{{ route('admin.rental.index') }}" class="btn-action">Kembali</a>
        </div>

    </div>
@endsection
