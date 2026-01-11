<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RentsBill - Rental Mobil Terpercaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #e9f5ff;
        }

        .navbar {
            background: white;
            padding: 15px 0;
        }

        .navbar-brand {
            font-weight: 700;
            color: #0e1c36;
            font-size: 24px;
        }

        .btn-login {
            border: 1px solid #0056ff;
            color: #0056ff;
            border-radius: 20px;
            padding: 5px 20px;
            font-weight: 600;
            text-decoration: none;
        }

        .btn-register {
            background: #0056ff;
            color: white;
            border-radius: 20px;
            padding: 6px 20px;
            font-weight: 600;
            text-decoration: none;
            margin-left: 10px;
        }

        .footer {
            background: #1b1b18;
            color: white;
            padding: 60px 0 30px;
        }

        .footer-logo {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 20px;
            display: block;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/">
                <i class="ri-car-fill me-2"></i> RentsBill
            </a>

            <div class="ms-auto d-flex align-items-center">
                <a href="{{ route('login') }}" class="btn-login">Masuk</a>
                <a href="{{ route('register') }}" class="btn-register shadow-sm">Daftar</a>
            </div>
        </div>
    </nav>

    <div style="margin-top: 80px;">
        @yield('content')
    </div>

    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <span class="footer-logo"><i class="ri-car-fill"></i> RentsBill</span>
                    <p class="text-white-50 w-75">Promo hebat dengan harga menarik, dari perusahaan rental mobil
                        terbesar. Temukan mobil terbaik untuk perjalananmu!</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 class="fw-bold mb-3 text-white">Layanan</h6>
                    <ul class="list-unstyled text-white-50">
                        <li class="mb-2">Admin ramah</li>
                        <li class="mb-2">Harga murah</li>
                        <li class="mb-2">Mobil berkualitas</li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h6 class="fw-bold mb-3 text-white">Hubungi Kami</h6>
                    <ul class="list-unstyled text-white-50">
                        <li class="mb-2"><i class="ri-whatsapp-line me-2"></i> 0899-2749-762</li>
                        <li class="mb-2"><i class="ri-mail-line me-2"></i> rentsbill@gmail.com</li>
                        <li class="mb-2"><i class="ri-instagram-line me-2"></i> @rentsbill_rentcar</li>
                    </ul>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <p class="text-center text-white-50 mb-0 small">&copy; 2026 RentsBill. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
