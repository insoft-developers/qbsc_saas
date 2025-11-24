<!DOCTYPE html>
<html>

<head>
    <title>Laporan Situasi</title>
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
    </style>
</head>

<body>
    <h3 style="text-align:center;">Laporan Situasi</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Satpam</th>
                <th>Tanggal</th>
                <th>Laporan</th>
                <th>Perusahaan</th>
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
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
