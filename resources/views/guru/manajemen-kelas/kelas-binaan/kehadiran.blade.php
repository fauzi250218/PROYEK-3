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
.page-header{ margin-bottom:30px; }
.page-header h1{ font-size:2rem; font-weight:800; }
.page-header p{ color:var(--muted); margin-top:6px; }

/* SUMMARY */
.summary-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:22px;
    margin-bottom:26px;
}
.summary-card{
    background:#fff;
    border-radius:18px;
    padding:24px;
    box-shadow:0 25px 60px rgba(2,6,23,.08);
}
.summary-card span{ font-size:.8rem; color:var(--muted); }
.summary-card h2{ font-size:2rem; font-weight:800; color:var(--primary); }

/* FILTER */
.filter-box{
    background:#fff;
    padding:16px;
    border-radius:14px;
    box-shadow:0 15px 40px rgba(2,6,23,.06);
    margin-bottom:30px;
    display:flex;
    gap:12px;
}
.filter-box select{
    flex:1;
    padding:12px;
    border-radius:10px;
    border:1px solid var(--border);
}
.filter-box button{
    background:var(--primary);
    color:#fff;
    border:none;
    padding:12px 22px;
    border-radius:10px;
    font-weight:700;
    cursor:pointer;
}

/* GRID */
.student-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(300px,1fr));
    gap:26px;
}
.student-card{
    background:#fff;
    border-radius:20px;
    padding:22px;
    box-shadow:0 20px 50px rgba(2,6,23,.08);
    display:flex;
    flex-direction:column;
}
.student-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:12px;
}
.student-name{ font-weight:800; }
.student-percent{
    background:var(--primary);
    color:#fff;
    font-size:.75rem;
    padding:4px 10px;
    border-radius:999px;
}

/* PROGRESS */
.progress{
    height:10px;
    background:#e5e7eb;
    border-radius:999px;
    margin-bottom:16px;
}
.progress-bar{ height:100%; border-radius:999px; }

/* STATS */
.stats{
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:6px 16px;
    font-size:.85rem;
    margin-bottom:16px;
}
.stats div{ display:flex; justify-content:space-between; color:var(--muted); }
.stats strong{ color:var(--text); }

/* ACTION */
.card-action{
    margin-top:auto;
    text-align:right;
}
.btn-detail{
    background:transparent;
    border:1px solid var(--primary);
    color:var(--primary);
    padding:7px 16px;
    border-radius:999px;
    font-size:.75rem;
    font-weight:700;
    text-decoration:none;
    transition:.2s;
}
.btn-detail:hover{
    background:var(--primary);
    color:#fff;
}
</style>

{{-- HEADER --}}
<div class="page-header">
    <h1>Rekap Kehadiran Siswa</h1>
    <p>Kelas {{ $kelas->nama_kelas }} · {{ $periodeAktif }}</p>
</div>

{{-- SUMMARY --}}
<div class="summary-grid">
    <div class="summary-card">
        <span>Total Siswa</span>
        <h2>{{ $totalSiswa }}</h2>
    </div>
    <div class="summary-card">
        <span>Rata-rata Kehadiran</span>
        <h2>{{ $rataRataKehadiran }}%</h2>
    </div>
    <div class="summary-card">
        <span>Periode</span>
        <h2>{{ $periodeAktif }}</h2>
    </div>
</div>

{{-- FILTER --}}
<form method="GET">
    <div class="filter-box">
        <select name="bulan">
            <option value="all" {{ request('bulan','all')=='all'?'selected':'' }}>
                Pilih Bulan
            </option>
            @foreach([
                1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
                5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
                9=>'September',10=>'Oktober',11=>'November',12=>'Desember'
            ] as $k=>$bulan)
                <option value="{{ $k }}" {{ request('bulan')==$k?'selected':'' }}>
                    {{ $bulan }}
                </option>
            @endforeach
        </select>

        <select name="tahun">
            @foreach($daftarTahun as $tahun)
                <option value="{{ $tahun }}"
                    {{ request('tahun',now()->year)==$tahun?'selected':'' }}>
                    {{ $tahun }}
                </option>
            @endforeach
        </select>

        <button type="submit">Tampilkan</button>
    </div>
</form>

{{-- GRID SISWA --}}
<div class="student-grid">
@forelse($rekap as $r)
@php
    $color = $r['persen'] >= 90 ? 'var(--primary)' :
             ($r['persen'] >= 75 ? 'var(--warning)' : 'var(--danger)');
@endphp
<div class="student-card">
    <div class="student-header">
        <div class="student-name">{{ $r['nama'] }}</div>
        <div class="student-percent">{{ $r['persen'] }}%</div>
    </div>

    <div class="progress">
        <div class="progress-bar" style="width:{{ $r['persen'] }}%;background:{{ $color }}"></div>
    </div>

    <div class="stats">
        <div>Hadir <strong>{{ $r['hadir'] }}</strong></div>
        <div>Sakit <strong>{{ $r['sakit'] }}</strong></div>
        <div>Izin <strong>{{ $r['izin'] }}</strong></div>
        <div>Alpha <strong>{{ $r['alpha'] }}</strong></div>
    </div>

    {{-- BUTTON DETAIL --}}
    <div class="card-action">
        <a
            href="{{ route('guru.kelas.binaan.kehadiran.detail', [
                'kelas' => $kelas->id,
                'murid' => $r['murid_id'],
                'bulan' => request('bulan','all'),
                'tahun' => request('tahun', now()->year),
            ]) }}"
            class="btn-detail"
        >
            Detail
        </a>
    </div>
</div>
@empty
    <p style="color:var(--muted)">Tidak ada data kehadiran.</p>
@endforelse
</div>

@endsection
