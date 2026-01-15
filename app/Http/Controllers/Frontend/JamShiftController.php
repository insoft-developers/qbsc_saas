<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\JamShift;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JamShiftController extends Controller
{
    use CommonTrait;
    public function jam_shift_table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $data = JamShift::where('comid', $comid);
            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    $disabled = $this->isOwner() ? '' : 'disabled';
                    $button = '';
                    $button .= '<center>';
                    $button .= '<button ' . $disabled . ' onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '<button ' . $disabled . ' onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
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
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'jam-shift';
        $isOwner = $this->isOwner();
        return view('frontend.setting.shift.shift', compact('view', 'isOwner'));
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
        $input = $request->all();
        $validated = $request->validate([
            'name' => 'required|max:100|min:3',
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
        ]);

        // Simpan ke database
        try {
            $input['comid'] = $this->comid();
            $input['name'] = strtoupper($request->name);
            $input['jam_masuk_awal'] = $request->jam_masuk_awal ?? Carbon::createFromFormat('H:i:s', $request->jam_masuk)->subHour()->format('H:i:s');

            $input['jam_masuk_akhir'] = $request->jam_masuk_akhir ?? Carbon::createFromFormat('H:i:s', $request->jam_masuk)->addHour()->format('H:i:s');

            $input['jam_pulang_awal'] = $request->jam_pulang_awal ?? Carbon::createFromFormat('H:i:s', $request->jam_pulang)->subHour()->format('H:i:s');

            $input['jam_pulang_akhir'] = $request->jam_pulang_akhir ?? Carbon::createFromFormat('H:i:s', $request->jam_pulang)->addHours(2)->format('H:i:s');
            JamShift::create($input);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
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
        $data = JamShift::find($id);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();
        $validated = $request->validate([
            'name' => 'required|max:100|min:3',
            'jam_masuk' => 'required',
            'jam_pulang' => 'required',
        ]);

        // Simpan ke database
        try {
            $input['comid'] = $this->comid();
            $input['name'] = strtoupper($request->name);

            $input['jam_masuk_awal'] = $request->jam_masuk_awal ?? Carbon::createFromFormat('H:i:s', $request->jam_masuk)->subHour()->format('H:i:s');

            $input['jam_masuk_akhir'] = $request->jam_masuk_akhir ?? Carbon::createFromFormat('H:i:s', $request->jam_masuk)->addHour()->format('H:i:s');

            $input['jam_pulang_awal'] = $request->jam_pulang_awal ?? Carbon::createFromFormat('H:i:s', $request->jam_pulang)->subHour()->format('H:i:s');

            $input['jam_pulang_akhir'] = $request->jam_pulang_akhir ?? Carbon::createFromFormat('H:i:s', $request->jam_pulang)->addHours(2)->format('H:i:s');

            
            $data = JamShift::find($id);

            $data->update($input);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return JamShift::destroy($id);
    }
}
