@extends('layouts.guru')

@section('title', 'Presensi')

@section('content')

@php
    \Carbon\Carbon::setLocale('id');
    setlocale(LC_TIME, 'id_ID.UTF-8');
@endphp


<style>
    .info-card {
        background: #fff;
        padding: 22px;
        border-radius: 12px;
        border: 1px solid #e6e6e6;
        margin-bottom: 18px;
    }

    .presensi-card {
        background: #fff;
        padding: 10px 14px; /* DIPERKECIL */
        border-radius: 12px;
        border: 1px solid #e6e6e6;
        margin-bottom: 8px; /* DIPERKECIL */
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .status-btn {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        border: 2px solid #cfd3ff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #6b6b6b;
        cursor: pointer;
        transition: 0.2s;
    }

    .status-btn.active {
        background: #4e73df;
        color: white;
        border-color: #4e73df;
    }

    .status-group {
        display: flex;
        gap: 10px; /* sedikit diperkecil */
    }

    .absen-wrapper {
        background: white;
        padding: 20px;
        border-radius: 12px;
        border: 1px solid #e6e6e6;
    }
</style>


{{-- ========================== CARD INFO SESI ========================== --}}
<div class="info-card shadow-sm">

    <h5 class="fw-bold mb-3">{{ $kelas->nama ?? 'Detail Sesi' }}</h5>

    <div class="row mb-2">
        <div class="col-md-6">
            <small class="text-muted">Sesi ke</small><br>
            <strong>{{ $nomorSesi }}</strong>
        </div>

        <div class="col-md-6">
            <small class="text-muted">Topik Pembelajaran</small><br>
            <strong>{{ $sesi->topik ?? '-' }}</strong>
        </div>
    </div>

    <div class="row mb-2">

        <div class="col-md-6">
            <small class="text-muted">Hari & Tanggal</small><br>
            <strong>
                {{ $jadwal ? \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l, d F Y') : '-' }}
            </strong>
        </div>

        <div class="col-md-6">
            <small class="text-muted">Waktu</small><br>
            <strong>
                {{ $jadwal ? \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') : '-' }}
                -
                {{ $jadwal ? \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') : '-' }}
            </strong>
        </div>

    </div>

    <div class="mt-2">
        <small class="text-muted">Jumlah Siswa</small><br>
        <strong>{{ count($murids) }} Siswa</strong>
    </div>

</div>


{{-- ========================== CARD PRESENSI ========================== --}}
<div class="absen-wrapper shadow-sm">

    <div class="d-flex justify-content-between align-items-center mb-3 px-1">
        <h6 class="fw-bold mb-0">Daftar Kehadiran</h6>

        <div>
            <input type="checkbox" id="checkAllHadir" onclick="tandaiHadirSemua()">
            <label for="checkAllHadir" class="ms-1">Tandai Hadir Semua</label>
        </div>
    </div>

    <form action="{{ route('guru.kelas.ajaran.kehadiran.simpan', $sesi->id) }}" method="POST">
        @csrf

        @foreach($murids as $murid)

            @php
                $status = $kehadiran[$murid->id]->status ?? null;
            @endphp

            <div class="presensi-card">

                <div class="fw-bold">{{ $murid->nama }}</div>

                <div class="status-group">
                    @foreach(['H','I','A','S'] as $st)
                        <label class="status-btn {{ $status == $st ? 'active' : '' }}">
                            {{ $st }}
                            <input type="radio"
                                   name="status[{{ $murid->id }}]"
                                   value="{{ $st }}"
                                   class="d-none status-radio"
                                   @if($status == $st) checked @endif>
                        </label>
                    @endforeach
                </div>

            </div>

        @endforeach

        <div class="text-end mt-3">
            <button class="btn btn-success px-4 py-2">Simpan Presensi</button>
        </div>

    </form>
</div>


<script>
    document.querySelectorAll(".status-btn").forEach(btn => {
        btn.addEventListener("click", function() {
            let group = this.closest(".status-group");

            group.querySelectorAll(".status-btn").forEach(b => b.classList.remove("active"));

            this.classList.add("active");
            this.querySelector("input").checked = true;
        });
    });

    function tandaiHadirSemua() {
        if (!document.getElementById("checkAllHadir").checked) return;

        document.querySelectorAll(".presensi-card").forEach(card => {

            let btnH = card.querySelector(".status-btn:nth-child(1)");

            card.querySelectorAll(".status-btn").forEach(b => b.classList.remove("active"));

            btnH.classList.add("active");
            btnH.querySelector("input").checked = true;
        });
    }
</script>

@endsection
