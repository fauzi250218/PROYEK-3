<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title','Admin Dashboard')</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
  @yield('extra-css')
</head>

<body class="admin">
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
        <a class="nav-link main-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
           href="{{ route('admin.dashboard') }}">
          <i class="bi bi-house-fill me-2"></i> Beranda
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link main-link {{ request()->is('admin/guru*') ? 'active' : '' }}"
           href="{{ route('admin.guru.index') }}">
          <i class="bi bi-person-badge me-2"></i> Guru
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link main-link {{ request()->is('admin/murid*') ? 'active' : '' }}"
           href="{{ route('admin.murid.index') }}">
          <i class="bi bi-people-fill me-2"></i> Siswa
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link main-link {{ request()->routeIs('admin.kelas.*') ? 'active' : '' }}"
           href="{{ route('admin.kelas.index') }}">
          <i class="bi bi-building-fill me-2"></i> Data Kelas
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link main-link {{ request()->routeIs('admin.jadwal.*') ? 'active' : '' }}"
           href="{{ route('admin.jadwal.index') }}">
          <i class="bi bi-calendar-week me-2"></i> Jadwal Pelajaran
        </a>
      </li>

      <li class="nav-item">
        <a class="nav-link main-link {{ request()->routeIs('admin.pembayaran-spp.*') ? 'active' : '' }}"
          href="{{ route('admin.pembayaran-spp.index') }}">
          <i class="bi bi-bank me-2"></i> Pembayaran SPP
        </a>
      </li>

  </nav>

  <!-- Main Content -->
  <div class="flex-grow-1 p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 header-top">
      <div class="d-flex align-items-center">
        <button class="sidebar-toggle-btn me-3" id="sidebarToggle">
          <i class="bi bi-list"></i>
        </button>
        <div>
          <h4 class="mb-0">Selamat datang, {{ Auth::user()->name ?? 'Admin' }}</h4>
          <small class="text-muted">SMP Negeri 1 Mars</small>
        </div>
      </div>

      <div class="d-flex align-items-center">

        <form action="{{ route('logout') }}" method="POST" class="ms-2">
          @csrf
          <button type="submit" class="btn btn-primary">Keluar</button>
        </form>
      </div>
    </div>

    @yield('content')
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/admin/sidebar.js') }}"></script>
@yield('extra-js')

</body>
</html>
