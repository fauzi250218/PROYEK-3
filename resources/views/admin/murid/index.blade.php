@extends('layouts.admin')

@section('title', 'Data Murid')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin-siswa-index.css') }}">
@endsection

@section('content')
<div class="container-fluid murid-index-page">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="page-title">Data Siswa</h5>
        <div class="d-flex align-items-center">
            <a href="#" class="export-link me-3">Export CSV</a>
            <a href="{{ route('admin.murid.create') }}" class="btn btn-primary">Tambahkan Siswa</a>
        </div>
    </div>

    <!-- Tab Navigasi -->
    <ul class="nav nav-tabs mb-3" id="kelasTabs" role="tablist">
        @forelse($muridPerKelas as $kelas => $murids)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                        id="tab-{{ $kelas }}" 
                        data-bs-toggle="tab" 
                        data-bs-target="#kelas-{{ $kelas }}" 
                        type="button" 
                        role="tab" 
                        aria-controls="kelas-{{ $kelas }}" 
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    Kelas {{ $kelas }}
                </button>
            </li>
        @empty
            <li class="nav-item">
                <button class="nav-link active" disabled>Tidak ada data siswa</button>
            </li>
        @endforelse
    </ul>

    <!-- Isi Tab -->
    <div class="tab-content" id="kelasTabsContent">
        @forelse($muridPerKelas as $kelas => $murids)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                 id="kelas-{{ $kelas }}" 
                 role="tabpanel" 
                 aria-labelledby="tab-{{ $kelas }}">

                @if($murids->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Jenis Kelamin</th>
                                    <th>No. WhatsApp</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($murids as $index => $m)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $m->nis }}</td>
                                        <td>{{ $m->nama }}</td>
                                        <td>{{ $m->email }}</td>
                                        <td>{{ $m->jenis_kelamin }}</td>
                                        <td>{{ $m->nomer_whatsapp }}</td>
                                        <td>
                                            <a href="{{ route('admin.murid.edit', $m->id) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.murid.destroy', $m->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus siswa ini?')">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center text-muted py-4">
                        Belum ada siswa di kelas {{ $kelas }}.
                    </div>
                @endif
            </div>
        @empty
            <div class="tab-pane fade show active text-center text-muted py-4">
                Tidak ada data siswa yang tersedia.
            </div>
        @endforelse
    </div>
</div>
@endsection
