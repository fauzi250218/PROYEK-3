@extends('layouts.admin')

@section('title', 'Data Kelas')

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between mb-3">
        <h4>Data Kelas</h4>
        <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary">+ Tambah Kelas</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse($kelas as $jenjang => $daftar)
        <h5 class="fw-bold mt-3">Kelas {{ $jenjang }}</h5>
        <div class="row">
            @foreach($daftar as $k)
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="text-primary">{{ $k->nama_kelas }}</h5>
                            <p><strong>Wali Kelas:</strong> {{ $k->guru->user->name ?? 'Belum ada wali' }}</p>
                            <p><strong>Deskripsi:</strong> {{ $k->deskripsi ?? '-' }}</p>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.kelas.kelolaMurid', $k->id) }}" class="btn btn-info btn-sm">Kelola Siswa</a>
                                <div>
                                    <a href="{{ route('admin.kelas.edit', $k->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                    <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @empty
        <div class="alert alert-secondary mt-3">Belum ada data kelas.</div>
    @endforelse
</div>
@endsection
