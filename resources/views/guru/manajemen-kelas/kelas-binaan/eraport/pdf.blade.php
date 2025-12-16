<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            table-layout: fixed;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
            word-wrap: break-word;
        }

        thead th {
            background-color: #e0e6f7;
            font-weight: bold;
            text-align: center;
        }

        /* Lebar kolom */
        th:nth-child(1), td:nth-child(1) { width: 22%; }
        th:nth-child(2), td:nth-child(2) { width: 12%; text-align: center; }
        th:nth-child(3), td:nth-child(3) { width: 66%; text-align: justify; } /* Justify di sini */
    </style>
</head>
<body>

<div class="header">
    <h3>LAPORAN HASIL BELAJAR</h3>
    <h4>SMP 1 MARS</h4>
</div>

<p><strong>Nama:</strong> {{ $murid->nama }}</p>
<p><strong>NIS:</strong> {{ $murid->nis }}</p>
<p><strong>Kelas:</strong> {{ $murid->kelas->nama_kelas }}</p>
<hr>

<table>
    <thead>
        <tr>
            <th>Mata Pelajaran</th>
            <th>Nilai Akhir</th>
            <th>Catatan Perkembangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($mapels as $mapel)
        <tr>
            <td>{{ $mapel }}</td>
            <td>{{ $nilai->where('mata_pelajaran', $mapel)->first()->rata_rata ?? '-' }}</td>
            <td>{{ $catatanMapel[$mapel]->catatan ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
