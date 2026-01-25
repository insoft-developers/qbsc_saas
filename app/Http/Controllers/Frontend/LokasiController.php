<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use App\Traits\CommonTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\Facades\DataTables;

class LokasiController extends Controller
{
    use CommonTrait;
    /**
     * Display a listing of the resource.
     */

    public function lokasi_table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $data = Lokasi::where('comid', $comid);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('is_active', function ($row) {
                    return $row->is_active === 1 ? '<center><span class="badge bg-success rounded-pill">Aktif</span></center>' : '<center><span class="badge bg-danger rounded-pill">Tidak</span></center>';
                })
                ->addColumn('company', function ($row) {
                    return $row->company->company_name ?? '';
                })
                ->addColumn('latitude', function ($row) {
                    return $row->latitude == null ? 'Belum Diset' : $row->latitude;
                })
                ->addColumn('longitude', function ($row) {
                    return $row->longitude == null ? 'Belum Diset' : $row->longitude;
                })
                ->addColumn('map', function ($row) {
                    if ($row->latitude && $row->longitude) {
                        $url = "https://www.google.com/maps/search/?api=1&query={$row->latitude},{$row->longitude}";

                        return '
            <div style="text-align:center">
                <a href="' .
                            $url .
                            '" target="_blank" class="text-primary fw-bold">
                    Lihat Lokasi
                </a>
            </div>
        ';
                    } else {
                        return '<div style="text-align:center">-</div>';
                    }
                })
                ->addColumn('action', function ($row) {
                    $disabled = $this->isOwner() ? '' : 'disabled';

                    $button = '';
                    $button .= '<center>';
                    $button .= '<a href="' . url('download_qrcode/' . $row->id) . '" title="Download QRCode" class="me-0 btn btn-insoft btn-light border-1"><i class="bi bi-qr-code"></i></a>';
                    if ($row->is_active == 1) {
                        $button .= '<button ' . $disabled . ' onclick="activate(' . $row->id . ', 0)" title="Non Aktifkan" class="me-0 btn btn-insoft btn-danger"><i class="bi bi-x-lg"></i></button>';
                    } else {
                        $button .= '<button ' . $disabled . ' onclick="activate(' . $row->id . ', 1)" title="Aktifkan" class="me-0 btn btn-insoft btn-success"><i class="bi bi-check-circle"></i></button>';
                    }

                    $button .= '<button ' . $disabled . ' onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '<button ' . $disabled . ' onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action', 'is_active','map'])
                ->make(true);

            // bi bi-trash3
        }
    }

    public function index()
    {
        $view = 'lokasi';
        $isOwner = $this->isOwner();
        return view('frontend.lokasi.lokasi', compact('view', 'isOwner'));
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
            'qrcode' => 'required',
            'nama_lokasi' => 'required|max:100|min:3',
            // 'jam_awal.*' => 'required',
            // 'jam_akhir.*' => 'required'
        ]);

        $paket = $this->what_paket($this->comid());
        $max = $paket['jumlah_lokasi'];

        $jumlah_lokasi = Lokasi::where('comid', $this->comid())->where('is_active', 1)->count();
        if ($jumlah_lokasi >= $max) {
            return response()->json([
                'success' => false,
                'message' => 'Jumlah Lokasi sudah melebihi quota paket anda, silahkan upgrade paket anda untuk menambah jumlah lokasi !!',
            ]);
        }

        // Simpan ke database
        try {
            // $jam_awal_str = implode(",", $input['jam_awal']);
            // $jam_akhir_str = implode(",", $input['jam_akhir']);

            // $input['jam_awal'] = $jam_awal_str;
            // $input['jam_akhir'] = $jam_akhir_str;

            $input['comid'] = $this->comid();
            $input['nama_lokasi'] = strtoupper($request->nama_lokasi);

            Lokasi::create($input);
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
        $lokasi = Lokasi::find($id);
        $data['data'] = $lokasi;
        // $jam_awal_arr = explode(",", $lokasi->jam_awal);
        // $jam_akhir_arr = explode(",", $lokasi->jam_akhir);
        // $data['awal'] = $jam_awal_arr;
        // $data['akhir'] = $jam_akhir_arr;
        return $data;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $input = $request->all();

        $data = Lokasi::findorFail($id);

        $validated = $request->validate([
            'qrcode' => 'required',
            'nama_lokasi' => 'required|max:100|min:3',
            // 'jam_awal.*' => 'required',
            // 'jam_akhir.*' => 'required'
        ]);

        // Simpan ke database
        try {
            // $jam_awal_str = implode(",", $input['jam_awal']);
            // $jam_akhir_str = implode(",", $input['jam_akhir']);

            // $input['jam_awal'] = $jam_awal_str;
            // $input['jam_akhir'] = $jam_akhir_str;

            $input['comid'] = $this->comid();
            $input['nama_lokasi'] = strtoupper($request->nama_lokasi);
            $data->update($input);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
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
        try {
            Lokasi::destroy($id);
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal menghapus data: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function activate(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:lokasis,id',
        ]);

        $data = Lokasi::findOrFail($request->id);
        if ($data->is_active !== 1) {
            $paket = $this->what_paket($this->comid());
            $max = $paket['jumlah_lokasi'];

            $jumlah_lokasi = Lokasi::where('comid', $this->comid())->where('is_active', 1)->count();
            if ($jumlah_lokasi >= $max) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah lokasi sudah melebihi quota paket anda, silahkan upgrade paket anda untuk menambah jumlah lokasi !!',
                ]);
            }
        }

        // toggle aktif/nonaktif
        $data->is_active = $data->is_active ? 0 : 1;
        $data->save();

        $message = $data->is_active ? 'Data berhasil diaktifkan.' : 'Data berhasil dinonaktifkan.';

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function download_qrcode($id)
    {
        $lokasi = Lokasi::find($id);
        $text = $lokasi->qrcode; // teks/link dinamis kamu
        $filename = 'qrcode_' . $lokasi->nama_lokasi . '.png'; // bisa diganti sesuai kebutuhan

        $qr = QrCode::format('png')->size(300)->margin(2)->errorCorrection('H')->generate($text);

        return response($qr)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
