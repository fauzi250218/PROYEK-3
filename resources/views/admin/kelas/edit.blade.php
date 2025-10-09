@extends('layouts.admin')

@section('title', 'Edit Kelas')

@section('content')
<div class="container mt-4">
  <h2>Edit Kelas</h2>

  <form action="{{ route('admin.kelas.update', $kelas->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="mb-3">
      <label>Nama Kelas</label>
      <input type="text" name="nama_kelas" class="form-control" value="{{ $kelas->nama_kelas }}" required>
    </div>
    <div class="mb-3">
      <label>Wali Kelas</label>
      <input type="text" name="wali_kelas" class="form-control" value="{{ $kelas->wali_kelas }}">
    </div>
    <div class="mb-3">
      <label>Deskripsi</label>
      <textarea name="deskripsi" class="form-control">{{ $kelas->deskripsi }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Perbarui</button>
    <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">Kembali</a>
  </form>
</div>
@endsection
