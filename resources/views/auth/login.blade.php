<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
</head>
<body>

<div class="login-container d-flex justify-content-center align-items-center">
    <div class="login-box p-5 shadow position-relative">
        <!-- Logo toga menyatu dengan card -->
        <div class="login-logo">
            <div class="circle">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
        </div>

        <!-- Judul -->
        <div class="text-center mb-4 mt-4">
            <h3 class="fw-bold text-primary">Let's get started now!</h3>
        </div>

        <!-- Form Login -->
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3 text-start">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control input-style" id="email" name="email" placeholder="mail@mail.com" required autofocus>
            </div>

            <div class="mb-4 text-start">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control input-style" id="password" name="password" placeholder="********" required>
            </div>

            <button type="submit" class="btn btn-login w-100">Sign In</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
