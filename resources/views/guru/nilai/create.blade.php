@extends('layouts.guru')
@section('title', 'Tambah Nilai')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/guru/nilai/tambah-nilai.css') }}">
@endsection

@section('content')
<div class="container py-5 nilai-create-page">

    <div class="header-section mb-4">
        <h2 class="fw-bold text-dark mb-1">Tambah Nilai</h2>
        <p class="text-muted">Untuk siswa: <strong>{{ $murid->nama }}</strong></p>
    </div>

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

            {{-- Nilai Tugas --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Nilai Tugas</label>
                <input type="number" name="tugas" class="form-control custom-input" min="0" max="100" required placeholder="Masukkan nilai tugas">
            </div>

            {{-- Nilai Ulangan Harian --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Nilai Ulangan Harian</label>
                <input type="number" name="ulangan_harian" class="form-control custom-input" min="0" max="100" required placeholder="Masukkan nilai ulangan harian">
            </div>

            {{-- Nilai UTS --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Nilai UTS</label>
                <input type="number" name="uts" class="form-control custom-input" min="0" max="100" required placeholder="Masukkan nilai UTS">
            </div>

            {{-- Nilai UAS --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Nilai UAS</label>
                <input type="number" name="uas" class="form-control custom-input" min="0" max="100" required placeholder="Masukkan nilai UAS">
            </div>

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
