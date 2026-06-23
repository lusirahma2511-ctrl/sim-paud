@extends('layouts.adminlte')

@section('content')
<style>
/* ===== GLOBAL WRAPPER (ONLY SCROLL HERE) ===== */
html, body {
    height: 100%;
    overflow: auto;
}

/* ===== CLEAN UI (BACKGROUND PUTIH) ===== */
.content-wrapper {
    background: #ffffff !important;
    margin-left: 250px; /* Lebar default sidebar AdminLTE */
    overflow: visible !important;
}

/* 🔥 FIX MODAL SCROLL & LAYOUT - REMOVED (GLOBALIZED) */

@media (max-width: 991.98px) {
    .content-wrapper {
        margin-left: 0 !important;
    }
}

/* ===== RESPONSIVE ===== */
.siswa-card {
    display: none;
}

@media (max-width: 768px) {
    .table-view {
        display: none;
    }

    .siswa-card {
        display: block;
    }

    .card-siswa-item {
        background: #fff;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .card-siswa-item img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 10px;
    }
}

/* ===== KARTU IDENTITAS MODAL STYLE ===== */
.id-card-modal {
    width: 350px;
    min-height: 500px;
    background: #f0f7ff;
    border-radius: 15px;
    margin: 0 auto;
    position: relative;
    padding-bottom: 50px;
    overflow: hidden;
    border: 1px solid #ddd;
}

.id-card-modal .card-header-kartu {
    background: #7791e9ff;
    color: white;
    padding: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    border-bottom: 4px solid #0056b3;
}

.id-card-modal .card-header-kartu img {
    width: 40px;
    height: auto;
}

.id-card-modal .header-text-kartu {
    text-align: left;
}

.id-card-modal .header-text-kartu h4 {
    margin: 0;
    font-weight: 800;
    font-size: 13px;
    text-transform: uppercase;
    color: #03376eff;
}

.id-card-modal .header-text-kartu small {
    font-size: 9px;
    font-weight: 600;
    color: #fff;
    display: block;
}

.id-card-modal .card-body-kartu {
    padding: 15px;
    text-align: center;
}

.id-card-modal .photo-container-kartu {
    width: 120px;
    height: 150px;
    margin: 0 auto 10px;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    border: 2px solid #eee;
    display: flex;
    align-items: center;
    justify-content: center;
}

.id-card-modal .photo-container-kartu img {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
}

.id-card-modal .student-name-kartu {
    font-size: 16px;
    font-weight: 800;
    color: #333;
    margin-bottom: 8px;
    text-transform: uppercase;
}

.id-card-modal .qr-section-kartu {
    margin: 5px 0;
    padding: 6px;
    background: #fff;
    display: inline-block;
    border-radius: 8px;
    border: 1px solid #f0f0f0;
}

.id-card-modal .student-nisn-kartu {
    font-size: 11px;
    font-weight: 600;
    color: #555;
}

.id-card-modal .footer-text-kartu {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: #ffffff;
    padding: 10px;
    font-size: 9px;
    color: #777;
    text-align: center;
}

.id-card-modal .footer-line-kartu {
    width: 80%;
    height: 1px;
    background: #b5d5f8ff;
    margin: 0 auto 6px;
}

@media print {
    .modal-header, .modal-footer, .nav-buttons {
        display: none !important;
    }
    .modal-body {
        padding: 0 !important;
    }
    .id-card-modal {
        box-shadow: none !important;
        border: 1px solid #000 !important;
    }
}
</style>

<section class="content">
<div class="container-fluid">

<!-- TOOLBAR -->
<div class="toolbar-siswa d-flex justify-content-between flex-wrap gap-2 mb-3">

    <div class="d-flex gap-2 flex-wrap">
        <button class="btn btn-primary" data-toggle="modal" data-target="#tambahSiswaModal" title="Tambah Siswa">
            <i class="fas fa-plus"></i>
        </button>

        <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
            @csrf
            <input type="file" name="file" class="form-control form-control-sm">
            <button class="btn btn-success btn-sm">Import</button>
        </form>
    </div>

    <form action="{{ route('admin.siswa.index') }}" method="GET" class="d-flex">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm me-2" placeholder="Cari...">
        <button class="btn btn-outline-primary btn-sm">Cari</button>
    </form>

</div>

<!-- CARD -->
<div class="card">
    <div class="card-header bg-info text-white">
        Daftar Siswa ({{ $siswa->total() }})
    </div>

    <div class="card-body p-2">

        <!-- TABLE DESKTOP -->
        <div class="table-container table-view">
            <table class="table table-bordered table-hover text-center align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>JK</th>
                        <th>Kelas</th>
                        <th>Nama Ayah</th>
                        <th>Alamat Singkat</th>
                        <th>Status</th>
                        <th>Foto</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
               <tbody>
    @forelse($siswa as $s)
    <tr>
        <td>{{ ($siswa->currentPage() - 1) * $siswa->perPage() + $loop->iteration }}</td>
        
        <td class="text-start fw-bold">{{ $s->nama_siswa }}</td>

        <td>{{ $s->jk }}</td>

        <td><span class="badge bg-secondary">{{ $s->kelas->nama_kelas ?? 'Belum Set' }}</span></td>

        <td>{{ $s->orangTua->nama_ayah ?? '-' }}</td>

        <td class="text-start" style="font-size: 0.75rem; max-width: 150px;">
            {{ Str::limit($s->alamat, 40) }}
        </td>

        <td>
            <span class="badge {{ ($s->status ?? 'Aktif') == 'Aktif' ? 'bg-success' : 'bg-danger' }}">
                {{ $s->status ?? 'Aktif' }}
            </span>
        </td>

        <td class="align-middle">
            @php
                $isDefaultPhoto = in_array($s->foto, ['', null, 'images/default-student.svg', 'default-student.svg', 'images/default.png', 'default.png']);
            @endphp
            @if(!$isDefaultPhoto)
                <img src="{{ asset('storage/'.$s->foto) }}" 
                     width="40" height="40" class="rounded-circle shadow-sm border border-light object-cover">
            @else
                <div class="rounded-circle shadow-sm d-inline-flex align-items-center justify-content-center bg-light border border-light"
                     style="width:40px; height:40px;">
                    <i class="fas fa-user-graduate text-muted" style="font-size:18px;"></i>
                </div>
            @endif
        </td>

        <td>
            <div class="d-flex gap-1 justify-content-center">
                <a href="{{ route('admin.siswa.show', $s->id) }}" class="btn btn-sm btn-success" title="Detail">
                    <i class="fas fa-eye"></i>
                </a>
                <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#kartuSiswaModal{{ $s->id }}" title="Cetak Kartu">
                    <i class="fas fa-print"></i>
                </button>
                <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editSiswaModal{{ $s->id }}" title="Edit Data">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" class="btn btn-sm btn-danger" 
                    onclick="confirmDelete('{{ $s->id }}', 'delete-form-siswa-{{ $s->id }}')"
                    title="Hapus Data">
                    <i class="fas fa-trash"></i>
                </button>
                <form id="delete-form-siswa-{{ $s->id }}" action="{{ route('admin.siswa.destroy', $s->id) }}" method="POST" style="display: none;">
                    @csrf @method('DELETE')
                </form>
            </div>
        </td>
    </tr>
    @empty
        <tr><td colspan="10" class="text-center">Belum ada data siswa</td></tr>
    @endforelse
</tbody>
            </table>
        </div>

        <!-- CARD HP -->
<div class="siswa-card">
    @foreach($siswa as $s)
    <div class="card-siswa-item">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h6 class="mb-0 text-bold">{{ $s->nama_siswa }}</h6>
                <small class="text-success">
        Panggilan: {{ $s->nama_panggilan ?? '-' }}
    </small><br>

                <small class="text-muted">NIK: {{ $s->nik ?? '-' }}</small>
            </div>
            @php
                $isDefaultPhoto = in_array($s->foto, ['', null, 'images/default-student.svg', 'default-student.svg', 'images/default.png', 'default.png']);
            @endphp
            @if(!$isDefaultPhoto)
                <img src="{{ asset('storage/'.$s->foto) }}" 
                     style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;" class="border border-light shadow-sm">
            @else
                <div class="d-flex align-items-center justify-content-center bg-light border border-light shadow-sm"
                     style="width:60px; height:60px; border-radius:50%;">
                    <i class="fas fa-user-graduate text-muted" style="font-size:28px;"></i>
                </div>
            @endif
        </div>

        <hr class="my-2">

        <div style="font-size: 0.9rem;">
            <p class="mb-1"><b>JK:</b> {{ $s->jk }}</p>
            <p class="mb-1"><b>Kelas:</b> {{ $s->kelas->nama_kelas ?? '-' }}</p>
            <p class="mb-1"><b>Ayah/Ibu:</b> {{ $s->orangTua->nama_ayah ?? $s->orangTua->nama_ibu ?? '-' }}</p>
            <p class="mb-1"><b>Status:</b> 
                <span class="badge {{ ($s->status ?? 'Aktif') == 'Aktif' ? 'bg-success' : 'bg-danger' }}">
                    {{ $s->status ?? 'Aktif' }}
                </span>
            </p>
            <p class="mb-1"><b>Alamat:</b> <span class="text-muted">{{ $s->alamat }}</span></p>
        </div>

        <div class="d-flex justify-content-center gap-2">

    <!-- PRINT -->
    <button class="btn btn-light btn-sm"
            data-toggle="modal"
            data-target="#kartuSiswaModal{{ $s->id }}"
            title="Cetak Kartu">
        <i class="fas fa-print text-primary"></i>
    </button>

    <!-- EDIT -->
    <button class="btn btn-light btn-sm"
            data-toggle="modal"
            data-target="#editSiswaModal{{ $s->id }}"
            title="Edit Data">
        <i class="fas fa-edit text-info"></i>
    </button>

    <!-- DELETE -->
    <form action="{{ route('admin.siswa.destroy', $s->id) }}"
          method="POST"
          class="m-0 p-0 btn-delete-siswa">
        @csrf
        @method('DELETE')
        <button type="button" class="btn btn-light btn-sm"
                data-toggle="tooltip"
                title="Hapus Data">
            <i class="fas fa-trash text-danger"></i>
        </button>
    </form>

</div>
</div>
    @endforeach
</div>

<!-- Modal Kartu Siswa -->
@foreach($siswa as $s)
<div class="modal fade" id="kartuSiswaModal{{ $s->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content" style="background: transparent; border: none;">
            <div class="modal-header border-0 p-0 mb-3 justify-content-center gap-2">
                <button type="button" class="btn btn-secondary shadow-sm" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
                <button type="button" class="btn btn-primary shadow-sm" onclick="printCard('card-to-print-{{ $s->id }}')">
                    <i class="fas fa-print"></i> Cetak
                </button>
                <button type="button" class="btn btn-success shadow-sm" onclick="downloadCard('card-to-print-{{ $s->id }}', '{{ Str::slug($s->nama_siswa) }}')">
                    <i class="fas fa-download"></i> PNG
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="id-card-modal" id="card-to-print-{{ $s->id }}">
                    <!-- HEADER -->
                    <div class="card-header-kartu">
                        <img src="{{ asset('images/logo paud rmv.png') }}" alt="Logo">
                        <div class="header-text-kartu">
                            <h4>Kartu Identitas Siswa</h4>
                            <small>POS PAUD TERATAI SINDANGSARI</small>
                        </div>
                    </div>

                    <div class="card-body-kartu">
                        <!-- FOTO -->
                        <div class="photo-container-kartu">
                            @php
                            $isDefaultPhoto = in_array($s->foto, ['', null, 'images/default-student.svg', 'default-student.svg', 'images/default.png', 'default.png']);
                        @endphp
                        @if(!$isDefaultPhoto)
                                <img src="{{ asset('storage/' . $s->foto) }}" 
                                     alt="Foto {{ $s->nama_siswa }}" class="object-cover w-full h-full">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light w-full h-full">
                                    <i class="fas fa-user-graduate text-muted" style="font-size:60px;"></i>
                                </div>
                            @endif
                        </div>

                        <!-- DATA UTAMA -->
                        <div class="student-name-kartu">{{ $s->nama_siswa }}</div>

                        <!-- QR CODE -->
                        <div class="qr-section-kartu">
                            @if($s->barcode)
                                {!! QrCode::size(80)->generate($s->barcode) !!}
                                <div class="mt-2" style="font-size: 9px; font-weight: bold;">
                                    {{ $s->nisn !== '-' ? 'NISN' : 'Kode' }}: {{ $s->barcode }}
                                </div>
                            @else
                                <small class="text-danger" style="font-size: 8px;">QR N/A</small>
                            @endif
                        </div>

                        <div class="student-nisn-kartu">NISN: {{ $s->nisn }}</div>
                    </div>

                    <!-- FOOTER -->
                    <div class="footer-text-kartu">
                        <div class="footer-line-kartu"></div>
                        Kartu ini merupakan tanda pengenal resmi siswa<br>
                        <b>POS PAUD TERATAI SINDANGSARI</b>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- PAGINATION -->
<div class="d-flex justify-content-center mt-3 mb-5">
    {{ $siswa->links('pagination::bootstrap-4') }}
</div>

<!-- Modal Edit Orang Tua -->
<div class="modal fade" id="editOrangTuaModal" tabindex="-1" aria-labelledby="editOrangTuaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="editOrangTuaModalLabel">Edit Data Orang Tua</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>

            <form id="formEditOrangTua" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <!-- Identitas Ayah -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Ayah</label>
                            <input type="text" name="nama_ayah" id="edit_nama_ayah" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Pekerjaan Ayah</label>
                            <input type="text" name="pekerjaan_ayah" id="edit_pekerjaan_ayah" class="form-control" required>
                        </div>

                        <!-- Identitas Ibu -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Ibu</label>
                            <input type="text" name="nama_ibu" id="edit_nama_ibu" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Pekerjaan Ibu</label>
                            <input type="text" name="pekerjaan_ibu" id="edit_pekerjaan_ibu" class="form-control" required>
                        </div>

                        <!-- Kontak & Alamat -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nomor WhatsApp/HP</label>
                            <input type="text" name="no_hp" id="edit_no_hp" class="form-control" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Alamat Lengkap</label>
                            <textarea class="form-control" name="alamat" id="edit_alamat_ortu" rows="2" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" id="btnBatalEditOrangTua">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-info">Update Orang Tua</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Tambah Siswa -->
<div class="modal fade" id="tambahSiswaModal" tabindex="-1" aria-labelledby="tambahSiswaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tambahSiswaModalLabel">Tambah Siswa Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>

            <!-- FORM -->
            <form id="formTambahSiswa" method="POST" action="{{ route('admin.siswa.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="row">

                        <!-- ===== KOLOM KIRI ===== -->
                        <div class="col-md-6">

                            <div class="mb-3">
                                <label class="form-label fw-bold">NIK</label>
                                <input type="text" class="form-control" name="nik" placeholder="Sesuai KK">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" class="form-control" name="nama_siswa" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Panggilan</label>
                                <input type="text" class="form-control" name="nama_panggilan" placeholder="Contoh: Budi">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">NISN</label>
                                <input type="text" class="form-control" name="nisn">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Jenis Kelamin</label>
                                    <select class="form-control" name="jk" required>
                                        <option value="">-- Pilih --</option>
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Agama</label>
                                    <select class="form-control" name="agama">
                                        <option value="">-- Pilih --</option>
                                        <option value="Islam">Islam</option>
                                        <option value="Kristen">Kristen</option>
                                        <option value="Katolik">Katolik</option>
                                        <option value="Hindu">Hindu</option>
                                        <option value="Budha">Budha</option>
                                    </select>
                                </div>
                            </div>

                        </div>

                        <!-- ===== KOLOM KANAN ===== -->
                        <div class="col-md-6">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Tempat Lahir</label>
                                    <input type="text" class="form-control" name="tempat_lahir">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Tanggal Lahir</label>
                                    <input type="date" class="form-control" name="tanggal_lahir">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Anak Ke</label>
                                    <input type="number" class="form-control" name="anak_ke" placeholder="Contoh: 1">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Jumlah Saudara</label>
                                    <input type="number" class="form-control" name="jumlah_saudara" placeholder="Contoh: 2">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Kelas</label>
                                <select class="form-control" name="kelas_id" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Orang Tua / Wali</label>
                                <div class="input-group">
                                    <select class="form-control select2" name="orang_tua_id" id="orang_tua_id" required>
                                        <option value="">-- Pilih Orang Tua --</option>
                                        <option value="new">+ Tambah Orang Tua Baru</option>
                                        @foreach($orang_tuas as $ot)
                                            <option value="{{ $ot->id }}">
                                                Ayah: {{ $ot->nama_ayah }} | Ibu: {{ $ot->nama_ibu }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-info btn-edit-ortu" title="Edit Data Orang Tua Terpilih">
                                            Edit
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Foto Siswa</label>
                                <input type="file" class="form-control" name="foto">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Status Siswa</label>
                                <select class="form-control" name="status" required>
                                    <option value="Aktif">Aktif</option>
                                    <option value="Nonaktif">Nonaktif</option>
                                </select>
                            </div>

                        </div>

                        <!-- ===== FULL WIDTH ===== -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Alamat Lengkap</label>
                                <textarea class="form-control" name="alamat" rows="2" placeholder="Jl. Nama Desa, RT/RW, Kec, Kab, Prov"></textarea>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>

            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="tambahOrangTuaModal" tabindex="-1" aria-labelledby="tambahOrangTuaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="tambahOrangTuaModalLabel">Tambah Data Orang Tua</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">&times;</button>
            </div>

            <form id="formTambahOrangTua" action="{{ route('admin.orang_tua.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Identitas Ayah -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="form-control" placeholder="Nama Lengkap Ayah" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Pekerjaan Ayah</label>
                            <input type="text" name="pekerjaan_ayah" class="form-control" placeholder="Contoh: PNS, Wiraswasta" required>
                        </div>

                        <!-- Identitas Ibu -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama Ibu</label>
                            <input type="text" name="nama_ibu" class="form-control" placeholder="Nama Lengkap Ibu" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Pekerjaan Ibu</label>
                            <input type="text" name="pekerjaan_ibu" class="form-control" placeholder="Contoh: Ibu Rumah Tangga" required>
                        </div>

                        <!-- Kontak & Alamat -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nomor WhatsApp/HP</label>
                            <input type="text" name="no_hp" class="form-control" placeholder="Contoh: 08123456789" required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-bold">Alamat Lengkap</label>
                            <textarea class="form-control" name="alamat" rows="2" placeholder="Jl. Nama Desa, RT/RW, Kec, Kab, Prov" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" id="btnBatalOrangTua">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSimpanOrangTua">Simpan Orang Tua</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Siswa -->
@foreach($siswa as $s)
<div class="modal fade" id="editSiswaModal{{ $s->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    Edit: <b>{{ $s->nama_siswa }}</b>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <!-- FORM -->
            <form method="POST" action="{{ route('admin.siswa.update', $s->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-body">

                    <div class="row">

                        <!-- IDENTITAS -->
                        <div class="col-md-6">
                            <h6 class="text-muted border-bottom pb-1 mb-3">Data Siswa</h6>

                            <div class="mb-3">
                                <label class="form-label fw-bold">NIK</label>
                                <input type="text" name="nik" class="form-control"
                                    value="{{ $s->nik }}" placeholder="Sesuai KK">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Lengkap</label>
                                <input type="text" name="nama_siswa" class="form-control"
                                    value="{{ $s->nama_siswa }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Nama Panggilan</label>
                                <input type="text" name="nama_panggilan" class="form-control"
                                    value="{{ $s->nama_panggilan }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">NISN</label>
                                <input type="text" name="nisn" class="form-control"
                                    value="{{ $s->nisn }}">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Jenis Kelamin</label>
                                    <select name="jk" class="form-control">
                                        <option value="L" {{ $s->jk == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ $s->jk == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Agama</label>
                                    <select name="agama" class="form-control">
                                        @foreach(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha'] as $agama)
                                            <option value="{{ $agama }}" {{ $s->agama == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- DETAIL -->
                        <div class="col-md-6">
                            <h6 class="text-muted border-bottom pb-1 mb-3">Detail & Kelahiran</h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" class="form-control" value="{{ $s->tempat_lahir }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ $s->tanggal_lahir }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Anak Ke</label>
                                    <input type="number" name="anak_ke" class="form-control" value="{{ $s->anak_ke }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Jumlah Saudara</label>
                                    <input type="number" name="jumlah_saudara" class="form-control" value="{{ $s->jumlah_saudara }}">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Kelas</label>
                                <select name="kelas_id" class="form-control">
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}"
                                            {{ $s->kelas_id == $k->id ? 'selected' : '' }}>
                                            {{ $k->nama_kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Orang Tua / Wali</label>
                                <div class="input-group">
                                    <select name="orang_tua_id" class="form-control select2-edit">
                                        <option value="">-- Pilih Orang Tua --</option>
                                        <option value="new">+ Tambah Orang Tua Baru</option>
                                        @foreach($orang_tuas as $ot)
                                            <option value="{{ $ot->id }}"
                                                {{ $s->orang_tua_id == $ot->id ? 'selected' : '' }}>
                                                Ayah: {{ $ot->nama_ayah }} | Ibu: {{ $ot->nama_ibu }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-info btn-edit-ortu" data-ortu-id="{{ $s->orang_tua_id }}" title="Edit Data Orang Tua Terpilih">
                                            Edit
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Ganti Foto (Opsional)</label>
                                <input type="file" name="foto" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Status Siswa</label>
                                <select name="status" class="form-control" required>
                                    <option value="Aktif" {{ $s->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Nonaktif" {{ $s->status == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                        </div>

                        <!-- ALAMAT -->
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Alamat Lengkap</label>
                                <textarea name="alamat" class="form-control" rows="2" placeholder="Jl. Nama Desa, RT/RW, Kec, Kab, Prov">{{ $s->alamat }}</textarea>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- FOOTER -->
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Update Data
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endforeach
@push('scripts')

<!-- SELECT2 JS (Load setelah jQuery di footer) -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- html2canvas -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
// Fungsi global untuk kartu
function printCard(id) {
    const content = document.getElementById(id).outerHTML;
    const win = window.open('', '_blank');
    win.document.write(`
        <html>
            <head>
                <title>Cetak Kartu Siswa</title>
                <style>
                    /* ===== RESET & BASE ===== */
                    * { box-sizing: border-box; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                    body { 
                        margin: 0; padding: 20px; 
                        background: white !important; 
                        display: flex; justify-content: center; align-items: flex-start;
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    }

                    /* ===== CARD STRUCTURE (FIXED SIZE) ===== */
                    .id-card-modal { 
                        width: 350px !important; 
                        height: 500px !important; 
                        background: #f0f7ff !important; 
                        border-radius: 15px !important; 
                        position: relative !important; 
                        overflow: hidden !important; 
                        border: 1px solid #ddd !important;
                        display: block !important;
                        margin: 0 auto !important;
                    }

                    /* ===== HEADER ===== */
                    .card-header-kartu { 
                        background: #7791e9 !important; 
                        color: white !important; 
                        padding: 15px !important; 
                        display: flex !important; 
                        align-items: center !important; 
                        justify-content: center !important; 
                        gap: 12px !important; 
                        border-bottom: 4px solid #0056b3 !important;
                        height: 80px !important;
                    }
                    .card-header-kartu img { width: 45px !important; height: auto !important; }
                    .header-text-kartu { text-align: left !important; }
                    .header-text-kartu h4 { 
                        margin: 0 !important; font-weight: 800 !important; font-size: 14px !important; 
                        text-transform: uppercase !important; color: #03376e !important; 
                    }
                    .header-text-kartu small { font-size: 10px !important; font-weight: 600 !important; color: #fff !important; display: block !important; }

                    /* ===== BODY ===== */
                    .card-body-kartu { padding: 20px !important; text-align: center !important; }
                    .photo-container-kartu { 
                        width: 130px !important; height: 160px !important; 
                        margin: 0 auto 15px !important; background: #fff !important; 
                        border-radius: 8px !important; overflow: hidden !important; 
                        border: 2px solid #eee !important; display: flex !important; 
                        align-items: center !important; justify-content: center !important; 
                    }
                    .photo-container-kartu img { width: 100% !important; height: 100% !important; object-fit: cover !important; }
                    .student-name-kartu { 
                        font-size: 18px !important; font-weight: 800 !important; 
                        color: #333 !important; margin-bottom: 10px !important; 
                        text-transform: uppercase !important; line-height: 1.2 !important;
                    }

                    /* ===== QR & NISN ===== */
                    .qr-section-kartu { 
                        margin: 10px 0 !important; padding: 8px !important; 
                        background: #fff !important; display: inline-block !important; 
                        border-radius: 8px !important; border: 1px solid #f0f0f0 !important; 
                    }
                    .student-nisn-kartu { font-size: 12px !important; font-weight: 600 !important; color: #555 !important; }

                    /* ===== FOOTER ===== */
                    .footer-text-kartu { 
                        position: absolute !important; bottom: 0 !important; left: 0 !important; 
                        right: 0 !important; background: #ffffff !important; padding: 12px !important; 
                        font-size: 10px !important; color: #777 !important; text-align: center !important; 
                    }
                    .footer-line-kartu { width: 80% !important; height: 1px !important; background: #b5d5f8 !important; margin: 0 auto 8px !important; }

                    @page { size: auto; margin: 0mm; }
                </style>
            </head>
            <body>${content}</body>
        </html>
    `);
    win.document.close();
    setTimeout(() => {
        win.print();
        win.close();
    }, 500);
}

function downloadCard(id, name) {
    const card = document.getElementById(id);
    html2canvas(card, {
        scale: 3,
        useCORS: true,
        backgroundColor: null,
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = `Kartu_Siswa_${name}.png`;
        link.href = canvas.toDataURL('image/png');
        link.click();
    });
}

$(document).ready(function () {

    // ================= INIT SELECT2 (BS4 Compatible) =================
    function initSelect2(parentModal = null) {
        // Cek apakah select2 sudah terload
        if (typeof $.fn.select2 === 'undefined') {
            console.error('Select2 is not loaded!');
            return;
        }

        let config = {
            width: '100%',
            containerCssClass: ':all:' // Memastikan select2 mengikuti lebar container
        };
        
        if (parentModal) {
            config.dropdownParent = $(parentModal);
        }

        $('.select2, .select2-edit').select2(config);
    }

    // Init Select2 saat halaman siap
    initSelect2();

    // Re-init Select2 saat modal dibuka
    $(document).on('shown.bs.modal', '.modal', function () {
        initSelect2('#' + $(this).attr('id'));
    });

    // ================= TAMBAH ORTU (Trigger dari Select2) =================
    $(document).on('select2:select', '#orang_tua_id, .select2-edit', function (e) {
        if (e.params.data.id === 'new') {
            let modalAwal = $(this).closest('.modal');
            let modalAwalId = modalAwal.attr('id');

            // Reset value select2 biar tidak nyangkut di "new"
            $(this).val(null).trigger('change');

            // Tutup modal siswa
            modalAwal.modal('hide');

            // Simpan ID modal asal agar bisa balik lagi nanti
            $('#tambahOrangTuaModal').data('trigger-modal', modalAwalId);

            // Buka modal tambah orang tua
            setTimeout(() => {
                $('#tambahOrangTuaModal').modal('show');
            }, 400);
        }
    });

    // ================= EDIT ORTU (Tombol Edit) =================
    $(document).on('click', '.btn-edit-ortu', function () {
        let modalAwal = $(this).closest('.modal');
        let select = modalAwal.find('select[name="orang_tua_id"]');
        let id = select.val();

        if (!id || id === 'new') {
            alert('Pilih orang tua yang ingin diedit terlebih dahulu.');
            return;
        }

        // Ambil data via AJAX dulu
        $.get('/admin/orang_tua/' + id + '/edit', function (data) {
            if (data.error) {
                alert(data.message);
                return;
            }
            $('#edit_nama_ayah').val(data.nama_ayah);
            $('#edit_pekerjaan_ayah').val(data.pekerjaan_ayah);
            $('#edit_nama_ibu').val(data.nama_ibu);
            $('#edit_pekerjaan_ibu').val(data.pekerjaan_ibu);
            $('#edit_no_hp').val(data.no_hp);
            $('#edit_alamat_ortu').val(data.alamat);

            $('#formEditOrangTua').attr('action', '/admin/orang_tua/' + id);
            $('#editOrangTuaModal').data('trigger-modal', modalAwal.attr('id'));

            // Tutup modal siswa
            modalAwal.modal('hide');

            // Buka modal edit orang tua
            setTimeout(() => {
                $('#editOrangTuaModal').modal('show');
            }, 400);
        }).fail(function (xhr) {
            let errorMessage = 'Gagal mengambil data orang tua.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            alert(errorMessage);
        });
    });

    // ================= TOMBOL BATAL (Balik ke Modal Siswa) =================
    $(document).on('click', '#btnBatalOrangTua, #btnBatalEditOrangTua', function () {
        let modalSekarang = $(this).closest('.modal');
        let modalAsalId = modalSekarang.data('trigger-modal');

        modalSekarang.modal('hide');

        if (modalAsalId) {
            setTimeout(() => {
                $('#' + modalAsalId).modal('show');
            }, 400);
        }
    });

    // ================= SIMPAN ORTU VIA AJAX (BS4) =================
    $('#formTambahOrangTua, #formEditOrangTua').on('submit', function (e) {
        e.preventDefault();
        let form = $(this);
        let modalSekarang = form.closest('.modal');
        let btnSubmit = form.find('button[type="submit"]');

        btnSubmit.prop('disabled', true).text('Menyimpan...');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function (res) {
                let text = 'Ayah: ' + res.nama_ayah + ' | Ibu: ' + res.nama_ibu;

                if (form.attr('id') === 'formTambahOrangTua') {
                    // Tambah option baru ke semua select orang tua
                    let newOption = new Option(text, res.id, true, true);
                    $('select[name="orang_tua_id"]').append(newOption).trigger('change');
                } else {
                    // Update text option yang sudah ada
                    $('select[name="orang_tua_id"] option[value="' + res.id + '"]').text(text);
                    $('select[name="orang_tua_id"]').trigger('change');
                }

                modalSekarang.modal('hide');

                let modalAsalId = modalSekarang.data('trigger-modal');
                if (modalAsalId) {
                    setTimeout(() => {
                        $('#' + modalAsalId).modal('show');
                        // Re-initialize select2 in the original modal
                        initSelect2('#' + modalAsalId);
                    }, 400);
                }

                if (form.attr('id') === 'formTambahOrangTua') {
                    form[0].reset();
                }
                
                // Use SweetAlert2 instead of alert()
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Data orang tua berhasil disimpan!',
                    timer: 2000,
                    showConfirmButton: false
                });
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;
                let html = '<ul style="text-align: left;">';
                if (errors) {
                    $.each(errors, function (k, v) {
                        html += '<li>' + v[0] + '</li>';
                    });
                } else {
                    html += '<li>' + xhr.statusText + '</li>';
                }
                html += '</ul>';
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal menyimpan!',
                    html: html
                });
            },
            complete: function() {
                btnSubmit.prop('disabled', false).text(form.attr('id') === 'formTambahOrangTua' ? 'Simpan Orang Tua' : 'Update Orang Tua');
            }
        });
    });

    // Hapus duplikat event handler di bawah jika ada
    // ================= DELETE SISWA (SWEETALERT2) =================
    $(document).on('click', '.btn-delete-siswa button', function (e) {
        e.preventDefault();
        let form = $(this).closest('form');
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data siswa yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Gunakan AJAX untuk delete agar bisa menangkap response
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data siswa berhasil dihapus!',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // Reload halaman setelah sukses
                        });
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat menghapus data.',
                        });
                    }
                });
            }
        });
    });

    // ================= NOTIFIKASI SUKSES/GAGAL (SWEETALERT2) =================
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}",
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Kesalahan Validasi',
            html: `
                <ul style="text-align: left;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            `,
        });
    @endif

});
</script>

@endpush

@endsection