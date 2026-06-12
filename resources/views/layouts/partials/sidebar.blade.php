<aside class="main-sidebar elevation-4 sidebar-paud">
@php
    $user = Auth::user();
@endphp 
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
/* === SIDEBAR STYLE === */
.sidebar-paud {
    background: linear-gradient(180deg, rgb(243,133,206) 0%, #00BCD4 100%);
}

/* Brand */
.sidebar-paud .brand-text {
    color: #fff;
    font-weight: 700;
    font-size: 18px;
}

.sidebar-paud .brand-link {
    border-bottom: 1px solid rgba(255,255,255,0.2);
}

/* Menu */
.sidebar-paud .nav-link {
    color: #fff;
    border-radius: 8px;
    margin: 3px 8px;
}

.sidebar-paud .nav-link:hover {
    background-color: rgb(245,236,156);
    color: #fff;
}

.sidebar-paud .nav-link.active {
    background-color: rgb(72,57,155);
    color: #fff;
    font-weight: 600;
}

.sidebar-paud .nav-link.active i {
    color: #fff;
}

/* Logo */
.brand-link img {
    background: #fff;
    padding: 10px;
    border-radius: 50%;
}

/* ================================= */
/* 🔥 FIX SCROLL SIDEBAR FINAL */
/* ================================= */

.main-sidebar {
    position: fixed !important;
    top: 0;
    left: 0;
    height: 100vh !important;
    overflow: hidden !important;
}

/* ini inti fix nya */
.main-sidebar .sidebar {
    height: calc(100vh - 180px) !important; /* auto sesuai logo + user */
    overflow-y: auto !important;
    overflow-x: hidden !important;
    padding-bottom: 80px;
}

/* scroll bar */
.main-sidebar .sidebar::-webkit-scrollbar {
    width: 6px;
}

.main-sidebar .sidebar::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.6);
    border-radius: 10px;
}

/* PENTING: jangan biarin parent nge-block scroll */
.content-wrapper {
    overflow: auto !important;
}

/* HAPUS konflik adminlte */
.wrapper, body {
    overflow: auto !important;
}
</style>

  <!-- Sidebar -->
  <div class="sidebar">

    <!-- User Panel -->
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
      <small class="d-block text-white fw-bold" style="font-size:13px">
            Selamat Datang,
        </small>
        <a href="#"class="d-block text-white fw-semibold text-decoration-none" style="word-wrap: break-word; white-space: normal;">{{ Auth::user()->name ?? 'Guest' }}</a>
      </div>
    </div>

    <nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
    <!-- Dashboard -->
     <li class="nav-item">
      <a href="{{ route('dashboard') }}"
     class="nav-link {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') || request()->routeIs('admin.hari_libur.*') ? 'active' : '' }}">
    <i class="nav-icon fas fa-tachometer-alt"></i>
    <p>Dashboard</p>
  </a>
</li>
        <!-- Users Menu -->
<li class="nav-item">
  <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') && !request()->routeIs('admin.hari_libur.*') ? 'active' : '' }}">
    <i class="nav-icon fas fa-users"></i>
    <p>
      Data Pengguna
    </p>
  </a>
</li>
        <!-- Data Siswa -->
        <li class="nav-item">
          <a href="{{ route('admin.siswa.index') }}" class="nav-link {{ request()->routeIs('admin.siswa.*') && !request()->routeIs('admin.hari_libur.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-user-graduate"></i>
            <p>Data Siswa</p>
          </a>
        </li>

        <!-- Data Guru -->
        <li class="nav-item">
          <a href="{{ route('admin.guru.index') }}" class="nav-link {{ request()->routeIs('admin.guru.*') && !request()->routeIs('admin.hari_libur.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-chalkboard-teacher"></i>
            <p>Data Guru</p>
          </a>
        </li>

        <!-- Data Kelas -->
        <li class="nav-item">
          <a href="{{ route('admin.kelas.index') }}" class="nav-link {{ request()->routeIs('admin.kelas.*') && !request()->routeIs('admin.hari_libur.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-school"></i>
            <p>Data Kelas</p>
          </a>
        </li>

        <li class="nav-item">
  <a href="{{ route('admin.presensi.index') }}" 
     class="nav-link {{ request()->routeIs('admin.presensi.*') && !request()->routeIs('admin.hari_libur.*') ? 'active' : '' }}">
    <i class="nav-icon fas fa-clipboard-check"></i>
    <p>Presensi</p>
  </a>
</li>
        
        <!-- Penilaian -->
        <li class="nav-item">
          <a href="{{ route('admin.penilaian.index') }}" 
             class="nav-link {{ (request()->routeIs('admin.penilaian.*') || request()->routeIs('admin.skala.*')) && !request()->routeIs('admin.hari_libur.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-star"></i>
            <p>Penilaian</p>
          </a>
        </li>
        
        <!-- E-Rapor -->
        <li class="nav-item">
          <a href="{{ route('admin.erapor.index') }}" 
             class="nav-link {{ request()->routeIs('admin.erapor.*') && !request()->routeIs('admin.hari_libur.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-file-alt"></i>
            <p>E-Rapor</p>
          </a>
        </li>

        <!-- Laporan -->
        <li class="nav-item">
          <a href="{{ route('admin.laporan.index') }}" class="nav-link {{ request()->routeIs('admin.laporan.*') && !request()->routeIs('admin.hari_libur.*') ? 'active' : '' }}">
            <i class="nav-icon fas fa-copy"></i>
            <p>Laporan</p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>