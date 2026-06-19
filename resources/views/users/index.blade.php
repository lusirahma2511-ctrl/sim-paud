@extends('layouts.adminlte')

@section('content')
@php
    $hasStatusColumn = \Illuminate\Support\Facades\Schema::hasColumn('users', 'status');
@endphp

<style>
/* ===== GLOBAL WRAPPER ===== */
.content-wrapper {
    background: #f4f6f9 !important;
}

/* ===== TOOLBAR (PINK/PURPLE STYLE LIKE SCREENSHOT) ===== */
.toolbar-user {
    background: white;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.btn-tambah-custom {
    background: #e83e8c !important; /* Pink/Purple like screenshot */
    color: white !important;
    border: none !important;
    padding: 8px 20px !important;
    font-weight: 600 !important;
    border-radius: 4px !important;
}

.btn-cari-custom {
    background: white !important;
    color: #007bff !important;
    border: 1px solid #ddd !important;
    padding: 4px 15px !important;
}

/* ===== CARD (FLAT & CLEAN) ===== */
.card-user-container {
    background: white;
    border-radius: 0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card-user-header {
    background: #17a2b8; /* Tosca/Cyan like screenshot */
    color: white;
    padding: 12px 20px;
    font-weight: 600;
}

/* ===== TABLE STYLING ===== */
.table {
    border: 1px solid #dee2e6;
}

.table thead th {
    background-color: white;
    border-bottom: 2px solid #dee2e6;
    border-right: 1px solid #dee2e6;
    color: #333;
    font-weight: bold;
    text-transform: none;
    font-size: 14px;
    padding: 15px 10px;
}

.table thead th:last-child {
    border-right: none;
}

.table tbody td {
    padding: 15px 10px;
    vertical-align: middle;
    border-top: 1px solid #dee2e6;
    border-right: 1px solid #dee2e6;
}

.table tbody td:last-child {
    border-right: none;
}

.table tbody tr:hover {
    background-color: #f8f9fc;
}

/* ===== ACTION BUTTONS (LIKE SCREENSHOT) ===== */
.btn-action-pink {
    background: #e83e8c !important;
    color: white !important;
    border-radius: 4px !important;
    font-size: 12px !important;
    padding: 4px 12px !important;
    margin: 0 2px;
}

.btn-action-tosca {
    background: #17a2b8 !important;
    color: white !important;
    border-radius: 4px !important;
    font-size: 12px !important;
    padding: 4px 12px !important;
    margin: 0 2px;
}

.btn-action-red {
    background: #dc3545 !important;
    color: white !important;
    border-radius: 4px !important;
    font-size: 12px !important;
    padding: 4px 12px !important;
    margin: 0 2px;
}

/* ===== BADGE ===== */
.badge-role {
    font-weight: normal;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
}
</style>

<div class="content mt-3">
    <div class="container-fluid">

        <!-- TOOLBAR (SESUAI SCREENSHOT) -->
        <div class="toolbar-user d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <button class="btn btn-primary" data-toggle="modal" data-target="#tambahUserModal" title="Tambah User">
                    <i class="fas fa-plus"></i>
                </button>
            </div>
            
            <form method="GET" action="{{ route('admin.users.index') }}" class="d-flex align-items-center">
                <input type="text" name="search" class="form-control form-control-sm me-0"
                       placeholder="Cari..." value="{{ request('search') }}" 
                       style="width: 180px; border-radius: 4px 0 0 4px; border-right: none;">
                <button class="btn btn-cari-custom btn-sm" style="border-radius: 0 4px 4px 0;">Cari</button>
            </form>
        </div>

        <!-- TABLE CARD (SESUAI SCREENSHOT) -->
        <div class="card-user-container">
            <div class="card-user-header">
                Daftar User ({{ $users->total() }})
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 text-center">
                        <thead>
                            <tr>
                                <th width="60">No</th>
                                <th>Nama Pengguna</th>
                                <th>Role Akses</th>
                                <th>Status</th>
                                <th width="250">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                            <tr>
                                <td>{{ ($users->currentPage()-1)*$users->perPage()+$loop->iteration }}</td>
                                <td class="text-start">
                                    <span class="fw-bold">{{ $user->name }}</span>
                                </td>
                                <td>
                                    <span class="badge badge-role 
                                        @if($user->role=='admin') badge-danger
                                        @elseif($user->role=='guru_kelas') badge-success
                                        @elseif($user->role=='guru_pendamping') badge-success
                                        @elseif($user->role=='kepala_sekolah') badge-warning
                                        @else badge-primary
                                        @endif">
                                        @switch($user->role)
                                            @case('guru_kelas') Guru Kelas @break
                                            @case('guru_pendamping') Guru Pendamping @break
                                            @case('kepala_sekolah') Kepala Sekolah @break
                                            @case('orang_tua') Orang Tua @break
                                            @default {{ strtoupper($user->role) }}
                                        @endswitch
                                    </span>
                                </td>
                                <td>
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
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-sm btn-success" title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-action-pink"
                                            title="Reset Password"
                                            onclick="confirmReset('{{ route('admin.users.resetPassword',$user->id) }}')">
                                            <i class="fas fa-key"></i>
                                        </button>

                                        <button class="btn btn-action-tosca"
                                            title="Edit User"
                                            data-toggle="modal"
                                            data-target="#editModal{{ $user->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <form action="{{ route('admin.users.destroy',$user->id) }}" method="POST" class="d-inline btn-delete-user">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-action-red" title="Hapus User">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="{{ $hasStatusColumn ? '5' : '4' }}" class="py-5 text-muted">Belum ada data user</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($users->hasPages())
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-center">
                    {{ $users->links('pagination::bootstrap-4') }}
                </div>
            </div>
            @endif
        </div>

    </div>
</div>

<!-- Modal Edit User -->
@foreach($users as $user)
<div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title font-weight-bold">
                    Edit: <b>{{ $user->name }}</b>
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>

            <form method="POST" action="{{ route('admin.users.update',$user->id) }}">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Role</label>
                            <select name="role" class="form-control roleSelect" required>
                                <option value="admin" {{ $user->role=='admin'?'selected':'' }}>Admin</option>
                                <option value="guru_kelas" {{ $user->role=='guru_kelas'?'selected':'' }}>Guru Kelas</option>
                                <option value="guru_pendamping" {{ $user->role=='guru_pendamping'?'selected':'' }}>Guru Pendamping</option>
                                <option value="kepala_sekolah" {{ $user->role=='kepala_sekolah'?'selected':'' }}>Kepala Sekolah</option>
                                <option value="orang_tua" {{ $user->role=='orang_tua'?'selected':'' }}>Orang Tua</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 emailField">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" value="{{ $user->email }}" class="form-control" placeholder="Untuk Admin/Guru">
                        </div>
                        <div class="col-md-6 mb-3 usernameField">
                            <label class="form-label fw-bold">NISN</label>
                            <input type="text" name="username" value="{{ $user->username }}" class="form-control" placeholder="Untuk Orang Tua">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Password Baru</label>
                            <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah" autocomplete="new-password">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                        </div>
                        @if($hasStatusColumn)
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="Aktif" {{ $user->status=='Aktif'?'selected':'' }}>Aktif</option>
                                <option value="Nonaktif" {{ $user->status=='Nonaktif'?'selected':'' }}>Nonaktif</option>
                            </select>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Data</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Tambah User -->
<div class="modal fade" id="tambahUserModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold">Tambah User Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Nama</label>
                            <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Role</label>
                            <select name="role" class="form-control roleSelect" required id="tambahRoleSelect">
                                <option value="">-- Pilih Role --</option>
                                <option value="admin">Admin</option>
                                <option value="guru_kelas">Guru Kelas</option>
                                <option value="guru_pendamping">Guru Pendamping</option>
                                <option value="kepala_sekolah">Kepala Sekolah</option>
                                <option value="orang_tua">Orang Tua</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 emailField">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email (Admin/Guru)">
                        </div>
                        <div class="col-md-6 mb-3 usernameField">
                            <label class="form-label fw-bold">NISN</label>
                            <input type="text" name="nisn" class="form-control" placeholder="NISN (Orang Tua)">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Minimal 8 karakter" autocomplete="new-password">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Status</label>
                            <select name="status" class="form-control" required>
                                <option value="Aktif" selected>Aktif</option>
                                <option value="Nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan User</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ================= NOTIFIKASI SWEETALERT2 =================
@if(session('success'))
    let successMsg = "{{ session('success') }}";
    @if(session('new_password'))
        successMsg += "\nPassword Baru: {{ session('new_password') }}";
    @endif
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: successMsg,
        confirmButtonText: 'OK'
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
        title: 'Validation Error',
        html: `
            <ul class="text-start">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        `,
    });
@endif

// ================= KONFIRMASI DELETE =================
$(document).on('click', '.btn-delete-user button', function (e) {
    e.preventDefault();
    let form = $(this).closest('form');
    Swal.fire({
        title: 'Hapus User?',
        text: "User yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});

// ================= KONFIRMASI RESET PASSWORD =================
function confirmReset(url) {
    Swal.fire({
        title: 'Reset Password?',
        text: "Password user akan dikembalikan ke default!",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#f39c12',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Reset!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
}

// ================= TOGGLE FIELD EMAIL/USERNAME =================
$(document).on('change', '.roleSelect', function() {
    let modal = $(this).closest('.modal');
    let role = $(this).val();
    
    if (role === 'orang_tua') {
        modal.find('.emailField').hide();
        modal.find('.usernameField').show();
    } else {
        modal.find('.emailField').show();
        modal.find('.usernameField').hide();
    }
});

// Trigger saat load pertama kali untuk modal edit
 $('.roleSelect').trigger('change');
 </script>
 @endpush