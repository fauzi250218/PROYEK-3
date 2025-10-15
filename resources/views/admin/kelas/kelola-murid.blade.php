@extends('layouts.admin')

@section('title', 'Kelola Siswa di Kelas ' . $kelas->nama_kelas)

@section('content')
<div class="container-fluid mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="page-title">Kelola Siswa - Kelas {{ $kelas->nama_kelas }}</h4>
        <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <p><strong>Nama Kelas:</strong> {{ $kelas->nama_kelas }}</p>
            <p><strong>Wali Kelas:</strong> {{ $kelas->wali->name ?? '-' }}</p>
            <p><strong>Deskripsi:</strong> {{ $kelas->deskripsi ?? '-' }}</p>
        </div>
    </div>

    <!-- Tambahkan Siswa -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header bg-primary text-white">
            Tambahkan Siswa ke Kelas
        </div>
        <div class="card-body">
            <form action="{{ route('admin.kelas.tambahMurid', $kelas->id) }}" method="POST">
                @csrf

                <ul class="nav nav-tabs mb-3" id="jenjangTab" role="tablist">
                    @foreach ($muridBelumMasukKelas as $jenjang => $list)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="tab-{{ $jenjang }}"
                                data-bs-toggle="tab" data-bs-target="#content-{{ $jenjang }}" type="button" role="tab">
                                Kelas {{ $jenjang }}
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content">
                    @foreach ($muridBelumMasukKelas as $jenjang => $list)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="content-{{ $jenjang }}" role="tabpanel">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th><input type="checkbox" id="selectAll{{ $jenjang }}"></th>
                                            <th>No</th>
                                            <th>NIS</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>Jenis Kelamin</th>
                                            <th>No. WhatsApp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($list as $index => $murid)
                                            <tr>
                                                <td><input type="checkbox" name="murid_ids[]" value="{{ $murid->id }}"></td>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $murid->nis }}</td>
                                                <td>{{ $murid->nama }}</td>
                                                <td>{{ $murid->email }}</td>
                                                <td>{{ $murid->jenis_kelamin }}</td>
                                                <td>{{ $murid->nomer_whatsapp }}</td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="7" class="text-center text-muted py-3">Tidak ada siswa tersedia</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Tambah ke Kelas
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar Siswa di Kelas -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <strong>Daftar Siswa di Kelas {{ $kelas->nama_kelas }}</strong>
        </div>
        <div class="card-body">
            @if($muridDalamKelas->count())
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>NIS</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Jenis Kelamin</th>
                                <th>No. WhatsApp</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($muridDalamKelas as $index => $murid)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $murid->nis }}</td>
                                    <td>{{ $murid->nama }}</td>
                                    <td>{{ $murid->email }}</td>
                                    <td>{{ $murid->jenis_kelamin }}</td>
                                    <td>{{ $murid->nomer_whatsapp }}</td>
                                    <td>
                                        <form action="{{ route('admin.kelas.hapusMurid', ['kelas_id' => $kelas->id, 'murid_id' => $murid->id]) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus siswa ini dari kelas?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
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
                <div class="text-center text-muted py-4">Belum ada siswa di kelas ini.</div>
            @endif
        </div>
    </div>
</div>

<script>
document.querySelectorAll('[id^="selectAll"]').forEach(chk => {
    chk.addEventListener('change', e => {
        const tab = e.target.id.replace('selectAll', '');
        document.querySelectorAll(`#content-${tab} input[type="checkbox"][name="murid_ids[]"]`)
            .forEach(box => box.checked = e.target.checked);
    });
});
</script>
@endsection
