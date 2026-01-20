@extends('layouts.guru')
@section('title', 'E-Raport')

@section('content')
<div class="container py-4">

    <!-- Header -->
    <div class="mb-4">
        <h3 class="fw-bold">E-Raport Kelas {{ $kelas->nama_kelas }}</h3>
        <p class="text-muted mb-1">Wali Kelas: <strong>{{ $guru->user->name }}</strong></p>
        <hr>
    </div>

    <!-- Card List Murid -->
    <div class="row g-3">
        @foreach($murids as $m)
        <div class="col-md-6 col-lg-4">
            <div class="card shadow-sm border-0 h-100">

                <div class="card-body">
                    <h5 class="card-title fw-bold mb-1">{{ $m->nama }}</h5>
                    <p class="text-muted mb-3">NIS: {{ $m->nis }}</p>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('guru.kelas.binaan.eraport.show', $m->id) }}"
                           class="btn btn-primary btn-sm">
                            <i class="bi bi-file-earmark-text"></i> Lihat Raport
                        </a>
                    </div>
                </div>

            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection
