@extends('layouts.admin')

@section('title', 'Edit Jadwal')

@section('content')
<div class="container mt-5">
    <h4 class="mb-4">Edit Jadwal</h4>

    <form action="{{ route('admin.jadwal.update', $jadwal->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row mb-3">
            <div class="col-md-4">
                <label>Kelas</label>
                <select name="kelas_id" class="form-control" required>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ $jadwal->kelas_id == $k->id ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Mata Pelajaran</label>
                <input type="text" name="mata_pelajaran" class="form-control" value="{{ $jadwal->mata_pelajaran }}" required>
            </div>
            <div class="col-md-4">
                <label>Guru</label>
                <input type="text" name="guru" class="form-control" value="{{ $jadwal->guru }}" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-3">
                <label>Jam Mulai</label>
                <input type="time" name="jam_mulai" class="form-control" value="{{ $jadwal->jam_mulai }}" required>
            </div>
            <div class="col-md-3">
                <label>Jam Selesai</label>
                <input type="time" name="jam_selesai" class="form-control" value="{{ $jadwal->jam_selesai }}" required>
            </div>
            <div class="col-md-3">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ $jadwal->tanggal }}" required>
            </div>
            <div class="col-md-3">
                <label>Keterangan</label>
                <input type="text" name="keterangan" class="form-control" value="{{ $jadwal->keterangan }}">
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Perbarui</button>
        <a href="{{ route('admin.jadwal.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
