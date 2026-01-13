<!DOCTYPE html>
<html>
<head>
    <title>Rekap Laporan Satpam</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h3 { text-align: center; margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; vertical-align: middle; }
        th { background-color: #eee; }
        img { max-width: 80px; max-height: 80px; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

    <!-- ABSEN -->
    <h3>Rekap Absensi Satpam</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Satpam</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($absen as $i => $row)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $row->satpam->name ?? '-' }}</td>
                <td>{{ date('d-m-Y', strtotime($row->tanggal)) }}</td>
                <td>{{ $row->jam_masuk ?? '-' }}</td>
                <td>{{ $row->jam_pulang ?? '-' }}</td>
                <td>
                    @if($row->foto)
                        <img src="{{ public_path('storage/'.$row->foto) }}">
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="page-break"></div>

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
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($suhu as $i => $row)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ date('d-m-Y', strtotime($row->tanggal)) }}</td>
                <td>{{ $row->jam }}</td>
                <td>{{ $row->kandang->name ?? '-' }}</td>
                <td>{{ $row->satpam->name ?? '-' }}</td>
                <td>{{ $row->temperature }}</td>
                <td>
                    @if($row->foto)
                        <img src="{{ public_path('storage/'.$row->foto) }}">
                    @endif
                </td>
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
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($kipas as $i => $row)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ date('d-m-Y', strtotime($row->tanggal)) }}</td>
                <td>{{ $row->jam }}</td>
                <td>{{ $row->kandang->name ?? '-' }}</td>
                <td>{{ $row->satpam->name ?? '-' }}</td>
                <td>{{ $row->kipas }}</td>
                <td>
                    @if($row->foto)
                        <img src="{{ public_path('storage/'.$row->foto) }}">
                    @endif
                </td>
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
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($alarm as $i => $row)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ date('d-m-Y', strtotime($row->tanggal)) }}</td>
                <td>{{ $row->jam }}</td>
                <td>{{ $row->kandang->name ?? '-' }}</td>
                <td>{{ $row->satpam->name ?? '-' }}</td>
                <td>{{ $row->is_alarm_on ? 'ON' : 'OFF' }}</td>
                <td>
                    @if($row->foto)
                        <img src="{{ public_path('storage/'.$row->foto) }}">
                    @endif
                </td>
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
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lampu as $i => $row)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ date('d-m-Y', strtotime($row->tanggal)) }}</td>
                <td>{{ $row->jam }}</td>
                <td>{{ $row->kandang->name ?? '-' }}</td>
                <td>{{ $row->satpam->name ?? '-' }}</td>
                <td>{{ $row->is_lamp_on ? 'ON' : 'OFF' }}</td>
                <td>
                    @if($row->foto)
                        <img src="{{ public_path('storage/'.$row->foto) }}">
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="page-break"></div>

    <!-- TAMU -->
    <h3>Laporan Tamu</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Tamu</th>
                <th>Jumlah</th>
                <th>Tujuan</th>
                <th>Status</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tamu as $i => $row)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $row->nama_tamu }}</td>
                <td>{{ $row->jumlah_tamu }}</td>
                <td>{{ $row->tujuan }}</td>
                <td>
                    @if($row->is_status == 1) Appointment
                    @elseif($row->is_status == 2) Masuk
                    @elseif($row->is_status == 3) Pulang
                    @endif
                </td>
                <td>
                    @if($row->foto)
                        <img src="{{ public_path('storage/'.$row->foto) }}">
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="page-break"></div>

    <!-- SITUASI -->
    <h3>Laporan Situasi</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Satpam</th>
                <th>Laporan</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($situasi as $i => $row)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $row->satpam->name ?? '-' }}</td>
                <td>{{ $row->laporan }}</td>
                <td>
                    @if($row->foto)
                        <img src="{{ public_path('storage/'.$row->foto) }}">
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="page-break"></div>

    <!-- BROADCAST -->
    <h3>Broadcast</h3>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Isi Broadcast</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($broadcast as $i => $row)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $row->message }}</td>
                <td>
                    @if($row->foto)
                        <img src="{{ public_path('storage/'.$row->foto) }}">
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
