<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title','Dashboard Guru')</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css/guru/guru.css') }}">
  @yield('extra-css')
</head>

<body class="guru">
<div class="d-flex" id="app">
  <!-- Sidebar -->
  <nav class="sidebar" id="sidebar">
    <div class="logo-wrapper text-center">
      <div class="logo-circle bg-white mx-auto mb-2">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="img-fluid">
      </div>
      <h6 class="logo-title">Guru</h6>
      <hr class="sidebar-divider">
    </div>

    <ul class="nav flex-column">

      <!-- Dashboard -->
      <li class="nav-item">
        <a class="nav-link main-link {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}" 
           href="{{ route('guru.dashboard') }}">
          <i class="bi bi-house-fill me-2"></i> Beranda
        </a>
      </li>

      <!-- Manajemen Kelas -->
      <li class="nav-item">
        <a class="nav-link main-link d-flex justify-content-between align-items-center
           {{ request()->routeIs('guru.kelas.*') ? 'active' : '' }}"
           data-bs-toggle="collapse" href="#kelasMenu"
           aria-expanded="{{ request()->routeIs('guru.kelas.*') ? 'true' : 'false' }}"
           aria-controls="kelasMenu">
          <span><i class="bi bi-building me-2"></i> Manajemen Kelas</span>
          <i class="bi bi-caret-down-fill small caret-icon"></i>
        </a>

        <div class="collapse {{ request()->routeIs('guru.kelas.*') ? 'show' : '' }} submenu" id="kelasMenu">
          <ul class="nav flex-column mt-1">
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('guru.kelas.binaan.*') ? 'active' : '' }}"
                 href="{{ route('guru.kelas.binaan.index') }}">
                <i class="bi bi-circle me-2"></i> Kelas Binaan
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('guru.kelas.ajaran.*') ? 'active' : '' }}"
                 href="{{ route('guru.kelas.ajaran.index') }}">
                <i class="bi bi-circle me-2"></i> Kelas Ajaran
              </a>
            </li>
          </ul>
        </div>
      </li>

      <!-- ✅ Manajemen Nilai (DIPERBAIKI DI SINI) -->
      <li class="nav-item">
        <a class="nav-link main-link {{ request()->routeIs('guru.nilai.*') ? 'active' : '' }}"
           href="{{ route('guru.nilai.semuaKelas') }}">
          <i class="bi bi-journal-check me-2"></i> Manajemen Nilai
        </a>
      </li>

      <!-- Pembayaran -->
      <li class="nav-item">
        <a class="nav-link main-link {{ request()->routeIs('guru.pembayaran.*') ? 'active' : '' }}" 
           href="{{ route('guru.pembayaran.index') }}">
          <i class="bi bi-cash-stack me-2"></i> Pembayaran
        </a>
      </li>

      <!-- Perkembangan -->
      <li class="nav-item">
        <a class="nav-link main-link {{ request()->routeIs('guru.perkembangan.*') ? 'active' : '' }}" 
           href="{{ route('guru.perkembangan.index') }}">
          <i class="bi bi-bar-chart-line me-2"></i> Perkembangan
        </a>
      </li>

      <!-- Obrolan -->
      <li class="nav-item">
        <a class="nav-link main-link {{ request()->routeIs('guru.obrolan.*') ? 'active' : '' }}" 
           href="{{ route('guru.obrolan.index') }}">
          <i class="bi bi-chat-dots-fill me-2"></i> Obrolan
        </a>
      </li>
    </ul>
  </nav>

  <!-- Main Content -->
  <div class="flex-grow-1 p-4">
    <div class="d-flex justify-content-between align-items-center mb-4 header-top">
      <div class="d-flex align-items-center">
        <button class="sidebar-toggle-btn me-3" id="sidebarToggle" aria-label="Toggle sidebar">
          <i class="bi bi-list"></i>
        </button>
        <div>
          <h4 class="mb-0">Selamat datang, {{ Auth::user()->name ?? 'Guru' }}</h4>
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

    <!-- Page Content -->
    @yield('content')
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Sidebar JS -->
<script src="{{ asset('js/guru/sidebar.js') }}"></script>

<!-- Extra JS per halaman -->
@yield('extra-js')

</body>
</html>
