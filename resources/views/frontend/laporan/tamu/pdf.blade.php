<!DOCTYPE html>
<html>

<head>
    <title>Laporan Tamu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h3 {
            text-align: center;
            margin-top: 20px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background-color: #eee;
        }

        td img {
            max-height: 70px;
            max-width: 100px;
        }
    </style>
</head>

<body>
    <h3>Laporan Tamu</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nama Tamu</th>
                <th>Jumlah Tamu</th>
                <th>Tujuan</th>
                <th>Whatsapp</th>
                <th>Waktu Tiba</th>
                <th>Waktu Pulang</th>
                <th>Status</th>
                <th>Satpam Masuk</th>
                <th>Satpam Pulang</th>
                <th>Catatan</th>
                <th>Dibuat Oleh</th>
                <th>Perusahaan</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $row)
                @php
                    $status = '';
                    if ($row->is_status == 1) {
                        $status = 'Appointment';
                    } elseif ($row->is_status == 2) {
                        $status = 'Masuk';
                    } elseif ($row->is_status == 3) {
                        $status = 'Pulang';
                    }
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ date('d-m-Y H:i', strtotime($row->created_at)) }}</td>
                    <td>{{ $row->nama_tamu }}</td>
                    <td>{{ $row->jumlah_tamu }}</td>
                    <td>{{ $row->tujuan }}</td>
                    <td>{{ $row->whatsapp }}</td>
                    <td>{{ $row->arrive_at == null ? '' : date('d-m-Y H:i', strtotime($row->arrive_at)) }}</td>
                    <td>{{ $row->leave_at == null ? '' : date('d-m-Y H:i', strtotime($row->leave_at)) }}</td>
                    <td>{{ $status }}</td>
                    <td>{{ $row->satpam->name ?? '' }}</td>
                    <td>{{ $row->satpam_pulang->name ?? '' }}</td>
                    <td>{{ $row->catatan }}</td>
                    <td>{{ $row->user->name ?? '' }}</td>
                    <td>{{ $row->company->company_name ?? '' }}</td>
                    <td>
                        @if($row->foto)
                            <img src="{{ public_path('storage/' . $row->foto) }}" alt="Foto Tamu">
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
