@extends('layouts.admin')

@section('title', 'Tambah Kelas')

@section('content')
<div class="container mt-4">
  <h2>Tambah Kelas</h2>

  <form action="{{ route('admin.kelas.store') }}" method="POST">
    @csrf
    <div class="mb-3">
      <label>Nama Kelas</label>
      <input type="text" name="nama_kelas" class="form-control" required>
    </div>
    <div class="mb-3">
      <label>Wali Kelas</label>
      <input type="text" name="wali_kelas" class="form-control">
    </div>
    <div class="mb-3">
      <label>Deskripsi</label>
      <textarea name="deskripsi" class="form-control"></textarea>
    </div>
    <button type="submit" class="btn btn-success">Simpan</button>
    <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">Kembali</a>
  </form>
</div>
@endsection
