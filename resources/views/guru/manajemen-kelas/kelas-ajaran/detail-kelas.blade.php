@extends('layouts.guru')
@section('title', 'Detail Kelas Ajaran')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/guru/manajemen-kelas/kelas-ajaran/detail.css') }}">
@endsection

@section('content')
<div class="container py-3">

    <!-- HEADER KELAS -->
    <div class="detail-header shadow-sm p-4 mb-4 rounded-4 bg-white">

        <h4 class="fw-bold mb-1">{{ $detail['nama_kelas'] }}</h4>
        <p class="text-muted mb-2">{{ $detail['mapel'] }}</p>

        <div class="small mb-1 text-secondary">
            <i class="bi bi-person-fill me-2"></i>Guru: {{ $detail['guru'] }}
        </div>

        <div class="small mb-1 text-secondary">
            <i class="bi bi-shield-check me-2"></i>Wali Kelas: {{ $detail['wali_kelas'] }}
        </div>

        <div class="small mb-3 text-secondary">
            <i class="bi bi-people-fill me-2"></i>Jumlah Siswa: {{ $detail['jumlah_siswa'] }}
        </div>

    </div>

    <!-- HEADER MODUL -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold">Modul & Sesi Pembelajaran</h5>

        <div class="d-flex gap-2">
            <button class="btn btn-primary px-4" id="btnUploadModul">
                <i class="bi bi-upload me-1"></i> Upload Modul
            </button>

            <button class="btn btn-success px-4" id="btnTambahSesi">
                <i class="bi bi-plus-circle me-1"></i> Tambah Sesi
            </button>
        </div>
    </div>

    <!-- DAFTAR SESI -->
    <div class="row g-3">

        @for ($i = 1; $i <= 3; $i++)
        <div class="col-md-4">
            <div class="sesi-card shadow-sm rounded-4 bg-white">

                <div class="sesi-content">
                    <p class="sesi-title fw-bold">SESI {{ $i }}</p>

                    <h6 class="fw-bold sesi-mapel">Biologi Etanol</h6>

                    <div class="small text-secondary d-flex align-items-center mb-1">
                        <i class="bi bi-person-fill me-2"></i>
                        Bahlil Lahadalia
                    </div>

                    <div class="small text-secondary d-flex align-items-center">
                        <i class="bi bi-calendar-event me-2"></i>
                        Senin, 07.30 – 09.00 WIB
                    </div>
                </div>

                <div class="text-end mt-2">
                    <a href="#" class="presensi-link">Presensi</a>
                </div>

            </div>
        </div>
        @endfor

    </div>

</div>
@endsection
