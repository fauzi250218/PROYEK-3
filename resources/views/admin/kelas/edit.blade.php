@extends('layouts.admin')

@section('title', 'Edit Kelas')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin/kelas/edit.css') }}">
@endsection

@section('content')
<div class="container-fluid kelas-edit-page py-5">
    <div class="form-wrapper p-5 bg-white rounded-4 shadow-sm mx-auto">
        <!-- Header -->
        <div class="d-flex align-items-center mb-4 border-bottom pb-3">
            <div class="icon-box me-3">
                <i class="bi bi-pencil-square text-primary fs-3"></i>
            </div>
            <div>
                <h3 class="fw-bold text-dark mb-1">Edit Data Kelas</h3>
                <p class="text-muted small mb-0">Perbarui informasi kelas dan wali kelas di bawah ini</p>
            </div>
        </div>

        <!-- Form -->
        <form action="{{ route('admin.kelas.update', $kelas->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <!-- Nama Kelas -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Nama Kelas</label>
                    <input type="text" 
                           name="nama_kelas" 
                           class="form-control" 
                           value="{{ old('nama_kelas', $kelas->nama_kelas) }}" 
                           placeholder="Contoh: 7A" 
                           required>
                </div>

                <!-- Wali Kelas -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">Wali Kelas (Opsional)</label>
                    <select name="guru_id" class="form-select">
                        <option value="">-- Tidak Ada Wali Kelas --</option>
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
            </div>

            <!-- Deskripsi -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Deskripsi</label>
                <textarea name="deskripsi" 
                          class="form-control" 
                          rows="5" 
                          placeholder="Keterangan tambahan (opsional)...">{{ old('deskripsi', $kelas->deskripsi) }}</textarea>
            </div>

            <!-- Tombol -->
            <div class="d-flex justify-content-end gap-3 mt-4">
                <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline-secondary px-4 py-2">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary-solid px-4 py-2">
                    <i class="bi bi-save me-1"></i> Perbarui
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
