@extends('layouts.guru')
@section('title', 'Daftar Murid - ' . $kelas->nama_kelas)

@section('content')
<div class="container mt-5">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-4">

            {{-- Header --}}
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                <div>
                    <h3 class="fw-bold text-success mb-1">{{ $mataPelajaran }}</h3>
                    <p class="text-muted mb-0 fs-6">Kelas: <strong>{{ $kelas->nama_kelas }}</strong></p>
                </div>
                <a href="{{ route('guru.nilai.index', $kelas->id) }}" 
                   class="btn btn-outline-primary btn-sm mt-3 mt-md-0 d-flex align-items-center gap-1">
                    <i class="bi bi-arrow-left"></i> <span>Kembali ke Daftar Mapel</span>
                </a>
            </div>

            {{-- Tabel Murid --}}
            @if($murids->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-person-x fs-1"></i>
                    <p class="mt-3 mb-0">Belum ada murid dalam kelas ini.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0 modern-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama Murid</th>
                                <th>Email</th>
                                <th>Jenis Kelamin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($murids as $murid)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $murid->nis }}</td>
                                <td class="fw-semibold text-start">{{ $murid->nama }}</td>
                                <td class="text-start">{{ $murid->email }}</td>
                                <td>{{ ucfirst($murid->jenis_kelamin) }}</td>
                                <td>
                                    <a href="{{ route('guru.nilai.detail', $murid->id) }}" 
                                       class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 shadow-sm px-3 rounded-pill">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Tambahan CSS Modern --}}
@push('styles')
<style>
    /* 🌿 Modern Table Styling */
    .modern-table {
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .modern-table thead {
        background-color: #025419;
        color: #2d6a4f;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
    }

    .modern-table thead th {
        border: none;
        padding: 14px 16px;
        text-align: center;
    }

    .modern-table tbody tr {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        transition: all 0.25s ease;
    }

    .modern-table tbody tr:hover {
        background-color: #f8fff9;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .modern-table tbody td {
        border-top: none;
        padding: 14px 16px;
        vertical-align: middle;
    }

    .modern-table tbody td:first-child {
        border-radius: 12px 0 0 12px;
    }

    .modern-table tbody td:last-child {
        border-radius: 0 12px 12px 0;
    }

    /* Responsiveness */
    @media (max-width: 768px) {
        .modern-table thead {
            display: none;
        }

        .modern-table tbody tr {
            display: block;
            margin-bottom: 12px;
        }

        .modern-table tbody td {
            display: flex;
            justify-content: space-between;
            padding: 10px 14px;
            border-bottom: 1px solid #f0f0f0;
        }

        .modern-table tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #2d6a4f;
            text-transform: uppercase;
            font-size: 0.75rem;
        }

        .modern-table tbody td:last-child {
            border-bottom: none;
        }
    }
</style>
@endpush
@endsection
