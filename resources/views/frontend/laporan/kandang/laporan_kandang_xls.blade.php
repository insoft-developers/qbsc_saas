@php
    if($periode == '01') {
        $periode = 'Januari';
    }
    else if($periode == '02') {
        $periode = 'Februari';
    }
    else if($periode == '03') {
        $periode = 'Maret';
    }
    else if($periode == '04') {
        $periode = 'April';
    }
    else if($periode == '05') {
        $periode = 'Mei';
    }
    else if($periode == '06') {
        $periode = 'Juni';
    }
    else if($periode == '07') {
        $periode = 'Juli';
    }
    else if($periode == '08') {
        $periode = 'Agustus';
    }
    else if($periode == '09') {
        $periode = 'September';
    }
    else if($periode == '10') {
        $periode = 'Oktober';
    }
    else if($periode == '11') {
        $periode = 'November';
    }
    else if($periode == '12') {
        $periode = 'Desember';
    }


@endphp
<table>
    <thead>
        <tr>
            <th colspan="{{ $hari + 3 }}"><center>{{ $periode }} {{ $tahun }}</center></th>
        </tr>
        <tr>
            <th rowspan="2">KANDANG</th>
            <th rowspan="2">JAM</th>
            <th rowspan="2">JENIS</th>
            <th colspan="{{ $hari }}">HARI</th>
        </tr>
        <tr>
            @for ($i = 1; $i <= $hari; $i++)
                <th>{{ $i }}</th>
            @endfor
        </tr>
    </thead>

    <tbody>

        @php
        $types = ['Suhu','Kipas','Alarm','Lampu'];
        @endphp

        @foreach ($jam as $jamItem)
            @foreach ($types as $idx => $type)
                <tr>
                    @if ($idx == 0)
                        <td rowspan="4">{{ $kandang->name }}</td>
                        <td rowspan="4">{{ $jamItem }}</td>
                    @endif

                    <td>{{ $type }}</td>

                    @for ($i = 1; $i <= $hari; $i++)
                        @php
                            $tanggal = sprintf("%s-%02d-%02d", $tahun, $periode, $i);
                            $value = "-";

                            if(isset($laporan[$tanggal][$jamItem][$type])) {
                                $value = $laporan[$tanggal][$jamItem][$type];

                                if ($type == "Alarm" || $type == "Lampu") {
                                    $value = $value == 1 ? "on" : "off";
                                }

                                if ($type == "Kipas") {
                                    $arr = explode(",", $value);
                                    $onCount = count(array_filter($arr, fn($v) => $v == 1));
                                    $value = $onCount . "/" . count($arr);
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
