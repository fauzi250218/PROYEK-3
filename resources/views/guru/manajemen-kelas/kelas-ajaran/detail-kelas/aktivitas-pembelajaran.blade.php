<!-- ===================================================== -->
<!-- HEADER AKTIVITAS PEMBELAJARAN -->
<!-- ===================================================== -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold">Aktivitas Pembelajaran</h5>

    <div class="d-flex gap-2">
        <!-- Tombol Sinkronisasi -->
        <button class="btn btn-outline-primary px-3" onclick="syncSesi()">
            <i class="bi bi-arrow-repeat me-1"></i> Sinkronisasi
        </button>

        <!-- Tambah sesi manual -->
        <button class="btn btn-success px-4" onclick="openPopupAktivitas()">
            <i class="bi bi-plus-circle me-1"></i> Tambah Sesi
        </button>
    </div>
</div>


<!-- ===================================================== -->
<!-- POPUP TAMBAH SESI (TANPA TUJUAN PEMBELAJARAN) -->
<!-- ===================================================== -->

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
        <input type="text" name="judul_sesi" class="form-control mb-2" required>

        <input type="hidden" name="topik">
        <input type="hidden" name="deskripsi">

        <!-- TANGGAL -->
        <label class="fw-semibold small d-flex gap-2">
            <i class="bi bi-calendar-event"></i> Tanggal Sesi
        </label>
        <input type="date"
               name="tanggal"
               class="form-control mb-2"
               value="{{ $jadwal->tanggal ?? date('Y-m-d') }}"
               required>

        <!-- JAM -->
        <div class="row">
            <div class="col-6">
                <label class="fw-semibold small d-flex gap-2">
                    <i class="bi bi-clock"></i> Mulai
                </label>
                <input type="time" name="jam_mulai"
                       class="form-control mb-2"
                       value="{{ $jadwal->jam_mulai ?? date('H:i') }}"
                       required>
            </div>

            <div class="col-6">
                <label class="fw-semibold small d-flex gap-2">
                    <i class="bi bi-clock"></i> Selesai
                </label>
                <input type="time" name="jam_selesai"
                       class="form-control mb-2"
                       value="{{ $jadwal->jam_selesai ?? date('H:i', strtotime('+1 hour')) }}"
                       required>
            </div>
        </div>

        <!-- BUTTON -->
        <div class="d-flex justify-content-end gap-2 mt-3">
            <button type="button" class="btn btn-light" onclick="closePopupAktivitas()">Batal</button>
            <button class="btn btn-success">Simpan</button>
        </div>
    </form>

</div>


<!-- ===================================================== -->
<!-- LIST SESI -->
<!-- ===================================================== -->
@if($sesi->isEmpty())

<div class="text-center text-muted py-5">
    <i class="bi bi-calendar-x display-6"></i>
    <p class="fw-semibold mt-2">Belum ada aktivitas pembelajaran.</p>
</div>

@else

@php $no = 1; @endphp

@foreach($sesi as $item)

<div class="card aktivitas-card shadow-sm border-0 mb-3">

    <div class="aktivitas-header d-flex justify-content-between align-items-center">
        <span class="badge bg-success p-2 px-3 rounded-pill">Sesi ke {{ $no }}</span>

        <div class="dropdown">
            <i class="bi bi-three-dots-vertical dropdown-toggle" data-bs-toggle="dropdown"></i>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('guru.kelas.ajaran.sesi.edit', $item->id) }}">Edit</a></li>
                <li><a class="dropdown-item text-danger"
                       onclick="return confirm('Yakin hapus sesi ini?')" 
                       href="{{ route('guru.kelas.ajaran.sesi.delete', $item->id) }}">Hapus</a></li>
            </ul>
        </div>
    </div>

    <div class="p-3">
        <p class="fw-semibold mb-1">{{ $item->judul_sesi }}</p>

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
            {{ substr($item->jam_mulai,0,5) }} - {{ substr($item->jam_selesai,0,5) }} WIB
        </div>
    </div>

</div>

@php $no++; @endphp

@endforeach

@endif



<!-- ===================================================== -->
<!-- CSS & JS -->
<!-- ===================================================== -->
<style>
.popup-overlay { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.45); z-index:998; }
.popup-box { display:none; width:450px; background:white; border-radius:18px; padding:20px; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); z-index:999; box-shadow:0 8px 30px rgba(0,0,0,0.1); }
.popup-header { display:flex; justify-content:space-between; align-items:center; padding-bottom:8px; border-bottom:1px solid #e5e5e5; margin-bottom:12px; }
.aktivitas-card { border-left:5px solid #198754; border-radius:14px; }
</style>

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
    if (!confirm("Sinkronisasi akan membuat sesi otomatis mengikuti jadwal. Lanjutkan?")) return;

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
