<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Kandang;
use App\Models\KandangAlarm;
use App\Models\KandangKipas;
use App\Models\KandangLampu;
use App\Models\KandangSuhu;
use App\Models\Satpam;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PatroliKandangController extends Controller
{
    use CommonTrait;

    public function kandang_suhu_table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $query = KandangSuhu::where('comid', $comid)->with(['satpam:id,name', 'company:id,company_name', 'kandang:id,name']);
            if ($request->start_date && $request->end_date) {
                $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            }

            if ($request->satpam_id) {
                $query->where('satpam_id', $request->satpam_id);
            }
            if ($request->kandang_id) {
                $query->where('kandang_id', $request->kandang_id);
            }

            $query->orderBy('tanggal','desc')->orderBy('jam','desc');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('tanggal', function ($row) {
                    return '<div style="text-align:center">' . date('d-m-Y', strtotime($row->tanggal)) . '</div>';
                })
                ->addColumn('satpam_id', function ($row) {
                    return $row->satpam->name ?? '';
                })
                ->addColumn('comid', function ($row) {
                    return $row->company->company_name ?? '';
                })
                ->addColumn('kandang_id', function ($row) {
                    return $row->kandang->name ?? '';
                })
                ->addColumn('latitude', function ($row) {
                    if ($row->latitude && $row->longitude) {
                        $url = "https://www.google.com/maps/@{$row->latitude},{$row->longitude},21z";
                        return '
            <div style="text-align:center">
                <a href="' .
                            $url .
                            '" target="_blank" class="text-primary fw-bold">
                    ' .
                            $row->latitude .
                            ' , ' .
                            $row->longitude .
                            '
                </a>
            </div>';
                    } else {
                        return '<div style="text-align:center">-</div>';
                    }
                })
                ->addColumn('foto', function ($row) {
                    if (!empty($row->foto)) {
                        $url = asset('storage/' . $row->foto);
                        return '<a href="' . asset('storage/' . $row->foto) . '" target="_blank"><img  style="cursor:pointer;" src="' . $url . '" alt="Foto" width="50" height="50" class="rounded-circle border"></a>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })

                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
                    // $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '<button onclick="deleteData(' . $row->id . ', 1)" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action', 'latitude', 'tanggal', 'foto'])
                ->make(true);

            // bi bi-trash3
        }
    }



    public function kandang_kipas_table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $query = KandangKipas::where('comid', $comid)->with(['satpam:id,name', 'company:id,company_name', 'kandang:id,name']);
            if ($request->start_date && $request->end_date) {
                $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            }

            if ($request->satpam_id) {
                $query->where('satpam_id', $request->satpam_id);
            }
            if ($request->kandang_id) {
                $query->where('kandang_id', $request->kandang_id);
            }

            $query->orderBy('tanggal','desc')->orderBy('jam','desc');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('tanggal', function ($row) {
                    return '<div style="text-align:center">' . date('d-m-Y', strtotime($row->tanggal)) . '</div>';
                })
                ->addColumn('satpam_id', function ($row) {
                    return $row->satpam->name ?? '';
                })
                ->addColumn('comid', function ($row) {
                    return $row->company->company_name ?? '';
                })
                ->addColumn('kandang_id', function ($row) {
                    return $row->kandang->name ?? '';
                })
                ->addColumn('kipas', function($row){
                    $arr_kipas = explode(",", $row->kipas);
                    $html = '';
                    foreach($arr_kipas as $index => $k) {
                        $kipas = "K".$index+1;
                        $ks = $k==1?'ON':'OFF';
                        $html .= $kipas.':'.$ks.', ';
                    }
                    return '<div style="white-space:normal;width:200px;">'.$html.'</div>';
                })
                ->addColumn('latitude', function ($row) {
                    if ($row->latitude && $row->longitude) {
                        $url = "https://www.google.com/maps/@{$row->latitude},{$row->longitude},21z";
                        return '
            <div style="text-align:center">
                <a href="' .
                            $url .
                            '" target="_blank" class="text-primary fw-bold">
                    ' .
                            $row->latitude .
                            ' , ' .
                            $row->longitude .
                            '
                </a>
            </div>';
                    } else {
                        return '<div style="text-align:center">-</div>';
                    }
                })
                ->addColumn('foto', function ($row) {
                    if (!empty($row->foto)) {
                        $url = asset('storage/' . $row->foto);
                        return '<a href="' . asset('storage/' . $row->foto) . '" target="_blank"><img  style="cursor:pointer;" src="' . $url . '" alt="Foto" width="50" height="50" class="rounded-circle border"></a>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })

                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
                    // $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '<button onclick="deleteData(' . $row->id . ', 2)" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action', 'latitude', 'tanggal', 'foto','kipas'])
                ->make(true);

            // bi bi-trash3
        }
    }


    public function kandang_alarm_table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $query = KandangAlarm::where('comid', $comid)->with(['satpam:id,name', 'company:id,company_name', 'kandang:id,name']);
            if ($request->start_date && $request->end_date) {
                $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            }

            if ($request->satpam_id) {
                $query->where('satpam_id', $request->satpam_id);
            }
            if ($request->kandang_id) {
                $query->where('kandang_id', $request->kandang_id);
            }

            $query->orderBy('tanggal','desc')->orderBy('jam','desc');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('tanggal', function ($row) {
                    return '<div style="text-align:center">' . date('d-m-Y', strtotime($row->tanggal)) . '</div>';
                })
                ->addColumn('satpam_id', function ($row) {
                    return $row->satpam->name ?? '';
                })
                ->addColumn('comid', function ($row) {
                    return $row->company->company_name ?? '';
                })
                ->addColumn('kandang_id', function ($row) {
                    return $row->kandang->name ?? '';
                })
                ->addColumn('is_alarm_on', function($row){
                    return $row->is_alarm_on == 1 ? 'ON':'OFF';
                })
                ->addColumn('latitude', function ($row) {
                    if ($row->latitude && $row->longitude) {
                        $url = "https://www.google.com/maps/@{$row->latitude},{$row->longitude},21z";
                        return '
            <div style="text-align:center">
                <a href="' .
                            $url .
                            '" target="_blank" class="text-primary fw-bold">
                    ' .
                            $row->latitude .
                            ' , ' .
                            $row->longitude .
                            '
                </a>
            </div>';
                    } else {
                        return '<div style="text-align:center">-</div>';
                    }
                })
                ->addColumn('foto', function ($row) {
                    if (!empty($row->foto)) {
                        $url = asset('storage/' . $row->foto);
                        return '<a href="' . asset('storage/' . $row->foto) . '" target="_blank"><img  style="cursor:pointer;" src="' . $url . '" alt="Foto" width="50" height="50" class="rounded-circle border"></a>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })

                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
                    // $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '<button onclick="deleteData(' . $row->id . ', 3)" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action', 'latitude', 'tanggal', 'foto'])
                ->make(true);

            // bi bi-trash3
        }
    }


    public function kandang_lampu_table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $query = KandangLampu::where('comid', $comid)->with(['satpam:id,name', 'company:id,company_name', 'kandang:id,name']);
            if ($request->start_date && $request->end_date) {
                $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            }

            if ($request->satpam_id) {
                $query->where('satpam_id', $request->satpam_id);
            }
            if ($request->kandang_id) {
                $query->where('kandang_id', $request->kandang_id);
            }

            $query->orderBy('tanggal','desc')->orderBy('jam','desc');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('tanggal', function ($row) {
                    return '<div style="text-align:center">' . date('d-m-Y', strtotime($row->tanggal)) . '</div>';
                })
                ->addColumn('satpam_id', function ($row) {
                    return $row->satpam->name ?? '';
                })
                ->addColumn('comid', function ($row) {
                    return $row->company->company_name ?? '';
                })
                ->addColumn('kandang_id', function ($row) {
                    return $row->kandang->name ?? '';
                })
                ->addColumn('is_lamp_on', function($row){
                    return $row->is_lamp_on == 1 ? 'ON':'OFF';
                })
                ->addColumn('latitude', function ($row) {
                    if ($row->latitude && $row->longitude) {
                        $url = "https://www.google.com/maps/@{$row->latitude},{$row->longitude},21z";
                        return '
            <div style="text-align:center">
                <a href="' .
                            $url .
                            '" target="_blank" class="text-primary fw-bold">
                    ' .
                            $row->latitude .
                            ' , ' .
                            $row->longitude .
                            '
                </a>
            </div>';
                    } else {
                        return '<div style="text-align:center">-</div>';
                    }
                })
                ->addColumn('foto', function ($row) {
                    if (!empty($row->foto)) {
                        $url = asset('storage/' . $row->foto);
                        return '<a href="' . asset('storage/' . $row->foto) . '" target="_blank"><img  style="cursor:pointer;" src="' . $url . '" alt="Foto" width="50" height="50" class="rounded-circle border"></a>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })

                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
                    // $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '<button onclick="deleteData(' . $row->id . ', 4)" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action', 'latitude', 'tanggal', 'foto'])
                ->make(true);

            // bi bi-trash3
        }
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'patroli-kandang';
        $satpams = Satpam::where('comid', $this->comid())->get();
        $kandangs = Kandang::where('comid', $this->comid())->get();
        return view('frontend.aktivitas.patroli_kandang.patroli_kandang', compact('view', 'satpams', 'kandangs'));
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
    public function destroy(Request $request, string $id)
    {
        $input = $request->all();
        $type = $input['type'];

        if($type == 1) {
            return KandangSuhu::destroy($id);
        }
        else if($type == 2) {
            return KandangKipas::destroy($id);
        }
        else if($type == 3) {
            return KandangAlarm::destroy($id);
        }
        else if($type == 4) {
            return KandangLampu::destroy($id);
        }
    }
}
