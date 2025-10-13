@extends('layouts.admin')

@section('title', 'Edit Jadwal')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin-jadwal-edit.css') }}">
@endsection

@section('content')
<div class="jadwal-edit-page container mt-5">
    <div class="edit-header text-center mb-5">
        <h2 class="fw-bold text-primary">Edit Jadwal Pelajaran</h2>
        <p class="text-muted">Perbarui data jadwal di bawah atau hapus jadwal bila diperlukan</p>
    </div>

    <!-- Form untuk hapus -->
    <form id="formHapus" action="{{ route('admin.jadwal.destroy', $jadwal->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <input type="hidden" name="hapus_semua" id="hapus_semua" value="0">
    </form>

    <!-- Form edit -->
    <div class="card shadow-lg border-0 rounded-4 p-4 jadwal-card">
        <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">
                <!-- Kelas -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary">Kelas</label>
                    <select name="kelas_id" class="form-select" required>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}" {{ $jadwal->kelas_id == $k->id ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Mata Pelajaran -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary">Mata Pelajaran</label>
                    <input type="text" name="mata_pelajaran" class="form-control" 
                           value="{{ $jadwal->mata_pelajaran }}" required>
                </div>

                <!-- Guru -->
                <div class="col-md-4">
                    <label class="form-label fw-semibold text-secondary">Guru</label>
                    <input type="text" name="guru" class="form-control" 
                           value="{{ $jadwal->guru }}" required>
                </div>

                <!-- Jam & Tanggal -->
                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary">Jam Mulai</label>
                    <input type="time" name="jam_mulai" class="form-control" 
                           value="{{ $jadwal->jam_mulai }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary">Jam Selesai</label>
                    <input type="time" name="jam_selesai" class="form-control" 
                           value="{{ $jadwal->jam_selesai }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" 
                           value="{{ $jadwal->tanggal }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-semibold text-secondary">Keterangan</label>
                    <input type="text" name="keterangan" class="form-control" 
                           value="{{ $jadwal->keterangan }}">
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary px-4 py-2">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-save me-1"></i> Simpan Perubahan
                    </button>
                    <button type="button" class="btn btn-danger px-4 py-2" 
                            data-bs-toggle="modal" data-bs-target="#hapusModal">
                        <i class="bi bi-trash me-1"></i> Hapus
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal konfirmasi hapus -->
<div class="modal fade" id="hapusModal" tabindex="-1" aria-labelledby="hapusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="hapusModalLabel">Konfirmasi Penghapusan</h5>
                <button type="button" class="btn-close btn-close-white" 
                        data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-3 fw-semibold">Apakah kamu yakin ingin menghapus jadwal ini?</p>
                <div class="alert alert-warning small">
                    Pilih salah satu opsi di bawah. Penghapusan bersifat permanen.
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                @if($jadwal->group_id)
                <button type="button" class="btn btn-danger" onclick="hapusSemua()">Hapus Semua</button>
                @endif
                <button type="button" class="btn btn-outline-danger" onclick="hapusSatu()">Hapus Jadwal Ini</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<script src="{{ asset('js/admin-jadwal-edit.js') }}"></script>
@endsection
