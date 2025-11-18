<?php

namespace App\Http\Controllers\Frontend;

use App\Exports\DocExport;
use App\Exports\PatroliExport;
use App\Http\Controllers\Controller;
use App\Models\DocChick;
use App\Models\Ekspedisi;
use App\Models\Lokasi;
use App\Models\Patroli;
use App\Models\Satpam;
use App\Traits\CommonTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use BcMath\Number;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class DocOutController extends Controller
{
    use CommonTrait;
    public function doc_out_table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $query = DocChick::where('comid', $comid)->with(['satpam:id,name', 'company:id,company_name', 'ekspedisi:id,name']);
            if ($request->start_date && $request->end_date) {
                $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            }

            if ($request->satpam_id) {
                $query->where('satpam_id', $request->satpam_id);
            }
            if ($request->ekspedisi_id) {
                $query->where('ekspedisi_id', $request->ekspedisi_id);
            }
            $query->orderBy('tanggal', 'desc');
            $query->orderBy('jam', 'desc');
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('tanggal', function ($row) {
                    return '<div style="text-align:center">' . date('d-m-Y', strtotime($row->tanggal)) . '</div>';
                })
                ->addColumn('jam', function($row){
                    return $row->jam;
                })
                ->addColumn('input_date', function ($row) {
                    return '<div style="text-align:center">' . date('d-m-Y H:i', strtotime($row->input_date)) . '</div>';
                })
                ->addColumn('satpam_id', function ($row) {
                    return $row->satpam->name ?? '';
                })
                ->addColumn('jumlah', function($row){
                    return number_format($row->jumlah).' Box';
                })
                ->addColumn('jenis', function($row){
                    return $row->jenis== 1? 'Male':'Female';
                })
                ->addColumn('comid', function ($row) {
                    return $row->company->company_name ?? '';
                })
                ->addColumn('ekspedisi_id', function ($row) {
                    return $row->ekspedisi->name ?? '';
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
                    $button .= '<button onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action', 'tanggal', 'foto','input_date'])
                ->make(true);

            // bi bi-trash3
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'doc-out';
        $satpams = Satpam::where('comid', $this->comid())->get();
        $ekspeditions = Ekspedisi::where('comid', $this->comid())->get();
        return view('frontend.aktivitas.doc.doc', compact('view', 'satpams', 'ekspeditions'));
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
        return DocChick::destroy($id);
    }

    public function export_xls(Request $request)
    {
        return Excel::download(new DocExport($request->start_date ?: null, $request->end_date ?: null, $request->satpam_id ?: null, $request->ekspedisi_id ?: null), 'Data_Pengiriman_DOC.xlsx');
    }

    public function export_pdf(Request $request)
    {
        $query = DocChick::where('comid', $this->comid())->with(['satpam', 'company', 'ekspedisi']);

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        if ($request->satpam_id) {
            $query->where('satpam_id', $request->satpam_id);
        }

        if ($request->ekspedisi_id) {
            $query->where('ekspedisi_id', $request->ekspedisi_id);
        }

        $data = $query->get();

        $pdf = Pdf::loadView('frontend.aktivitas.doc.pdf', compact('data'))->setPaper('a4', 'landscape');

        return $pdf->stream('Data_Pengiriman_DOC.pdf');
    }
}
