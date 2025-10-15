@extends('layouts.guru')
@section('title','Laporan Kelas - Kelas Binaan')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/guru/manajemen-siswa/laporan.css') }}">
@endsection

@section('content')
<div class="laporan-modern container-fluid py-4">

    <!-- Header -->
    <div class="section-header align-with-header mb-4">
        <h3 class="fw-bold text-dark mb-1">Laporan Kelas Binaan</h3>
        <p class="text-muted">Analisis perkembangan kelas Anda dalam satu tampilan interaktif dan informatif.</p>
    </div>

    <!-- Statistik Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3 col-sm-6">
            <div class="stat-card bg-gradient-green">
                <i class="bi bi-mortarboard-fill icon"></i>
                <div>
                    <h6 class="label">Nama Kelas</h6>
                    <h4 class="value">IX-B</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card bg-gradient-blue">
                <i class="bi bi-people-fill icon"></i>
                <div>
                    <h6 class="label">Jumlah Siswa</h6>
                    <h4 class="value">32</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card bg-gradient-yellow">
                <i class="bi bi-calendar-check-fill icon"></i>
                <div>
                    <h6 class="label">Kehadiran</h6>
                    <h4 class="value">95%</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="stat-card bg-gradient-red">
                <i class="bi bi-bar-chart-fill icon"></i>
                <div>
                    <h6 class="label">Nilai Rata-rata</h6>
                    <h4 class="value">89</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Section -->
    <div class="row g-4 align-items-stretch">
        <div class="col-lg-8">
            <div class="progress-card shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-3 text-success"><i class="bi bi-graph-up-arrow me-2"></i>Performa Akademik Siswa</h5>

                <div class="progress-row">
                    <span class="label">Kognitif</span>
                    <div class="progress"><div class="progress-bar bg-success" style="width: 88%"></div></div>
                    <span class="percent">88%</span>
                </div>
                <div class="progress-row">
                    <span class="label">Motorik</span>
                    <div class="progress"><div class="progress-bar bg-info" style="width: 85%"></div></div>
                    <span class="percent">85%</span>
                </div>
                <div class="progress-row">
                    <span class="label">Sosial-Emosional</span>
                    <div class="progress"><div class="progress-bar bg-warning" style="width: 90%"></div></div>
                    <span class="percent">90%</span>
                </div>
                <div class="progress-row">
                    <span class="label">Bahasa</span>
                    <div class="progress"><div class="progress-bar bg-danger" style="width: 92%"></div></div>
                    <span class="percent">92%</span>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="summary shadow-sm rounded-4 p-4 text-center">
                <div class="circle-chart mx-auto mb-3">
                    <div class="circle-inner">
                        <span class="fw-bold fs-4 text-success">95%</span>
                        <small>Kehadiran</small>
                    </div>
                </div>
                <h6 class="fw-semibold text-dark">Rata-rata Kehadiran Kelas</h6>
                <p class="text-muted small">Mayoritas siswa aktif dan hadir secara konsisten sepanjang semester.</p>
            </div>
        </div>
    </div>

    <!-- Catatan Umum -->
    <div class="summary-card mt-5 p-4 rounded-4 shadow-sm">
        <h5 class="fw-bold mb-3 text-success"><i class="bi bi-journal-text me-2"></i>Catatan Umum</h5>
        <p class="text-secondary">
            Kelas IX-B menunjukkan peningkatan akademik dan perilaku yang positif. 
            Kehadiran sangat baik dan partisipasi siswa meningkat pada kegiatan sekolah. 
            Diharapkan konsistensi ini terus dipertahankan di semester berikutnya.
        </p>
    </div>

    <!-- Tombol Unduh -->
    <div class="text-end mt-4">
        <button class="btn-modern-download">
            <i class="bi bi-file-earmark-arrow-down-fill me-2"></i>Unduh Laporan (PDF)
        </button>
    </div>

</div>
@endsection
