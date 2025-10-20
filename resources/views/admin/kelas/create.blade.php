@extends('layouts.admin')

@section('title', 'Tambah Kelas')

@section('content')
<div class="container mt-5">
    <h3 class="mb-4 fw-bold">Tambah Kelas Baru</h3>

    <form action="{{ route('admin.kelas.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Nama Kelas</label>
            <input type="text" name="nama_kelas" class="form-control" placeholder="Contoh: 7A" required>
        </div>

        <div class="mb-3">
            <label>Wali Kelas (opsional)</label>
            <select name="guru_id" class="form-control">
                <option value="">-- Tidak Ada Wali Kelas --</option>
                @foreach($guru as $g)
                    <option value="{{ $g->id }}">{{ $g->user->name ?? 'Tanpa Nama' }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" placeholder="Keterangan tambahan..."></textarea>
        </div>

        <button type="submit" class="btn btn-success">Simpan</button>
        <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
