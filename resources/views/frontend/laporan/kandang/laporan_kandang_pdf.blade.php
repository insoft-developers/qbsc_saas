@php

    $periode = $data['periode'];

    if ($periode == '01') {
        $nama_bulan = 'Januari';
    } elseif ($periode == '02') {
        $nama_bulan = 'Februari';
    } elseif ($periode == '03') {
        $nama_bulan = 'Maret';
    } elseif ($periode == '04') {
        $nama_bulan = 'April';
    } elseif ($periode == '05') {
        $nama_bulan = 'Mei';
    } elseif ($periode == '06') {
        $nama_bulan = 'Juni';
    } elseif ($periode == '07') {
        $nama_bulan = 'Juli';
    } elseif ($periode == '08') {
        $nama_bulan = 'Agustus';
    } elseif ($periode == '09') {
        $nama_bulan = 'September';
    } elseif ($periode == '10') {
        $nama_bulan = 'Oktober';
    } elseif ($periode == '11') {
        $nama_bulan = 'November';
    } elseif ($periode == '12') {
        $nama_bulan = 'Desember';
    }

@endphp

<!DOCTYPE html>
<html>

<head>
    <title>Laporan Kandang</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            page-break-inside: auto;

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

        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
    </style>
</head>
<table>
    <thead>
        <tr>
            <th colspan="{{ $data['hari'] + 3 }}">
                <center>{{ $nama_bulan }} {{ $data['tahun'] }}</center>
            </th>
        </tr>
        <tr>
            <th rowspan="2">KANDANG</th>
            <th rowspan="2">JAM</th>
            <th rowspan="2">JENIS</th>
            <th colspan="{{ $data['hari'] }}">HARI</th>
        </tr>
        <tr>
            @for ($i = 1; $i <= $data['hari']; $i++)
                <th>{{ $i }}</th>
            @endfor
        </tr>
    </thead>

    <tbody>

        @php
            $types = ['Suhu', 'Kipas', 'Alarm', 'Lampu'];
        @endphp

        @foreach ($data['jam'] as $jamItem)
            @foreach ($types as $idx => $type)
                @php
                    if($type == 'Lampu') {
                        $border = 'border-bottom:4px solid red';
                    } else {
                        $border = '';
                    }
                @endphp
                <tr style="{{ $border }}">


                    <td>{{ $data['kandang']->name }}</td>
                    <td>{{ $jamItem }}</td>


                    <td>{{ $type }}</td>

                    @for ($i = 1; $i <= $data['hari']; $i++)
                        @php
                            $tanggal = sprintf('%s-%02d-%02d', $data['tahun'], $periode, $i);
                            $value = '-';

                            if (isset($data['laporan'][$tanggal][$jamItem][$type])) {
                                $value = $data['laporan'][$tanggal][$jamItem][$type];

                                if ($type == 'Alarm' || $type == 'Lampu') {
                                    $value = $value == 1 ? 'on' : 'off';
                                }

                                if ($type == 'Kipas') {
                                    $arr = explode(',', $value);
                                    $onCount = count(array_filter($arr, fn($v) => $v == 1));
                                    $value = $onCount . '/' . count($arr);
                                }
                            }
                        @endphp
                        <td>{{ $value }}</td>
                    @endfor
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>

</html>
