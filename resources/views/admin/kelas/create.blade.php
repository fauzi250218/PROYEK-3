@extends('layouts.admin')

@section('title', 'Tambah Kelas')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin-kelas-form.css') }}">
@endsection

@section('content')
<div class="kelas-page">
    <div class="kelas-header text-center text-white">
        <h2 class="fw-bold mb-1">Tambah Kelas Baru</h2>
        <p class="lead">Isi data di bawah untuk menambahkan kelas baru ke sistem akademik</p>
    </div>

    <div class="kelas-container">
        <div class="kelas-card shadow-lg">
            <form action="{{ route('admin.kelas.store') }}" method="POST" autocomplete="off">
                @csrf
                <div class="form-floating mb-4">
                    <input type="text" name="nama_kelas" class="form-control" id="namaKelas" placeholder="Kelas 7A" required>
                    <label for="namaKelas">Nama Kelas</label>
                </div>

                <div class="form-floating mb-4">
                    <input type="text" name="wali_kelas" class="form-control" id="waliKelas" placeholder="Bapak Fajar Rahman">
                    <label for="waliKelas">Wali Kelas</label>
                </div>

                <div class="form-floating mb-4">
                    <textarea name="deskripsi" class="form-control" id="deskripsi" style="height: 120px" placeholder="Tuliskan keterangan tambahan..."></textarea>
                    <label for="deskripsi">Deskripsi</label>
                </div>

                <div class="d-flex justify-content-end gap-3">
                    <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-submit btn-lg">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
