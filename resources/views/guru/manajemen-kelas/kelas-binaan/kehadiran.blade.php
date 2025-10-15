@extends('layouts.guru')
@section('title','Rekap Kehadiran - Kelas Binaan')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/guru/manajemen-siswa/kehadiran.css') }}">
@endsection

@section('content')
<div class="kehadiran-wrapper container-fluid py-4">

    <!-- Header -->
    <div class="section-header align-with-header mb-4">
        <h3 class="fw-bold text-dark mb-1">Rekap Kehadiran Siswa</h3>
        <p class="text-muted">Lihat data kehadiran siswa di kelas binaan dengan tampilan ringkas dan interaktif.</p>
    </div>

    <!-- Statistik Atas -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <h6>Total Siswa</h6>
                <h3>32</h3>
                <p><i class="bi bi-people-fill"></i> siswa terdaftar</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <h6>Rata-rata Kehadiran</h6>
                <h3 class="text-success">95%</h3>
                <p><i class="bi bi-graph-up"></i> bulan Oktober</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <h6>Periode Aktif</h6>
                <h3>Oktober 2025</h3>
                <p><i class="bi bi-calendar-event"></i> semester ganjil</p>
            </div>
        </div>
    </div>

    <!-- Filter Bulan -->
    <div class="filter-bar d-flex flex-wrap align-items-center mb-4">
        <select class="form-select bulan-select me-2">
            <option>Bulan Ini</option>
            <option>Januari</option>
            <option>Februari</option>
            <option>Maret</option>
        </select>
        <button class="btn-filter">Tampilkan</button>
    </div>

    <!-- Grid Card Kehadiran -->
    <div class="row g-4">
        @php
            $data = [
                ['nama'=>'Rizky Ahmad','hadir'=>20,'sakit'=>1,'izin'=>1,'alpa'=>0],
                ['nama'=>'Siti Rahma','hadir'=>19,'sakit'=>2,'izin'=>0,'alpa'=>1],
                ['nama'=>'Budi Santoso','hadir'=>18,'sakit'=>1,'izin'=>2,'alpa'=>1],
                ['nama'=>'Dewi Lestari','hadir'=>22,'sakit'=>0,'izin'=>1,'alpa'=>0],
            ];
        @endphp

        @foreach($data as $row)
        @php
            $total = $row['hadir'] + $row['sakit'] + $row['izin'] + $row['alpa'];
            $persen = round(($row['hadir'] / $total) * 100);
        @endphp
        <div class="col-md-3 col-sm-6">
            <div class="attendance-card shadow-sm">
                <div class="header d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0">{{ $row['nama'] }}</h6>
                    <span class="badge bg-success">{{ $persen }}%</span>
                </div>

                <div class="progress mt-3" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: {{ $persen }}%;"></div>
                </div>

                <div class="mt-3 d-flex justify-content-between small text-muted">
                    <span>Hadir: <b>{{ $row['hadir'] }}</b></span>
                    <span>Sakit: <b>{{ $row['sakit'] }}</b></span>
                </div>
                <div class="d-flex justify-content-between small text-muted">
                    <span>Izin: <b>{{ $row['izin'] }}</b></span>
                    <span>Alpa: <b>{{ $row['alpa'] }}</b></span>
                </div>

                <div class="footer mt-3 text-end">
                    <button class="btn-detail">Detail</button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection
