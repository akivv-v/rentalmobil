@extends('layouts.app')

@section('content')
<div class="card-auth">
    <h3 class="title">Masuk</h3>
    <p class="subtitle">Selamat datang kembali! Silakan masuk ke akun anda.</p>

    {{-- Pesan Error jika login gagal --}}
    @if(session('error'))
        <div class="alert alert-danger py-2 small text-center">
            {{ session('error') }}
        </div>
    @endif

    {{-- Pesan Sukses setelah registrasi --}}
    @if(session('success'))
        <div class="alert alert-success py-2 small text-center">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('login.store') }}" method="POST">
        @csrf

        <div class="mb-3 text-start">
            <label class="form-label small fw-medium">Email</label>
            <input 
                type="email" 
                name="email" 
                class="form-control" 
                placeholder="Masukkan email anda"
                value="{{ old('email') }}"
                required>
        </div>

        <div class="mb-3 text-start">
            <label class="form-label small fw-medium">Password</label>
            <input 
                type="password" 
                name="password" 
                class="form-control" 
                placeholder="Masukkan password anda"
                required>
        </div>

        <button type="submit" class="btn btn-primary-custom w-100 mt-2 text-white shadow-sm">
            Masuk Sekarang
        </button>
    </form>

    <p class="bottom-link mt-4 text-center">
        Belum punya akun? <a href="{{ route('register') }}" class="fw-bold text-decoration-none">Daftar</a>
    </p>
</div>
@endsection