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
            <input type="text" name="nama_kelas" class="form-control" value="{{ $kelas->nama_kelas }}" required>
        </div>

        <div class="mb-3">
            <label>Wali Kelas</label>
            <select name="user_id" class="form-control">
                <option value="">-- Tidak Ada --</option>
                @foreach($guru as $g)
                    <option value="{{ $g->id }}" {{ $kelas->user_id == $g->id ? 'selected' : '' }}>
                        {{ $g->name }}
                    </option>
                @endforeach
            </select>
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
