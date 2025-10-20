@extends('layouts.guru')

@section('title', 'Dashboard Guru')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/guru/dashboard/dashboard-guru.css') }}">
@endsection

@section('content')
<div class="container-fluid dashboard-page">
    <h4 class="mb-4 fw-bold text-dark">Dashboard Guru</h4>

    <!-- Statistik Singkat -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="stat-card bg-primary text-white">
                <h2 class="fw-bold mb-0">{{ $jumlahMuridKelas }}</h2>
                <p class="mb-0">Jumlah Murid Kelas</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card bg-success text-white">
                <h2 class="fw-bold mb-0">{{ $jumlahPerkembangan }}</h2>
                <p class="mb-0">Laporan Perkembangan</p>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card bg-warning text-dark">
                <h2 class="fw-bold mb-0">{{ $jumlahNilai }}</h2>
                <p class="mb-0">Nilai Sudah Diinput</p>
            </div>
        </div>
    </div>

    <!-- Grafik Perkembangan per Bulan -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Laporan Perkembangan Siswa</h5>
            <canvas id="laporanChart" height="120"></canvas>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Ambil data dari controller Laravel
    const labels = @json($labelsBulan);
    const dataPoints = @json($dataBulan);

    document.addEventListener("DOMContentLoaded", function () {
        const ctx = document.getElementById("laporanChart");

        if (ctx) {
            new Chart(ctx, {
                type: "line",
                data: {
                    labels: labels,
                    datasets: [{
                        label: "Jumlah Laporan",
                        data: dataPoints,
                        fill: true,
                        backgroundColor: "rgba(40, 167, 69, 0.2)",
                        borderColor: "#28a745",
                        borderWidth: 3,
                        tension: 0.4,
                        pointBackgroundColor: "#fff",
                        pointBorderColor: "#28a745",
                        pointHoverBackgroundColor: "#28a745",
                        pointHoverBorderColor: "#fff",
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: "bottom" },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => `${ctx.parsed.y} laporan`
                            },
                            backgroundColor: "#1b4d3e",
                            titleColor: "#fff",
                            bodyColor: "#fff"
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 1 },
                            grid: { color: "#e9ecef" }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: "#333" }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
