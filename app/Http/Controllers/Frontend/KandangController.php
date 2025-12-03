<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Kandang;
use App\Models\User;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class KandangController extends Controller
{
    use CommonTrait;
    public function kandang_table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $data = Kandang::where('comid', $comid);
            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
                    if ($row->is_active == 1) {
                        $button .= '<button onclick="activate(' . $row->id . ', 0)" title="Non Aktifkan" class="me-0 btn btn-insoft btn-danger"><i class="bi bi-x-lg"></i></button>';
                    } else {
                        $button .= '<button onclick="activate(' . $row->id . ', 1)" title="Aktifkan" class="me-0 btn btn-insoft btn-success"><i class="bi bi-check-circle"></i></button>';
                    }
                    $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '<button onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    $button .= '</center>';
                    return $button;
                })
                ->addColumn('is_empty', function ($row) {
                    return $row->is_empty === 1 ? '<center><span class="badge bg-danger rounded-pill">Kosong</span></center>' : '<center><span class="badge bg-success rounded-pill">Berisi</span></center>';
                })
                ->addColumn('is_active', function ($row) {
                    return $row->is_active === 1 ? '<center><span class="badge bg-success rounded-pill">Aktif</span></center>' : '<center><span class="badge bg-danger rounded-pill">Tidak</span></center>';
                })
                ->addColumn('comid', function ($row) {
                    return $row->company->company_name ?? '';
                })
                ->addColumn('pic', function ($row) {
                    return $row->pics->name ?? '';
                })
                ->rawColumns(['action', 'is_empty', 'is_active'])
                ->make(true);

            // bi bi-trash3
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'kandang';
        $users = User::where('company_id', $this->comid())->get();
        return view('frontend.farm.kandang.kandang', compact('view', 'users'));
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
            'code' => 'required',
            'name' => 'required|max:100|min:3',
            'std_temp' => 'required',
            'fan_amount' => 'required',
            'is_empty' => 'required',
            'pic' => 'required',
        ]);

        $paket = $this->what_paket($this->comid());
        $max = $paket['jumlah_farm'];

        $jumlah_kandang = Kandang::where('comid', $this->comid())->where('is_active', 1)->count();
        if ($jumlah_kandang >= $max) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah Kandang sudah melebihi quota paket anda, silahkan upgrade paket anda untuk menambah jumlah kandang !!',
            ]);
        }
        // Simpan ke database
        try {
            $input['comid'] = $this->comid();
            $input['name'] = strtoupper($request->name);
            $input['is_active'] = 1;
            Kandang::create($input);
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
        $data = Kandang::find($id);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();
        $validated = $request->validate([
            'code' => 'required',
            'name' => 'required|max:100|min:3',
            'std_temp' => 'required',
            'fan_amount' => 'required',
            'is_empty' => 'required',
            'pic' => 'required',
        ]);

        // Simpan ke database
        try {
            $input['comid'] = $this->comid();
            $input['name'] = strtoupper($request->name);
            $data = Kandang::find($id);
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
        return Kandang::destroy($id);
    }

    public function activate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:kandangs,id',
        ]);

        $data = Kandang::findOrFail($request->id);
        if ($data->is_active !== 1) {
            $paket = $this->what_paket($this->comid());
            $max = $paket['jumlah_farm'];

            $jumlah_kandang = Kandang::where('comid', $this->comid())->where('is_active', 1)->count();
            if ($jumlah_kandang >= $max) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah kandang sudah melebihi quota paket anda, silahkan upgrade paket anda untuk menambah jumlah kandang !!',
                ]);
            }
        }

        // toggle aktif/nonaktif
        $data->is_active = $data->is_active ? 0 : 1;
        $data->save();

        $message = $data->is_active ? 'Data berhasil diaktifkan.' : 'Data berhasil dinonaktifkan.';

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }
}
