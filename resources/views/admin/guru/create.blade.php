@extends('layouts.admin')

@section('title', 'Tambah Guru')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin-guru-form.css') }}">
@endsection

@section('content')
<div class="container mt-5 pt-5"> <!-- Tambah margin-top supaya turun -->
    <h3 class="mb-4">Tambah Guru</h3>

    <!-- Kotak Card -->
    <div class="form-card shadow-sm p-4">
        <form action="{{ route('admin.guru.store') }}" method="POST">
            @csrf

            <!-- Nama -->
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" required>
            </div>

            <!-- Email + Kelas + Jenis Kelamin -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Alamat Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kelas</label>
                    <select name="kelas" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="7">Kelas 7</option>
                        <option value="8">Kelas 8</option>
                        <option value="9">Kelas 9</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
            </div>

            <!-- Kata Sandi + Nomor WhatsApp -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Kata Sandi</label>
                    <input type="password" name="kata_sandi" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor WhatsApp</label>
                    <input type="text" name="nomer_whatsapp" class="form-control">
                </div>
            </div>

            <!-- Mata Pelajaran -->
            <div class="mb-3">
                <label class="form-label">Mata Pelajaran</label>
                <input type="text" name="mata_pelajaran" class="form-control">
            </div>

            <!-- Buttons -->
            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('admin.guru.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
