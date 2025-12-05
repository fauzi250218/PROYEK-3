@extends('layouts.guru')
@section('title', 'Kelas Ajaran')

@section('extra-css')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/guru/manajemen-kelas/kelas-ajaran/index.css') }}">
@endsection

@section('content')

<div class="container-fluid py-5 semua-kelas-wrapper">

    <!-- JUDUL HALAMAN -->
    <div class="text-start mb-4">
        <h2 class="title-page fw-bold mb-2">
            <i class="bi bi-people-fill text-success me-2"></i>Kelas Ajaran
        </h2>
        <p class="subtitle">Daftar kelas yang Anda ajarkan pada tahun akademik ini.</p>
    </div>

    @php
        // Pastikan variabel terdefinisi
        $kelasAjaran = $kelasAjaran ?? [];

        // Mengelompokkan kelas berdasarkan angka di nama kelas (misal: 7A -> 7)
        $kelompok = [];

        foreach ($kelasAjaran as $k) {
            if (!isset($k['kelas'])) continue;

            preg_match('/\d+/', $k['kelas'], $match);
            $level = $match[0] ?? null;

            if ($level) {
                $kelompok[$level][] = $k;
            }
        }
    @endphp

    {{-- EMPTY STATE --}}
    @if(empty($kelasAjaran) || empty($kelompok))
        <div class="empty-state text-center mt-5">
            <i class="bi bi-emoji-neutral display-6 d-block mb-2 text-secondary"></i>
            <p class="fw-semibold text-muted">Belum ada kelas yang Anda ajarkan tahun ini.</p>
        </div>
    @else

        {{-- LOOP KELAS PER GRADE --}}
        @foreach([7, 8, 9] as $grade)

            @if(isset($kelompok[$grade]) && count($kelompok[$grade]) > 0)

                <h4 class="fw-bold mb-3 ms-1">Kelas {{ $grade }}</h4>

                <div class="kelas-tiles">

                    @foreach($kelompok[$grade] as $kelas)
                        <a href="{{ route('guru.kelas.ajaran.detail', $kelas['id']) }}"
                           class="kelas-tile">

                            <div class="tile-overlay"></div>

                            <div class="tile-icon">
                                <i class="bi bi-people-fill"></i>
                            </div>

                            <div class="tile-content">
                                <h5 class="nama-kelas">{{ $kelas['kelas'] }}</h5>

                                <p class="wali">
                                    <strong>Wali:</strong> {{ $kelas['guru'] ?? 'Tidak ada data guru' }}
                                </p>

                                <p class="desc">
                                    {{ $kelas['deskripsi'] ?? 'Tidak ada deskripsi kelas.' }}
                                </p>
                            </div>

                        </a>
                    @endforeach

                </div>

            @endif

        @endforeach

    @endif

</div>

@endsection
