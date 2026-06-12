<aside class="main-sidebar elevation-4 sidebar-paud">

    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}"
       class="brand-link text-center d-flex flex-column align-items-center">

        <img src="{{ asset('images/logo paud.png') }}"
             alt="Logo"
             class="elevation-3 mb-2"
             style="width: 90px; height: 90px; object-fit: cover;">

        <span class="brand-text fw-bold">
            SIM PAUD
        </span>
    </a>

    <style>
        /* === SIDEBAR WARNA LOGO PAUD === */
        .sidebar-paud {
            background: linear-gradient(180deg, rgb(243, 133, 206) 0%, #00BCD4 100%);
        }

        /* Brand text */
        .sidebar-paud .brand-text {
            color: #ffffff;
            font-weight: 700;
            font-size: 18px;
        }

        /* Garis bawah brand */
        .sidebar-paud .brand-link {
            border-bottom: 1px solid rgba(255,255,255,0.2);
            padding: 15px 10px;
        }

        /* Logo */
        .brand-link img {
            background-color: white;
            padding: 10px;
            border-radius: 50%;
        }

        /* Sidebar user */
        .sidebar-paud .user-panel {
            border-bottom: 1px solid rgba(255,255,255,0.2);
            padding-left: 10px;
            padding-right: 10px;
        }

        /* Menu default */
        .sidebar-paud .nav-link {
            color: #ffffff;
            border-radius: 8px;
            margin: 3px 8px;
            transition: 0.3s;
        }

        /* Hover menu */
        .sidebar-paud .nav-link:hover {
            background-color: rgb(245, 236, 156);
            color: #333333;
        }

        /* Hover icon */
        .sidebar-paud .nav-link:hover i {
            color: #333333;
        }

        /* Menu aktif */
        .sidebar-paud .nav-link.active {
            background-color: rgb(72, 57, 155);
            color: #ffffff;
            font-weight: 600;
        }

        /* Icon aktif */
        .sidebar-paud .nav-link.active i {
            color: #ffffff;
        }

        /* Text menu */
        .sidebar-paud .nav-link p {
            margin: 0;
        }
    </style>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">

            <div class="image">
                @if(Auth::user()->foto)
                    <img
                        src="{{ asset('storage/'.Auth::user()->foto) }}"
                        class="img-circle elevation-2"
                        style="width:32px; height:32px; object-fit:cover;">
                @else
                    <div class="img-circle elevation-2 d-flex align-items-center justify-content-center"
                         style="width:32px; height:32px; background:#ddd;">
                        <i class="fas fa-user text-gray-600" style="font-size:16px;"></i>
                    </div>
                @endif
            </div>

            <div class="info">
                <small class="d-block text-white fw-bold" style="font-size:13px">
                    Selamat Datang,
                </small>

                <a href="#"
                   class="d-block text-white fw-semibold text-decoration-none"
                   style="word-wrap: break-word; white-space: normal;">
                    {{ Auth::user()->name ?? 'Guest' }}
                </a>
            </div>

        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">

            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('orangtua.dashboard') }}"
                       class="nav-link {{ request()->routeIs('orangtua.dashboard') ? 'active' : '' }}">

                        <i class="nav-icon fas fa-tachometer-alt"></i>

                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- E-Rapor -->
                <li class="nav-item">
                    <a href="{{ route('orangtua.rapor') }}"
                       class="nav-link {{ request()->routeIs('orangtua.rapor*') ? 'active' : '' }}">

                        <i class="nav-icon fas fa-file-alt"></i>

                        <p>E-Rapor</p>
                    </a>
                </li>

            </ul>

        </nav>
    </div>
</aside>