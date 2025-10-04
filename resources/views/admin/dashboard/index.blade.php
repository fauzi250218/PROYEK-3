@extends('layouts.admin')

@section('title','Dashboard')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin-dashboard.css') }}">
@endsection

@section('content')
<div class="container-fluid dashboard-page">
    <h4 class="mb-4">Dashboard Admin</h4>

    <!-- Statistik singkat -->
    <div class="row mb-3">
        <!-- Total Guru -->
        <div class="col-md-3">
            <div class="stat-card d-flex align-items-center">
                <div>
                    <p class="stat-number">{{ $totalGuru }}</p>
                    <p class="stat-label">Total Guru Terdaftar</p>
                </div>
            </div>
        </div>

        <!-- Total Murid -->
        <div class="col-md-3">
            <div class="stat-card d-flex align-items-center">
                <div>
                    <p class="stat-number">{{ $totalMurid }}</p>
                    <p class="stat-label">Total Siswa Terdaftar</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Murid -->
    <div class="card p-4 shadow-sm">
        <h5 class="mb-3">Distribusi Murid per Kelas</h5>
        <canvas id="muridChart"></canvas>
    </div>
</div>
@endsection

@section('extra-js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data murid per kelas dikirim dari controller
    const muridData = {
        kelas7: {{ $muridKelas7 }},
        kelas8: {{ $muridKelas8 }},
        kelas9: {{ $muridKelas9 }}
    };

    const ctx = document.getElementById('muridChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Kelas 7', 'Kelas 8', 'Kelas 9'],
            datasets: [{
                label: 'Jumlah Murid',
                data: [muridData.kelas7, muridData.kelas8, muridData.kelas9],
                backgroundColor: ['#509CDB', '#28a745', '#ffc107'],
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' Murid';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });
</script>
@endsection
