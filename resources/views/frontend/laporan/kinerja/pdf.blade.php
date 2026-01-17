<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Kinerja Satpam</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px;
        }

        h3 {
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #f0f0f0;
        }

        .text-left {
            text-align: left;
        }

        .foto {
            width: 60px;
            height: 80px;
            object-fit: cover;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

<h3>LAPORAN KINERJA SATPAM</h3>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Foto</th>
            <th>Nama Satpam</th>
            <th>Jabatan</th>
            <th>Hadir</th>
            <th>Tepat Waktu</th>
            <th>Terlambat</th>
            <th>Total Terlambat</th>
            <th>Cepat Pulang</th>
            <th>Total Cepat Pulang</th>
            <th>Titik Patroli</th>
            <th>Patroli Diluar Jadwal</th>
            <th>Perusahaan</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($data as $index => $row)
            @php
                // HITUNG TERLAMBAT (MENIT)
                $totalTerlambat = 0;
                $pulangCepat = 0;

                foreach ($row->absensi as $abs) {
                    if ($abs->status == 2 && str_contains($abs->catatan_masuk ?? '', 'terlambat')) {
                        preg_match('/\d+/', $abs->catatan_masuk, $m);
                        $totalTerlambat += (int) ($m[0] ?? 0);
                    }

                    if ($abs->status == 2 && str_contains($abs->catatan_keluar ?? '', 'pulang lebih cepat')) {
                        preg_match('/\d+/', $abs->catatan_keluar, $m);
                        $pulangCepat += (int) ($m[0] ?? 0);
                    }
                }

                $patroliDiluar = $row->patroli->filter(function ($p) {
                    if (empty($p->jam_awal_patroli) || empty($p->jam_akhir_patroli)) {
                        return true;
                    }

                    return !(
                        $p->jam >= $p->jam_awal_patroli &&
                        $p->jam <= $p->jam_akhir_patroli
                    );
                })->count();
            @endphp

            <tr>
                <td>{{ $index + 1 }}</td>
                <td>
                    @if (!empty($row->face_photo_path))
                        <img src="{{ public_path('storage/' . $row->face_photo_path) }}" class="foto">
                    @else
                        -
                    @endif
                </td>
                <td class="text-left">{{ $row->name }}</td>
                <td>{{ $row->is_danru ? 'Danru' : 'Anggota' }}</td>
                <td>{{ $row->absensi->where('status', 2)->count() }}</td>
                <td>{{ $row->absensi->where('status', 2)->where('is_terlambat', 0)->where('is_pulang_cepat', 0)->count() }}</td>
                <td>{{ $row->absensi->where('status', 2)->where('is_terlambat', 1)->count() }} X</td>
                <td>{{ $totalTerlambat }} menit</td>
                <td>{{ $row->absensi->where('status', 2)->where('is_pulang_cepat', 1)->count() }} X</td>
                <td>{{ $pulangCepat }} menit</td>
                <td>{{ $row->patroli->count() }}</td>
                <td>{{ $patroliDiluar }}</td>
                <td>{{ $row->company->company_name ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
