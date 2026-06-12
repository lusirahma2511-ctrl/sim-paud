@extends('layouts.adminlte')

@section('title', 'Dashboard Orang Tua')

@section('content')

<style>

.content-wrapper{
    background:#f4f7fb !important;
    padding-bottom: 30px !important;
}

/* =========================
HEADER
========================= */

.dashboard-header{
    background:linear-gradient(135deg,#4e73df,#6f42c1);
    border-radius:22px;
    padding:30px;
    color:white;
    margin-bottom:25px;
    box-shadow:0 4px 20px rgba(0,0,0,.08);
}

.dashboard-title{
    font-size:30px;
    font-weight:700;
    margin-bottom:5px;
}

.dashboard-subtitle{
    opacity:.9;
    font-size:15px;
}

/* =========================
CARD
========================= */

.dashboard-card{
    border:none;
    border-radius:22px;
    overflow:hidden;
    background:white;
    box-shadow:0 4px 18px rgba(0,0,0,.05);
    transition:.2s;
}

.dashboard-card:hover{
    transform:translateY(-4px);
}

.card-body-custom{
    padding:30px;
}

/* =========================
ICON
========================= */

.icon-circle{
    width:80px;
    height:80px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:auto;
    margin-bottom: 15px;
    font-size:32px;
    color:white;
}

.bg-blue{
    background:linear-gradient(135deg,#36b9cc,#1d8cf8);
}

.bg-green{
    background:linear-gradient(135deg,#1cc88a,#17a673);
}

/* =========================
TEXT
========================= */

.nama-anak{
    font-size:20px;
    font-weight:700;
    color:#2c3e50;
    margin-bottom:8px;
}

.text-muted-custom{
    color:#7b8a9a;
    font-size:14px;
}

.badge-modern{
    background:#eef4ff;
    color:#224abe;
    border-radius:30px;
    padding:8px 16px;
    font-size:12px;
    font-weight:600;
}

/* =========================
BUTTON
========================= */

.btn-modern{
    border:none;
    border-radius:30px;
    padding:12px 24px;
    font-weight:600;
    transition:.2s;
}

.btn-modern:hover{
    transform:translateY(-2px);
}

.btn-rapor{
    background:linear-gradient(135deg,#1cc88a,#17a673);
    color:white;
}

/* =========================
INFO BOX
========================= */

.info-box-modern{
    background:#f8fbff;
    border-radius:16px;
    padding:15px;
    margin-top:15px;
}

.info-title{
    font-size:12px;
    color:#7b8a9a;
    margin-bottom:4px;
}

.info-value{
    font-size:14px;
    font-weight:600;
    color:#2c3e50;
}

/* =========================
RESPONSIVE
========================= */

@media(max-width:768px){

    .dashboard-title{
        font-size:24px;
    }

    .card-body-custom{
        padding:25px;
    }

}

</style>

<div class="container-fluid">

    <!-- HEADER -->
    <div class="dashboard-header">

        <div class="row align-items-center">

            <div class="col-md-8">

                <div class="dashboard-title">
                    <i class="fas fa-home mr-2"></i>
                    Dashboard Orang Tua
                </div>

                <div class="dashboard-subtitle">
                    Selamat datang orang tua,
                    <strong>{{ $siswas->pluck('nama_siswa')->implode(', ') }}</strong>
                </div>

            </div>

            <div class="col-md-4 text-md-right mt-3 mt-md-0">

                <i class="fas fa-school"
                   style="font-size:70px;opacity:.15;"></i>

            </div>

        </div>

    </div>

    <!-- CONTENT -->
    <div class="row">

        <!-- DATA ANAK - Show each child in its own column for better layout -->
        @foreach($siswas as $siswa)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body text-center card-body-custom">
                    <div class="icon-circle bg-blue">
                        <i class="fas fa-child"></i>
                    </div>

                    <div class="nama-anak">
                        {{ $siswa->nama_siswa }}
                    </div>

                    <div class="text-muted-custom mb-3">
                        Data Anak Terdaftar
                    </div>

                    <span class="badge-modern d-inline-block mb-2">
                        NISN : {{ $siswa->nisn ?? '-' }}
                    </span>

                    <div class="info-box-modern text-left">
                        <div class="row">
                            <div class="col-6">
                                <div class="info-title">
                                    Kelas
                                </div>
                                <div class="info-value">
                                    {{ $siswa->kelas->nama_kelas ?? '-' }}
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="info-title">
                                    Status
                                </div>
                                <div class="info-value {{ ($siswa->status ?? 'Aktif') == 'Aktif' ? 'text-success' : 'text-danger' }}">
                                    {{ $siswa->status ?? 'Aktif' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <!-- RAPOR - Full width or take remaining space -->
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card dashboard-card h-100">
                <div class="card-body text-center card-body-custom">
                    <div class="icon-circle bg-green">
                        <i class="fas fa-file-alt"></i>
                    </div>

                    <div class="nama-anak">
                        Laporan Perkembangan
                    </div>

                    <div class="text-muted-custom mb-4">
                        Lihat hasil perkembangan dan penilaian anak
                    </div>

                    <a href="{{ route('orangtua.rapor') }}"
                       class="btn btn-modern btn-rapor">
                        <i class="fas fa-eye mr-1"></i>
                        Lihat Rapor Anak
                    </a>

                    <div class="info-box-modern text-left">
                        <div class="info-title">
                            Informasi
                        </div>
                        <div class="info-value">
                            Orang tua dapat melihat hasil perkembangan anak secara berkala dari guru kelas.
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection