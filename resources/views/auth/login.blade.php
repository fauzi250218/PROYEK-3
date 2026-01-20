<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | Sistem Monitoring</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>

  <div class="login-page">
    <!-- Kiri -->
    <div class="login-left d-flex flex-column justify-content-center align-items-center text-center text-white">
      <div class="overlay"></div>
      <img src="{{ asset('images/simpel.png') }}" alt="Logo" class="logo mb-4">
      <h2 class="fw-bold">SIMPel</h2>
      <p class="lead">Sistem Informasi Manajemen dan Pelaporan Siswa</p>
    </div>

    <!-- Kanan -->
    <div class="login-right d-flex align-items-center justify-content-center">
      <div class="login-card shadow-lg animate-slide">
        <h4 class="fw-semibold mb-3 text-center">Masuk ke Akun Anda</h4>
        <p class="text-muted text-center mb-4 small">Gunakan kredensial Anda untuk login</p>

        <form method="POST" action="{{ route('login') }}">
          @csrf
          <div class="mb-3">
            <label for="email" class="form-label text-muted">Email</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="bi bi-envelope"></i></span>
              <input type="email" id="email" name="email" class="form-control" placeholder="Masukan Email Anda" required autofocus>
            </div>
          </div>

          <div class="mb-4">
            <label for="password" class="form-label text-muted">Kata Sandi</label>
            <div class="input-group input-group-lg">
              <span class="input-group-text"><i class="bi bi-lock"></i></span>
              <input type="password" id="password" name="password" class="form-control" placeholder="Masukan Kata Sandi Anda" required>
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">Masuk Sekarang</button>
        </form>

        <div class="text-center mt-4">
          <p class="text-muted small mb-0">© {{ date('Y') }} SIMPel</p>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
