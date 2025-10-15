@extends('layouts.guru')
@section('title','Kelas Ajaran')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/guru/dashboard-guru.css') }}">
@endsection

@section('content')
<div class="container-fluid dashboard-page">
  <h4 class="mb-4">Kelas Ajaran</h4>

  <div class="card p-4 shadow-sm">
    <table class="table table-bordered table-hover">
      <thead class="table-primary">
        <tr>
          <th>No</th>
          <th>Kelas</th>
          <th>Mata Pelajaran</th>
        </tr>
      </thead>
      <tbody>
        @forelse($kelasAjaran as $i => $kelas)
          <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $kelas['kelas'] }}</td>
            <td>{{ $kelas['mapel'] }}</td>
          </tr>
        @empty
          <tr><td colspan="3" class="text-center">Belum ada kelas ajaran.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
