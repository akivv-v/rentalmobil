<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Rental Mobil</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <style>
        :root {
            --sidebar-bg: #ffffff;
            --content-bg: #f8fafc;
            --primary-dark: #1e293b;
            --accent-color: #3b82f6;
            --text-muted: #64748b;
        }

        body {
            background: var(--content-bg);
            color: var(--primary-dark);
            overflow-x: hidden;
        }

        /* SIDEBAR STYLING */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            left: 0;
            top: 0;
            border-right: 1px solid #e2e8f0;
            padding: 30px 20px;
            z-index: 1000;
        }

        .brand-section {
            padding: 0 15px 30px 15px;
            text-align: center;
        }

        .logo-wrapper {
            width: 50px;
            height: 50px;
            background: var(--primary-dark);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px auto;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .admin-logo {
            width: 30px;
            height: auto;
            filter: brightness(0) invert(1);
        }

        .admin-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--primary-dark);
            margin: 0;
            letter-spacing: -0.5px;
        }

        /* NAVIGATION */
        .nav-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 1px;
            margin: 25px 0 10px 15px;
            display: block;
        }

        .nav-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-item {
            margin-bottom: 5px;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .nav-link-custom i {
            font-size: 1.1rem;
            margin-right: 12px;
        }

        .nav-link-custom:hover {
            background: #f1f5f9;
            color: var(--primary-dark);
        }

        .nav-link-custom.active {
            background: var(--primary-dark);
            color: #ffffff !important;
            box-shadow: 0 10px 15px -3px rgba(30, 41, 59, 0.2);
        }

        /* CONTENT AREA */
        .content {
            margin-left: 260px;
            padding: 40px;
            min-height: 100vh;
        }

        /* LOGOUT BUTTON */
        .btn-logout {
            margin-top: 20px;
            width: 100%;
            border: none;
            background: #fff1f2;
            color: #e11d48;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }

        .btn-logout:hover {
            background: #ffe4e6;
            transform: translateY(-2px);
        }

        /* SCROLLBAR CUSTOM */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .badge-notification {
            font-size: 0.65rem;
            padding: 2px 6px;
            border-radius: 50%;
            background-color: #ef4444;
            /* Merah terang */
            color: white;
            margin-left: auto;
            /* Agar nempel di kanan */
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
        }
    </style>
</head>

<body>

    <aside class="sidebar">
        <div class="brand-section">
            <div class="logo-wrapper">
                <img src="{{ asset('storage/logo.png') }}" class="admin-logo" alt="Logo">
            </div>
            <h4 class="admin-title">Rent's Bill</h4>
        </div>

        <nav>
            <span class="nav-label">Utama</span>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                        class="nav-link-custom {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i> Dashboard
                    </a>
                </li>
            </ul>

            <span class="nav-label">Operasional</span>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="{{ route('admin.rental.index') }}"
                        class="nav-link-custom {{ request()->routeIs('admin.rental.*') ? 'active' : '' }}">
                        <i class="bi bi-wallet2"></i> Transaksi Rental
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.servis.index') }}"
                        class="nav-link-custom {{ request()->routeIs('admin.servis.*') ? 'active' : '' }}">
                        <i class="bi bi-tools"></i> Servis Mobil
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.laporan.index') }}"
                        class="nav-link-custom {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-line-fill"></i> Laporan Bulanan
                    </a>
                </li>
            </ul>

            <span class="nav-label">Master Data</span>
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="{{ route('admin.mobil.index') }}"
                        class="nav-link-custom {{ request()->routeIs('admin.mobil.*') ? 'active' : '' }}">
                        <i class="bi bi-car-front"></i> Data Mobil
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.penyewa.index') }}"
                        class="nav-link-custom {{ request()->routeIs('admin.penyewa.*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i> Data Penyewa
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.karyawan.index') }}"
                        class="nav-link-custom {{ request()->routeIs('admin.karyawan.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge"></i> Data Karyawan
                    </a>
                </li>
            </ul>

            <span class="nav-label">Sistem</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="bi bi-box-arrow-right me-2"></i> Keluar
                </button>
            </form>
        </nav>
    </aside>

    <main class="content">
        @yield('content')
    </main>

</body>

</html>
