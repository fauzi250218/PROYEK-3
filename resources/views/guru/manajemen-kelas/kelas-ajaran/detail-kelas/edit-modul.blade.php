@extends('layouts.guru')

@section('title', 'Edit Modul')

@section('content')

<div class="container">

    <!-- HEADER PAGE -->
    <h4 class="fw-bold mb-4">Edit Modul Pembelajaran</h4>

    <div class="card shadow-sm border-0 p-4">

        <!-- =============================== -->
        <!-- FORM UPDATE MODUL               -->
        <!-- =============================== -->

        <form action="{{ route('guru.kelas.ajaran.modul.update', $modul->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="mb-0">

            @csrf
            @method('PUT')

            <!-- JUDUL MODUL -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Judul Modul</label>
                <input type="text" name="judul" class="form-control"
                       value="{{ $modul->judul }}" required>
            </div>

            <!-- TOPIK -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Topik</label>
                <input type="text" name="topik" class="form-control"
                       value="{{ $modul->topik }}">
            </div>

            <!-- CATATAN -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Catatan</label>
                <textarea name="catatan" class="form-control" rows="3">{{ $modul->catatan }}</textarea>
            </div>

            <!-- FILE LAMA -->
            <div class="mb-3">
                <label class="form-label fw-semibold">File Saat Ini</label>

                <div class="border p-3 rounded bg-light d-flex justify-content-between align-items-center">
                    <span>{{ basename($modul->file) }}</span>

                    <a href="{{ asset('storage/'.$modul->file) }}"
                       target="_blank"
                       class="btn btn-sm btn-primary">
                        Lihat PDF
                    </a>
                </div>
            </div>

            <!-- FILE BARU -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Ganti File (Opsional)</label>
                <input type="file" name="file" class="form-control">
                <small class="text-muted">Kosongkan jika tidak ingin mengganti file.</small>
            </div>

            <!-- =============================== -->
            <!-- BUTTONS                         -->
            <!-- =============================== -->
            <div class="d-flex justify-content-end mt-4">

                <!-- KEMBALI -->
                <a href="{{ route('guru.kelas.ajaran.detail', $kelas->id) }}"
                   class="btn btn-secondary me-2">
                    Kembali
                </a>

                <!-- SIMPAN -->
                <button type="submit" class="btn btn-success me-2">
                    Simpan Perubahan
                </button>

        </form>

                <!-- HAPUS MODUL -->
                <form action="{{ route('guru.kelas.ajaran.modul.delete', $modul->id) }}"
                      method="POST"
                      onsubmit="return confirm('Yakin ingin menghapus modul ini? File PDF akan terhapus permanen.');">

                    @csrf
                    @method('DELETE')

                    <button type="submit" class="btn btn-danger">
                        Hapus Modul
                    </button>

                </form>

            </div>

    </div>

</div>

@endsection
