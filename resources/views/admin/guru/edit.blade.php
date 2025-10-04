@extends('layouts.admin')

@section('title', 'Edit Guru')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin-guru-form.css') }}">
@endsection

@section('content')
<div class="container mt-5 pt-5">
    <h3 class="mb-4">Edit Guru</h3>

    <div class="form-wrapper p-4">
        <form action="{{ route('admin.guru.update', $guru->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nama -->
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="{{ $guru->nama }}" required>
            </div>

            <!-- Email + Kelas + Jenis Kelamin -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Alamat Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $guru->email }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kelas</label>
                    <select name="kelas" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="7" {{ $guru->kelas == '7' ? 'selected' : '' }}>Kelas 7</option>
                        <option value="8" {{ $guru->kelas == '8' ? 'selected' : '' }}>Kelas 8</option>
                        <option value="9" {{ $guru->kelas == '9' ? 'selected' : '' }}>Kelas 9</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="Laki-laki" {{ $guru->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $guru->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <!-- Kata Sandi + Nomor WhatsApp -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Kata Sandi (isi hanya jika ingin ganti)</label>
                    <input type="password" name="kata_sandi" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor WhatsApp</label>
                    <input type="text" name="nomer_whatsapp" class="form-control" value="{{ $guru->nomer_whatsapp }}">
                </div>
            </div>

            <!-- Mata Pelajaran -->
            <div class="mb-3">
                <label class="form-label">Mata Pelajaran</label>
                <input type="text" name="mata_pelajaran" class="form-control" value="{{ $guru->mata_pelajaran }}">
            </div>

            <!-- Buttons -->
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.guru.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
