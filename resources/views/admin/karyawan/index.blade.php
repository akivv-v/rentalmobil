@extends('admin.layouts.app')

@section('content')
<style>
    body { background-color: #f8f9fa; }
    
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
        text-decoration: none;
    }

    .btn-edit { background: #f0fdf4; color: #16a34a; }
    .btn-delete { background: #fef2f2; color: #dc2626; }

    .btn-action:hover { transform: translateY(-2px); opacity: 0.8; }

    .badge-role {
        background: #f1f5f9;
        color: #475569;
        font-weight: 600;
        padding: 5px 12px;
        border-radius: 8px;
        font-size: 0.7rem;
    }
</style>

<div class="container-fluid py-4">
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Data Karyawan</h2>
            <p class="text-muted small">Manajemen staf dan hak akses operasional</p>
        </div>
        <a href="{{ route('admin.karyawan.create') }}" class="btn btn-dark px-4 py-2 fw-bold shadow-sm" style="border-radius: 12px;">
            <i class="bi bi-person-plus-fill me-2"></i> Tambah Karyawan
        </a>
    </div>

    {{-- SEARCH BAR --}}
    <div class="card-main p-3 mb-4">
        <form method="GET" action="{{ route('admin.karyawan.index') }}">
            <div class="input-group">
                <input type="text" name="search" class="form-control search-input shadow-none" 
                       placeholder="Cari nama, email, atau no telp..." value="{{ $search }}">
                <button class="btn search-btn" type="submit">
                    <i class="bi bi-search"></i>
                </button>
                @if ($search)
                    <a href="{{ route('admin.karyawan.index') }}" class="btn btn-outline-secondary ms-2 rounded-3">Reset</a>
                @endif
            </div>
        </form>
    </div>

    {{-- NOTIFIKASI --}}
    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif

    {{-- TABLE --}}
    <div class="card-main overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Karyawan</th>
                        <th>Kontak</th>
                        <th>Alamat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($karyawans as $k)
                        <tr>
                            <td class="text-center text-muted small">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        <i class="bi bi-person text-secondary"></i>
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-dark">{{ $k->nama }}</span>
                                        <small class="text-muted">{{ $k->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="small d-block"><i class="bi bi-telephone text-muted me-2"></i>{{ $k->no_telp }}</span>
                            </td>
                            <td>
                                <p class="small text-muted mb-0 text-truncate" style="max-width: 250px;">
                                    {{ $k->alamat }}
                                </p>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.karyawan.edit', $k->id) }}" class="btn-action btn-edit" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.karyawan.destroy', $k->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Hapus karyawan ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn-action btn-delete" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $karyawans->links() }}
    </div>
</div>
@endsection