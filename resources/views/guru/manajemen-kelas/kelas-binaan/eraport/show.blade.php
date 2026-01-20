@extends('layouts.guru')
@section('title', 'E-Raport - ' . $murid->nama)

@section('content')
<div class="container py-4">

    <h4>E-RAPORT</h4>

    <p><strong>Nama:</strong> {{ $murid->nama }}</p>
    <p><strong>NIS:</strong> {{ $murid->nis }}</p>
    <p><strong>Kelas:</strong> {{ $murid->kelas->nama_kelas }}</p>
    <p><strong>Sekolah:</strong> SMP 1 MARS</p>

    <hr>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mata Pelajaran</th>
                <th>Nilai Akhir</th>
                <th>Catatan Perkembangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mapels as $mapel)
            <tr>
                <td>{{ $mapel }}</td>
                <td>
                    {{ $nilai->where('mata_pelajaran', $mapel)->first()->rata_rata ?? '-' }}
                </td>
                <td>
                    {{ $catatanMapel[$mapel]->catatan ?? '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <a href="{{ route('guru.kelas.binaan.eraport.download', $murid->id) }}" class="btn btn-success mt-3">
        Download PDF
    </a>

</div>
@endsection
