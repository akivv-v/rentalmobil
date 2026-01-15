@extends('user.layouts.app')

@section('content')
    <div class="container my-5">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ route('user.rental.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- ================== CARD 1 DATA PENYEWA ================== --}}
            <div class="card card-custom p-4 mb-4 border-0 shadow-sm">
                <h4 class="title mb-3"><i class="bi bi-person-fill text-primary"></i> Data Diri Penyewa</h4>
                <input type="hidden" name="mobil_id" value="{{ $mobil->id }}">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        {{-- readonly tidak butuh placeholder karena sudah ada isinya --}}
                        <input type="text" class="form-control bg-light" value="{{ Auth::user()->name }}" readonly>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">No. WhatsApp / Telp</label>
                        <input type="number" class="form-control" name="telp" value="{{ old('telp') }}"
                            placeholder="Contoh: 081234567890" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" name="alamat" rows="2"
                            placeholder="Masukkan alamat domisili saat ini secara lengkap" required>{{ old('alamat') }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Pekerjaan</label>
                        <input type="text" class="form-control" name="pekerjaan" required value="{{ old('pekerjaan') }}"
                            placeholder="Contoh: Karyawan Swasta / Wiraswasta">
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Foto KTP (JPEG/PNG)</label>
                        <input type="file" name="foto_ktp" class="form-control" required>
                        {{-- Tambahan info kecil di bawah input file --}}
                        <small class="text-muted">Pastikan foto KTP terlihat jelas dan tidak buram.</small>
                    </div>
                </div>
            </div>

            {{-- ================== CARD 2 DATA MOBIL ================== --}}
            <div class="card card-custom p-4 mb-4 border-0 shadow-sm">
                <h4 class="title mb-3"><i class="bi bi-car-front-fill text-primary"></i> Data Rental Mobil</h4>

                <div class="p-3 bg-light rounded mb-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold fs-5 text-uppercase">{{ $mobil->nama_mobil }}</span>
                            <span class="text-muted ms-2">| Rp {{ number_format($mobil->harga_sewa, 0, ',', '.') }} /
                                Hari</span>
                        </div>
                        <div class="mt-2 mt-md-0">
                            <span class="badge bg-dark me-1"><i class="bi bi-hash"></i> {{ $mobil->plat_nomor }}</span>
                            <span class="badge bg-secondary me-1"><i class="bi bi-calendar-event"></i>
                                {{ $mobil->tahun }}</span>
                            <span class="badge bg-info text-dark">
                                {{ ucfirst($mobil->deskripsi) }}</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="tanggal_mulai" id="tanggalMulai" required
                            min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Lama Sewa (hari)</label>
                        <input type="number" class="form-control" name="lama_sewa" id="lamaSewa" required min="1"
                            value="1" placeholder="Masukkan lama sewa">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label text-dark fw-bold">Penanggung Jawab</label>
                        <select class="form-select @error('karyawan_id') is-invalid @enderror" name="karyawan_id" required>
                            <option value="" disabled selected>Pilih sobat rentsbillmu</option>
                            @foreach ($karyawan as $k)
                                <option value="{{ $k->id }}" {{ old('karyawan_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_karyawan ?? $k->nama }}
                                </option>
                            @endforeach
                        </select>
                        @error('karyawan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted mt-1 d-block" style="font-size: 0.75rem;">
                            *Karyawan yang akan menyerahkan kunci.
                        </small>
                    </div>
                </div>
            </div>

            {{-- ================== CARD 3 PEMBAYARAN ================== --}}
            <div class="card card-custom p-4 mb-4 border-0 shadow-sm">
                <h4 class="title mb-3"><i class="bi bi-wallet-fill text-primary"></i> Ringkasan Pembayaran</h4>

                <div
                    class="d-flex justify-content-between align-items-center p-3 border rounded mb-3 bg-primary-subtle text-primary-emphasis">
                    <h5 class="mb-0">Total yang harus dibayar:</h5>
                    <h4 class="mb-0 fw-bold">Rp <span
                            id="totalHargaText">{{ number_format($mobil->harga_sewa, 0, ',', '.') }}</span></h4>
                </div>

                {{-- Hidden Inputs untuk dikirim ke Controller --}}
                <input type="hidden" name="jenis_pembayaran" value="lunas">
                <input type="hidden" name="jumlah_bayar" id="inputBayar" value="{{ $mobil->harga_sewa }}">

                <div class="mb-3">
                    <label class="form-label">Metode Pembayaran</label>
                    <select class="form-control" name="metode_pembayaran" id="metode_pembayaran" required
                        onchange="toggleBankInfo()">
                        <option value="" disabled selected>Pilih Metode Pembayaran</option>
                        <option value="transfer">Transfer Bank</option>
                        <option value="office">Bayar di Kantor</option>
                    </select>
                    <small class="text-muted mt-2 d-block">*Pembayaran dilakukan secara penuh di awal.</small>
                </div>

                <div id="info_bank" class="alert alert-info mt-3" style="display: none; border-left: 5px solid #0056ff;">
                    <h6 class="fw-bold"><i class="ri-bank-card-line"></i> Informasi Pembayaran:</h6>
                    <p class="mb-1 small">Silakan transfer ke rekening berikut:</p>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between">
                        <span>Bank:</span>
                        <strong class="text-uppercase">Bank Central Asia (BCA)</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>No. Rekening:</span>
                        <strong class="text-primary">1234-567-890</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>Atas Nama:</span>
                        <strong>PT RentsBill Indonesia</strong>
                    </div>
                    <hr class="my-2">
                    <small class="text-danger">*Simpan bukti transfer untuk diunggah setelah ini.</small>
                </div>
            </div>

            {{-- Ganti tombol submit lama dengan ini --}}
            <div class="row g-3">
                <div class="col-md-6">
                    <button type="submit" name="action" value="print"
                        class="btn btn-outline-dark w-100 py-3 fw-bold shadow-sm">
                        <i class="bi bi-printer-fill me-2"></i> Cetak Struk Tagihan
                    </button>
                </div>
                <div class="col-md-6">
                    <button type="submit" name="action" value="save"
                        class="btn btn-primary w-100 py-3 fw-bold shadow-sm">
                        <i class="bi bi-check-circle-fill me-2"></i> Konfirmasi & Bayar Lanjut
                    </button>
                </div>
            </div>
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

        function toggleBankInfo() {
            const selectElement = document.getElementById('metode_pembayaran');
            const bankInfoDiv = document.getElementById('info_bank');

            if (selectElement.value === 'transfer') {
                bankInfoDiv.style.display = 'block'; // Munculkan
            } else {
                bankInfoDiv.style.display = 'none'; // Sembunyikan jika pilih Bayar di Kantor
            }
        }

        inputLama.addEventListener('input', hitung);
    </script>
@endsection
