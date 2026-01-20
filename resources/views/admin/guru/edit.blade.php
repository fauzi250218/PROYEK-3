@extends('layouts.admin')

@section('title', 'Edit Guru')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin/guru/create.css') }}">
@endsection

@section('content')
<div class="container guru-form-page py-5">
    <h3 class="mb-4 fw-bold text-dark">Edit Data Guru</h3>

    <div class="form-card shadow-lg p-4 bg-white rounded-4">
        @if(!isset($guru))
            <div class="alert alert-danger">Data guru tidak tersedia.</div>
        @else
            <form action="{{ route('admin.guru.update', $guru->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Nama Lengkap -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name', $guru->user->name ?? '') }}" required>
                </div>

                <!-- Email & Password -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Email</label>
                        <input type="email" name="email" class="form-control"
                               value="{{ old('email', $guru->user->email ?? '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Kata Sandi (isi jika ingin ubah)</label>
                        <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
                    </div>
                </div>

                <!-- Jenis Kelamin & WhatsApp -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nomor WhatsApp</label>
                        <input type="text" name="nomer_whatsapp" class="form-control"
                               value="{{ old('nomer_whatsapp', $guru->nomer_whatsapp) }}">
                    </div>
                </div>

                <!-- Mata Pelajaran & Wali Kelas (satu baris) -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Mata Pelajaran</label>
                        <input type="text" name="mata_pelajaran" class="form-control"
                               value="{{ old('mata_pelajaran', $guru->mata_pelajaran) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Wali Kelas (Opsional)</label>
                        <select name="kelas_id" class="form-select">
                            <option value="">-- Tidak Ada --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}" {{ $k->guru_id == $guru->id ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
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

                    @if($guru->foto_profil)
                        <div class="mt-3">
                            <p class="fw-semibold text-secondary mb-2">Foto Saat Ini:</p>
                            <img src="{{ asset('storage/' . $guru->foto_profil) }}" 
                                 alt="Foto Profil" class="rounded shadow-sm" 
                                 style="width: 130px; height: 130px; object-fit: cover; border: 2px solid #dee2e6;">
                        </div>
                    @endif
                </div>

                <!-- Tombol -->
                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('admin.guru.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-success px-4">
                        <i class="bi bi-save"></i> Update
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection
