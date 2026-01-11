@extends('admin.layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold" style="color:#1e3038;">Data Penyewa</h2>

        <a href="{{ route('admin.karyawan.create') }}" class="btn px-4 py-2 fw-semibold shadow"
            style="background:#1e3038; border:none; color:white;">
            + Tambah Karyawan
        </a>
    </div>

    <form method="GET" action="{{ route('admin.karyawan.index') }}" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari nama, email, atau no telp..."
                value="{{ $search }}">

            <button class="btn me-1" style="background:#1e3038; color:white;">Search</button>

            @if ($search)
                <a href="{{ route('admin.karyawan.index') }}" class="btn"
                    style="background: #1e3038; color: white;">Reset</a>
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
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($karyawans as $k)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $k->nama }}</td>
                    <td>{{ $k->email }}</td>
                    <td>{{ $k->no_telp }}</td>
                    <td>{{ $k->alamat }}</td>

                    <td>
                        <a href="{{ route('admin.karyawan.edit', $k->id) }}" class="btn btn-sm" style="background: #1e3038; color:white;">Edit</a>

                        <form action="{{ route('admin.karyawan.destroy', $k->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Hapus karyawan ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm" style="background: #1e3038; color:white;">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-3">{{ $karyawans->links() }}</div>
    </div>
@endsection
