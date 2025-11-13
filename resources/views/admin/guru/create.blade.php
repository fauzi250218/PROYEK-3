@extends('layouts.admin')

@section('title', 'Tambah Guru')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin/guru/create.css') }}">
@endsection

@section('content')
<div class="container guru-form-page py-5">
    <h3 class="mb-4 fw-bold text-dark">Tambah Guru Baru</h3>

    <div class="form-card shadow-lg p-4 bg-white rounded-4">
        <form action="{{ route('admin.guru.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Nama Lengkap -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" required placeholder="Nama Lengkap Guru">
            </div>

            <!-- Email & Password -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="Email Guru">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kata Sandi</label>
                    <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
                </div>
            </div>

            <!-- Jenis Kelamin & WhatsApp -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nomor WhatsApp</label>
                    <input type="text" name="nomer_whatsapp" class="form-control" placeholder="08xxxxxxxxxx">
                </div>
            </div>

            <!-- Mata Pelajaran & Wali Kelas (satu baris) -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Mata Pelajaran</label>
                    <input type="text" name="mata_pelajaran" class="form-control" placeholder="Contoh: Matematika">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Wali Kelas (Opsional)</label>
                    <select name="kelas_id" class="form-select">
                        <option value="">-- Tidak Ada --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Foto Profil -->
            <div class="mb-4">
                <label class="form-label fw-semibold d-block">Foto Profil</label>
                <div class="upload-box">
                    <i class="bi bi-cloud-arrow-up-fill upload-icon"></i>
                    <p class="upload-text mb-1">Pilih foto guru untuk diunggah</p>
                    <input type="file" name="foto_profil" class="form-control file-input" accept="image/*">
                    <small class="text-muted">Format: JPG, PNG (maks. 2MB)</small>
                </div>
            </div>

            <!-- Tombol -->
            <div class="d-flex justify-content-end gap-3 mt-4">
                <a href="{{ route('admin.guru.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
