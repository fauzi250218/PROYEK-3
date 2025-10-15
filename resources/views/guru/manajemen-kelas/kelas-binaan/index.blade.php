@extends('layouts.guru')
@section('title','Kelas Binaan')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/guru/manajemen-siswa/kelas-binaan.css') }}">
@endsection

@section('content')
<div class="kelas-binaan-wrapper container-fluid py-4">

    <div class="section-header mb-5">
        <h3 class="fw-bold text-dark mb-1">Kelas Binaan Saya</h3>
        <p class="text-muted">Kelola data siswa, perkembangan belajar, dan laporan kelas Anda.</p>
    </div>

    @if($kelasBinaan->isEmpty())
        <div class="alert alert-warning">
            Anda belum memiliki kelas binaan.
        </div>
    @else
        @foreach($kelasBinaan as $kelas)
        <div class="menu-list mb-4 p-3 border rounded shadow-sm bg-white">

            <!-- Data Siswa -->
            <a href="{{ route('guru.kelas.binaan.dataSiswa', ['id' => $kelas->id]) }}" class="menu-item">
                <div class="menu-icon bg-primary-subtle text-primary">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="menu-text">
                    <h5>Data Siswa</h5>
                    <p>Lihat daftar lengkap siswa di kelas {{ $kelas->nama_kelas }}.</p>
                </div>
                <div class="menu-arrow text-primary">
                    <i class="bi bi-arrow-right-circle-fill"></i>
                </div>
            </a>

            <!-- Perkembangan Belajar -->
            <a href="{{ route('guru.kelas.binaan.perkembangan', ['id' => $kelas->id]) }}" class="menu-item">
                <div class="menu-icon bg-success-subtle text-success">
                    <i class="bi bi-bar-chart-line"></i>
                </div>
                <div class="menu-text">
                    <h5>Perkembangan Belajar</h5>
                    <p>Pantau kemajuan belajar siswa berdasarkan aspek perkembangan.</p>
                </div>
                <div class="menu-arrow text-success">
                    <i class="bi bi-arrow-right-circle-fill"></i>
                </div>
            </a>

            <!-- Kehadiran -->
            <a href="{{ route('guru.kelas.binaan.kehadiran', ['id' => $kelas->id]) }}" class="menu-item">
                <div class="menu-icon bg-warning-subtle text-warning">
                    <i class="bi bi-calendar-check"></i>
                </div>
                <div class="menu-text">
                    <h5>Rekap Kehadiran</h5>
                    <p>Lihat statistik kehadiran siswa di kelas {{ $kelas->nama_kelas }}.</p>
                </div>
                <div class="menu-arrow text-warning">
                    <i class="bi bi-arrow-right-circle-fill"></i>
                </div>
            </a>

            <!-- Catatan -->
            <a href="{{ route('guru.kelas.binaan.catatan', ['id' => $kelas->id]) }}" class="menu-item">
                <div class="menu-icon bg-info-subtle text-info">
                    <i class="bi bi-journal-text"></i>
                </div>
                <div class="menu-text">
                    <h5>Catatan Perilaku</h5>
                    <p>Lihat atau tambahkan catatan perilaku serta prestasi siswa.</p>
                </div>
                <div class="menu-arrow text-info">
                    <i class="bi bi-arrow-right-circle-fill"></i>
                </div>
            </a>

            <!-- Laporan -->
            <a href="{{ route('guru.kelas.binaan.laporan', ['id' => $kelas->id]) }}" class="menu-item">
                <div class="menu-icon bg-danger-subtle text-danger">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <div class="menu-text">
                    <h5>Laporan Kelas</h5>
                    <p>Rekap data perkembangan, absensi, dan catatan siswa Anda.</p>
                </div>
                <div class="menu-arrow text-danger">
                    <i class="bi bi-arrow-right-circle-fill"></i>
                </div>
            </a>
        </div>
        @endforeach
    @endif

</div>
@endsection
