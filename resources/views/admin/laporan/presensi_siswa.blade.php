@extends('layouts.adminlte')

@section('title', 'Laporan Presensi Siswa')

@section('content')

<style>

/* ================= WRAPPER ================= */
.content-wrapper{
    background: #f4f6f9 !important;
}

.content{
    padding: 20px !important;
    min-height: 100vh;
}

/* ================= HEADER ================= */
.page-header{
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 25px;
}

.page-title{
    font-size: 30px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

/* ================= BUTTON ================= */
.action-group{
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.btn-modern{
    border: none;
    border-radius: 12px;
    padding: 12px 20px;
    font-size: 14px;
    font-weight: 600;
    transition: .2s;
    color: white !important;
    box-shadow: 0 4px 12px rgba(0,0,0,.08);
}

.btn-modern:hover{
    transform: translateY(-2px);
}

.btn-secondary{
    background: linear-gradient(135deg,#6c757d,#495057);
}

.btn-print{
    background: linear-gradient(135deg,#4e73df,#224abe);
}

/* ================= CARD ================= */
.card-modern{
    border: none;
    border-radius: 18px;
    overflow: hidden;
    background: white;
    box-shadow: 0 4px 18px rgba(0,0,0,.07);
    margin-bottom: 25px;
}

.card-header-modern{
    padding: 18px 24px;
    border-bottom: 1px solid #f1f1f1;
}

.card-title-modern{
    font-size: 18px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.card-body{
    padding: 24px;
}

/* ================= FORM ================= */
.filter-label{
    font-weight: 600;
    margin-bottom: 8px;
    color: #444;
}

.form-control{
    height: 48px;
    border-radius: 12px;
    border: 1px solid #ddd;
}

.form-control:focus{
    border-color: #4e73df;
    box-shadow: 0 0 0 .15rem rgba(78,115,223,.15);
}

.btn-filter{
    height: 48px;
    border-radius: 12px;
    border: none;
    font-weight: 600;
    background: linear-gradient(135deg,#36b9cc,#258391);
    color: white;
}

/* ================= TABLE ================= */
.table{
    margin: 0;
}

.table thead{
    background: linear-gradient(135deg,#4e73df,#224abe);
    color: white;
}

.table th{
    border: none !important;
    padding: 16px;
    font-size: 14px;
    font-weight: 700;
}

.table td{
    padding: 15px;
    vertical-align: middle !important;
    font-size: 14px;
}

.table tbody tr:hover{
    background: #f8f9fc;
}

/* ================= BADGE ================= */
.badge-custom{
    padding: 8px 13px;
    border-radius: 30px;
    font-size: 13px;
    font-weight: 700;
}

.badge-hadir{
    background: #1cc88a;
    color: white;
}

.badge-izin{
    background: #36b9cc;
    color: white;
}

.badge-sakit{
    background: #f6c23e;
    color: #212529;
}

.badge-alfa{
    background: #e74a3b;
    color: white;
}

/* ================= EMPTY ================= */
.empty-data{
    padding: 40px !important;
    color: #888;
    font-weight: 500;
}

/* ================= MODAL ================= */
.modal-content{
    border: none;
    border-radius: 20px;
    overflow: hidden;
}

.modal-header{
    background: linear-gradient(135deg,#4e73df,#224abe);
    color: white;
    border: none;
}

.modal-title{
    font-weight: 700;
}

.modal-body{
    padding: 35px;
}

.export-icon{
    font-size: 70px;
    color: #dc3545;
}

/* ================= RESPONSIVE ================= */
@media(max-width:768px){

    .page-header{
        flex-direction: column;
        align-items: flex-start;
    }

    .action-group{
        width: 100%;
    }

    .action-group .btn{
        flex: 1;
        text-align: center;
    }

    .page-title{
        font-size: 24px;
    }
}

</style>

<section class="content">
<div class="container-fluid">

    <!-- HEADER -->
    <div class="page-header">

        <h1 class="page-title">
            <i class="fas fa-file-alt mr-2"></i>
            Laporan Presensi Siswa
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
                    data-target="#aksiLaporanModal">

                <i class="fas fa-file-export mr-1"></i>
                Export Laporan

            </button>

        </div>

    </div>

    <!-- FILTER -->
    <div class="card card-modern">

        <div class="card-header-modern">
            <h5 class="card-title-modern">
                <i class="fas fa-filter mr-2"></i>
                Filter Laporan
            </h5>
        </div>

        <div class="card-body">

            <form method="GET">

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <label class="filter-label">Bulan</label>

                        <select name="bulan" class="form-control">

                            @for($i = 1; $i <= 12; $i++)

                            <option value="{{ $i }}"
                                {{ $bulan == $i ? 'selected' : '' }}>

                                {{ DateTime::createFromFormat('!m', $i)->format('F') }}

                            </option>

                            @endfor

                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="filter-label">Tahun</label>

                        <select name="tahun" class="form-control">

                            @for($y = date('Y'); $y >= 2020; $y--)

                            <option value="{{ $y }}"
                                {{ $tahun == $y ? 'selected' : '' }}>

                                {{ $y }}

                            </option>

                            @endfor

                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label class="filter-label">Kelas</label>

                        <select name="kelas_id" class="form-control">

                            <option value="">Semua Kelas</option>

                            @foreach($kelas as $k)

                            <option value="{{ $k->id }}"
                                {{ $kelasId == $k->id ? 'selected' : '' }}>

                                {{ $k->nama_kelas }}

                            </option>

                            @endforeach

                        </select>
                    </div>

                    <div class="col-md-3 mb-3 d-flex align-items-end">

                        <button class="btn btn-filter w-100">

                            <i class="fas fa-search mr-1"></i>
                            Tampilkan Laporan

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

                <i class="fas fa-calendar-check mr-2"></i>

                Rekap Presensi Bulan
                {{ DateTime::createFromFormat('!m', $bulan)->format('F') }}
                {{ $tahun }}

            </h5>

        </div>

        <div class="table-responsive">

            <table class="table table-hover text-center">

                <thead>
                    <tr>
                        <th width="70">No</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
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

                        <td>{{ $d['kelas'] }}</td>

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

                        <td colspan="7" class="empty-data">

                            <i class="fas fa-folder-open d-block mb-3"
                               style="font-size:40px;color:#ccc;"></i>

                            Tidak ada data laporan presensi

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
<div class="modal fade" id="aksiLaporanModal" tabindex="-1">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title">
                    <i class="fas fa-file-export mr-2"></i>
                    Export Laporan
                </h5>

                <button type="button"
                        class="close text-white"
                        data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>

            <div class="modal-body text-center">

                <i class="fas fa-file-pdf export-icon mb-4"></i>

                <h4 class="font-weight-bold mb-2">
                    Pilih Aksi Laporan
                </h4>

                <p class="text-muted mb-4">
                    Cetak laporan atau unduh PDF laporan presensi
                </p>

                <div class="d-flex justify-content-center flex-wrap">

                    <a href="{{ route('admin.laporan.presensiSiswa.cetak', [
                        'bulan' => request('bulan'),
                        'tahun' => request('tahun'),
                        'kelas_id' => request('kelas_id')
                    ]) }}"
                    target="_blank"
                    class="btn btn-primary mr-2 mb-2"
                    style="border-radius:12px;padding:12px 22px;">

                        <i class="fas fa-print mr-1"></i>
                        Cetak

                    </a>

                    <a href="{{ route('admin.laporan.presensiSiswa.download', [
                        'bulan' => request('bulan'),
                        'tahun' => request('tahun'),
                        'kelas_id' => request('kelas_id')
                    ]) }}"
                    class="btn btn-success mb-2"
                    style="border-radius:12px;padding:12px 22px;">

                        <i class="fas fa-download mr-1"></i>
                        Unduh PDF

                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection