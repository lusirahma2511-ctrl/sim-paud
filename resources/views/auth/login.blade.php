<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - SIM PAUD</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body {
        background: linear-gradient(135deg, #0d6efd, #d63384);
    }

    .login-card {
        border-radius: 20px;
    }

    .btn-custom {
        background-color: #d63384;
        border: none;
        transition: 0.3s;
    }

    .btn-custom:hover {
        background-color: #b02a6f;
        transform: translateY(-2px);
    }

    .form-control {
        border-radius: 12px;
        padding-left: 45px;
        height: 45px;
        border: 1px solid #dee2e6;
        background-color: #f8f9fa;
    }

    .form-control:focus {
        border-color: #fd7e14;
        box-shadow: 0 0 0 0.2rem rgba(253,126,20,.25);
        background-color: #ffffff;
    }

    .input-group-text {
        background: transparent;
        border: none;
        position: absolute;
        z-index: 10;
        height: 45px;
        display: flex;
        align-items: center;
        padding-left: 15px;
        color: #d63384;
    }

    .form-wrapper {
        position: relative;
    }

    .form-label {
        font-size: 14px;
        font-weight: 600;
        color: #555;
        margin-bottom: 5px;
    }

    .title-main {
        color: #0d6efd;
    }

    .subtitle {
        color: #d63384;
    }
    .fa-whatsapp:hover {
    transform: scale(1.2);
    transition: 0.3s;
}
.login-card {
    border-radius: 20px;
    position: relative;
    padding-bottom: 70px;  /* tambah ruang bawah */
}

.wa-icon {
    position: absolute;
    bottom: 3px;
    right: 15px;
    background-color: #25D366;
    color: white;
    padding: 10px;
    border-radius: 50%;
    font-size: 18px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
}
@keyframes shake {
    0% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    50% { transform: translateX(5px); }
    75% { transform: translateX(-5px); }
    100% { transform: translateX(0); }
}

.input-error {
    animation: shake 0.3s;
    border: 2px solid red !important;
}
.login-card {
    border-radius: 20px;
    width: 100%;
    max-width: 400px;
    position: relative;
    padding: 30px;
}

.form-control {
    height: 45px;
    border-radius: 10px;
    background: #f8f9fa;
}

.btn-custom {
    background: linear-gradient(135deg, #d63384, #0d6efd);
    border: none;
    color: white;
}

.btn-custom:hover {
    transform: translateY(-2px);
}

.wa-icon {
    position: absolute;
    bottom: 15px;
    right: 15px;
    background: #25D366;
    color: white;
    padding: 10px;
    border-radius: 50%;
}
</style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card login-card shadow-lg p-4">

        <!-- HEADER -->
        <div class="text-center mb-4">
            <img src="{{ asset('images/logo paud.png') }}" style="width: 120px;" class="mb-3">
            <h4 class="fw-bold title-main mb-1">SIM PAUD</h4>
            <small class="text-muted">Silahkan Login</small>
        </div>

        <form method="POST" action="{{ route('login.store') }}">
            @csrf

            <!-- LOGIN -->
            <div class="mb-3">
                <label class="form-label">Email / NISN</label>
                <div class="position-relative">
                    <i class="fa fa-user position-absolute"
                       style="left:15px; top:50%; transform:translateY(-50%); color:#d63384;"></i>

                    <input type="text"
                           name="login"
                           id="login"
                           class="form-control ps-5"
                           placeholder="Email (Admin/Guru) atau NISN (Orang Tua)"
                           required autofocus>
                </div>
            </div>

            <!-- PASSWORD -->
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="position-relative">

                    <i class="fa fa-lock position-absolute"
                       style="left:15px; top:50%; transform:translateY(-50%); color:#d63384;"></i>

                    <input type="password"
                           name="password"
                           id="password"
                           class="form-control ps-5 pe-5"
                           required>

                    <span onclick="togglePassword()"
                          style="position:absolute; right:15px; top:50%; transform:translateY(-50%); cursor:pointer; color:#d63384;">
                        <i id="eyeIcon" class="fa fa-eye"></i>
                    </span>
                </div>
            </div>

            <!-- BUTTON -->
            <div class="d-grid mb-3">
               <button type="submit" id="loginBtn" class="btn btn-custom fw-semibold">
    Login
</button>
            </div>

            <!-- INFO -->
            <div class="text-center small text-muted">
                Jika mengalami kendala login, hubungi admin.
            </div>

        </form>

        <!-- WA BUTTON -->
        <a href="https://wa.me/6281546992407"
           target="_blank"
           class="wa-icon">
            <i class="fa-brands fa-whatsapp"></i>
        </a>

    </div>
</div>
<script>
function togglePassword() {
    const password = document.getElementById("password");
    const eyeIcon = document.getElementById("eyeIcon");

    if (password.type === "password") {
        password.type = "text";
        eyeIcon.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        password.type = "password";
        eyeIcon.classList.replace("fa-eye-slash", "fa-eye");
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- LOADING BUTTON -->
<script>
document.querySelector("form").addEventListener("submit", function() {
    const btn = document.getElementById("loginBtn");

    if (btn.disabled) return; // cegah double klik

    btn.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i> Loading...';
    btn.disabled = true;
});
</script>

<!-- ERROR HANDLING -->
@if ($errors->any())
<script>
document.getElementById('login').classList.add('input-error');
document.getElementById('password').classList.add('input-error');

Swal.fire({
    icon: 'error',
    title: 'Login Gagal',
    text: '{{ $errors->first() }}',
    confirmButtonColor: '#d63384'
});
</script>
@endif
</body>
</html>
