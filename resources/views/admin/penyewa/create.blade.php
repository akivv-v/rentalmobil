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

    .btn-save {
        background: #1e293b;
        color: white;
        border: none;
        padding: 12px 35px;
        border-radius: 12px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-save:hover {
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
                    <h4>Registrasi Penyewa</h4>
                    <p class="text-muted small">Masukkan data diri pelanggan baru secara lengkap</p>
                </div>

                <form action="{{ route('admin.penyewa.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <div class="input-icon-group">
                                <input type="text" name="nama" class="form-control" placeholder="Nama sesuai KTP" required>
                                <i class="bi bi-person"></i>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <div class="input-icon-group">
                                <input type="email" name="email" class="form-control" placeholder="alamat@email.com" required>
                                <i class="bi bi-envelope"></i>
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <div class="input-icon-group">
                                <input type="number" name="no_telp" class="form-control" placeholder="08xxxxxxxxxx" required>
                                <i class="bi bi-whatsapp"></i>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat" rows="3" class="form-control" placeholder="Jl. Nama Jalan No. Rumah, Kota, Provinsi"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Unggah Foto KTP</label>
                        <input type="file" name="foto_ktp" class="form-control">
                        <div class="mt-2 p-3 border rounded-3 bg-light">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle me-2 text-primary"></i>
                                <small class="text-muted">Pastikan foto KTP terlihat jelas dan tidak buram untuk keperluan verifikasi.</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center pt-3">
                        <a href="{{ route('admin.penyewa.index') }}" class="btn-back">Batal</a>
                        <button type="submit" class="btn-save shadow-sm">
                            <i class="bi bi-check-circle me-2"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection