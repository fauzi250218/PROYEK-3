<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title','Admin Dashboard')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @yield('extra-css')
  <script>
    (function() {
      var state = localStorage.getItem("sidebarState");
      if (state === "collapsed") {
        document.documentElement.classList.add("sidebar-init-collapsed");
      }
      if (state === "open") {
        document.documentElement.classList.add("sidebar-init-open");
      }
    })();
  </script>
</head>
<body>

<div class="d-flex" id="app">
  <!-- Sidebar -->
  <nav class="sidebar" id="sidebar">
    <div class="logo-wrapper text-center">
      <div class="logo-circle bg-white mx-auto mb-2">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid">
      </div>
      <h6 class="logo-title">Admin</h6>
      <hr class="sidebar-divider">
    </div>

    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link @if(request()->routeIs('admin.dashboard')) active @endif" href="{{ route('admin.dashboard') }}">
          <i class="bi bi-house-fill"></i>
          <span class="link-text ms-2">Beranda</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link @if(request()->is('admin/guru*')) active @endif" href="{{ route('admin.guru.index') }}">
          <i class="bi bi-person-badge"></i>
          <span class="link-text ms-2">Guru</span>
        </a>
      </li>
          <li class="nav-item">
        <a class="nav-link @if(request()->is('admin/murid*')) active @endif" href="{{ route('admin.murid.index') }}">
          <i class="bi bi-person-badge"></i>
          <span class="link-text ms-2">Siswa</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="bi bi-bank"></i>
          <span class="link-text ms-2">Pembayaran</span>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#">
          <i class="bi bi-gear-fill"></i>
          <span class="link-text ms-2">Pengaturan</span>
        </a>
      </li>
    </ul>
  </nav>

  <!-- Overlay (mobile only) -->
  <div class="overlay" id="sidebarOverlay"></div>

  <!-- Main Content -->
  <div class="flex-grow-1 p-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 header-top">
      <div class="d-flex align-items-center">
        <!-- Hamburger -->
        <button class="btn btn-outline-primary me-3" id="sidebarToggle">
          <i class="bi bi-list"></i>
        </button>
        <div>
          <h4 class="mb-0">Selamat datang, {{ Auth::user()->name ?? 'Admin' }}</h4>
          <small class="text-muted">SMP Negeri 1 Krasak</small>
        </div>
      </div>

      <div class="d-flex align-items-center">
        <!-- Notifikasi -->
        <button class="btn btn-outline-secondary me-3 position-relative">
          <i class="bi bi-bell-fill"></i>
          <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">3</span>
        </button>
        <!-- Logout -->
        <form action="{{ route('logout') }}" method="POST" class="ms-2">
          @csrf
          <button type="submit" class="btn btn-primary">Keluar</button>
        </form>
      </div>
    </div>

    <!-- Konten Halaman -->
    @yield('content')
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/admin.js') }}"></script>
</body>
</html>
