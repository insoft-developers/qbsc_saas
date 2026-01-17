<?php

namespace App\Http\Controllers\Frontend;

use App\Exports\KinerjaExport;
use App\Http\Controllers\Controller;
use App\Models\Satpam;
use App\Traits\CommonTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

class LaporanKinerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    use CommonTrait;
    public function table(Request $request)
    {
        if ($request->ajax()) {
            $bulan = $request->periode ?? date('m'); // default bulan ini
            $tahun = $request->tahun ?? date('Y'); // default tahun ini

            $comid = $this->comid();
            $query = Satpam::with([
                'absensi' => function ($q) use ($bulan, $tahun) {
                    $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
                },

                'patroli' => function ($q) use ($bulan, $tahun) {
                    $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
                },
            ])
                ->where('comid', $comid)
                ->orderBy('created_at', 'desc');
            return DataTables::of($query)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    $disabled = $this->isOwner() ? '' : 'disabled';
                    $button = '';
                    $button .= '<center>';
                    $button .= '<button ' . $disabled . ' onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    $button .= '</center>';
                    return $button;
                })
                ->addColumn('foto', function ($row) {
                    if (!empty($row->face_photo_path)) {
                        $url = asset('storage/' . $row->face_photo_path);
                        return '<a href="' . asset('storage/' . $row->face_photo_path) . '" target="_blank"><img  style="cursor:pointer;" src="' . $url . '" alt="Foto" width="50" height="50" class="rounded-circle border"></a>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('satpam_id', function ($row) {
                    return $row->name;
                })
                ->addColumn('jabatan', function ($row) {
                    return $row->is_danru == 1 ? 'Danru' : 'Anggota';
                })
                ->addColumn('hadir', function ($row) {
                    return $row->absensi->where('status', 2)->count();
                })
                ->addColumn('tepat_waktu', function ($row) {
                    return $row->absensi->where('status', 2)->where('is_terlambat', 0)->where('is_pulang_cepat', 0)->count();
                })
                ->addColumn('terlambat', function ($row) {
                    return $row->absensi->where('status', 2)->where('is_terlambat', 1)->count() . ' X';
                })
                ->addColumn('total_terlambat', function ($row) {
                    $totalTerlambat = 0;

                    foreach ($row->absensi as $abs) {
                        if ($abs->status == 2) {
                            if (strpos($abs->catatan_masuk, 'terlambat') !== false) {
                                // cek apakah ada teks Terlambat
                                preg_match('/\d+/', $abs->catatan_masuk, $matches);
                                $menit = $matches[0] ?? 0; // ambil angka, default 0
                                $totalTerlambat += (int) $menit;
                            }
                        }
                    }

                    return $totalTerlambat . ' menit';
                })

                ->addColumn('cepat_pulang', function ($row) {
                    return $row->absensi->where('status', 2)->where('is_pulang_cepat', 1)->count() . ' X';
                })
                ->addColumn('total_cepat_pulang', function ($row) {
                    $pulangCepat = 0;

                    foreach ($row->absensi as $abs) {
                        if ($abs->status == 2) {
                            if (strpos($abs->catatan_keluar, 'pulang lebih cepat') !== false) {
                                // cek apakah ada teks Terlambat
                                preg_match('/\d+/', $abs->catatan_keluar, $matches);
                                $menit = $matches[0] ?? 0; // ambil angka, default 0
                                $pulangCepat += (int) $menit;
                            }
                        }
                    }

                    return $pulangCepat . ' menit';
                })

                ->addColumn('total_patroli', function ($row) {
                    return $row->patroli->count();
                })

                ->addColumn('patroli_diluar_jadwal', function ($row) {
                    return $row->patroli
                        ->filter(function ($p) {
                            // 🚨 Jika jadwal kosong → dianggap di luar range
                            if (empty($p->jam_awal_patroli) || empty($p->jam_akhir_patroli)) {
                                return true;
                            }

                            $jamPatroli = Carbon::createFromFormat('H:i:s', $p->jam);
                            $jamMulai = Carbon::createFromFormat('H:i', $p->jam_awal_patroli);
                            $jamAkhir = Carbon::createFromFormat('H:i', $p->jam_akhir_patroli);

                            // true = DI LUAR RANGE
                            return !$jamPatroli->between($jamMulai, $jamAkhir, true);
                        })
                        ->count();
                })

                ->addColumn('comid', function ($row) {
                    return $row->company->company_name ?? '';
                })

                ->rawColumns(['action', 'foto'])
                ->make(true);

            // bi bi-trash3
        }
    }

    public function index()
    {
        $view = 'laporan-kinerja';
        return view('frontend.laporan.kinerja.kinerja', compact('view'));
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
        //
    }

    public function export_xls(Request $request)
    {
        return Excel::download(new KinerjaExport($request->periode ?: null, $request->tahun ?: null), 'Laporan Kinerja Satpam.xlsx');
    }

    public function export_pdf(Request $request)
    {
        $bulan = $request->periode ?? date('m'); // default bulan ini
        $tahun = $request->tahun ?? date('Y'); // default tahun ini

        $comid = $this->comid();
        $query = Satpam::with([
            'absensi' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            },

            'patroli' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            },
        ])
            ->where('comid', $comid)
            ->orderBy('created_at', 'desc');

        $data = $query->get();

        $pdf = Pdf::loadView('frontend.laporan.kinerja.pdf', compact('data'))->setPaper('legal', 'landscape');

        return $pdf->stream('Laporan Kinerja Satpam.pdf');
    }
}
