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
        <div class="col-md-6">
            <div class="stat-card bg-primary text-white">
                <h2 class="fw-bold mb-0">{{ $jumlahMuridKelas }}</h2>
                <p class="mb-0">Jumlah Murid Kelas</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="stat-card bg-warning text-dark">
                <h2 class="fw-bold mb-0">{{ $jumlahNilai }}</h2>
                <p class="mb-0">Nilai Sudah Diinput</p>
            </div>
        </div>
    </div>

    <!-- Grafik Nilai yang Sudah Diinput -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Jumlah Nilai yang Sudah Diinput per Bulan</h5>
            <canvas id="nilaiChart" height="120"></canvas>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // ================================
    // Ambil data dari Controller
    // ================================
    const labelsBulan = @json($labelsBulan);
    const dataNilai = @json($dataNilai);

    document.addEventListener("DOMContentLoaded", function () {

        // ================================
        // Grafik Nilai yang Sudah Diinput
        // ================================
        const ctxNilai = document.getElementById("nilaiChart");

        if (ctxNilai) {
            new Chart(ctxNilai, {
                type: "line",
                data: {
                    labels: labelsBulan,
                    datasets: [{
                        label: "Jumlah Nilai Diinput",
                        data: dataNilai,
                        fill: true,
                        backgroundColor: "rgba(255, 206, 86, 0.3)",
                        borderColor: "#ffc107",
                        borderWidth: 3,
                        tension: 0.4,
                        pointBackgroundColor: "#fff",
                        pointBorderColor: "#ffc107",
                        pointHoverBackgroundColor: "#ffc107",
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
                                label: (ctx) => `${ctx.parsed.y} nilai`
                            }
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
