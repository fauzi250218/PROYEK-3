@extends('layouts.admin')

@section('title', 'Tambah Murid')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin/murid/create.css') }}">
@endsection

@section('content')
<div class="container murid-form-page py-5">
    <h3 class="mb-4 fw-bold text-dark">Tambah Murid Baru</h3>

    <div class="form-card shadow-lg p-4 bg-white rounded-4">
        <form action="{{ route('admin.murid.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Nama Lengkap -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required placeholder="Nama Lengkap Siswa">
            </div>

            <!-- NIS -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Nomor Induk Siswa (NIS)</label>
                <input type="text" name="nis" class="form-control" required placeholder="Nomer Induk Siswa">
            </div>

            <!-- Email, Kelas, Jenis Kelamin -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="Email Siswa">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Kelas</label>
                    <select name="kelas_id" class="form-select" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
            </div>

            <!-- Password & WhatsApp -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kata Sandi</label>
                    <input type="password" name="kata_sandi" class="form-control" required placeholder="Minimal 6 karakter">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nomor WhatsApp</label>
                    <input type="text" name="nomer_whatsapp" class="form-control" placeholder="08xxxxxxxxxx">
                </div>
            </div>

            <!-- Foto Profil -->
            <div class="mb-4">
                <label class="form-label fw-semibold d-block">Foto Profil</label>
                <div class="upload-box">
                    <i class="bi bi-cloud-arrow-up-fill upload-icon"></i>
                    <p class="upload-text mb-1">Pilih foto siswa untuk diunggah</p>
                    <input type="file" name="foto_profil" class="form-control file-input" accept="image/*">
                    <small class="text-muted">Format: JPG, PNG (maks. 2MB)</small>
                </div>
            </div>

            <!-- Tombol -->
            <div class="d-flex justify-content-end gap-3 mt-4">
                <a href="{{ route('admin.murid.index') }}" class="btn btn-outline-secondary px-4">
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
