<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
<!-- SELECT2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- GOOGLE FONTS (POPPINS) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @include('layouts.partials.head')
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
<style>
* {
    font-family: 'Poppins', sans-serif;
}

.content-wrapper {
    padding-top: 70px !important;
    background: #ffffff !important;
}

/* 🔥 GLOBAL UI STYLE */
.content {
    background: #ffffff !important;
    min-height: 100vh;
    padding: 20px 0;
}

.card {
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    border: none;
    margin-bottom: 1.5rem;
}

.btn-primary {
    background: linear-gradient(90deg, rgb(233, 103, 205), #6f42c1) !important;
    border: none !important;
    color: white !important;
}

.btn-primary:hover {
    opacity: 0.9;
    color: white !important;
}

/* 🔥 GLOBAL MODAL STYLE (STANDARDIZED) */
.modal {
    overflow-y: auto !important;
    z-index: 1060 !important;
}

.modal-backdrop {
    z-index: 1055 !important;
}

body.modal-open {
    overflow: hidden !important;
    padding-right: 0 !important;
}

.modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.modal-header {
    border-bottom: 1px solid #f0f0f0;
    padding: 20px 25px;
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
}

.modal-body {
    padding: 25px;
}

.modal-footer {
    border-top: 1px solid #f0f0f0;
    padding: 15px 25px;
    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
}

.modal-dialog-centered {
    display: flex;
    align-items: center;
    min-height: calc(100% - 3.5rem);
}

/* FIX SELECT2 INSIDE MODAL */
.select2-container {
    z-index: 9999 !important;
}

.select2-container--open {
    z-index: 10000 !important;
}
</style>
    <!-- Navbar -->
    @include('layouts.partials.navbar')

    <!-- Sidebar -->
    @if(auth()->user()->role === 'admin')
        @include('layouts.partials.sidebar')
    @elseif(auth()->user()->role === 'guru' || auth()->user()->role === 'guru_kelas')
        @include('layouts.partials.sidebarguru')
    @elseif(auth()->user()->role === 'kepala_sekolah')
        @include('layouts.partials.sidebarkepala')
    @elseif(auth()->user()->role === 'orang_tua')
        @include('layouts.partials.sidebarorangtua')
    @endif

    <!-- Content Wrapper -->
    <div class="content-wrapper" style="padding-top: 70px;">

        <!-- Content Header -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>@yield('title')</h1>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">



                <!-- INI YANG BENAR -->
                @yield('content')

            </div>
        </section>

    </div>

    <!-- Footer -->
    <footer class="main-footer">
        <strong>&copy; 2026 SIM PAUD - POS PAUD TERATAI SINDANGSARI</strong>
    </footer>

</div>

<!-- Footer Partials (Berisi jQuery, Bootstrap, AdminLTE via CDN) -->
@include('layouts.partials.footer')

<!-- GLOBAL SWEETALERT -->
<script>
$(document).ready(function() {
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false,
            borderRadius: '15px'
        });
    @endif

    @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: "{{ session('error') }}",
            borderRadius: '15px'
        });
    @endif

    @if ($errors->any())
        let errorText = @json(implode('\\n', $errors->all()));
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: errorText,
            borderRadius: '15px'
        });
    @endif
});

// STANDARDIZED DELETE CONFIRMATION
function confirmDelete(id, formId = null) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal',
        borderRadius: '15px'
    }).then((result) => {
        if (result.isConfirmed) {
            if (formId) {
                document.getElementById(formId).submit();
            } else {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    })
}

// 🔥 GLOBAL MODAL & SELECT2 FIXES
$(document).on('hidden.bs.modal', '.modal', function () {
    if ($('.modal.show').length || $('.modal.in').length) {
        $('body').addClass('modal-open');
    } else {
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    }
});

$(document).on('shown.bs.modal', '.modal', function () {
    if (typeof $.fn.select2 !== 'undefined') {
        $(this).find('.select2, .select2-edit').each(function() {
            $(this).select2({
                width: '100%',
                dropdownParent: $(this).closest('.modal')
            });
        });
    }
});
</script>

<!-- Script Tambahan dari Halaman Lain -->
@stack('scripts')

</body>
</html>