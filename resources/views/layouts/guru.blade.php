<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guru - {{ config('app.name') }}</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        .main-sidebar {
            background: linear-gradient(180deg, #6f42c1 0%, #4a2c7a 100%);
        }

        .brand-link {
            background: rgba(255,255,255,0.05);
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .brand-text {
            font-weight: 600;
            color: #fff;
        }

        .nav-sidebar .nav-link {
            color: rgba(255,255,255,0.85);
            border-radius: 8px;
            margin: 4px 8px;
        }

        .nav-sidebar .nav-link:hover {
            background: rgba(255,255,255,0.15);
            color: #fff;
        }

        .nav-sidebar .nav-link.active {
            background: #ffffff;
            color: #6f42c1 !important;
            font-weight: 600;
        }

        .content-wrapper {
            background-color: #f4f6f9;
        }

        .card {
            border-radius: 12px;
        }

        .navbar {
            border-bottom: 1px solid #eee;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- ================= NAVBAR ================= -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">

        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#">
                    <i class="fas fa-bars"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-user-circle"></i> {{ Auth::user()->name }}
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            </li>
        </ul>

    </nav>
    <!-- ================= END NAVBAR ================= -->


    <!-- ================= SIDEBAR ================= -->
    <aside class="main-sidebar elevation-4">

        <a href="#" class="brand-link text-center">
            <span class="brand-text">Panel Guru</span>
        </a>

        <div class="sidebar">

            <div class="user-panel mt-3 pb-3 mb-3 text-center">
                <div class="info">
                    <span class="d-block text-white font-weight-bold">
                        {{ Auth::user()->name }}
                    </span>
                </div>
            </div>

            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">

                    <li class="nav-item">
                        <a href="{{ route('guru.dashboard') }}"
                           class="nav-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-home"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('guru.presensi.index') }}"
                           class="nav-link {{ request()->routeIs('guru.presensi.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-qrcode"></i>
                            <p>Presensi</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('guru.nilai.index') }}"
                           class="nav-link {{ request()->routeIs('guru.nilai.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-edit"></i>
                            <p>Input Nilai</p>
                        </a>
                    </li>

                </ul>
            </nav>

        </div>
    </aside>
    <!-- ================= END SIDEBAR ================= -->


    <!-- ================= CONTENT ================= -->
    <div class="content-wrapper">

        <section class="content pt-4">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>

    </div>
    <!-- ================= END CONTENT ================= -->


</div> <!-- ✅ PENUTUP WRAPPER (HARUS DI SINI) -->


<!-- ================= SCRIPTS ================= -->
<script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

@yield('scripts')

</body>
</html>