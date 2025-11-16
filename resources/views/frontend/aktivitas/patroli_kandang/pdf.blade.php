<!DOCTYPE html>
<html>

<head>
    <title>Data Patroli Kandang</title>
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
        }

        th {
            background-color: #eee;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>

    <!-- SUHU -->
    <h3>Data Patroli Suhu Kandang</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Kandang</th>
                <th>Nama Satpam</th>
                <th>Suhu</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Catatan</th>
                <th>Sync Date</th>
                <th>Perusahaan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($suhu as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ date('d-m-Y', strtotime($row->tanggal)) }}</td>
                    <td>{{ $row->jam }}</td>
                    <td>{{ $row->kandang->name ?? '' }}</td>
                    <td>{{ $row->satpam->name ?? '' }}</td>
                    <td>{{ $row->temperature }}</td>
                    <td>{{ $row->latitude }}</td>
                    <td>{{ $row->longitude }}</td>
                    <td>{{ $row->note }}</td>
                    <td>{{ date('d-m-Y H:i', strtotime($row->created_at)) }}</td>
                    <td>{{ $row->company->company_name ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- KIPAS -->
    <h3>Data Patroli Kipas Kandang</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Kandang</th>
                <th>Nama Satpam</th>
                <th>Kipas</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Catatan</th>
                <th>Sync Date</th>
                <th>Perusahaan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kipas as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ date('d-m-Y', strtotime($row->tanggal)) }}</td>
                    <td>{{ $row->jam }}</td>
                    <td>{{ $row->kandang->name ?? '' }}</td>
                    <td>{{ $row->satpam->name ?? '' }}</td>
                    <td>{{ $row->kipas }}</td>
                    <td>{{ $row->latitude }}</td>
                    <td>{{ $row->longitude }}</td>
                    <td>{{ $row->note }}</td>
                    <td>{{ date('d-m-Y H:i', strtotime($row->created_at)) }}</td>
                    <td>{{ $row->company->company_name ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- ALARM -->
    <h3>Data Patroli Alarm Kandang</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Kandang</th>
                <th>Nama Satpam</th>
                <th>Alarm</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Catatan</th>
                <th>Sync Date</th>
                <th>Perusahaan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($alarm as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ date('d-m-Y', strtotime($row->tanggal)) }}</td>
                    <td>{{ $row->jam }}</td>
                    <td>{{ $row->kandang->name ?? '' }}</td>
                    <td>{{ $row->satpam->name ?? '' }}</td>
                    <td>{{ $row->is_alarm_on == 1 ? 'ON':'OFF' }}</td>
                    <td>{{ $row->latitude }}</td>
                    <td>{{ $row->longitude }}</td>
                    <td>{{ $row->note }}</td>
                    <td>{{ date('d-m-Y H:i', strtotime($row->created_at)) }}</td>
                    <td>{{ $row->company->company_name ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <!-- LAMPU -->
    <h3>Data Patroli Lampu Kandang</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Kandang</th>
                <th>Nama Satpam</th>
                <th>Lampu</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Catatan</th>
                <th>Sync Date</th>
                <th>Perusahaan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lampu as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ date('d-m-Y', strtotime($row->tanggal)) }}</td>
                    <td>{{ $row->jam }}</td>
                    <td>{{ $row->kandang->name ?? '' }}</td>
                    <td>{{ $row->satpam->name ?? '' }}</td>
                    <td>{{ $row->is_lamp_on == 1 ? 'ON':'OFF' }}</td>
                    <td>{{ $row->latitude }}</td>
                    <td>{{ $row->longitude }}</td>
                    <td>{{ $row->note }}</td>
                    <td>{{ date('d-m-Y H:i', strtotime($row->created_at)) }}</td>
                    <td>{{ $row->company->company_name ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
