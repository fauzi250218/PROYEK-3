@extends('layouts.guru')

@section('title', 'Data Siswa - ' . ($kelas->nama_kelas ?? ''))

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/guru/manajemen-siswa/data-siswa.css') }}">
@endsection

@section('content')
<div class="container py-4">

    <h3 class="fw-bold text-dark mb-3">Data Siswa di {{ $kelas->nama_kelas }}</h3>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($kelas->murids->count() > 0)
                <table class="table table-bordered table-hover">
                    <thead class="table-success">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Jenis Kelamin</th>
                            <th>No. WhatsApp</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kelas->murids as $index => $murid)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $murid->nis }}</td>
                                <td>{{ $murid->nama }}</td>
                                <td>{{ $murid->email ?? '-' }}</td>
                                <td>{{ $murid->jenis_kelamin ?? '-' }}</td>
                                <td>{{ $murid->nomer_whatsapp ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-warning text-center">
                    Belum ada siswa di kelas ini.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
