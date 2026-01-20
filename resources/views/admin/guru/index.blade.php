@extends('layouts.admin')

@section('title', 'Data Guru')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/guru/index.css') }}">
@endsection

@section('content')
<div class="container-fluid guru-index-page">

    {{-- ================= HEADER ================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            {{-- 🔥 TITLE DIPERBESAR --}}
            <h3 class="page-title fw-bold mb-1">Guru / Pengajar</h3>
            <small class="text-muted">
                Manajemen data guru dan kelas binaan
            </small>
        </div>

        {{-- ❌ ICON + DIHILANGKAN --}}
        <a href="{{ route('admin.guru.create') }}" class="btn btn-primary">
            Tambahkan Guru
        </a>
    </div>

    {{-- ================= SEARCH ================= --}}
    <div class="search-wrapper mb-4">
        <div class="input-group search-box">
            <span class="input-group-text">
                <i class="bi bi-search"></i>
            </span>
            <input
                type="text"
                id="searchInput"
                class="form-control"
                placeholder="Cari nama, email, kelas, atau mapel">
        </div>
    </div>

    {{-- ================= ALERT ================= --}}
    @if(session('success'))
        <div class="alert alert-success fade-in">
            {{ session('success') }}
        </div>
    @endif

    {{-- ================= CONTENT ================= --}}
    <div class="guru-card fade-in">

        @if($guru && $guru->count() > 0)

            <div class="table-responsive">
                <table class="table align-middle" id="guruTable">
                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Kelas Binaan</th>
                            <th>JK</th>
                            <th>No. WhatsApp</th>
                            <th>Mata Pelajaran</th>
                            <th width="160">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($guru as $index => $g)
                            <tr>
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <strong>{{ $g->user->name ?? '-' }}</strong>
                                </td>

                                <td class="text-muted">
                                    {{ $g->user->email ?? '-' }}
                                </td>

                                {{-- KELAS BINAAN --}}
                                <td>
                                    @php
                                        $kelasBinaan = \App\Models\Kelas::where('guru_id', $g->id)
                                            ->pluck('nama_kelas');
                                    @endphp

                                    @forelse($kelasBinaan as $kelas)
                                        <span class="badge-kelas">{{ $kelas }}</span>
                                    @empty
                                        <span class="text-muted">Belum ada kelas</span>
                                    @endforelse
                                </td>

                                <td>{{ $g->jenis_kelamin ?? '-' }}</td>
                                <td>{{ $g->nomer_whatsapp ?? '-' }}</td>
                                <td>{{ $g->mata_pelajaran ?? '-' }}</td>

                                <td>
                                    <a href="{{ route('admin.guru.edit', $g->id) }}"
                                       class="btn btn-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>

                                    <form action="{{ route('admin.guru.destroy', $g->id) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-danger btn-sm"
                                                onclick="return confirm('Yakin hapus guru ini?')">
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
            {{-- EMPTY STATE --}}
            <div class="empty-box text-center py-5">
                <h5 class="fw-bold mb-2">Belum ada data guru</h5>
                <p class="text-muted mb-3">
                    Data guru akan muncul setelah ditambahkan oleh admin
                </p>
                <a href="{{ route('admin.guru.create') }}" class="btn btn-primary">
                    Tambahkan Guru
                </a>
            </div>
        @endif

    </div>
</div>

{{-- ================= JS ================= --}}
<script>
document.getElementById('searchInput').addEventListener('keyup', function () {
    const filter = this.value.toLowerCase();
    const rows = document.querySelectorAll('#guruTable tbody tr');
    let visible = 0;

    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        const show = text.includes(filter);
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    this.style.background = filter ? '#eef2ff' : '#fff';

    let empty = document.getElementById('emptyRealtime');
    if (visible === 0 && rows.length > 0) {
        if (!empty) {
            const tbody = document.querySelector('#guruTable tbody');
            empty = document.createElement('tr');
            empty.id = 'emptyRealtime';
            empty.innerHTML = `
                <td colspan="8" class="text-center text-muted py-4">
                    Data tidak ditemukan
                </td>`;
            tbody.appendChild(empty);
        }
    } else {
        if (empty) empty.remove();
    }
});
</script>
@endsection
