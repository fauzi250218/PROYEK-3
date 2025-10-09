@extends('layouts.admin')

@section('title', 'Kelola Siswa di Kelas ' . $kelas->nama_kelas)

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin-kelas-index.css') }}">
@endsection

@section('content')
<div class="container-fluid mt-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="page-title">Kelola Siswa - Kelas {{ $kelas->nama_kelas }}</h4>
        <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <p><strong>Nama Kelas:</strong> {{ $kelas->nama_kelas }}</p>
            <p><strong>Wali Kelas:</strong> {{ $kelas->wali_kelas ?? '-' }}</p>
            <p><strong>Deskripsi:</strong> {{ $kelas->deskripsi ?? '-' }}</p>
        </div>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            Tambahkan Siswa ke Kelas
        </div>
        <div class="card-body">
            <form action="{{ route('admin.kelas.tambahMurid', $kelas->id) }}" method="POST" class="d-flex align-items-center">
                @csrf
                <div class="flex-grow-1 me-2">
                    <select name="murid_id" class="form-select" required>
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($muridBelumMasukKelas as $murid)
                            <option value="{{ $murid->id }}">{{ $murid->nama }} ({{ $murid->nis }})</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Tambah
                </button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <strong>Daftar Siswa di Kelas {{ $kelas->nama_kelas }}</strong>
        </div>
        <div class="card-body">
            @if($muridDalamKelas->count())
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
                            @foreach($muridDalamKelas as $index => $murid)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $murid->nis }}</td>
                                    <td>{{ $murid->nama }}</td>
                                    <td>{{ $murid->email }}</td>
                                    <td>{{ $murid->jenis_kelamin }}</td>
                                    <td>{{ $murid->nomer_whatsapp }}</td>
                                    <td>
                                        <form action="{{ route('admin.kelas.hapusMurid', ['kelas_id' => $kelas->id, 'murid_id' => $murid->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus siswa ini dari kelas?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
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
                    Belum ada siswa di kelas ini.
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
