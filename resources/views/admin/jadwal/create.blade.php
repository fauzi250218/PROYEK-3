@extends('layouts.admin')

@section('title', 'Tambah Jadwal')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin-tambah-jadwal.css') }}">
@endsection

@section('content')
<div class="jadwal-wrapper container-fluid px-4 py-4">
    <!-- HEADER -->
    <div class="jadwal-header mb-4">
        <h2 class="fw-bold">Tambah Jadwal Pelajaran</h2>
        <p class="text-muted">Isi form berikut untuk menambahkan jadwal pelajaran baru</p>
    </div>

    <!-- FORM CARD FULL WIDTH -->
    <div class="card jadwal-card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('admin.jadwal.store') }}" method="POST" autocomplete="off">
                @csrf

                <div class="row g-4">
                    <!-- Kelas -->
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Kelas</label>
                        <select name="kelas_id" class="form-select" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Mata Pelajaran -->
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Mata Pelajaran</label>
                        <input type="text" name="mata_pelajaran" class="form-control" placeholder="Contoh: Matematika" required>
                    </div>

                    <!-- Guru -->
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Guru Pengajar</label>
                        <input type="text" name="guru" class="form-control" placeholder="Nama Guru" required>
                    </div>

                    <!-- Tanggal -->
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control" required>
                    </div>

                    <!-- Jam Mulai -->
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Jam Mulai</label>
                        <input type="time" name="jam_mulai" class="form-control" required>
                    </div>

                    <!-- Jam Selesai -->
                    <div class="col-lg-4 col-md-6">
                        <label class="form-label">Jam Selesai</label>
                        <input type="time" name="jam_selesai" class="form-control" required>
                    </div>

                    <!-- Keterangan -->
                    <div class="col-12">
                        <label class="form-label">Keterangan (Opsional)</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Tambahkan catatan jika perlu..."></textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4 gap-3">
                    <a href="{{ route('admin.jadwal.index') }}" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-save me-1"></i> Simpan Jadwal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
