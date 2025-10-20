@extends('layouts.admin')

@section('title', 'Kelola Siswa di Kelas ' . $kelas->nama_kelas)

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="page-title">Kelola Siswa - Kelas {{ $kelas->nama_kelas }}</h4>
        <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Informasi kelas -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <p><strong>Nama Kelas:</strong> {{ $kelas->nama_kelas }}</p>
            <p><strong>Wali Kelas:</strong> {{ $kelas->guru && $kelas->guru->user ? $kelas->guru->user->name : '-' }}</p>
            <p><strong>Deskripsi:</strong> {{ $kelas->deskripsi ?? '-' }}</p>
        </div>
    </div>

    <!-- Daftar Siswa di Kelas -->
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
