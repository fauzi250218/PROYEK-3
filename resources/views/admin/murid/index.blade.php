@extends('layouts.admin')

@section('title', 'Data Siswa')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin-siswa-index.css') }}">
@endsection

@section('content')
<div class="container-fluid murid-index-page">

    <!-- ================= HEADER ================= -->
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            {{-- 🔥 HEADER DIPERBESAR --}}
            <h2 class="page-title fw-bold mb-1">Data Siswa</h2>

            {{-- PARAGRAF PENJELASAN --}}
            <p class="text-muted mb-0" style="max-width: 520px;">
               Manajemen Data Siswa.
            </p>
        </div>

        <a href="{{ route('admin.murid.create') }}" class="btn btn-primary">
            Tambahkan Siswa
        </a>
    </div>

    <!-- ================= TAB NAVIGASI ================= -->
    <ul class="nav nav-tabs mb-3" id="kelasTabs" role="tablist">
        @forelse($muridPerJenjang as $jenjang => $murids)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                        id="tab-{{ $jenjang }}"
                        data-bs-toggle="tab"
                        data-bs-target="#kelas-{{ $jenjang }}"
                        type="button"
                        role="tab"
                        aria-controls="kelas-{{ $jenjang }}"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                    Kelas {{ $jenjang }}
                </button>
            </li>
        @empty
            <li class="nav-item">
                <button class="nav-link active" disabled>
                    Tidak ada data siswa
                </button>
            </li>
        @endforelse
    </ul>

    <!-- ================= ISI TAB ================= -->
    <div class="tab-content" id="kelasTabsContent">
        @forelse($muridPerJenjang as $jenjang => $murids)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                 id="kelas-{{ $jenjang }}"
                 role="tabpanel"
                 aria-labelledby="tab-{{ $jenjang }}">

                @if($murids->count())
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
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
                                        <td>{{ $m->kelas->nama_kelas ?? '-' }}</td>
                                        <td>{{ $m->jenis_kelamin }}</td>
                                        <td>{{ $m->nomer_whatsapp ?? '-' }}</td>
                                        <td>
                                            <a href="{{ route('admin.murid.edit', $m->id) }}"
                                               class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i> Edit
                                            </a>

                                            <form action="{{ route('admin.murid.destroy', $m->id) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin hapus siswa ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
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
                        Belum ada siswa di kelas {{ $jenjang }}.
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
