@extends('layouts.adminlte')
@section('content')
<div class="content mt-3">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Kelas: {{ $kelas->nama_kelas }}</h5>
                <a href="{{ route('admin.kelas.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4 fw-bold">Nama Kelas</div>
                    <div class="col-md-8">{{ $kelas->nama_kelas }}</div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-4 fw-bold">Guru Kelas</div>
                    <div class="col-md-8">{{ $kelas->guru->nama_guru ?? '-' }}</div>
                </div>
                
                <hr>
                
                <h6 class="fw-bold mb-3">Daftar Siswa di Kelas Ini</h6>
                @if($kelas->siswa->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="bg-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>NISN</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kelas->siswa as $index => $siswa)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $siswa->nama_siswa }}</td>
                                    <td>{{ $siswa->nisn ?? '-' }}</td>
                                    <td>{{ $siswa->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    <td>
                                        <span class="badge {{ ($siswa->status ?? 'Aktif') == 'Aktif' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $siswa->status ?? 'Aktif' }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">Belum ada siswa di kelas ini.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
