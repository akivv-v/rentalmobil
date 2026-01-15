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

        .form-control,
        .form-select {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 15px;
            color: #334155;
            transition: 0.3s;
        }

        .form-control:focus,
        .form-select:focus {
            background: #fff;
            border-color: #49628c;
            box-shadow: 0 0 0 4px rgba(73, 98, 140, 0.1);
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

        /* Styling khusus file upload */
        input[type="file"]::file-selector-button {
            background: #49628c;
            color: white;
            border: none;
            padding: 5px 15px;
            border-radius: 8px;
            margin-right: 15px;
            cursor: pointer;
        }
    </style>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card-form">
                    <div class="form-header mb-4 text-center">
                        <h4>Tambah Unit Mobil</h4>
                        <p class="text-muted small">Lengkapi formulir di bawah untuk menambah armada baru</p>
                    </div>

                    <form action="{{ route('admin.mobil.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nama Mobil</label>
                                <input type="text" name="nama_mobil" class="form-control"
                                    placeholder="Contoh: Avanza Veloz" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Merk</label>
                                <input type="text" name="merk" class="form-control" placeholder="Contoh: Toyota">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Plat Nomor</label>
                                <input type="text" name="plat_nomor" class="form-control" placeholder="B 1234 ABC"
                                    required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tahun Pembuatan</label>
                                <input type="number" name="tahun" class="form-control" placeholder="2023">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Harga Sewa / Hari</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"
                                        style="border-radius: 12px 0 0 12px;">Rp</span>
                                    <input type="number" name="harga_sewa" class="form-control" placeholder="500000"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status Awal</label>
                                <select name="status" class="form-select">
                                    <option value="tersedia">Tersedia (Siap Sewa)</option>
                                    <option value="disewa">Disewa (Sedang Berjalan)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Foto Mobil</label>
                            <input type="file" name="gambar" class="form-control">
                            <small class="text-muted mt-1 d-block font-italic">* Format: JPG, PNG, WEBP (Maks 2MB)</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Deskripsi & Spesifikasi</label>
                            <textarea name="deskripsi" rows="4" class="form-control"
                                placeholder="Masukkan detail fitur (Contoh: Matic, Sunroof, 7 Seater...)"></textarea>
                        </div>

                        <div class="d-flex justify-content-center pt-3">
                            <a href="{{ route('admin.mobil.index') }}" class="btn-back">Batal</a>
                            <button type="submit" class="btn-save shadow-sm">
                                <i class="bi bi-cloud-arrow-up me-2"></i> Simpan Unit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
