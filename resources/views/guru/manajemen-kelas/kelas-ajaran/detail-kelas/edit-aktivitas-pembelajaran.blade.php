@extends('layouts.guru')

@section('title', 'Edit Aktivitas Pembelajaran')

@section('content')

<div class="container py-3">

    <div class="card shadow-sm p-4 rounded-4">

        <h4 class="fw-bold mb-3">Edit Aktivitas Pembelajaran</h4>

        <form action="{{ route('guru.kelas.ajaran.sesi.update', $sesi->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">

            {{-- Judul --}}
            <label class="fw-semibold small">Judul Sesi</label>
            <input 
                type="text"
                name="judul_sesi"
                class="form-control mb-3"
                value="{{ $sesi->judul_sesi }}"
                required
            >

            {{-- Topik --}}
            <label class="fw-semibold small">Topik</label>
            <input 
                type="text"
                name="topik"
                class="form-control mb-3"
                placeholder="Tuliskan topik..."
                value="{{ $sesi->topik }}"
            >

            {{-- Tujuan Pembelajaran (TEXTAREA BIASA) --}}
            <label class="fw-semibold small">Tujuan Pembelajaran</label>
            <textarea 
                name="deskripsi"
                class="form-control mb-3"
                rows="5"
                placeholder="Tuliskan tujuan pembelajaran..."
            >{{ $sesi->deskripsi }}</textarea>

            {{-- Tanggal --}}
            <label class="fw-semibold small">Tanggal Sesi</label>
            <input 
                type="date" 
                name="tanggal"
                class="form-control mb-3"
                value="{{ $sesi->tanggal }}"
                required
            >

            {{-- Jam --}}
            <div class="row">
                <div class="col-6 mb-3">
                    <label class="fw-semibold small">Jam Mulai</label>
                    <input 
                        type="time"
                        name="jam_mulai"
                        class="form-control"
                        value="{{ $sesi->jam_mulai }}"
                        required
                    >
                </div>

                <div class="col-6 mb-3">
                    <label class="fw-semibold small">Jam Selesai</label>
                    <input 
                        type="time"
                        name="jam_selesai"
                        class="form-control"
                        value="{{ $sesi->jam_selesai }}"
                        required
                    >
                </div>
            </div>

            {{-- Aksi --}}
            <div class="d-flex justify-content-end gap-2 mt-3">
                <a href="{{ route('guru.kelas.ajaran.detail', $kelas_id) }}" class="btn btn-light">
                    Batal
                </a>
                <button class="btn btn-success">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>

</div>

@endsection
