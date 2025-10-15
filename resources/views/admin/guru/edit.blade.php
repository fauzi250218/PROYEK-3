@extends('layouts.admin')

@section('title', 'Edit Guru')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/admin-guru-form.css') }}">
@endsection

@section('content')
<div class="container mt-5 pt-5">
    <h3 class="mb-4">Edit Guru</h3>

    <div class="form-wrapper p-4 bg-white rounded shadow-sm">
        {{-- Pastikan $guru dan $kelas diterima dari controller --}}
        @if(!isset($guru))
            <div class="alert alert-danger">Data guru tidak tersedia.</div>
        @else
            <form action="{{ route('admin.guru.update', $guru->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nama --}}
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $guru->user->name ?? '') }}" required>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Alamat Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $guru->user->email ?? '') }}" required>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Wali Kelas (opsional)</label>
                        <select name="kelas_id" class="form-control">
                            <option value="">-- Tidak Ada --</option>

                            {{-- Pastikan $kelas adalah collection --}}
                            @if(isset($kelas) && $kelas->count())
                                @foreach($kelas as $k)
                                    {{-- Pilih jika kelas ini dipunya oleh user guru tersebut (kelas.user_id) --}}
                                    {{-- Kita bandingkan dengan $guru->user_id --}}
                                    <option value="{{ $k->id }}"
                                        {{ (old('kelas_id') !== null ? old('kelas_id') : ($k->user_id ?? null)) == $guru->user_id ? 'selected' : '' }}>
                                        {{ $k->nama_kelas }} {{ $k->deskripsi ? '— '.$k->deskripsi : '' }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Kata Sandi (isi jika ingin ubah)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor WhatsApp</label>
                        <input type="text" name="nomer_whatsapp" class="form-control" value="{{ old('nomer_whatsapp', $guru->nomer_whatsapp) }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Mata Pelajaran</label>
                    <input type="text" name="mata_pelajaran" class="form-control" value="{{ old('mata_pelajaran', $guru->mata_pelajaran) }}">
                </div>

                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('admin.guru.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection
