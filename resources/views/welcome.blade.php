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


</style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center vh-100">
<div class="card login-card shadow-lg p-4" style="width: 420px;">

<div class="text-center mb-4">
        <img src="{{ asset('images/logo paud.png') }}" alt="Logo" style="width: 150px;" class="mb-3">
        <h4 class="fw-bold title-main mb-1">SIM PAUD</h4>
        <p class="subtitle small">Silahkan Login</p>
</div>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3 form-wrapper">
    <label class="form-label">Email</label>
    <span class="input-group-text">
        <i class="fa fa-envelope"></i>
    </span>
    <input type="email" name="email" class="form-control" required autofocus>
</div>

<div class="mb-3 position-relative">
    <label class="form-label text-start d-block">Password</label>
    <span class="input-group-text">
            <i class="fa fa-lock"></i>
        </span>
    <input type="password" 
           name="password" 
           id="password" 
           class="form-control pe-5" 
           required>

    <span onclick="togglePassword()" 
          style="position:absolute; right:15px; top:38px; cursor:pointer; color:#d63384;">
        <i id="eyeIcon" class="fa fa-eye"></i>
    </span>
</div>
            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-custom rounded-3 fw-semibold">
                    Login
                </button>
            </div>

            <div class="mt-3 text-center small text-muted">
    <p class="mb-0">
        Jika mengalami kendala login, silakan menghubungi Admin 
        POS PAUD Teratai Sindangsari.
    </p>
</div>

<!-- WA ICON DI LUAR TEXT -->
<a href="https://wa.me/6281546992407"
   target="_blank"
   class="wa-icon text-decoration-none">
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
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
    } else {
        password.type = "password";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
    }
}
</script>

</body>
</html>
