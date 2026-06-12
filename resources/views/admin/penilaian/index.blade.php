@extends('layouts.adminlte')

@section('content')

<style>
.content {
    background: #ffffffff;
    min-height: 100vh;
    padding: 20px;
}

/* CARD */
.card {
    border-radius: 14px;
    border: none;
    box-shadow: 0 6px 18px rgba(0,0,0,0.08);
}

/* HEADER */
.page-title {
    font-weight: 600;
}

/* BUTTON */
.btn-primary {
    background: linear-gradient(90deg, #e967cd, #6f42c1);
    border: none;
}

/* TABLE */
.table thead th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    color: #333;
    font-weight: bold;
    font-size: 14px;
    padding: 15px 10px;
}

/* BADGE */
.badge-kode {
    background: #6f42c1;
    font-size: 13px;
}

/* ALERT */
.total-valid { color: #28a745; }
.total-invalid { color: #dc3545; }

/* MODAL */
.modal-content {
    border-radius: 12px;
}
</style>

<section class="content">
<div class="container-fluid">

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="page-title">
        <i class="fas fa-clipboard-list"></i> Kriteria Penilaian
    </h4>

    <div>
        <a href="{{ route('admin.skala.index') }}" class="btn btn-success me-2">
            <i class="fas fa-balance-scale"></i> Skala Nilai
        </a>

        <button class="btn btn-primary" data-toggle="modal" data-target="#tambahKriteriaModal">
            <i class="fas fa-plus"></i>
        </button>
    </div>
</div>

<!-- TOTAL BOBOT -->
<div class="card mb-3 p-3">
    <div class="d-flex justify-content-between align-items-center">
        <span class="{{ $totalBobot == 1.0 ? 'total-valid' : 'total-invalid' }}">
            <b>Total Bobot:</b> {{ number_format($totalBobot, 2) }}

            @if($totalBobot == 1.0)
                <i class="fas fa-check-circle"></i> Valid
            @else
                <i class="fas fa-exclamation-triangle"></i> Harus = 1.00
            @endif
        </span>
    </div>
</div>

<!-- TABLE -->
<div class="card">
<div class="card-header">
    <b>Data Kriteria ({{ $kriterias->count() }})</b>
</div>

<div class="card-body table-responsive">
<table class="table table-bordered mb-0 text-center align-middle">

<thead>
<tr>
    <th>No</th>
    <th>Kode</th>
    <th class="text-start">Nama</th>
    <th>Bobot</th>
    <th>%</th>
    <th>Deskripsi</th>
    <th width="120">Aksi</th>
</tr>
</thead>

<tbody>
@forelse($kriterias as $kriteria)
<tr>
<td>{{ $loop->iteration }}</td>

<td>
<span class="badge badge-kode">{{ $kriteria->kode }}</span>
</td>

<td class="text-start">
<strong>{{ $kriteria->nama_kriteria }}</strong>
</td>

<td>{{ number_format($kriteria->bobot,2) }}</td>
<td>{{ $kriteria->bobot * 100 }}%</td>
<td>{{ $kriteria->deskripsi ?? '-' }}</td>

<td>
<button class="btn btn-sm btn-warning"
    data-toggle="modal"
    data-target="#editKriteriaModal{{ $kriteria->id }}">
    <i class="fas fa-edit"></i>
</button>

<form action="{{ route('admin.penilaian.destroy', $kriteria->id) }}"
      method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="button" class="btn btn-sm btn-danger btn-delete"
    data-id="{{ $kriteria->id }}">
    <i class="fas fa-trash"></i>
</button>
</form>
</td>
</tr>

<!-- MODAL EDIT -->
<div class="modal fade" id="editKriteriaModal{{ $kriteria->id }}">
<div class="modal-dialog">
<div class="modal-content">

<form action="{{ route('admin.penilaian.update', $kriteria->id) }}" method="POST">
@csrf
@method('PUT')

<div class="modal-header">
<h5>Edit Kriteria</h5>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">

<div class="mb-2">
<label>Kode</label>
<input type="text" name="kode" class="form-control"
value="{{ $kriteria->kode }}" readonly>

<div class="mb-2">
<label>Nama</label>
<input type="text" name="nama_kriteria"
value="{{ $kriteria->nama_kriteria }}"
class="form-control" required>
</div>

<div class="mb-2">
<label>Bobot</label>
<input type="number" name="bobot"
value="{{ $kriteria->bobot }}"
step="0.01" class="form-control" required>
</div>

<div class="mb-2">
<label>Deskripsi</label>
<textarea name="deskripsi" class="form-control">
{{ $kriteria->deskripsi }}
</textarea>
</div>

</div>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
<button class="btn btn-primary">Update</button>
</div>

</form>

</div>
</div>
</div>

@empty
<tr>
<td colspan="7">
<div class="text-center text-muted">
Belum ada data
</div>
</td>
</tr>
@endforelse
</tbody>

</table>
</div>
</div>

</div>
</section>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="tambahKriteriaModal">
<div class="modal-dialog">
<div class="modal-content">

<form action="{{ route('admin.penilaian.store') }}" method="POST">
@csrf

<div class="modal-header">
<h5>Tambah Kriteria</h5>
<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">

<div class="mb-2">
<label>Kode</label>
<input type="text" name="kode" id="kodeInput" class="form-control" required>
<small id="kodeWarning" class="text-danger d-none">
    ⚠️ Kode sudah digunakan!
</small>
</div>

<div class="mb-2">
<label>Nama</label>
<input type="text" name="nama_kriteria" class="form-control" required>
</div>

<div class="mb-2">
<label>Bobot</label>
<input type="number" name="bobot" step="0.01" min="0" max="1"
class="form-control" placeholder="Contoh: 0.10" required>

<small class="text-muted">
    Format: 0.01 - 1.00 (contoh: 0.10 = 10%)
</small>
</div>

<div class="mb-2">
<label>Deskripsi</label>
<textarea name="deskripsi" class="form-control"></textarea>
</div>

</div>

<div class="modal-footer">
<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
<button class="btn btn-success">Simpan</button>
</div>

</form>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.querySelectorAll('.btn-delete').forEach(btn => {
    btn.addEventListener('click', function (e) {

        e.preventDefault(); // 🔥 WAJIB

        let form = this.closest('form'); // 🔥 ambil form asli

        Swal.fire({
            title: 'Yakin hapus?',
            text: 'Data tidak bisa dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit(); // 🔥 submit form asli Laravel
            }
        });

    });
});
</script>

<script>
let existingKode = [
    @foreach($kriterias as $k)
        "{{ strtoupper($k->kode) }}",
    @endforeach
];

let kodeInput = document.getElementById('kodeInput');
let warning = document.getElementById('kodeWarning');

kodeInput.addEventListener('input', function() {
    this.value = this.value.toUpperCase();

    if (existingKode.includes(this.value)) {
        warning.classList.remove('d-none');
        this.classList.add('is-invalid');
    } else {
        warning.classList.add('d-none');
        this.classList.remove('is-invalid');
    }
});
</script>

<script>
let existingKode = [
    @foreach($kriterias as $k)
        "{{ $k->kode }}",
    @endforeach
];

document.getElementById('kodeInput').addEventListener('input', function() {
    let val = this.value.toUpperCase();
    let warning = document.getElementById('kodeWarning');

    if (existingKode.includes(val)) {
        warning.classList.remove('d-none');
        this.classList.add('is-invalid');
    } else {
        warning.classList.add('d-none');
        this.classList.remove('is-invalid');
    }
});
</script>

<script>
document.querySelectorAll('input[name="bobot"]').forEach(input => {
    input.addEventListener('blur', function() {
        if (this.value !== '') {
            this.value = Number(this.value).toFixed(2);
        }
    });
});
</script>
@endsection