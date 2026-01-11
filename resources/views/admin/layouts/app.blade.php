<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Rental Mobil</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        body {
            background: #f8fafc;
            font-family: 'Segoe UI', sans-serif;
        }

        .admin-title {
            font-family: 'Segoe UI', sans-serif;
            font-weight: 600;
            font-size: 2rem;
            letter-spacing: 1.5px;
            color: #ffffff;
            text-align: center;
            margin-bottom: 1.8rem;
            position: relative;
        }

        .admin-title::after {
            content: "";
            width: 150px;
            height: 3px;
            background: #49628c;
            /* light-cyan accent */
            display: block;
            margin: 8px auto 0 auto;
            border-radius: 6px;
        }

        .logo-circle {
            width: 90px;
            /* ukuran lingkaran */
            height: 90px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px auto;
            /* agar posisi center */
            overflow: hidden;
            /* supaya logo rapi */
        }

        .admin-logo {
            width: 60px;
            /* atur ukuran logo */
            height: auto;
            object-fit: contain;
        }

        .sidebar {
            width: 240px;
            height: 100vh;
            background: #1e3038;
            /* dark slate premium */
            position: fixed;
            padding-top: 25px;
            transition: .3s;
            border-right: 1px solid #0f172a;
        }

        .sidebar h4 {
            font-size: 20px;
            color: #fff;
            letter-spacing: .8px;
            font-weight: 600;
        }

        .sidebar small {
            color: #94a3b8;
            /* abu */
            margin-left: 15px;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .7px;
        }

        .sidebar a {
            color: #cbd5e1;
            /* abu terang */
            padding: 12px 20px;
            display: block;
            font-size: 15px;
            text-decoration: none;
            border-radius: 6px;
            margin: 3px 10px;
            transition: .2s ease-in-out;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #49628c;
            /* cyan modern */
            color: #fff !important;
            transform: translateX(3px);
            /* efek maju */
            font-weight: 600;
        }

        .btn:hover {
            background: #64748b !important;
            color: #fff !important;
        }

        .content {
            margin-left: 260px;
            padding: 30px;
        }

        .sidebar-link {
            background: transparent;
            border: none;
            color: #cbd5e1;
            padding: 12px 20px;
            font-size: 15px;
            border-radius: 6px;
            margin: 3px 10px;
            transition: .2s ease-in-out;
            cursor: pointer;
        }

        .sidebar-link:hover {
            background: #49628c;
            color: #fff !important;
            transform: translateX(3px);
            font-weight: 600;
        }

        .sidebar-link:active {
            transform: translateX(3px);
        }
    </style>
</head>

<body>

    <div class="sidebar">
        <div class="logo-circle">
            <img src="{{ asset('storage/logo.png') }}" class="admin-logo" alt="Logo">
        </div>

        <h4 class="admin-title">Rent's Bill</h4>

        <small>Utama</small>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2 me-2"></i> Dashboard
        </a>

        <small class="mt-3 d-block">Operasional</small>
        <a href="{{ route('admin.rental.index') }}" class="{{ request()->routeIs('admin.rental.*') ? 'active' : '' }}">
            <i class="bi bi-receipt-cutoff me-2"></i> Transaksi Rental
        </a>

        <small class="mt-3 d-block">Master Data</small>
        <a href="{{ route('admin.mobil.index') }}" class="{{ request()->routeIs('admin.mobil.*') ? 'active' : '' }}">
            <i class="bi bi-car-front-fill me-2"></i> Data Mobil
        </a>
        <a href="{{ route('admin.penyewa.index') }}"
            class="{{ request()->routeIs('admin.penyewa.*') ? 'active' : '' }}">
            <i class="bi bi-people me-2"></i> Data Penyewa
        </a>
        <a href="{{ route('admin.karyawan.index') }}"
            class="{{ request()->routeIs('admin.karyawan.*') ? 'active' : '' }}">
            <i class="bi bi-person-lines-fill me-2"></i> Data Karyawan
        </a>

        <small class="mt-3 d-block">Sistem</small>
        <form action="{{ route('logout') }}" method="POST" class="mt-1">
            @csrf
            <button type="submit" class="sidebar-link text-start w-100 text-danger">
                <i class="bi bi-box-arrow-right me-2"></i> Logout
            </button>
        </form>
    </div>

    <div class="content">
        @yield('content')
    </div>

</body>

</html>
