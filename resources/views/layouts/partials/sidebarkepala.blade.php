<aside class="main-sidebar elevation-4 sidebar-paud">

    <!-- BRAND -->
    <a href="{{ route('kepala.dashboard') }}"
       class="brand-link text-center d-flex flex-column align-items-center">

        <img src="{{ asset('images/logo paud.png') }}"
             alt="Logo"
             class="elevation-3 mb-2"
             style="width:90px; height:90px; object-fit:cover;">

        <span class="brand-text fw-bold">
            SIM PAUD
        </span>

    </a>

<style>

/* =========================
SIDEBAR
========================= */

.sidebar-paud{
    background: linear-gradient(
        180deg,
        rgb(243,133,206) 0%,
        #00BCD4 100%
    );
}

/* =========================
BRAND
========================= */

.sidebar-paud .brand-text{
    color: #ffffff;
    font-weight: 700;
    font-size: 18px;
}

.sidebar-paud .brand-link{
    border-bottom: 1px solid rgba(255,255,255,.2);
    padding: 20px 10px;
}

.brand-link img{
    background: #ffffff;
    padding: 10px;
    border-radius: 50%;
}

/* =========================
USER PANEL
========================= */

.sidebar-paud .user-panel{
    border-bottom: 1px solid rgba(255,255,255,.15);
    padding-left: 10px;
    padding-right: 10px;
}

.sidebar-paud .user-panel .info a{
    color: #ffffff !important;
    word-wrap: break-word;
    white-space: normal;
}

/* =========================
MENU
========================= */

.sidebar-paud .nav-link{
    color: #ffffff !important;
    border-radius: 12px;
    margin: 5px 10px;
    padding: 12px 15px;
    transition: all .2s ease;
    font-weight: 500;
}

/* Hover */

.sidebar-paud .nav-link:hover{
    background: rgba(255,255,255,.18);
    color: #ffffff !important;
    transform: translateX(3px);
}

.sidebar-paud .nav-link:hover i{
    color: #ffffff !important;
}

/* ACTIVE */

.sidebar-paud .nav-link.active{
    background: rgb(72,57,155) !important;
    color: #ffffff !important;
    font-weight: 700;
    box-shadow: 0 4px 10px rgba(0,0,0,.15);
}

/* Icon */

.sidebar-paud .nav-link i{
    margin-right: 8px;
}

.sidebar-paud .nav-link.active i{
    color: #ffffff !important;
}

/* Text menu */

.sidebar-paud .nav-link p{
    margin: 0;
}

/* =========================
SCROLLBAR
========================= */

.sidebar::-webkit-scrollbar{
    width: 5px;
}

.sidebar::-webkit-scrollbar-thumb{
    background: rgba(255,255,255,.3);
    border-radius: 20px;
}

</style>

    <!-- SIDEBAR -->
    <div class="sidebar">

        <!-- USER PANEL -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">

            <div class="image">

                @if(Auth::user()->foto)
                    <img 
                        src="{{ asset('storage/'.Auth::user()->foto) }}"
                        class="img-circle elevation-2"
                        style="width:32px; height:32px; object-fit:cover;"
                    >
                @else
                    <div class="img-circle elevation-2 d-flex align-items-center justify-content-center"
                         style="width:32px; height:32px; background:#ddd;">
                        <i class="fas fa-user text-gray-600" style="font-size:16px;"></i>
                    </div>
                @endif

            </div>

            <div class="info">

                <small class="d-block text-white fw-bold"
                       style="font-size:13px">

                    Selamat Datang,

                </small>

                <a href="#"
                   class="d-block text-white fw-semibold text-decoration-none">

                    {{ Auth::user()->name ?? 'Guest' }}

                </a>

            </div>

        </div>

        <!-- MENU -->
        <nav class="mt-2">

            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu">

                <!-- DASHBOARD -->
                <li class="nav-item">

                    <a href="{{ route('dashboard') }}"
                       class="nav-link {{ request()->routeIs('dashboard') || request()->routeIs('kepala.dashboard') ? 'active' : '' }}">

                        <i class="nav-icon fas fa-home"></i>

                        <p>Dashboard</p>

                    </a>

                </li>

                <!-- PRESENSI SISWA -->
                <li class="nav-item">

                    <a href="{{ route('kepala.presensiSiswa') }}"
                       class="nav-link {{ request()->routeIs('kepala.presensiSiswa') ? 'active' : '' }}">

                        <i class="nav-icon fas fa-user-check"></i>

                        <p>Laporan Presensi Siswa</p>

                    </a>

                </li>

                <!-- PRESENSI GURU -->
                <li class="nav-item">

                    <a href="{{ route('kepala.presensiGuru') }}"
                       class="nav-link {{ request()->routeIs('kepala.presensiGuru') ? 'active' : '' }}">

                        <i class="nav-icon fas fa-chalkboard-teacher"></i>

                        <p>Laporan Presensi Guru</p>

                    </a>

                </li>

                <!-- PENILAIAN -->
                <li class="nav-item">

                    <a href="{{ route('kepala.penilaian') }}"
                       class="nav-link {{ request()->routeIs('kepala.penilaian') ? 'active' : '' }}">

                        <i class="nav-icon fas fa-star"></i>

                        <p>Laporan Penilaian</p>

                    </a>

                </li>

            </ul>

        </nav>

    </div>

</aside>