{{-- =====================================================
RUANG DISKUSI – FULL STABLE VERSION
FIXED 403 FORBIDDEN (NO FEATURE REMOVED)
===================================================== --}}

@php
    $namaGuru = $guru->nama ?? auth()->user()->name ?? 'Guru';
    $inisial  = strtoupper(substr($namaGuru, 0, 1));
    $foto     = $guru->foto_profil ?? null;
@endphp

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Ruang Diskusi</h4>

    <button class="btn btn-primary btn-sm px-4"
            onclick="openBagikanMateriModal()">
        Bagikan Materi
    </button>
</div>

<div class="row g-4">

{{-- =====================================================
KOLOM SESI
===================================================== --}}
<div class="col-md-6">

@forelse($sesi as $item)

@if($item->updated_at->eq($item->created_at))
    @continue
@endif

@php
    $plain  = trim(strip_tags($item->deskripsi));
    $isLong = strlen($plain) > 280;
@endphp

<div class="session-card">

    <div class="session-header">
        <div class="avatar">
            @if($foto)
                <img src="{{ asset('storage/'.$foto) }}">
            @else
                <span>{{ $inisial }}</span>
            @endif
        </div>

        <div class="header-meta">
            <div class="name-line">
                <span class="name">{{ $namaGuru }}</span>
                <span class="activity">memperbarui sesi</span>
                <span class="session-badge">Sesi {{ $loop->iteration }}</span>
            </div>
            <span class="timestamp">{{ $item->updated_at->diffForHumans() }}</span>
        </div>
    </div>

    <div class="session-content">
        @if($item->topik)
            <div class="topic">{{ $item->topik }}</div>
        @endif

        @if($plain)
            <div id="desc-wrapper-{{ $item->id }}"
                 class="objective {{ $isLong ? 'collapsed' : '' }}">
                {!! $item->deskripsi !!}
            </div>

            @if($isLong)
                <span id="toggle-link-{{ $item->id }}"
                      class="read-toggle"
                      onclick="toggleDesc({{ $item->id }})">
                    Baca selengkapnya
                </span>
            @endif
        @endif
    </div>

    <div class="session-footer">
        <a href="{{ route('guru.kelas.ajaran.kehadiran.presensi', $item->id) }}">
            Presensi
        </a>
    </div>

</div>

@empty
<p class="text-muted">Belum ada sesi yang diperbarui.</p>
@endforelse

</div>

{{-- =====================================================
KOLOM MODUL
===================================================== --}}
<div class="col-md-6">

@foreach($sesi as $s)
@php $modSesi = $modul->where('sesi_id', $s->id); @endphp

@if($modSesi->count())

<div class="module-card">

    <div class="module-header">
        <div class="avatar">
            @if($foto)
                <img src="{{ asset('storage/'.$foto) }}">
            @else
                <span>{{ $inisial }}</span>
            @endif
        </div>

        <div class="header-meta">
            <div class="name-line">
                <span class="name">{{ $namaGuru }}</span>
                <span class="activity">mengunggah modul</span>
                <span class="session-badge">Sesi {{ $loop->iteration }}</span>
            </div>
            <span class="timestamp">
                Terakhir diunggah {{ $modSesi->last()->created_at->diffForHumans() }}
            </span>
        </div>
    </div>

    <div class="module-list">
        @foreach($modSesi as $m)
        <div class="module-item modul-card-click"
             data-edit="{{ route('guru.kelas.ajaran.modul.edit', $m->id) }}">

            <div class="file-name" title="{{ basename($m->file) }}">
                {{ basename($m->file) }}
            </div>

            <div class="file-actions">
                <button class="btn-preview preview-btn edit-ignore"
                        data-file="{{ route('guru.kelas.ajaran.modul.preview', $m->id) }}">
                    Pratinjau
                </button>

                <a href="{{ asset('storage/'.$m->file) }}"
                   class="btn-download edit-ignore"
                   download>
                    Unduh
                </a>
            </div>

        </div>
        @endforeach
    </div>

</div>

@endif
@endforeach

</div>
</div>

{{-- =====================================================
MODAL PREVIEW PDF
===================================================== --}}
{{-- =====================================================
MODAL PREVIEW PDF (FULL WIDTH + ACTION)
===================================================== --}}
<div id="previewModulOverlay" class="preview-overlay">
    <div class="preview-modal-full">

        {{-- HEADER --}}
        <div class="preview-header">
            <span class="preview-title">Pratinjau Dokumen PDF</span>

            <div class="preview-actions">
                <a id="downloadPdfBtn"
                   href="#"
                   target="_blank"
                   download
                   class="btn btn-success btn-sm">
                    Unduh
                </a>

                <button class="btn btn-secondary btn-sm"
                        onclick="closePreviewModal()">
                    Tutup
                </button>
            </div>
        </div>

        {{-- PDF --}}
        <iframe id="pdfView"></iframe>

    </div>
</div>


{{-- =====================================================
MODAL UPLOAD MODUL (FIXED)
===================================================== --}}
<div id="bagikanMateriModal" class="upload-overlay">
    <div class="upload-modal modern">

        <div class="upload-header">
            <div>
                <h5>Bagikan Materi Pembelajaran</h5>
                <small>Upload materi untuk siswa</small>
            </div>
            <button class="btn-close" onclick="closeBagikanMateriModal()"></button>
        </div>

        <div class="stepper">
            <div class="step active" id="step-indicator-1">
                <span>1</span><label>Sesi & Topik</label>
            </div>
            <div class="line"></div>
            <div class="step" id="step-indicator-2">
                <span>2</span><label>Materi</label>
            </div>
        </div>

        <form method="POST"
              action="{{ route('guru.kelas.ajaran.upload-modul') }}"
              enctype="multipart/form-data">
            @csrf

            {{-- FIX WAJIB (ANTI 403) --}}
            <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">

            {{-- STEP 1 --}}
            <div class="materi-step active" id="materi-step-1">
                <label>Pilih Sesi</label>
                <select name="sesi_id" class="form-select mb-3" required>
                    @foreach($sesi as $i => $ss)
                        <option value="{{ $ss->id }}">
                            Sesi {{ $i + 1 }}
                        </option>
                    @endforeach
                </select>

                <label>Topik Pembelajaran</label>
                <input type="text"
                       name="topik"
                       class="form-control mb-4"
                       required>

                <div class="text-end">
                    <button type="button"
                            class="btn btn-primary btn-sm px-4"
                            onclick="nextMateriStepPro()">Lanjut</button>
                </div>
            </div>

            {{-- STEP 2 --}}
            <div class="materi-step" id="materi-step-2">
                <label>Judul Materi</label>
                <input type="text"
                       name="judul_materi"
                       class="form-control mb-3"
                       required>

                <label>File PDF</label>
                <div class="dropzone mb-3">
                    <input type="file"
                           name="file_materi"
                           accept="application/pdf"
                           required
                           onchange="handleFileSelect(this)">

                    <div class="drop-content">
                        <strong id="drop-title">Tarik & lepas PDF</strong>
                        <span id="drop-subtitle">atau klik untuk memilih</span>
                    </div>
                </div>

                <label>Catatan (Opsional)</label>
                <textarea name="catatan"
                          class="form-control mb-4"
                          rows="3"></textarea>

                <div class="d-flex justify-content-between">
                    <button type="button"
                            class="btn btn-light btn-sm"
                            onclick="prevMateriStepPro()">Kembali</button>
                    <button type="submit"
                            class="btn btn-success btn-sm px-4">
                        Upload Materi
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- =====================================================
CSS
===================================================== --}}
<style>
body{background:#f8fafc}
.avatar{width:44px;height:44px;border-radius:50%;background:#e5e7eb;
display:flex;align-items:center;justify-content:center;font-weight:600}
.avatar img{width:100%;height:100%;object-fit:cover}

.session-card,.module-card{
background:#fff;border:1px solid #e5e7eb;border-radius:16px;
box-shadow:0 8px 20px rgba(15,23,42,.05);margin-bottom:22px}

.session-header,.module-header{
display:flex;gap:14px;padding:18px;border-bottom:1px solid #f1f5f9}

.name{font-weight:600;font-size:14px}
.activity{font-size:13px;color:#64748b}
.timestamp{font-size:12px;color:#94a3b8}
.session-badge{
font-size:11px;background:#16a34a;color:#fff;
padding:2px 10px;border-radius:999px}

.session-content{padding:18px}
.topic{font-size:15px;font-weight:600}
.objective{font-size:14px;line-height:1.7}
.objective.collapsed{max-height:140px;overflow:hidden}
.read-toggle{font-size:13px;color:#2563eb;cursor:pointer}

.session-footer{
padding:14px 18px;border-top:1px solid #f1f5f9;
display:flex;justify-content:flex-end}

.module-list{padding:16px}
.module-item{
display:flex;justify-content:space-between;
align-items:center;padding:14px;border-radius:12px;
background:#f8fafc;border:1px solid #e5e7eb;
margin-bottom:10px;cursor:pointer}

.file-name{
font-size:13px;
max-width:70%;
white-space:nowrap;
overflow:hidden;
text-overflow:ellipsis
}

.file-actions{display:flex;gap:8px}
.btn-preview,.btn-download{
font-size:11px;padding:6px 12px;border-radius:8px;border:none;color:#fff}
.btn-preview{background:#2563eb}
.btn-download{background:#16a34a}

.preview-overlay,.upload-overlay{
display:none;position:fixed;inset:0;
background:rgba(0,0,0,.5);z-index:9999;
align-items:center;justify-content:center}

.upload-overlay.active{display:flex}

.preview-modal,.upload-modal{
background:#fff;padding:22px;border-radius:14px;
max-width:95%}

.upload-modal.modern{width:560px}

.stepper{display:flex;align-items:center;margin:18px 0}
.step{display:flex;flex-direction:column;align-items:center;font-size:12px;color:#94a3b8}
.step span{
width:30px;height:30px;border-radius:50%;
border:2px solid #cbd5f5;
display:flex;align-items:center;justify-content:center}
.step.active span{background:#2563eb;color:#fff;border-color:#2563eb}
.line{flex:1;height:2px;background:#e5e7eb}

.materi-step{display:none}
.materi-step.active{display:block}

.dropzone{
border:2px dashed #c7d2fe;border-radius:14px;
padding:30px;position:relative;text-align:center;background:#f8fafc}
.dropzone input{position:absolute;inset:0;opacity:0;cursor:pointer}

.preview-modal-full{
    width:75vw;
    height:83vh;
     max-width:1200px;
    background:#fff;
    border-radius:16px;
    display:flex;
    flex-direction:column;
    box-shadow:0 25px 70px rgba(0,0,0,.35);
}

.preview-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:14px 20px;
    border-bottom:1px solid #e5e7eb;
    background:#f8fafc;
}

.preview-title{
    font-weight:600;
    font-size:14px;
}

.preview-actions{
    display:flex;
    gap:8px;
}

.preview-modal-full iframe{
    flex:1;
    width:100%;
    height:100%;
    border:none;
}   
</style>

{{-- =====================================================
JS
===================================================== --}}
<script>
function toggleDesc(id){
    const w=document.getElementById("desc-wrapper-"+id);
    const l=document.getElementById("toggle-link-"+id);
    w.classList.toggle("collapsed");
    l.textContent=w.classList.contains("collapsed")
        ?"Baca selengkapnya":"Sembunyikan";
}

// ===============================
// PREVIEW PDF (FIXED + DOWNLOAD)
// ===============================
document.querySelectorAll('.preview-btn').forEach(btn=>{
    btn.addEventListener('click', e => {
        e.stopPropagation();

        const fileUrl = btn.dataset.file;

        // tampilkan PDF
        document.getElementById('pdfView').src =
            fileUrl + '#toolbar=1&navpanes=0&scrollbar=1';

        // SET LINK DOWNLOAD (INI YANG KURANG)
        document.getElementById('downloadPdfBtn').href = fileUrl;

        // buka modal
        document.getElementById('previewModulOverlay').style.display = 'flex';
    });
});
function closePreviewModal(){
    document.getElementById('previewModulOverlay').style.display='none';
    document.getElementById('pdfView').src='';
}

document.querySelectorAll('.modul-card-click').forEach(card=>{
    card.addEventListener('click',function(e){
        if(e.target.closest('.edit-ignore')) return;
        window.location.href=this.dataset.edit;
    });
});

function openBagikanMateriModal(){
    materiStepSwitch(1);
    document.getElementById('bagikanMateriModal').classList.add('active');
}
function closeBagikanMateriModal(){
    document.getElementById('bagikanMateriModal').classList.remove('active');
}

function nextMateriStepPro(){ materiStepSwitch(2); }
function prevMateriStepPro(){ materiStepSwitch(1); }

function materiStepSwitch(step){
    document.querySelectorAll('.materi-step')
        .forEach(s=>s.classList.remove('active'));
    document.getElementById('materi-step-'+step)
        .classList.add('active');

    document.querySelectorAll('.step')
        .forEach(s=>s.classList.remove('active'));
    document.getElementById('step-indicator-'+step)
        .classList.add('active');
}

function handleFileSelect(input){
    if(!input.files || !input.files.length) return;

    const fullName  = input.files[0].name;
    const maxLength = 32; // aman, tidak jebol form

    let displayName = fullName;

    if(fullName.length > maxLength){
        const dot  = fullName.lastIndexOf('.');
        const ext  = dot !== -1 ? fullName.substring(dot) : '';
        const base = fullName.substring(0, maxLength - ext.length - 3);
        displayName = base + '...' + ext;
    }

    const title = document.getElementById('drop-title');
    const sub   = document.getElementById('drop-subtitle');

    title.textContent = displayName;
    title.title       = fullName; // hover = nama asli
    title.style.color = '#16a34a';
    sub.textContent   = 'File siap diunggah';
}

</script>
