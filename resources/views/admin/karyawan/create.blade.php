@extends('admin.layouts.app')

@section('content')
    <style>
        .card-custom {
            background: #1e3038;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .25);
            border: none;
        }

        .card-custom h4 {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 25px;
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
            box-shadow: 0 4px 10px rgba(0, 0, 0, .25);
        }
    </style>

    <div class="container-fluid d-flex justify-content-center">
        <div class="col-lg-7">
            <div class="card card-custom">
                <h4>Tambah Karyawan</h4>

                <form action="{{ route('admin.karyawan.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Karyawan</label>
                        <input type="text" name="nama" class="form-control" placeholder="Masukkan nama karyawan..."
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Masukkan email karyawan..."
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor Telp</label>
                        <input type="number" name="no_telp" class="form-control" placeholder="08XXXXXXXXXX" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" rows="3" class="form-control" placeholder="Masukkan alamat karyawan..."></textarea>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="{{ route('admin.karyawan.index') }}" class="btn-action me-2">Kembali</a>

                        <button class="btn-action">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        @endsection
