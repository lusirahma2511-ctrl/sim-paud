@extends('layouts.adminlte')

@section('content')

<style>
.content {
    background: #f4f6f9;
    min-height: 100vh;
    padding: 20px 0;
}

/* CARD */
.card {
    background: #fff;
    border-radius: 10px;
    border: 1px solid #e3e6f0;
    box-shadow: 0 2px 6px rgba(0,0,0,0.05);
}

/* HEADER */
.card-header {
    background: #fff;
    border-bottom: 1px solid #e3e6f0;
    font-weight: 600;
    color: #333;
}

/* TABLE */
.table thead {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
}

.table th, 
.table td {
    border: 1px solid #dee2e6 !important;
}

/* BUTTON */
.btn-primary {
    background: #4e73df;
    border: none;
}
.btn-primary:hover {
    background: #2e59d9;
}

/* BADGE */
.badge-success { background-color: #28a745; }
.badge-warning { background-color: #ffc107; color: #000; }
.badge-info { background-color: #17a2b8; }
.badge-danger { background-color: #dc3545; }

</style>

<section class="content">
<div class="container-fluid">

    <!-- FILTER -->
    <div class="card mb-3 p-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

            <a href="{{ route('admin.presensi.index', ['tipe' => $tipe]) }}" class="btn btn-secondary">
                ← Kembali
            </a>

            <form action="{{ route('admin.presensi.rekap') }}" method="GET" class="d-flex flex-wrap gap-2">

                <input type="hidden" name="tipe" value="{{ $tipe }}">

                <!-- BULAN -->
                <select name="bulan" class="form-control">
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                        </option>
                    @endfor
                </select>

                <!-- TAHUN -->
                <select name="tahun" class="form-control">
                    @for($i = 2020; $i <= date('Y'); $i++)
                        <option value="{{ $i }}" {{ $tahun == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>

                <!-- FILTER KELAS (KHUSUS SISWA) -->
                @if($tipe == 'siswa')
                <select name="kelas_id" class="form-control">
                    <option value="">Semua Kelas</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
                @endif

                <button class="btn btn-primary">Filter</button>

            </form>
        </div>
    </div>

    <!-- TABLE -->
    <div class="card">
        <div class="card-header">
            Rekap Presensi {{ ucfirst($tipe) }} 
            - {{ \Carbon\Carbon::create()->month($bulan)->format('F') }} {{ $tahun }}
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th class="text-start">Nama {{ ucfirst($tipe) }}</th>
                        <th>H</th>
                        <th>S</th>
                        <th>I</th>
                        <th>A</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($rekap as $data)
                    <tr>
                        <td>{{ $loop->iteration }}</td>

                        <td class="text-start">
                            {{ $data['nama'] ?? '-' }}
                        </td>

                        <td><span class="badge bg-success">{{ $data['hadir'] ?? 0 }}</span></td>
                        <td><span class="badge bg-warning text-dark">{{ $data['sakit'] ?? 0 }}</span></td>
                        <td><span class="badge bg-info">{{ $data['izin'] ?? 0 }}</span></td>
                        <td><span class="badge bg-danger">{{ $data['alpha'] ?? 0 }}</span></td>

                        <td><strong>{{ $data['total'] ?? 0 }}</strong></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7">Belum ada data rekap.</td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>
</section>

@endsection