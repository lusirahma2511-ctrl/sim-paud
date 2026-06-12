{{-- resources/views/admin/laporan/presensi_guru.blade.php --}}

@extends('layouts.adminlte')

@section('title', 'Laporan Presensi Guru')

@section('content')

<style>

.content-wrapper{
    background: #f4f6f9 !important;
}

.content{
    padding: 20px !important;
    min-height: 100vh;
}

.page-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:15px;
    margin-bottom:25px;
}

.page-title{
    font-size:30px;
    font-weight:700;
    color:#2c3e50;
}

.action-group{
    display:flex;
    gap:12px;
    flex-wrap:wrap;
}

.btn-modern{
    border:none;
    border-radius:12px;
    padding:12px 20px;
    color:white !important;
    font-weight:600;
    transition:.2s;
}

.btn-modern:hover{
    transform:translateY(-2px);
}

.btn-secondary{
    background:linear-gradient(135deg,#6c757d,#495057);
}

.btn-print{
    background:linear-gradient(135deg,#4e73df,#224abe);
}

.card-modern{
    border:none;
    border-radius:18px;
    overflow:hidden;
    background:white;
    box-shadow:0 4px 18px rgba(0,0,0,.07);
    margin-bottom:25px;
}

.card-header-modern{
    padding:18px 24px;
    border-bottom:1px solid #f1f1f1;
}

.card-title-modern{
    font-size:18px;
    font-weight:700;
    margin:0;
}

.card-body{
    padding:24px;
}

.filter-label{
    font-weight:600;
    margin-bottom:8px;
}

.form-control{
    height:48px;
    border-radius:12px;
}

.btn-filter{
    height:48px;
    border:none;
    border-radius:12px;
    background:linear-gradient(135deg,#36b9cc,#258391);
    font-weight:600;
}

.table thead{
    background:linear-gradient(135deg,#4e73df,#224abe);
    color:white;
}

.table th{
    border:none !important;
    padding:16px;
}

.table td{
    padding:15px;
    vertical-align:middle !important;
}

.badge-custom{
    padding:8px 13px;
    border-radius:30px;
    font-size:13px;
    font-weight:700;
}

.badge-hadir{
    background:#1cc88a;
    color:white;
}

.badge-izin{
    background:#36b9cc;
    color:white;
}

.badge-sakit{
    background:#f6c23e;
    color:#212529;
}

.badge-alfa{
    background:#e74a3b;
    color:white;
}

</style>

<section class="content">
<div class="container-fluid">

    <!-- HEADER -->
    <div class="page-header">

        <h1 class="page-title">
            <i class="fas fa-user-check mr-2"></i>
            Laporan Presensi Guru
        </h1>

        <div class="action-group">

            <a href="{{ route('admin.laporan.index') }}"
               class="btn btn-modern btn-secondary">

                <i class="fas fa-arrow-left mr-1"></i>
                Kembali

            </a>

            <button type="button"
                    class="btn btn-modern btn-print"
                    data-toggle="modal"
                    data-target="#modalExportGuru">

                <i class="fas fa-file-export mr-1"></i>
                Export

            </button>

        </div>

    </div>

    <!-- FILTER -->
    <div class="card card-modern">

        <div class="card-header-modern">
            <h5 class="card-title-modern">
                Filter Laporan
            </h5>
        </div>

        <div class="card-body">

            <form method="GET">

                <div class="row">

                    <div class="col-md-5 mb-3">

                        <label class="filter-label">
                            Bulan
                        </label>

                        <select name="bulan" class="form-control">

                            @for($i = 1; $i <= 12; $i++)

                            <option value="{{ $i }}"
                                {{ $bulan == $i ? 'selected' : '' }}>

                                {{ DateTime::createFromFormat('!m', $i)->format('F') }}

                            </option>

                            @endfor

                        </select>

                    </div>

                    <div class="col-md-5 mb-3">

                        <label class="filter-label">
                            Tahun
                        </label>

                        <select name="tahun" class="form-control">

                            @for($y = date('Y'); $y >= 2020; $y--)

                            <option value="{{ $y }}"
                                {{ $tahun == $y ? 'selected' : '' }}>

                                {{ $y }}

                            </option>

                            @endfor

                        </select>

                    </div>

                    <div class="col-md-2 mb-3 d-flex align-items-end">

                        <button class="btn btn-filter btn-info w-100">

                            <i class="fas fa-search mr-1"></i>
                            Filter

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <!-- TABLE -->
    <div class="card card-modern">

        <div class="card-header-modern">

            <h5 class="card-title-modern">

                Rekap Presensi Guru
                {{ DateTime::createFromFormat('!m', $bulan)->format('F') }}
                {{ $tahun }}

            </h5>

        </div>

        <div class="table-responsive">

            <table class="table table-hover text-center">

                <thead>

                <tr>

                    <th>No</th>
                    <th>Nama Guru</th>
                    <th>Hadir</th>
                    <th>Izin</th>
                    <th>Sakit</th>
                    <th>Alfa</th>

                </tr>

                </thead>

                <tbody>

                @forelse($data as $d)

                <tr>

                    <td>{{ $loop->iteration }}</td>

                    <td class="text-left font-weight-bold">
                        {{ $d['nama'] }}
                    </td>

                    <td>
                        <span class="badge-custom badge-hadir">
                            {{ $d['hadir'] }}
                        </span>
                    </td>

                    <td>
                        <span class="badge-custom badge-izin">
                            {{ $d['izin'] }}
                        </span>
                    </td>

                    <td>
                        <span class="badge-custom badge-sakit">
                            {{ $d['sakit'] }}
                        </span>
                    </td>

                    <td>
                        <span class="badge-custom badge-alfa">
                            {{ $d['alfa'] }}
                        </span>
                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="6" class="text-center p-4">
                        Tidak ada data presensi guru
                    </td>

                </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>
</section>

<!-- MODAL -->
<div class="modal fade" id="modalExportGuru" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content border-0"
             style="border-radius:20px; overflow:hidden;">

            <div class="modal-header text-white"
                 style="background:linear-gradient(135deg,#4e73df,#224abe);">

                <h5 class="modal-title">
                    Export Laporan Guru
                </h5>

                <button type="button"
                        class="close text-white"
                        data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>

            <div class="modal-body text-center p-4">

                <a href="{{ route('admin.laporan.presensiGuru.cetak', [
                    'bulan' => request('bulan'),
                    'tahun' => request('tahun')
                ]) }}"
                target="_blank"
                class="btn btn-primary btn-lg mr-2">

                    <i class="fas fa-print mr-1"></i>
                    Cetak

                </a>

                <a href="{{ route('admin.laporan.presensiGuru.download', [
                    'bulan' => request('bulan'),
                    'tahun' => request('tahun')
                ]) }}"
                class="btn btn-success btn-lg">

                    <i class="fas fa-download mr-1"></i>
                    Download PDF

                </a>

            </div>

        </div>

    </div>

</div>

@endsection