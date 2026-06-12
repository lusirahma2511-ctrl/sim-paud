@extends('layouts.adminlte')

@section('title', 'Dashboard Kepala Sekolah')

@section('content')

<style>

.content-wrapper{
    background:#f4f6f9 !important;
}

.content-header h3{
    font-weight:700;
    color:#2c3e50;
}

/* =========================
CARD MENU
========================= */

.menu-card{
    border:none;
    border-radius:18px;
    overflow:hidden;
    transition:.25s;
    box-shadow:0 4px 18px rgba(0,0,0,.06);
}

.menu-card:hover{
    transform:translateY(-4px);
}

.menu-link{
    display:block;
    padding:30px;
    color:white !important;
    text-decoration:none !important;
}

.menu-icon{
    font-size:45px;
    margin-bottom:15px;
}

.menu-title{
    font-size:20px;
    font-weight:700;
}

.menu-desc{
    opacity:.9;
    font-size:14px;
}

/* =========================
GRADIENT
========================= */

.bg-presensi-siswa{
    background:linear-gradient(135deg,#1cc88a,#17a673);
}

.bg-presensi-guru{
    background:linear-gradient(135deg,#36b9cc,#258391);
}

/* =========================
CARD CHART
========================= */

.chart-card{
    border:none;
    border-radius:20px;
    overflow:hidden;
    box-shadow:0 4px 18px rgba(0,0,0,.06);
}

.chart-header{
    padding:18px 24px;
    background:white;
    border-bottom:1px solid #eee;
}

.chart-title{
    font-size:18px;
    font-weight:700;
    color:#2c3e50;
    margin:0;
}

.chart-body{
    padding:25px;
    background:white;
}

.chart-wrapper{
    position:relative;
    height:400px;
}

/* =========================
INFO BOX
========================= */

.info-box-modern{
    background:white;
    border-radius:18px;
    padding:25px;
    box-shadow:0 4px 18px rgba(0,0,0,.06);
    margin-bottom:20px;
}

.info-title{
    font-size:14px;
    color:#888;
    margin-bottom:10px;
}

.info-value{
    font-size:35px;
    font-weight:800;
    color:#4e73df;
}

/* =========================
EMPTY
========================= */

.empty-chart{
    text-align:center;
    padding:70px 20px;
    color:#999;
}

.empty-chart i{
    font-size:70px;
    margin-bottom:15px;
    opacity:.5;
}

/* =========================
RESPONSIVE
========================= */

@media(max-width:768px){

    .menu-title{
        font-size:18px;
    }

    .chart-wrapper{
        height:300px;
    }

}

</style>

<section class="content-header">
    <div class="container-fluid">

        <h3>
            📊 Dashboard Kepala Sekolah
        </h3>

    </div>
</section>

<section class="content">
<div class="container-fluid">

    <!-- MENU -->
    <div class="row mb-4">

        <div class="col-md-4 mb-3">

            <div class="menu-card">

                <a href="{{ route('kepala.presensiSiswa') }}"
                   class="menu-link bg-presensi-siswa">

                    <div class="menu-icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>

                    <div class="menu-title">
                        Laporan Presensi Siswa
                    </div>

                    <div class="menu-desc">
                        Monitoring kehadiran seluruh siswa
                    </div>

                </a>

            </div>

        </div>

        <div class="col-md-4 mb-3">

            <div class="menu-card">

                <a href="{{ route('kepala.presensiGuru') }}"
                   class="menu-link bg-presensi-guru">

                    <div class="menu-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>

                    <div class="menu-title">
                        Laporan Presensi Guru
                    </div>

                    <div class="menu-desc">
                        Monitoring kehadiran guru PAUD
                    </div>

                </a>

            </div>

        </div>

        <div class="col-md-4 mb-3">

            <div class="menu-card">

                <a href="{{ route('kepala.penilaian') }}"
                   class="menu-link bg-info">

                    <div class="menu-icon">
                        <i class="fas fa-star"></i>
                    </div>

                    <div class="menu-title">
                        Laporan Penilaian
                    </div>

                    <div class="menu-desc">
                        Monitoring perkembangan anak didik
                    </div>

                </a>

            </div>

        </div>

    </div>

    <!-- INFO -->
    <div class="row">

        <div class="col-md-3">

            <div class="info-box-modern">

                <div class="info-title">
                    Total Data Penilaian
                </div>

                <div class="info-value">
                    {{ array_sum($data) }}
                </div>

            </div>

        </div>

    </div>

    <!-- CHART -->
    <div class="chart-card">

        <div class="chart-header">

            <h5 class="chart-title">
                <i class="fas fa-chart-bar mr-2 text-primary"></i>
                Grafik Perkembangan Anak
            </h5>

        </div>

        <div class="chart-body">

            <div class="chart-wrapper">

                <canvas id="chart"></canvas>

            </div>

            <div id="emptyChart" class="empty-chart" style="display:none;">

                <i class="fas fa-chart-bar"></i>

                <h5>
                    Data penilaian belum tersedia
                </h5>

            </div>

        </div>

    </div>

</div>
</section>

@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener("DOMContentLoaded", function () {

    let labels = @json($labels ?? []);
    let data = @json($data ?? []);

    const canvas = document.getElementById('chart');
    const emptyChart = document.getElementById('emptyChart');

    // jika data kosong
    if(labels.length === 0 || data.length === 0){

        canvas.style.display = 'none';
        emptyChart.style.display = 'block';

        return;

    }

    new Chart(canvas, {

        type: 'bar',

        data: {

            labels: labels,

            datasets: [{

                label: 'Jumlah Penilaian',

                data: data,

                borderRadius: 10,

                borderWidth: 1

            }]

        },

        options: {

            responsive:true,

            maintainAspectRatio:false,

            plugins: {

                legend: {
                    display:false
                }

            },

            scales: {

                y: {

                    beginAtZero:true,

                    ticks:{
                        precision:0
                    }

                }

            }

        }

    });

});

</script>

@endpush