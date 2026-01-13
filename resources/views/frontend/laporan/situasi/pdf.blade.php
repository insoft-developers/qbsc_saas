<!DOCTYPE html>
<html>

<head>
    <title>Laporan Situasi</title>
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
    <h3>Laporan Situasi</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Satpam</th>
                <th>Tanggal</th>
                <th>Laporan</th>
                <th>Perusahaan</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->satpam->name ?? '' }}</td>
                    <td>{{ date('d-m-Y H:i', strtotime($row->tanggal)) }}</td>
                    <td>{{ $row->laporan }}</td>
                    <td>{{ $row->company->company_name ?? '' }}</td>
                    <td>
                        @if($row->foto)
                            <img src="{{ public_path('storage/' . $row->foto) }}" alt="Foto Situasi">
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
