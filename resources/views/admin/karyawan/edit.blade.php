@extends('admin.layouts.app')

@section('content')
<style>
    body { background-color: #f8f9fa; }

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
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
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
                    <h4>Perbarui Data Karyawan</h4>
                    <p class="text-muted small">Mengedit informasi staf <span class="fw-bold text-dark">{{ $karyawan->nama }}</span></p>
                </div>

                <form action="{{ route('admin.karyawan.update', $karyawan->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf 
                    @method('PUT')

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <div class="input-icon-group">
                                <input type="text" name="nama" class="form-control shadow-none" value="{{ old('nama', $karyawan->nama) }}" placeholder="Masukkan nama lengkap..." required>
                                <i class="bi bi-person-vcard"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <div class="input-icon-group">
                                <input type="email" name="email" class="form-control shadow-none" value="{{ old('email', $karyawan->email) }}" placeholder="email@perusahaan.com" required>
                                <i class="bi bi-envelope"></i>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <div class="input-icon-group">
                                <input type="number" name="no_telp" class="form-control shadow-none" value="{{ old('no_telp', $karyawan->no_telp) }}" placeholder="08XXXXXXXXXX" required>
                                <i class="bi bi-telephone"></i>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jabatan / Posisi</label>
                        <div class="input-icon-group">
                            <input type="text" name="jabatan" class="form-control shadow-none" value="{{ old('jabatan', $karyawan->jabatan) }}" placeholder="Contoh: Admin Operasional / Driver">
                            <i class="bi bi-briefcase"></i>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Alamat Domisili</label>
                        <textarea name="alamat" rows="3" class="form-control shadow-none" placeholder="Masukkan alamat lengkap karyawan...">{{ old('alamat', $karyawan->alamat) }}</textarea>
                    </div>

                    <div class="d-flex justify-content-center pt-2">
                        <a href="{{ route('admin.karyawan.index') }}" class="btn-back">Batal</a>
                        <button type="submit" class="btn-update shadow-sm">
                            <i class="bi bi-arrow-clockwise me-2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection