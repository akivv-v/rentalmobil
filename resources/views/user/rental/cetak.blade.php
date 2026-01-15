<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $invoice->status === 'lunas' ? 'Kwitansi' : 'Tagihan' }} - {{ $invoice->kode_invoice }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            background-color: #f8f9fa;
        }

        .invoice-card {
            max-width: 800px;
            margin: 30px auto;
            background: white;
            padding: 40px;
            border: 1px solid #ddd;
            position: relative;
            overflow: hidden;
        }

        .line {
            border-top: 2px dashed #000;
            margin: 20px 0;
        }

        /* Stempel Lunas */
        .stempel-lunas {
            position: absolute;
            top: 150px;
            right: 50px;
            border: 5px solid #28a745;
            color: #28a745;
            font-size: 40px;
            font-weight: bold;
            padding: 10px 20px;
            transform: rotate(-15deg);
            opacity: 0.3;
            border-radius: 10px;
            text-transform: uppercase;
            z-index: 10;
        }

        @media print {
            body {
                background-color: white;
            }

            .invoice-card {
                margin: 0;
                border: none;
                width: 100%;
                max-width: 100%;
            }

            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="invoice-card shadow-sm">
        {{-- Tampilkan Stempel jika Lunas --}}
        @if ($invoice->status === 'lunas')
            <div class="stempel-lunas">LUNAS</div>
        @endif

        <div class="text-center">
            <h2 class="fw-bold mb-0">RENTSBILL INDONESIA</h2>
            <p class="mb-1">Jl. Raya Rent No. 123, Mojokerto</p>
            <p class="small text-muted">Telp: 0812-3456-7890 | Email: support@rentsbill.id</p>
        </div>

        <div class="line"></div>

        <div class="row">
            <div class="col-7">
                <h5 class="fw-bold text-uppercase">
                    {{ $invoice->status === 'lunas' ? 'Kwitansi Pembayaran' : 'Struk Tagihan' }}
                </h5>
                <table class="table table-sm table-borderless small">
                    <tr>
                        <td width="120">Kode Invoice</td>
                        <td>: {{ $invoice->kode_invoice }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>: {{ $invoice->created_at->format('d M Y H:i') }}</td>
                    </tr>
                </table>
            </div>
            <div class="col-5 text-end">
                <h6 class="fw-bold mb-1">Penyewa:</h6>
                <p class="small mb-0">
                    <strong>{{ $invoice->rental->penyewa->nama }}</strong><br>
                    {{ $invoice->rental->penyewa->no_telp }}<br>
                    <span class="text-muted">{{ Str::limit($invoice->rental->penyewa->alamat, 40) }}</span>
                </p>
            </div>
        </div>

        <table class="table table-bordered mt-4">
            <thead class="table-light text-center small text-uppercase">
                <tr>
                    <th>Item Deskripsi</th>
                    <th width="100">Durasi</th>
                    <th width="150">Harga/Hari</th>
                    <th width="150">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Sewa Mobil {{ $invoice->rental->mobil->nama_mobil }}</strong><br>
                        <small class="text-muted small">Unit ID: #{{ $invoice->rental->mobil->id }} | Plat:
                            {{ $invoice->rental->mobil->plat_nomor }}</small>
                    </td>
                    <td class="text-center align-middle">{{ $invoice->rental->lama_sewa }} Hari</td>
                    <td class="text-end align-middle">Rp
                        {{ number_format($invoice->rental->mobil->harga_sewa, 0, ',', '.') }}</td>
                    <td class="text-end align-middle fw-bold">Rp
                        {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end py-2">TOTAL AKHIR</th>
                    <th class="text-end py-2 fs-5 text-primary">Rp
                        {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        <div class="row mt-4">
            <div class="col-7 small">
                <p class="mb-1 fw-bold">Keterangan:</p>
                <ul class="ps-3 mb-0 text-muted">
                    <li>Metode: {{ ucfirst($invoice->metode_pembayaran) }}</li>
                    <li>Status: <strong>{{ $invoice->status === 'lunas' ? 'LUNAS (Verified)' : 'PENDING' }}</strong>
                    </li>
                    @if ($invoice->status === 'lunas')
                        <li>Tgl Lunas: {{ $invoice->updated_at->format('d/m/Y') }}</li>
                    @endif
                </ul>

                {{-- INFORMASI KONTAK SOBAT RENTSBILL --}}
                @if ($invoice->status === 'lunas')
                    <div class="mt-3 p-2 border rounded bg-light border-success"
                        style="border-style: dotted !important;">
                        <p class="mb-0 small fw-bold text-success"><i class="bi bi-person-badge me-1"></i> Sobat
                            RentsBill Anda:</p>
                        <p class="mb-0 small">{{ $invoice->rental->karyawan->no_telp }}
                            ({{ $invoice->rental->karyawan->nama }})</p>
                    </div>
                @endif
            </div>
            <div class="col-5 text-center align-self-center">
                <p class="small mb-5">Hormat Kami,</p>
                <br>
                <p class="fw-bold mb-0">( Admin RentsBill )</p>
                <small class="text-muted italic" style="font-size: 10px;">*Valid tanpa tanda tangan basah</small>
            </div>
        </div>

        <div class="text-center mt-5 no-print">
            <hr>
            <button onclick="window.print()" class="btn btn-primary px-4 shadow-sm">
                <i class="bi bi-printer me-2"></i> Cetak Kwitansi
            </button>
            <button onclick="window.close()" class="btn btn-outline-secondary px-4 ms-2">Tutup</button>
        </div>
    </div>

</body>

</html>
