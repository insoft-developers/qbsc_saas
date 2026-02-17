<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Notifikasi;
use App\Services\FcmService;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Yajra\DataTables\Facades\DataTables;

class AdminNotifikasiController extends Controller
{
    public function index()
    {
        $view = 'notifikasi';
        $companies = Company::orderBy('id', 'asc')->get();
        return view('admin.notifikasi.notifikasi', compact('view', 'companies'));
    }

    public function table(Request $request)
    {
        if ($request->ajax()) {
            $data = Notifikasi::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    return $row->image == null ? '' : '<img style="width:50px;border-radius:4px;" src="' . asset('storage/' . $row->image) . '">';
                })
                ->addColumn('pesan', function ($row) {
                    return '<div style="white-space:normal;width:300px;">' . $row->pesan . '</div>';
                })

                ->addColumn('created_at', function ($row) {
                    return $row->created_at == null ? '' : date('d-m-Y', strtotime($row->created_at));
                })

                ->addColumn('comid', function ($row) {
                    return $row->comid == -1 ? $row->company_name : $row->company->company_name ?? '';
                })
                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
                    $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                    $button .= '<button onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';

                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action', 'image', 'is_active', 'pesan'])
                ->make(true);
        }
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
        $request->validate([
            'pengirim' => 'required',
            'judul' => 'required',
            'pesan' => 'required',
            'image' => 'nullable',
            'comid' => 'required',
        ]);

        $path = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Gunakan Intervention Image
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file->getRealPath());

            // Resize otomatis jika terlalu besar
            if ($image->width() > 1280) {
                $image->scale(width: 1280);
            }

            // Tentukan folder penyimpanan
            $folder = storage_path('app/public/notifikasi');
            if (!file_exists($folder)) {
                mkdir($folder, 0755, true);
            }

            // Buat nama unik
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $savePath = $folder . '/' . $filename;

            // Simpan langsung (sekali saja)
            $image->save($savePath, 80); // kualitas 80% untuk kompres <1MB

            // Simpan path relatif ke database
            $path = 'notifikasi/' . $filename;
        }

        $input['image'] = $path;
        Notifikasi::create($input);
        $this->notifikasi($input['comid'], $input['judul'], $input['pesan']);
        return response()->json([
            "success" => true,
            "message" => "Sukses"
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
    public function edit(string $id) {
        return Notifikasi::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {
        $data = Notifikasi::find($id);

        $input = $request->all();
        $request->validate([
            'pengirim' => 'required',
            'judul' => 'required',
            'pesan' => 'required',
            'image' => 'nullable',
            'comid' => 'required',
        ]);

        $path = $data->image;
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Gunakan Intervention Image
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file->getRealPath());

            // Resize otomatis jika terlalu besar
            if ($image->width() > 1280) {
                $image->scale(width: 1280);
            }

            // Tentukan folder penyimpanan
            $folder = storage_path('app/public/notifikasi');
            if (!file_exists($folder)) {
                mkdir($folder, 0755, true);
            }

            // Buat nama unik
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $savePath = $folder . '/' . $filename;

            // Simpan langsung (sekali saja)
            $image->save($savePath, 80); // kualitas 80% untuk kompres <1MB

            // Simpan path relatif ke database
            $path = 'notifikasi/' . $filename;
        }

        $input['image'] = $path;
        $data->update($input);
        return response()->json([
            "success" => true,
            "message" => "Sukses"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        return Notifikasi::destroy($id);
    }

    public function notifikasi($comid, $judul, $pesan)
    {
        $topic = $comid == -1 ? 'qbsc_all' : 'qbsc_bos_' . $comid;
        $title = $judul;
        $body = $pesan;
        $this->send($topic, $title, $body);
    }

    protected function send($topic, $title, $body)
    {
        $fcm = new FcmService();
        return $fcm->sendToTopic($topic, $title, $body, [
            'comid' => $topic,
        ]);
    }
}
