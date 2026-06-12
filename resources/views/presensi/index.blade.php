@extends('layouts.adminlte')

@section('content')

<style>
.content {
    background: #f4f6f9;
    min-height: 100vh;
    padding: 20px;
}
.card {
    border-radius: 12px;
    border: none;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.btn-primary {
    background: linear-gradient(90deg, rgb(233,103,205), #6f42c1);
    border: none;
}
.filter-btn {
    border: 1px solid #4e73df;
    color: #4e73df;
}
.filter-btn.active,
.filter-btn:hover {
    background: #4e73df;
    color: white;
}
.badge-hadir { background:#28a745; }
.badge-sakit { background:#ffc107; color:#000; }
.badge-izin { background:#17a2b8; }
.badge-alpha { background:#dc3545; }

.btn-light {
    background: #f8f9fa;
    border: 1px solid #dcdcdc !important;
    color: #555;
    transition: 0.2s;
}

.btn-light:hover {
    background: #e9ecef;
    color: #222;
}

.gap-2 {
    gap: 10px !important;
}
</style>

<section class="content">
<div class="container-fluid">

<!-- FILTER -->
<div class="mb-3">
    <a href="{{ route('admin.presensi.index',['tipe'=>'guru']) }}"
       class="btn filter-btn {{ $tipe=='guru'?'active':'' }}">Guru</a>

    <a href="{{ route('admin.presensi.index',['tipe'=>'siswa']) }}"
       class="btn filter-btn {{ $tipe=='siswa'?'active':'' }}">Siswa</a>

    <a href="{{ route('admin.presensi.rekap',['tipe'=>$tipe]) }}"
       class="btn btn-success">Rekap</a>
</div>

<!-- FILTER -->
<div class="card p-3 mb-3">
<form action="{{ route('admin.presensi.index') }}" method="GET" class="d-flex gap-2">
    <input type="hidden" name="tipe" value="{{ $tipe }}">
    <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}">

    @if($tipe=='siswa')
    <select name="kelas_id" class="form-control">
        <option value="">Semua Kelas</option>
        @foreach($kelas as $k)
        <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
        @endforeach
    </select>
    @endif

    <div class="d-flex gap-2 ms-auto">

    <button type="submit" class="btn btn-light border px-4">
        <i class="fas fa-filter text-secondary"></i>
        <span class="text-secondary">Filter</span>
    </button>

</div>
</form>
</div>

<!-- TABLE -->
<div class="card">
<div class="card-header d-flex justify-content-between">
    <b>Presensi {{ ucfirst($tipe) }}</b>

    @if($isHariLibur)
        <span class="badge bg-warning text-dark">
            <i class="fas fa-calendar-times"></i> Hari Libur
        </span>
    @else
        <div>
            <span class="badge bg-success">H: {{ $rekap['hadir'] ?? 0 }}</span>
            <span class="badge bg-warning text-dark">S: {{ $rekap['sakit'] ?? 0 }}</span>
            <span class="badge bg-info">I: {{ $rekap['izin'] ?? 0 }}</span>
            <span class="badge bg-danger">A: {{ $rekap['alpha'] ?? 0 }}</span>
        </div>
    @endif
</div>

<div class="card-body">
@if($isHariLibur)
    <div class="text-center py-5">
        <i class="fas fa-calendar-times fa-5x text-warning mb-3"></i>
        <h4 class="text-warning">Hari Libur</h4>
        <p class="text-muted">Tidak ada presensi pada hari libur</p>
    </div>
@else
<table class="table table-bordered text-center">
<thead>
<tr>
    <th>No</th>
    <th>Nama</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
@forelse($presensi as $p)
<tr>
<td>{{ $loop->iteration }}</td>

<td>
{{ $tipe=='guru' ? $p->guru->nama_guru : $p->siswa->nama_siswa }}
</td>

<td>
<span class="badge 
@if(strtolower($p->status)=='hadir') badge-hadir
@elseif(strtolower($p->status)=='sakit') badge-sakit
@elseif(strtolower($p->status)=='izin') badge-izin
@else badge-alpha
@endif">
{{ ucfirst($p->status) }}
</span>
</td>

<td>
<button class="btn btn-warning btn-sm btn-edit"
    data-id="{{ $p->id }}"
    data-nama="{{ $tipe=='guru' ? $p->guru->nama_guru : $p->siswa->nama_siswa }}"
    data-tanggal="{{ $p->tanggal }}"
    data-status="{{ strtolower($p->status) }}">
    Edit
</button>

<button class="btn btn-danger btn-sm btn-delete"
    data-id="{{ $p->id }}">
    Hapus
</button>
</td>

</tr>
@empty
<tr>
<td colspan="4">Belum ada data</td>
</tr>
@endforelse
</tbody>
</table>
@endif
</div>
</div>

</div>
</section>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah">
<div class="modal-dialog">
<div class="modal-content">

<form action="{{ route('admin.presensi.store') }}" method="POST">
@csrf

<div class="modal-header">
<h5>Tambah Presensi</h5>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">

<input type="hidden" name="tipe_presensi" value="{{ $tipe }}">

@if($tipe=='siswa')
<select id="kelasSelect" class="form-control mb-2">
<option value="">Pilih Kelas</option>
@foreach($kelas as $k)
<option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
@endforeach
</select>

<select name="user_id" id="siswaSelect" class="form-control mb-2">
<option value="">Pilih Siswa</option>
@foreach($siswa as $s)
<option value="{{ $s->id }}" data-kelas="{{ $s->kelas_id }}">
{{ $s->nama_siswa }}
</option>
@endforeach
</select>
@else
<select name="user_id" class="form-control mb-2">
<option value="">Pilih Guru</option>
@foreach($guru as $g)
<option value="{{ $g->id }}">{{ $g->nama_guru }}</option>
@endforeach
</select>
@endif

<input type="date" name="tanggal" class="form-control mb-2">

<select name="status" class="form-control">
<option value="hadir">Hadir</option>
<option value="sakit">Sakit</option>
<option value="izin">Izin</option>
<option value="alpha">Alpha</option>
</select>

</div>

<div class="modal-footer">
<button class="btn btn-primary">Simpan</button>
</div>

</form>

</div>
</div>
</div>

<!-- MODAL EDIT GLOBAL -->
<div class="modal fade" id="modalEdit">
<div class="modal-dialog">
<div class="modal-content">

<form id="formEdit" method="POST">
@csrf
@method('PUT')

<div class="modal-header">
<h5>Edit Presensi</h5>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">
<input type="hidden" name="tipe_presensi" value="{{ $tipe }}">

<input type="text" id="editNama" class="form-control mb-2" readonly>

<input type="date" name="tanggal" id="editTanggal" class="form-control mb-2">

<select name="status" id="editStatus" class="form-control">
<option value="hadir">Hadir</option>
<option value="sakit">Sakit</option>
<option value="izin">Izin</option>
<option value="alpha">Alpha</option>
</select>
</div>

<div class="modal-footer">
<button class="btn btn-primary">Update</button>
</div>

</form>

</div>
</div>
</div>

@push('scripts')
<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {

    console.log('Presensi script loaded!');
    console.log('Button edit count:', $('.btn-edit').length);
    console.log('Button delete count:', $('.btn-delete').length);

    // EDIT
    $(document).on('click', '.btn-edit', function() {
        console.log('Edit button clicked! ID:', $(this).data('id'));
        
        $('#editNama').val($(this).data('nama'));
        $('#editTanggal').val($(this).data('tanggal'));
        $('#editStatus').val($(this).data('status'));
        $('#formEdit').attr('action', `/admin/presensi/${$(this).data('id')}`);
        $('#modalEdit').modal('show');
    });

    // DELETE
    $(document).on('click', '.btn-delete', function() {
        let id = $(this).data('id');
        let tipe = '{{ $tipe }}';

        console.log('Delete button clicked! ID:', id, 'Tipe:', tipe);

        Swal.fire({
            title: 'Yakin hapus?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya hapus'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/presensi/${id}?tipe=${tipe}`;

                form.innerHTML = `
                    @csrf
                    @method('DELETE')
                `;

                document.body.appendChild(form);
                form.submit();
            }
        });
    });

});
</script>
@endpush

@endsection