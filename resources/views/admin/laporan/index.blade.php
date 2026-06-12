@extends('layouts.adminlte')

@section('content')

<section class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">📊 Pusat Laporan</h1>
    </div>
</section>

<section class="content">
<div class="container">

    <!-- FILTER -->
    <div class="card card-outline card-primary shadow-sm mb-4">
        <div class="card-body">
            <form class="row">
                <div class="col-md-4">
                    <label>Bulan</label>
                    <select name="bulan" class="form-control">
                        <option value="">-- Pilih --</option>
                        @foreach(range(1,12) as $b)
                            <option value="{{ $b }}">
                                {{ date('F', mktime(0,0,0,$b,1)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Tahun</label>
                    <select name="tahun" class="form-control">
                        <option>{{ date('Y') }}</option>
                        <option>{{ date('Y')-1 }}</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100">
                        <i class="fas fa-sync"></i> Refresh
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row">

        <!-- PRESENSI -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">
                    Rekapitulasi Presensi
                </div>

                <div class="card-body text-center">
                    <h4 class="text-success">{{ $persenSiswa ?? '0%' }}</h4>
                    <small>Kehadiran Siswa</small>

                    <hr>

                    <h4 class="text-primary">{{ $persenGuru ?? '0%' }}</h4>
                    <small>Kehadiran Guru</small>
                </div>

                <div class="card-footer bg-white">

                    <a href="{{ route('admin.laporan.presensiSiswa') }}" 
                       class="btn btn-success btn-sm w-100 mb-2">
                        <i class="fas fa-table"></i> Rekap Siswa
                    </a>

                    <a href="{{ route('admin.laporan.presensiGuru') }}" 
                       class="btn btn-info btn-sm w-100 mb-2">
                        <i class="fas fa-table"></i> Rekap Guru
                    </a>

                </div>
            </div>
        </div>

        <!-- PENILAIAN -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-warning">
                    Perkembangan Anak
                </div>

                <div class="card-body">
                    <p class="text-muted">
                        Laporan perkembangan anak (BSB, BSH, MB, BB)
                    </p>
                </div>

                <div class="card-footer bg-white">

                    <a href="{{ route('admin.laporan.penilaian') }}" 
                       class="btn btn-warning w-100 mb-2">
                        <i class="fas fa-eye"></i> Lihat Laporan
                    </a>

                </div>
            </div>
        </div>

    </div>

</div>
</section>

@endsection