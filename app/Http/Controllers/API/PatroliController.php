<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DocChick;
use App\Models\Ekspedisi;
use App\Models\Kandang;
use App\Models\KandangAlarm;
use App\Models\KandangKipas;
use App\Models\KandangLampu;
use App\Models\KandangSuhu;
use App\Models\Lokasi;
use App\Models\Mesin;
use App\Models\Patroli;
use App\Models\PatroliKandang;
use App\Models\Satpam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
class PatroliController extends Controller
{
    public function sendPatrolitoServer(Request $request)
    {
        $request->validate([
            'id' => 'required|uuid',
            'tanggal' => 'required|date',
            'jam' => 'required',
            'location_id' => 'required',
            'location_code' => 'required',
            'satpam_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'note' => 'nullable|string',
            'comid' => 'required',
            'photo' => 'nullable|file|mimes:jpg,jpeg,png', // tidak batasi ukuran
        ]);

        try {
            $photoPath = null;

            if ($request->hasFile('photo')) {
                $file = $request->file('photo');

                // Gunakan Intervention Image
                $manager = new ImageManager(new Driver());
                $image = $manager->read($file->getRealPath());

                // Resize otomatis jika terlalu besar
                if ($image->width() > 1280) {
                    $image->scale(width: 1280);
                }

                // Tentukan folder penyimpanan
                $folder = storage_path('app/public/patroli');
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }

                // Buat nama unik
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $savePath = $folder . '/' . $filename;

                // Simpan langsung (sekali saja)
                $image->save($savePath, 80); // kualitas 80% untuk kompres <1MB

                // Simpan path relatif ke database
                $photoPath = 'patroli/' . $filename;
            }

            $lokasi = Lokasi::find($request->location_id);

            $patroli = Patroli::create([
                'uuid' => $request->id,
                'tanggal' => $request->tanggal,
                'jam' => $request->jam,
                'jam_awal' => $lokasi->jam_awal,
                'jam_akhir' => $lokasi->jam_akhir,
                'location_id' => $request->location_id,
                'location_code' => $request->location_code,
                'satpam_id' => $request->satpam_id,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'note' => $request->note,
                'comid' => $request->comid,
                'photo_path' => $photoPath,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data patroli tersimpan',
                'data' => $patroli,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal simpan data',
                'error' => $th->getMessage(),
            ]);
        }
    }


    public function patroliKandangToServer(Request $request) {
        $request->validate([
            'uuid' => 'required|uuid',
            'tanggal' => 'required|date',
            'jam' => 'required',
            'kandang_id' => 'required',
            'satpam_id' => 'required',
            'std_temp' => 'nullable',
            'temperature' => 'nullable',
            'fan_amount' => 'nullable',
            'kipas' => 'nullable',
            'is_alarm_on' => 'nullable',
            'is_lamp_on' => 'nullable',
            'note' => 'nullable|string',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'comid' => 'required',
            'foto' => 'nullable|file|mimes:jpg,jpeg,png', // tidak batasi ukuran
        ]);

        try {
            $photoPath = null;

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
                $folder = storage_path('app/public/kandang');
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }

                // Buat nama unik
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $savePath = $folder . '/' . $filename;

                // Simpan langsung (sekali saja)
                $image->save($savePath, 80); // kualitas 80% untuk kompres <1MB

                // Simpan path relatif ke database
                $photoPath = 'kandang/' . $filename;
            }

            $patroli = PatroliKandang::create([
                "uuid" => $request->uuid,
                "tanggal" => $request->tanggal,
                "jam"=> $request->jam,
                "kandang_id" => $request->kandang_id,
                "satpam_id" => $request->satpam_id,
                "std_temp" => $request->std_temp,
                "temperature" => $request->temperature,
                "fan_amount" => $request->fan_amount,
                "kipas" => $request->kipas,
                "is_alarm_on" => $request->is_alarm_on,
                "is_lamp_on" => $request->is_lamp_on,
                "note" => $request->note,
                "foto" => $photoPath,
                "latitude" => $request->latitude,
                "longitude" => $request->longitude,
                "comid" => $request->comid
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data patroli tersimpan',
                'data' => $patroli,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal simpan data',
                'error' => $th->getMessage(),
            ]);
        }
    }

    public function getDataKandang(Request $request) {
        $request->validate([
            "comid" => 'required'
        ]);

        $data = Kandang::where('comid', $request->comid)->get();
        return response()->json([
            "success" => true,
            "data" => $data
        ]);
    }


    public function getDataEkspedisi(Request $request) {
        $request->validate([
            "comid" => 'required'
        ]);

        $data = Ekspedisi::where('comid', $request->comid)->get();
        return response()->json([
            "success" => true,
            "data" => $data
        ]);
    }


    public function getDataMesin(Request $request) {
        $request->validate([
            "comid" => 'required'
        ]);

        $data = Mesin::where('comid', $request->comid)->get();
        return response()->json([
            "success" => true,
            "data" => $data
        ]);
    }

    public function syncSuhuKandang(Request $request) {
        $request->validate([
            'uuid' => 'required|uuid',
            'tanggal' => 'required|date',
            'jam' => 'required',
            'kandang_id' => 'required',
            'satpam_id' => 'required',
            'std_temp' => 'nullable',
            'temperature' => 'nullable',
            'note' => 'nullable|string',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'comid' => 'required',
            'foto' => 'nullable|file|mimes:jpg,jpeg,png', // tidak batasi ukuran
        ]);

        try {
            $photoPath = null;

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
                $folder = storage_path('app/public/kandang');
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }

                // Buat nama unik
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $savePath = $folder . '/' . $filename;

                // Simpan langsung (sekali saja)
                $image->save($savePath, 80); // kualitas 80% untuk kompres <1MB

                // Simpan path relatif ke database
                $photoPath = 'kandang/' . $filename;
            }

            $patroli = KandangSuhu::create([
                "uuid" => $request->uuid,
                "tanggal" => $request->tanggal,
                "jam"=> $request->jam,
                "kandang_id" => $request->kandang_id,
                "satpam_id" => $request->satpam_id,
                "std_temp" => $request->std_temp,
                "temperature" => $request->temperature,
                "note" => $request->note,
                "foto" => $photoPath,
                "latitude" => $request->latitude,
                "longitude" => $request->longitude,
                "comid" => $request->comid
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data patroli tersimpan',
                'data' => $patroli,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal simpan data',
                'error' => $th->getMessage(),
            ]);
        }
    }


    public function syncKipasKandang(Request $request) {
        $request->validate([
            'uuid' => 'required|uuid',
            'tanggal' => 'required|date',
            'jam' => 'required',
            'kandang_id' => 'required',
            'satpam_id' => 'required',
            'kipas' => 'nullable',
            'note' => 'nullable|string',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'comid' => 'required',
            'foto' => 'nullable|file|mimes:jpg,jpeg,png', // tidak batasi ukuran
        ]);

        try {
            $photoPath = null;

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
                $folder = storage_path('app/public/kandang');
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }

                // Buat nama unik
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $savePath = $folder . '/' . $filename;

                // Simpan langsung (sekali saja)
                $image->save($savePath, 80); // kualitas 80% untuk kompres <1MB

                // Simpan path relatif ke database
                $photoPath = 'kandang/' . $filename;
            }

            $patroli = KandangKipas::create([
                "uuid" => $request->uuid,
                "tanggal" => $request->tanggal,
                "jam"=> $request->jam,
                "kandang_id" => $request->kandang_id,
                "satpam_id" => $request->satpam_id,
                "kipas" => $request->kipas,
                "note" => $request->note,
                "foto" => $photoPath,
                "latitude" => $request->latitude,
                "longitude" => $request->longitude,
                "comid" => $request->comid
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data patroli tersimpan',
                'data' => $patroli,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal simpan data',
                'error' => $th->getMessage(),
            ]);
        }
    }


    public function syncAlarmKandang(Request $request) {
        $request->validate([
            'uuid' => 'required|uuid',
            'tanggal' => 'required|date',
            'jam' => 'required',
            'kandang_id' => 'required',
            'satpam_id' => 'required',
            'is_alarm_on' => 'nullable',
            'note' => 'nullable|string',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'comid' => 'required',
            'foto' => 'nullable|file|mimes:jpg,jpeg,png', // tidak batasi ukuran
        ]);

        try {
            $photoPath = null;

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
                $folder = storage_path('app/public/kandang');
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }

                // Buat nama unik
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $savePath = $folder . '/' . $filename;

                // Simpan langsung (sekali saja)
                $image->save($savePath, 80); // kualitas 80% untuk kompres <1MB

                // Simpan path relatif ke database
                $photoPath = 'kandang/' . $filename;
            }

            $patroli = KandangAlarm::create([
                "uuid" => $request->uuid,
                "tanggal" => $request->tanggal,
                "jam"=> $request->jam,
                "kandang_id" => $request->kandang_id,
                "satpam_id" => $request->satpam_id,
                "is_alarm_on" => $request->is_alarm_on ? 1 : 0,
                "note" => $request->note,
                "foto" => $photoPath,
                "latitude" => $request->latitude,
                "longitude" => $request->longitude,
                "comid" => $request->comid
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data patroli tersimpan',
                'data' => $patroli,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal simpan data',
                'error' => $th->getMessage(),
            ]);
        }
    }


    public function syncLampuKandang(Request $request) {
        $request->validate([
            'uuid' => 'required|uuid',
            'tanggal' => 'required|date',
            'jam' => 'required',
            'kandang_id' => 'required',
            'satpam_id' => 'required',
            'is_lamp_on' => 'nullable',
            'note' => 'nullable|string',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'comid' => 'required',
            'foto' => 'nullable|file|mimes:jpg,jpeg,png', // tidak batasi ukuran
        ]);

        try {
            $photoPath = null;

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
                $folder = storage_path('app/public/kandang');
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }

                // Buat nama unik
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $savePath = $folder . '/' . $filename;

                // Simpan langsung (sekali saja)
                $image->save($savePath, 80); // kualitas 80% untuk kompres <1MB

                // Simpan path relatif ke database
                $photoPath = 'kandang/' . $filename;
            }

            $patroli = KandangLampu::create([
                "uuid" => $request->uuid,
                "tanggal" => $request->tanggal,
                "jam"=> $request->jam,
                "kandang_id" => $request->kandang_id,
                "satpam_id" => $request->satpam_id,
                "is_lamp_on" => $request->is_lamp_on ? 1 : 0,
                "note" => $request->note,
                "foto" => $photoPath,
                "latitude" => $request->latitude,
                "longitude" => $request->longitude,
                "comid" => $request->comid
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data patroli tersimpan',
                'data' => $patroli,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal simpan data',
                'error' => $th->getMessage(),
            ]);
        }
    }

    public function syncDocReport(Request $request) {
        $input = $request->all();
        $request->validate([
            'uuid' => 'required|uuid',
            'tanggal' => 'required|date',
            'jam' => 'required',
            'satpam_id' => 'required',
            'jumlah' => 'required',
            'ekspedisi_id' => 'required',
            'tujuan' => 'nullable',
            'no_polisi' => 'nullable',
            'jenis' => 'required',
            'note' => 'nullable|string',
            'comid' => 'required',
            'input_date' => 'required',
            'foto' => 'nullable|file|mimes:jpg,jpeg,png', // tidak batasi ukuran
        ]);

        try {
            $photoPath = null;

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
                $folder = storage_path('app/public/doc');
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }

                // Buat nama unik
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $savePath = $folder . '/' . $filename;

                // Simpan langsung (sekali saja)
                $image->save($savePath, 80); // kualitas 80% untuk kompres <1MB

                // Simpan path relatif ke database
                $photoPath = 'doc/' . $filename;
            }

            $input['foto'] = $photoPath;
            $doc = DocChick::create($input);

            return response()->json([
                'success' => true,
                'message' => 'Data Doc tersimpan',
                'data' => $doc,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal simpan data',
                'error' => $th->getMessage(),
            ]);
        }
    }
}