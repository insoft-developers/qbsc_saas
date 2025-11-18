<!DOCTYPE html>
<html>

<head>
    <title>Data Pengiriman Doc</title>
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
    <h3 style="text-align:center;">Data Pengiriman Doc</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Tgl Input</th>
                <th>Nama Satpam</th>
                <th>Jumlah Box</th>
                <th>Ekspedisi</th>
                <th>Tujuan</th>
                <th>No Polisi</th>
                <th>Jenis</th>
                <th>Catatan</th>
                <th>Perusahaan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ date('d-m-Y', strtotime($row->tanggal)) }}</td>
                    <td>{{ $row->jam }}</td>
                    <td>{{ date('d-m-Y H:i', strtotime($row->input_date)) }}</td>
                    
                    <td>{{ $row->satpam->name ?? '' }}</td>
                    <td>{{ $row->jumlah }}</td>
                    <td>{{ $row->ekspedisi->name ?? '' }}</td>
                    <td>{{ $row->tujuan }}</td>
                    <td>{{ $row->no_polisi }}</td>
                    <td>{{ $row->jenis == 1 ? 'Male' : 'Female' }}</td>
                    <td>{{ $row->note }}</td>
                    <td>{{ $row->company->company_name ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
