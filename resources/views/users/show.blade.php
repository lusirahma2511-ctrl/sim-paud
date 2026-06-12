@extends('layouts.adminlte')
@section('content')
<div class="content mt-3">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Detail Pengguna: {{ $user->name }}</h5>
                <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-4">
                        @if($user->foto)
                            <img src="{{ asset('storage/'.$user->foto) }}" 
                                 class="img-fluid rounded shadow" style="max-width: 250px;">
                        @else
                            <div class="d-flex align-items-center justify-content-center rounded shadow"
                                 style="width: 250px; height: 250px; background:#ddd;">
                                <i class="fas fa-user text-gray-500" style="font-size:120px;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-8">
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Nama Lengkap</div>
                            <div class="col-md-8">{{ $user->name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Login ID</div>
                            <div class="col-md-8">{{ $user->email ?? $user->username ?? '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Role Akses</div>
                            <div class="col-md-8">
                                <span class="badge badge-role 
                                    @if($user->role=='admin') badge-danger
                                    @elseif($user->role=='guru') badge-success
                                    @elseif($user->role=='guru_kelas') badge-success
                                    @elseif($user->role=='kepala_sekolah') badge-warning
                                    @else badge-primary
                                    @endif">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Status</div>
                            <div class="col-md-8">
                                @php
                                    $userStatus = strtolower(trim($user->status ?? ''));
                                    $isAktif = in_array($userStatus, ['aktif', '']);
                                @endphp
                                <span class="badge {{ $isAktif ? 'badge-success' : 'badge-secondary' }}">
                                    @if($isAktif)
                                        <i class="fas fa-check-circle"></i> Aktif
                                    @else
                                        <i class="fas fa-times-circle"></i> Nonaktif
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Password Default</div>
                            <div class="col-md-8">
                                {{ $user->is_default_password ? 'Ya' : 'Tidak' }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Dibuat pada</div>
                            <div class="col-md-8">{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') : '-' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Diupdate pada</div>
                            <div class="col-md-8">{{ $user->updated_at ? \Carbon\Carbon::parse($user->updated_at)->format('d/m/Y H:i') : '-' }}</div>
                        </div>

                        <hr>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $user->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editModalLabel{{ $user->id }}">Edit User</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold">Nama Pengguna <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold">Role Akses <span class="text-danger">*</span></label>
                                <select class="form-control" name="role" id="editRoleSelect{{ $user->id }}" required>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="guru" {{ old('role', $user->role) == 'guru' ? 'selected' : '' }}>Guru</option>
                                    <option value="guru_kelas" {{ old('role', $user->role) == 'guru_kelas' ? 'selected' : '' }}>Guru Kelas</option>
                                    <option value="kepala_sekolah" {{ old('role', $user->role) == 'kepala_sekolah' ? 'selected' : '' }}>Kepala Sekolah</option>
                                    <option value="orang_tua" {{ old('role', $user->role) == 'orang_tua' ? 'selected' : '' }}>Orang Tua</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group" id="editEmailGroup{{ $user->id }}">
                                <label class="fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" id="editEmailInput{{ $user->id }}">
                            </div>
                            <div class="form-group d-none" id="editNisnGroup{{ $user->id }}">
                                <label class="fw-bold">Username (NISN) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="username" value="{{ old('username', $user->username) }}" id="editNisnInput{{ $user->id }}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold">Status <span class="text-danger">*</span></label>
                                <select class="form-control" name="status" required>
                                    <option value="Aktif" {{ old('status', $user->status ?? 'Aktif') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="Nonaktif" {{ old('status', $user->status) == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="fw-bold">Password Baru <span class="text-muted small">(opsional, isi hanya jika ingin mengganti password)</span></label>
                                <input type="password" class="form-control" name="password" placeholder="Min. 6 karakter">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="fw-bold">Konfirmasi Password Baru</label>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Ulangi password baru">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmReset(url) {
        if (confirm('Anda yakin ingin mereset password pengguna ini?')) {
            window.location.href = url;
        }
    }

    function confirmDelete(id, form) {
        if (confirm('Anda yakin ingin menghapus pengguna ini?')) {
            form.submit();
        }
    }

    document.getElementById('editRoleSelect{{ $user->id }}').addEventListener('change', function() {
        const selectedRole = this.value;
        const emailGroup = document.getElementById('editEmailGroup{{ $user->id }}');
        const nisnGroup = document.getElementById('editNisnGroup{{ $user->id }}');
        const emailInput = document.getElementById('editEmailInput{{ $user->id }}');
        const nisnInput = document.getElementById('editNisnInput{{ $user->id }}');

        if (selectedRole === 'orang_tua') {
            emailGroup.classList.add('d-none');
            emailInput.required = false;
            nisnGroup.classList.remove('d-none');
            nisnInput.required = true;
        } else {
            nisnGroup.classList.add('d-none');
            nisnInput.required = false;
            emailGroup.classList.remove('d-none');
            emailInput.required = true;
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const initialRole = document.getElementById('editRoleSelect{{ $user->id }}').value;
        const emailGroup = document.getElementById('editEmailGroup{{ $user->id }}');
        const nisnGroup = document.getElementById('editNisnGroup{{ $user->id }}');
        const emailInput = document.getElementById('editEmailInput{{ $user->id }}');
        const nisnInput = document.getElementById('editNisnInput{{ $user->id }}');

        if (initialRole === 'orang_tua') {
            emailGroup.classList.add('d-none');
            emailInput.required = false;
            nisnGroup.classList.remove('d-none');
            nisnInput.required = true;
        } else {
            nisnGroup.classList.add('d-none');
            nisnInput.required = false;
            emailGroup.classList.remove('d-none');
            emailInput.required = true;
        }
    });
</script>
@endsection
