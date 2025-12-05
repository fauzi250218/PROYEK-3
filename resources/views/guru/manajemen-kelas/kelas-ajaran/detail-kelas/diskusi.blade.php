<!-- ===================================================== -->
<!-- HEADER RUANG DISKUSI + BUTTON UPLOAD MODUL -->
<!-- ===================================================== -->

<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0">Ruang Diskusi</h5>

    <button class="btn btn-primary px-4" onclick="openPopupModul()">
        <i class="bi bi-upload me-1"></i> Upload Modul
    </button>
</div>

<div class="row">

    <!-- ===================================================== -->
    <!-- KOLOM KIRI (SESI) -->
    <!-- ===================================================== -->
    <div class="col-md-6">

        @php
            $namaGuru = $guru->nama ?? auth()->user()->name ?? 'Guru Pengampu';
            $no = 1;
        @endphp

        @foreach($sesi as $item)

            <div class="diskusi-card mb-3 shadow-sm p-3">

                <div class="d-flex align-items-center mb-2">
                    <div class="edlink-avatar"></div>

                    <div class="ms-2">
                        <span class="fw-semibold">{{ $namaGuru }}</span>
                        <span class="text-muted"> menambahkan sesi </span>
                        <span class="text-success fw-semibold">Sesi {{ $no }}</span>
                        <div class="text-muted small">{{ $item->created_at->diffForHumans() }}</div>
                    </div>
                </div>

                <div class="mt-2">

                    @if($item->topik)
                        <p class="mb-2 topik-text">{{ $item->topik }}</p>
                    @endif

                    @php
                        $plain  = trim(strip_tags($item->deskripsi));
                        $isLong = strlen($plain) > 250;
                    @endphp

                    <div id="desc-wrapper-{{ $item->id }}" class="session-desc-wrapper {{ $isLong ? 'collapsed' : '' }}">
                        {!! $item->deskripsi !!}
                    </div>

                    @if($isLong)
                        <p id="toggle-link-{{ $item->id }}"
                           class="text-primary small fw-bold pointer mb-0"
                           onclick="toggleDesc({{ $item->id }})">
                           Baca selengkapnya...
                        </p>
                    @endif

                    <div class="text-end mt-2">
                        {{-- PRESENSI DIJADIKAN LINK --}}
                        <a href="{{ route('guru.kelas.ajaran.kehadiran.presensi', $item->id) }}"
                           class="presensi-text text-primary text-decoration-none fw-semibold">
                            Presensi
                        </a>
                    </div>
                </div>

            </div>

            @php $no++; @endphp

        @endforeach

    </div>




    <!-- ===================================================== -->
    <!-- KOLOM KANAN (MODUL GROUPED PER SESI) -->
    <!-- ===================================================== -->
    <div class="col-md-6">

        <div class="modul-wrapper p-3 rounded shadow-sm bg-white">

            @if($modul->isEmpty())
                <p class="text-muted">Belum ada modul yang diunggah.</p>
            @endif

            <div class="modul-scroll">

                @foreach($sesi as $s)

                    @php
                        $modSesi = $modul->where('sesi_id', $s->id);
                    @endphp

                    @if($modSesi->count() > 0)

                        <div class="d-flex align-items-center mt-3 mb-2">
                            <div class="edlink-avatar"></div>
                            <div class="ms-2">
                                <span class="fw-semibold">{{ $namaGuru }}</span>
                                <span class="text-muted"> menambahkan materi pada </span>
                                <span class="text-success fw-semibold">Sesi {{ $loop->iteration }}</span>
                                <div class="text-muted small">
                                    {{ $modSesi->first()->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>

                        @foreach($modSesi as $m)

                            <div class="edlink-file-card p-2 mb-3 pointer modul-card"
                                 data-edit="{{ route('guru.kelas.ajaran.modul.edit', $m->id) }}">

                                <div class="d-flex justify-content-between align-items-center">

                                    <div class="d-flex align-items-center gap-2">
                                        <div class="file-icon-pdf">PDF</div>
                                        <span class="fw-semibold edlink-file-name">
                                            {{ basename($m->file) }}
                                        </span>
                                    </div>

                                    <div class="d-flex gap-2">

                                        <!-- TOMBOL PRATINJAU -->
                                        <button type="button"
                                                class="btn btn-sm btn-primary preview-btn"
                                                data-judul="{{ $m->judul }}"
                                                data-topik="{{ $m->topik }}"
                                                data-catatan="{{ $m->catatan }}"
                                                data-file="{{ asset('storage/'.$m->file) }}">
                                            Pratinjau
                                        </button>

                                        <!-- TOMBOL UNDUH -->
                                        <a href="{{ asset('storage/'.$m->file) }}"
                                           class="btn btn-sm btn-outline-success"
                                           target="_blank">
                                           Unduh
                                        </a>

                                    </div>

                                </div>
                            </div>

                        @endforeach

                    @endif

                @endforeach

            </div>
        </div>

    </div>

</div>





<!-- ===================================================== -->
<!-- MODAL PREVIEW PDF (LAMA, TETAP DIPAKAI) -->
<!-- ===================================================== -->

<div id="previewModulOverlay" class="preview-overlay">
    <div class="preview-modal">

        <h4 class="fw-bold mb-3">Pratinjau Materi</h4>

        <h5 id="prevJudul" class="fw-semibold mb-1"></h5>
        <p id="prevTopik" class="text-muted mb-1"></p>
        <p id="prevCatatan" class="text-muted"></p>

        <div class="viewer-box">
            <iframe id="pdfView" src="" width="100%" height="100%" style="border:none;"></iframe>
        </div>

        <div class="d-flex justify-content-end mt-3">
            <a id="btnDownloadFile" class="btn btn-success btn-sm me-2" download>Unduh</a>
            <button class="btn btn-secondary btn-sm" onclick="closePreviewModal()">Tutup</button>
        </div>

    </div>
</div>





<!-- ===================================================== -->
<!-- MODAL UPLOAD MODUL 2 STEP ALA EDLINK (BARU)          -->
<!-- ===================================================== -->

<div id="uploadModulOverlay" class="upload-overlay">
    <div class="upload-modal">

        <!-- HEADER MODAL (tanpa ikon back) -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            <div class="d-flex align-items-center">
                <span class="fw-semibold small text-muted">
                    Bagikan sesuatu di kelas Anda:
                </span>
            </div>

            <button type="button" class="btn-close" aria-label="Close" onclick="closePopupModul()"></button>
        </div>

        <!-- NAV STEP (sekarang hanya 2 step) -->
        <div class="upload-step-nav mb-3">
            <button type="button" class="step-tab active" id="tab-step-1" onclick="goToUploadStep(1)">
                Pilih Sesi
            </button>
            <button type="button" class="step-tab" id="tab-step-2" onclick="goToUploadStep(2)">
                Atur Materi
            </button>
        </div>

        <!-- FORM UPLOAD -->
        <form id="uploadModulForm"
              method="POST"
              action="{{ route('guru.kelas.ajaran.upload-modul') }}"
              enctype="multipart/form-data">

            @csrf
            <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">

            <!-- ================= STEP 1 : PILIH SESI ================= -->
            <div class="upload-step" id="upload-step-1">

                <div class="mb-3">
                    <label class="form-label fw-semibold small">
                        Di sesi berapa materi akan dibagikan?
                    </label>

                    <select name="sesi_id" id="upload_sesi_id" class="form-select">
                        @foreach($sesi as $index => $item)
                            <option value="{{ $item->id }}">
                                Sesi {{ $index + 1 }} - {{ $item->judul_sesi ?? 'Tanpa judul' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Topik Sesi</label>
                    <input type="text"
                           class="form-control"
                           name="topik_sesi"
                           id="upload_topik_sesi"
                           placeholder="contoh: Pengantar Materi Bangun Datar">
                </div>

                <div class="d-flex justify-content-end mt-3">
                    <button type="button"
                            class="btn btn-success btn-sm"
                            onclick="nextUploadStep(1)">
                        Langkah Berikutnya
                    </button>
                </div>
            </div>

            <!-- ================= STEP 2 : ATUR MATERI ================= -->
            <div class="upload-step d-none" id="upload-step-2">

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Judul Materi</label>
                    <input type="text"
                           class="form-control"
                           name="judul_materi"
                           id="upload_judul_materi"
                           placeholder="contoh: Modul Pengantar Bangun Datar">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">File Materi (PDF)</label>
                    <input type="file"
                           class="form-control"
                           name="file_materi"
                           id="upload_file_materi"
                           accept="application/pdf">
                    <small class="text-muted">
                        Format: PDF, maks 20MB.
                    </small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold small">Catatan untuk Siswa (Opsional)</label>
                    <textarea class="form-control"
                              name="catatan_materi"
                              id="upload_catatan_materi"
                              rows="3"
                              placeholder="contoh: Silakan baca sampai halaman 10 sebelum pertemuan berikutnya."></textarea>
                </div>

                <div class="d-flex justify-content-between mt-3">
                    <button type="button"
                            class="btn btn-outline-secondary btn-sm"
                            onclick="prevUploadStep(2)">
                        Kembali
                    </button>

                    <button type="submit"
                            class="btn btn-success btn-sm">
                        Bagikan Materi
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>





<!-- ===================================================== -->
<!-- CSS -->
<!-- ===================================================== -->

<style>
.diskusi-card {
    border-radius: 14px;
    background: white;
    border: 1px solid #e8e8e9;
}

.edlink-avatar {
    width: 42px;
    height: 42px;
    background: #eee;
    border-radius: 50%;
}

.topik-text { font-weight: 700; }

.session-desc-wrapper.collapsed {
    max-height: 120px;
    overflow: hidden;
}

.pointer { cursor:pointer; }

.modul-wrapper { border:1px solid #e8e8e9; }

.modul-scroll {
    max-height:75vh;
    overflow-y:auto;
    padding-right:6px;
}

.edlink-file-card {
    background:#e8edff;
    border-radius:8px;
    border:1px solid #d7dcff;
}

.file-icon-pdf {
    width:28px;
    height:28px;
    background:#ff6b6b;
    color:white;
    border-radius:6px;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:11px;
    font-weight:bold;
}

/* ===================================================== */
/* MODAL PREVIEW (LAMA)                                  */
/* ===================================================== */
.preview-overlay {
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.45);
    z-index:9999;
    align-items:center;
    justify-content:center;
    padding:20px;
}

.preview-modal {
    background:white;
    border-radius:14px;
    padding:25px;
    width:850px;
    max-width:95%;
    max-height:90vh;
    overflow-y:auto;
}

.viewer-box {
    border:1px solid #ddd;
    border-radius:12px;
    height:65vh;
    overflow:hidden;
    background:white;
    margin-top:15px;
}

/* ===================================================== */
/* MODAL UPLOAD MODUL 2 STEP (BARU)                      */
/* ===================================================== */
.upload-overlay {
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,0.45);
    z-index:10000;
    align-items:center;
    justify-content:center;
    padding:20px;
}

.upload-modal {
    background:white;
    border-radius:10px;
    padding:18px 22px;
    width:520px;
    max-width:95%;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
    font-size:14px;
}

.upload-step-nav {
    display:flex;
    border-bottom:1px solid #e5e5e5;
    margin-bottom:12px;
}

.step-tab {
    flex:1;
    border:none;
    background:transparent;
    padding:8px 0;
    font-size:13px;
    font-weight:600;
    color:#777;
    border-bottom:2px solid transparent;
}

.step-tab.active {
    color:#198754;
    border-color:#198754;
}

.upload-step {
    font-size:14px;
}

.upload-step .form-label {
    margin-bottom:4px;
}

/* helper */
.d-none { display:none !important; }
</style>





<!-- ===================================================== -->
<!-- JAVASCRIPT -->
<!-- ===================================================== -->

<script>
/* ==========================
 * BACA SELENGKAPNYA
 * ========================== */
function toggleDesc(id){
    let wrapper = document.getElementById("desc-wrapper-"+id);
    let link = document.getElementById("toggle-link-"+id);

    wrapper.classList.toggle("collapsed");
    link.textContent = wrapper.classList.contains("collapsed")
                       ? "Baca selengkapnya..."
                       : "Sembunyikan";
}

/* ==========================
 * PREVIEW MODAL (LAMA)
 * ========================== */
document.querySelectorAll('.preview-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.stopPropagation();

        openPreviewModal(
            this.dataset.judul,
            this.dataset.topik,
            this.dataset.catatan,
            this.dataset.file
        );
    });
});

function openPreviewModal(judul, topik, catatan, fileUrl)
{
    document.getElementById('prevJudul').textContent   = judul || '-';
    document.getElementById('prevTopik').textContent   = topik || '-';
    document.getElementById('prevCatatan').textContent = catatan || '-';

    const viewerUrl = fileUrl + "#toolbar=1&navpanes=0&scrollbar=1&zoom=page-width";

    document.getElementById('pdfView').src = viewerUrl;
    document.getElementById('btnDownloadFile').href = fileUrl;

    document.getElementById('previewModulOverlay').style.display = "flex";
}

function closePreviewModal(){
    document.getElementById('previewModulOverlay').style.display = "none";
    document.getElementById('pdfView').src = "";
}

/* ==========================
 * KLIK CARD → EDIT
 * ========================== */
document.querySelectorAll('.modul-card').forEach(card => {
    card.addEventListener('click', function(e) {

        if (e.target.closest('button') || e.target.closest('a')) {
            return;
        }

        window.location.href = this.dataset.edit;
    });
});

/* ===================================================== */
/* MODAL UPLOAD MODUL 2 STEP                             */
/* ===================================================== */

let currentUploadStep = 1;

function openPopupModul()
{
    // reset form
    const form = document.getElementById('uploadModulForm');
    if (form) {
        form.reset();
    }

    // step awal
    currentUploadStep = 1;
    refreshUploadStepUI();

    document.getElementById('uploadModulOverlay').style.display = 'flex';
}

function closePopupModul()
{
    document.getElementById('uploadModulOverlay').style.display = 'none';
}

/* ganti tab manual (kalau user klik header tab) */
function goToUploadStep(step) {
    currentUploadStep = step;
    refreshUploadStepUI();
}

/* next / prev dengan tombol */
function nextUploadStep(fromStep)
{
    if (fromStep === 1) {
        const sesi  = document.getElementById('upload_sesi_id').value;
        const topik = document.getElementById('upload_topik_sesi').value.trim();

        if (!sesi) {
            alert('Silakan pilih sesi terlebih dahulu.');
            return;
        }

        // topik boleh kosong, tapi kasih konfirmasi
        if (!topik) {
            if (!confirm('Topik belum diisi. Lanjutkan tanpa topik?')) {
                return;
            }
        }

        currentUploadStep = 2;
    }

    refreshUploadStepUI();
}

function prevUploadStep(fromStep)
{
    if (fromStep === 2) {
        currentUploadStep = 1;
    }
    refreshUploadStepUI();
}

/* update tampilan step & tab */
function refreshUploadStepUI()
{
    // step block
    [1,2].forEach(function(step){
        const block = document.getElementById('upload-step-'+step);
        if (!block) return;
        if (step === currentUploadStep) {
            block.classList.remove('d-none');
        } else {
            block.classList.add('d-none');
        }
    });

    // tab header
    [1,2].forEach(function(step){
        const tab = document.getElementById('tab-step-'+step);
        if (!tab) return;
        if (step === currentUploadStep) {
            tab.classList.add('active');
        } else {
            tab.classList.remove('active');
        }
    });
}
</script>
