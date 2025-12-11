@extends('layouts.guru')

@section('title', 'Edit Modul')

@section('content')

<div class="container">

    <h4 class="fw-bold mb-4">Edit Modul Pembelajaran</h4>

    <div class="card shadow-sm border-0 p-4 position-relative">

        {{-- DELETE ICON --}}
        <form action="{{ route('guru.kelas.ajaran.modul.delete', $modul->id) }}"
              method="POST"
              onsubmit="return confirm('Hapus modul ini?')"
              class="position-absolute delete-icon-wrapper">

            @csrf
            @method('DELETE')

            <button type="submit"
                    class="p-0 border-0 bg-transparent"
                    style="cursor: pointer;">
                <i class="bi bi-trash3-fill delete-icon"></i>
            </button>
        </form>

        {{-- FORM UPDATE MODUL --}}
        <form action="{{ route('guru.kelas.ajaran.modul.update', $modul->id) }}"
              method="POST"
              enctype="multipart/form-data">

            @csrf
            @method('PUT')

            {{-- show validation errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- flash messages --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            {{-- JUDUL --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Judul Modul</label>
                <input type="text" name="judul" class="form-control"
                       value="{{ old('judul', $modul->judul) }}" required>
            </div>

            {{-- TOPIK --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Topik</label>
                <input type="text" name="topik" class="form-control"
                       value="{{ old('topik', $modul->topik) }}">
            </div>

            {{-- CATATAN --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Catatan</label>
                <textarea name="catatan" class="form-control" rows="3">{{ old('catatan', $modul->catatan) }}</textarea>
            </div>

            {{-- FILE LAMA --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">File Saat Ini</label>
                <div class="border p-3 rounded bg-light d-flex justify-content-between align-items-center">
                    <div class="text-truncate" style="max-width:70%;">
                        {{ basename($modul->file) }}
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ asset('storage/'.$modul->file) }}" target="_blank"
                           class="btn btn-sm btn-primary">Lihat PDF</a>

                    </div>
                </div>
            </div>

            {{-- FILE BARU --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Ganti File (Opsional)</label>
                <input type="file" name="file" class="form-control" accept="application/pdf">
                <small class="text-muted">Kosongkan jika tidak ingin mengganti file. Maks 0MB (PDF).</small>
            </div>

            {{-- BUTTON --}}
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('guru.kelas.ajaran.detail', $kelas->id) }}" class="btn btn-secondary me-2">
                    Kembali
                </a>

                <button type="submit" class="btn btn-success">
                    Simpan Perubahan
                </button>
            </div>

        </form>

    </div>

</div>

<style>
    .delete-icon-wrapper { top: 6px; right: 10px; position: absolute; }
    .delete-icon { font-size: 22px; color: #dc3545; opacity: 0.85; transition: 0.25s; }
    .delete-icon-wrapper:hover .delete-icon { opacity:1; color:#b30000; transform: scale(1.08); }
</style>

@endsection
