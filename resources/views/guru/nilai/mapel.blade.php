@extends('layouts.guru')
@section('title', 'Daftar Mata Pelajaran - ' . $kelas->nama_kelas)

@section('content')
<div class="container py-5">
    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-4">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h4 class="fw-bold text-success mb-1">
                        Mata Pelajaran di Kelas {{ $kelas->nama_kelas }}
                    </h4>
                    <p class="text-muted small mb-0">
                        Pilih mata pelajaran untuk melihat daftar muridnya.
                    </p>
                </div>
                <a href="{{ route('guru.nilai.semuaKelas') }}"
                   class="btn btn-outline-secondary btn-sm d-flex align-items-center">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
            </div>

            {{-- Daftar Mata Pelajaran --}}
            @if($mapelList->isEmpty())
                <div class="alert alert-warning text-center mb-0 rounded-3">
                    Belum ada jadwal mata pelajaran pada kelas ini.
                </div>
            @else
                <div class="list-group list-group-flush">
                    @foreach($mapelList as $mapel)
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-2 border-0 border-bottom">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 text-success fw-bold rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width:45px; height:45px;">
                                    {{ strtoupper(substr($mapel, 0, 2)) }}
                                </div>
                                <div>
                                    <div class="fw-semibold text-dark">{{ $mapel }}</div>
                                    <small class="text-muted">Klik untuk melihat daftar murid</small>
                                </div>
                            </div>
                            <a href="{{ route('guru.nilai.mapel.murid', ['kelasId' => $kelas->id, 'mapel' => $mapel]) }}"
                               class="btn btn-success btn-sm px-3 shadow-sm">
                                <i class="bi bi-people-fill me-1"></i> Lihat Murid
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
