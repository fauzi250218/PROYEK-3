@extends('layouts.guru')
@section('title','Catatan Perilaku - Kelas Binaan')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/guru/manajemen-kelas/catatan.css') }}">
@endsection

@section('content')
<div class="catatan-wrapper container-fluid py-4">

    <!-- Header -->
    <div class="section-header align-with-header mb-4">
        <h3 class="fw-bold text-dark mb-1">Catatan Perilaku / Kegiatan</h3>
        <p class="text-muted">Kelola catatan penting siswa seperti prestasi, perilaku, atau kegiatan harian.</p>
    </div>

    <!-- Tombol Tambah -->
    <div class="text-end mb-4">
        <a href="{{ route('guru.kelas.binaan.catatan', $kelas->id) }}" class="btn-add">
            <i class="bi bi-plus-circle me-1"></i> Tambah Catatan
        </a>
    </div>

    <!-- Daftar Catatan -->
    <div class="timeline">
        @forelse($catatan as $note)
        <div class="timeline-item shadow-sm">
            <div class="timeline-marker {{ strtolower($note->kategori) }}"></div>
            <div class="timeline-content">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h5 class="fw-semibold text-dark mb-0">{{ $note->murid->nama }}</h5>
                    <span class="badge-jenis {{ strtolower($note->kategori) }}">{{ $note->kategori }}</span>
                </div>
                <p class="text-muted small mb-1">{{ $note->deskripsi }}</p>
                <span class="tanggal">{{ \Carbon\Carbon::parse($note->tanggal)->translatedFormat('d F Y') }}</span>
            </div>
        </div>
        @empty
        <p class="text-muted">Belum ada catatan perilaku yang ditambahkan.</p>
        @endforelse
    </div>

</div>
@endsection
