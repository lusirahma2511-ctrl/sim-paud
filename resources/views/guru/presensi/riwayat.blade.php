@extends('layouts.adminlte')

@section('content')

<style>
.content{
    background:#f4f6f9;
    min-height:100vh;
    padding:20px 0;
}

/* ================= CARD ================= */
.custom-card{
    border:none;
    border-radius:20px;
    overflow:hidden;
    background:#fff;
    box-shadow:0 4px 20px rgba(0,0,0,.06);
}

.custom-header{
    background:linear-gradient(135deg,#7367f0,#9c27b0);
    color:white;
    padding:18px 25px;
    font-size:18px;
    font-weight:700;
}

/* ================= TITLE ================= */
.page-title{
    font-size:28px;
    font-weight:800;
    color:#2c3e50;
}

.page-subtitle{
    color:#777;
    margin-top:3px;
}

/* ================= BUTTON ================= */
.btn-main{
    background:linear-gradient(135deg,#7367f0,#9c27b0);
    border:none;
    color:white;
    border-radius:12px;
    padding:12px 18px;
    font-weight:600;
    transition:.3s;
}

.btn-main:hover{
    transform:translateY(-2px);
    color:white;
    box-shadow:0 8px 18px rgba(115,103,240,.25);
}

/* ================= FORM ================= */
.form-control{
    border-radius:12px;
    height:45px;
    border:1px solid #e4e6ef;
}

.form-control:focus{
    border-color:#7367f0;
    box-shadow:none;
}

/* ================= TABLE ================= */
.table{
    margin-bottom:0;
}

.table thead th{
    background:#f8f9fc;
    color:#444;
    border:none;
    font-weight:700;
    padding:15px;
    white-space:nowrap;
}

.table tbody td{
    vertical-align:middle;
    padding:15px;
    border-top:1px solid #f1f1f1;
}

.table tbody tr:hover{
    background:#fafafa;
}

/* ================= BADGE ================= */
.badge-status{
    padding:8px 14px;
    border-radius:30px;
    font-size:12px;
    font-weight:700;
}

.badge-hadir{
    background:#d1fae5;
    color:#065f46;
}

.badge-sakit{
    background:#fef3c7;
    color:#92400e;
}

.badge-izin{
    background:#dbeafe;
    color:#1e40af;
}

.badge-alpha{
    background:#fee2e2;
    color:#991b1b;
}

/* ================= EMPTY ================= */
.empty-box{
    padding:50px 20px;
    text-align:center;
}

.empty-box i{
    font-size:60px;
    color:#cbd5e1;
    margin-bottom:15px;
}

.empty-box h5{
    color:#555;
    font-weight:700;
}

.empty-box p{
    color:#888;
}

/* ================= STATS ================= */
.stats-card{
    border-radius:18px;
    background:white;
    padding:25px;
    box-shadow:0 4px 20px rgba(0,0,0,.06);
    text-align:center;
}

.stats-number{
    font-size:35px;
    font-weight:800;
    color:#7367f0;
}

.stats-label{
    color:#777;
}

/* ================= MOBILE ================= */
@media(max-width:768px){

    .page-title{
        font-size:22px;
    }

    .table thead{
        display:none;
    }

    .table,
    .table tbody,
    .table tr,
    .table td{
        display:block;
        width:100%;
    }

    .table tr{
        margin-bottom:15px;
        border-radius:15px;
        overflow:hidden;
        background:white;
        box-shadow:0 2px 10px rgba(0,0,0,.05);
    }

    .table td{
        text-align:right;
        position:relative;
        padding-left:50%;
        border:none;
        border-bottom:1px solid #f1f1f1;
    }

    .table td::before{
        content:attr(data-label);
        position:absolute;
        left:15px;
        width:45%;
        text-align:left;
        font-weight:700;
        color:#555;
    }

}
</style>

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">

        <div>

            <div class="page-title">
                <i class="fas fa-history mr-2"></i>
                Riwayat Presensi
            </div>

            <div class="page-subtitle">
                Data riwayat presensi guru dan siswa
            </div>

        </div>

        <a href="{{ route('guru.presensi.index') }}"
           class="btn btn-main mt-3 mt-md-0">

            <i class="fas fa-qrcode mr-1"></i>
            Scan Barcode

        </a>

    </div>

    <!-- FILTER -->
    <div class="custom-card mb-4">

        <div class="custom-header">
            Filter Data
        </div>

        <div class="card-body">

            <form method="GET">

                <div class="row">

                    <div class="col-md-3 mb-3">

                        <label>Tipe Presensi</label>

                        <select name="tipe" class="form-control">

                            <option value="guru"
                                {{ $tipe == 'guru' ? 'selected' : '' }}>
                                Presensi Saya
                            </option>

                            <option value="siswa"
                                {{ $tipe == 'siswa' ? 'selected' : '' }}>
                                Presensi Siswa
                            </option>

                        </select>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label>Bulan</label>

                        <select name="bulan" class="form-control">

                            @for($i = 1; $i <= 12; $i++)

                                <option value="{{ $i }}"
                                    {{ $bulan == $i ? 'selected' : '' }}>

                                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}

                                </option>

                            @endfor

                        </select>

                    </div>

                    <div class="col-md-3 mb-3">

                        <label>Tahun</label>

                        <select name="tahun" class="form-control">

                            @for($i = date('Y'); $i >= date('Y') - 3; $i--)

                                <option value="{{ $i }}"
                                    {{ $tahun == $i ? 'selected' : '' }}>

                                    {{ $i }}

                                </option>

                            @endfor

                        </select>

                    </div>

                    <div class="col-md-3 d-flex align-items-end mb-3">

                        <button type="submit"
                                class="btn btn-main w-100">

                            <i class="fas fa-filter mr-1"></i>
                            Filter

                        </button>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <!-- STATS -->
    <div class="row mb-4">

        <div class="col-md-4 mb-3">

            <div class="stats-card">

                <div class="stats-number">
                    {{ $presensi->count() }}
                </div>

                <div class="stats-label">
                    Total Presensi
                </div>

            </div>

        </div>

        <div class="col-md-4 mb-3">

            <div class="stats-card">

                <div class="stats-number">
                    {{ $presensi->where('status','hadir')->count() }}
                </div>

                <div class="stats-label">
                    Hadir
                </div>

            </div>

        </div>

        <div class="col-md-4 mb-3">

            <div class="stats-card">

                <div class="stats-number">
                    {{ $presensi->where('status','!=','hadir')->count() }}
                </div>

                <div class="stats-label">
                    Tidak Hadir
                </div>

            </div>

        </div>

    </div>

    <!-- TABLE -->
    <div class="custom-card">

        <div class="custom-header">

            Riwayat
            {{ $tipe == 'guru' ? 'Presensi Saya' : 'Presensi Siswa' }}

            -
            {{ \Carbon\Carbon::create()->month((int)$bulan)->translatedFormat('F') }}

        </div>

        <div class="table-responsive">

            <table class="table">

                <thead>

                    <tr>

                        <th>No</th>
                        <th>Tanggal</th>

                        @if($tipe == 'siswa')
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                        @endif

                        <th>Jam Masuk</th>
                        <th>Status</th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($presensi as $p)

                        <tr>

                            <td data-label="No">
                                {{ $loop->iteration }}
                            </td>

                            <td data-label="Tanggal">

                                {{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y') }}

                            </td>

                            @if($tipe == 'siswa')

                                <td data-label="Nama Siswa">

                                    <div class="font-weight-bold">
                                        {{ $p->siswa->nama_siswa ?? '-' }}
                                    </div>

                                </td>

                                <td data-label="Kelas">

                                    {{ $p->kelas->nama_kelas ?? '-' }}

                                </td>

                            @endif

                            <td data-label="Jam Masuk">

                                {{ $p->jam_masuk ?? '-' }}

                            </td>

                            <td data-label="Status">

                                @if($p->status == 'hadir')

                                    <span class="badge-status badge-hadir">
                                        Hadir
                                    </span>

                                @elseif($p->status == 'sakit')

                                    <span class="badge-status badge-sakit">
                                        Sakit
                                    </span>

                                @elseif($p->status == 'izin')

                                    <span class="badge-status badge-izin">
                                        Izin
                                    </span>

                                @else

                                    <span class="badge-status badge-alpha">
                                        Alpha
                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6">

                                <div class="empty-box">

                                    <i class="fas fa-folder-open"></i>

                                    <h5>
                                        Tidak Ada Data
                                    </h5>

                                    <p>
                                        Belum ada data presensi ditemukan
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection