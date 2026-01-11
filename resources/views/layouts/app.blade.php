<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Rental Mobil' }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f1f6ff;
            height: 100vh;
            margin: 0;
        }

        .auth-container {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Bagian Kiri: Gambar */
        .auth-image {
            background-image: url('{{ asset('storage/mobil3.jpg') }}'); /* Pastikan path gambar benar */
            background-size: cover;
            background-position: center;
            flex: 1;
            display: none; /* Sembunyikan di HP */
        }

        /* Bagian Kanan: Form */
        .auth-form-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            padding: 40px;
        }

        .card-auth {
            width: 100%;
            max-width: 400px;
            border: none;
        }

        .title {
            color: #1d4ed8;
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 28px;
            text-align: center;
        }

        .subtitle {
            color: #64748b;
            margin-bottom: 30px;
            font-size: 14px;
            text-align: center;
        }

        .btn-primary-custom {
            background: #1d4ed8;
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
        }

        .btn-primary-custom:hover {
            background: #163fa3;
        }

        .form-control {
            padding: 12px;
            border-radius: 8px;
            background: #f8fafc;
        }

        @media (min-width: 992px) {
            .auth-image {
                display: block; /* Tampilkan di Laptop/Tablet Besar */
            }
        }
    </style>
</head>

<body>

    <div class="auth-container">
        <div class="auth-image">
            <div class="h-100 w-100">
                </div>
        </div>

        <div class="auth-form-section">
            <div class="card-auth">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>