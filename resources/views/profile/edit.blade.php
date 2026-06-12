@extends('layouts.adminlte')

@section('title', 'Profile Saya')

@section('content')

<style>
.profile-card {
    border-radius: 15px;
    overflow: hidden;
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    color: white;
}

.profile-card img {
    border: 4px solid #fff;
    margin-top: 15px;
}

.form-card {
    border-radius: 15px;
}

.form-control {
    border-radius: 10px;
}

.btn-save {
    border-radius: 10px;
    padding: 10px;
}
</style>

<div class="container-fluid">

    <div class="row">

        <!-- PROFILE -->
        <div class="col-md-4 mb-3">
            <div class="card profile-card shadow text-center p-3">

                @if($user->foto)
                    <img class="img-fluid img-circle mx-auto"
                         style="width:120px;height:120px;object-fit:cover;"
                         src="{{ asset('storage/'.$user->foto) }}">
                @else
                    <div class="img-circle mx-auto d-flex align-items-center justify-content-center"
                         style="width:120px; height:120px; background:#ddd;">
                        <i class="fas fa-user text-gray-500" style="font-size:60px;"></i>
                    </div>
                @endif

                <h4 class="mt-3 mb-0">{{ $user->name }}</h4>
                <small class="text-light text-capitalize">{{ $user->role }}</small>

                <hr class="bg-white">

                <p class="mb-0">{{ $user->email }}</p>

            </div>
        </div>

        <!-- FORM -->
        <div class="col-md-8 mb-3">
            <div class="card form-card shadow-sm border-0">

                <div class="card-header bg-white">
                    <h5 class="mb-0">Edit Profile</h5>
                </div>

                <div class="card-body">

                    @if(session('success'))
                        <div class="alert alert-success py-2">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            <div class="col-md-6 mb-3">
                                <label>Nama</label>
                                <input type="text" name="name" class="form-control"
                                       value="{{ old('name', $user->name) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control"
                                       value="{{ old('email', $user->email) }}">
                            </div>

                        </div>

                        <div class="mb-3">
                            <label>Foto Profil</label>
                            <input type="file" name="foto" class="form-control">
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Password Baru</label>
                                <input type="password" name="password" class="form-control">
                                <small class="text-muted">Kosongkan jika tidak diubah</small>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-save w-100">
                            💾 Simpan Perubahan
                        </button>

                    </form>

                </div>
            </div>
        </div>

    </div>

</div>
@endsection