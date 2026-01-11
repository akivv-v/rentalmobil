@extends('user.layouts.app')

@section('content')
    <section class="py-5 rounded-bottom-5">
        <div class="container text-center">
            <h1 class="fw-bolder text-dark mb-3">Pilih Mobil Impianmu</h1>
            <p class="lead text-dark mb-4">Sewa mobil dengan mudah, cepat, dan harga bersahabat</p>

            <div class="row justify-content-center">
                <div class="col-lg-7 col-md-9">
                    <form action="{{ route('user.mobil.index') }}" method="GET" class="input-group shadow-lg rounded-pill">
                        <input type="text" name="search" class="form-control border-0 rounded-start-pill py-3 ps-4"
                            placeholder="Cari Mobil, Avanza, Innova, dll..." value="{{ request('search') }}">
                        <button type="submit" class="btn bg-white border-0 rounded-end-pill px-4">
                            <i class="bi bi-search fs-5 text-secondary"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <hr class="mb-5">

    <div class="container mb-5">
        <div class="row g-4">

            {{-- Jika tidak ada data --}}
            @if ($mobils->isEmpty())
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-info-circle-fill me-2"></i>Maaf, belum ada mobil yang tersedia saat ini.
                    </div>
                </div>
            @endif

            @foreach ($mobils as $mobil)
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">

                        {{-- Gambar --}}
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $mobil->gambar) }}" class="card-img-top rounded-top-4"
                                style="height: 230px; object-fit: cover;" alt="{{ $mobil->nama_mobil }}">

                            <span class="badge position-absolute top-0 end-0 m-3 bg-dark text-white py-2 px-3">
                                {{ ucfirst($mobil->transmisi) }}
                            </span>
                        </div>

                        <div class="card-body p-4">
                            <h4 class="fw-bold text-dark">{{ $mobil->nama_mobil }}</h4>

                            <p class="fw-semibold h5 text-success mb-3">
                                Rp {{ number_format($mobil->harga_sewa, 0, ',', '.') }}
                                <small class="text-muted fs-6">/Hari</small>
                            </p>

                            {{-- Info Mobil --}}
                            <div class="d-flex justify-content-between text-muted small mb-3">
                                <span><i class="bi bi-calendar-fill me-1"></i> {{ $mobil->tahun }}</span>
                                <span><i class="bi bi-car-front-fill me-1"></i> {{ strtoupper($mobil->plat_nomor) }}</span>

                                @if ($mobil->status == 'tersedia')
                                    <span class="text-success"><i class="bi bi-check-circle-fill me-1"></i> Tersedia</span>
                                @else
                                    <span class="text-danger"><i class="bi bi-x-circle-fill me-1"></i> Disewa</span>
                                @endif
                            </div>

                            {{-- Tombol --}}
                            @if (strtolower($mobil->status) == 'tersedia')
                                <a href="{{ route('user.rental.create', $mobil->id) }}" 
                                    class="btn btn-dark d-block rounded-3 fw-semibold py-2">
                                    Sewa Sekarang
                                </a>
                            @else
                                <button class="btn btn-secondary d-block rounded-3 fw-semibold py-2" disabled>
                                    Sedang Disewa
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection
