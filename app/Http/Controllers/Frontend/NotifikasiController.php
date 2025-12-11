<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\JamShift;
use App\Models\Notifikasi;

use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class NotifikasiController extends Controller
{
    use CommonTrait;
    public function notifikasi_table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $data = Notifikasi::where('comid', $comid)->orWhere('comid', -1);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->addColumn('pesan', function ($row) {
                    return '<div style="white-space:normal;">' . Str::limit($row->pesan, 200, '...') . '</div>';
                })

                ->addColumn('is_read', function ($row) {
                    $isRead = explode(',', $row->is_read);
                    if (count($isRead) > 0) {
                        if (in_array(Auth::user()->id, $isRead)) {
                            return '<span class="badge bg-success">Read</span>';
                        } else {
                            return '';
                        }
                    } else {
                        return '';
                    }
                })

                ->addColumn('action', function ($row) {
                    $disabled = $this->isOwner() ? '' : 'disabled';
                    $button = '';
                    $button .= '<center>';
                    $button .= '<a href="' . url('notifikasi/' . $row->id) . '"><button  onclick="viewData(' . $row->id . ')" title="LIhat Selengkapnya" class="me-0 btn btn-insoft btn-info"><i class="bi bi-file"></i></button></a>';
                    $button .= '</center>';
                    return $button;
                })

                ->addColumn('comid', function ($row) {
                    return $row->company->company_name ?? '';
                })

                ->rawColumns(['action', 'pesan', 'is_read'])
                ->make(true);

            // bi bi-trash3
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'notifikasi';
        $isOwner = $this->isOwner();
        return view('frontend.notifikasi.notifikasi', compact('view', 'isOwner'));
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
        $data = Notifikasi::find($id);
        $comid = $this->comid();

        if ($data->comid == -1 || $data->comid == $comid) {
            $view = 'notifikasi-detail';
            
            // pecah string menjadi array angka
            $list = array_map('trim', explode(',', $data->is_read));

            // cek apakah $comid sudah ada di array
            if (!in_array($comid, $list)) {
                // tambahkan
                $list[] = $comid;

                // gabungkan kembali ke string
                $is_read = implode(',', $list);

                $data->is_read = $is_read;
                $data->save();
            }

            return view('frontend.notifikasi.notifikasi_detail', compact('data', 'view'));
        } else {
            abort(404, 'Halaman tidak ditemukan');
        }
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

    public function check()
    {
        $comid = $this->comid();

        $count = Cache::remember("notif_count_$comid", 5, function () use ($comid) {
            return Notifikasi::where(function ($q) use ($comid) {
                $q->where('comid', $comid)->orWhere('comid', -1);
            })
                ->where(function ($q) use ($comid) {
                    $q->whereNull('is_read')->orWhere(function ($sub) use ($comid) {
                        $sub->where('is_read', 'NOT LIKE', "$comid,%")
                            ->where('is_read', 'NOT LIKE', "%,$comid,%")
                            ->where('is_read', 'NOT LIKE', "%,$comid")
                            ->where('is_read', '!=', "$comid");
                    });
                })
                ->count();
        });

        return response()->json(['count' => $count]);
    }

    public function list()
    {
        $data = Notifikasi::where('comid', $this->comid())
            ->orWhere('comid', -1)
            ->orderBy('id', 'desc')
            ->limit(6)
            ->get();
        return $data;
    }

    
}
