@extends('layouts.guru')
@section('title', 'Detail Nilai Siswa')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/guru/nilai/detail-nilai.css') }}">

<style>
    /* Lebih modern & clean */
    .nilai-table table {
        border-radius: 12px;
        overflow: hidden;
    }

    .nilai-table thead {
        background: #f8f9fa;
        font-weight: 600;
    }

    tr.add-row {
        background: #f4f6f9;
    }

    /* Aksi tombol sejajar & lebih rapi */
    .aksi-buttons {
        display: flex;
        gap: 6px;
        justify-content: center;
    }

    .aksi-buttons .btn {
        padding: 4px 8px;
        border-radius: 6px;
    }
</style>
@endsection

@section('content')
<div class="container py-5 nilai-page">

    <!-- Header -->
    <div class="nilai-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">{{ $murid->nama }}</h2>
            <p class="text-muted mb-0">Kelas: {{ $murid->kelas->nama_kelas }}</p>
        </div>
    </div>

    <!-- Tabel Nilai -->
    <div class="nilai-table shadow-card">
        <table class="table align-middle table-hover text-center mb-0">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Mata Pelajaran</th>
                    <th>Tugas</th>
                    <th>Ulangan Harian</th>
                    <th>UTS</th>
                    <th>UAS</th>
                    <th>Rata-rata</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>

                {{-- BARIS TAMBAH NILAI --}}
                <tr class="add-row">
                    <form action="{{ route('guru.nilai.store', $murid->id) }}" method="POST">
                        @csrf
                        <td><strong>+</strong></td>

                        <td>
                            <select name="mata_pelajaran" class="form-select form-select-sm" required>
                                <option value="">Pilih Mapel</option>

                                {{-- AUTO SELECT MAPEL SESUAI YANG DIKLIK PADA HALAMAN SEBELUMNYA --}}
                                @foreach($mapelList as $m)
                                    <option value="{{ $m }}"
                                        {{ isset($selectedMapel) && $selectedMapel == $m ? 'selected' : '' }}>
                                        {{ $m }}
                                    </option>
                                @endforeach
                            </select>
                        </td>

                        <td><input type="number" name="tugas" class="form-control form-control-sm"></td>
                        <td><input type="number" name="ulangan_harian" class="form-control form-control-sm"></td>
                        <td><input type="number" name="uts" class="form-control form-control-sm"></td>
                        <td><input type="number" name="uas" class="form-control form-control-sm"></td>

                        <td>-</td>

                        <td>
                            <button class="btn btn-success btn-sm px-3">Simpan</button>
                        </td>
                    </form>
                </tr>

                {{-- DATA NILAI --}}
                @forelse($murid->nilai as $nilai)
                <tr id="row-{{ $nilai->id }}">
                    <td>{{ $loop->iteration }}</td>
                    <td class="text-dark fw-semibold">{{ $nilai->mata_pelajaran }}</td>

                    <td>{{ $nilai->tugas }}</td>
                    <td>{{ $nilai->ulangan_harian }}</td>
                    <td>{{ $nilai->uts }}</td>
                    <td>{{ $nilai->uas }}</td>

                    <td><strong>{{ number_format($nilai->rata_rata, 2) }}</strong></td>

                    <td>
                        <div class="aksi-buttons">

                            {{-- tombol edit --}}
                            <button onclick="editRow({{ $nilai->id }})" 
                                class="btn btn-warning btn-sm" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </button>

                            {{-- tombol hapus --}}
                            <form action="{{ route('guru.nilai.destroy', $nilai->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Hapus nilai ini?')" title="Hapus">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>

                {{-- EDIT ROW --}}
                <tr id="edit-{{ $nilai->id }}" style="display:none; background:#fcfcfc;">
                    <form action="{{ route('guru.nilai.update', $nilai->id) }}" method="POST">
                        @csrf @method('PUT')

                        <td>{{ $loop->iteration }}</td>
                        <td class="fw-semibold">{{ $nilai->mata_pelajaran }}</td>

                        <td><input type="number" name="tugas" value="{{ $nilai->tugas }}" class="form-control form-control-sm"></td>
                        <td><input type="number" name="ulangan_harian" value="{{ $nilai->ulangan_harian }}" class="form-control form-control-sm"></td>
                        <td><input type="number" name="uts" value="{{ $nilai->uts }}" class="form-control form-control-sm"></td>
                        <td><input type="number" name="uas" value="{{ $nilai->uas }}" class="form-control form-control-sm"></td>

                        <td>-</td>

                        <td>
                            <div class="aksi-buttons">
                                <button class="btn btn-success btn-sm px-3">Simpan</button>
                                <button type="button" onclick="cancelEdit({{ $nilai->id }})" 
                                        class="btn btn-secondary btn-sm px-3">Batal</button>
                            </div>
                        </td>

                    </form>
                </tr>

                @empty
                <tr>
                    <td colspan="8" class="text-muted py-4">Belum ada nilai.</td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>

<script>
function editRow(id) {
    document.getElementById("row-" + id).style.display = "none";
    document.getElementById("edit-" + id).style.display = "table-row";
}

function cancelEdit(id) {
    document.getElementById("row-" + id).style.display = "table-row";
    document.getElementById("edit-" + id).style.display = "none";
}
</script>

@endsection
