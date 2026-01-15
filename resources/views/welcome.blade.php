@extends('layouts.guest_landing')

@section('content')
    <style>
        body {
            background: #e9f5ff;
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
            text-decoration: none;
            display: inline-block;
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

        /* PERBAIKAN CARD MOBIL AGAR SAMA BESAR */
        .car-card {
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            /* Memaksa card mengisi ruang kolom */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: none;
            transition: 0.3s;
        }

        .car-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .car-image-wrapper {
            width: 100%;
            height: 200px;
            /* Tinggi gambar konsisten */
            overflow: hidden;
        }

        .car-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* Gambar tidak gepeng, terpotong rapi */
        }

        .card-body {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            /* Membuat body mengisi sisa card */
        }

        .price-tag {
            font-size: 1.2rem;
            font-weight: 800;
            color: #0056ff;
        }

        /* PERBAIKAN CARD ULASAN */
        .review-card {
            background: #fff;
            border-radius: 15px;
            padding: 25px;
            border: none;
            height: 100%;
        }
    </style>

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
                    <img src="{{ asset('storage/mobil1.jpg') }}" alt="Hero Mobil" width="90%"
                        class="rounded-4 shadow-lg">
                </div>
            </div>
        </section>

        {{-- FITUR --}}
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
                <h3 class="section-title">Mobil yang Ada</h3>
                <p class="text-muted">Pilih mobil yang sesuai dengan gaya dan kebutuhan perjalanan Anda.</p>
            </div>

            <div class="row g-4">
                @forelse($mobils as $m)
                    <div class="col-md-4">
                        <div class="car-card">
                            <div class="car-image-wrapper">
                                <img src="{{ asset('storage/' . $m->gambar) }}" alt="{{ $m->nama_mobil }}">
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="fw-bold mb-0">{{ $m->nama_mobil }}</h5>
                                    @if ($m->status == 'tersedia')
                                        <span class="badge bg-success-subtle text-success">
                                            <i class="ri-checkbox-circle-line"></i> {{ ucfirst($m->status) }}
                                        </span>
                                    @elseif($m->status == 'disewa')
                                        <span class="badge bg-danger-subtle text-danger">
                                            <i class="ri-error-warning-line"></i> {{ ucfirst($m->status) }}
                                        </span>
                                    @else
                                        {{-- Untuk status lain seperti 'perbaikan' atau 'maintenance' --}}
                                        <span class="badge bg-warning-subtle text-warning">
                                            <i class="ri-tools-line"></i> {{ ucfirst($m->status) }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-muted small mb-4">
                                    <i class="ri-id-card-line"></i> {{ $m->plat_nomor }} |
                                    <i class="ri-calendar-2-line me-1"></i> {{ $m->tahun }}
                                </p>

                                {{-- Spacer untuk mendorong harga ke bawah --}}
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-muted small">Harga :</span>
                                            <div class="price-tag">Rp {{ number_format($m->harga_sewa, 0, ',', '.') }}<span
                                                    class="fs-6 text-muted fw-normal">/hari</span></div>
                                        </div>
                                        @if ($m->status == 'tersedia')
                                            {{-- Muncul jika tersedia --}}
                                            <a href="{{ route('login') }}" class="btn btn-primary-custom px-3 py-2 fs-6">
                                                Sewa <i class="ri-arrow-right-s-line"></i>
                                            </a>
                                        @else
                                            {{-- Muncul jika status selain 'tersedia' (misal: disewa/perbaikan) --}}
                                            <button class="btn btn-secondary disabled px-3 py-2 fs-6"
                                                style="border-radius: 30px; opacity: 0.7;">
                                                <i class="ri-time-line"></i> {{ ucfirst($m->status) }}
                                            </button>
                                        @endif
                                    </div>
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
                        <div class="review-card shadow-sm h-100">
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-grow-1">
                                    {{-- LOGIKA PRIVASI NAMA: Mikhaila -> Mik****** --}}
                                    <h6 class="fw-bold mb-0">
                                        {{ Str::mask($u->user->name, '*', 3) }}
                                    </h6>

                                    <div class="text-warning">
                                        {{-- LOGIKA BINTANG BERWARNA --}}
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $u->bintang)
                                                <i class="ri-star-fill"></i> {{-- Bintang Kuning Penuh --}}
                                            @else
                                                <i class="ri-star-line"></i> {{-- Bintang Outline Saja --}}
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <i class="ri-double-quotes-r fs-1 text-primary-emphasis opacity-25"></i>
                            </div>
                            <p class="text-muted italic small">"{{ $u->komentar }}"</p>
                            <div class="text-end mt-2 mt-auto">
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
