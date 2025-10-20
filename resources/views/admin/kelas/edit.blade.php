@extends('layouts.admin')

@section('title', 'Edit Kelas')

@section('content')
<div class="container mt-5">
    <h3 class="mb-4 fw-bold">Edit Data Kelas</h3>

    <form action="{{ route('admin.kelas.update', $kelas->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Nama Kelas</label>
            <input type="text" name="nama_kelas" class="form-control" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required>
        </div>

        <div class="mb-3">
            <label>Wali Kelas (opsional)</label>
            <!-- GANTI: name menjadi guru_id dan tampilkan nama lewat relasi user -->
            <select name="guru_id" class="form-control">
                <option value="">-- Tidak Ada --</option>
                @foreach($guru as $g)
                    <option value="{{ $g->id }}" {{ (int) old('guru_id', $kelas->guru_id) === (int) $g->id ? 'selected' : '' }}>
                        {{ $g->user->name ?? 'Tanpa Nama' }}
                    </option>
                @endforeach
            </select>
            @error('guru_id')
                <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control">{{ old('deskripsi', $kelas->deskripsi) }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
