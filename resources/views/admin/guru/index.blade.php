@extends('layouts.adminlte')
@section('content')
<style>
/* ===== GLOBAL WRAPPER (ONLY SCROLL HERE) ===== */
html, body {
    height: 100%;
    overflow: auto;
}

@media (max-width: 991.98px) {
    .content-wrapper {
        margin-left: 0 !important;
    }
}

/* ===== CARD ===== */
.card-guru-container {
    background-color: rgba(255,255,255,0.95);
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    overflow: hidden;
    border: none;
}

.card-guru-header {
    background: #17a2b8; /* Tosca/Cyan like screenshot */
    color: white;
    padding: 12px 20px;
    font-weight: 600;
}

/* ===== BUTTON (PINK/PURPLE GRADIENT LIKE SISWA) ===== */

.btn-tambah-custom {
    background: linear-gradient(90deg, rgb(233, 103, 205), #6f42c1) !important;
    color: white !important;
    border: none !important;
    padding: 8px 20px !important;
    font-weight: 600 !important;
    border-radius: 8px !important;
    box-shadow: 0 4px 10px rgba(233, 103, 205, 0.3);
}

/* ===== TOOLBAR ===== */
.toolbar-guru {
    position: sticky;
    top: 60px;
    z-index: 1000;
    background: white;
    padding: 15px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

/* ===== TABLE STYLING ===== */
.table {
    border: 1px solid #dee2e6;
}

.table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    color: #333;
    font-weight: bold;
    font-size: 14px;
    padding: 15px 10px;
}

.table tbody td {
    padding: 15px 10px;
    vertical-align: middle;
}

/* ===== ACTION BUTTONS (CONSISTENT WITH SISWA) ===== */
.btn-action-pink {
    background: linear-gradient(90deg, rgb(233, 103, 205), #6f42c1) !important;
    color: white !important;
    border-radius: 6px !important;
    padding: 6px 12px !important;
    border: none !important;
}

.btn-action-tosca {
    background: #17a2b8 !important;
    color: white !important;
    border-radius: 6px !important;
    padding: 6px 12px !important;
    border: none !important;
}

.btn-action-red {
    background: #dc3545 !important;
    color: white !important;
    border-radius: 6px !important;
    padding: 6px 12px !important;
    border: none !important;
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

.id-card-modal .card-header-kartu img { width: 40px; height: auto; }
.id-card-modal .header-text-kartu { text-align: left; }
.id-card-modal .header-text-kartu h4 { margin: 0; font-weight: 800; font-size: 13px; text-transform: uppercase; color: #03376eff; }
.id-card-modal .header-text-kartu small { font-size: 9px; font-weight: 600; color: #fff; display: block; }
.id-card-modal .card-body-kartu { padding: 15px; text-align: center; }
.id-card-modal .photo-container-kartu { width: 120px; height: 150px; margin: 0 auto 10px; background: #fff; border-radius: 8px; overflow: hidden; border: 2px solid #eee; display: flex; align-items: center; justify-content: center; }
.id-card-modal .photo-container-kartu img { max-width: 100%; max-height: 100%; object-fit: cover; }
.id-card-modal .student-name-kartu { font-size: 16px; font-weight: 800; color: #333; margin-bottom: 8px; text-transform: uppercase; }
.id-card-modal .qr-section-kartu { margin: 5px 0; padding: 6px; background: #fff; display: inline-block; border-radius: 8px; border: 1px solid #f0f0f0; }
.id-card-modal .student-nisn-kartu { font-size: 11px; font-weight: 600; color: #555; }
.id-card-modal .footer-text-kartu { position: absolute; bottom: 0; left: 0; right: 0; background: #ffffff; padding: 10px; font-size: 9px; color: #777; text-align: center; }
.id-card-modal .footer-line-kartu { width: 80%; height: 1px; background: #b5d5f8ff; margin: 0 auto 6px; }

@media print {
    .modal-header, .modal-footer, .nav-buttons { display: none !important; }
    .modal-body { padding: 0 !important; }
    .id-card-modal { box-shadow: none !important; border: 1px solid #000 !important; -webkit-print-color-adjust: exact; }
}
</style>

<div class="content mt-3">
    <div class="container-fluid">

        <!-- TOOLBAR (SESUAI SISWA) -->
        <div class="toolbar-guru d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <button class="btn btn-tambah-custom" data-toggle="modal" data-target="#tambahGuruModal" title="Tambah Guru">
                <i class="fas fa-plus"></i>
            </button>
            
            <form action="{{ route('admin.guru.index') }}" method="GET" class="d-flex">
                <input type="text" name="search" class="form-control form-control-sm me-2"
                       placeholder="Cari guru..." value="{{ request('search') }}" 
                       style="width: 180px;">
                <button class="btn btn-outline-primary btn-sm">Cari</button>
            </form>
        </div>

        <!-- TABLE CARD -->
        <div class="card-guru-container">
            <div class="card-guru-header">
                Daftar Guru ({{ $gurus->count() }})
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 text-center align-middle">
                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th class="text-start">Nama Guru</th>
                                <th>NIP</th>
                                <th>Jabatan</th>
                                <th>Status</th>
                                <th>Foto</th>
                                <th width="180">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gurus as $g)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-start font-weight-bold text-dark">{{ $g->nama_guru }}</td>
                                    <td>{{ $g->nip ?? '-' }}</td>
                                    <td><span class="badge badge-info">{{ $g->jabatan }}</span></td>
                                    <td>
                                        <span class="badge {{ ($g->status ?? 'Aktif') == 'Aktif' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $g->status ?? 'Aktif' }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        @php
                                            $isDefaultPhoto = in_array($g->foto_guru, ['', null, 'images/default-teacher.svg', 'default-teacher.svg', 'images/default.png', 'default.png']);
                                        @endphp
                                        @if(!$isDefaultPhoto)
                                            <img src="{{ asset('storage/' . $g->foto_guru) }}" 
                                                 alt="{{ $g->nama_guru }}" width="40" height="40" class="rounded-circle border border-light shadow-sm object-cover">
                                        @else
                                            <div class="rounded-circle border border-light shadow-sm d-inline-flex align-items-center justify-content-center bg-light"
                                                 style="width:40px; height:40px;">
                                                <i class="fas fa-chalkboard-teacher text-muted" style="font-size:18px;"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-row align-items-center justify-content-center gap-1">
                                            <!-- Detail -->
                                            <a href="{{ route('admin.guru.show', $g->id) }}" class="btn btn-action-pink btn-sm" title="Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <!-- Cetak Kartu -->
                                            <button class="btn btn-action-tosca btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#kartuGuruModal{{ $g->id }}"
                                                title="Cetak Kartu">
                                                <i class="fas fa-print"></i>
                                            </button>
                                            <!-- Edit -->
                                            <button class="btn btn-sm btn-primary" 
                                                data-toggle="modal" 
                                                data-target="#editGuruModal{{ $g->id }}"
                                                title="Edit Data">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <!-- Hapus -->
                                            <button type="button" class="btn btn-action-red btn-sm" 
                                                onclick="confirmDelete('{{ $g->id }}', 'delete-form-{{ $g->id }}')"
                                                title="Hapus Data">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $g->id }}" action="{{ route('admin.guru.destroy', $g->id) }}" method="POST" style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-5 text-muted">Belum ada data guru.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Kartu Guru -->
@foreach($gurus as $g)
<div class="modal fade" id="kartuGuruModal{{ $g->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content" style="background: transparent; border: none;">
            <div class="modal-header border-0 p-0 mb-3 justify-content-center gap-2">
                <button type="button" class="btn btn-secondary shadow-sm" data-dismiss="modal">
                    <i class="fas fa-times"></i> Tutup
                </button>
                <button type="button" class="btn btn-primary shadow-sm" onclick="printCard('card-guru-{{ $g->id }}')">
                    <i class="fas fa-print"></i> Cetak
                </button>
                <button type="button" class="btn btn-success shadow-sm" onclick="downloadCard('card-guru-{{ $g->id }}', '{{ Str::slug($g->nama_guru) }}')">
                    <i class="fas fa-download"></i> PNG
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="id-card-modal" id="card-guru-{{ $g->id }}">
                    <div class="card-header-kartu">
                        <img src="{{ asset('images/logo paud rmv.png') }}" alt="Logo">
                        <div class="header-text-kartu">
                            <h4>Kartu Identitas Guru</h4>
                            <small>POS PAUD TERATAI SINDANGSARI</small>
                        </div>
                    </div>
                    <div class="card-body-kartu">
                        <div class="photo-container-kartu">
                            @php
                                $isDefaultPhoto = in_array($g->foto_guru, ['', null, 'images/default-teacher.svg', 'default-teacher.svg', 'images/default.png', 'default.png']);
                            @endphp
                            @if(!$isDefaultPhoto)
                                <img src="{{ asset('storage/' . $g->foto_guru) }}" alt="{{ $g->nama_guru }}" class="w-full h-full object-cover">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light" style="width:100%; height:100%;">
                                    <i class="fas fa-chalkboard-teacher text-muted" style="font-size:60px;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="student-name-kartu">{{ $g->nama_guru }}</div>
                        <div class="qr-section-kartu">
                            @if($g->barcode)
                                {!! QrCode::size(80)->generate($g->barcode) !!}
                                <div class="mt-2" style="font-size: 9px; font-weight: bold;">
                                    {{ $g->nip ? 'NIP' : 'Kode' }}: {{ $g->barcode }}
                                </div>
                            @else
                                <small class="text-danger" style="font-size: 8px;">QR N/A</small>
                            @endif
                        </div>
                        <div class="student-nisn-kartu">NIP: {{ $g->nip ?? '-' }}</div>
                        <div class="badge badge-light border mt-2">{{ $g->jabatan }}</div>
                    </div>
                    <div class="footer-text-kartu">
                        <div class="footer-line-kartu"></div>
                        Kartu ini merupakan tanda pengenal resmi guru<br>
                        <b>POS PAUD TERATAI SINDANGSARI</b>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Tambah Guru -->
<div class="modal fade" id="tambahGuruModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold">Tambah Guru Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.guru.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">NIP</label>
                            <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" placeholder="Isi '-' jika tidak ada">
                            @error('nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">NIK</label>
                            <input type="text" name="nik" class="form-control @error('nik') is-invalid @enderror" placeholder="Isi NIK">
                            @error('nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Nama Guru</label>
                            <input type="text" name="nama_guru" class="form-control @error('nama_guru') is-invalid @enderror" required>
                            @error('nama_guru')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Tanggal Lahir</label>
                            <input type="date" name="ttl" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Jenis Kelamin</label>
                            <select name="jk" class="form-control" required>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Jabatan</label>
                            <select name="jabatan" class="form-control" required>
                                <option value="Guru Pendamping">Guru Pendamping</option>
                                <option value="Kepala Sekolah">Kepala Sekolah</option>
                                <option value="Guru Kelas">Guru Kelas</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">No HP</label>
                            <input type="text" name="no_hp" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Foto</label>
                            <input type="file" name="foto_guru" class="form-control @error('foto_guru') is-invalid @enderror">
                            @error('foto_guru')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal 5MB (JPG/PNG)</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="Aktif">Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="fw-bold">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Guru -->
@foreach($gurus as $g)
<div class="modal fade" id="editGuruModal{{ $g->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title font-weight-bold">Edit Guru: {{ $g->nama_guru }}</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.guru.update', $g->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">NIP</label>
                            <input type="text" name="nip" value="{{ $g->nip }}" class="form-control @error('nip') is-invalid @enderror">
                            @error('nip')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">NIK</label>
                            <input type="text" name="nik" value="{{ $g->nik }}" class="form-control @error('nik') is-invalid @enderror">
                            @error('nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Nama Guru</label>
                            <input type="text" name="nama_guru" value="{{ $g->nama_guru }}" class="form-control @error('nama_guru') is-invalid @enderror">
                            @error('nama_guru')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Tanggal Lahir</label>
                            <input type="date" name="ttl" value="{{ $g->ttl }}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Jenis Kelamin</label>
                            <select name="jk" class="form-control">
                                <option value="L" {{ $g->jk == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ $g->jk == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Jabatan</label>
                            <select name="jabatan" class="form-control">
                                <option value="Guru Pendamping" {{ $g->jabatan == 'Guru Pendamping' || $g->jabatan == 'Guru' ? 'selected' : '' }}>Guru Pendamping</option>
                                <option value="Kepala Sekolah" {{ $g->jabatan == 'Kepala Sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                                <option value="Guru Kelas" {{ $g->jabatan == 'Guru Kelas' ? 'selected' : '' }}>Guru Kelas</option>
                                <option value="Admin" {{ $g->jabatan == 'Admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">No HP</label>
                            <input type="text" name="no_hp" value="{{ $g->no_hp }}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Email</label>
                            <input type="email" name="email" value="{{ $g->email }}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Barcode</label>
                            <input type="text" name="barcode" value="{{ $g->barcode }}" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="Aktif" {{ $g->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Nonaktif" {{ $g->status == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="fw-bold">Foto (Kosongkan jika tidak diubah)</label>
                            <input type="file" name="foto_guru" class="form-control @error('foto_guru') is-invalid @enderror">
                            @error('foto_guru')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal 5MB (JPG/PNG)</small>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="fw-bold">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2">{{ $g->alamat }}</textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light px-0 pb-0">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
// Fungsi global untuk kartu
function printCard(id) {
    const content = document.getElementById(id).outerHTML;
    const win = window.open('', '_blank');
    win.document.write(`
        <html>
            <head>
                <title>Cetak Kartu Guru</title>
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
                        font-size: 18px !important; font-weight: 800 !important; color: #333 !important; 
                        margin-bottom: 10px !important; text-transform: uppercase !important; 
                    }
                    .qr-section-kartu { 
                        margin: 10px 0 !important; padding: 8px !important; 
                        background: #fff !important; display: inline-block !important; 
                        border-radius: 8px !important; border: 1px solid #f0f0f0 !important; 
                    }
                    .student-nisn-kartu { font-size: 12px !important; font-weight: 600 !important; color: #555 !important; }
                    
                    /* ===== FOOTER ===== */
                    .footer-text-kartu { 
                        position: absolute !important; bottom: 0 !important; left: 0 !important; right: 0 !important; 
                        background: #ffffff !important; padding: 12px !important; font-size: 10px !important; 
                        color: #777 !important; text-align: center !important; 
                    }
                    .footer-line-kartu { 
                        width: 80% !important; height: 1px !important; background: #b5d5f8 !important; 
                        margin: 0 auto 8px !important; 
                    }

                    @page { size: auto; margin: 0mm; }
                </style>
            </head>
            <body>${content}</body>
        </html>
    `);
    win.document.close();
    setTimeout(() => { win.print(); win.close(); }, 500);
}

function downloadCard(id, name) {
    const card = document.getElementById(id);
    html2canvas(card, { scale: 3, useCORS: true, backgroundColor: null }).then(canvas => {
        const link = document.createElement('a');
        link.download = `Kartu_Guru_${name}.png`;
        link.href = canvas.toDataURL('image/png');
        link.click();
    });
}

function confirmDelete(id, formId) {
    Swal.fire({
        title: 'Hapus Data Guru?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById(formId).submit();
        }
    });
}

$(document).ready(function() {
    // ================= NOTIFIKASI SWEETALERT2 =================
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", timer: 2000, showConfirmButton: false });
    @endif
});
</script>
@endpush
@endsection