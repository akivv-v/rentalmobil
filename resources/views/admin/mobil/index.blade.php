@extends('admin.layouts.app')

@section('content')
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card-main {
            background: #ffffff;
            border-radius: 20px;
            border: 1px solid #eef2f7;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02);
        }

        .table thead th {
            background: #f8fafc;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            color: #64748b;
            padding: 15px 25px;
            border: none;
        }

        .table tbody td {
            padding: 15px 25px;
            color: #334155;
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }

        .search-input {
            border-radius: 12px 0 0 12px !important;
            border: 1px solid #e2e8f0;
            padding: 12px 20px;
        }

        .search-btn {
            border-radius: 0 12px 12px 0 !important;
            background: #1e293b;
            color: white;
            padding: 0 25px;
        }

        .btn-action {
            width: 35px;
            height: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            transition: 0.3s;
            border: none;
            margin: 0 2px;
        }

        .btn-detail {
            background: #eff6ff;
            color: #2563eb;
        }

        .btn-edit {
            background: #f0fdf4;
            color: #16a34a;
        }

        .btn-delete {
            background: #fef2f2;
            color: #dc2626;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            opacity: 0.8;
        }

        .badge-status {
            padding: 6px 14px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .bg-tersedia {
            background: #f0fdf4;
            color: #16a34a;
        }

        .bg-disewa {
            background: #fef2f2;
            color: #dc2626;
        }
    </style>

    <div class="container-fluid py-4">
        {{-- HEADER & TOMBOL TAMBAH --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-dark mb-1">Data Mobil</h2>
                <p class="text-muted small">Kelola armada dan status ketersediaan unit</p>
            </div>
            <a href="{{ route('admin.mobil.create') }}" class="btn btn-dark px-4 py-2 fw-bold shadow-sm"
                style="border-radius: 12px;">
                <i class="bi bi-plus-lg me-2"></i> Tambah Mobil
            </a>
        </div>

        {{-- SEARCH BAR --}}
        <div class="card-main p-3 mb-4">
            <form method="GET" action="{{ route('admin.mobil.index') }}">
                <div class="input-group">
                    <input type="text" name="search" class="form-control search-input shadow-none"
                        placeholder="Cari nama mobil atau plat nomor..." value="{{ request('search') }}">
                    <button class="btn search-btn" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                    @if (request('search'))
                        <a href="{{ route('admin.mobil.index') }}"
                            class="btn btn-outline-secondary ms-2 rounded-3">Reset</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        {{-- TABLE CARD --}}
        <div class="card-main overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th class="text-center">No</th>
                            <th>Info Mobil</th>
                            <th>Plat Nomor</th>
                            <th>Harga Sewa</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($mobil as $m)
                            <tr>
                                <td class="text-center text-muted">
                                    {{ ($mobil->currentPage() - 1) * $mobil->perPage() + $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-3 p-2 me-3">
                                            <i class="bi bi-car-front text-secondary fs-4"></i>
                                        </div>
                                        <div>
                                            <span class="d-block fw-bold text-dark">{{ $m->nama_mobil }}</span>
                                            <small class="text-muted">{{ $m->merk }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border fw-medium">{{ $m->plat_nomor }}</span></td>
                                <td class="fw-bold text-dark">Rp {{ number_format($m->harga_sewa, 0, ',', '.') }}<small
                                        class="text-muted fw-normal">/hari</small></td>
                                <td>
                                    <span
                                        class="badge-status {{ $m->status == 'tersedia' ? 'bg-tersedia' : 'bg-disewa' }}">
                                        <i class="bi bi-circle-fill me-1" style="font-size: 0.5rem;"></i>
                                        {{ ucfirst($m->status) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <button class="btn-action btn-detail" title="Detail" data-bs-toggle="modal"
                                        data-bs-target="#modalDetail{{ $m->id }}">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <a href="{{ route('admin.mobil.edit', $m->id) }}" class="btn-action btn-edit"
                                        title="Edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('admin.mobil.destroy', $m->id) }}" method="POST"
                                        class="d-inline" onsubmit="return confirm('Yakin hapus mobil ini?')">
                                        @csrf @method('DELETE')
                                        <button class="btn-action btn-delete" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            {{-- MODAL DETAIL (ELEGANT STYLE) --}}
                            <div class="modal fade" id="modalDetail{{ $m->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content border-0 shadow" style="border-radius: 25px;">
                                        <div class="modal-header border-0 px-4 pt-4">
                                            <h5 class="fw-bold">Detail Unit</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body px-4 pb-4">
                                            <div class="row g-4">
                                                <div class="col-md-5">
                                                    @if ($m->gambar)
                                                        <img src="{{ asset('storage/' . $m->gambar) }}"
                                                            class="img-fluid rounded-4 shadow-sm w-100"
                                                            style="height: 250px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded-4 d-flex align-items-center justify-content-center"
                                                            style="height: 250px;">
                                                            <i class="bi bi-image text-muted fs-1"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="row g-3">
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">Nama Mobil</small>
                                                            <span class="fw-bold">{{ $m->nama_mobil }}</span>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">Merek</small>
                                                            <span class="fw-bold">{{ $m->merk }}</span>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">Plat Nomor</small>
                                                            <span
                                                                class="badge bg-light text-dark border">{{ $m->plat_nomor }}</span>
                                                        </div>
                                                        <div class="col-6">
                                                            <small class="text-muted d-block">Tahun</small>
                                                            <span class="fw-bold">{{ $m->tahun }}</span>
                                                        </div>
                                                        <div class="col-12 mt-3">
                                                            <small class="text-muted d-block">Deskripsi</small>
                                                            <p class="text-dark small">
                                                                {{ $m->deskripsi ?: 'Tidak ada deskripsi.' }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- PAGINATION --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $mobil->links() }}
        </div>
    </div>
@endsection
