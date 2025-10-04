@extends('layouts.admin')

@section('title', 'Data Guru')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin-index-guru.css') }}">
@endsection

@section('content')
<div class="container-fluid guru-index-page">

    <!-- Header atas -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="page-title">Guru / Pengajar</h5>
        <div class="d-flex align-items-center">
            <a href="#" class="export-link me-3">Export CSV</a>
            <a href="{{ route('admin.guru.create') }}" class="btn btn-primary">Tambahkan Guru</a>
        </div>
    </div>

    <!-- Search dengan ikon -->
    <div class="mb-3 search-wrapper">
        <div class="input-group search-box">
            <span class="input-group-text">
                <i class="bi bi-search"></i>
            </span>
            <input type="text" id="searchInput" class="form-control" placeholder="Cari berdasarkan nama atau email">
        </div>
    </div>

    <!-- Jika ada data -->
    @if($guru->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="guruTable">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Kelas</th>
                        <th>Jenis Kelamin</th>
                        <th>No. WhatsApp</th>
                        <th>Mata Pelajaran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($guru as $index => $g)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $g->nama }}</td>
                            <td>{{ $g->email }}</td>
                            <td>{{ $g->kelas }}</td>
                            <td>{{ $g->jenis_kelamin }}</td>
                            <td>{{ $g->nomer_whatsapp }}</td>
                            <td>{{ $g->mata_pelajaran }}</td>
                            <td>
                                <a href="{{ route('admin.guru.edit', $g->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('admin.guru.destroy', $g->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus guru ini?')">
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
        <!-- Empty State -->
        <div class="empty-box text-center d-flex flex-column justify-content-center align-items-center">
            <h5 class="fw-bold">Tidak ada Guru saat ini!</h5>
            <p class="text-muted">Guru akan muncul disini setelah ditambahkan</p>
        </div>
    @endif
</div>
@endsection
