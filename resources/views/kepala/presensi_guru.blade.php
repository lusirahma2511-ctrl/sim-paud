@extends('layouts.adminlte')

@section('title', 'Laporan Rekap Presensi Guru')

@section('content')

<style>
.content-wrapper {
    background: #f4f6f9 !important;
}

.stat-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    transition: 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-icon {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.bg-hadir { background: linear-gradient(135deg, #28a745, #20c997); }
.bg-sakit-izin { background: linear-gradient(135deg, #ffc107, #ff9800); }
.bg-total { background: linear-gradient(135deg, #007bff, #0056b3); }
.bg-date { background: linear-gradient(135deg, #6f42c1, #a25ddc); }

.table-card {
    border: none;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}

.table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    text-transform: uppercase;
    font-size: 12px;
    letter-spacing: 1px;
}
</style>

<div class="container-fluid">
    
    <!-- FILTER -->
    <div class="card stat-card mb-4">
        <div class="card-body">
            <form method="GET" class="row align-items-end">
                <div class="col-md-4">
                    <label class="form-label font-weight-bold">Bulan</label>
                    <select name="bulan" class="form-control select2">
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label font-weight-bold">Tahun</label>
                    <select name="tahun" class="form-control">
                        @for($y=date('Y'); $y>=date('Y')-3; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter mr-1"></i> Filter
                    </button>
                </div>
                <div class="col-md-2">
                    <a href="{{ route('kepala.presensiGuru') }}" class="btn btn-secondary w-100">
                        <i class="fas fa-sync-alt"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- STATISTIK RINGKAS -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-total mr-3"><i class="fas fa-users"></i></div>
                    <div>
                        <div class="text-muted small">Total Presensi</div>
                        <div class="h4 mb-0 font-weight-bold">{{ $totalPresensi }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-hadir mr-3"><i class="fas fa-check-circle"></i></div>
                    <div>
                        <div class="text-muted small">Total Hadir</div>
                        <div class="h4 mb-0 font-weight-bold">{{ $totalHadir }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-sakit-izin mr-3"><i class="fas fa-user-clock"></i></div>
                    <div>
                        <div class="text-muted small">Total Izin/Sakit</div>
                        <div class="h4 mb-0 font-weight-bold">{{ $totalIzin + $totalSakit }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card">
                <div class="card-body d-flex align-items-center">
                    <div class="stat-icon bg-date mr-3"><i class="fas fa-calendar-day"></i></div>
                    <div>
                        <div class="text-muted small">Periode</div>
                        <div class="h5 mb-0 font-weight-bold">{{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }} {{ $tahun }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABEL REKAP -->
    <div class="card table-card">
        <div class="card-header bg-white">
            <h5 class="mb-0 font-weight-bold"><i class="fas fa-table mr-2 text-primary"></i> Rekap Bulanan Guru</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover text-center mb-0">
                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th class="text-left">Nama Guru</th>
                            <th>Jabatan</th>
                            <th class="text-success">Hadir</th>
                            <th class="text-warning">Sakit</th>
                            <th class="text-primary">Izin</th>
                            <th class="text-danger">Alpha</th>
                            <th>Total Hari</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tabelData as $i => $item)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="text-left font-weight-bold">{{ $item['nama'] }}</td>
                            <td><span class="badge badge-light border">{{ $item['jabatan'] }}</span></td>
                            <td><span class="badge badge-success px-3">{{ $item['hadir'] }}</span></td>
                            <td><span class="badge badge-warning px-3">{{ $item['sakit'] }}</span></td>
                            <td><span class="badge badge-primary px-3">{{ $item['izin'] }}</span></td>
                            <td><span class="badge badge-danger px-3">{{ $item['alpha'] }}</span></td>
                            <td class="font-weight-bold">{{ $item['hadir'] + $item['sakit'] + $item['izin'] + $item['alpha'] }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="py-5 text-muted">
                                <i class="fas fa-folder-open fa-3x mb-3 d-block"></i>
                                Belum ada data presensi guru untuk periode ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
