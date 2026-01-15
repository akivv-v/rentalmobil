<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'User - Rent\'s Bill' }}</title>

    {{-- BOOTSTRAP --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- ICONS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css">

    {{-- GOOGLE FONT --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    {{-- CUSTOM CSS --}}
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: #E8F2FF;
        }

        /* Navbar */


        .content-wrapper {
            padding-top: 90px;
        }
    </style>
</head>

<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg bg-white shadow-sm fixed-top">
        <div class="container">

            {{-- LOGO --}}
            <a class="navbar-brand fw-bold d-flex align-items-center text-dark" href="{{ route('user.dashboard') }}">
                <i class="bi bi-car-front-fill me-2 fs-4"></i> RentsBill
            </a>

            {{-- MOBILE TOGGLER --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- MENU --}}
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav gap-4">

                    <li class="nav-item">
                        <a class="nav-link fw-semibold {{ request()->routeIs('user.dashboard') ? 'text-primary' : '' }}"
                            href="{{ route('user.dashboard') }}">
                            Beranda
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold {{ request()->routeIs('user.mobil.index') ? 'text-primary' : '' }}"
                            href="{{ route('user.mobil.index') }}">
                            Mobil
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link fw-semibold {{ request()->routeIs('user.ulasan.index') ? 'text-primary' : '' }}"
                            href="{{ route('user.ulasan.index') }}">
                            Ulasan
                        </a>
                    </li>

                </ul>
            </div>

            {{-- AKUN / LOGOUT --}}
            {{-- USER DROPDOWN --}}
            @if (Auth::check())
                <div class="dropdown">
                    <button class="btn btn-outline-dark btn-sm dropdown-toggle fw-bold" type="button"
                        data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> Hai, {{ Auth::user()->name ?? 'User' }}
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li>
                            <a class="dropdown-item" href="{{ route('user.riwayat') }}">
                                <i class="bi bi-receipt"></i> Riwayat sewa
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item bg-transparent border-0">
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @else
                <a href="{{ route('login') }}" class="btn btn-dark btn-sm">
                    Masuk <i class="bi bi-box-arrow-in-right ms-1"></i>
                </a>
            @endif

        </div>
    </nav>


    {{-- PAGE CONTENT --}}
    <div class="content-wrapper container">
        @yield('content')
    </div>

    {{-- FOOTER --}}
    <footer class="bg-dark text-light pt-5 pb-3 mt-5">
        <div class="container">
            <div class="row">
                <!-- Brand -->
                <div class="col-md-4 mb-4">
                    <h4 class="fw-bold mb-3"><i class="bi bi-car-front-fill"></i> RentsBill</h4>
                    <p class="text-white-50 small">
                        Promo hebat dengan harga menarik, dari perusahaan rental mobil terbesar.
                        Temukan mobil terbaik untuk perjalananmu!
                    </p>
                </div>

                <!-- Menu -->
                <div class="col-md-2 col-6 mb-4 me-3 ms-3">
                    <h6 class="fw-bold mb-3">Menu</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('user.dashboard') }}"
                                class="text-white-50 text-decoration-none">Beranda</a></li>
                        <li class="mb-2"><a href="{{ route('user.mobil.index') }}"
                                class="text-white-50 text-decoration-none">Mobil</a></li>
                        <li class="mb-2"><a href="{{ route('user.ulasan.index') }}"
                                class="text-white-50 text-decoration-none">Ulasan</a></li>
                    </ul>
                </div>

                <!-- Support -->
                <div class="col-md-2 col-6 mb-4 me-3 ms-3">
                    <h6 class="fw-bold mb-3">Layanan</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Admin ramah</a>
                        </li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Harga murah</a>
                        </li>
                        <li class="mb-2"><a href="#" class="text-white-50 text-decoration-none">Mobil
                                berkualitas</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="col-md-2 col-6 mb-4 ms-5">
                    <h6 class="fw-bold mb-3">Hubungi Kami</h6>
                    <p class="small text-white-50">
                        WA: 0899-2749-762<br>
                        Email: rentsbill@gmail.com<br>
                        <i class="bi bi-instagram"></i> : @rentsbill_rentcar
                    </p>
                </div>
            </div>

            <hr class="border-secondary my-4">

            <div class="text-center small text-white-50">
                &copy; {{ date('Y') }} RentsBill . All Rights Reserved.
            </div>
        </div>
    </footer>



    {{-- SCRIPT --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
