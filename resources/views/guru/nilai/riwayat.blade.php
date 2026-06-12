@extends('layouts.adminlte')

@section('content')
<style>
.content {
    background: #f8f9fc;
    min-height: 100vh;
    padding: 25px 0;
}

.page-title {
    font-weight: 800;
    color: #2c3e50;
    letter-spacing: -0.5px;
}

.custom-card {
    border: none;
    border-radius: 20px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 8px 30px rgba(0,0,0,0.05);
    margin-bottom: 25px;
}

.custom-header {
    background: #fff;
    border-bottom: 1px solid #f0f0f0;
    padding: 20px 25px;
}

.custom-header h5 {
    margin: 0;
    font-weight: 800;
    color: #2c3e50;
    display: flex;
    align-items: center;
}

.table-container {
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #edf2f7;
}

.table {
    margin-bottom: 0;
}

.table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.table thead th {
    vertical-align: middle;
    text-align: center;
    font-weight: 700;
    text-transform: uppercase;
    font-size: 11px;
    letter-spacing: 0.5px;
    padding: 15px 10px;
    border: none;
}

.table tbody td {
    vertical-align: middle;
    padding: 15px 12px;
}

.badge-nilai {
    padding: 6px 10px;
    border-radius: 8px;
    font-weight: 700;
    font-size: 11px;
}

/* Badge colors for PAUD standards */
.badge-bsb { background: #1cc88a; color: white; } /* Berkembang Sangat Baik */
.badge-bsh { background: #4e73df; color: white; } /* Berkembang Sesuai Harapan */
.badge-mb { background: #f6c23e; color: white; }  /* Mulai Berkembang */
.badge-bb { background: #e74a3b; color: white; }  /* Belum Berkembang */
.badge-none { background: #eaecf4; color: #858796; }

.btn-custom {
    border-radius: 12px;
    padding: 10px 20px;
    font-weight: 700;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary-custom {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.2);
}

.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(102, 126, 234, 0.3);
    color: white;
}

.rank-badge {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    font-weight: 800;
    margin: 0 auto;
}

.rank-1 { background: #f6c23e; color: #fff; box-shadow: 0 0 10px rgba(246, 194, 62, 0.4); }
.rank-2 { background: #a0aec0; color: #fff; }
.rank-3 { background: #ed8936; color: #fff; }
.rank-other { background: #edf2f7; color: #718096; }

.progress-custom {
    height: 12px;
    border-radius: 10px;
    background: #edf2f7;
    margin-top: 5px;
}
</style>

<div class="container-fluid py-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <h2 class="page-title mb-1">Hasil Penilaian Perkembangan</h2>
        </div>
        <a href="{{ route('guru.nilai.index') }}" class="btn btn-primary-custom btn-custom shadow-sm mt-3 mt-md-0">
            <i class="fas fa-plus mr-2"></i> Input Nilai Baru
        </a>
    </div>

    <!-- FILTER KELAS & SEMESTER -->
    <div class="custom-card">
        <div class="custom-header">
            <h5><i class="fas fa-filter mr-2 text-primary"></i> Filter Data</h5>
        </div>
        <div class="card-body p-4">
            <form method="GET" action="{{ route('guru.nilai.riwayat') }}" class="row align-items-end">
                @if(auth()->user()->role !== 'guru_kelas')
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="mb-2 font-weight-bold text-muted small text-uppercase">Pilih Kelas</label>
                    <select name="kelas_id" class="form-select form-control">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="mb-2 font-weight-bold text-muted small text-uppercase">Pilih Semester</label>
                    <select name="semester" class="form-select form-control">
                        <option value="1" {{ request('semester', 1) == 1 ? 'selected' : '' }}>Semester 1</option>
                        <option value="2" {{ request('semester', 1) == 2 ? 'selected' : '' }}>Semester 2</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3 mb-md-0">
                    <label class="mb-2 font-weight-bold text-muted small text-uppercase">Tahun Ajaran</label>
                    <select name="tahun_ajaran" class="form-select form-control">
                        @foreach($tahunAjaranOptions as $ta)
                            <option value="{{ $ta }}" {{ request('tahun_ajaran', $tahunAjaran) == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 mt-3 mt-md-0">
                    <button type="submit" class="btn btn-primary-custom btn-custom w-100">
                        <i class="fas fa-search mr-1"></i> Terapkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TABEL RIWAYAT NILAI -->
    <div class="custom-card">
        <div class="custom-header">
            <h5><i class="fas fa-list-ul mr-2 text-primary"></i> Data Capaian Anak Didik</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-hover text-center">
                        <thead>
                            <tr>
                                <th width="60">No</th>
                                <th class="text-left">Anak Didik</th>
                                <th>Kelas</th>
                                @foreach($kriterias as $k)
                                    <th title="{{ $k->nama_kriteria }}">{{ $k->kode }}</th>
                                @endforeach
                                <th>Tgl Input</th>
                                <th width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @forelse($nilaiPerkembangans as $siswaId => $items)
                                @php
                                    $firstItem = $items->first();
                                    $siswa = $firstItem->siswa;
                                    $nilaiArray = [];
                                    foreach ($items as $item) {
                                        $nilaiArray[$item->kriteria_id] = $item->nilai;
                                    }
                                @endphp
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td class="text-left">
                                        <div class="font-weight-bold text-dark">{{ $siswa->nama_siswa }}</div>
                                        <div class="small text-muted">NISN: {{ $siswa->nisn ?? '-' }}</div>
                                    </td>
                                    <td><span class="badge badge-light border px-2 py-1">{{ $siswa->kelas->nama_kelas ?? '-' }}</span></td>
                                    @foreach($kriterias as $k)
                                        @php 
                                            $val = $nilaiArray[$k->id] ?? null;
                                            $badgeClass = 'badge-none';
                                            $label = '-';
                                            if($val >= 100) { $badgeClass = 'badge-bsb'; $label = 'BSB'; }
                                            elseif($val >= 75) { $badgeClass = 'badge-bsh'; $label = 'BSH'; }
                                            elseif($val >= 50) { $badgeClass = 'badge-mb'; $label = 'MB'; }
                                            elseif($val > 0) { $badgeClass = 'badge-bb'; $label = 'BB'; }
                                        @endphp
                                        <td>
                                            <span class="badge badge-nilai {{ $badgeClass }}" title="Skor: {{ $val ?? 0 }}">
                                                {{ $label }}
                                            </span>
                                        </td>
                                    @endforeach
                                    <td class="small text-muted">{{ $firstItem->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <a href="{{ route('guru.nilai.index', ['siswa_id' => $siswa->id, 'kelas_id' => $kelas_id]) }}" 
                                               class="btn btn-sm btn-light border mr-1" title="Edit Nilai">
                                                <i class="fas fa-edit text-warning"></i>
                                            </a>
                                            <form action="{{ route('guru.nilai.destroy', $siswa->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light border" 
                                                        onclick="return confirm('Hapus riwayat nilai {{ $siswa->nama_siswa }}?')" title="Hapus">
                                                    <i class="fas fa-trash text-danger"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($kriterias) + 5 }}" class="py-5 text-muted text-center">
                                        <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                                        <p class="mb-0 font-weight-bold">Data tidak ditemukan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @if($kelas_id && !empty($matriksNormalisasi))
    <!-- Tombol Detail Analisis -->
    <div class="mb-3">
        <button class="btn btn-light btn-sm border shadow-sm rounded-pill px-3" type="button" data-toggle="collapse" data-target="#prosesAnalisis">
            <i class="fas fa-microscope mr-1 text-info"></i> Lihat Detail Analisis Data
        </button>
    </div>

    <div class="collapse mb-4" id="prosesAnalisis">
        <div class="custom-card">
            <div class="custom-header">
                <h5><i class="fas fa-calculator mr-2 text-info"></i> Hasil Normalisasi Nilai Perkembangan</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead class="bg-light text-dark">
                            <tr>
                                <th class="text-left">Nama Anak Didik</th>
                                @foreach($kriterias as $k)
                                    <th>{{ $k->kode }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($matriksNormalisasi as $sId => $row)
                            <tr>
                                <td class="text-left font-weight-bold">{{ $nilaiPerkembangans[$sId]->first()->siswa->nama_siswa }}</td>
                                @foreach($kriterias as $k)
                                    <td class="small">{{ $row[$k->id] }}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- HASIL ANALISIS OPTIMALISASI -->
    @if($kelas_id && !empty($hasilSAW))
    <div class="custom-card border-top-primary">
        <div class="custom-header bg-light">
            <h5><i class="fas fa-chart-bar mr-2 text-primary"></i> Urutan Capaian Perkembangan Anak</h5>
        </div>
        <div class="card-body p-4">
            <p class="text-muted small mb-4">
                <i class="fas fa-info-circle mr-1"></i>
                Data di bawah menunjukkan urutan anak didik berdasarkan tingkat capaian perkembangan yang paling optimal sesuai kriteria.
            </p>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr class="bg-light text-dark">
                            <th width="80">Urutan</th>
                            <th class="text-left">Anak Didik</th>
                            <th>NISN</th>
                            <th class="text-right">Skor Capaian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($hasilSAW as $index => $res)
                        <tr>
                            <td>
                                @php 
                                    $rankClass = 'rank-other';
                                    if($index == 0) $rankClass = 'rank-1';
                                    elseif($index == 1) $rankClass = 'rank-2';
                                    elseif($index == 2) $rankClass = 'rank-3';
                                @endphp
                                <div class="rank-badge {{ $rankClass }}">
                                    @if($index == 0) <i class="fas fa-crown"></i> @else {{ $index + 1 }} @endif
                                </div>
                            </td>
                            <td class="text-left">
                                <div class="font-weight-bold">{{ $res['nama'] }}</div>
                            </td>
                            <td><span class="text-muted">{{ $res['nisn'] ?? '-' }}</span></td>
                            <td class="text-right">
                                <div class="d-inline-block text-right" style="width: 150px;">
                                    <div class="font-weight-bold text-primary">{{ number_format($res['skor'], 3) }}</div>
                                    <div class="progress progress-custom">
                                        <div class="progress-bar bg-primary" role="progressbar" 
                                             style="width: {{ $res['skor'] * 100 }}%" 
                                             aria-valuenow="{{ $res['skor'] * 100 }}" aria-valuemin="0" aria-valuemax="100">
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="alert alert-primary border-0 mt-4 py-3" style="border-radius: 12px; background: #f0f4ff; color: #3b5998;">
                <div class="d-flex">
                    <i class="fas fa-lightbulb fa-lg mr-3 mt-1"></i>
                    <div>
                        <h6 class="font-weight-bold mb-1 small text-uppercase">Informasi Analisis:</h6>
                        <p class="mb-0 small">
                            Skor Capaian dihitung berdasarkan penggabungan seluruh nilai kriteria dengan bobot kepentingan masing-masing. 
                            Anak dengan skor mendekati **1.000** menunjukkan perkembangan yang sangat optimal sesuai dengan target capaian yang ditetapkan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @elseif($kelas_id)
    <div class="alert alert-warning border-0 shadow-sm p-4" style="border-radius: 15px;">
        <i class="fas fa-exclamation-triangle mr-2"></i> Data nilai belum mencukupi untuk melakukan analisis perkembangan otomatis.
    </div>
    @endif

</div>
@endsection

