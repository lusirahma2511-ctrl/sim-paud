@extends('layouts.adminlte')

@section('title', 'Laporan Perkembangan Anak')

@section('content')

<style>
.content-wrapper{
    background:#f4f7fb !important;
}

.dashboard-header{
    background:linear-gradient(135deg,#1cc88a,#17a673);
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

.dashboard-card{
    border:none;
    border-radius:22px;
    overflow:hidden;
    background:white;
    box-shadow:0 4px 18px rgba(0,0,0,.05);
    transition:.2s;
    height:100%;
}

.dashboard-card:hover{
    transform:translateY(-4px);
}

.card-body-custom{
    padding:35px;
}

.icon-circle{
    width:80px;
    height:80px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    margin:auto;
    margin-bottom:20px;
    font-size:32px;
    color:white;
}

.bg-green-gradient{
    background:linear-gradient(135deg,#1cc88a,#17a673);
}

.nama-anak{
    font-size:22px;
    font-weight:700;
    color:#2c3e50;
    margin-bottom:8px;
}

.btn-modern{
    border:none;
    border-radius:30px;
    padding:12px 24px;
    font-weight:600;
    transition:.2s;
    background:linear-gradient(135deg,#4e73df,#6f42c1);
    color:white;
}

.btn-modern:hover{
    transform:translateY(-2px);
    color: white;
}
</style>

<div class="container-fluid">

    <div class="dashboard-header">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="dashboard-title">
                    <i class="fas fa-file-alt mr-2"></i>
                    Laporan Perkembangan Anak
                </div>
                <div class="dashboard-subtitle">
                    Silakan pilih data anak untuk melihat detail rapor
                </div>
            </div>
            <div class="col-md-4 text-md-right mt-3 mt-md-0">
                <i class="fas fa-chart-bar" style="font-size:70px;opacity:.15;"></i>
            </div>
        </div>
    </div>

    <div class="row">
        @foreach($siswas as $siswa)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card dashboard-card">
                <div class="card-body text-center card-body-custom">
                    <div class="icon-circle bg-green-gradient">
                        <i class="fas fa-user-graduate"></i>
                    </div>

                    <div class="nama-anak">
                        {{ $siswa->nama_siswa }}
                    </div>

                    <div class="text-muted mb-3">
                        NISN: {{ $siswa->nisn ?? '-' }}
                    </div>

                    <div class="mb-4">
                        <span class="badge badge-info px-3 py-2" style="border-radius:20px;">
                            Kelas: {{ $siswa->kelas->nama_kelas ?? '-' }}
                        </span>
                    </div>

                    <a href="{{ route('orangtua.rapor.show', $siswa->id) }}" class="btn btn-modern w-100">
                        <i class="fas fa-eye mr-1"></i> Lihat Rapor
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>

@endsection