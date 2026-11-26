<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\PaketLangganan;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AdminPackageController extends Controller
{
    public function index()
    {
        $view = 'paket';
        $companies = Company::orderBy('company_name')->get();
        return view('admin.package.index', compact('view', 'companies'));
    }

    public function table(Request $request)
    {
        if ($request->ajax()) {
            $query = PaketLangganan::query();

            $data = $query->orderBy('urutan', 'asc');


            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('nama_paket', function ($row) {
                    return '<div style="white-space:normal;width:230px;">' . $row->nama_paket . '<br><small>' . $row->subtitle . '</small></div>';
                })

                ->addColumn('harga', function ($row) {
                    return 'Rp ' . number_format($row->harga, 0, ',', '.');
                })

                ->addColumn('periode_text', function ($row) {
                    if ($row->periode == 1) {
                        return 'Bulanan';
                    } elseif ($row->periode == 2) {
                        return 'Tahunan';
                    }
                    return 'Lainnya';
                })

                ->addColumn('company_type_text', function ($row) {
                    return $row->company_type == 1
                        ? 'Peternakan'
                        : 'Reguler';
                })

                ->addColumn('fitur', function ($row) {
                    $fitur = [
                        'Broadcast' => $row->is_broadcast,
                        'Scan Tamu' => $row->is_scan_tamu,
                        'User Area' => $row->is_user_area,
                        'Mobile App' => $row->is_mobile_app,
                        'Google Meet' => $row->is_google_meet,
                        'Request Feature' => $row->is_request_feature,
                    ];

                    $html = '';

                    foreach ($fitur as $nama => $aktif) {
                        $html .= $aktif
                            ? '<span class="badge bg-success me-1 mb-1">' . $nama . '</span>'
                            : '';
                    }

                    return '<div style="white-space:normal;width:230px;">' . $html . '</div>' ?: '-';
                })

                ->addColumn('status', function ($row) {
                    return $row->is_active
                        ? '<span class="badge bg-success">Aktif</span>'
                        : '<span class="badge bg-danger">Tidak Aktif</span>';
                })

                ->addColumn('is_show', function ($row) {
                    return $row->is_show === 1
                        ? '<span class="badge bg-success">Ya</span>'
                        : '<span class="badge bg-danger">Tidak</span>';
                })

                ->addColumn('comids', function ($row) {

                    if (empty($row->comids)) {
                        return '<span class="badge bg-success">Semua</span>';
                    }

                    $ids = explode(',', $row->comids);

                    $companies = Company::whereIn('id', $ids)
                        ->pluck('company_name');

                    $html = '';

                    foreach ($companies as $company) {
                        $html .= '<span class="badge bg-info me-1 mb-1">'
                            . e($company) .
                            '</span>';
                    }

                     return '<div style="white-space:normal;width:230px;">' . $html . '</div>' ?: '-';
                })

                ->addColumn('action', function ($row) {
                    return '
                        <center>
                            <button onclick="editData(' . $row->id . ')"
                                class="btn btn-insoft btn-warning"
                                title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </button>

                            <button onclick="deleteData(' . $row->id . ')"
                                class="btn btn-insoft btn-danger"
                                title="Hapus">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </center>
                    ';
                })

                ->rawColumns(['fitur', 'status', 'action', 'nama_paket', 'is_show','comids'])
                ->make(true);
        }
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        PaketLangganan::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Paket berhasil ditambahkan.'
        ]);
    }

    public function edit($id)
    {
        return response()->json(
            PaketLangganan::findOrFail($id)
        );
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validateData($request);

        $paket = PaketLangganan::findOrFail($id);
        $paket->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Paket berhasil diperbarui.'
        ]);
    }

    public function destroy($id)
    {
        $paket = PaketLangganan::findOrFail($id);

        // Cegah penghapusan paket yang masih digunakan perusahaan
        if ($paket->companies()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Paket masih digunakan oleh perusahaan. Nonaktifkan saja paket ini.'
            ], 422);
        }

        $paket->delete();

        return response()->json([
            'success' => true,
            'message' => 'Paket berhasil dihapus.'
        ]);
    }

    private function validateData(Request $request)
    {
        $validated = $request->validate([
            'nama_paket' => 'required|string|max:255',
            'subtitle' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
            'periode' => 'required|integer|in:1,2',
            'hari' => 'required|integer|min:0',
            'jumlah_satpam' => 'required|integer|min:0',
            'jumlah_lokasi' => 'required|integer|min:0',
            'jumlah_farm' => 'nullable|integer|min:0',
            'jumlah_user_admin' => 'nullable|integer|min:0',
            'company_type' => 'required|integer|in:0,1',
            'is_active' => 'required|boolean',
            'is_show' => 'required',
            'urutan' => 'required|integer|min:0',

            'is_broadcast' => 'required|boolean',
            'is_scan_tamu' => 'required|boolean',
            'is_user_area' => 'required|boolean',
            'is_mobile_app' => 'required|boolean',
            'is_google_meet' => 'required|boolean',
            'is_request_feature' => 'required|boolean',

            'comids' => 'nullable|array',
            'comids.*' => 'integer|exists:companies,id',
        ]);

        $validated['comids'] = !empty($validated['comids'])
            ? implode(',', $validated['comids'])
            : null;

        return $validated;
    }
}
