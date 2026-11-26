<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AbsenLocation;
use App\Models\Absensi;
use App\Models\Broadcast;
use App\Models\Company;
use App\Models\CustomFeature;
use App\Models\DocBoxOption;
use App\Models\DocChick;
use App\Models\Ekspedisi;
use App\Models\EmergencyList;
use App\Models\JadwalPatroli;
use App\Models\JadwalPatroliDetail;
use App\Models\JamShift;
use App\Models\Kandang;
use App\Models\KandangAlarm;
use App\Models\KandangKipas;
use App\Models\KandangLampu;
use App\Models\KandangSuhu;
use App\Models\LaporanSituasi;
use App\Models\Lokasi;
use App\Models\Notifikasi;
use App\Models\PaketLangganan;
use App\Models\Patroli;
use App\Models\Pembelian;
use App\Models\RunningText;
use App\Models\Satpam;
use App\Models\Tamu;
use App\Models\User;
use App\Models\UserArea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AdminCompanyController extends Controller
{
    public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = Company::where('is_active', 1);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('is_active', function ($row) {
                    return $row->is_active === 1 ? '<center><span class="badge bg-success rounded-pill">Aktif</span></center>' : '<center><span class="badge bg-danger rounded-pill">Tidak</span></center>';
                })

                ->addColumn('paket', function($row){
                    return optional($row->paket)->nama_paket ?? '';
                })

                 ->addColumn('expired_date', function($row){
                    return $row->expired_date ? date('d-m-Y', strtotime($row->expired_date)) : '';
                })

                
                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';

                    $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                    // $button .= '<button onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';

                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action', 'is_active'])
                ->make(true);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'company';
        $company = Company::all();
        return view('admin.company.index', compact('view', 'company'));
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
    public function store(Request $request) {}

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
        return Company::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = Company::find($id);
        $data->timezone = $request->timezone;
        $data->save();
        

        return response()->json([
            'success' => true,
            'message' => 'Sukses',
        ]);
    }

   
}
