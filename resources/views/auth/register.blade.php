@extends('layouts.app')

@section('content')
    <h3 class="title">Daftar Akun</h3>
    <p class="subtitle">Bergabunglah dengan RentsBill dan sewa mobil impianmu.</p>

    @if($errors->any())
        <div class="alert alert-danger py-2 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </div>
    @endif

    <form action="{{ route('register.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label small fw-medium">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-medium">Email</label>
            <input type="email" name="email" class="form-control" placeholder="name@example.com" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label small fw-medium">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
        </div>

        <button type="submit" class="btn btn-primary-custom w-100 mt-2 text-white">Buat Akun Sekarang</button>
    </form>

    <p class="bottom-link mt-4 text-center">
        Sudah punya akun? <a href="{{ route('login') }}" class="fw-bold text-decoration-none">Masuk</a>
    </p>
@endsection
