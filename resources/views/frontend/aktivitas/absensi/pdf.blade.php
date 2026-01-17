<!DOCTYPE html>
<html>

<head>
    <title>Data Absensi Satpam</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #eee;
        }

        img {
            width: 80px;
            height: auto;
        }
    </style>
</head>

<body>
    <h3 style="text-align:center;">Data Absensi Satpam</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Satpam</th>
                <th>Lokasi Masuk</th>
                <th>Lokasi Pulang</th>
                <th>Shift</th>
                <th>Jam Shift Masuk</th>
                <th>Masuk</th>
                <th>Jam Shift Pulang</th>
                <th>Keluar</th>
                <th>Status</th>
                <th>Foto Masuk</th>
                <th>Foto Pulang</th>
                <th>Catatan Masuk</th>
                <th>Catatan Pulang</th>
                <th>Perusahaan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ date('d-m-Y', strtotime($row->tanggal)) }}</td>
                    <td>{{ $row->satpam->name ?? '' }}</td>
                    <td>{{ $row->latitude }} - {{ $row->longitude }}</td>
                    <td>{{ $row->latitude2 }} - {{ $row->longitude2 }}</td>
                    <td>{{ $row->shift_name }}</td>
                    <td>{{ $row->jam_setting_masuk }}</td>
                    <td>{{ $row->jam_masuk ? date('d-m-Y H:i', strtotime($row->jam_masuk)) : '' }}</td>
                    <td>{{ $row->jam_setting_pulang }}</td>
                    <td>{{ $row->jam_keluar ? date('d-m-Y H:i', strtotime($row->jam_keluar)) : '' }}</td>
                    <td>{{ $row->status == 1 ? 'Masuk' : 'Pulang' }}</td>
                    {{-- FOTO --}}
                    <td style="text-align:center">
                        @if (!empty($row->foto_masuk) && file_exists(public_path('storage/' . $row->foto_masuk)))
                            <img src="{{ public_path('storage/' . $row->foto_masuk) }}">
                        @else
                            -
                        @endif
                    </td>
                    {{-- FOTO --}}
                    <td style="text-align:center">
                        @if (!empty($row->foto_pulang) && file_exists(public_path('storage/' . $row->foto_pulang)))
                            <img src="{{ public_path('storage/' . $row->foto_pulang) }}">
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $row->catatan_masuk }}</td>
                    <td>{{ $row->catatan_keluar }}</td>
                    <td>{{ $row->company->company_name ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
