@extends('layouts.adminlte')
@section('content')
<div class="content mt-3">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Guru: {{ $guru->nama_guru }}</h5>
                <a href="{{ route('admin.guru.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        @php
                            $isDefaultPhoto = in_array($guru->foto_guru, ['', null, 'images/default-teacher.svg', 'default-teacher.svg', 'images/default.png', 'default.png']);
                        @endphp
                        @if(!$isDefaultPhoto)
                            <img src="{{ asset('storage/'.$guru->foto_guru) }}" 
                                 class="img-fluid rounded-circle shadow border border-light" style="max-width: 250px; height: 250px; object-fit: cover;">
                        @else
                            <div class="d-flex align-items-center justify-content-center rounded-circle shadow border border-light bg-light"
                                 style="width:250px; height:250px;">
                                <i class="fas fa-chalkboard-teacher text-muted" style="font-size:120px;"></i>
                            </div>
                        @endif
                        <div class="mt-3">
                            @if($guru->barcode)
                                {!! QrCode::size(120)->generate($guru->barcode) !!}
                                <div class="mt-2 text-center">
                                    <div class="text-muted small mb-1">Scan QR untuk Absensi</div>
                                    <div class="fw-bold text-dark">
                                        {{ $guru->nip ? 'NIP' : 'Kode' }}: {{ $guru->barcode }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Nama Guru</div>
                            <div class="col-md-8">{{ $guru->nama_guru }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">NIP</div>
                            <div class="col-md-8">{{ $guru->nip ?? '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">NIK</div>
                            <div class="col-md-8">{{ $guru->nik ?? '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Jenis Kelamin</div>
                            <div class="col-md-8">{{ $guru->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Tanggal Lahir</div>
                            <div class="col-md-8">{{ $guru->ttl ? \Carbon\Carbon::parse($guru->ttl)->format('d/m/Y') : '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Jabatan</div>
                            <div class="col-md-8">{{ $guru->jabatan }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">No HP</div>
                            <div class="col-md-8">{{ $guru->no_hp ?? '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Email</div>
                            <div class="col-md-8">{{ $guru->email ?? '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Alamat Lengkap</div>
                            <div class="col-md-8">{{ $guru->alamat ?? '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Status</div>
                            <div class="col-md-8">
                                <span class="badge {{ ($guru->status ?? 'Aktif') == 'Aktif' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $guru->status ?? 'Aktif' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
