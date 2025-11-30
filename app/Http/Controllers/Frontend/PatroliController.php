<?php

namespace App\Http\Controllers\Frontend;

use App\Exports\PatroliExport;
use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use App\Models\Patroli;
use App\Models\Satpam;
use App\Traits\CommonTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PatroliController extends Controller
{
    use CommonTrait;

    public function patroli_table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $query = Patroli::where('comid', $comid)->with(['satpam:id,name', 'company:id,company_name', 'lokasi:id,nama_lokasi']);
            if ($request->start_date && $request->end_date) {
                $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            }

            if ($request->satpam_id) {
                $query->where('satpam_id', $request->satpam_id);
            }
            if ($request->location_id) {
                $query->where('location_id', $request->location_id);
            }
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('tanggal', function ($row) {
                    return '<div style="text-align:center">' . date('d-m-Y', strtotime($row->tanggal)) . '</div>';
                })
                ->addColumn('satpam_id', function ($row) {
                    return $row->satpam->name ?? '';
                })
                ->addColumn('jam', function ($row) {
                    $isInRange = $this->jamDalamRange($row->jam, $row->jam_awal, $row->jam_akhir);

                    if ($isInRange) {
                        return '<span>' . $row->jam . '</span>';
                    } else {
                        return '<span style="color:red;font-weight:bold;">' . $row->jam . '</span>';
                    }
                })
                ->addColumn('jam_awal', function ($row) {
                    $awal = explode(',', $row->jam_awal);
                    $akhir = explode(',', $row->jam_akhir);
                    $html = '';
                    foreach ($awal as $index => $a) {
                        $html .= $a . '-' . $akhir[$index] . '<br>';
                    }

                    return $html;
                })
                ->addColumn('comid', function ($row) {
                    return $row->company->company_name ?? '';
                })
                ->addColumn('location_id', function ($row) {
                    return $row->lokasi->nama_lokasi ?? '';
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
                ->addColumn('photo_path', function ($row) {
                    if (!empty($row->photo_path)) {
                        $url = asset('storage/' . $row->photo_path);
                        return '<a href="' . asset('storage/' . $row->photo_path) . '" target="_blank"><img  style="cursor:pointer;" src="' . $url . '" alt="Foto" width="50" height="50" class="rounded-circle border"></a>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })

                ->addColumn('action', function ($row) {
                    $disabled = $this->isOwner() ? '': 'disabled'; 
                    $button = '';
                    $button .= '<center>';
                    // $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '<button '.$disabled.' onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action', 'latitude', 'tanggal', 'photo_path', 'jam_awal','jam'])
                ->make(true);

            // bi bi-trash3
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'patroli-satpam';
        $satpams = Satpam::where('comid', $this->comid())->get();
        $locations = Lokasi::where('comid', $this->comid())->get();
        return view('frontend.aktivitas.patroli.patroli', compact('view', 'satpams', 'locations'));
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
        return Patroli::destroy($id);
    }

    public function exportXls(Request $request)
    {
        return Excel::download(new PatroliExport($request->start_date ?: null, $request->end_date ?: null, $request->satpam_id ?: null, $request->location_id ?: null), 'Data_Patroli.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = Patroli::where('comid', $this->comid())->with(['satpam', 'company', 'lokasi']);

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        if ($request->satpam_id) {
            $query->where('satpam_id', $request->satpam_id);
        }

        if ($request->status) {
            $query->where('location_id', $request->location_id);
        }

        $data = $query->get();

        $pdf = Pdf::loadView('frontend.aktivitas.patroli.pdf', compact('data'))->setPaper('a4', 'landscape');

        return $pdf->stream('Data_Patroli.pdf');
    }
}
