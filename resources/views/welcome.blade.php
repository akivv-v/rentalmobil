@extends('layouts.guest_landing')

@section('content')
    <style>
        body {
            background: #e9f5ff;
            font-family: 'Poppins', sans-serif;
        }

        section {
            margin-top: 100px;
            margin-bottom: 80px;
        }

        .hero-section {
            margin-top: 50px !important;
        }

        .hero-title {
            font-size: 42px;
            font-weight: 700;
            color: #0e1c36;
        }

        .hero-text {
            color: #4a5568;
            width: 85%;
            font-size: 1.1rem;
        }

        .btn-primary-custom {
            background: #0056ff;
            color: #fff;
            border-radius: 30px;
            padding: 12px 30px;
            font-weight: 600;
            border: none;
            transition: 0.3s;
        }

        .btn-primary-custom:hover {
            background: #0041c2;
            color: #fff;
            transform: translateY(-2px);
        }

        .feature-card {
            background: #fff;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            text-align: center;
            border: none;
        }

        .feature-card i {
            font-size: 40px;
            color: #0056ff;
            margin-bottom: 15px;
            display: block;
        }

        .section-title {
            font-size: 32px;
            font-weight: 700;
            color: #0e1c36;
        }
    </style>

    {{-- Pastikan Library Icon RemixIcon ada --}}
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <div class="container">
        {{-- HERO SECTION --}}
        <section class="hero-section">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="hero-title">Solusi Rental Mobil <br> Terpercaya & Nyaman.</h1>
                    <p class="hero-text mt-3">Nikmati perjalanan Anda dengan armada terbaik kami. Harga transparan dan
                        pelayanan prima.</p>
                    <div class="mt-4">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-primary-custom">
                                Mulai Sewa Sekarang <i class="ri-arrow-right-line"></i>
                            </a>
                        @else
                            <a href="{{ auth()->user()->role == 'admin' ? route('admin.dashboard') : route('user.dashboard') }}"
                                class="btn btn-primary-custom">
                                Ke Dashboard Saya <i class="ri-dashboard-line"></i>
                            </a>
                        @endguest
                    </div>
                </div>
                <div class="col-md-6 text-center">
                    {{-- Pastikan file gambar ini ada di public/storage/ atau ganti dengan URL gambar lain --}}
                    <img src="{{ asset('storage/mobil1.jpg') }}" alt="Hero Mobil" width="90%"
                        class="rounded-4 shadow-lg">
                </div>
            </div>
        </section>

        {{-- FITUR SIMPLE --}}
        <section>
            <h3 class="text-center section-title mb-5">Kenapa Memilih Kami?</h3>
            <div class="row g-4 text-center">
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <i class="ri-shield-check-fill"></i>
                        <h5 class="fw-bold">Aman & Terpercaya</h5>
                        <p class="text-muted">Proses legalitas lengkap dan unit mobil dalam kondisi prima.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <i class="ri-price-tag-3-fill"></i>
                        <h5 class="fw-bold">Harga Kompetitif</h5>
                        <p class="text-muted">Tarif sewa transparan tanpa ada biaya tambahan yang tersembunyi.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card h-100">
                        <i class="ri-customer-service-2-fill"></i>
                        <h5 class="fw-bold">Layanan 24 Jam</h5>
                        <p class="text-muted">Tim kami siap membantu Anda kapanpun dibutuhkan selama perjalanan.</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- SECTION DAFTAR MOBIL --}}
        <section id="mobil">
            <div class="text-center mb-5">
                <div>
                    <h3 class="section-title">Mobil yang Tersedia</h3>
                    <p class="text-muted">Pilih mobil yang sesuai dengan gaya dan kebutuhan perjalanan Anda.</p>
                </div>
            </div>

            <div class="row g-4">
                @forelse($mobils as $m)
                    <div class="col-md-4">
                        <div class="car-card h-100">
                            <div class="car-image-wrapper">
                                <img src="{{ asset('storage/' . $m->gambar) }}" alt="{{ $m->nama_mobil }}">
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="fw-bold mb-0">{{ $m->nama_mobil }}</h5>
                                    <span class="badge bg-success-subtle text-success">Tersedia</span>
                                </div>
                                <p class="text-muted small mb-3">
                                    <i class="ri-id-card-line"></i> {{ $m->plat_nomor}} | 
                                    <i class="ri-calendar-2-line me-1"></i> {{ $m->tahun }}
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="text-muted small">Harga :</span>
                                        <div class="price-tag">Rp {{ number_format($m->harga_sewa, 0, ',', '.') }}<span class="fs-6 text-muted fw-normal">/hari</span></div>
                                    </div>
                                    <a href="{{ route('login') }}" class="btn btn-primary-custom px-3 py-2 fs-6">
                                        Sewa <i class="ri-arrow-right-s-line"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Belum ada mobil yang tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>
        </section>

        {{-- SECTION ULASAN --}}
        <section id="ulasan">
            <div class="text-center mb-5">
                <h3 class="section-title">Apa Kata Mereka?</h3>
                <p class="text-muted">Testimoni nyata dari pelanggan setia RentsBill.</p>
            </div>

            <div class="row g-4">
                @forelse($ulasans as $u)
                    <div class="col-md-4">
                        <div class="review-card h-100 shadow-sm">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-0">{{ $u->user->name }}</h6>
                                    <div class="text-warning">
                                        @for($i=1; $i<=5; $i++)
                                            <i class="ri-star-{{ $i <= $u->rating ? 'fill' : 'line' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <i class="ri-double-quotes-r fs-1 text-primary-emphasis opacity-25"></i>
                            </div>
                            <p class="text-muted italic small">"{{ $u->komentar }}"</p>
                            <div class="text-end mt-2">
                                <small class="text-muted">{{ $u->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-md-4 mx-auto text-center">
                        <p class="text-muted">Belum ada ulasan.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </div>
@endsection
