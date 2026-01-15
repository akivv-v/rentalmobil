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

    .btn-view { background: #eff6ff; color: #2563eb; }
    .btn-edit { background: #f0fdf4; color: #16a34a; }
    .btn-delete { background: #fef2f2; color: #dc2626; }

    .btn-action:hover { transform: translateY(-2px); opacity: 0.8; }

    .avatar-circle {
        width: 40px;
        height: 40px;
        background: #f1f5f9;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 700;
        font-size: 0.8rem;
    }
</style>

<div class="container-fluid py-4">
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Data Penyewa</h2>
            <p class="text-muted small">Kelola informasi pelanggan dan verifikasi identitas</p>
        </div>
        <a href="{{ route('admin.penyewa.create') }}" class="btn btn-dark px-4 py-2 fw-bold shadow-sm" style="border-radius: 12px;">
            <i class="bi bi-person-plus me-2"></i> Tambah Penyewa
        </a>
    </div>

    {{-- SEARCH --}}
    <div class="card-main p-3 mb-4">
        <form method="GET" action="{{ route('admin.penyewa.index') }}">
            <div class="input-group">
                <input type="text" name="search" class="form-control search-input shadow-none" 
                       placeholder="Cari nama, email, atau no telp..." value="{{ $search ?? '' }}">
                <button class="btn search-btn" type="submit">
                    <i class="bi bi-search"></i>
                </button>
                @if ($search)
                    <a href="{{ route('admin.penyewa.index') }}" class="btn btn-outline-secondary ms-2 rounded-3">Reset</a>
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

    {{-- TABLE --}}
    <div class="card-main overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Profil</th>
                        <th>Kontak</th>
                        <th>Alamat</th>
                        <th class="text-center">KTP</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($penyewas as $p)
                        <tr>
                            <td class="text-center text-muted">{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-circle me-3">
                                        {{ strtoupper(substr($p->nama, 0, 2)) }}
                                    </div>
                                    <div>
                                        <span class="d-block fw-bold text-dark">{{ $p->nama }}</span>
                                        <small class="text-muted">{{ $p->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    <i class="bi bi-telephone text-muted me-1"></i> {{ $p->no_telp }}
                                </div>
                            </td>
                            <td>
                                <p class="small text-muted mb-0 text-truncate" style="max-width: 200px;">
                                    {{ $p->alamat ?? '-' }}
                                </p>
                            </td>
                            <td class="text-center">
                                @if ($p->foto_ktp)
                                    <button class="btn btn-sm btn-light border fw-bold px-3 shadow-sm" 
                                            style="border-radius: 8px; font-size: 0.7rem;"
                                            data-bs-toggle="modal" data-bs-target="#modalKTP{{ $p->id }}">
                                        <i class="bi bi-card-image me-1 text-primary"></i> LIHAT
                                    </button>
                                @else
                                    <span class="badge bg-light text-muted">Belum Ada</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.penyewa.edit', $p->id) }}" class="btn-action btn-edit" title="Edit">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.penyewa.destroy', $p->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin ingin menghapus data penyewa?')">
                                    @csrf @method('DELETE')
                                    <button class="btn-action btn-delete" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- MODAL FOTO KTP --}}
                        <div class="modal fade" id="modalKTP{{ $p->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content border-0 shadow" style="border-radius: 25px;">
                                    <div class="modal-header border-0 px-4 pt-4">
                                        <h5 class="fw-bold">Verifikasi Identitas</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-4 text-center">
                                        <div class="bg-light p-3 rounded-4">
                                            <img src="{{ asset('storage/' . $p->foto_ktp) }}" class="img-fluid rounded-3 shadow-sm">
                                        </div>
                                        <div class="mt-3">
                                            <h6 class="fw-bold mb-0">{{ $p->nama }}</h6>
                                            <small class="text-muted">KTP Penyewa</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Tidak ada data penyewa ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $penyewas->links() }}
    </div>
</div>
@endsection