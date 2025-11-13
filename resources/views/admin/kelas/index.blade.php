@extends('layouts.admin')

@section('title', 'Data Kelas')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin/kelas/index.css') }}">
@endsection

@section('content')
<div class="container-fluid kelas-index-page py-5 px-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold text-dark mb-1">Data Kelas</h2>
            <p class="text-muted small mb-0">Kelola seluruh kelas dan wali kelas yang terdaftar</p>
        </div>
        <a href="{{ route('admin.kelas.create') }}" class="btn btn-main px-4 py-2 rounded-3 shadow-sm">
            <i class="bi bi-plus-circle me-2"></i> Tambah Kelas
        </a>
    </div>

    <!-- Notifikasi -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Daftar Kelas -->
    @forelse($kelas as $jenjang => $daftar)
        <div class="mb-5">
            <h5 class="fw-semibold text-secondary mb-3">Kelas {{ $jenjang }}</h5>

            <div class="row g-4">
                @foreach($daftar as $k)
                    <div class="col-lg-4 col-md-6">
                        <div class="kelas-card bg-white rounded-4 border shadow-sm">
                            <div class="kelas-card-body p-4">

                                <!-- Header Kelas -->
                                <div class="d-flex align-items-center mb-3">
                                    <div class="kelas-icon me-3">
                                        <i class="bi bi-mortarboard-fill"></i>
                                    </div>
                                    <div>
                                        <h5 class="fw-bold text-dark mb-1">{{ $k->nama_kelas }}</h5>
                                        <small class="text-muted">{{ $k->guru->user->name ?? 'Belum ada wali kelas' }}</small>
                                    </div>
                                </div>

                                <!-- Deskripsi -->
                                <p class="text-secondary small mb-4">
                                    {{ $k->deskripsi ?? 'Tidak ada deskripsi kelas.' }}
                                </p>

                                {{-- Jadwal Hari Ini DIHAPUS --}}

                                <!-- Tombol Aksi -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="{{ route('admin.kelas.kelolaMurid', $k->id) }}" 
                                       class="btn btn-sm btn-outline-main px-3">
                                        <i class="bi bi-people-fill me-1"></i> Siswa
                                    </a>

                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.kelas.edit', $k->id) }}" 
                                           class="btn btn-sm btn-outline-warning px-2">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>

                                        <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger px-2">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    @empty
        <div class="text-center text-muted py-5">
            <i class="bi bi-journal-x display-5 d-block mb-3"></i>
            <p class="fs-5 mb-0">Belum ada data kelas yang tersedia.</p>
        </div>
    @endforelse

</div>
@endsection
