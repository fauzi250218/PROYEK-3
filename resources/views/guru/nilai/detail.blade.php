@extends('layouts.guru')
@section('title', 'Detail Nilai Siswa')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/guru/nilai/detail-nilai.css') }}">
@endsection

@section('content')
<div class="container py-5 nilai-page">

    <!-- Header -->
    <div class="nilai-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">{{ $murid->nama }}</h2>
            <p class="text-muted mb-0">
                <strong>Kelas:</strong> {{ $murid->kelas->nama_kelas ?? '-' }}
            </p>
        </div>
        <a href="{{ route('guru.nilai.create', $murid->id) }}" class="btn btn-add">
            <i class="bi bi-plus-circle me-1"></i> Tambah Nilai
        </a>
    </div>

    <!-- Tabel Nilai -->
    <div class="nilai-table shadow-card">
        <table class="table align-middle table-hover text-center">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mata Pelajaran</th>
                    <th>Keterangan</th>
                    <th>Nilai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($murid->nilai as $nilai)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="fw-semibold text-dark">{{ $nilai->mata_pelajaran }}</td>
                    <td class="keterangan-cell">
                        <div class="keterangan-wrapper">
                            @if($nilai->tugas) <span>Tugas</span> @endif
                            @if($nilai->ulangan_harian) <span>Ulangan Harian</span> @endif
                            @if($nilai->uts) <span>UTS</span> @endif
                            @if($nilai->uas) <span>UAS</span> @endif
                        </div>
                    </td>
                    <td><strong>{{ $nilai->tugas ?? $nilai->ulangan_harian ?? $nilai->uts ?? $nilai->uas ?? '-' }}</strong></td>
                    <td>
                        <a href="{{ route('guru.nilai.edit', $nilai->id) }}" class="btn btn-edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('guru.nilai.destroy', $nilai->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-delete" onclick="return confirm('Hapus nilai ini?')">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-muted py-4">Belum ada nilai yang diinput.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Tombol Kembali -->
    <div class="text-start mt-4">
        <a href="{{ route('guru.nilai.index', $murid->kelas_id) }}" class="btn btn-back">
            Kembali ke Data Siswa
        </a>
    </div>
</div>
@endsection
