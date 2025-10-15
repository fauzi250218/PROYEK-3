@extends('layouts.guru')
@section('title','Perkembangan Belajar - Kelas Binaan')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/guru/manajemen-siswa/perkembangan.css') }}">
@endsection

@section('content')
<div class="perkembangan-wrapper container-fluid py-4">

    <!-- Header -->
    <div class="section-header align-with-header mb-4">
        <h3 class="fw-bold text-dark mb-1">Perkembangan Belajar Siswa</h3>
        <p class="text-muted">Pantau perkembangan belajar siswa dalam berbagai aspek penilaian.</p>
    </div>

    <!-- Search Bar -->
    <div class="search-bar mb-4">
        <input type="text" class="form-control search-input" placeholder="Cari nama siswa...">
    </div>

    <!-- Grid Card -->
    <div class="row g-4">
        @php
            $data = [
                ['nama'=>'Rizky Ahmad','kognitif'=>90,'motorik'=>85,'sosial'=>88,'bahasa'=>92],
                ['nama'=>'Siti Rahma','kognitif'=>93,'motorik'=>89,'sosial'=>90,'bahasa'=>95],
                ['nama'=>'Budi Santoso','kognitif'=>87,'motorik'=>83,'sosial'=>84,'bahasa'=>88],
            ];
        @endphp

        @foreach($data as $row)
        <div class="col-md-4">
            <div class="card-student shadow-sm">
                <div class="student-header d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-semibold">{{ $row['nama'] }}</h5>
                    <span class="badge-status">Aktif</span>
                </div>

                <div class="student-body mt-3">
                    <div class="progress-item mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Kognitif</span>
                            <span class="fw-bold text-dark">{{ $row['kognitif'] }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: {{ $row['kognitif'] }}%"></div>
                        </div>
                    </div>

                    <div class="progress-item mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Motorik</span>
                            <span class="fw-bold text-dark">{{ $row['motorik'] }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: {{ $row['motorik'] }}%"></div>
                        </div>
                    </div>

                    <div class="progress-item mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Sosial-Emosional</span>
                            <span class="fw-bold text-dark">{{ $row['sosial'] }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: {{ $row['sosial'] }}%"></div>
                        </div>
                    </div>

                    <div class="progress-item">
                        <div class="d-flex justify-content-between">
                            <span>Bahasa</span>
                            <span class="fw-bold text-dark">{{ $row['bahasa'] }}%</span>
                        </div>
                        <div class="progress">
                            <div class="progress-bar bg-success" style="width: {{ $row['bahasa'] }}%"></div>
                        </div>
                    </div>
                </div>

                <div class="student-footer text-end mt-3">
                    <a href="#" class="btn-detail">Lihat Detail</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

</div>
@endsection
