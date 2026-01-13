<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Satpam;
use App\Models\Absen;
use App\Models\KandangSuhu;
use App\Models\KandangKipas;
use App\Models\KandangAlarm;
use App\Models\KandangLampu;
use App\Models\Tamu;
use App\Models\LaporanSituasi;
use App\Models\Broadcast;
use App\Exports\RekapAllExport;
use App\Models\Absensi;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;


class RekapController extends Controller
{
    public function index(Request $request)
    {
        $satpams = Satpam::where('comid', auth()->user()->comid)->get();

        $start = $request->start ?? now()->format('Y-m-d');
        $end = $request->end ?? $start;
        $satpam_id = $request->satpam_id;
        $view = 'rekap';

        // Untuk blade view, kita bisa load data untuk tabel awal (optional, bisa via ajax nanti)
        return view('frontend.laporan.rekap.rekap', compact('satpams', 'start', 'end', 'satpam_id','view'));
    }

    public function table(Request $request)
    {
        $start = $request->start;
        $end = $request->end;
        $satpam_id = $request->satpam_id;

        $data = [
            'absen' => Absensi::with('satpam')->whereBetween('tanggal', [$start, $end])
                              ->when($satpam_id, fn($q)=>$q->where('satpam_id', $satpam_id))->get(),
            'suhu' => KandangSuhu::with('satpam','kandang','company')->whereBetween('tanggal', [$start, $end])
                                  ->when($satpam_id, fn($q)=>$q->where('satpam_id', $satpam_id))->get(),
            'kipas' => KandangKipas::with('satpam','kandang','company')->whereBetween('tanggal', [$start, $end])
                                    ->when($satpam_id, fn($q)=>$q->where('satpam_id', $satpam_id))->get(),
            'alarm' => KandangAlarm::with('satpam','kandang','company')->whereBetween('tanggal', [$start, $end])
                                    ->when($satpam_id, fn($q)=>$q->where('satpam_id', $satpam_id))->get(),
            'lampu' => KandangLampu::with('satpam','kandang','company')->whereBetween('tanggal', [$start, $end])
                                    ->when($satpam_id, fn($q)=>$q->where('satpam_id', $satpam_id))->get(),
            'tamu'  => Tamu::with('satpam','satpam_pulang','user','company')->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59'])
                              ->when($satpam_id, fn($q)=>$q->where('satpam_id', $satpam_id))->get(),
            'situasi' => LaporanSituasi::with('satpam','company')->whereBetween('tanggal', [$start, $end])
                                        ->when($satpam_id, fn($q)=>$q->where('satpam_id', $satpam_id))->get(),
            'broadcast' => Broadcast::whereBetween('tanggal', [$start, $end])->get()
        ];

        return response()->json($data);
    }

    public function exportExcel(Request $request)
    {
        $start = $request->start;
        $end = $request->end;
        $satpam_id = $request->satpam_id;

        return Excel::download(new RekapAllExport($start, $end, $satpam_id), "rekap_{$start}_{$end}.xlsx");
    }

    public function exportPDF(Request $request)
    {
        $start = $request->start;
        $end = $request->end;
        $satpam_id = $request->satpam_id;

        $data = [
            'absen' => Absensi::with('satpam')->whereBetween('tanggal', [$start, $end])
                              ->when($satpam_id, fn($q)=>$q->where('satpam_id', $satpam_id))->get(),
            'suhu' => KandangSuhu::with('satpam','kandang','company')->whereBetween('tanggal', [$start, $end])
                                  ->when($satpam_id, fn($q)=>$q->where('satpam_id', $satpam_id))->get(),
            'kipas' => KandangKipas::with('satpam','kandang','company')->whereBetween('tanggal', [$start, $end])
                                    ->when($satpam_id, fn($q)=>$q->where('satpam_id', $satpam_id))->get(),
            'alarm' => KandangAlarm::with('satpam','kandang','company')->whereBetween('tanggal', [$start, $end])
                                    ->when($satpam_id, fn($q)=>$q->where('satpam_id', $satpam_id))->get(),
            'lampu' => KandangLampu::with('satpam','kandang','company')->whereBetween('tanggal', [$start, $end])
                                    ->when($satpam_id, fn($q)=>$q->where('satpam_id', $satpam_id))->get(),
            'tamu'  => Tamu::with('satpam','satpam_pulang','user','company')->whereBetween('created_at', [$start.' 00:00:00', $end.' 23:59:59'])
                              ->when($satpam_id, fn($q)=>$q->where('satpam_id', $satpam_id))->get(),
            'situasi' => LaporanSituasi::with('satpam','company')->whereBetween('tanggal', [$start, $end])
                                        ->when($satpam_id, fn($q)=>$q->where('satpam_id', $satpam_id))->get(),
            'broadcast' => Broadcast::whereBetween('tanggal', [$start, $end])->get()
        ];

        $pdf = PDF::loadView('frontend.laporan.rekap_pdf', $data);
        return $pdf->stream("rekap_{$start}_{$end}.pdf");
    }
}
