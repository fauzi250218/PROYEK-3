@extends('layouts.guru')
@section('title', 'Detail Kelas Ajaran')

@section('content')

<div class="container py-3">

    <!-- HEADER DETAIL KELAS -->
    <div class="detail-header shadow-sm p-4 mb-4 rounded-4 bg-white">
        <h4 class="fw-bold mb-1">{{ $detail['nama_kelas'] }}</h4>
        <p class="text-muted mb-2">{{ $detail['mapel'] }}</p>

        <div class="small mb-1 text-secondary">
            <i class="bi bi-shield-check me-2"></i> Wali Kelas: {{ $detail['wali_kelas'] }}
        </div>
        <div class="small text-secondary">
            <i class="bi bi-people-fill me-2"></i> Jumlah Siswa: {{ $detail['jumlah_siswa'] }}
        </div>
    </div>

    <!-- TAB SWITCH -->
    <div class="tab-switch mb-4">
        <button id="tabDiskusi" class="tab-btn active" onclick="switchTab('diskusi')">Ruang Diskusi</button>
        <button id="tabAktifitas" class="tab-btn" onclick="switchTab('aktifitas')">Aktivitas Pembelajaran</button>
    </div>

    <!-- CONTENT WRAPPERS -->
    <div id="contentDiskusi">
        @include('guru.manajemen-kelas.kelas-ajaran.detail-kelas.diskusi')
    </div>

    <div id="contentAktifitas" style="display:none;">
        @include('guru.manajemen-kelas.kelas-ajaran.detail-kelas.aktivitas-pembelajaran')
    </div>

</div>

@endsection

{{-- ===================== CSS ===================== --}}
@section('extra-css')
<style>
.tab-switch {
    background: #e9ecef;
    padding: 6px;
    border-radius: 50px;
    display: flex;
    width: max-content;
}

.tab-btn {
    border: none;
    background: transparent;
    padding: 8px 20px;
    border-radius: 50px;
    font-weight: 600;
    color: #5c636a;
    cursor: pointer;
}
.tab-btn.active {
    background: #0d6efd;
    color: white;
}

.card-diskusi {
    background: white;
    border-radius: 16px;
    padding: 16px;
}
</style>
@endsection

{{-- ===================== JAVASCRIPT ===================== --}}
@section('extra-js')
<script>
function switchTab(tab) {
    document.getElementById('contentDiskusi').style.display = tab === 'diskusi' ? 'block' : 'none';
    document.getElementById('contentAktifitas').style.display = tab === 'aktifitas' ? 'block' : 'none';

    document.getElementById('tabDiskusi').classList.toggle('active', tab === 'diskusi');
    document.getElementById('tabAktifitas').classList.toggle('active', tab === 'aktifitas');
}
</script>
@endsection
