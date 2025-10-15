@extends('layouts.admin')

@section('title', 'Tambah Guru')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin-guru-form.css') }}">
@endsection

@section('content')
<div class="container mt-5 pt-5">
    <h3 class="mb-4 fw-bold">Tambah Guru Baru</h3>

    <div class="form-card shadow-sm p-4 bg-white rounded">
        <form action="{{ route('admin.guru.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" required placeholder="Contoh: Fajar Rahman">
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required placeholder="contoh@email.com">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor WhatsApp</label>
                    <input type="text" name="nomer_whatsapp" class="form-control" placeholder="08xxxxxxxxxx">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Mata Pelajaran</label>
                <input type="text" name="mata_pelajaran" class="form-control" placeholder="Contoh: Matematika">
            </div>

            <div class="mb-3">
                <label class="form-label">Wali Kelas (opsional)</label>
                <select name="kelas_id" class="form-control">
                    <option value="">-- Tidak Ada --</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-4">
                <a href="{{ route('admin.guru.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
