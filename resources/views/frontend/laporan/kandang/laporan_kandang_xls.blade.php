@php
    $namaBulan = [
        '01' => 'Januari',
        '02' => 'Februari',
        '03' => 'Maret',
        '04' => 'April',
        '05' => 'Mei',
        '06' => 'Juni',
        '07' => 'Juli',
        '08' => 'Agustus',
        '09' => 'September',
        '10' => 'Oktober',
        '11' => 'November',
        '12' => 'Desember',
    ][$periode] ?? '-';

    $types = ['Suhu', 'Kipas', 'Alarm', 'Lampu'];
@endphp

<table>
    <thead>
        <tr>
            <th colspan="{{ $hari + 3 }}">
                <center>{{ $namaBulan }} {{ $tahun }}</center>
            </th>
        </tr>

        <tr>
            <th rowspan="2">KANDANG</th>
            <th rowspan="2">JAM</th>
            <th rowspan="2">JENIS</th>
            <th colspan="{{ $hari }}">TANGGAL</th>
        </tr>

        <tr>
            @for ($i = 1; $i <= $hari; $i++)
                <th>{{ $i }}</th>
            @endfor
        </tr>
    </thead>

    <tbody>
        @foreach ($jam as $jamItem)
            @foreach ($types as $idx => $type)
                <tr>
                    @if ($idx === 0)
                        <td rowspan="{{ count($types) }}">
                            {{ $kandang->name }}
                        </td>

                        <td rowspan="{{ count($types) }}">
                            {{ $jamItem }}
                        </td>
                    @endif

                    <td>{{ $type }}</td>

                    @for ($i = 1; $i <= $hari; $i++)
                        @php
                            // $periode tetap berupa angka, misalnya "07"
                            $tanggal = sprintf(
                                '%04d-%02d-%02d',
                                (int) $tahun,
                                (int) $periode,
                                $i
                            );

                            $value = $laporan[$tanggal][$jamItem][$type] ?? '-';

                            if ($value !== '-') {
                                if (in_array($type, ['Alarm', 'Lampu'])) {
                                    $value = (int) $value === 1 ? 'on' : 'off';
                                }

                                if ($type === 'Kipas') {
                                    $arr = array_map(
                                        'trim',
                                        explode(',', (string) $value)
                                    );

                                    $onCount = count(
                                        array_filter(
                                            $arr,
                                            fn ($v) => (int) $v === 1
                                        )
                                    );

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