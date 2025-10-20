@extends('layouts.guru')
@section('title', 'Tambah Nilai')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/guru/nilai/tambah-nilai.css') }}">
@endsection

@section('content')
<div class="container py-5 nilai-create-page">

    <!-- Header -->
    <div class="header-section mb-4">
        <h2 class="fw-bold text-dark mb-1">Tambah Nilai</h2>
        <p class="text-muted">Untuk siswa: <strong>{{ $murid->nama }}</strong></p>
    </div>

    <!-- Card Form -->
    <div class="form-card shadow-card p-4">
        <form action="{{ route('guru.nilai.store', $murid->id) }}" method="POST">
            @csrf

            {{-- Mata Pelajaran --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Mata Pelajaran</label>
                <select name="mata_pelajaran" class="form-select custom-input" required>
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach($mapelList as $mapel)
                        <option value="{{ $mapel }}">{{ $mapel }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Jenis Nilai --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Keterangan</label>
                <select name="keterangan" class="form-select custom-input" required>
                    <option value="">-- Pilih Jenis Nilai --</option>
                    <option value="tugas">Tugas</option>
                    <option value="ulangan_harian">Ulangan Harian</option>
                    <option value="uts">UTS</option>
                    <option value="uas">UAS</option>
                </select>
            </div>

            {{-- Nilai --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Nilai</label>
                <input type="number" name="nilai" class="form-control custom-input" min="0" max="100" required placeholder="Masukkan nilai (0 - 100)">
            </div>

            {{-- Tombol --}}
            <div class="d-flex justify-content-start gap-3">
                <button type="submit" class="btn btn-save">
                    <i class="bi bi-check-circle me-1"></i> Simpan Nilai
                </button>
                <a href="{{ route('guru.nilai.detail', $murid->id) }}" class="btn btn-cancel">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
