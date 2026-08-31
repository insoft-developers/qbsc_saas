<?php

namespace App\Http\Controllers\BOS;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\KandangAlarm;
use App\Models\KandangKipas;
use App\Models\KandangLampu;
use App\Models\KandangSuhu;
use App\Models\Lokasi;
use App\Models\Patroli;
use App\Models\Satpam;
use App\Models\SatpamLocation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BosTrackingController extends Controller
{
    public function index(Request $request)
    {
        $limit = (int) $request->query('limit', 20);

        $query = $data = Absensi::with('satpam:id,name', 'company:id,company_name')->where('comid', $request->comid)->where('status', 2);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        // 🔍 FILTER NAMA SATPAM
        if ($request->filled('satpam_id')) {
            $query->where('satpam_id', $request->satpam_id);
        }

        $data = $query->orderBy('jam_masuk', 'desc')->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function map(Request $request)
    {

        $comid = $request->comid;
        $id = $request->id;
        $absensi = Absensi::findOrFail($id);

        $jamMasuk = Carbon::parse($absensi->jam_masuk);
        $jamPulang = Carbon::parse($absensi->jam_keluar);
        $satpamId = $absensi->satpam_id;

        $row_data = [];

        // ================= ABSEN MASUK =================
        $this->pushRow($row_data, $absensi->jam_masuk, $absensi->satpam->name ?? '', 'Absen Masuk', $absensi->latitude, $absensi->longitude);

        // ================= SUHU =================
        $suhu = KandangSuhu::where('satpam_id', $satpamId)
            ->where('comid', $comid)
            ->whereRaw('TIMESTAMP(tanggal, jam) BETWEEN ? AND ?', [$jamMasuk, $jamPulang])
            ->orderByRaw('TIMESTAMP(tanggal, jam)')
            ->get();

        foreach ($suhu as $sh) {
            $this->pushRow($row_data, $sh->tanggal . ' ' . $sh->jam, $sh->satpam->name ?? '', 'Cek Suhu ' . ($sh->kandang->name ?? ''), $sh->latitude, $sh->longitude);
        }

        // ================= KIPAS =================
        $kipas = KandangKipas::where('satpam_id', $satpamId)
            ->where('comid', $comid)
            ->whereRaw('TIMESTAMP(tanggal, jam) BETWEEN ? AND ?', [$jamMasuk, $jamPulang])
            ->orderByRaw('TIMESTAMP(tanggal, jam)')
            ->get();

        foreach ($kipas as $kp) {
            $this->pushRow($row_data, $kp->tanggal . ' ' . $kp->jam, $kp->satpam->name ?? '', 'Cek Kipas ' . ($kp->kandang->name ?? ''), $kp->latitude, $kp->longitude);
        }

        // ================= ALARM =================
        $alarm = KandangAlarm::where('satpam_id', $satpamId)
            ->where('comid', $comid)
            ->whereRaw('TIMESTAMP(tanggal, jam) BETWEEN ? AND ?', [$jamMasuk, $jamPulang])
            ->orderByRaw('TIMESTAMP(tanggal, jam)')
            ->get();

        foreach ($alarm as $al) {
            $this->pushRow($row_data, $al->tanggal . ' ' . $al->jam, $al->satpam->name ?? '', 'Cek Alarm ' . ($al->kandang->name ?? ''), $al->latitude, $al->longitude);
        }

        // ================= LAMPU =================
        $lampu = KandangLampu::where('satpam_id', $satpamId)
            ->where('comid', $comid)
            ->whereRaw('TIMESTAMP(tanggal, jam) BETWEEN ? AND ?', [$jamMasuk, $jamPulang])
            ->orderByRaw('TIMESTAMP(tanggal, jam)')
            ->get();

        foreach ($lampu as $lp) {
            $this->pushRow($row_data, $lp->tanggal . ' ' . $lp->jam, $lp->satpam->name ?? '', 'Cek Lampu ' . ($lp->kandang->name ?? ''), $lp->latitude, $lp->longitude);
        }

        // ================= PATROLI =================
        $patroli = Patroli::where('satpam_id', $satpamId)
            ->where('comid', $comid)
            ->whereRaw('TIMESTAMP(tanggal, jam) BETWEEN ? AND ?', [$jamMasuk, $jamPulang])
            ->orderByRaw('TIMESTAMP(tanggal, jam)')
            ->get();

        foreach ($patroli as $pt) {
            $this->pushRow($row_data, $pt->tanggal . ' ' . $pt->jam, $pt->satpam->name ?? '', 'Patroli ' . ($pt->lokasi->nama_lokasi ?? ''), $pt->latitude, $pt->longitude);
        }

        $satcol = SatpamLocation::where('satpam_id', $satpamId)
            ->where('accuracy', '<', 50)
            ->whereBetween('recorded_at', [$jamMasuk->toDateTimeString(), $jamPulang->toDateTimeString()])
            ->orderBy('recorded_at', 'asc')
            ->get();

        foreach ($satcol as $sc) {
            $this->pushRow($row_data, $sc->recorded_at, $sc->satpam->name ?? '', 'Walking ', $sc->latitude, $sc->longitude);
        }

        // ================= ABSEN PULANG (FORCE) =================
        $this->pushRow(
            $row_data,
            $absensi->jam_keluar,
            $absensi->satpam->name ?? '',
            'Absen Pulang',
            $absensi->latitude2 ?? $absensi->latitude,
            $absensi->longitude2 ?? $absensi->longitude,
            true, // <-- FORCE MASUK WALAU NULL
        );

        // ================= SORT =================
        $row_data = collect($row_data)->sortBy(fn($i) => Carbon::parse($i['tanggal']))->values()->toArray();

        // return $row_data;
        return response()->json([
            "success" => true,
            "data" => $row_data
        ]);
    }

    private function pushRow(&$rows, $tanggal, $satpam, $keterangan, $lat, $lng, $force = false)
    {
        if (!$force) {
            if (is_null($lat) || is_null($lng)) {
                return;
            }
        }

        $rows[] = [
            'tanggal' => $tanggal,
            'satpam_name' => $satpam,
            'keterangan' => $keterangan,
            'latitude' => $lat,
            'longitude' => $lng,
        ];
    }


    public function live_tracking(Request $request)
    {
        $comid = $request->comid;

        // ==========================================
        // SATPAM AKTIF + SEDANG ABSEN
        // ==========================================
        $satpams = Satpam::query()
            ->select([
                'id',
                'name',
                'last_latitude',
                'last_longitude',
                'last_seen_at',
            ])
            ->where('comid', $comid)
            ->where('is_active', 1)
            ->whereHas('absensi', function ($query) {
                $query->where('status', 1);
            })
            ->whereNotNull('last_latitude')
            ->whereNotNull('last_longitude')
            ->get();

        // ==========================================
        // LOKASI PATROLI
        // ==========================================
        $patrolis = Lokasi::query()
            ->select([
                'id',
                'nama_lokasi',
                'latitude',
                'longitude',
            ])
            ->where('comid', $comid)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $data = [];

        // ==========================================
        // SATPAM
        // ==========================================
        foreach ($satpams as $satpam) {
            $data[] = [
                'id' => $satpam->id,
                'type' => 'satpam',
                'name' => $satpam->name,
                'latitude' => (float) $satpam->last_latitude,
                'longitude' => (float) $satpam->last_longitude,
                'last_seen_at' => $satpam->last_seen_at,
            ];
        }

        // ==========================================
        // LOKASI PATROLI
        // ==========================================
        foreach ($patrolis as $patroli) {
            $data[] = [
                'id' => $patroli->id,
                'type' => 'patroli',
                'name' => $patroli->nama_lokasi,
                'latitude' => (float) $patroli->latitude,
                'longitude' => (float) $patroli->longitude,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
