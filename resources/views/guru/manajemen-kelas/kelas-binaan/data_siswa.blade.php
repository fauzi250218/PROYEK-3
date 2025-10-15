@extends('layouts.guru')
@section('title', 'Kelas Binaan Saya')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/guru/manajemen-siswa/kelas-binaan.css') }}">
@endsection

@section('content')
<div class="kelas-binaan-wrapper container-fluid py-4">

    <!-- Header -->
    <div class="section-header mb-5">
        <h3 class="fw-bold text-dark mb-1">Kelas Binaan Saya</h3>
        <p class="text-muted">Kelola data siswa, perkembangan belajar, dan laporan kelas Anda.</p>
    </div>

    <!-- Cek apakah guru punya kelas binaan -->
    @if(isset($kelasBinaan) && $kelasBinaan->count() > 0)
        <div class="row">
            @foreach($kelasBinaan as $kelas)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-body">

                            <!-- Nama Kelas -->
                            <h5 class="card-title text-primary fw-bold mb-1">
                                {{ $kelas->nama_kelas }}
                            </h5>

                            <!-- Nama Wali Kelas -->
                            <p class="text-muted small mb-2">
                                Wali Kelas: <span class="fw-semibold">{{ $kelas->wali->name ?? '-' }}</span>
                            </p>

                            <!-- Deskripsi -->
                            @if(!empty($kelas->deskripsi))
                                <p class="text-muted small">{{ $kelas->deskripsi }}</p>
                            @endif

                            <hr>

                            <!-- Tombol Navigasi -->
                            <div class="d-flex flex-column gap-2">
                                <a href="{{ route('guru.kelas.binaan.dataSiswa', ['id' => $kelas->id]) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-people-fill me-1"></i> Data Siswa
                                </a>

                                <a href="{{ route('guru.kelas.binaan.perkembangan', ['id' => $kelas->id]) }}"
                                   class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-bar-chart-line me-1"></i> Perkembangan Belajar
                                </a>

                                <a href="{{ route('guru.kelas.binaan.kehadiran', ['id' => $kelas->id]) }}"
                                   class="btn btn-sm btn-outline-warning">
                                    <i class="bi bi-calendar-check me-1"></i> Rekap Kehadiran
                                </a>

                                <a href="{{ route('guru.kelas.binaan.catatan', ['id' => $kelas->id]) }}"
                                   class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-journal-text me-1"></i> Catatan Perilaku
                                </a>

                                <a href="{{ route('guru.kelas.binaan.laporan', ['id' => $kelas->id]) }}"
                                   class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-file-earmark-text me-1"></i> Laporan Kelas
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-warning text-center rounded-3">
            Anda belum memiliki kelas binaan.
        </div>
    @endif

</div>
@endsection
