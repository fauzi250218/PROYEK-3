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
        <p class="text-muted">Perbarui data nilai untuk siswa: 
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
                    @foreach($mapelList as $mapel)
                        <option value="{{ $mapel }}" {{ $nilai->mata_pelajaran == $mapel ? 'selected' : '' }}>
                            {{ $mapel }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Jenis Nilai --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Keterangan</label>
                <select name="keterangan" class="form-select custom-input" required>
                    <option value="tugas" {{ $nilai->tugas ? 'selected' : '' }}>Tugas</option>
                    <option value="ulangan_harian" {{ $nilai->ulangan_harian ? 'selected' : '' }}>Ulangan Harian</option>
                    <option value="uts" {{ $nilai->uts ? 'selected' : '' }}>UTS</option>
                    <option value="uas" {{ $nilai->uas ? 'selected' : '' }}>UAS</option>
                </select>
            </div>

            {{-- Nilai --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Nilai</label>
                <input 
                    type="number" 
                    name="nilai" 
                    class="form-control custom-input" 
                    value="{{ $nilai->tugas ?? $nilai->ulangan_harian ?? $nilai->uts ?? $nilai->uas }}" 
                    min="0" max="100" 
                    required
                    placeholder="Masukkan nilai (0 - 100)">
            </div>

            {{-- Tombol --}}
            <div class="d-flex justify-content-start gap-3">
                <button type="submit" class="btn btn-save">
                    <i class="bi bi-arrow-repeat me-1"></i> Perbarui Nilai
                </button>
                <a href="{{ route('guru.nilai.detail', $nilai->murid_id) }}" class="btn btn-cancel">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>
        </form>
    </div>

</div>
@endsection
