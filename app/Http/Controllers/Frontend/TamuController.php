<?php

namespace App\Http\Controllers\Frontend;

use App\Exports\SituasiExport;
use App\Exports\TamuExport;
use App\Http\Controllers\Controller;
use App\Models\JamShift;
use App\Models\LaporanSituasi;
use App\Models\Satpam;
use App\Models\Tamu;
use App\Models\User;
use App\Traits\CommonTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class TamuController extends Controller
{
    use CommonTrait;
    public function tamu_table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $query = Tamu::where('comid', $comid)->with(['satpam:id,name', 'company:id,company_name']);
            if ($request->start_date && $request->end_date) {
                $start = Carbon::parse($request->start_date)->startOfDay();
                $end = Carbon::parse($request->end_date)->endOfDay();

                $query->whereBetween('created_at', [$start, $end]);
            }

            if ($request->satpam_id) {
                $query->where(function ($q) use ($request) {
                    $q->where('satpam_id', $request->satpam_id)->orWhere('satpam_id_pulang', $request->satpam_id);
                });
            }

            if ($request->user_id) {
                if ($request->user_id == -1) {
                    $query->whereNull('created_by');
                } else {
                    $query->where('created_by', $request->user_id);
                }
            }

            $query->orderBy('created_at', 'desc');
            return DataTables::of($query)
                ->addIndexColumn()

                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
                    if ($row->is_status == 1 || $row->is_status == 2) {
                        if (Auth::user()->id === $row->created_by) {
                            $button .= '<button title="Copy Link QRCode" class="me-0 btn btn-insoft btn-light border-1 copyLink" data-link="' . url('copy_link_tamu/' . $row->uuid) . '"><i class="bi bi-qr-code"></i></button>';
                        } else {
                            $button .= '<button disabled title="Copy Link QRCode" class="me-0 btn btn-insoft btn-light border-1"><i class="bi bi-qr-code"></i></button>';
                        }
                    } else {
                        $button .= '<button disabled title="Copy Link QRCode" class="me-0 btn btn-insoft btn-light border-1"><i class="bi bi-qr-code"></i></button>';
                    }

                    if ($row->created_by === Auth::user()->id) {
                        $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                        $button .= '<button onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    } else {
                        $button .= '<button disabled title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                        $button .= '<button disabled title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    }
                    $button .= '</center>';
                    return $button;
                })
                ->addColumn('created_at', function ($row) {
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })

                ->addColumn('arrive_at', function ($row) {
                    return $row->arrive_at == null ? '' : date('d-m-Y H:i', strtotime($row->arrive_at));
                })

                ->addColumn('leave_at', function ($row) {
                    return $row->leave_at == null ? '' : date('d-m-Y H:i', strtotime($row->leave_at));
                })
                ->addColumn('satpam_id', function ($row) {
                    $satpam_masuk = $row->satpam->name ?? '';
                    $satpam_pulang = $row->satpam_pulang->name ?? '';
                    return '- ' . $satpam_masuk . '<br>- ' . $satpam_pulang;
                })
                ->addColumn('is_status', function ($row) {
                    if ($row->is_status == 1) {
                        return '<div class="badge bg-info">Appointment</div>';
                    } elseif ($row->is_status == 2) {
                        return '<div class="badge bg-success">Tiba</div>';
                    } elseif ($row->is_status == 3) {
                        return '<div class="badge bg-danger">Pulang</div>';
                    }
                })
                ->addColumn('catatan', function ($row) {
                    return '<div style="white-space:normal;width:200px;">' . $row->catatan . '</div>';
                })
                ->addColumn('nama_tamu', function ($row) {
                    return '<div style="white-space:normal;width:200px;">' . $row->nama_tamu . '</div>';
                })

                ->addColumn('tujuan', function ($row) {
                    return '<div style="white-space:normal;width:150px;">' . $row->tujuan . '</div>';
                })
                ->addColumn('foto', function ($row) {
                    if (!empty($row->foto)) {
                        $url = asset('storage/' . $row->foto);
                        return '<a href="' . asset('storage/' . $row->foto) . '" target="_blank"><img  style="cursor:pointer;" src="' . $url . '" alt="Foto" width="50" height="50" class="rounded-circle border"></a>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('created_by', function ($row) {
                    if($row->created_by == -1) {
                        return 'Satpam';
                    } else {
                         return $row->user->name ?? '';
                    }
                   
                })
                ->addColumn('comid', function ($row) {
                    return $row->company->company_name ?? '';
                })

                ->rawColumns(['action', 'foto', 'catatan', 'nama_tamu', 'tujuan', 'is_status', 'satpam_id'])
                ->make(true);

            // bi bi-trash3
        }
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'laporan-tamu';
        $satpams = Satpam::where('comid', $this->comid())->get();
        $users = User::where('company_id', $this->comid())->get();
        return view('frontend.laporan.tamu.tamu', compact('view', 'satpams', 'users'));
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
            'nama_tamu' => 'required|string|max:100',
            'jumlah_tamu' => 'required',
            'tujuan' => 'required',
            'whatsapp' => 'nullable',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $paket = $this->what_paket($this->comid());
        if($paket['is_scan_tamu'] !== 1) {
            return response()->json([
                "sucess" => false,
                "message" => 'Paket anda saat ini hanya mengizinkan anda untuk menambah data tamu via aplikasi satpam secara manual, Silahkan upgrade paket anda untuk bisa membuat qr tamu dan scan di aplikasi satpam..!!'
            ]);
        }

        // Simpan foto ke storage
        $path = null;

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('tamu', 'public');
        }

        // Simpan ke database
        $input['foto'] = $path;
        $input['uuid'] = Str::uuid();
        $input['is_status'] = 1;
        $input['comid'] = $this->comid();
        $input['created_by'] = Auth::user()->id;
        Tamu::create($input);
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.',
        ]);
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
        return Tamu::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();
        $validated = $request->validate([
            'nama_tamu' => 'required|string|max:100',
            'jumlah_tamu' => 'required',
            'tujuan' => 'required',
            'whatsapp' => 'nullable',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = Tamu::find($id);

        // Simpan foto ke storage
        $path = $data->foto;

        // Jika ada foto baru diupload
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($data->foto && Storage::disk('public')->exists($data->foto)) {
                Storage::disk('public')->delete($data->foto);
            }

            // Upload foto baru
            $path = $request->file('foto')->store('tamu', 'public');
        }

        // Simpan ke database
        $input['foto'] = $path;
        $data->update($input);
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return LaporanSituasi::destroy($id);
    }

    public function export_xls(Request $request)
    {
        return Excel::download(new TamuExport($request->start_date ?: null, $request->end_date ?: null, $request->satpam_id ?: null, $request->user_id ?: null), 'Laporan Tamu.xlsx');
    }

    public function export_pdf(Request $request)
    {
        $query = Tamu::where('comid', $this->comid())->with(['satpam:id,name', 'company:id,company_name', 'satpam_pulang:id,name', 'user:id,name']);

        if ($request->start_date && $request->end_date) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();

            $query->whereBetween('tanggal', [$start, $end]);
        }

        if ($request->satpam_id) {
            $query->where(function ($q) use ($request) {
                $q->where('satpam_id', $request->satpam_id)->orWhere('satpam_id_pulang', $request->satpam_id);
            });
        }

        if ($request->user_id) {
            if ($request->user_id == -1) {
                $query->whereNull('created_by');
            } else {
                $query->where('created_by', $request->user_id);
            }
        }

        $data = $query->get();

        $pdf = Pdf::loadView('frontend.laporan.tamu.pdf', compact('data'))->setPaper('a4', 'landscape');

        return $pdf->stream('Laporan Tamu.pdf');
    }

    public function copy_link_tamu($uuid)
    {
        $tamu = Tamu::with('company')->where('uuid', $uuid)->where('is_status', '<', 3)->first();
        return view('frontend.laporan.tamu.qrcode', compact('tamu'));
    }
}
