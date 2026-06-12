@extends('layouts.adminlte')

@section('content')

<style>
.content {
    background: #fff;
    min-height: 100vh;
    padding: 20px;
}

.card {
    border-radius: 14px;
    border: none;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}

.table thead {
    background: linear-gradient(90deg, #28a745, #1e7e34);
    color: #fff;
}

.btn-primary {
    background: linear-gradient(90deg, #e967cd, #6f42c1);
    border: none;
}

.modal-header {
    background: linear-gradient(90deg, #28a745, #1e7e34);
    color: #fff;
}

.btn {
    border-radius: 8px;
}
</style>

<section class="content">
<div class="container-fluid">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4><i class="fas fa-balance-scale"></i> Kelola Skala Nilai</h4>

    <div>
        <a href="{{ route('admin.penilaian.index') }}" class="btn btn-secondary me-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <button class="btn btn-primary" data-toggle="modal" data-target="#tambahSkala">
            <i class="fas fa-plus"></i>
        </button>
    </div>
</div>

<!-- TABLE -->
<div class="card">
<div class="card-body table-responsive">

<table class="table table-bordered table-hover text-center align-middle">
<thead>
<tr>
    <th>No</th>
    <th>Keterangan</th>
    <th>Nilai</th>
    <th width="150">Aksi</th>
</tr>
</thead>

<tbody>
@forelse($skalas as $s)
<tr>
    <td>{{ $loop->iteration }}</td>

    <td class="text-start">
        <strong>{{ $s->keterangan }}</strong>
    </td>

    <td>
        <span class="badge bg-primary" style="font-size: 1rem;">
            {{ $s->nilai }}
        </span>
    </td>

    <td>
        <!-- EDIT -->
        <button class="btn btn-sm btn-warning"
            data-toggle="modal"
            data-target="#editSkala{{ $s->id }}">
            <i class="fas fa-edit"></i>
        </button>

        <!-- DELETE -->
        <form action="{{ route('admin.skala.destroy', $s->id) }}"
              method="POST"
              class="d-inline form-delete">
            @csrf
            @method('DELETE')

            <button type="button" class="btn btn-sm btn-danger btn-delete">
                <i class="fas fa-trash"></i>
            </button>
        </form>
    </td>
</tr>
@empty
<tr>
    <td colspan="4" class="text-muted">Belum ada data</td>
</tr>
@endforelse
</tbody>

</table>
</div>
</div>

</div>
</section>

<!-- ================= MODAL TAMBAH ================= -->
<div class="modal fade" id="tambahSkala" tabindex="-1">
<div class="modal-dialog">
<form action="{{ route('admin.skala.store') }}" method="POST" class="modal-content">
@csrf

<div class="modal-header">
    <h5>Tambah Skala</h5>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">

<div class="mb-3">
<label>Keterangan</label>
<input type="text" name="keterangan" class="form-control"
placeholder="Contoh: Berkembang Sangat Baik" required>
</div>

<div class="mb-3">
<label>Nilai</label>
<input type="number" name="nilai" class="form-control"
placeholder="Contoh: 90" required>
</div>

</div>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
<button type="submit" class="btn btn-primary btn-submit">
    Simpan
</button>
</div>

</form>
</div>
</div>

<!-- ================= MODAL EDIT ================= -->
@foreach($skalas as $s)
<div class="modal fade" id="editSkala{{ $s->id }}" tabindex="-1">
<div class="modal-dialog">

<form action="{{ route('admin.skala.update', $s->id) }}" method="POST" class="modal-content">
@csrf
@method('PUT')

<div class="modal-header">
    <h5>Edit Skala</h5>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">

<div class="mb-3">
<label>Keterangan</label>
<input type="text" name="keterangan"
value="{{ $s->keterangan }}" class="form-control" required>
</div>

<div class="mb-3">
<label>Nilai</label>
<input type="number" name="nilai"
value="{{ $s->nilai }}" class="form-control" required>
</div>

</div>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
<button type="submit" class="btn btn-warning btn-submit">
    Update
</button>
</div>

</form>

</div>
</div>
@endforeach


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ================= DELETE =================
    document.querySelectorAll('.btn-delete').forEach(button => {

        button.addEventListener('click', function (e) {

            e.preventDefault();

            let form = this.closest('form');

            Swal.fire({
                title: 'Yakin hapus data?',
                text: 'Data tidak bisa dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Hapus',
                cancelButtonText: 'Batal'
            }).then((result) => {

                if (result.isConfirmed) {

                    this.disabled = true;

                    this.innerHTML =
                        '<i class="fas fa-spinner fa-spin"></i>';

                    form.submit();
                }

            });

        });

    });


    // ================= ANTI DOUBLE SUBMIT =================
    document.querySelectorAll('form').forEach(form => {

        form.addEventListener('submit', function () {

            let btn = this.querySelector('.btn-submit');

            if (btn) {

                btn.disabled = true;

                btn.innerHTML =
                    '<i class="fas fa-spinner fa-spin"></i> Loading...';

            }

        });

    });

});
</script>
@endsection