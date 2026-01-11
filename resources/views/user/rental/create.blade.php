@extends('user.layouts.app')

@section('content')
<div class="container my-5">
    <form action="{{ route('user.rental.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- ================== CARD 1 DATA PENYEWA ================== --}}
        <div class="card card-custom p-4 mb-4 border-0 shadow-sm">
            <h4 class="title mb-3"><i class="bi bi-person-fill text-primary"></i> Data Diri Penyewa</h4>
            <input type="hidden" name="mobil_id" value="{{ $mobil->id }}">

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" class="form-control bg-light" value="{{ Auth::user()->name }}" readonly>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">No. WhatsApp / Telp</label>
                    <input type="number" class="form-control" name="telp" value="{{ old('telp') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea class="form-control" name="alamat" rows="2" required>{{ old('alamat') }}</textarea>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Pekerjaan</label>
                    <input type="text" class="form-control" name="pekerjaan" required value="{{ old('pekerjaan') }}">
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Foto KTP (JPEG/PNG)</label>
                    <input type="file" name="foto_ktp" class="form-control" required>
                </div>
            </div>
        </div>

        {{-- ================== CARD 2 DATA MOBIL ================== --}}
        <div class="card card-custom p-4 mb-4 border-0 shadow-sm">
            <h4 class="title mb-3"><i class="bi bi-car-front-fill text-primary"></i> Data Rental Mobil</h4>
            <div class="p-3 bg-light rounded mb-3">
                <span class="fw-bold fs-5">{{ $mobil->nama_mobil }}</span> 
                <span class="text-muted ms-2">| Rp {{ number_format($mobil->harga_sewa, 0, ',', '.') }} / Hari</span>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" name="tanggal_mulai" id="tanggalMulai" required min="{{ date('Y-m-d') }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Lama Sewa (hari)</label>
                    <input type="number" class="form-control" name="lama_sewa" id="lamaSewa" required min="1" value="1">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Penanggung Jawab</label>
                    <select class="form-control" name="penanggungjawab" required>
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach ($karyawan as $k)
                            <option value="{{ $k->id }}">{{ $k->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- ================== CARD 3 PEMBAYARAN ================== --}}
        <div class="card card-custom p-4 mb-4 border-0 shadow-sm">
            <h4 class="title mb-3"><i class="bi bi-wallet-fill text-primary"></i> Ringkasan Pembayaran</h4>
            
            <div class="d-flex justify-content-between align-items-center p-3 border rounded mb-3 bg-primary-subtle text-primary-emphasis">
                <h5 class="mb-0">Total yang harus dibayar:</h5>
                <h4 class="mb-0 fw-bold">Rp <span id="totalHargaText">{{ number_format($mobil->harga_sewa, 0, ',', '.') }}</span></h4>
            </div>

            {{-- Hidden Inputs untuk dikirim ke Controller --}}
            <input type="hidden" name="jenis_pembayaran" value="lunas">
            <input type="hidden" name="jumlah_bayar" id="inputBayar" value="{{ $mobil->harga_sewa }}">

            <div class="mb-3">
                <label class="form-label">Metode Pembayaran</label>
                <select class="form-control" name="metode_pembayaran" required>
                    <option value="transfer">Transfer Bank / E-Wallet</option>
                    <option value="office">Bayar di Kantor</option>
                </select>
                <small class="text-muted mt-2 d-block">*Pembayaran dilakukan secara penuh di awal.</small>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm">
            <i class="bi bi-check-circle-fill me-2"></i> Konfirmasi & Sewa Sekarang
        </button>
    </form>
</div>

<script>
    const hargaPerHari = {{ $mobil->harga_sewa }};
    const inputLama = document.getElementById('lamaSewa');
    const displayTotal = document.getElementById('totalHargaText');
    const hiddenBayar = document.getElementById('inputBayar');

    function hitung() {
        let lama = parseInt(inputLama.value) || 0;
        let total = lama * hargaPerHari;

        // Update tampilan teks
        displayTotal.innerText = new Intl.NumberFormat('id-ID').format(total);
        
        // Update input hidden yang akan dikirim ke Controller
        hiddenBayar.value = total;
    }

    inputLama.addEventListener('input', hitung);
</script>
@endsection