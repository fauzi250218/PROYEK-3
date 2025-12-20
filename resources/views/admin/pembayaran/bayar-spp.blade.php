@extends('layouts.admin')

@section('content')
<style>
/* =====================================================
   SPP PAYMENT – MIDTRANS SNAP (FINAL WORKING)
   ===================================================== */

body {
    background:#f1f5f9;
    font-family: Inter, system-ui, -apple-system, BlinkMacSystemFont;
}

/* layout */
.pay-layout {
    max-width:1100px;
    margin:40px auto;
    display:grid;
    grid-template-columns:1.3fr 1fr;
    gap:30px;
}

/* card */
.card {
    background:#ffffff;
    border-radius:20px;
    padding:30px;
    box-shadow:0 20px 40px rgba(15,23,42,.08);
}

/* invoice */
.invoice-title {
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:28px;
}

.invoice-title h2 {
    font-size:1.5rem;
    font-weight:800;
}

.invoice-badge {
    background:#fff7ed;
    color:#9a3412;
    padding:6px 14px;
    font-size:.7rem;
    border-radius:999px;
    font-weight:700;
}

/* info */
.invoice-info {
    display:grid;
    grid-template-columns:repeat(2,1fr);
    gap:22px;
    margin-bottom:30px;
}

.invoice-info span {
    font-size:.7rem;
    color:#64748b;
}

.invoice-info strong {
    display:block;
    margin-top:6px;
    font-size:.95rem;
}

/* total */
.invoice-total {
    border-top:1px dashed #e5e7eb;
    padding-top:24px;
    display:flex;
    justify-content:space-between;
    align-items:flex-end;
}

.invoice-total span {
    font-size:.85rem;
    color:#64748b;
}

.invoice-total h1 {
    font-size:2.3rem;
    font-weight:900;
    font-family:ui-monospace, monospace;
}

/* action */
.btn-pay {
    width:100%;
    background:#2563eb;
    border:none;
    padding:16px;
    font-weight:700;
    border-radius:14px;
    cursor:pointer;
    color:#fff;
    margin-bottom:14px;
    font-size:.9rem;
}

.btn-pay:hover {
    background:#1d4ed8;
}

.btn-back {
    display:block;
    text-align:center;
    padding:14px;
    border-radius:14px;
    background:#f1f5f9;
    color:#0f172a;
    text-decoration:none;
    font-weight:600;
}

.secure-note {
    margin-top:26px;
    font-size:.7rem;
    color:#64748b;
    text-align:center;
}
</style>

<div class="pay-layout">

    {{-- ================= LEFT : INVOICE ================= --}}
    <div class="card">
        <div class="invoice-title">
            <h2>Invoice SPP</h2>
            <div class="invoice-badge">MENUNGGU PEMBAYARAN</div>
        </div>

        <div class="invoice-info">
            <div>
                <span>NAMA MURID</span>
                <strong>{{ $tagihan->murid->nama }}</strong>
            </div>

            <div>
                <span>NIS</span>
                <strong>{{ $tagihan->murid->nis }}</strong>
            </div>

            <div>
                <span>KELAS</span>
                <strong>{{ $tagihan->murid->kelas->nama_kelas ?? '-' }}</strong>
            </div>

            <div>
                <span>PERIODE</span>
                <strong>{{ $tagihan->bulan }} {{ $tagihan->tahun }}</strong>
            </div>

            <div>
                <span>SISTEM PEMBAYARAN</span>
                <strong>MIDTRANS (NON TUNAI)</strong>
            </div>

            <div>
                <span>STATUS</span>
                <strong>BELUM DIBAYAR</strong>
            </div>
        </div>

        <div class="invoice-total">
            <span>Total Pembayaran</span>
            <h1>Rp {{ number_format($tagihan->nominal,0,',','.') }}</h1>
        </div>
    </div>

    {{-- ================= RIGHT : ACTION ================= --}}
    <div class="card">
        <h3 style="margin-bottom:8px">Proses Pembayaran</h3>

        <p style="font-size:.8rem;color:#64748b;margin-bottom:20px">
            Pembayaran resmi diproses melalui Midtrans.
            Admin hanya bertindak sebagai perantara transaksi.
        </p>

        <button id="pay-button" class="btn-pay">
            Bayar Sekarang
        </button>

        <a href="{{ route('admin.pembayaran-spp.index') }}"
           class="btn-back">
            Kembali ke Daftar Tagihan
        </a>

        <div class="secure-note">
            Transaksi aman & terenkripsi melalui Midtrans
        </div>
    </div>

</div>

{{-- ================= MIDTRANS SNAP ================= --}}
<script
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.clientKey') }}">
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const payButton = document.getElementById('pay-button');

    payButton.addEventListener('click', function () {

        if (typeof snap === 'undefined') {
            alert('Midtrans Snap belum termuat');
            return;
        }

        snap.pay(@json($snapToken), {

            onSuccess: function () {

                // 🔥 WAJIB: update DB dulu
                fetch("{{ route('admin.pembayaran-spp.force-lunas', $tagihan->id) }}", {
                    method: "POST",
                    credentials: "same-origin",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    }
                })
                .then(() => {
                    window.location.href =
                        "{{ route('admin.pembayaran-spp.index') }}";
                });

            },

            onPending: function () {
                alert('Menunggu pembayaran...');
            },

            onError: function () {
                alert('Pembayaran gagal');
            }

        });

    });

});
</script>
@endsection
