<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\JadwalPatroli;
use App\Models\JadwalPatroliDetail;
use App\Models\Lokasi;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class JadwalPatroliDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    use CommonTrait;
    public function table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $id = $request->jadwal_id;
            $data = JadwalPatroliDetail::where('comid', $comid)->where('patroli_id', $id);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('patroli_id', function ($row) {
                    return $row->jadwal->name ?? '';
                })
                ->addColumn('location_id', function ($row) {
                    return $row->location->nama_lokasi ?? '';
                })
                ->addColumn('jam_patroli', function ($row) {
                    return $row->jam_awal . ' - ' . $row->jam_akhir;
                })
                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
                    if (Auth::user()->level == 'owner') {
                        $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                        $button .= '<button onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    } else {
                        $button .= '<button disabled title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                        $button .= '<button disabled title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    }

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

    public function index() {}

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
        $input = $request->all();
        $validated = $request->validate([
            'patroli_id' => 'required',
            'location_id' => 'required',
            'urutan' => 'nullable',
            'jam_awal' => 'required',
            'jam_akhir' => 'required',
        ]);

        $lastUrutan = JadwalPatroliDetail::where('comid', $this->comid())->where('patroli_id', $request->patroli_id)->max('urutan');

        $urutan = $lastUrutan ? $lastUrutan + 1 : 1;

        $input['comid'] = $this->comid();
        $input['urutan'] = $request->urutan ?? $urutan;
        JadwalPatroliDetail::create($input);
        return response()->json([
            'success' => true,
            'message' => 'Success',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $view = 'jadwal-patroli-detail';
        $jadwal = JadwalPatroli::findorFail($id);
        if (!$jadwal) {
            return redirect()->back();
        }

        if ($jadwal->comid != $this->comid()) {
            return redirect()->back();
        }

        $locations = Lokasi::where('comid', $this->comid())->where('is_active', 1)->get();
        return view('frontend.setting.jadwal_patroli.detail.detail', compact('view', 'id', 'jadwal', 'locations'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = JadwalPatroliDetail::find($id);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();
        $validated = $request->validate([
            'patroli_id' => 'required',
            'location_id' => 'required',
            'urutan' => 'required',
            'jam_awal' => 'required',
            'jam_akhir' => 'required',
        ]);

        $input['comid'] = $this->comid();
        $data = JadwalPatroliDetail::find($id);
        $data->update($input);
        return response()->json([
            'success' => true,
            'message' => 'Success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        JadwalPatroliDetail::destroy($id);
        return response()->json([
            'success' => true,
            'message' => 'sucess',
        ]);
    }
}
