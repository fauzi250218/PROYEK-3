@extends('layouts.guru')
@section('title', 'Kelas Ajaran')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/guru/manajemen-kelas/kelas-ajaran/index.css') }}">
@endsection

@section('content')

<div class="container py-3">

    <div class="section-header">
        <h4 class="fw-bold">Kelas Ajaran</h4>
        <p>Daftar kelas yang Anda ajarkan pada tahun akademik ini.</p>
    </div>

    @php
        $kelompok = [];
        foreach ($kelasAjaran as $k) {
            $level = substr($k['kelas'], 6, 1); // 7,8,9
            $kelompok[$level][] = $k;
        }
    @endphp

    <div class="kelas-3kolom">

        {{-- KOLOM: KELAS 7 --}}
        <div class="kelas-kolom">
            <h5 class="kelas-judul">Kelas 7</h5>

            @foreach ($kelompok[7] ?? [] as $kelas)
            <div class="kelas-card"
                 onclick="window.location='{{ route('guru.kelas.ajaran.detail', $kelas['id']) }}'">
                <h5>{{ $kelas['kelas'] }}</h5>
                <p class="mapel">{{ $kelas['mapel'] }}</p>
                <div class="guru-info">
                    <i class="bi bi-person-circle"></i>
                    {{ $kelas['guru'] ?? 'Guru Tidak Diketahui' }}
                </div>
            </div>
            @endforeach
        </div>

        {{-- KOLOM: KELAS 8 --}}
        <div class="kelas-kolom">
            <h5 class="kelas-judul">Kelas 8</h5>

            @foreach ($kelompok[8] ?? [] as $kelas)
            <div class="kelas-card"
                 onclick="window.location='{{ route('guru.kelas.ajaran.detail', $kelas['id']) }}'">
                <h5>{{ $kelas['kelas'] }}</h5>
                <p class="mapel">{{ $kelas['mapel'] }}</p>
                <div class="guru-info">
                    <i class="bi bi-person-circle"></i>
                    {{ $kelas['guru'] ?? 'Guru Tidak Diketahui' }}
                </div>
            </div>
            @endforeach
        </div>

        {{-- KOLOM: KELAS 9 --}}
        <div class="kelas-kolom">
            <h5 class="kelas-judul">Kelas 9</h5>

            @foreach ($kelompok[9] ?? [] as $kelas)
            <div class="kelas-card"
                 onclick="window.location='{{ route('guru.kelas.ajaran.detail', $kelas['id']) }}'">
                <h5>{{ $kelas['kelas'] }}</h5>
                <p class="mapel">{{ $kelas['mapel'] }}</p>
                <div class="guru-info">
                    <i class="bi bi-person-circle"></i>
                    {{ $kelas['guru'] ?? 'Guru Tidak Diketahui' }}
                </div>
            </div>
            @endforeach
        </div>

    </div>

</div>

@endsection
