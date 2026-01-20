@extends('layouts.admin')

@section('content')
<style>
/* =================================================
   ADMIN SPP – CREATE BILLING (FINAL)
   ================================================= */

body {
    background:#eef1f7;
    font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont;
}

/* layout */
.spp-page {
    max-width:1200px;
    margin:0 auto;
}

/* hero */
.spp-hero {
    background:linear-gradient(135deg,#1e293b,#334155);
    border-radius:18px;
    padding:32px;
    color:#fff;
    margin-bottom:28px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.spp-hero h2 {
    font-weight:700;
    margin-bottom:6px;
}

.spp-hero p {
    margin:0;
    opacity:.85;
    font-size:.9rem;
}

.spp-badge {
    background:rgba(255,255,255,.15);
    padding:10px 18px;
    border-radius:999px;
    font-size:.8rem;
}

/* grid */
.spp-grid {
    display:grid;
    grid-template-columns:2.3fr 1fr;
    gap:24px;
}

/* panel */
.spp-panel {
    background:#fff;
    border-radius:18px;
    box-shadow:0 20px 40px rgba(0,0,0,.08);
    padding:28px;
}

.spp-summary {
    background:#fff;
    border-radius:18px;
    box-shadow:0 20px 40px rgba(0,0,0,.08);
    padding:24px;
    height:fit-content;
}

/* section */
.spp-section {
    margin-bottom:26px;
}

.spp-section h5 {
    font-size:.9rem;
    font-weight:700;
    margin-bottom:14px;
    color:#0f172a;
}

/* form */
.form-label {
    font-size:.75rem;
    font-weight:600;
    color:#475569;
}

.form-control,
.form-select {
    border-radius:12px;
    height:48px;
    font-size:.9rem;
}

.form-control:focus,
.form-select:focus {
    border-color:#6366f1;
    box-shadow:0 0 0 4px rgba(99,102,241,.15);
}

/* info */
.spp-info {
    background:#f1f5f9;
    border-radius:14px;
    padding:16px;
    font-size:.8rem;
    color:#334155;
}

/* summary */
.spp-summary-item {
    display:flex;
    justify-content:space-between;
    margin-bottom:14px;
    font-size:.85rem;
}

.spp-summary-item span:first-child {
    color:#64748b;
}

.spp-summary-total {
    margin-top:18px;
    padding-top:14px;
    border-top:1px dashed #cbd5e1;
    font-weight:700;
}

/* action */
.spp-actions {
    display:flex;
    justify-content:space-between;
    margin-top:28px;
}

.btn-primary {
    background:linear-gradient(135deg,#6366f1,#4f46e5);
    border:none;
    padding:12px 32px;
    font-weight:600;
    border-radius:12px;
}

.btn-primary:hover {
    opacity:.95;
}
</style>

<div class="container-fluid spp-page">

    {{-- HERO --}}
    <div class="spp-hero">
        <div>
            <h2>Buat Tagihan SPP</h2>
            <p>Membuat tagihan pembayaran siswa</p>
        </div>
        <div class="spp-badge">
            Billing System
        </div>
    </div>

    {{-- GRID --}}
    <div class="spp-grid">

        {{-- FORM --}}
        <div class="spp-panel">
            <form action="{{ route('admin.pembayaran-spp.store') }}" method="POST">
                @csrf

                {{-- TARGET --}}
                <div class="spp-section">
                    <h5>Target Siswa</h5>

                    <label class="form-label">Kelas</label>
                    <select name="kelas_id" class="form-select">
                        <option value="all">Semua Kelas</option>
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- PERIODE --}}
                <div class="spp-section">
                    <h5>Periode Tagihan</h5>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label">Bulan</label>
                            <select name="bulan" class="form-select">
                                @foreach ([
                                    'Januari','Februari','Maret','April','Mei','Juni',
                                    'Juli','Agustus','September','Oktober','November','Desember'
                                ] as $bulan)
                                    <option value="{{ $bulan }}">{{ $bulan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Tahun</label>
                            <input type="number"
                                   name="tahun"
                                   class="form-control"
                                   value="{{ date('Y') }}">
                        </div>
                    </div>
                </div>

                {{-- NOMINAL --}}
                <div class="spp-section">
                    <h5>Nominal Tagihan</h5>

                    <input type="number"
                           id="nominalInput"
                           name="nominal"
                           class="form-control"
                           placeholder="250000">
                </div>

                {{-- INFO --}}
                <div class="spp-section">
                    <div class="spp-info">
                        Tagihan akan dibuat otomatis untuk siswa sesuai target.
                        Pembayaran dilakukan oleh siswa melalui sistem Midtrans.
                    </div>
                </div>

                {{-- ACTION --}}
                <div class="spp-actions">
                    <a href="{{ route('admin.pembayaran-spp.index') }}"
                       class="btn btn-outline-secondary">
                        Kembali
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Simpan Tagihan
                    </button>
                </div>
            </form>
        </div>

        {{-- SUMMARY --}}
        <div class="spp-summary">
            <h5 class="mb-3">Ringkasan</h5>

            <div class="spp-summary-item">
                <span>Target</span>
                <span id="sumTarget">Semua Kelas</span>
            </div>

            <div class="spp-summary-item">
                <span>Periode</span>
                <span id="sumPeriode">-</span>
            </div>

            <div class="spp-summary-item">
                <span>Nominal / Siswa</span>
                <span id="sumNominal">Rp 0</span>
            </div>

            <div class="spp-summary-total">
                Total akan dihitung otomatis
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const kelas   = document.querySelector('[name="kelas_id"]');
    const bulan   = document.querySelector('[name="bulan"]');
    const tahun   = document.querySelector('[name="tahun"]');
    const nominal = document.getElementById('nominalInput');

    function formatRp(num) {
        return 'Rp ' + (num || 0).toLocaleString('id-ID');
    }

    function updateSummary() {
        document.getElementById('sumTarget').innerText =
            kelas.value === 'all'
                ? 'Semua Kelas'
                : kelas.options[kelas.selectedIndex].text;

        document.getElementById('sumPeriode').innerText =
            bulan.value + ' ' + tahun.value;

        document.getElementById('sumNominal').innerText =
            formatRp(parseInt(nominal.value));
    }

    [kelas, bulan, tahun, nominal].forEach(el => {
        el.addEventListener('input', updateSummary);
    });

    updateSummary();
});
</script>
@endsection
