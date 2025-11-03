@extends('layouts.admin')

@section('title', 'Edit Murid')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin/murid/edit.css') }}">
@endsection

@section('content')
<div class="container murid-form-page py-5">
    <h3 class="mb-4 fw-bold text-dark">Edit Data Murid</h3>

    <div class="form-card shadow-lg p-4 bg-white rounded-4">
        <form action="{{ route('admin.murid.update', $murid->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Nama Lengkap -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" 
                       value="{{ old('nama', $murid->nama) }}" required>
            </div>

            <!-- NIS -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Nomor Induk Siswa (NIS)</label>
                <input type="text" name="nis" class="form-control" 
                       value="{{ old('nis', $murid->nis) }}" required>
            </div>

            <!-- Email, Kelas, Jenis Kelamin -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email', $murid->email) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Kelas</label>
                    <select name="kelas_id" class="form-select" required>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ $murid->kelas_id == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="Laki-laki" {{ $murid->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $murid->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <!-- Password & WhatsApp -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Kata Sandi (isi jika ingin ubah)</label>
                    <input type="password" name="kata_sandi" class="form-control" placeholder="Kosongkan jika tidak ingin ubah">
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Nomor WhatsApp</label>
                    <input type="text" name="nomer_whatsapp" class="form-control" 
                           value="{{ old('nomer_whatsapp', $murid->nomer_whatsapp) }}">
                </div>
            </div>

            <!-- Foto Profil -->
            <div class="mb-4">
                <label class="form-label fw-semibold d-block">Foto Profil</label>
                <div class="upload-box">
                    <i class="bi bi-cloud-arrow-up-fill upload-icon"></i>
                    <p class="upload-text mb-1">Pilih foto baru (opsional)</p>
                    <input type="file" name="foto_profil" class="form-control file-input" accept="image/*">
                    <small class="text-muted">Format: JPG, PNG (maks. 2MB)</small>
                </div>

                @if($murid->foto_profil)
                    <div class="mt-3">
                        <p class="fw-semibold text-secondary mb-2">Foto Saat Ini:</p>
                        <img src="{{ asset('storage/' . $murid->foto_profil) }}" 
                             alt="Foto Profil" class="rounded shadow-sm" 
                             style="width: 130px; height: 130px; object-fit: cover; border: 2px solid #dee2e6;">
                    </div>
                @endif
            </div>

            <!-- Tombol -->
            <div class="d-flex justify-content-end gap-3 mt-4">
                <a href="{{ route('admin.murid.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-success px-4">
                    <i class="bi bi-save"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
