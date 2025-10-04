@extends('layouts.admin')

@section('title', 'Edit Murid')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin-kelola-siswa.css') }}">
@endsection

@section('content')
<div class="container mt-5 pt-5">
    <h3 class="mb-4">Edit Siswa</h3>

    <div class="form-wrapper p-4">
        <form action="{{ route('admin.murid.update', $murid->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Nama -->
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="{{ $murid->nama }}" required>
            </div>

            <!-- NISN -->
            <div class="mb-3">
                <label class="form-label">NIS</label>
                <input type="text" name="nisn" class="form-control" value="{{ $murid->nisn }}" required>
            </div>

            <!-- Email + Kelas + Jenis Kelamin -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Alamat Email</label>
                    <input type="email" name="email" class="form-control" value="{{ $murid->email }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kelas</label>
                    <select name="kelas" class="form-control" required>
                        <option value="7" {{ $murid->kelas == '7' ? 'selected' : '' }}>Kelas 7</option>
                        <option value="8" {{ $murid->kelas == '8' ? 'selected' : '' }}>Kelas 8</option>
                        <option value="9" {{ $murid->kelas == '9' ? 'selected' : '' }}>Kelas 9</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="Laki-laki" {{ $murid->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $murid->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
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
                    <input type="text" name="nomer_whatsapp" class="form-control" value="{{ $murid->nomer_whatsapp }}">
                </div>
            </div>

            <!-- Buttons -->
            <button type="submit" class="btn btn-success">Perbarui</button>
            <a href="{{ route('admin.murid.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection
