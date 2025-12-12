<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use Illuminate\Http\Request;

class NotifController extends Controller
{
    public function index(Request $request) {
        $input = $request->all();
        $query = Broadcast::with('user')->where('comid', $input['comid'])->orderBy('id','desc')->get();

        $data = [];
        foreach($query as $key) {
            $row['id'] = $key->id;
            $row['judul']= $key->judul;
            $row['pesan'] = substr($key->pesan, 0, 100).'...';
            $row['pengirim'] = $key->user->name ?? '';
            $row['waktu'] = date('d F Y - H:i', strtotime($key->created_at));
            $row['image'] = $key->image;
            array_push($data, $row);
        }

        return response()->json([
            "success" => true,
            "data" => $data
        ]);

    }
}
