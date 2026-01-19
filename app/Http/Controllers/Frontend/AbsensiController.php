<?php

namespace App\Http\Controllers\Frontend;

use App\Exports\AbsensiExport;
use App\Http\Controllers\Controller;
use App\Models\AbsenLocation;
use App\Models\Absensi;
use App\Models\Satpam;
use App\Traits\CommonTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class AbsensiController extends Controller
{
    use CommonTrait;
    public function absensi_table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $query = Absensi::where('comid', $comid);
            if ($request->start_date && $request->end_date) {
                $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
            }

            if ($request->satpam_id) {
                $query->where('satpam_id', $request->satpam_id);
            }
            if ($request->status) {
                $query->where('status', $request->status);
            }

            if ($request->jam_absen) {
                if ($request->jam_absen == 1) {
                    $query->where('is_terlambat', 0)->where('is_pulang_cepat', 0);
                } elseif ($request->jam_absen == 2) {
                    $query->where('is_terlambat', 1);
                } elseif ($request->jam_absen == 3) {
                    $query->where('is_pulang_cepat', 1);
                } elseif ($request->jam_absen == 4) {
                    $query->where('is_terlambat', 1)->where('is_pulang_cepat', 1);
                }
            }
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
                ->addColumn('latitude', function ($row) {
                    if ($row->latitude && $row->longitude) {
                        $url = "https://www.google.com/maps/search/?api=1&query={$row->latitude},{$row->longitude}";

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
            </div>
        ';
                    } else {
                        return '<div style="text-align:center">-</div>';
                    }
                })

                ->addColumn('latitude2', function ($row) {
                    if ($row->latitude2 && $row->longitude2) {
                        $url = "https://www.google.com/maps/search/?api=1&query={$row->latitude2},{$row->longitude2}";

                        return '
            <div style="text-align:center">
                <a href="' .
                            $url .
                            '" target="_blank" class="text-primary fw-bold">
                    ' .
                            $row->latitude2 .
                            ' , ' .
                            $row->longitude2 .
                            '
                </a>
            </div>
        ';
                    } else {
                        return '<div style="text-align:center">-</div>';
                    }
                })

                ->addColumn('jam_masuk', function ($row) {
                    return '<div style="text-align:center">' . date('H:i:s', strtotime($row->jam_masuk)) . '</div>';
                })
                ->addColumn('jam_keluar', function ($row) {
                    return $row->jam_keluar ? '<div style="text-align:center">' . date('H:i:s', strtotime($row->jam_keluar)) . '</div>' : '';
                })
                ->addColumn('status', function ($row) {
                    return $row->status == 1 ? '<span class="badge bg-info rounded-pill"><i class="fa fa-check"></i> Masuk</span>' : '<span class="badge bg-danger rounded-pill">Pulang</span>';
                })

                ->addColumn('foto_masuk', function ($row) {
                    if (!empty($row->foto_masuk)) {
                        $url = asset('storage/' . $row->foto_masuk);
                        return '<a href="' . asset('storage/' . $row->foto_masuk) . '" target="_blank"><img  style="cursor:pointer;" src="' . $url . '" alt="Foto" width="50" height="50" class="rounded-circle border"></a>';
                    } else {
                        return '-';
                    }
                })

                ->addColumn('foto_pulang', function ($row) {
                    if (!empty($row->foto_pulang)) {
                        $url = asset('storage/' . $row->foto_pulang);
                        return '<a href="' . asset('storage/' . $row->foto_pulang) . '" target="_blank"><img  style="cursor:pointer;" src="' . $url . '" alt="Foto" width="50" height="50" class="rounded-circle border"></a>';
                    } else {
                        return '-';
                    }
                })

                // 🔍 Filter manual untuk kolom relasi
                ->filterColumn('satpam_id', function ($query, $keyword) {
                    $query->whereHas('satpam', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->filterColumn('comid', function ($query, $keyword) {
                    $query->whereHas('company', function ($q) use ($keyword) {
                        $q->where('company_name', 'like', "%{$keyword}%");
                    });
                })

                ->filterColumn('jam_masuk', function ($query, $keyword) {
                    $query->where('jam_masuk', 'like', "%{$keyword}%");
                })
                ->filterColumn('jam_keluar', function ($query, $keyword) {
                    $query->where('jam_keluar', 'like', "%{$keyword}%");
                })

                ->filterColumn('tanggal', function ($query, $keyword) {
                    // Konversi keyword pencarian (misalnya "05-11-2025") agar bisa dicocokkan dengan kolom tanggal (Y-m-d)
                    $query->whereRaw("DATE_FORMAT(tanggal, '%d-%m-%Y') LIKE ?", ["%{$keyword}%"]);
                })

                ->filterColumn('description', function ($query, $keyword) {
                    $query->where('description', 'like', "%{$keyword}%");
                })

                ->addColumn('action', function ($row) {
                    $disabled = $this->isOwner() ? '' : 'disabled';
                    $button = '';
                    $button .= '<center>';
                    $button .= '<button ' . $disabled . ' onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '<button ' . $disabled . ' onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action', 'latitude', 'tanggal', 'jam_masuk', 'jam_keluar', 'status','latitude2','foto_masuk','foto_pulang'])
                ->make(true);

            // bi bi-trash3
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'absensi-satpam';
        $satpams = Satpam::where('comid', $this->comid())->get();
        $isOwner = $this->isOwner();
        return view('frontend.aktivitas.absensi.absensi', compact('view', 'satpams', 'isOwner'));
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
            'tanggal' => 'required',
            'satpam_id' => 'required',
            'jam_masuk' => 'required',
            'status_absen' => 'required',
            'jam_keluar' => 'nullable',
            'description' => 'nullable',
        ]);

        // Simpan ke database
        try {
            $comid = $this->comid();
            $input['comid'] = $comid;
            $input['status'] = $request->status_absen;
            if ($request->jam_masuk) {
                $input['jam_masuk'] = date('Y-m-d') . ' ' . $request->jam_masuk;
            }
            if ($request->jam_keluar) {
                $input['jam_keluar'] = date('Y-m-d') . ' ' . $request->jam_keluar;
            }
            $location = AbsenLocation::where('comid', $comid)->first();
            if ($location) {
                $input['latitude'] = $location->latitude;
                $input['longitude'] = $location->longitude;
            }

            Absensi::create($input);
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
        $data = Absensi::find($id);
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();
        $validated = $request->validate([
            'tanggal' => 'required',
            'satpam_id' => 'required',
            'jam_masuk' => 'required',
            'status_absen' => 'required',
            'jam_keluar' => 'nullable',
            'description' => 'nullable',
        ]);

        // Simpan ke database
        try {
            $comid = $this->comid();

            $data = Absensi::find($id);

            $input['comid'] = $comid;
            $input['status'] = $request->status_absen;
            if ($request->jam_masuk) {
                $input['jam_masuk'] = $data->tanggal . ' ' . $request->jam_masuk;
            }
            if ($request->jam_keluar) {
                $input['jam_keluar'] = $request->tanggal . ' ' . $request->jam_keluar;
            }

            $input['tanggal'] = $data->tanggal;

            
            unset($input['latitude'], $input['longitude']);
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
        $data = Absensi::destroy($id);
        return $data;
    }

    public function exportXls(Request $request)
    {
        return Excel::download(new AbsensiExport($request->start_date ?: null, $request->end_date ?: null, $request->satpam_id ?: null, $request->status ?: null, $request->jam_absen ?: null), 'Data_Absensi.xlsx');
    }

    public function exportPdf(Request $request)
    {
        $query = Absensi::where('comid', $this->comid())->with(['satpam', 'company']);

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
        }

        if ($request->satpam_id) {
            $query->where('satpam_id', $request->satpam_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->jam_absen) {
            if ($request->jam_absen == 1) {
                $query->where('is_terlambat', 0)->where('is_pulang_cepat', 0);
            } elseif ($request->jam_absen == 2) {
                $query->where('is_terlambat', 1);
            } elseif ($request->jam_absen == 3) {
                $query->where('is_pulang_cepat', 1);
            } elseif ($request->jam_absen == 4) {
                $query->where('is_terlambat', 1)->where('is_pulang_cepat', 1);
            }
        }

        $data = $query->get();

        $pdf = Pdf::loadView('frontend.aktivitas.absensi.pdf', compact('data'))->setPaper('legal', 'landscape');

        return $pdf->stream('Data_Absensi.pdf');
    }
}
