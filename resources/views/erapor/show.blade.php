@extends('layouts.adminlte')

@section('title', 'Detail E-Rapor')

@section('content')
<style>
    .erapor-container {
        font-family: 'Times New Roman', Times, serif !important;
        background: linear-gradient(180deg, #8174ff, #f3d2fa);
        min-height: 100vh;
        padding: 20px;
        border-radius: 15px;
    }

    .erapor-container * {
        font-family: 'Times New Roman', Times, serif !important;
    }

    .card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 6px 15px rgba(0,0,0,0.08);
    }

    .card-header {
        background: linear-gradient(90deg, #6f42c1, #ef99f1);
        color: white;
        border-top-left-radius: 15px !important;
        border-top-right-radius: 15px !important;
    }

    .btn-custom {
        border-radius: 10px;
        font-weight: 500;
        padding: 8px 15px;
    }

    .btn-purple {
        background: #6f42c1;
        color: white;
    }

    .btn-purple:hover {
        background: #5a34a1;
        color: white;
    }

    .nilai-box {
        text-align: center;
        padding: 25px;
        border-radius: 15px;
        background: linear-gradient(135deg, #6f42c1, #a25ddc);
        color: white;
    }

    .nilai-besar {
        font-size: 3rem;
        font-weight: bold;
    }

    .badge-custom {
        padding: 6px 10px;
        border-radius: 8px;
    }
</style>

<div class="erapor-container">
<div class="container-fluid">

    <!-- HEADER BUTTON -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <a href="{{ route('admin.erapor.index') }}?kelas_id={{ $siswa->kelas_id }}&semester={{ $semester }}&tahun_ajaran={{ $tahunAjaran }}" class="btn btn-secondary btn-custom">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <div>
            <a href="{{ route('admin.erapor.print', $siswa->id) }}?semester={{ $semester }}&tahun_ajaran={{ $tahunAjaran }}" target="_blank" class="btn btn-purple btn-custom">
                <i class="fas fa-print"></i> Print
            </a>

            <a href="{{ route('admin.erapor.download', $siswa->id) }}?semester={{ $semester }}&tahun_ajaran={{ $tahunAjaran }}" class="btn btn-danger btn-custom">
                <i class="fas fa-file-pdf"></i> Download PDF
            </a>
        </div>
    </div>

    <div class="row mb-4">

        <!-- DATA SISWA -->
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-user"></i> Data Peserta Didik - Semester {{ $semester }} ({{ $tahunAjaran }})</h5>
        </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><th width="40%">NISN</th><td>: {{ $siswa->nisn }}</td></tr>
                                <tr><th>Nama</th><td>: <strong>{{ $siswa->nama_siswa }}</strong></td></tr>
                                <tr><th>Kelas</th><td>: {{ $siswa->kelas->nama_kelas ?? '-' }}</td></tr>
                            </table>
                        </div>

                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr><th width="40%">Jenis Kelamin</th><td>: {{ $siswa->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</td></tr>
                                <tr><th>Tanggal Lahir</th><td>: {{ $siswa->tanggal_lahir }}</td></tr>
                                <tr><th>Alamat</th><td>: {{ $siswa->alamat }}</td></tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- HASIL SAW -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line"></i> Hasil Analisis Data</h5>
                </div>

                <div class="card-body d-flex flex-column justify-content-center">
                    <div class="nilai-box mb-3">
                        <div class="nilai-besar">
    {{ number_format($sawResult['total'], 3) }} {{-- Ubah dari 2 ke 3 --}}
</div>

                        <small>Skor Akhir</small>
                    </div>

                    <div class="text-center">
                        <h5>Peringkat: <strong>{{ $sawResult['rank'] }}</strong></h5>
                        <small class="text-muted">
                            Dari {{ count($sawResult['all_results']) }} siswa
                        </small>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- TABEL NILAI -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-clipboard-list"></i> Detail Nilai Perkembangan</h5>
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered text-center align-middle">
            <thead style="background:#f3e8ff;">
                <tr>
                    <th>No</th>
                    <th>Kode</th>
                    <th>Dimensi Profil Lulusan</th>
                    <th>Skor</th>
                    <th>Capaian Perkembangan (Kualitatif)</th>
                </tr>
            </thead>

            <tbody>
            @foreach($kriterias as $i => $k)
                @php
                    // Ambil data dari array yang dikirim controller
                    $dataNilai = $nilaiArray[$k->id] ?? null;
                    $skorAngka = $dataNilai['angka'] ?? 0;
                    $teksKualitatif = $dataNilai['teks'] ?? 'Belum Dinilai';

                    // Tentukan warna badge berdasarkan skor
                    $color = 'secondary';
                    if($skorAngka >= 85) $color = 'success';
                    elseif($skorAngka >= 75) $color = 'primary';
                    elseif($skorAngka >= 60) $color = 'warning';
                    elseif($skorAngka > 0) $color = 'danger';
                @endphp

                <tr>
                    <td>{{ $i+1 }}</td>
                    <td><span class="badge badge-secondary">{{ $k->kode }}</span></td>
                    <td class="text-left"><strong>{{ $k->nama_kriteria }}</strong></td>
                    <td><span class="text-primary fw-bold">{{ $skorAngka }}</span></td>
                    <td>
                        <span class="badge badge-{{ $color }} badge-custom" style="font-size: 0.9rem;">
                            {{ $teksKualitatif }}
                        </span>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>


</div>
</div>
@endsection