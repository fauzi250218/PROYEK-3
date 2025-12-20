@extends('layouts.admin')

@section('content')
<style>
/* =====================================================
   SPP BILLING – ENTERPRISE SAAS STYLE (FINAL)
   ===================================================== */

body {
    background:#f1f5f9;
    font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont;
}

.spp-container {
    max-width: 1450px;
    margin: 0 auto;
}

/* ===== PAGE HEADER ===== */
.page-header {
    display:flex;
    justify-content:space-between;
    align-items:flex-end;
    margin-bottom:30px;
}

.page-title h1 {
    font-size:1.75rem;
    font-weight:700;
    color:#0f172a;
}

.page-title span {
    font-size:.85rem;
    color:#64748b;
}

.btn-create {
    background:#0040d7;
    color:#fff;
    padding:12px 22px;
    border-radius:10px;
    font-size:.8rem;
    font-weight:600;
    text-decoration:none;
}

/* ===== KPI ===== */
.kpi {
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
    margin-bottom:28px;
}

.kpi-card {
    background:#ffffff;
    border-radius:16px;
    padding:22px;
    box-shadow:0 8px 24px rgba(15,23,42,.05);
}

.kpi-card small {
    font-size:.7rem;
    letter-spacing:.06em;
    color:#64748b;
}

.kpi-card strong {
    display:block;
    margin-top:10px;
    font-size:2rem;
    font-weight:700;
    color:#020617;
}

/* ===== TABLE ===== */
.table-wrap {
    background:#fff;
    border-radius:18px;
    box-shadow:0 12px 32px rgba(15,23,42,.06);
    overflow:hidden;
}

.table-toolbar {
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:18px 22px;
    border-bottom:1px solid #e5e7eb;
}

.table-toolbar input {
    width:320px;
    padding:10px 16px;
    border-radius:10px;
    border:1px solid #d1d5db;
    font-size:.8rem;
}

/* ===== TABLE ===== */
table {
    width:100%;
    border-collapse:collapse;
}

thead {
    background:#f8fafc;
}

thead th {
    padding:14px 18px;
    font-size:.65rem;
    letter-spacing:.08em;
    color:#64748b;
    text-align:left;
}

tbody td {
    padding:18px;
    font-size:.8rem;
    color:#0f172a;
    border-bottom:1px solid #f1f5f9;
}

tbody tr:hover {
    background:#f8fafc;
}

.money {
    font-family:ui-monospace, monospace;
    font-weight:600;
}

/* ===== STATUS ===== */
.status {
    display:flex;
    align-items:center;
    gap:8px;
    font-weight:600;
    font-size:.7rem;
}

.dot {
    width:8px;
    height:8px;
    border-radius:50%;
}

.lunas .dot { background:#22c55e; }
.belum .dot { background:#f59e0b; }

/* ===== ACTION ===== */
.action-btn {
    background:#0040d7;
    color:#fff;
    padding:8px 16px;
    border-radius:8px;
    font-size:.7rem;
    text-decoration:none;
    display:inline-block;
}

.action-btn.disabled {
    background:#e5e7eb;
    color:#9ca3af;
    pointer-events:none;
}
</style>

<div class="spp-container">

    {{-- HEADER --}}
    <div class="page-header">
        <div class="page-title">
            <h1>Tagihan SPP</h1>
            <span>Manajemen pembayaran sekolah</span>
        </div>

        <a href="{{ route('admin.pembayaran-spp.create') }}" class="btn-create">
            Tagihan Baru
        </a>
    </div>

    {{-- KPI --}}
    <div class="kpi">
        <div class="kpi-card">
            <small>TOTAL TAGIHAN</small>
            <strong>{{ $tagihan->count() }}</strong>
        </div>
        <div class="kpi-card">
            <small>LUNAS</small>
            <strong>{{ $tagihan->where('status','lunas')->count() }}</strong>
        </div>
        <div class="kpi-card">
            <small>BELUM DIBAYAR</small>
            <strong>{{ $tagihan->where('status','belum_bayar')->count() }}</strong>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-wrap">
        <div class="table-toolbar">
            <strong>Daftar Tagihan</strong>
            <input type="text" id="search" placeholder="Cari murid / NIS / kelas">
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIS</th>
                    <th>Nama Murid</th>
                    <th>Kelas</th>
                    <th>Periode</th>
                    <th>Nominal</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody id="tableBody">
                @forelse($tagihan as $t)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $t->murid->nis }}</td>
                    <td><strong>{{ $t->murid->nama }}</strong></td>
                    <td>{{ $t->murid->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $t->bulan }} {{ $t->tahun }}</td>
                    <td class="money">
                        Rp {{ number_format($t->nominal,0,',','.') }}
                    </td>
                    <td>
                        <div class="status {{ $t->status === 'lunas' ? 'lunas' : 'belum' }}">
                            <span class="dot"></span>
                            {{ strtoupper(str_replace('_',' ',$t->status)) }}
                        </div>
                    </td>
                    <td>
                        @if($t->status === 'belum_bayar')
                            <a href="{{ route('admin.pembayaran-spp.bayar', $t->id) }}"
                               class="action-btn">
                                Bayar
                            </a>
                        @else
                            <span class="action-btn disabled">Selesai</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8"
                        style="text-align:center;padding:40px;color:#64748b">
                        Belum ada data tagihan
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<script>
document.getElementById('search').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#tableBody tr').forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(q)
            ? ''
            : 'none';
    });
});
</script>
@endsection
