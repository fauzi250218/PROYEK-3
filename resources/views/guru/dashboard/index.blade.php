@extends('layouts.guru')

@section('title','Dashboard Guru')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/guru/dashboard/dashboard-guru.css') }}">
@endsection

@section('content')
<div class="container-fluid dashboard-page">
    <h4 class="mb-4">Dashboard Guru</h4>

    <div class="row mb-3">
        <div class="col-md-4">
            <div class="stat-card d-flex align-items-center">
                <div>
                    <p class="stat-number">{{ $jumlahMuridKelas ?? 0 }}</p>
                    <p class="stat-label">Jumlah Murid Kelas</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card d-flex align-items-center">
                <div>
                    <p class="stat-number">{{ $jumlahPerkembangan ?? 0 }}</p>
                    <p class="stat-label">Laporan Perkembangan</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card d-flex align-items-center">
                <div>
                    <p class="stat-number">{{ $jumlahNilai ?? 0 }}</p>
                    <p class="stat-label">Nilai Sudah Diinput</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card p-4 shadow-sm">
        <h5 class="mb-3">Informasi Tambahan</h5>
        <p>Belum ada grafik, fitur akan ditambahkan nanti.</p>
    </div>
</div>
@endsection
