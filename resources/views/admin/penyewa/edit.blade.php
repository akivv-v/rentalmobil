@extends('admin.layouts.app')

@section('content')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card-form {
            background: #ffffff;
            border-radius: 20px;
            padding: 40px;
            border: 1px solid #eef2f7;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        }

        .form-header h4 {
            color: #1e293b;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .form-label {
            color: #64748b;
            font-weight: 600;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .form-control {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 15px;
            color: #334155;
            transition: 0.3s;
        }

        .form-control:focus {
            background: #fff;
            border-color: #1e293b;
            box-shadow: 0 0 0 4px rgba(30, 41, 59, 0.05);
        }

        .preview-ktp-container {
            background: #f8fafc;
            border-radius: 15px;
            padding: 15px;
            border: 1px dashed #cbd5e1;
            display: inline-block;
            margin-top: 10px;
        }

        .btn-update {
            background: #1e293b;
            color: white;
            border: none;
            padding: 12px 35px;
            border-radius: 12px;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-update:hover {
            background: #334155;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-back {
            background: #f1f5f9;
            color: #475569;
            border: none;
            padding: 12px 35px;
            border-radius: 12px;
            font-weight: 600;
            margin-right: 10px;
            text-decoration: none;
            display: inline-block;
            transition: 0.3s;
        }

        .btn-back:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        .input-icon-group {
            position: relative;
        }

        .input-icon-group i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
    </style>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-form">
                    <div class="form-header mb-4 text-center">
                        <h4>Edit Data Penyewa</h4>
                        <p class="text-muted small">Perbarui informasi profil pelanggan <span
                                class="fw-bold text-dark">{{ $penyewa->nama }}</span></p>
                    </div>

                    <form action="{{ route('admin.penyewa.update', $penyewa->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <div class="input-icon-group">
                                    <input type="text" name="nama" class="form-control shadow-none"
                                        value="{{ old('nama', $penyewa->nama) }}" placeholder="Nama sesuai KTP" required>
                                    <i class="bi bi-person"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <div class="input-icon-group">
                                    <input type="email" name="email" class="form-control shadow-none"
                                        value="{{ old('email', $penyewa->email) }}" placeholder="alamat@email.com" required>
                                    <i class="bi bi-envelope"></i>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nomor Telepon</label>
                                <div class="input-icon-group">
                                    <input type="number" name="no_telp" class="form-control shadow-none"
                                        value="{{ old('no_telp', $penyewa->no_telp) }}" placeholder="08xxxxxxxxxx" required>
                                    <i class="bi bi-whatsapp"></i>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea name="alamat" rows="3" class="form-control shadow-none"
                                placeholder="Jl. Nama Jalan No. Rumah, Kota, Provinsi">{{ old('alamat', $penyewa->alamat) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Foto KTP</label>
                            <input type="file" name="foto_ktp" class="form-control shadow-none">

                            @if ($penyewa->foto_ktp)
                                <div class="preview-ktp-container shadow-sm mt-3">
                                    <small class="text-muted d-block mb-2 fw-bold">KTP Terdaftar:</small>
                                    <img src="{{ asset('storage/' . $penyewa->foto_ktp) }}" class="rounded-3"
                                        style="max-width: 200px; border: 1px solid #e2e8f0;">
                                </div>
                            @endif
                            <small class="text-muted mt-2 d-block font-italic">* Kosongkan jika tidak ingin mengganti foto
                                KTP</small>
                        </div>

                        <div class="d-flex justify-content-center pt-3">
                            <a href="{{ route('admin.penyewa.index') }}" class="btn-back">Batal</a>
                            <button type="submit" class="btn-update shadow-sm">
                                <i class="bi bi-arrow-repeat me-2"></i> Update Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
