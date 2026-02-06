<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AbsenLocation;
use App\Models\Absensi;
use App\Models\JamShift;
use App\Models\Satpam;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class AbsenController extends Controller
{
    public function verifyFace(Request $request)
    {
        $face_url = config('services.face_api.url');

        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:5120', 'dimensions:max_width=4000,max_height=4000'],
            'user_id' => 'required|integer',
            'absen_model' => 'required',
        ]);

        $userId = $request->user_id;
        $file = $request->file('image');
        $model = $request->absen_model;
        $jamSekarang = date('H:i:s');
        // $jamSekarang = '08:30:30';

        $now = Carbon::now();
        $myShift = JamShift::find($request->shift_id);

        if ($model == 'masuk') {
            $jamMasukAwal = Carbon::parse($myShift->jam_masuk_awal)->setDateFrom($now);
            $jamMasukAkhir = Carbon::parse($myShift->jam_masuk_akhir)->setDateFrom($now);
            $jamMasukUser = Carbon::parse($jamSekarang)->setDateFrom($now);

            if ($jamMasukAkhir->lt($jamMasukAwal)) {
                $jamMasukAkhir->addDay();

                if ($jamMasukUser->lt($jamMasukAwal)) {
                    $selisihJam = $jamMasukUser->diffInHours($jamMasukAwal);
                    if ($selisihJam > 6) {
                        $jamMasukUser->addDay();
                    }
                }
            }

            if ($jamMasukUser->lt($jamMasukAwal)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Absen gagal, absen masuk diperbolehkan mulai pukul ' . $myShift->jam_masuk_awal,
                ]);
            }

            if ($jamMasukUser->gt($jamMasukAkhir)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Absen gagal, absen masuk diperbolehkan paling lambat pukul ' . $myShift->jam_masuk_akhir,
                ]);
            }
        } else {
            $jamPulang = date('H:i:s');
            $jamPulangAwal = Carbon::parse($myShift->jam_pulang_awal)->setDateFrom($now);
            $jamPulangAkhir = Carbon::parse($myShift->jam_pulang_akhir)->setDateFrom($now);
            $jamPulangUser = Carbon::parse($jamPulang)->setDateFrom($now);

            if ($jamPulangAkhir->lt($jamPulangAwal)) {
                $jamPulangAkhir->addDay();

                if ($jamPulangUser->lt($jamPulangAwal)) {
                    $selisihJam = $jamPulangUser->diffInHours($jamPulangAwal);
                    if ($selisihJam > 6) {
                        $jamPulangUser->addDay();
                    }
                }
            }

            if ($jamPulangUser->lt($jamPulangAwal)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Absen gagal, absen pulang diperbolehkan mulai pukul ' . $myShift->jam_pulang_awal,
                ]);
            }

            if ($jamPulangUser->gt($jamPulangAkhir)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Absen gagal, absen pulang diperbolehkan paling lambat pukul ' . $myShift->jam_pulang_akhir,
                ]);
            }
        }

        $user = Satpam::find($userId);
        if (!$user || !$user->face_embedding) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'User tidak ditemukan atau belum punya embedding wajah.',
                ],
                404,
            );
        }

        $storedEmbedding = $user->face_embedding;

        $response = Http::attach('image', file_get_contents($file->getRealPath()), $file->getClientOriginalName())->post($face_url . '/verify', [
            'stored_embedding' => json_encode(json_decode($storedEmbedding)),
        ]);

        if ($response->failed()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal terhubung ke server Face API',
                ],
                500,
            );
        }

        $result = $response->json();

        $matched = $result['matched'] ?? false;
        $distance = $result['distance'] ?? null;
        $tanggal = date('Y-m-d');
        $jam = date('Y-m-d H:i:s');
        if ($matched) {
            if ($model == 'masuk') {
                $myShift = JamShift::find($request->shift_id);
                $shift_id = $myShift->id ?? null;
                $nama_shift = $myShift->name ?? null;
                $jam_masuk_shift = $myShift->jam_masuk ?? null;
                $jam_pulang_shift = $myShift->jam_pulang ?? null;

                $pathMasuk = null;

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
                    $folder = storage_path('app/public/absensi');
                    if (!file_exists($folder)) {
                        mkdir($folder, 0777, true);
                    }

                    // Buat nama unik
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $savePath = $folder . '/' . $filename;

                    // Simpan langsung (sekali saja)
                    $image->save($savePath, 80); // kualitas 80% untuk kompres <1MB

                    // Simpan path relatif ke database
                    $pathMasuk = 'absensi/' . $filename;
                }

                Absensi::create([
                    'tanggal' => $tanggal,
                    'satpam_id' => $userId,
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'jam_masuk' => $jam,
                    'shift_id' => $shift_id,
                    'shift_name' => $nama_shift,
                    'jam_setting_masuk' => $jam_masuk_shift,
                    'jam_setting_pulang' => $jam_pulang_shift,
                    'status' => 1,
                    'description' => 'Absensi Berhasil',
                    'comid' => $user->comid,
                    'foto_masuk' => $pathMasuk,
                ]);

                Satpam::where('id', $userId)->update([
                    'last_latitude' => $request->latitude,
                    'last_longitude' => $request->longitude,
                    'last_seen_at' => now(),
                ]);

                $message = 'Absensi masuk berhasil.';
            } else {
                $absensi = Absensi::where('satpam_id', $userId)->where('status', 1)->whereNull('jam_keluar')->orderBy('id', 'desc')->first();

                $last_shift_id = $absensi->shift_id;
                if ($last_shift_id != $request->shift_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Shift Kerja Pulang tidak cocok dengan Shift Kerja Masuk',
                    ]);
                }

                if ($absensi) {
                    $pathPulang = null;

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
                        $folder = storage_path('app/public/absensi');
                        if (!file_exists($folder)) {
                            mkdir($folder, 0777, true);
                        }

                        // Buat nama unik
                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                        $savePath = $folder . '/' . $filename;

                        // Simpan langsung (sekali saja)
                        $image->save($savePath, 80); // kualitas 80% untuk kompres <1MB

                        // Simpan path relatif ke database
                        $pathPulang = 'absensi/' . $filename;
                    }

                    $absensi->jam_keluar = date('Y-m-d H:i:s');
                    $absensi->status = 2;
                    $absensi->latitude2 = $request->latitude;
                    $absensi->longitude2 = $request->longitude;
                    $absensi->foto_pulang = $pathPulang;
                    $absensi->save();
                    $message = 'Absensi pulang berhasil';

                    Satpam::where('id', $userId)->update([
                        'last_latitude' => $request->latitude,
                        'last_longitude' => $request->longitude,
                        'last_seen_at' => now(),
                    ]);
                } else {
                    $message = 'Belum ada absen masuk';
                }
            }

            return response()->json([
                'success' => true,
                'matched' => true,
                'distance' => $distance,
                'message' => $message,
            ]);
        }

        return response()->json(['success' => false, 'matched' => false, 'distance' => $distance, 'message' => 'Wajah tidak cocok. Absensi gagal.']);
    }

    public function absenActive(Request $request)
    {
        $input = $request->all();

        $data = Absensi::where('satpam_id', $input['satpam_id'])->orderBy('id', 'desc')->first();
        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'success' => true,
                'data' => [
                    'tanggal' => date('Y-m-d H:i:s'),
                    'jam_masuk' => null,
                    'jam_keluar' => null,
                    'status' => null,
                ],
            ]);
        }
    }

    public function getDataShift(Request $request)
    {
        $input = $request->all();
        $data = JamShift::where('comid', $input['comid'])->get();
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function laporan_absensi(Request $request)
    {
        $limit = (int) $request->query('limit', 20);

        $query = Absensi::with(['satpam:id,name', 'company:id,company_name'])->where('satpam_id', $request->satpam_id);
        $data = $query->orderBy('jam_masuk', 'desc')->paginate($limit);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function update_pos_satpam(Request $request)
    {
        $validated = $request->validate([
            'comid' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        try {
            AbsenLocation::where('comid', $request->comid)->update([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Update lokasi Pos Absen Berhasil',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Gangguan Server/Offline Mode',
            ]);
        }
    }
}
