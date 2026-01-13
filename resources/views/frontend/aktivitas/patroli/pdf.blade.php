@php
    use Carbon\Carbon;

    function jamDalamRangeLocal($jamKontrol, $jam_awal, $jam_akhir)
    {
        if (!$jamKontrol || !$jam_awal || !$jam_akhir) {
            return false;
        }

        $kontrol = Carbon::parse(trim($jamKontrol));
        $awal = Carbon::parse(trim($jam_awal));
        $akhir = Carbon::parse(trim($jam_akhir));

        return $kontrol->between($awal, $akhir);
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
            font-size: 11px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            vertical-align: top;
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
            <th>Foto</th>
            <th>Sync Date</th>
            <th>Perusahaan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $i => $row)

            @php
                $range = jamDalamRangeLocal(
                    $row->jam,
                    $row->jam_awal_patroli,
                    $row->jam_akhir_patroli
                );
            @endphp

            <tr>
                <td style="color:{{$range ? 'black':'red'}}"  >{{ $i + 1 }}</td>
                <td style="color:{{$range ? 'black':'red'}}"  >{{ date('d-m-Y', strtotime($row->tanggal)) }}</td>
                <td style="color:{{$range ? 'black':'red'}}"  >{{ $row->jam }}</td>
                <td style="color:{{$range ? 'black':'red'}}"  >{{ $row->jam_awal_patroli }} - {{ $row->jam_akhir_patroli }}</td>
                <td style="color:{{$range ? 'black':'red'}}"  >{{ $row->lokasi->nama_lokasi ?? '' }}</td>
                <td style="color:{{$range ? 'black':'red'}}"  >{{ $row->satpam->name ?? '' }}</td>
                <td style="color:{{$range ? 'black':'red'}}"  >{{ $row->latitude }}</td>
                <td style="color:{{$range ? 'black':'red'}}"  >{{ $row->longitude }}</td>
                <td style="color:{{$range ? 'black':'red'}}"  >{{ $row->note }}</td>

                {{-- FOTO --}}
                <td style="text-align:center">
                    @if (!empty($row->photo_path) && file_exists(public_path('storage/'.$row->photo_path)))
                        <img src="{{ public_path('storage/'.$row->photo_path) }}">
                    @else
                        -
                    @endif
                </td>

                <td style="color:{{$range ? 'black':'red'}}"  >{{ date('d-m-Y H:i', strtotime($row->created_at)) }}</td>
                <td style="color:{{$range ? 'black':'red'}}"  >{{ $row->company->company_name ?? '' }}</td>
            </tr>

        @endforeach
    </tbody>
</table>

</body>
</html>
