<?php

namespace App\Http\Controllers\BOS;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Tamu;
use App\Models\User;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class BosTamuController extends Controller
{
    use CommonTrait;
    public function index(Request $request)
    {
        $limit = (int) $request->query('limit', 20);

        $query = Tamu::with(['satpam', 'company', 'user', 'satpam_pulang'])->where('comid', $request->comid);

        // 🔍 FILTER TANGGAL
        if ($request->filled('start_date') && $request->filled('end_date')) {
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

        $data = $query->orderBy('created_at', 'desc')->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function user(Request $request) {
        $comid = $request->comid;

        $data = User::where('company_id', $comid)->get();

        return response()->json([
            "success" => true,
            "data" => $data
        ]);

    }

    public function add(Request $request) {
        $input = $request->all();
        $validated = $request->validate([
            'nama_tamu' => 'required|string|max:100',
            'jumlah_tamu' => 'required',
            'tujuan' => 'required',
            'whatsapp' => 'nullable',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $paket = $this->what_paket($input['comid']);
        $company = Company::find($input['comid']);
        if($paket['is_scan_tamu'] !== 1) {
            return response()->json([
                "sucess" => false,
                "message" => 'Paket anda saat ini hanya mengizinkan anda untuk menambah data tamu via aplikasi satpam secara manual, Silahkan upgrade paket anda untuk bisa membuat qr tamu dan scan di aplikasi satpam..!!'
            ]);
        }


        if ($company->is_active !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Paket tidak aktif, silahkan aktifkan paket anda untuk membuat broadcast',
            ]);
        }

        if ($company->expired_date && Carbon::now()->gt($company->expired_date)) {
            return response()->json([
                'success' => false,
                'message' => 'Paket Anda Expired, silahkan perbarui paket anda untuk membuat broadcast',
            ]);
        }
        // Simpan foto ke storage
        $path = null;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');

            // Gunakan Intervention Image
            $manager = new ImageManager(new Driver());
            $image = $manager->read($file->getRealPath());

            // Resize otomatis jika terlalu besar
            if ($image->width() > 1280) {
                $image->scale(width: 1280);
            }

            // Tentukan folder penyimpanan
            $folder = storage_path('app/public/tamu');
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            // Buat nama unik
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $savePath = $folder . '/' . $filename;

            // Simpan langsung (sekali saja)
            $image->save($savePath, 80); // kualitas 80% untuk kompres <1MB

            // Simpan path relatif ke database
            $path = 'tamu/' . $filename;
        }

        // Simpan ke database
        $input['foto'] = $path;
        $input['uuid'] = Str::uuid();
        $input['is_status'] = 1;
        Tamu::create($input);
        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.',
        ]);
    }

    public function delete(Request $request)
    {
        $tamu = Tamu::find($request->id);

        if (!$tamu) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ],
                404,
            );
        }

        // 🔥 Hapus foto jika ada
        if ($tamu->foto && Storage::disk('public')->exists($tamu->foto)) {
            Storage::disk('public')->delete($tamu->foto);
        }

        // 🔥 Hapus data
        $tamu->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data Tamu & foto berhasil dihapus',
        ]);
    }
}
