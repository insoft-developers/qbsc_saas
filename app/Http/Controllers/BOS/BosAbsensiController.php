<?php

namespace App\Http\Controllers\BOS;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\JamShift;
use App\Models\Satpam;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BosAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $limit = (int) $request->query('limit', 20);

        $query = Absensi::with(['satpam', 'company'])->where('comid', $request->comid);

        // 🔍 FILTER TANGGAL
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        // 🔍 FILTER STATUS (1 = masuk, 2 = pulang)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 🔍 FILTER NAMA SATPAM
        if ($request->filled('satpam_id')) {
            $query->where('satpam_id', $request->satpam_id);
        }

        $data = $query->orderBy('tanggal', 'desc')->orderBy('id', 'desc')->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function satpam(Request $request)
    {
        $comid = $request->comid;

        $data = Satpam::where('comid', $comid)->get();

        return response()->json([
            "success" => true,
            "data" => $data
        ]);
    }

    public function lupaPulang(Request $request)
    {
        $input = $request->all();

        $absensi = Absensi::find($input['id']);

        if (!$absensi) {
            return response()->json([
                'success' => false,
                'message' => 'Data absensi tidak ditemukan..!',
            ]);
        }

        if ($absensi->shift_id == null) {
            return response()->json([
                'success' => false,
                'message' => 'Shift Kerja tidak ada..!',
            ]);
        }

        $shift = JamShift::find($absensi->shift_id);

        if (!$shift) {
            return response()->json([
                'success' => false,
                'message' => 'Shift Kerja tidak ditemukan..!',
            ]);
        }

        // Ambil timezone perusahaan
        $timezone = $absensi->company?->timezone ?? 'Asia/Jakarta';

        // Tanggal absensi dianggap sebagai tanggal lokal perusahaan
        $tanggalAbsen = Carbon::parse(
            $absensi->tanggal,
            $timezone
        );

        // Jam pulang adalah jam lokal perusahaan
        $jamPulangAkhir = Carbon::createFromFormat(
            'H:i:s',
            $shift->jam_pulang_akhir,
            $timezone
        )->setDate(
            $tanggalAbsen->year,
            $tanggalAbsen->month,
            $tanggalAbsen->day
        );

        // Jika jam pulang melewati tengah malam
        if ($shift->jam_pulang_akhir < $shift->jam_masuk) {
            $jamPulangAkhir->addDay();
        }

        $absensi->jam_keluar = $jamPulangAkhir->format('Y-m-d H:i:s');
        $absensi->catatan_keluar = 'tidak-absen-pulang';
        $absensi->status = 2;

        // updated_at adalah waktu server/aplikasi
        $absensi->updated_at = Carbon::now();

        $absensi->save();

        return response()->json([
            'success' => true,
            'message' => 'Success',
        ]);
    }
}
