@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin/dashboard/dashboard.css') }}">
@endsection

@section('content')
<div class="dashboard-container container-fluid py-4">

    <!-- Header -->
    <div class="mb-4">
        <h3 class="fw-bold text-dark">Dashboard Admin</h3>
        <p class="text-muted">data guru, siswa, dan aktivitas sekolah.</p>
    </div>

    <!-- Statistik Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card shadow-sm">
                <div class="icon-wrapper bg-primary">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <p class="stat-title">Total Guru</p>
                    <h4 class="stat-number">{{ $totalGuru }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card shadow-sm">
                <div class="icon-wrapper bg-success">
                    <i class="bi bi-person-lines-fill"></i>
                </div>
                <div>
                    <p class="stat-title">Total Siswa</p>
                    <h4 class="stat-number">{{ $totalMurid }}</h4>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="stat-card shadow-sm">
                <div class="icon-wrapper bg-warning">
                    <i class="bi bi-house-door-fill"></i>
                </div>
                <div>
                    <p class="stat-title">Total Kelas</p>
                    <h4 class="stat-number">{{ $totalKelas }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row g-4">
        <!-- Grafik Murid -->
        <div class="col-lg-8">
            <div class="chart-card shadow-sm">
                <div class="chart-header">
                    <h5 class="chart-title">Jumlah Murid per Bulan</h5>
                    <p class="text-muted small">Data pembaruan per kelas 7, 8, dan 9</p>
                </div>
                <canvas id="muridPerBulanChart"></canvas>
            </div>
        </div>

        <!-- Grafik Guru -->
        <div class="col-lg-4">
            <div class="chart-card shadow-sm">
                <div class="chart-header">
                    <h5 class="chart-title">Jumlah Tenaga Pengajar</h5>
                </div>
                <canvas id="guruGenderChart"></canvas>
                <div class="chart-legend mt-3 text-center">
                    <span><span class="legend-dot bg-primary"></span> Laki-laki</span>
                    <span class="ms-3"><span class="legend-dot bg-pink"></span> Perempuan</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    // ================
    // 📊 GRAFIK MURID
    // ================
    const ctxMurid = document.getElementById('muridPerBulanChart').getContext('2d');

    const gradient7 = ctxMurid.createLinearGradient(0, 0, 0, 400);
    gradient7.addColorStop(0, '#4e73df');
    gradient7.addColorStop(1, 'rgba(78, 115, 223, 0.2)');

    const gradient8 = ctxMurid.createLinearGradient(0, 0, 0, 400);
    gradient8.addColorStop(0, '#1cc88a');
    gradient8.addColorStop(1, 'rgba(28, 200, 138, 0.2)');

    const gradient9 = ctxMurid.createLinearGradient(0, 0, 0, 400);
    gradient9.addColorStop(0, '#f6c23e');
    gradient9.addColorStop(1, 'rgba(246, 194, 62, 0.2)');

    new Chart(ctxMurid, {
        type: 'bar',
        data: {
            labels: @json($bulan),
            datasets: [
                { label: 'Kelas 7', data: @json($muridKelas7PerBulan), backgroundColor: gradient7, borderColor: '#4e73df', borderWidth: 1 },
                { label: 'Kelas 8', data: @json($muridKelas8PerBulan), backgroundColor: gradient8, borderColor: '#1cc88a', borderWidth: 1 },
                { label: 'Kelas 9', data: @json($muridKelas9PerBulan), backgroundColor: gradient9, borderColor: '#f6c23e', borderWidth: 1 }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    backgroundColor: '#2e2e2e',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    cornerRadius: 6
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f0f0f0' },
                    ticks: { stepSize: 1 }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // ======================
    // 👩‍🏫 GRAFIK GURU GENDER
    // ======================
    const ctxGuru = document.getElementById('guruGenderChart').getContext('2d');
    new Chart(ctxGuru, {
        type: 'doughnut',
        data: {
            labels: ['Laki-laki', 'Perempuan'],
            datasets: [{
                data: [{{ $guruLaki }}, {{ $guruPerempuan }}],
                backgroundColor: ['#4e73df', '#e83e8c'],
                hoverOffset: 6
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: (ctx) => `${ctx.label}: ${ctx.parsed} Guru`
                    }
                }
            }
        }
    });
});
</script>
@endsection
