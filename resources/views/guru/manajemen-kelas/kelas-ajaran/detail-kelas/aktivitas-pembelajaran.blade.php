<!-- =====================================================
     HEADER AKTIVITAS PEMBELAJARAN
===================================================== -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">Aktivitas Pembelajaran</h4>

    <div class="d-flex gap-2">
        <button class="btn btn-outline-primary px-3" onclick="syncSesi()">
            <i class="bi bi-arrow-repeat me-1"></i> Sinkronisasi
        </button>

        <button class="btn btn-success px-4" onclick="openPopupAktivitas()">
            <i class="bi bi-plus-circle me-1"></i> Tambah Sesi
        </button>
    </div>
</div>



<!-- =====================================================
     POPUP TAMBAH SESI
===================================================== -->

<div id="popupOverlay" class="popup-overlay"></div>

<div id="popupAktivitas" class="popup-box">

    <div class="popup-header">
        <h5 class="fw-bold m-0">Tambah Sesi Pembelajaran</h5>
        <i class="bi bi-x-lg popup-close" onclick="closePopupAktivitas()"></i>
    </div>

    <form action="{{ route('guru.kelas.ajaran.sesi.store') }}" method="POST">
        @csrf
        <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">

        <!-- JUDUL -->
        <label class="fw-semibold small mt-2">Judul Sesi</label>
        <input type="text" name="judul_sesi" class="form-control mb-3 shadow-sm-sm" required>

        <input type="hidden" name="topik">
        <input type="hidden" name="deskripsi">

        <!-- Tanggal -->
        <label class="fw-semibold small"><i class="bi bi-calendar-event me-1"></i>Tanggal Sesi</label>
        <input type="date" name="tanggal"
               value="{{ $jadwal->tanggal ?? date('Y-m-d') }}"
               class="form-control mb-3 shadow-sm-sm" required>

        <div class="row">
            <div class="col-6">
                <label class="fw-semibold small"><i class="bi bi-clock me-1"></i>Mulai</label>
                <input type="time" name="jam_mulai"
                       class="form-control mb-3 shadow-sm-sm"
                       value="{{ $jadwal->jam_mulai ?? date('H:i') }}" required>
            </div>
            <div class="col-6">
                <label class="fw-semibold small"><i class="bi bi-clock me-1"></i>Selesai</label>
                <input type="time" name="jam_selesai"
                       class="form-control mb-3 shadow-sm-sm"
                       value="{{ $jadwal->jam_selesai ?? date('H:i', strtotime('+1 hour')) }}" required>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-3">
            <button type="button" class="btn btn-light" onclick="closePopupAktivitas()">Batal</button>
            <button class="btn btn-success px-4">Simpan</button>
        </div>
    </form>

</div>



<!-- =====================================================
     LIST SESI
===================================================== -->
@if($sesi->isEmpty())

<div class="text-center text-muted py-5">
    <i class="bi bi-calendar-x display-5"></i>
    <p class="fw-semibold mt-2">Belum ada aktivitas pembelajaran.</p>
</div>

@else

@php $no = 1; @endphp

@foreach($sesi as $item)

<div class="aktivitas-card shadow-sm mb-3">

    <div class="d-flex justify-content-between align-items-start p-3 pb-2">

        <span class="badge sesi-badge rounded-pill">
            Sesi ke {{ $no }}
        </span>

        <div class="dropdown">
            <i class="bi bi-three-dots-vertical fs-5 pointer dropdown-toggle text-secondary"
               data-bs-toggle="dropdown"></i>

            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                <li>
                    <a class="dropdown-item" href="{{ route('guru.kelas.ajaran.sesi.edit', $item->id) }}">
                        <i class="bi bi-pencil me-2"></i>Edit
                    </a>
                </li>

                <li>
                    <a class="dropdown-item text-danger"
                       onclick="return confirm('Yakin hapus sesi ini?')"
                       href="{{ route('guru.kelas.ajaran.sesi.delete', $item->id) }}">
                        <i class="bi bi-trash me-2"></i>Hapus
                    </a>
                </li>
            </ul>
        </div>

    </div>

    <div class="px-3 pb-3">

        <p class="fw-bold mb-1">{{ $item->judul_sesi }}</p>

        @if(!empty($item->topik))
        <div class="text-secondary small mb-1">
            <i class="bi bi-bookmark me-1"></i> {{ $item->topik }}
        </div>
        @endif

        <div class="text-secondary small mb-1">
            <i class="bi bi-calendar-event me-1"></i>
            {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('l, d F Y') }}
        </div>

        <div class="text-secondary small">
            <i class="bi bi-clock me-1"></i>
            {{ substr($item->jam_mulai, 0, 5) }} - {{ substr($item->jam_selesai, 0, 5) }} WIB
        </div>

    </div>

</div>

@php $no++; @endphp

@endforeach

@endif




<!-- =====================================================
     CSS (FULL CLEAN VERSION)
===================================================== -->
<style>

/* Popup */
.popup-overlay {
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.45);
    z-index:998;
}

.popup-box {
    display:none;
    width:460px;
    background:white;
    border-radius:18px;
    padding:22px;
    position:fixed;
    top:50%;
    left:50%;
    transform:translate(-50%, -50%);
    z-index:999;
    box-shadow:0 12px 40px rgba(0,0,0,0.18);
}

.popup-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding-bottom:10px;
    border-bottom:1px solid #e8e8e8;
    margin-bottom:15px;
}

.popup-close { cursor:pointer; color:#666; }

.shadow-sm-sm { box-shadow:0 1px 4px rgba(0,0,0,0.08); }

/* CARD AKTIVITAS — TANPA GARIS HIJAU */
.aktivitas-card {
    border-radius:14px;
    background:white;
    border:1px solid #e6e6e6; /* clean border */
    transition:0.2s;
}

.aktivitas-card:hover {
    transform:translateY(-2px);
    box-shadow:0 4px 18px rgba(0,0,0,0.1);
}

/* Badge sesi */
.sesi-badge {
    background:#eef4ff;
    color:#1b56d9;
    font-weight:600;
    padding:6px 14px;
    font-size:13px;
}

/* Pointer */
.pointer { cursor:pointer; }

</style>



<!-- =====================================================
     JAVASCRIPT
===================================================== -->
<script>
function openPopupAktivitas() {
    document.getElementById("popupOverlay").style.display = "block";
    document.getElementById("popupAktivitas").style.display = "block";
}

function closePopupAktivitas() {
    document.getElementById("popupOverlay").style.display = "none";
    document.getElementById("popupAktivitas").style.display = "none";
}

function syncSesi() {
    if (!confirm("Sinkronisasi akan menyesuaikan sesi dengan jadwal. Lanjutkan?")) return;

    fetch("{{ route('guru.kelas.ajaran.sesi.sync', $kelas_id) }}", {
        method: "POST",
        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" }
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        location.reload();
    });
}
</script>
