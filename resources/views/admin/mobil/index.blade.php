@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold" style="color:#1e3038;">Data Mobil</h2>

        <a href="{{ route('admin.mobil.create') }}" class="btn px-4 py-2 fw-semibold shadow"
            style="background:#1e3038; border:none; color:white;">
            + Tambah Mobil
        </a>
    </div>

    {{-- SEARCH --}}
    <form method="GET" action="{{ route('admin.mobil.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari nama mobil atau plat nomor..."
                value="{{ request('search') }}">

            <button class="btn me-1" style="background:#1e3038; color:white;">Search</button>

            @if (request('search'))
                <a href="{{ route('admin.mobil.index') }}" class="btn"
                    style="background:#1e3038; color:white;">Reset</a>
            @endif
        </div>
    </form>

    {{-- ALERT --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="table table-hover align-middle" style="color:white;">
            <thead style="background:#1e3038; color:white;">
                <tr>
                    <th>No</th>
                    <th>Nama Mobil</th>
                    <th>Plat Nomor</th>
                    <th>Harga Sewa</th>
                    <th>Status</th>
                    <th width="200px">Aksi</th>
                </tr>
            </thead>

            <tbody style="background:#49628c;">
                @foreach ($mobil as $m)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $m->nama_mobil }}</td>
                        <td>{{ $m->plat_nomor }}</td>
                        <td>Rp {{ number_format($m->harga_sewa, 0, ',', '.') }}</td>

                        <td>
                            <span class="badge {{ $m->status == 'tersedia' ? 'bg-success' : 'bg-danger' }} px-3 py-2 fw-semibold">
                                {{ ucfirst($m->status) }}
                            </span>
                        </td>

                        <td>
                            <button type="button" class="btn btn-sm"
                                style="background:#1e3038; color:white;"
                                data-bs-toggle="modal" data-bs-target="#modalDetail{{ $m->id }}">
                                Detail
                            </button>

                            <a href="{{ route('admin.mobil.edit', $m->id) }}" class="btn btn-sm"
                                style="background:#1e3038; color:white;">
                                Edit
                            </a>

                            <form action="{{ route('admin.mobil.destroy', $m->id) }}" method="POST"
                                  class="d-inline" onsubmit="return confirm('Yakin hapus mobil ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm" style="background:#1e3038; color:white;">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>

                    {{-- MODAL DETAIL --}}
                    <div class="modal fade" id="modalDetail{{ $m->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content">

                                <div class="modal-header border-0">
                                    <h5 class="modal-title fw-semibold">Detail Mobil</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        style="filter:brightness(0) invert(1);"></button>
                                </div>

                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-md-5 text-center">
                                            @if($m->gambar)
                                                <img src="{{ asset('storage/'.$m->gambar) }}" class="img-fluid rounded shadow mb-3"
                                                     style="max-height:230px; object-fit:cover;">
                                            @endif
                                        </div>

                                        <div class="col-md-7">
                                            <p><strong>Nama Mobil:</strong> {{ $m->nama_mobil }}</p>
                                            <p><strong>Merek:</strong> {{ $m->merk }}</p>
                                            <p><strong>Plat Nomor:</strong> {{ $m->plat_nomor }}</p>
                                            <p><strong>Tahun:</strong> {{ $m->tahun }}</p>
                                            <p><strong>Harga Sewa:</strong> Rp {{ number_format($m->harga_sewa, 0, ',', '.') }}</p>
                                            <p><strong>Status:</strong> {{ ucfirst($m->status) }}</p>
                                            <p><strong>Deskripsi:</strong><br> {{ $m->deskripsi ?: '-' }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer border-0">
                                    <button class="btn px-4" data-bs-dismiss="modal"
                                        style="background:#1e3038; color:white;">Tutup</button>
                                </div>

                            </div>
                        </div>
                    </div>
                    {{-- END MODAL --}}

                @endforeach
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="mt-3">
        {{ $mobil->links() }}
    </div>
@endsection
