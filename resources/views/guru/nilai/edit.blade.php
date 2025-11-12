@extends('layouts.guru')
@section('title', 'Edit Nilai')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/guru/nilai/edit-nilai.css') }}">
@endsection

@section('content')
<div class="container py-5 nilai-edit-page">

    <!-- Header -->
    <div class="header-section mb-4">
        <h2 class="fw-bold text-dark mb-1">Edit Nilai</h2>
        <p class="text-muted">
            Perbarui data nilai untuk siswa: 
            <strong>{{ $nilai->murid->nama ?? 'Tidak Diketahui' }}</strong>
        </p>
    </div>

    <!-- Card Form -->
    <div class="form-card shadow-card p-4">
        <form action="{{ route('guru.nilai.update', $nilai->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Mata Pelajaran --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Mata Pelajaran</label>
                <select name="mata_pelajaran" class="form-select custom-input" required>
                    <option value="">-- Pilih Mata Pelajaran --</option>
                    @foreach($mapelList as $mapel)
                        <option value="{{ $mapel }}" {{ $nilai->mata_pelajaran == $mapel ? 'selected' : '' }}>
                            {{ $mapel }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Nilai Tugas --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Nilai Tugas</label>
                <input type="number" name="tugas" class="form-control custom-input" 
                    value="{{ $nilai->tugas }}" min="0" max="100" required placeholder="Masukkan nilai tugas">
            </div>

            {{-- Nilai Ulangan Harian --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Nilai Ulangan Harian</label>
                <input type="number" name="ulangan_harian" class="form-control custom-input" 
                    value="{{ $nilai->ulangan_harian }}" min="0" max="100" required placeholder="Masukkan nilai ulangan harian">
            </div>

            {{-- Nilai UTS --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Nilai UTS</label>
                <input type="number" name="uts" class="form-control custom-input" 
                    value="{{ $nilai->uts }}" min="0" max="100" required placeholder="Masukkan nilai UTS">
            </div>

            {{-- Nilai UAS --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Nilai UAS</label>
                <input type="number" name="uas" class="form-control custom-input" 
                    value="{{ $nilai->uas }}" min="0" max="100" required placeholder="Masukkan nilai UAS">
            </div>

            {{-- Tombol --}}
            <div class="d-flex justify-content-start gap-3">
                <button type="submit" class="btn btn-save">
                    <i class="bi bi-check-circle me-1"></i> Perbarui Nilai
                </button>
                <a href="{{ route('guru.nilai.detail', $nilai->murid_id) }}" class="btn btn-cancel">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
