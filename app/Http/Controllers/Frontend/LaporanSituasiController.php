<?php

namespace App\Http\Controllers\Frontend;

use App\Exports\SituasiExport;
use App\Http\Controllers\Controller;
use App\Models\JamShift;
use App\Models\LaporanSituasi;
use App\Models\Satpam;
use App\Traits\CommonTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class LaporanSituasiController extends Controller
{
    use CommonTrait;
    public function laporan_situasi_table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $query = LaporanSituasi::where('comid', $comid)->with(['satpam:id,name', 'company:id,company_name']);
            if ($request->start_date && $request->end_date) {
                $start = Carbon::parse($request->start_date)->startOfDay();
                $end = Carbon::parse($request->end_date)->endOfDay();

                $query->whereBetween('tanggal', [$start, $end]);
            }

            if ($request->satpam_id) {
                $query->where('satpam_id', $request->satpam_id);
            }
            if ($request->ekspedisi_id) {
                $query->where('ekspedisi_id', $request->ekspedisi_id);
            }
            $query->orderBy('tanggal', 'desc');
            return DataTables::of($query)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
                    $button .= '<button onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    $button .= '</center>';
                    return $button;
                })
                ->addColumn('satpam_id', function ($row) {
                    return $row->satpam->name ?? '';
                })

                ->addColumn('laporan', function ($row) {
                    $full = $row->laporan;
                    $maxChars = 200; // kira2 3 baris

                    if (strlen($full) > $maxChars) {
                        $short = substr($full, 0, $maxChars) . '...';
                        return '
            <div class="laporan-short" style="white-space:normal;width:400px; display:block;">' .
                            $short .
                            '</div>
            <div class="laporan-full" style="white-space:normal;width:400px; display:none;">' .
                            $full .
                            '</div>
            <a href="javascript:void(0)" class="read-more">Selengkapnya</a>
        ';
                    } else {
                        return '<div style="white-space:normal;width:400px;">' . $full . '</div>';
                    }
                })

                ->addColumn('tanggal', function ($row) {
                    return date('d-m-Y H:i:s', strtotime($row->tanggal));
                })

                ->addColumn('foto', function ($row) {
                    if (!empty($row->foto)) {
                        $url = asset('storage/' . $row->foto);
                        return '<a href="' . asset('storage/' . $row->foto) . '" target="_blank"><img  style="cursor:pointer;" src="' . $url . '" alt="Foto" width="50" height="50" class="rounded-circle border"></a>';
                    } else {
                        return '-';
                    }
                })

                ->addColumn('comid', function ($row) {
                    return $row->company->company_name ?? '';
                })

                ->rawColumns(['action', 'laporan', 'foto'])
                ->make(true);

            // bi bi-trash3
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'laporan-situasi';
        $satpams = Satpam::where('comid', $this->comid())->get();
        return view('frontend.laporan.situasi.situasi', compact('view', 'satpams'));
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
    public function edit(string $id) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return LaporanSituasi::destroy($id);
    }


    public function export_xls(Request $request)
    {
        return Excel::download(new SituasiExport($request->start_date ?: null, $request->end_date ?: null, $request->satpam_id ?: null), 'Laporan Situasi.xlsx');
    }

    public function export_pdf(Request $request)
    {
        $query = LaporanSituasi::where('comid', $this->comid())->with(['satpam', 'company']);

        if ($request->start_date && $request->end_date) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();

            $query->whereBetween('tanggal', [$start, $end]);
        }

        if ($request->satpam_id) {
            $query->where('satpam_id', $request->satpam_id);
        }

        $data = $query->get();

        $pdf = Pdf::loadView('frontend.laporan.situasi.pdf', compact('data'))->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan Situasi.pdf');
    }
}
