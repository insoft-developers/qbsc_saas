<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use App\Models\User;
use App\Services\FcmService;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BroadcastController extends Controller
{
    use CommonTrait;
    public function broadcast_table(Request $request)
    {
        if ($request->ajax()) {
            $comid = $this->comid();
            $data = Broadcast::where('comid', $comid)->with('company:id,company_name','user:id,name');
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('created_at', function($row){
                    return date('d-m-Y H:i', strtotime($row->created_at));
                })
                ->addColumn('comid', function ($row) {
                    return $row->company->company_name ?? '-';
                })
                ->addColumn('pengirim', function ($row) {
                    return $row->user->name ?? '-';
                })
                ->addColumn('image', function ($row) {
                    if (!empty($row->image)) {
                        $url = asset('storage/' . $row->image);
                        return '<a href="' . asset('storage/' . $row->image) . '" target="_blank"><img  style="cursor:pointer;" src="' . $url . '" alt="Foto" width="50" height="50" class="rounded-circle border"></a>';
                    } else {
                        return '<center>-</center>';
                    }
                })

                ->addColumn('pesan', function($row){
                    return '<div style="white-space:normal;width:250px;">'.$row->pesan.'</div>';
                })

                ->addColumn('send_status', function($row){
                    if($row->send_status == 1) {
                        return '<span class="badge bg-success">Terkirim</span>';
                    } else {
                        return '<span class="badge bg-danger">Menunggu</span>';
                    }
                })

                ->addColumn('action', function ($row) {
                    $button = '';
                    $button .= '<center>';
                    if (Auth::user()->level == 'owner') {
                        $button .= '<button onclick="editData(' . $row->id . ')" title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                        $button .= '<button onclick="deleteData(' . $row->id . ')" title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    } else {
                        $button .= '<button disabled title="Edit Data" class="me-0 btn btn-insoft btn-warning"><i class="bi bi-pencil-square"></i></button>';
                        $button .= '<button disabled title="Hapus Data" class="btn btn-insoft btn-danger"><i class="bi bi-trash3"></i></button>';
                    }

                    $button .= '</center>';
                    return $button;
                })
                ->rawColumns(['action', 'image', 'send_status','pesan'])
                ->make(true);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'broadcast';
        return view('frontend.broadcast.broadcast', compact('view'));
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'judul' => 'required|string|max:100',
            'pesan' => 'required',
        ]);

        $paket = $this->what_paket($this->comid());
        if($paket['is_broadcast'] !== 1) {
            return response()->json([
                "success" => false,
                "message" => "Silahkan upgrade paket anda untuk bisa membuat broadcast pesan.!!"
            ]);
        }

        // Simpan foto ke storage
        $path = null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('broadcast', 'public');
        }

        // Simpan ke database
        $input['image'] = $path;
        $input['pengirim'] = Auth::user()->id;
        $input['comid'] = $this->comid();
        $broadcast = Broadcast::create($input);
        if($broadcast) {
            $topic = "qbsc_satpam_".$this->comid();
            $title = $input['judul'];
            $body = $input['pesan'];
            $this->send($topic, $title, $body);
        }


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
        return Broadcast::find($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();
        
        $data = Broadcast::findOrFail($id);

        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'judul' => 'required|string|max:100',
            'pesan' => 'required',
        ]);

        $path = $data->image;

        // Jika ada foto baru diupload
        if ($request->hasFile('image')) {
            // Hapus foto lama jika ada
            if ($data->image && Storage::disk('public')->exists($data->image)) {
                Storage::disk('public')->delete($data->image);
            }

            // Upload foto baru
            $path = $request->file('image')->store('broadcast', 'public');
        }

        $input['image'] = $path;
        $input['pengirim'] = Auth::user()->id;
        $input['comid'] = $this->comid();

        $data->update($input);
        // Update data user
        
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return Broadcast::destroy($id);
    }


    protected function send($topic, $title, $body)
    {
        $fcm = new FcmService; 
        return $fcm->sendToTopic($topic, $title, $body, [
            "comid" => $topic
        ]);
    }
}
