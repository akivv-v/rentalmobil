@extends('user.layouts.app')

@section('content')
    <style>
        body {
            background: #e9f5ff;
            font-family: Poppins;
        }

        /* Jarak antar section agar konsisten */
        section {
            margin-top: 150px;
            margin-bottom: 80px;
        }

        .hero-section {
            margin-top: 0 !important;
            margin-bottom: 80px;
        }


        .hero-title {
            font-size: 38px;
            font-weight: 700;
            color: #0e1c36;
        }

        .hero-text {
            color: #4a5568;
            width: 80%
        }

        .btn-primary-custom {
            background: #0056ff;
            color: #fff;
            border-radius: 30px;
            padding: 10px 25px;
            font-weight: 600;
            border: none;
        }

        .feature-card {
            background: #fff;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            text-align: center;
        }

        .feature-card i {
            font-size: 30px;
            margin-bottom: 10px;
            color: #0056ff;
        }

        .section-title {
            font-size: 32px;
            font-weight: 700;
            color: #0e1c36
        }

    </style>

    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    {{-- HERO SECTION --}}
    <section class="hero-section container d-flex justify-content-between align-items-center">
        <div>
            <h1 class="hero-title">Rental mobil untuk <br> segala kebutuhan.</h1>
            <p class="hero-text mt-3">Promo hebat dengan harga menarik, dari perusahaan rental mobil terbesar.</p>
            <a href="{{ route('user.mobil.index') }}" class="btn btn-primary-custom mt-3">
                Lihat Selengkapnya <i class="ri-arrow-right-line"></i>
            </a>
        </div>
        <img src="{{ asset('storage/mobil1.jpg') }}" width="45%" class="rounded-3 shadow">
    </section>

    <section class="container mt-5">
        <h3 class="text-center section-title mb-4">Simpelnya rental mobil disini</h3>
        <div class="row g-4 text-center">
            <div class="col-md-4 d-flex">
                <div class="feature-card h-100 w-100"><i class="ri-map-pin-2-fill"></i>
                    <h5>Pilih Lokasi Kamu</h5>
                    <p>Tentukan lokasi rumah agar unit dapat langsung dibawa!</p>
                </div>
            </div>
            <div class="col-md-4 d-flex">
                <div class="feature-card h-100 w-100"><i class="ri-car-fill"></i>
                    <h5>Pilih Mobil</h5>
                    <p>Pilih unit mobil sesuai kebutuhanmu.</p>
                </div>
            </div>
            <div class="col-md-4 d-flex">
                <div class="feature-card h-100 w-100"><i class="ri-send-plane-fill"></i>
                    <h5>Mobil Meluncur</h5>
                    <p>Unit diantar ke depan rumah kamu!</p>
                </div>
            </div>
        </div>
    </section>

    {{-- LAYANAN ANTAR JEMPUT --}}
    <section class="container my-5 d-flex justify-content-between align-items-center">
        <img src="{{ asset('storage/mobil2.jpg') }}" width="45%" class="rounded-4 shadow">
        <div class="ms-4">
            <h2 class="section-title">Sewa Mobil Antar Jemput</h2>
            <p>Banyak pilihan mobil MPV seperti Avanza, Ertiga, Xenia, dan lainnya.</p>
            <a href="{{ route('user.mobil.index') }}" class="btn btn-primary-custom">Lihat Selengkapnya</a>
        </div>
    </section>
@endsection
