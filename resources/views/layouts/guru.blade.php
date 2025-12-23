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

  <!-- ================= SIDEBAR ================= -->
  <nav class="sidebar" id="sidebar">

    {{-- ================= FOTO PROFIL (DIPERBAIKI) ================= --}}
    @php
        $guru     = Auth::user()->guru ?? null;
        $foto     = $guru->foto_profil ?? null;
        $nama     = $guru->nama_lengkap ?? Auth::user()->name ?? 'Guru';
        $inisial  = strtoupper(substr($nama, 0, 1));
    @endphp

    <div class="logo-wrapper text-center">
      <div class="profile-circle mx-auto mb-2">
        @if($foto)
          <img src="{{ asset('storage/'.$foto) }}" alt="Foto Guru">
        @else
          <span>{{ $inisial }}</span>
        @endif
      </div>

      <h6 class="logo-title">{{ $nama }}</h6>
      <hr class="sidebar-divider">
    </div>
    {{-- ================= END FOTO PROFIL ================= --}}

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

      <!-- Manajemen Nilai -->
      <li class="nav-item">
        <a class="nav-link main-link {{ request()->routeIs('guru.nilai.*') ? 'active' : '' }}"
           href="{{ route('guru.nilai.semuaKelas') }}">
          <i class="bi bi-journal-check me-2"></i> Manajemen Nilai
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

  <!-- ================= MAIN CONTENT ================= -->
  <div class="flex-grow-1 p-4">

    <div class="d-flex justify-content-between align-items-center mb-4 header-top">
      <div class="d-flex align-items-center">
        <button class="sidebar-toggle-btn me-3" id="sidebarToggle">
          <i class="bi bi-list"></i>
        </button>
        <div>
          <h4 class="mb-0">Selamat datang, {{ $nama }}</h4>
          <small class="text-muted">SMP Negeri 1 Mars</small>
        </div>
      </div>

      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary">Keluar</button>
      </form>
    </div>

    @yield('content')
  </div>

</div>

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/guru/sidebar.js') }}"></script>
@yield('extra-js')

</body>
</html>
