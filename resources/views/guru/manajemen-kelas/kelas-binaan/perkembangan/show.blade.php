@extends('layouts.guru')
@section('title', 'Detail Perkembangan')

@section('content')
<div class="container py-4">

    {{-- Tombol kembali --}}
    <a href="{{ route('guru.kelas.binaan.perkembangan.index', $murid->kelas->id) }}" 
       class="btn btn-light mb-3">← Kembali</a>

    <div class="card mb-4">
        <div class="card-body">
            <h4>{{ $murid->nama }} 
                <small class="text-muted">({{ $murid->nis }})</small>
            </h4>

            <p>Kelas: {{ $murid->kelas->nama_kelas ?? '-' }}</p>

            <p>Rata-rata Nilai: 
                <strong>{{ number_format($murid->rata_rata, 2) }}</strong>
            </p>
        </div>
    </div>

    <div class="row">

        {{-- FORM TAMBAH CATATAN --}}
        <div class="col-md-6">
            <h5>Tambah Catatan Perkembangan</h5>

            <form action="{{ route('guru.kelas.binaan.perkembangan.storeCatatan', [
                'id' => $murid->kelas->id,
                'murid' => $murid->id
            ]) }}" method="POST">
                @csrf

                <div class="mb-2">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select" required>
                        <option value="Kognitif">Kognitif</option>
                        <option value="Motorik">Motorik</option>
                        <option value="Sosial-Emosional">Sosial-Emosional</option>
                        <option value="Bahasa">Bahasa</option>
                    </select>
                </div>

                <div class="mb-2">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" class="form-control" rows="4" required></textarea>
                </div>

                <div class="mb-2">
                    <label class="form-label">Tanggal (Opsional)</label>
                    <input type="date" name="tanggal" class="form-control">
                </div>

                <button class="btn btn-success">Simpan Catatan</button>
            </form>
        </div>

        {{-- LIST CATATAN --}}
        <div class="col-md-6">
            <h5>Riwayat Catatan</h5>

            @forelse($catatan as $c)
                <div class="mb-3 p-3 border rounded bg-white">

                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>{{ $c->kategori }}</strong>
                            <div class="text-muted small">
                                {{ $c->created_at->format('d M Y H:i') }} oleh 
                                {{ $c->guru->user->name ?? '-' }}
                            </div>
                        </div>

                        {{-- Hanya guru pembuat catatan yg boleh hapus --}}
                        @if($c->guru_id === $guru->id)
                            <form action="{{ route('guru.kelas.binaan.perkembangan.destroyCatatan', [
                                'id' => $murid->kelas->id,
                                'catatan' => $c->id
                            ]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Hapus catatan?')">
                                    Hapus
                                </button>
                            </form>
                        @endif

                    </div>

                    <p class="mt-2">{{ $c->catatan }}</p>
                </div>

            @empty
                <p class="text-muted">Belum ada catatan perkembangan.</p>
            @endforelse
        </div>

    </div>
</div>
@endsection
