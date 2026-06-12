@extends('layouts.adminlte')

@section('title', 'Generate E-Rapor')

@section('content')
<div class="container-fluid">

    <!-- FILTER -->
    <div class="card shadow-sm">
        <div class="card-header bg-purple text-white">
            <h3 class="card-title">Filter Data</h3>
        </div>

        <form method="GET">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Kelas</label>
                        <select name="kelas_id" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Semester</label>
                        <select name="semester" class="form-control" onchange="this.form.submit()">
                            <option value="1" {{ request('semester', 1) == 1 ? 'selected' : '' }}>Semester 1</option>
                            <option value="2" {{ request('semester', 1) == 2 ? 'selected' : '' }}>Semester 2</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Tahun Ajaran</label>
                        <select name="tahun_ajaran" class="form-control" onchange="this.form.submit()">
                            @foreach($tahunAjaranOptions as $ta)
                                <option value="{{ $ta }}" {{ request('tahun_ajaran', $tahunAjaran) == $ta ? 'selected' : '' }}>{{ $ta }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>

    <!-- DATA SISWA -->
    @if(request('kelas_id'))
    <div class="card mt-3 shadow-sm">
        <div class="card-header bg-purple text-white">
            <h3 class="card-title">Daftar Siswa - Semester {{ $semester }}</h3>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover">
                <thead class="bg-light">
                    <tr>
                        <th>No</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th width="250">Aksi</th>
                    </tr>
                </thead>

                <tbody>
@forelse($siswas as $i => $s)
    <tr>
        <td>{{ $i+1 }}</td>
        <td>{{ $s->nisn }}</td>
        <td>
            {{ $s->nama_siswa }}
            <br>
            <!-- Indikator Status (Opsional tapi keren) -->
            @if($s->nilai_perkembangans_count < count($kriterias))
                <small class="text-danger"><i class="fas fa-exclamation-triangle"></i> Nilai belum lengkap ({{ $s->nilai_perkembangans_count }}/8)</small>
            @else
                <small class="text-success"><i class="fas fa-check-circle"></i> Nilai Siap</small>
            @endif
        </td>
        <td width="250">
            @if($s->nilai_perkembangans_count >= count($kriterias))
                <!-- PREVIEW -->
                <a href="{{ route('admin.erapor.show', $s->id) }}?semester={{ $semester }}&tahun_ajaran={{ $tahunAjaran }}" target="_blank"
                   class="btn btn-sm btn-info">
                    <i class="fas fa-eye"></i> Preview
                </a>

                <!-- PRINT -->
                <a href="{{ route('admin.erapor.print', $s->id) }}?semester={{ $semester }}&tahun_ajaran={{ $tahunAjaran }}" target="_blank"
                   class="btn btn-sm btn-success">
                    <i class="fas fa-print"></i> Print
                </a>

                <!-- DOWNLOAD -->
                <a href="{{ route('admin.erapor.download', $s->id) }}?semester={{ $semester }}&tahun_ajaran={{ $tahunAjaran }}"
                   class="btn btn-sm btn-danger">
                    <i class="fas fa-file-pdf"></i> Download
                </a>
            @else
                <button class="btn btn-sm btn-secondary" disabled title="Nilai belum diinput lengkap oleh guru">
                    <i class="fas fa-lock"></i> Belum Siap
                </button>
            @endif
        </td>
    </tr>
@empty
    <!-- ... -->
@endforelse
</tbody>

            </table>
        </div>
    </div>
    @endif

</div>

<style>
.bg-purple {
    background: #6f42c1 !important;
}
.text-purple {
    color: #6f42c1;
}
</style>

@endsection