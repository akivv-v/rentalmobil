@extends('admin.layouts.app')

@section('content')
    <style>
        .card-custom {
            background: #1e3038;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 10px rgba(0,0,0,.25);
            border: none;
        }

        .card-custom h4 {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 25px;
            letter-spacing: .5px;
        }

        .form-label {
            color: #ffffff;
            font-weight: 500;
        }

        .form-control,
        .form-select {
            background: #fff;
            border: 1px solid #49628c;
            border-radius: 8px;
        }

        .preview-img {
            border-radius: 8px;
            margin-top: 10px;
            border: 2px solid #49628c;
        }

        .btn-action {
            background: #49628c;
            border: none;
            padding: 10px 32px;
            border-radius: 10px;
            font-weight: 600;
            color: white;
            text-decoration: none;
            display: inline-block;
            transition: .25s;
        }

        .btn-action:hover {
            background: #435a7a;
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0,0,0,.25);
        }
    </style>

    <div class="container-fluid d-flex justify-content-center">
        <div class="col-lg-7">
            <div class="card card-custom">
                <h4>Edit Mobil</h4>

                <form action="{{ route('admin.mobil.update', $mobil->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Mobil</label>
                        <input type="text" name="nama_mobil" class="form-control"
                               value="{{ $mobil->nama_mobil }}" placeholder="Masukkan nama mobil..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Merk</label>
                        <input type="text" name="merk" class="form-control"
                               value="{{ $mobil->merk }}" placeholder="Masukkan merk mobil...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Plat Nomor</label>
                        <input type="text" name="plat_nomor" class="form-control"
                               value="{{ $mobil->plat_nomor }}" placeholder="Contoh: B 1234 XYZ" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tahun</label>
                        <input type="number" name="tahun" class="form-control"
                               value="{{ $mobil->tahun }}" placeholder="Tahun pembuatan mobil...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga Sewa / Hari</label>
                        <input type="number" name="harga_sewa" class="form-control"
                               value="{{ $mobil->harga_sewa }}" placeholder="Masukkan harga sewa..." required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Mobil</label>
                        <select name="status" class="form-select">
                            <option value="tersedia" {{ $mobil->status == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="disewa" {{ $mobil->status == 'disewa' ? 'selected' : '' }}>Disewa</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar Mobil</label>
                        <input type="file" name="gambar" class="form-control">

                        @if($mobil->gambar)
                            <img src="{{ asset('storage/' . $mobil->gambar) }}" width="140"
                                 class="preview-img" alt="Gambar Mobil">
                        @endif
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="form-control"
                                  placeholder="Masukkan deskripsi mobil...">{{ $mobil->deskripsi }}</textarea>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('admin.mobil.index') }}" class="btn-action me-2">Kembali</a>

                        <button class="btn-action">
                            Update
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
