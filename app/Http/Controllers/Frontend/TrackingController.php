<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\KandangAlarm;
use App\Models\KandangKipas;
use App\Models\KandangLampu;
use App\Models\KandangSuhu;
use App\Models\Patroli;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    use CommonTrait;
    public function table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $data = Absensi::where('comid', $comid)->where('status', 2)->orderBy('jam_masuk', 'desc');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jam_masuk', function ($row) {
                    return date('d-m-Y H:i:s', strtotime($row->jam_masuk));
                })
                ->addColumn('jam_pulang', function ($row) {
                    return date('d-m-Y H:i:s', strtotime($row->jam_keluar));
                })
                ->addColumn('nama_satpam', function ($row) {
                    return $row->satpam->name ?? '';
                })
                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
                    $button .= '<a href="' . url('tracking_map/' . $row->id) . '"><button title="Tracking Data" class="me-0 btn btn-insoft btn-success"><i class="bi bi-check-square"></i></button></a>';

                    $button .= '</center>';
                    return $button;
                })

                ->addColumn('comid', function ($row) {
                    return $row->company->company_name ?? '';
                })

                ->rawColumns(['action'])
                ->make(true);

            // bi bi-trash3
        }
    }

    public function index()
    {
        $view = 'tracking';
        return view('frontend.aktivitas.tracking.tracking', compact('view'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function map($id)
    {
        $view = 'map';

        $absensi = Absensi::findOrFail($id);

        $jamMasuk = Carbon::parse($absensi->jam_masuk);
        $jamPulang = Carbon::parse($absensi->jam_keluar);
        $satpamId = $absensi->satpam_id;

        $row_data = [];

        // ================= ABSEN MASUK =================
        $this->pushRow($row_data, $absensi->jam_masuk, $absensi->satpam->name ?? '', 'Absen Masuk', $absensi->latitude, $absensi->longitude);

        // ================= SUHU =================
        $suhu = KandangSuhu::where('satpam_id', $satpamId)
            ->whereRaw('TIMESTAMP(tanggal, jam) BETWEEN ? AND ?', [$jamMasuk, $jamPulang])
            ->orderByRaw('TIMESTAMP(tanggal, jam)')
            ->get();

        foreach ($suhu as $sh) {
            $this->pushRow($row_data, $sh->tanggal . ' ' . $sh->jam, $sh->satpam->name ?? '', 'Cek Suhu ' . ($sh->kandang->name ?? ''), $sh->latitude, $sh->longitude);
        }

        // ================= KIPAS =================
        $kipas = KandangKipas::where('satpam_id', $satpamId)
            ->whereRaw('TIMESTAMP(tanggal, jam) BETWEEN ? AND ?', [$jamMasuk, $jamPulang])
            ->orderByRaw('TIMESTAMP(tanggal, jam)')
            ->get();

        foreach ($kipas as $kp) {
            $this->pushRow($row_data, $kp->tanggal . ' ' . $kp->jam, $kp->satpam->name ?? '', 'Cek Kipas ' . ($kp->kandang->name ?? ''), $kp->latitude, $kp->longitude);
        }

        // ================= ALARM =================
        $alarm = KandangAlarm::where('satpam_id', $satpamId)
            ->whereRaw('TIMESTAMP(tanggal, jam) BETWEEN ? AND ?', [$jamMasuk, $jamPulang])
            ->orderByRaw('TIMESTAMP(tanggal, jam)')
            ->get();

        foreach ($alarm as $al) {
            $this->pushRow($row_data, $al->tanggal . ' ' . $al->jam, $al->satpam->name ?? '', 'Cek Alarm ' . ($al->kandang->name ?? ''), $al->latitude, $al->longitude);
        }

        // ================= LAMPU =================
        $lampu = KandangLampu::where('satpam_id', $satpamId)
            ->whereRaw('TIMESTAMP(tanggal, jam) BETWEEN ? AND ?', [$jamMasuk, $jamPulang])
            ->orderByRaw('TIMESTAMP(tanggal, jam)')
            ->get();

        foreach ($lampu as $lp) {
            $this->pushRow($row_data, $lp->tanggal . ' ' . $lp->jam, $lp->satpam->name ?? '', 'Cek Lampu ' . ($lp->kandang->name ?? ''), $lp->latitude, $lp->longitude);
        }

        // ================= PATROLI =================
        $patroli = Patroli::where('satpam_id', $satpamId)
            ->whereRaw('TIMESTAMP(tanggal, jam) BETWEEN ? AND ?', [$jamMasuk, $jamPulang])
            ->orderByRaw('TIMESTAMP(tanggal, jam)')
            ->get();

        foreach ($patroli as $pt) {
            $this->pushRow($row_data, $pt->tanggal . ' ' . $pt->jam, $pt->satpam->name ?? '', 'Patroli ' . ($pt->lokasi->nama_lokasi ?? ''), $pt->latitude, $pt->longitude);
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

        return view('frontend.aktivitas.tracking.map', compact('view', 'row_data'));
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
}
