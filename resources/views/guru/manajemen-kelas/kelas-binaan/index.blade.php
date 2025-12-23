@extends('layouts.guru')
@section('title','Kelas Binaan')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/guru/manajemen-siswa/kelas-binaan.css') }}">
@endsection

@section('content')
<div class="kelas-binaan-wrapper container-fluid py-4">

    {{-- HEADER --}}
    <div class="section-header mb-4 kelas-header-left">
        <h3 class="fw-bold text-dark mb-1">Kelas Binaan Saya</h3>
        <p class="text-muted mb-0">
            Kelola data siswa dan perkembangan belajar di kelas Anda.
        </p>
    </div>

    {{-- EMPTY STATE POLOS --}}
    @if($kelasBinaan->isEmpty())

        <div class="empty-neutral">
            <i class="bi bi-emoji-neutral"></i>
            <p class="fw-semibold mb-0">
                Belum ada kelas yang Anda ajarkan tahun ini.
            </p>
        </div>

    @else

    {{-- DAFTAR KELAS --}}
    @foreach($kelasBinaan as $kelas)
    <div class="menu-list mb-4 p-3 border rounded shadow-sm bg-white">

        <a href="{{ route('guru.kelas.binaan.dataSiswa', ['id' => $kelas->id]) }}" class="menu-item">
            <div class="menu-icon bg-primary-subtle text-primary">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="menu-text">
                <h5>Data Siswa</h5>
                <p>Daftar siswa kelas {{ $kelas->nama_kelas }}.</p>
            </div>
        </a>

        <a href="{{ route('guru.kelas.binaan.perkembangan.index', ['id' => $kelas->id]) }}" class="menu-item">
            <div class="menu-icon bg-success-subtle text-success">
                <i class="bi bi-bar-chart-line"></i>
            </div>
            <div class="menu-text">
                <h5>Perkembangan Belajar</h5>
                <p>Pantau perkembangan belajar siswa.</p>
            </div>
        </a>

        <a href="{{ route('guru.kelas.binaan.kehadiran', ['id' => $kelas->id]) }}" class="menu-item">
            <div class="menu-icon bg-warning-subtle text-warning">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div class="menu-text">
                <h5>Rekap Kehadiran</h5>
                <p>Lihat data kehadiran siswa.</p>
            </div>
        </a>

        <a href="{{ route('guru.kelas.binaan.eraport.index', ['id' => $kelas->id]) }}" class="menu-item">
            <div class="menu-icon bg-danger-subtle text-danger">
                <i class="bi bi-file-earmark-bar-graph"></i>
            </div>
            <div class="menu-text">
                <h5>E-Raport</h5>
                <p>Kelola nilai dan raport siswa.</p>
            </div>
        </a>

    </div>
    @endforeach

    @endif

</div>

{{-- CSS PENYESUAIAN --}}
<style>
/* HEADER AGAR SEJAJAR DENGAN BURGER */
.kelas-header-left{
    padding-left:6px; /* sesuaikan dengan posisi burger toggler */
}

/* EMPTY STATE */
.empty-neutral{
    margin-top:95px;
    text-align:center;
    color:#6b7280;
}

.empty-neutral i{
    font-size:40px; /* DIBESARKAN */
    display:block;
    margin-bottom:10px;
}

.empty-neutral p{
    font-size:1.05rem; /* DIBESARKAN */
}
</style>

@endsection
