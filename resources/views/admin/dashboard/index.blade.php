@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin/dashboard/dashboard.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
<style>
/* FIX KHUSUS CHART */
.chart-box{
    height:320px;
    position:relative;
}
.chart-box.small{
    height:260px;
}
.chart-box canvas{
    width:100% !important;
    height:100% !important;
}
</style>
@endsection

@section('content')
<div class="dashboard-wrap">

    <!-- ================= HEADER ================= -->
    <div class="dashboard-header">
        <h1>Dashboard Admin</h1>
        <p>Ringkasan data akademik dan pembayaran sekolah</p>
    </div>

    <!-- ================= STAT ================= -->
    <div class="stat-grid">

        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="stat-info">
                <span>Total Guru</span>
                <h2>{{ $totalGuru }}</h2>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <i class="bi bi-person-lines-fill"></i>
            </div>
            <div class="stat-info">
                <span>Total Siswa</span>
                <h2>{{ $totalMurid }}</h2>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="bi bi-house-door-fill"></i>
            </div>
            <div class="stat-info">
                <span>Total Kelas</span>
                <h2>{{ $totalKelas }}</h2>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon info">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="stat-info">
                <span>Total Pembayaran</span>
                <h2>Rp {{ number_format($totalPembayaran,0,',','.') }}</h2>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="stat-info">
                <span>SPP Lunas</span>
                <h2>{{ $sppLunas }}</h2>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon danger">
                <i class="bi bi-x-circle-fill"></i>
            </div>
            <div class="stat-info">
                <span>SPP Belum Lunas</span>
                <h2>{{ $sppBelumLunas }}</h2>
            </div>
        </div>

    </div>

    <!-- ================= CHART ================= -->
    <div class="chart-grid">

        <!-- BAR CHART -->
        <div class="chart-card">
            <div class="chart-header">
                <h3>Jumlah Murid per Bulan</h3>
                <span>Akumulasi murid setiap angkatan</span>
            </div>
            <div class="chart-box">
                <canvas id="muridPerBulanChart"></canvas>
            </div>
        </div>

        <!-- DOUGHNUT -->
        <div class="chart-card">
            <div class="chart-header">
                <h3>Komposisi Guru</h3>
                <span>Berdasarkan jenis kelamin</span>
            </div>
            <div class="chart-box small">
                <canvas id="guruGenderChart"></canvas>
            </div>

            <div class="chart-legend">
                <span><i class="dot primary"></i> Laki-laki</span>
                <span><i class="dot pink"></i> Perempuan</span>
            </div>
        </div>

    </div>

</div>
@endsection

@section('extra-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {

    /* ================= BAR CHART ================= */
    new Chart(document.getElementById('muridPerBulanChart'), {
        type: 'bar',
        data: {
            labels: @json($bulan),
            datasets: [
                {
                    label: 'Kelas 7',
                    data: @json($muridKelas7PerBulan),
                    backgroundColor: '#2563eb',
                    borderRadius: 8
                },
                {
                    label: 'Kelas 8',
                    data: @json($muridKelas8PerBulan),
                    backgroundColor: '#16a34a',
                    borderRadius: 8
                },
                {
                    label: 'Kelas 9',
                    data: @json($muridKelas9PerBulan),
                    backgroundColor: '#f59e0b',
                    borderRadius: 8
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            },
            scales: {
                x: { grid: { display:false } },
                y: {
                    beginAtZero: true,
                    grid: { color:'rgba(0,0,0,.08)' }
                }
            }
        }
    });

    /* ================= DOUGHNUT ================= */
    new Chart(document.getElementById('guruGenderChart'), {
        type: 'doughnut',
        data: {
            labels: ['Laki-laki','Perempuan'],
            datasets: [{
                data: [{{ $guruLaki }}, {{ $guruPerempuan }}],
                backgroundColor: ['#2563eb','#ec4899']
            }]
        },
        options: {
            responsive:true,
            cutout:'60%',
            plugins:{
                legend:{ display:false }
            }
        }
    });

});
</script>
@endsection
