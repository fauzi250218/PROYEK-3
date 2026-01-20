@extends('layouts.guru')

@section('title', 'Edit Aktivitas Pembelajaran')

@section('content')

<div class="container py-3">

    <div class="card shadow-sm p-4 rounded-4">

        <h4 class="fw-bold mb-4">Edit Aktivitas Pembelajaran</h4>

        {{-- ERROR --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('guru.kelas.ajaran.sesi.update', $sesi->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- KELAS ID (DIKIRIM DARI CONTROLLER) --}}
            <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">

            {{-- JUDUL SESI --}}
            <div class="mb-3">
                <label class="fw-semibold small">Judul Sesi</label>
                <input
                    type="text"
                    name="judul_sesi"
                    class="form-control"
                    value="{{ old('judul_sesi', $sesi->judul_sesi) }}"
                    required
                >
            </div>

            {{-- TOPIK --}}
            <div class="mb-3">
                <label class="fw-semibold small">Topik</label>
                <input
                    type="text"
                    name="topik"
                    class="form-control"
                    value="{{ old('topik', $sesi->topik) }}"
                >
            </div>

            {{-- DESKRIPSI --}}
            <div class="mb-3">
                <label class="fw-semibold small">Tujuan Pembelajaran</label>
                <textarea
                    name="deskripsi"
                    class="form-control"
                    rows="5"
                >{{ old('deskripsi', $sesi->deskripsi) }}</textarea>
            </div>

            {{-- TANGGAL --}}
            <div class="mb-3">
                <label class="fw-semibold small">Tanggal</label>
                <input
                    type="date"
                    name="tanggal"
                    class="form-control"
                    value="{{ old('tanggal', $sesi->tanggal) }}"
                    required
                >
            </div>

            {{-- JAM --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="fw-semibold small">Jam Mulai</label>
                    <input
                        type="time"
                        name="jam_mulai"
                        class="form-control"
                        value="{{ old('jam_mulai', $sesi->jam_mulai) }}"
                        required
                    >
                </div>

                <div class="col-md-6 mb-3">
                    <label class="fw-semibold small">Jam Selesai</label>
                    <input
                        type="time"
                        name="jam_selesai"
                        class="form-control"
                        value="{{ old('jam_selesai', $sesi->jam_selesai) }}"
                        required
                    >
                </div>
            </div>

            {{-- ACTION --}}
            <div class="d-flex justify-content-end gap-2 mt-4">
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
