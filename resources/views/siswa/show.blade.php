@extends('layouts.adminlte')
@section('content')
<div class="content mt-3">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Siswa: {{ $siswa->nama_siswa }}</h5>
                <a href="{{ route('admin.siswa.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        @php
                            $isDefaultPhoto = in_array($siswa->foto, ['', null, 'images/default-student.svg', 'default-student.svg', 'images/default.png', 'default.png']);
                        @endphp
                        @if(!$isDefaultPhoto)
                            <img src="{{ asset('storage/'.$siswa->foto) }}" 
                                 class="img-fluid rounded-circle shadow border border-light" style="max-width: 250px; height: 250px; object-fit: cover;">
                        @else
                            <div class="d-flex align-items-center justify-content-center rounded-circle shadow border border-light bg-light"
                                 style="width:250px; height:250px;">
                                <i class="fas fa-user-graduate text-muted" style="font-size:120px;"></i>
                            </div>
                        @endif
                        <div class="mt-3">
                            @if($siswa->barcode)
                                {!! QrCode::size(120)->generate($siswa->barcode) !!}
                                <div class="mt-2 text-center">
                                    <div class="text-muted small mb-1">Scan QR untuk Absensi</div>
                                    <div class="fw-bold text-dark">
                                        {{ $siswa->nisn !== '-' ? 'NISN' : 'Kode' }}: {{ $siswa->barcode }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Nama Lengkap</div>
                            <div class="col-md-8">{{ $siswa->nama_siswa }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Nama Panggilan</div>
                            <div class="col-md-8">{{ $siswa->nama_panggilan ?? '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">NIK</div>
                            <div class="col-md-8">{{ $siswa->nik ?? '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">NISN</div>
                            <div class="col-md-8">{{ $siswa->nisn ?? '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Jenis Kelamin</div>
                            <div class="col-md-8">{{ $siswa->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Tempat, Tanggal Lahir</div>
                            <div class="col-md-8">{{ $siswa->tempat_lahir ?? '-' }}, {{ $siswa->tanggal_lahir ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d/m/Y') : '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Agama</div>
                            <div class="col-md-8">{{ $siswa->agama ?? '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Anak Ke</div>
                            <div class="col-md-8">{{ $siswa->anak_ke ?? '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Jumlah Saudara</div>
                            <div class="col-md-8">{{ $siswa->jumlah_saudara ?? '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Kelas</div>
                            <div class="col-md-8">{{ $siswa->kelas->nama_kelas ?? '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Alamat Lengkap</div>
                            <div class="col-md-8">{{ $siswa->alamat ?? '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Status</div>
                            <div class="col-md-8">
                                <span class="badge {{ ($siswa->status ?? 'Aktif') == 'Aktif' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $siswa->status ?? 'Aktif' }}
                                </span>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <h6 class="fw-bold mb-3">Data Orang Tua</h6>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Nama Ayah</div>
                            <div class="col-md-8">{{ $siswa->orangTua->nama_ayah ?? '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Pekerjaan Ayah</div>
                            <div class="col-md-8">{{ $siswa->orangTua->pekerjaan_ayah ?? '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Nama Ibu</div>
                            <div class="col-md-8">{{ $siswa->orangTua->nama_ibu ?? '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Pekerjaan Ibu</div>
                            <div class="col-md-8">{{ $siswa->orangTua->pekerjaan_ibu ?? '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Nomor HP</div>
                            <div class="col-md-8">{{ $siswa->orangTua->no_hp ?? '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Alamat Orang Tua</div>
                            <div class="col-md-8">{{ $siswa->orangTua->alamat ?? '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
