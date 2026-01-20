@extends('layouts.guru')
@section('title', 'Perkembangan Belajar')

@section('content')
<div class="container py-4">

    <h3 class="fw-bold">Perkembangan Belajar — Kelas: {{ $kelas->nama_kelas }}</h3>

    <div class="mb-3">
        <small>
            Wali Kelas: {{ $guru->nama_lengkap ?? $guru->user->name ?? '-' }}
        </small>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th style="width: 70px;">Rank</th>
                        <th>Nama</th>
                        <th style="width: 140px;">NIS</th>
                        <th style="width: 140px;">Rata-Rata</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($murids as $murid)
                    <tr>
                        <td class="fw-bold">#{{ $murid->ranking }}</td>
                        <td>{{ $murid->nama }}</td>
                        <td>{{ $murid->nis }}</td>
                        <td>{{ number_format($murid->rata_rata, 2) }}</td>
                        <td>
                            <a 
                                href="{{ route('guru.kelas.binaan.perkembangan.show', [
                                    'id' => $kelas->id,
                                    'murid' => $murid->id
                                ]) }}" 
                                class="btn btn-sm btn-primary"
                            >
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-3">
                            Belum ada murid di kelas ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection
