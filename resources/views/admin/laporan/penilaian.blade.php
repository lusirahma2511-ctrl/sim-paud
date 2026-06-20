@extends('layouts.adminlte')

@section('title', 'Laporan Penilaian Perkembangan')

@section('content')

<style>

.content-wrapper{
    background:#f4f6f9 !important;
}

.content{
    padding:25px !important;
}

/* =======================
HEADER
======================= */

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:15px;
    margin-bottom:25px;
}

.page-title{
    font-size:28px;
    font-weight:700;
    color:#2c3e50;
    margin:0;
}

.page-subtitle{
    color:#6c757d;
    margin-top:4px;
}

/* =======================
BUTTON
======================= */

.action-group{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
}

.btn-modern{
    border:none;
    border-radius:12px;
    padding:11px 18px;
    font-weight:600;
    transition:.2s;
}

.btn-modern:hover{
    transform:translateY(-2px);
}

.btn-back{
    background:#6c757d;
    color:white;
}

.btn-export{
    background:#4e73df;
    color:white;
}

/* =======================
CARD
======================= */

.card-modern{
    border:none;
    border-radius:18px;
    overflow:hidden;
    background:white;
    box-shadow:0 4px 18px rgba(0,0,0,.05);
    margin-bottom:25px;
}

.card-header-modern{
    padding:18px 22px;
    border-bottom:1px solid #edf2f7;
    background:white;
}

.card-title-modern{
    margin:0;
    font-size:18px;
    font-weight:700;
    color:#2c3e50;
}

/* =======================
SUMMARY
======================= */

.summary-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
    gap:18px;
    margin-bottom:25px;
}

.summary-card{
    background:white;
    border-radius:18px;
    padding:25px;
    box-shadow:0 4px 18px rgba(0,0,0,.05);
}

.summary-title{
    font-size:14px;
    color:#6c757d;
    margin-bottom:10px;
}

.summary-value{
    font-size:32px;
    font-weight:700;
}

.bsb{ color:#10b981; }
.bsh{ color:#3b82f6; }
.mb{ color:#f59e0b; }
.bb{ color:#ef4444; }

/* =======================
TABLE
======================= */

.table{
    margin:0;
}

.table thead{
    background:#4e73df;
    color:white;
}

.table th{
    border:none !important;
    padding:14px;
    font-size:14px;
    font-weight:600;
    white-space:nowrap;
}

.table td{
    padding:14px;
    vertical-align:middle !important;
    border-color:#eef2f7;
}

.table tbody tr:hover{
    background:#f8fbff;
}

.nama-anak{
    text-align:left;
    font-weight:600;
    color:#2c3e50;
}

/* =======================
BADGE
======================= */

.badge-custom{
    padding:8px 14px;
    border-radius:30px;
    font-size:12px;
    font-weight:700;
    display:inline-block;
    min-width:55px;
}

.badge-bsb{
    background:#d1fae5;
    color:#047857;
}

.badge-bsh{
    background:#dbeafe;
    color:#1d4ed8;
}

.badge-mb{
    background:#fef3c7;
    color:#92400e;
}

.badge-bb{
    background:#fee2e2;
    color:#b91c1c;
}

/* =======================
CHART
======================= */

.chart-wrapper{
    padding:20px;
    height:350px;
}

/* =======================
EMPTY
======================= */

.empty-data{
    text-align:center;
    padding:60px 20px;
    color:#999;
}

.empty-data i{
    font-size:60px;
    margin-bottom:15px;
    opacity:.4;
}

/* =======================
RESPONSIVE
======================= */

@media(max-width:768px){

    .page-header{
        flex-direction:column;
        align-items:flex-start;
    }

    .page-title{
        font-size:22px;
    }

    .table th,
    .table td{
        font-size:12px;
        padding:10px;
    }

}

</style>

<section class="content">
<div class="container-fluid">

    <!-- HEADER -->
    <div class="page-header">

        <div>

            <h1 class="page-title">
                <i class="fas fa-chart-line text-primary mr-2"></i>
                Laporan Penilaian Perkembangan Anak
            </h1>

            <div class="page-subtitle">
                Monitoring perkembangan seluruh siswa PAUD
            </div>

        </div>

        <div class="action-group">

            <a href="{{ route('admin.laporan.index') }}"
               class="btn btn-modern btn-back">

                <i class="fas fa-arrow-left mr-1"></i>
                Kembali

            </a>

            <button type="button"
                    class="btn btn-modern btn-export"
                    data-toggle="modal"
                    data-target="#modalExport">

                <i class="fas fa-file-export mr-1"></i>
                Export

            </button>

        </div>

    </div>

    <!-- FILTER -->
    <div class="card-modern mb-4">
        <div class="card-header-modern">
            <h5 class="card-title-modern">
                <i class="fas fa-filter text-primary mr-2"></i>
                Filter Data
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.laporan.penilaian') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label font-weight-bold">Tahun Ajaran</label>
                    <select name="tahun_ajaran" class="form-control" onchange="this.form.submit()">
                        @foreach($tahunAjaranOptions as $ta)
                            <option value="{{ $ta }}" {{ $tahunAjaran == $ta ? 'selected' : '' }}>
                                {{ $ta }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label font-weight-bold">Semester</label>
                    <select name="semester" class="form-control" onchange="this.form.submit()">
                        <option value="">Semua Semester</option>
                        <option value="1" {{ $semester == '1' ? 'selected' : '' }}>1</option>
                        <option value="2" {{ $semester == '2' ? 'selected' : '' }}>2</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label font-weight-bold">Kelas</label>
                    <select name="kelas_id" class="form-control" onchange="this.form.submit()">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- SUMMARY -->
    <div class="summary-grid">

        <div class="summary-card">
            <div class="summary-title">Jumlah BSB</div>
            <div class="summary-value bsb">
                {{ $jumlahBSB ?? 0 }}
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-title">Jumlah BSH</div>
            <div class="summary-value bsh">
                {{ $jumlahBSH ?? 0 }}
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-title">Jumlah MB</div>
            <div class="summary-value mb">
                {{ $jumlahMB ?? 0 }}
            </div>
        </div>

        <div class="summary-card">
            <div class="summary-title">Jumlah BB</div>
            <div class="summary-value bb">
                {{ $jumlahBB ?? 0 }}
            </div>
        </div>

    </div>

    <!-- CHART -->
    <div class="card-modern">

        <div class="card-header-modern">

            <h5 class="card-title-modern">
                <i class="fas fa-chart-bar text-primary mr-2"></i>
                Grafik Statistik Penilaian
            </h5>

        </div>

        <div class="chart-wrapper">
            <canvas id="chartPenilaian"></canvas>
        </div>

    </div>

    <!-- TABLE -->
    <div class="card-modern">

        <div class="card-header-modern">

            <h5 class="card-title-modern">
                <i class="fas fa-table text-primary mr-2"></i>
                Data Penilaian Siswa
            </h5>

        </div>

        <div class="table-responsive">

            <table class="table table-hover text-center">

                <thead>

                    <tr>

                        <th>No</th>
                        <th>Nama Anak</th>

                        @foreach($kriteriaList as $kriteria)

                            <th>{{ $kriteria }}</th>

                        @endforeach

                    </tr>

                </thead>

                <tbody>

                @forelse($data as $d)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td class="nama-anak">
                            {{ $d['nama'] }}
                        </td>

                        @foreach($kriteriaList as $kriteria)

                            @php

                                $nilai = $d[$kriteria] ?? '-';

                                $badge = 'badge-bb';

                                if($nilai == 'BSB'){
                                    $badge = 'badge-bsb';
                                }elseif($nilai == 'BSH'){
                                    $badge = 'badge-bsh';
                                }elseif($nilai == 'MB'){
                                    $badge = 'badge-mb';
                                }

                            @endphp

                            <td>

                                <span class="badge-custom {{ $badge }}">
                                    {{ $nilai }}
                                </span>

                            </td>

                        @endforeach

                    </tr>

                @empty

                    <tr>

                        <td colspan="20">

                            <div class="empty-data">

                                <i class="fas fa-folder-open"></i>

                                <h5>
                                    Tidak ada data penilaian
                                </h5>

                            </div>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
</section>

<!-- MODAL EXPORT -->
<div class="modal fade" id="modalExport">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0"
             style="border-radius:18px;overflow:hidden;">

            <div class="modal-header bg-primary text-white border-0">

                <h5 class="modal-title">
                    Export Laporan
                </h5>

                <button type="button"
                        class="close text-white"
                        data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>

            <div class="modal-body text-center p-4">

                <i class="fas fa-file-pdf text-danger mb-3"
                   style="font-size:70px;"></i>

                <h4 class="font-weight-bold">
                    Export Laporan Penilaian
                </h4>

                <p class="text-muted mb-4">
                    Cetak atau download laporan PDF
                </p>

                <a href="{{ route('admin.laporan.penilaian.cetak', request()->query()) }}"
                   target="_blank"
                   class="btn btn-primary btn-lg mr-2">

                    <i class="fas fa-print mr-1"></i>
                    Cetak

                </a>

                <a href="{{ route('admin.laporan.penilaian.download', request()->query()) }}"
                   class="btn btn-success btn-lg">

                    <i class="fas fa-download mr-1"></i>
                    Download

                </a>

            </div>

        </div>

    </div>

</div>

<!-- CHART -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

const ctx = document.getElementById('chartPenilaian');

new Chart(ctx, {

    type: 'bar',

    data: {

        labels: ['BB', 'MB', 'BSH', 'BSB'],

        datasets: [{

            label: 'Jumlah Penilaian',

            data: [

                {{ $jumlahBB ?? 0 }},
                {{ $jumlahMB ?? 0 }},
                {{ $jumlahBSH ?? 0 }},
                {{ $jumlahBSB ?? 0 }}

            ],

            borderRadius: 12,
            borderWidth: 1

        }]

    },

    options: {

        responsive:true,

        maintainAspectRatio:false,

        plugins: {

            legend:{
                display:false
            }

        },

        scales:{

            y:{
                beginAtZero:true,
                ticks:{
                    precision:0
                }
            }

        }

    }

});

</script>

@endsection
