@extends('layouts.adminlte')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title"><i class="fas fa-calendar-times mr-2"></i> Pengaturan Hari Libur</h3>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#createModal">
                        <i class="fas fa-plus mr-1"></i>
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($hariLiburs as $i => $hl)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $hl->tanggal->translatedFormat('l, d F Y') }}</td>
                                    <td>{{ $hl->keterangan }}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editModal-{{ $hl->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form id="delete-form-{{ $hl->id }}" action="{{ route('admin.hari_libur.destroy', $hl->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete('{{ $hl->id }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createModalLabel"><i class="fas fa-plus-circle mr-2"></i>Tambah Hari Libur</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('admin.hari_libur.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label>Jenis Input</label>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="jenis_input" id="singleDate" value="single" checked onchange="toggleDateInputs()">
              <label class="form-check-label" for="singleDate">
                Tanggal Tunggal
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="radio" name="jenis_input" id="dateRange" value="range" onchange="toggleDateInputs()">
              <label class="form-check-label" for="dateRange">
                Rentang Tanggal
              </label>
            </div>
          </div>
          <div class="form-group" id="singleDateGroup">
            <label>Tanggal Libur</label>
            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" value="{{ old('tanggal') }}">
            @error('tanggal')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>
          <div class="form-row" id="dateRangeGroup" style="display: none;">
            <div class="form-group col-md-6">
              <label>Tanggal Mulai</label>
              <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai') }}">
              @error('tanggal_mulai')
                  <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
            <div class="form-group col-md-6">
              <label>Tanggal Selesai</label>
              <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai') }}">
              @error('tanggal_selesai')
                  <span class="invalid-feedback">{{ $message }}</span>
              @enderror
            </div>
          </div>
          <div class="form-group">
            <label>Keterangan</label>
            <input type="text" name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" required placeholder="Contoh: Libur Hari Raya" value="{{ old('keterangan') }}">
            @error('keterangan')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function toggleDateInputs() {
    const singleDateGroup = document.getElementById('singleDateGroup');
    const dateRangeGroup = document.getElementById('dateRangeGroup');
    const singleDateRadio = document.getElementById('singleDate');
    const dateRangeRadio = document.getElementById('dateRange');

    if (singleDateRadio.checked) {
        singleDateGroup.style.display = 'block';
        dateRangeGroup.style.display = 'none';
    } else {
        singleDateGroup.style.display = 'none';
        dateRangeGroup.style.display = 'flex';
    }
}
</script>

<!-- Modal Edit (Per Item) -->
@foreach($hariLiburs as $hl)
<div class="modal fade" id="editModal-{{ $hl->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel-{{ $hl->id }}" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel-{{ $hl->id }}"><i class="fas fa-edit mr-2"></i>Edit Hari Libur</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('admin.hari_libur.update', $hl->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="form-group">
            <label>Tanggal Libur</label>
            <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" required value="{{ old('tanggal', $hl->tanggal->format('Y-m-d')) }}">
            @error('tanggal')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>
          <div class="form-group">
            <label>Keterangan</label>
            <input type="text" name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" required placeholder="Contoh: Libur Hari Raya" value="{{ old('keterangan', $hl->keterangan) }}">
            @error('keterangan')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>Update</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endforeach

@endsection
