@extends('layouts.adminlte')

@section('content')
<style>
.content {
    background: #ffffff !important;
    min-height: 100vh;
    padding: 20px 0;
    overflow: visible !important;
}

/* CARD STYLE KAYA GURU */
.card-custom {
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 6px 16px rgba(0,0,0,0.15);
}

/* HEADER BIRU */
.card-header-custom {
    background: #17a2b8; /* Tosca/Cyan like screenshot */
    color: white;
    padding: 12px 20px;
    font-weight: 600;
}

/* TOMBOL BULAT */
.btn-circle {
    width: 45px;
    height: 45px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
}

.table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    color: #333;
    font-weight: bold;
    font-size: 14px;
    padding: 15px 10px;
}
/* AKSI BUTTON */
.btn-action {
    border-radius: 10px;
    padding: 6px 10px;
}

.btn-primary {
    background: linear-gradient(90deg, rgb(233, 103, 205), #6f42c1);
    border: none;
}
</style>

<section class="content">
<div class="container-fluid">

<!-- TOP BAR -->
<div class="d-flex justify-content-between align-items-center mb-3">

    <!-- tombol tambah -->
    <button class="btn btn-primary btn-circle shadow"
        data-toggle="modal"
        data-target="#tambahKelasModal">
        +
    </button>

    <!-- search -->
    <form action="{{ route('admin.kelas.index') }}" method="GET" class="d-flex">
        <input type="text" name="search" class="form-control me-2"
            placeholder="Cari kelas..." value="{{ request('search') }}">
        <button class="btn btn-outline-primary">Cari</button>
    </form>
</div>

<!-- CARD -->
<div class="card card-custom">
<div class="card-header card-header-custom">
    Daftar Kelas ({{ $kelas->count() }})
</div>

<div class="card-body table-responsive">
<table class="table table-bordered table-hover align-middle text-center">

<thead>
<tr>
    <th>No</th>
    <th>Nama Kelas</th>
    <th>Guru Kelas</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
@forelse($kelas as $k)
<tr>
    <td>{{ $loop->iteration }}</td>
    <td><b>{{ $k->nama_kelas }}</b></td>
    <td>
        {{ $k->guru->nama_guru ?? '-' }}
        @if(isset($k->guru) && ($k->guru->status ?? 'Aktif') !== 'Aktif')
            <br>
            <span class="badge badge-danger" style="font-size: 10px;">
                <i class="fas fa-exclamation-triangle"></i> Guru Nonaktif
            </span>
        @endif
    </td>
    <td>
        <!-- Detail -->
        <a href="{{ route('admin.kelas.show', $k->id) }}" class="btn btn-success btn-action" title="Detail">
            <i class="fas fa-eye"></i>
        </a>
        <!-- EDIT -->
        <button class="btn btn-info btn-action"
            data-toggle="modal"
            data-target="#editKelasModal{{ $k->id }}">
            <i class="fas fa-pen"></i>
        </button>

        <form action="{{ route('admin.kelas.destroy', $k->id) }}"
      method="POST"
      class="d-inline btn-delete-kelas">
    @csrf
    @method('DELETE')
    <button type="button" class="btn btn-danger btn-action">
        <i class="fas fa-trash"></i>
    </button>
</form>

    </td>
</tr>

<!-- MODAL EDIT -->
<div class="modal fade" id="editKelasModal{{ $k->id }}">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header bg-info text-white">
    <h5>Edit Kelas</h5>
    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
</div>

<form action="{{ route('admin.kelas.update', $k->id) }}" method="POST">
@csrf
@method('PUT')

<div class="modal-body">
    <div class="mb-3">
        <label>Nama Kelas</label>
        <input type="text" name="nama_kelas"
            value="{{ $k->nama_kelas }}" class="form-control">
    </div>

    <div class="mb-3">
        <label>Guru Kelas</label>
        <select name="guru_id" class="form-control guru-select" required>
            <option value="">-- Pilih Guru --</option>
            @foreach($gurus as $g)
                <option value="{{ $g->id }}" 
                    data-status="{{ $g->status }}"
                    {{ $k->guru_id == $g->id ? 'selected' : '' }}>
                    {{ $g->nama_guru }} {{ ($g->status ?? 'Aktif') !== 'Aktif' ? '(Nonaktif)' : '' }}
                </option>
            @endforeach
        </select>
        <div class="guru-warning mt-1 text-danger small d-none">
            <i class="fas fa-exclamation-circle"></i> Peringatan: Guru yang dipilih berstatus Nonaktif!
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary"
        data-dismiss="modal">Batal</button>
    <button class="btn btn-warning">Update</button>
</div>

</form>
</div>
</div>
</div>

@empty
<tr>
    <td colspan="4">Belum ada data</td>
</tr>
@endforelse
</tbody>

</table>
</div>
</div>

</div>
</section>

<!-- ================= TAMBAH ================= -->
<div class="modal fade" id="tambahKelasModal">
<div class="modal-dialog">
<div class="modal-content">

<div class="modal-header bg-info text-white">
    <h5>Tambah Kelas</h5>
    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
</div>

<form action="{{ route('admin.kelas.store') }}" method="POST">
@csrf

<div class="modal-body">
    <div class="mb-3">
        <label>Nama Kelas</label>
        <input type="text" name="nama_kelas" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Guru Kelas</label>
        <select name="guru_id" class="form-control guru-select" required>
            <option value="">-- Pilih Guru --</option>
            @foreach($gurus as $g)
                <option value="{{ $g->id }}" data-status="{{ $g->status }}">
                    {{ $g->nama_guru }} {{ ($g->status ?? 'Aktif') !== 'Aktif' ? '(Nonaktif)' : '' }}
                </option>
            @endforeach
        </select>
        <div class="guru-warning mt-1 text-danger small d-none">
            <i class="fas fa-exclamation-circle"></i> Peringatan: Guru yang dipilih berstatus Nonaktif!
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary"
        data-dismiss="modal">Batal</button>
    <button class="btn btn-success">Simpan</button>
</div>

</form>
</div>
</div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {

    // Use event delegation for dynamic elements
    $(document).on('click', '.btn-delete-kelas button', function(e) {
        e.preventDefault();
        let form = $(this).closest('form');

        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data kelas yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Data kelas berhasil dihapus!',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    },
                    error: function(xhr) {
                        console.error(xhr);
                        Swal.fire('Gagal!', 'Terjadi kesalahan: ' + (xhr.responseText || xhr.statusText), 'error');
                    }
                });
            }
        });
    });

    // ================= GURU NONAKTIF WARNING =================
    function checkGuruStatus(selectElement) {
        let selectedOption = $(selectElement).find('option:selected');
        let status = selectedOption.data('status');
        let warningDiv = $(selectElement).siblings('.guru-warning');

        if (status && status !== 'Aktif') {
            warningDiv.removeClass('d-none');
        } else {
            warningDiv.addClass('d-none');
        }
    }

    // Trigger on change
    $(document).on('change', '.guru-select', function() {
        checkGuruStatus(this);
    });

    // Trigger on modal shown (for Edit modal)
    $(document).on('shown.bs.modal', '.modal', function () {
        $(this).find('.guru-select').each(function() {
            checkGuruStatus(this);
        });
    });

});
</script>
@endpush