@extends('layouts.guru')
@section('title', 'Manajemen Nilai - Data Siswa')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/guru/nilai/data-siswa.css') }}">
@endsection

@section('content')
<div class="container py-4">
    <div class="header-info mb-4">
        <h3 class="fw-bold text-success">
            <i class="bi bi-mortarboard-fill me-2"></i>Kelas: {{ $kelas->nama_kelas }}
        </h3>
        <p class="text-muted mb-1">
            <strong>Wali Kelas:</strong> {{ $kelas->guru->user->name ?? 'Belum ada wali kelas' }}
        </p>
        <hr class="mt-2">
    </div>

    <div class="table-responsive shadow-sm rounded-3 bg-white p-3">
        <table class="table align-middle table-hover mb-0">
            <thead class="table-header">
                <tr>
                    <th class="text-center">No</th>
                    <th>Nama Siswa</th>
                    <th>NIS</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelas->murids as $murid)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td>{{ $murid->nama }}</td>
                    <td>{{ $murid->nis }}</td>
                    <td class="text-center">
                        <a href="{{ route('guru.nilai.detail', $murid->id) }}" 
                           class="btn btn-gradient-success btn-sm">
                           <i class="bi bi-clipboard-data me-1"></i> Lihat Nilai
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted py-3">
                        <i class="bi bi-exclamation-circle me-1"></i> Belum ada murid di kelas ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        <a href="{{ route('guru.nilai.semuaKelas') }}" class="btn btn-outline-secondary">
            Kembali ke Semua Kelas
        </a>
    </div>
</div>
@endsection

@section('extra-js')
<script src="{{ asset('js/guru/nilai/data-siswa.js') }}"></script>
@endsection
