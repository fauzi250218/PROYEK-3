@extends('layouts.guru')

@section('content')

<style>
:root{
    --bg:#f4f7f2;
    --card:#ffffff;
    --text:#020617;
    --muted:#64748b;
    --primary:#16a34a;
    --warning:#f59e0b;
    --danger:#dc2626;
    --border:#e5e7eb;
}

/* HEADER */
.page-header{ margin-bottom:24px; }
.page-header h1{ font-size:1.8rem; font-weight:800; }

/* STUDENT INFO */
.student-info{
    background:#fff;
    padding:18px;
    border-radius:14px;
    box-shadow:0 15px 40px rgba(2,6,23,.06);
    margin-bottom:26px;
}
.student-info strong{ font-size:1.1rem; }

/* TABLE */
.card{
    background:#fff;
    border-radius:18px;
    padding:24px;
    box-shadow:0 25px 60px rgba(2,6,23,.08);
}
.table{
    width:100%;
    border-collapse:collapse;
    font-size:.9rem;
}
.table th{
    text-align:left;
    font-size:.75rem;
    color:var(--muted);
    text-transform:uppercase;
    padding:12px 10px;
    border-bottom:1px solid var(--border);
}
.table td{
    padding:14px 10px;
    border-bottom:1px solid var(--border);
}

/* BADGE */
.badge{
    padding:4px 12px;
    border-radius:999px;
    font-size:.7rem;
    font-weight:700;
}
.badge.hadir{ background:#dcfce7; color:#166534; }
.badge.izin{ background:#fef3c7; color:#92400e; }
.badge.sakit{ background:#e0f2fe; color:#075985; }
.badge.alpha{ background:#fee2e2; color:#991b1b; }

/* ACTION */
.action-bar{ margin-top:22px; text-align:right; }
.btn-back{
    background:#e5e7eb;
    color:#020617;
    padding:10px 20px;
    border-radius:10px;
    text-decoration:none;
    font-weight:700;
}
</style>

{{-- HEADER --}}
<div class="page-header">
    <h1>Detail Kehadiran Siswa</h1>
</div>

{{-- NAMA SISWA --}}
<div class="student-info">
    <strong>Nama Siswa:</strong> {{ $murid->nama }}
</div>

@php
/**
 * REKAP KEHADIRAN PER MATA PELAJARAN
 */
$rekapMapel = [];

foreach ($kelas->sesi as $sesi) {

    $absen = $sesi->kehadiran
        ->where('murid_id', $murid->id)
        ->first();

    if (!$absen) continue;

    // ✅ AKSES MAPEL YANG BENAR
    $mapel = $sesi->jadwal->mata_pelajaran ?? 'Tidak diketahui';

    if (!isset($rekapMapel[$mapel])) {
        $rekapMapel[$mapel] = [
            'H' => 0,
            'I' => 0,
            'S' => 0,
            'A' => 0,
            'keterangan' => [],
        ];
    }

    $rekapMapel[$mapel][$absen->status]++;

    if ($absen->keterangan) {
        $rekapMapel[$mapel]['keterangan'][] = $absen->keterangan;
    }
}
@endphp

{{-- TABLE REKAP --}}
<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Mata Pelajaran</th>
                <th>Hadir</th>
                <th>Izin</th>
                <th>Sakit</th>
                <th>Alpha</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekapMapel as $mapel => $r)
                <tr>
                    <td><strong>{{ $mapel }}</strong></td>
                    <td><span class="badge hadir">{{ $r['H'] }}</span></td>
                    <td><span class="badge izin">{{ $r['I'] }}</span></td>
                    <td><span class="badge sakit">{{ $r['S'] }}</span></td>
                    <td><span class="badge alpha">{{ $r['A'] }}</span></td>
                    <td>
                        {{ collect($r['keterangan'])->unique()->implode(', ') ?: '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="color:var(--muted)">
                        Tidak ada data kehadiran.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- ACTION --}}
<div class="action-bar">
    <a href="{{ route('guru.kelas.binaan.kehadiran', $kelas->id) }}" class="btn-back">
        ← Kembali ke Rekap
    </a>
</div>

@endsection
