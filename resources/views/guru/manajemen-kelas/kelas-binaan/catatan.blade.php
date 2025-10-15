@extends('layouts.guru')
@section('title','Catatan Perilaku - Kelas Binaan')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/guru/manajemen-siswa/catatan.css') }}">
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
        <a href="#" class="btn-add">Tambah Catatan</a>
    </div>

    <!-- Daftar Catatan -->
    <div class="timeline">
        @php
            $catatan = [
                ['nama'=>'Rizky Ahmad','jenis'=>'Prestasi','deskripsi'=>'Menang lomba matematika tingkat kabupaten','tanggal'=>'12 Maret 2025'],
                ['nama'=>'Siti Rahma','jenis'=>'Perilaku','deskripsi'=>'Membantu teman saat ujian praktek','tanggal'=>'8 Maret 2025'],
                ['nama'=>'Budi Santoso','jenis'=>'Peringatan','deskripsi'=>'Sering datang terlambat ke sekolah','tanggal'=>'5 Maret 2025'],
            ];
        @endphp

        @foreach($catatan as $note)
        <div class="timeline-item shadow-sm">
            <div class="timeline-marker {{ strtolower($note['jenis']) }}"></div>
            <div class="timeline-content">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <h5 class="fw-semibold text-dark mb-0">{{ $note['nama'] }}</h5>
                    <span class="badge-jenis {{ strtolower($note['jenis']) }}">{{ $note['jenis'] }}</span>
                </div>
                <p class="text-muted small mb-1">{{ $note['deskripsi'] }}</p>
                <span class="tanggal">{{ $note['tanggal'] }}</span>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection
