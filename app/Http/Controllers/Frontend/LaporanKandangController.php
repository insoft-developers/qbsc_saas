<?php

namespace App\Http\Controllers\Frontend;

use App\Exports\LaporanKandangExport;
use App\Http\Controllers\Controller;
use App\Models\Kandang;
use App\Traits\CommonTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class LaporanKandangController extends Controller
{
    use CommonTrait;
    public function index()
    {
        $view = 'laporan-kandang';
        $kandangs = Kandang::where('comid', $this->comid())->get();
        return view('frontend.laporan.kandang.laporan_kandang', compact('view', 'kandangs'));
    }

    public function tampilkan_laporan(Request $request)
    {
        $input = $request->all();
        $kandang_id = $input['kandang'];

        // ========================
        //   SETUP RANGE TANGGAL
        // ========================
        $bulan = $input['tahun'] . '-' . $input['periode'];
        $daysInMonth = Carbon::parse($bulan . '-01')->daysInMonth;

        // Jam yang ingin ditampilkan
        $jamList = ['00:00:00', '01:00:00', '02:00:00', '03:00:00', '04:00:00', '05:00:00', '06:00:00', '07:00:00', '08:00:00', '09:00:00', '10:00:00', '11:00:00', '12:00:00', '13:00:00', '14:00:00', '15:00:00', '16:00:00', '17:00:00', '18:00:00', '19:00:00', '20:00:00', '21:00:00', '22:00:00', '23:00:00'];
        $comid = $this->comid();
        
        $laporan = $this->data_laporan($input, $comid, $kandang_id);

        // ========================
        //   RESPONSE
        // ========================
        $data = [
            'hari' => $daysInMonth,
            'jam' => $jamList,
            'kandang' => DB::table('kandangs')->find($kandang_id),
            'laporan' => $laporan,
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function export_xls(Request $request)
    {
        $input = $request->all();

        $bulan = $input['tahun'] . '-' . $input['periode'];
        $daysInMonth = Carbon::parse($bulan . '-01')->daysInMonth;

        $jamList = ['00:00:00', '01:00:00', '02:00:00', '03:00:00', '04:00:00', '05:00:00', '06:30:00', '16:00:00', '17:00:00', '18:00:00', '19:00:00', '20:00:00', '21:00:00', '22:00:00', '23:00:00'];

        $kandang = Kandang::find($input['kandang']);
        $kandang_id = $kandang->id;
        $comid = $this->comid();
        // === Ambil data laporan (pakai logika backend yang sudah kamu buat) ===
        $laporan = $this->data_laporan($input, $comid, $kandang_id);

        $data = [
            'hari' => $daysInMonth,
            'jam' => $jamList,
            'kandang' => $kandang,
            'laporan' => $laporan,
            'periode' => $input['periode'],
            'tahun' => $input['tahun'],
        ];

        return Excel::download(new LaporanKandangExport($data), 'laporan-kandang.xlsx');
    }


    public function export_pdf(Request $request)
    {
        $input = $request->all();

        $bulan = $input['tahun'] . '-' . $input['periode'];
        $daysInMonth = Carbon::parse($bulan . '-01')->daysInMonth;

        $jamList = ['00:00:00', '01:00:00', '02:00:00', '03:00:00', '04:00:00', '05:00:00', '06:30:00', '16:00:00', '17:00:00', '18:00:00', '19:00:00', '20:00:00', '21:00:00', '22:00:00', '23:00:00'];

        $kandang = Kandang::find($input['kandang']);
        $kandang_id = $kandang->id;
        $comid = $this->comid();
        // === Ambil data laporan (pakai logika backend yang sudah kamu buat) ===
        $laporan = $this->data_laporan($input, $comid, $kandang_id);

        $data = [
            'hari' => $daysInMonth,
            'jam' => $jamList,
            'kandang' => $kandang,
            'laporan' => $laporan,
            'periode' => $input['periode'],
            'tahun' => $input['tahun'],
        ];

        $pdf = Pdf::loadView('frontend.laporan.kandang.laporan_kandang_pdf', compact('data'))->setPaper('a4', 'landscape');

        return $pdf->stream('laporan-kandang.pdf');
    }

    protected function data_laporan($input, $comid, $kandang_id) {
        // ========================
        //   GET DATA RAW
        // ========================
        $suhu = DB::table('kandang_suhus')->where('comid', $comid)->where('kandang_id', $kandang_id)->whereMonth('tanggal', $input['periode'])->whereYear('tanggal', $input['tahun'])->get();

        $kipas = DB::table('kandang_kipas')->where('comid', $comid)->where('kandang_id', $kandang_id)->whereMonth('tanggal', $input['periode'])->whereYear('tanggal', $input['tahun'])->get();

        $alarm = DB::table('kandang_alarms')->where('comid', $comid)->where('kandang_id', $kandang_id)->whereMonth('tanggal', $input['periode'])->whereYear('tanggal', $input['tahun'])->get();

        $lampu = DB::table('kandang_lampus')->where('comid', $comid)->where('kandang_id', $kandang_id)->whereMonth('tanggal', $input['periode'])->whereYear('tanggal', $input['tahun'])->get();

        // ========================
        //   STRUKTUR LAPORAN
        // ========================
        $laporan = [];

        // Helper: ubah 02:10:00 → 02:00:00
        function hourKey($time)
        {
            return Carbon::parse($time)->format('H:00:00');
        }

        // Helper: cek apakah data terbaru dalam jam itu
        function isLatest(&$laporan, $tgl, $jam, $actualTime)
        {
            if (!isset($laporan[$tgl][$jam]['__time'])) {
                return true;
            }
            return strtotime($actualTime) > strtotime($laporan[$tgl][$jam]['__time']);
        }

        // ========================
        //   SUHU
        // ========================
        foreach ($suhu as $row) {
            $tgl = Carbon::parse($row->tanggal)->format('Y-m-d');
            $jam = hourKey($row->jam);

            if (isLatest($laporan, $tgl, $jam, $row->jam)) {
                $laporan[$tgl][$jam]['Suhu'] = $row->temperature;
                $laporan[$tgl][$jam]['__time'] = $row->jam;
            }
        }

        // ========================
        //   KIPAS
        // ========================
        foreach ($kipas as $row) {
            $tgl = Carbon::parse($row->tanggal)->format('Y-m-d');
            $jam = hourKey($row->jam);

            if (isLatest($laporan, $tgl, $jam, $row->jam)) {
                $laporan[$tgl][$jam]['Kipas'] = $row->kipas;
                $laporan[$tgl][$jam]['__time'] = $row->jam;
            }
        }

        // ========================
        //   ALARM
        // ========================
        foreach ($alarm as $row) {
            $tgl = Carbon::parse($row->tanggal)->format('Y-m-d');
            $jam = hourKey($row->jam);

            if (isLatest($laporan, $tgl, $jam, $row->jam)) {
                $laporan[$tgl][$jam]['Alarm'] = $row->is_alarm_on;
                $laporan[$tgl][$jam]['__time'] = $row->jam;
            }
        }

        // ========================
        //   LAMPU
        // ========================
        foreach ($lampu as $row) {
            $tgl = Carbon::parse($row->tanggal)->format('Y-m-d');
            $jam = hourKey($row->jam);

            if (isLatest($laporan, $tgl, $jam, $row->jam)) {
                $laporan[$tgl][$jam]['Lampu'] = $row->is_lamp_on;
                $laporan[$tgl][$jam]['__time'] = $row->jam;
            }
        }

        // ========================
        //   HAPUS FIELD INTERNAL __time
        // ========================
        foreach ($laporan as $tgl => $jamItems) {
            foreach ($jamItems as $jam => $dt) {
                unset($laporan[$tgl][$jam]['__time']);
            }
        }

        return $laporan;
    }
}
