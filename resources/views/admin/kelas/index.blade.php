@extends('layouts.admin')

@section('title', 'Data Kelas')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin-kelas-index.css') }}">
@endsection

@section('content')
<div class="container-fluid kelas-index-page">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="page-title">Data Kelas</h5>
        <a href="{{ route('admin.kelas.create') }}" class="btn btn-primary">+ Tambah Kelas</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- ✅ Tabs untuk Kelas 7, 8, 9 --}}
    @if($kelas->count())
        <ul class="nav nav-tabs" id="kelasTabs" role="tablist">
            @foreach($kelas as $jenjang => $daftar)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                            id="tab{{ $jenjang }}" 
                            data-bs-toggle="tab" 
                            data-bs-target="#kelas{{ $jenjang }}" 
                            type="button" 
                            role="tab" 
                            aria-controls="kelas{{ $jenjang }}" 
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                        Kelas {{ $jenjang }}
                    </button>
                </li>
            @endforeach
        </ul>

        <div class="tab-content mt-3" id="kelasTabsContent">
            @foreach($kelas as $jenjang => $daftar)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                     id="kelas{{ $jenjang }}" 
                     role="tabpanel" 
                     aria-labelledby="tab{{ $jenjang }}">
                    @if($daftar->count())
                        <div class="row">
                            @foreach($daftar as $k)
                                <div class="col-md-4 col-sm-6 mb-4">
                                    <div class="card kelas-card shadow-sm">
                                        <div class="card-body">
                                            <h5 class="card-title text-primary mb-0">{{ $k->nama_kelas }}</h5>
                                            <p><strong>Wali Kelas:</strong> {{ $k->wali_kelas ?? '-' }}</p>
                                            <p class="text-muted"><strong>Deskripsi:</strong> {{ $k->deskripsi ?? '-' }}</p>

                                            <div class="d-flex justify-content-between mt-3">
                                                <a href="{{ route('admin.kelas.kelolaMurid', $k->id) }}" class="btn btn-info btn-sm">
                                                    <i class="bi bi-people"></i> Kelola Siswa
                                                </a>
                                                <div class="d-flex gap-1">
                                                    <a href="{{ route('admin.kelas.edit', $k->id) }}" class="btn btn-warning btn-sm">
                                                        <i class="bi bi-pencil"></i> Edit
                                                    </a>
                                                    <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kelas ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="bi bi-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <h5>Belum ada data untuk kelas {{ $jenjang }}.</h5>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center text-muted py-5">
            <h5>Belum ada data kelas.</h5>
            <p>Silakan tambahkan kelas baru menggunakan tombol di atas.</p>
        </div>
    @endif
</div>
@endsection
