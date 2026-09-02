<?php

namespace App\Http\Controllers\BOS;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use App\Models\Patroli;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BosPatroliController extends Controller
{
    public function index(Request $request)
    {
        $limit = (int) $request->query('limit', 20);

        $query = Patroli::with(['lokasi', 'satpam', 'company'])->where('comid', $request->comid);

        // 🔍 FILTER TANGGAL
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        // 🔍 FILTER STATUS (1 = masuk, 2 = pulang)
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        // 🔍 FILTER NAMA SATPAM
        if ($request->filled('satpam_id')) {
            $query->where('satpam_id', $request->satpam_id);
        }

        $data = $query->orderBy('tanggal', 'desc')->orderBy('jam', 'desc')->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function lokasi(Request $request)
    {
        $comid = $request->comid;

        $data = Lokasi::where('comid', $comid)->get();

        return response()->json([
            "success" => true,
            "data" => $data
        ]);
    }

    public function lokasiKunjungan(Request $request)
    {
        try {
            $comid = $request->comid;

            $startDateTime = $request->start_datetime;
            $endDateTime = $request->end_datetime;

            $limit = (int) $request->get('limit', 20);
            $limit = min($limit, 100);

            $data = Lokasi::query()
                ->where('lokasis.comid', $comid)
                ->where('lokasis.is_active', 1)

                ->leftJoin('patrolis', function ($join) use (
                    $comid,
                    $startDateTime,
                    $endDateTime
                ) {
                    $join->on(
                        'patrolis.location_id',
                        '=',
                        'lokasis.id'
                    )
                        ->where(
                            'patrolis.comid',
                            $comid
                        )
                        ->whereBetween(
                            DB::raw(
                                "TIMESTAMP(patrolis.tanggal, patrolis.jam)"
                            ),
                            [
                                $startDateTime,
                                $endDateTime,
                            ]
                        );
                })

                ->select(
                    'lokasis.id',
                    'lokasis.nama_lokasi',
                    'lokasis.qrcode',
                    'lokasis.latitude',
                    'lokasis.longitude',
                    DB::raw(
                        'COUNT(patrolis.id) as jumlah_kunjungan'
                    )
                )

                ->groupBy(
                    'lokasis.id',
                    'lokasis.nama_lokasi',
                    'lokasis.qrcode',
                    'lokasis.latitude',
                    'lokasis.longitude'
                )

                ->orderBy(
                    'lokasis.nama_lokasi'
                )

                ->paginate($limit);

            return response()->json([
                'success' => true,
                'message' => 'Data kunjungan lokasi berhasil diambil',
                'data' => $data,
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kunjungan lokasi',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function lokasiKunjunganDetail(Request $request)
    {
        $request->validate([
            'comid' => 'required',
            'location_id' => 'required',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date',
        ]);

        $data = Patroli::query()
            ->where('patrolis.comid', $request->comid)
            ->where('patrolis.location_id', $request->location_id)
            ->whereBetween(
                DB::raw("TIMESTAMP(patrolis.tanggal, patrolis.jam)"),
                [
                    $request->start_datetime,
                    $request->end_datetime
                ]
            )
            ->leftJoin(
                'satpams',
                'satpams.id',
                '=',
                'patrolis.satpam_id'
            )
            ->select(
                'patrolis.id',
                'patrolis.tanggal',
                'patrolis.jam',
                'patrolis.satpam_id',
                'satpams.name as nama_satpam',
                'patrolis.note',
                'patrolis.photo_path'
            )
            ->orderBy('patrolis.tanggal')
            ->orderBy('patrolis.jam')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }
}
