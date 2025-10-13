@extends('layouts.admin')

@section('title', 'Kalender Jadwal')

@section('extra-css')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/main.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin-jadwal.css') }}">
@endsection

@section('content')
<div class="container mt-4">
    <h4 class="mb-4 fw-bold text-primary">Kalender Jadwal Pelajaran</h4>
    <div id="calendar"></div>
</div>

<!-- Modal Aksi Tanggal -->
<div class="modal fade" id="aksiTanggalModal" tabindex="-1" aria-labelledby="aksiTanggalModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0 rounded-4">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-semibold" id="aksiTanggalModalLabel">
                    Jadwal Tanggal <span id="tanggalTerpilih"></span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="jadwalContainer">
                    <p class="text-muted">Klik tombol di bawah untuk melihat jadwal hari ini.</p>
                </div>
            </div>
            <div class="modal-footer bg-light">
                <button id="btnTambahMapel" class="btn btn-success">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Mapel Baru
                </button>
                <button id="btnLihatJadwal" class="btn btn-outline-primary">
                    <i class="bi bi-list-ul me-1"></i> Lihat Semua Jadwal Hari Ini
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script src="{{ asset('js/admin-jadwal.js') }}"></script>
@endsection
