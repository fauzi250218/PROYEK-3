@extends('layouts.admin')

@section('title', 'Tambah Jadwal')

@section('content')
<div class="container mt-5">
    <!-- Header Section -->
    <div class="text-center mb-4">
        <h2 class="fw-bold text-primary">Tambah Jadwal Pelajaran</h2>
        <p class="text-muted">Lengkapi data di bawah untuk menambahkan jadwal baru</p>
    </div>

    <!-- Form Card -->
    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-4">
            <form action="{{ route('admin.jadwal.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <!-- Kelas -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary">Kelas</label>
                        <select name="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Mata Pelajaran -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary">Mata Pelajaran</label>
                        <input type="text" name="mata_pelajaran" class="form-control" placeholder="Contoh: Matematika" required>
                    </div>

                    <!-- Guru -->
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-secondary">Guru</label>
                        <input type="text" name="guru" class="form-control" placeholder="Nama Guru" required>
                    </div>

                    <!-- Jam Mulai -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-control" required>
                    </div>

                    <!-- Jam Selesai -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-control" required>
                    </div>

                    <!-- Tanggal -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control" required>
                    </div>

                    <!-- Keterangan -->
                    <div class="col-md-3">
                        <label class="form-label fw-semibold text-secondary">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" placeholder="Opsional">
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-save me-1"></i> Simpan Jadwal
                    </button>
                    <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary px-4 py-2">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('extra-css')
<!-- Tambahan Bootstrap Icons untuk ikon tombol -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background-color: #f8f9fa;
    }

    .card {
        background: #ffffff;
    }

    .form-label {
        font-size: 0.95rem;
    }

    input.form-control, select.form-select {
        border-radius: 8px;
        transition: 0.2s ease-in-out;
    }

    input.form-control:focus, select.form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13,110,253,.25);
    }

    .btn-primary {
        border-radius: 8px;
        font-weight: 500;
    }

    .btn-outline-secondary {
        border-radius: 8px;
    }
</style>
@endsection
