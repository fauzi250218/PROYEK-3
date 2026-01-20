@extends('layouts.guru')
@section('title', 'Manajemen Nilai - Semua Kelas')

@section('extra-css')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/guru/nilai/semua-kelas.css') }}">
@endsection

@section('content')
<div class="container-fluid py-5 semua-kelas-wrapper">
    <div class="text-start mb-4">
        <h2 class="title-page fw-bold mb-2">
            <i class="bi bi-mortarboard-fill text-success me-2"></i>Daftar Semua Kelas
        </h2>
        <p class="subtitle">Kelola nilai Pelajaran Siswa.</p>
    </div>

    @if($kelasList->isEmpty())
        <div class="empty-state text-center mt-5">
            <i class="bi bi-emoji-neutral display-6 d-block mb-2 text-secondary"></i>
            <p class="fw-semibold text-muted">Belum ada data kelas yang tersedia.</p>
        </div>
    @else
        <div class="kelas-tiles">
            @foreach($kelasList as $kelas)
            <a href="{{ route('guru.nilai.index', ['id' => $kelas->id]) }}" class="kelas-tile">
                <div class="tile-overlay"></div>
                <div class="tile-icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="tile-content">
                    <h5 class="nama-kelas">{{ $kelas->nama_kelas }}</h5>
                    <p class="wali"><strong>Wali:</strong> {{ $kelas->guru->user->name ?? '-' }}</p>
                    <p class="desc">{{ $kelas->deskripsi ?? 'Tidak ada deskripsi kelas.' }}</p>
                </div>
            </a>
            @endforeach
        </div>
    @endif
</div>
@endsection

@section('extra-js')
<script src="{{ asset('js/guru/nilai/semua-kelas.js') }}"></script>
@endsection
