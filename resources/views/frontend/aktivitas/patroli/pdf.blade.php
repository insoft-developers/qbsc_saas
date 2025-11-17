@php
    function jamDalamRangeLocal($jamKontrol, $jam_awal, $jam_akhir)
    {
        $awal = array_map('trim', explode(',', $jam_awal));
        $akhir = array_map('trim', explode(',', $jam_akhir));

        foreach ($awal as $i => $start) {
            if ($jamKontrol >= $start && $jamKontrol <= ($akhir[$i] ?? null)) {
                return true;
            }
        }
        return false;
    }
@endphp


<!DOCTYPE html>
<html>

<head>
    <title>Data Patroli Satpam</title>
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
    <h3 style="text-align:center;">Data Patroli Satpam</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Jam Patroli</th>
                <th>Lokasi</th>
                <th>Nama Satpam</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Catatan</th>
                <th>Sync Date</th>
                <th>Perusahaan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $row)
                @php
                    $awal = explode(',', $row->jam_awal);
                    $akhir = explode(',', $row->jam_akhir);
                    $html = '';
                    foreach ($awal as $index => $a) {
                        $html .= date('H:i', strtotime($a)) . '-' . date('H:i', strtotime($akhir[$index])) . ' ';
                    }

                    $range = jamDalamRangeLocal($row->jam, $row->jam_awal, $row->jam_akhir);

                @endphp

                <tr style="background:{{ $range ? 'white':'red' }};color:{{$range ? 'black':'white'}}">
                    <td>{{ $i + 1 }}</td>
                    <td>{{ date('d-m-Y', strtotime($row->tanggal)) }}</td>
                    <td>
                        {{ $row->jam }}
                    </td>

                    <td>{{ $html }}</td>
                    <td>{{ $row->lokasi->nama_lokasi ?? '' }}</td>
                    <td>{{ $row->satpam->name ?? '' }}</td>
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
