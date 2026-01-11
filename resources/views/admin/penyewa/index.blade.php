@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold" style="color:#1e3038;">Data Penyewa</h2>

        <a href="{{ route('admin.penyewa.create') }}" class="btn px-4 py-2 fw-semibold shadow"
            style="background:#1e3038; border:none; color:white;">
            + Tambah Penyewa
        </a>
    </div>

    <form method="GET" action="{{ route('admin.penyewa.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari nama, email, no telp..."
                value="{{ $search ?? '' }}">

            <button class="btn me-1" style="background:#1e3038; color:white;">Search</button>

            @if ($search)
                <a href="{{ route('admin.penyewa.index') }}" class="btn" style="background:#1e3038; color:white;">
                    Reset
                </a>
            @endif
        </div>
    </form>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-hover" style="color:white;">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No Telp</th>
                <th>Alamat</th>
                <th>Foto KTP</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($penyewas as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->email }}</td>
                    <td>{{ $p->no_telp }}</td>
                    <td>{{ $p->alamat ?? '-' }}</td>

                    <td>
                        @if ($p->foto_ktp)
                            <button class="btn btn-sm" style="background: #1e3038; color:white;" data-bs-toggle="modal"
                                data-bs-target="#modalKTP{{ $p->id }}">
                                Lihat Foto
                            </button>
                        @else
                            <span class="badge bg-secondary">Tidak ada</span>
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('admin.penyewa.edit', $p->id) }}" class="btn btn-sm"
                            style="background: #1e3038; color:white;">Edit</a>

                        <form action="{{ route('admin.penyewa.destroy', $p->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus data penyewa?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm" style="background: #1e3038; color:white;">Hapus</button>
                        </form>
                    </td>
                </tr>

                {{-- MODAL FOTO --}}
                <div class="modal fade" id="modalKTP{{ $p->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content" style="background:#1e3038; color:white;">
                            <div class="modal-header" style="background:#49628c;">
                                <h5 class="modal-title">Foto KTP - {{ $p->nama }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="{{ asset('storage/' . $p->foto_ktp) }}" class="img-fluid rounded">
                            </div>
                        </div>
                    </div>
                </div>

            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- PAGINATION --}}
    <div class="mt-3">
        {{ $penyewas->links() }}
    </div>
    </div>
@endsection
