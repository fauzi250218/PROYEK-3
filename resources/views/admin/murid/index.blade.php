@extends('layouts.admin')

@section('title', 'Data Murid')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin-siswa-index.css') }}">
@endsection

@section('content')
<div class="container-fluid murid-index-page">

    <!-- Header atas -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="page-title">Murid</h5>
        <div class="d-flex align-items-center">
            <a href="#" class="export-link me-3">Export CSV</a>
            <a href="{{ route('admin.murid.create') }}" class="btn btn-primary">Tambahkan Siswa</a>
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
    @if($murids->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="muridTable">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Kelas</th>
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
                            <td>{{ $m->kelas }}</td>
                            <td>{{ $m->jenis_kelamin }}</td>
                            <td>{{ $m->nomer_whatsapp }}</td>
                            <td>
                                <a href="{{ route('admin.murid.edit', $m->id) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('admin.murid.destroy', $m->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus Siswa ini?')">
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
            <h5 class="fw-bold">Tidak ada Siswa saat ini!</h5>
            <p class="text-muted">Siswa akan muncul disini setelah ditambahkan</p>
        </div>
    @endif
</div>
@endsection
