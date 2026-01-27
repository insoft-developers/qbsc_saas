<?php

namespace App\Http\Controllers\Frontend;

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
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LiveTrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    use CommonTrait;
    public function table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $data = Satpam::with([
                'absensi' => function ($query) {
                    $query->where('status', 1);
                },
            ])

                ->whereHas('absensi', function ($q) {
                    $q->where('status', 1);
                })
                ->where('comid', $comid);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jam_masuk', function ($row) {
                    return $row->absensi[0]->jam_masuk ?? '';
                })
                ->addColumn('shift_name', function ($row) {
                    return $row->absensi[0]->shift_name ?? '';
                })
                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
                    $button .= '<a href="' . url('live_map/' . $row->id) . '"><button title="Tracking Data" class="me-0 btn btn-insoft btn-success"><i class="bi bi-check-square"></i></button></a>';

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
        return view('frontend.aktivitas.live.live_tracking', compact('view'));
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

    public function live_map()
    {
        $comid = $this->comid();
        $satpams = Satpam::with([
            'absensi' => function ($query) {
                $query->where('status', 1);
            },
        ])
        ->whereHas('absensi', function ($q) {
                    $q->where('status', 1);
                })
        ->where('comid', $comid)
        ->get();

        $view = 'live-map';

        $patroli = Lokasi::select('id', 'nama_lokasi', 'latitude', 'longitude')->get();

       

        // return $row_data;
        return view('frontend.aktivitas.live.map', compact('view', 'satpams','patroli'));
    }

    public function update_location()
    {
        $comid = $this->comid();
        $satpams = Satpam::with([
            'absensi' => function ($query) {
                $query->where('status', 1);
            },
        ])
        ->select('id','name','last_latitude', 'last_longitude')
        ->whereHas('absensi', function ($q) {
                    $q->where('status', 1);
                })
        ->where('comid', $comid)
        ->get();
        return response()->json($satpams);
    }


    
}
