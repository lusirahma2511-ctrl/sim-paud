<!DOCTYPE html>
<html>
<head>
    @include('layouts.partials.head')
</head>

<body style="background:#f8f9fa">

<!-- NAVBAR SIMPLE -->
<nav class="navbar navbar-expand-lg navbar-dark bg-warning px-4">
    <span class="navbar-brand">Dashboard Kepala Sekolah</span>

    <div class="ms-auto">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-danger btn-sm">Logout</button>
        </form>
    </div>
</nav>

<div class="container mt-4">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')

</body>
</html>