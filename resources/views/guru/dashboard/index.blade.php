@extends('layouts.guru')

@section('title', 'Dashboard Guru')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/guru/dashboard/dashboard-guru.css') }}">

<style>

</style>
@endsection


@section('content')

<div class="container-fluid dashboard-page py-3">

    <h4 class="mb-4 fw-bold">Dashboard Guru</h4>

    <div class="row g-4">

        <div class="col-lg-6">

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <div class="stat-card">
                        <div class="stat-icon primary">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="stat-info">
                            <h2>{{ $jumlahMuridKelas }}</h2>
                            <p>Jumlah Siswa</p>
                        </div>
                    </div>
                </div>

                <div class="col-6">
                    <div class="stat-card">
                        <div class="stat-icon success">
                            <i class="bi bi-journal-check"></i>
                        </div>
                        <div class="stat-info">
                            <h2>{{ $jumlahNilai }}</h2>
                            <p>Nilai Diinput</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grafik-card">
                <h5 class="fw-bold mb-3">Nilai Diinput per Bulan</h5>
                <canvas id="nilaiChart"></canvas>
            </div>

        </div>


        <div class="col-lg-6">

            {{-- KALENDER --}}
            <div class="calendar-card mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <button class="btn btn-calendar btn-sm" id="prevBtn"><i class="bi bi-chevron-left"></i></button>
                    <h5 id="calMonth" class="fw-bold m-0"></h5>
                    <button class="btn btn-calendar btn-sm" id="nextBtn"><i class="bi bi-chevron-right"></i></button>
                </div>

                <div id="calendarGrid"></div>
            </div>

            {{-- JADWAL --}}
            <div class="jadwal-card">
                <h6 class="fw-bold mb-3">
                    Jadwal Tanggal:
                    <span id="tanggalLabel">{{ date('d M Y') }}</span>
                </h6>

                <div id="jadwalList">
                    @forelse ($jadwalHariIni as $j)
                        <div class="jadwal-item">
                            <strong>{{ $j['mata_pelajaran'] }}</strong><br>
                            <small>Guru: {{ $j['guru'] }}</small><br>
                            <small>Kelas: {{ $j['kelas']['nama_kelas'] }}</small><br>
                            <small>{{ substr($j['jam_mulai'], 0, 5) }} – {{ substr($j['jam_selesai'], 0, 5) }}</small>
                        </div>
                    @empty
                        <p class="text-muted">Tidak ada jadwal.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

</div>

@endsection


@section('extra-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// ============================================================
//   GRAFIK
// ============================================================
document.addEventListener("DOMContentLoaded", () => {
    const ctx = document.getElementById("nilaiChart").getContext("2d");

    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, "rgba(255,179,0,0.38)");
    gradient.addColorStop(1, "rgba(255,179,0,0.05)");

    new Chart(ctx, {
        type: "line",
        data: {
            labels: @json($labelsBulan),
            datasets: [{
                label: "Nilai",
                data: @json($dataNilai),
                borderColor: "#ffb300",
                backgroundColor: gradient,
                borderWidth: 3,
                tension: 0.35,
                fill: true,
                pointBackgroundColor: "#ffb300",
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    renderCalendar();
});


// ============================================================
//     KALENDER FIX FULL (SENIN – MINGGU)
// ============================================================

let current = new Date();

function renderCalendar() {
    const grid = document.getElementById("calendarGrid");
    const header = document.getElementById("calMonth");

    const monthNames = [
        "Januari","Februari","Maret","April","Mei","Juni",
        "Juli","Agustus","September","Oktober","November","Desember"
    ];

    let year = current.getFullYear();
    let month = current.getMonth();

    header.textContent = `${monthNames[month]} ${year}`;
    grid.innerHTML = "";

    const days = ["Sen","Sel","Rab","Kam","Jum","Sab","Min"];
    days.forEach(d => grid.innerHTML += `<div class='day-name'>${d}</div>`);

    let jsDay = new Date(year, month, 1).getDay();
    let startDay = (jsDay === 0) ? 6 : jsDay - 1;

    for (let i = 0; i < startDay; i++) {
        grid.innerHTML += "<div></div>";
    }

    const total = new Date(year, month + 1, 0).getDate();
    const today = new Date();

    for (let d = 1; d <= total; d++) {
        const isToday =
            d === today.getDate() &&
            month === today.getMonth() &&
            year === today.getFullYear()
            ? "today" : "";

        grid.innerHTML += `
            <div class="day ${isToday}" onclick="selectDay(event, ${d})">
                ${d}
            </div>
        `;
    }
}

function selectDay(e, d) {
    document.querySelectorAll(".day").forEach(el => el.classList.remove("selected"));
    e.target.classList.add("selected");

    let day = d;
    let month = current.getMonth() + 1;
    let year = current.getFullYear();

    let tanggal = `${year}-${String(month).padStart(2,'0')}-${String(day).padStart(2,'0')}`;

    document.getElementById("tanggalLabel").innerText =
        `${day} ${document.getElementById("calMonth").innerText}`;

    fetch(`/guru/jadwal/${tanggal}`)
        .then(res => res.json())
        .then(data => {
            let html = "";

            if (data.length === 0) {
                html = `<p class="text-muted">Tidak ada jadwal.</p>`;
            } else {
                data.forEach(j => {
                    html += `
                        <div class="jadwal-item">
                            <strong>${j.mata_pelajaran}</strong><br>
                            <small>Guru: ${j.guru}</small><br>
                            <small>Kelas: ${j.kelas.nama_kelas}</small><br>
                            <small>${j.jam_mulai.substring(0,5)} – ${j.jam_selesai.substring(0,5)}</small>
                        </div>
                    `;
                });
            }

            document.getElementById("jadwalList").innerHTML = html;
        });
}

document.getElementById("prevBtn").onclick = () => {
    current.setMonth(current.getMonth() - 1);
    renderCalendar();
};

document.getElementById("nextBtn").onclick = () => {
    current.setMonth(current.getMonth() + 1);
    renderCalendar();
};
</script>

@endsection
