@extends('layouts.admin')

@section('title', 'Kelola Siswa di Kelas ' . $kelas->nama_kelas)

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin/kelas/kelola-murid.css') }}">
@endsection

@section('content')
<div class="container-fluid kelola-siswa-page py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 header-section">
        <h3 class="fw-bold mb-0 text-dark">
            <i class="bi bi-people-fill text-primary me-2"></i> Kelola Siswa - {{ $kelas->nama_kelas }}
        </h3>
        <a href="{{ route('admin.kelas.index') }}" class="btn btn-outline-secondary rounded-3 px-4 shadow-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Informasi Kelas -->
    <div class="card info-card mb-4 shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h5 class="fw-semibold text-primary mb-3 d-flex align-items-center">
                <i class="bi bi-info-circle-fill me-2"></i> Informasi Kelas
            </h5>

            <div class="row g-3">
                <div class="col-md-4">
                    <div class="info-item">
                        <p class="label">Nama Kelas</p>
                        <h6 class="value">{{ $kelas->nama_kelas }}</h6>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-item">
                        <p class="label">Wali Kelas</p>
                        <h6 class="value">
                            {{ $kelas->guru && $kelas->guru->user ? $kelas->guru->user->name : '-' }}
                        </h6>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-item">
                        <p class="label">Deskripsi</p>
                        <h6 class="value">{{ $kelas->deskripsi ?? '-' }}</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jadwal Pelajaran -->
    <div class="card jadwal-card mb-4 border-0 shadow-md rounded-4 overflow-hidden">
        <div class="card-header gradient-header text-white py-3 px-4 d-flex align-items-center">
            <i class="bi bi-calendar3-week me-2 fs-5"></i>
            <h5 class="fw-semibold mb-0">Jadwal Pelajaran Kelas {{ $kelas->nama_kelas }}</h5>
        </div>

        <div class="card-body px-4 pb-4 bg-white">

            @php
                use Carbon\Carbon;

                // Urutan nama hari secara umum
                $urutanHari = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];

                // PROSES: dari tanggal → jadwal unik → grup per hari
                $jadwalPerKelas = $jadwalKelas
                    ->where('kelas_id', $kelas->id)

                    // Mengubah tanggal → hari
                    ->map(function ($item) {
                        $hari = Carbon::parse($item->tanggal)
                                      ->locale('id')
                                      ->translatedFormat('l');

                        $item->hari = ucfirst($hari);
                        return $item;
                    })

                    // Hilangkan jadwal yang sama (perwakilan saja)
                    ->unique(function ($item) {
                        return $item->hari
                            . '|' . $item->mata_pelajaran
                            . '|' . $item->guru
                            . '|' . $item->jam_mulai
                            . '|' . $item->jam_selesai;
                    })

                    // Kelompokkan berdasarkan hari
                    ->groupBy('hari')

                    // Sort by hari
                    ->sortBy(function ($_, $hari) use ($urutanHari) {
                        return array_search($hari, $urutanHari);
                    });
            @endphp

            @if($jadwalPerKelas->count())
                <div class="table-responsive">
                    <table class="table align-middle table-hover clean-table">
                        <thead>
                            <tr>
                                <th class="text-center">Hari</th>
                                <th>Mata Pelajaran</th>
                                <th>Guru</th>
                                <th>Jam Pelajaran</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($jadwalPerKelas as $hari => $items)
                                @php $isFirst = true; @endphp

                                @foreach($items as $jadwal)
                                    <tr>
                                        {{-- Tampilkan nama hari sekali saja --}}
                                        @if($isFirst)
                                            <td class="text-center fw-semibold text-dark align-middle" 
                                                rowspan="{{ count($items) }}">
                                                {{ $hari }}
                                            </td>
                                            @php $isFirst = false; @endphp
                                        @endif

                                        <td class="fw-semibold">{{ $jadwal->mata_pelajaran }}</td>
                                        <td>{{ $jadwal->guru }}</td>
                                        <td>
                                            {{ substr($jadwal->jam_mulai, 0, 5) }}
                                            -
                                            {{ substr($jadwal->jam_selesai, 0, 5) }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>

                    </table>
                </div>
            @else
                <div class="text-center text-muted py-4">
                    <i class="bi bi-calendar-x display-6 d-block mb-2"></i>
                    <p class="mb-0">Belum ada jadwal untuk kelas ini.</p>
                </div>
            @endif

        </div>
    </div>

    <!-- Daftar Siswa -->
    <div class="card siswa-card border-0 shadow-md rounded-4 overflow-hidden">
        <div class="card-header gradient-header text-white py-3 px-4 d-flex align-items-center">
            <i class="bi bi-list-ul me-2 fs-5"></i>
            <h5 class="fw-semibold mb-0">Daftar Siswa Kelas {{ $kelas->nama_kelas }}</h5>
        </div>

        <div class="card-body px-4 pb-4 bg-white">

            @if($muridDalamKelas->count())
                <div class="table-responsive">
                    <table class="table align-middle table-hover clean-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Jenis Kelamin</th>
                                <th>No. WhatsApp</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($muridDalamKelas as $index => $murid)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $murid->nis }}</td>
                                    <td class="fw-semibold">{{ $murid->nama }}</td>
                                    <td>{{ $murid->email }}</td>
                                    <td>{{ $murid->jenis_kelamin }}</td>
                                    <td>{{ $murid->nomer_whatsapp ?? '-' }}</td>

                                    <td class="text-center">
                                        <form action="{{ route('admin.kelas.hapusMurid', [
                                            'kelas_id' => $kelas->id,
                                            'murid_id' => $murid->id
                                        ]) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus siswa ini dari kelas?')"
                                        class="d-inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="btn btn-outline-danger btn-sm rounded-pill px-3 shadow-sm">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            @else
                <div class="text-center text-muted py-4">
                    <i class="bi bi-person-x display-6 d-block mb-2"></i>
                    <p class="mb-0">Belum ada siswa di kelas ini.</p>
                </div>
            @endif

        </div>

    </div>

</div>
@endsection
