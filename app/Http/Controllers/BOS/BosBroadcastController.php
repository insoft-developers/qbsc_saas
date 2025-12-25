<?php

namespace App\Http\Controllers\BOS;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use App\Models\Company;
use App\Services\FcmService;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class BosBroadcastController extends Controller
{
    use CommonTrait;

    public function index(Request $request)
    {
        $limit = (int) $request->query('limit', 20);

        $query = Broadcast::with(['user', 'company'])->where('comid', $request->comid);

        // 🔍 FILTER TANGGAL
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = Carbon::parse($request->start_date)->startOfDay();
            $end = Carbon::parse($request->end_date)->endOfDay();
            $query->whereBetween('created_at', [$start, $end]);
        }

        $data = $query->orderBy('created_at', 'desc')->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function add(Request $request)
    {
        $input = $request->all();
        $comid = $input['comid'];
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png',
            'judul' => 'required|string|max:100',
            'pesan' => 'required',
        ]);
        $company = Company::find($comid);
        $paket = $this->what_paket($comid);
        if ($paket['is_broadcast'] !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Silahkan upgrade paket anda untuk bisa membuat broadcast pesan.!!',
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
            $folder = storage_path('app/public/broadcast');
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            // Buat nama unik
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $savePath = $folder . '/' . $filename;

            // Simpan langsung (sekali saja)
            $image->save($savePath, 80); // kualitas 80% untuk kompres <1MB

            // Simpan path relatif ke database
            $path = 'broadcast/' . $filename;
        }

        // Simpan ke database
        $input['image'] = $path;
        $broadcast = Broadcast::create($input);
        if ($broadcast) {
            $topic = 'qbsc_satpam_' . $comid;
            $title = $input['judul'];
            $body = $input['pesan'];
            $this->send($topic, $title, $body);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.',
        ]);
    }

    protected function send($topic, $title, $body)
    {
        $fcm = new FcmService();
        return $fcm->sendToTopic($topic, $title, $body, [
            'comid' => $topic,
        ]);
    }

    public function delete(Request $request)
    {
        $broadcast = Broadcast::find($request->id);

        if (!$broadcast) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Data tidak ditemukan',
                ],
                404,
            );
        }

        // 🔥 Hapus foto jika ada
        if ($broadcast->image && Storage::disk('public')->exists($broadcast->image)) {
            Storage::disk('public')->delete($broadcast->image);
        }

        // 🔥 Hapus data
        $broadcast->delete();

        return response()->json([
            'success' => true,
            'message' => 'Broadcast & foto berhasil dihapus',
        ]);
    }
}
