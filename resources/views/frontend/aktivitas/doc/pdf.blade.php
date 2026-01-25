<!DOCTYPE html>
<html>
<head>
    <title>Data Pengiriman Doc</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h3 { text-align: center; margin-top: 20px; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 6px; vertical-align: top; text-align: left; }
        th { background-color: #eee; }
        td img { max-height: 70px; max-width: 100px; margin-right: 5px; display: inline-block; vertical-align: middle; }
        .box-detail { white-space: pre-line; } /* supaya enter di JSON muncul */
    </style>
</head>
<body>
    <h3>Data Pengiriman Doc</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Tgl Input</th>
                <th>Nama Satpam</th>
                <th>Jumlah Box</th>
                <th>Total Ekor</th>
                <th>Box Detail</th>
                <th>Ekspedisi</th>
                <th>Nama Supir</th>
                <th>Tujuan</th>
                <th>No Polisi</th>
                <th>Nomor Segel</th>
                <th>Catatan</th>
                <th>Perusahaan</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ date('d-m-Y', strtotime($row->tanggal)) }}</td>
                    <td>{{ $row->jam }}</td>
                    <td>{{ date('d-m-Y H:i', strtotime($row->input_date)) }}</td>
                    <td>{{ $row->satpam->name ?? '-' }}</td>
                    <td>{{ $row->jumlah }}</td>
                    <td>{{ $row->total_ekor }}</td>
                    <td class="box-detail">
                        @if(!empty($row->doc_box_option))
                            @php
                                $boxDetail = is_string($row->doc_box_option) ? json_decode($row->doc_box_option, true) : $row->doc_box_option;
                                if (!is_array($boxDetail)) $boxDetail = [];
                            @endphp
                            @foreach($boxDetail as $item)
                                - {{ $item['option_name'] ?? '-' }} : {{ $item['jumlah_box'] ?? 0 }} x {{ $item['isi'] ?? 0 }} = {{ $item['total_ekor'] ?? ($item['jumlah_box'] * $item['isi']) ?? 0 }}<br>
                            @endforeach
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $row->ekspedisi->name ?? '-' }}</td>
                    <td>{{ $row->nama_supir ?? '-' }}</td>
                    <td>{{ $row->tujuan ?? '-' }}</td>
                    <td>{{ $row->no_polisi ?? '-' }}</td>
                    <td>{{ $row->nomor_segel ?? '-' }}</td>
                    <td>{{ $row->note ?? '-' }}</td>
                    <td>{{ $row->company->company_name ?? '-' }}</td>
                    <td style="white-space: nowrap;">
                        @php
                            $fotos = [];
                            if($row->foto) {
                                if(Str::startsWith($row->foto, '[')) {
                                    $fotos = json_decode($row->foto, true);
                                } else {
                                    $fotos = [$row->foto];
                                }
                            }
                        @endphp
                        @foreach($fotos as $foto)
                            @if($foto && file_exists(public_path('storage/' . $foto)))
                                <img src="{{ public_path('storage/' . $foto) }}" alt="Foto Doc">
                            @endif
                        @endforeach
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
