<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DocBoxOption;
use App\Models\DocChick;
use App\Models\Ekspedisi;
use App\Models\JadwalPatroli;
use App\Models\JadwalPatroliDetail;
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
            'jam_awal_patroli' => 'nullable',
            'jam_akhir_patroli' => 'nullable'
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
                    mkdir($folder, 0755, true);
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
                'jam_awal_patroli' => $request->jam_awal_patroli,
                'jam_akhir_patroli' => $request->jam_akhir_patroli
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
                'error' => 'Gangguan Server/Offline Mode',
            ]);
        }
    }

    public function patroliKandangToServer(Request $request)
    {
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
                    mkdir($folder, 0755, true);
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
                'uuid' => $request->uuid,
                'tanggal' => $request->tanggal,
                'jam' => $request->jam,
                'kandang_id' => $request->kandang_id,
                'satpam_id' => $request->satpam_id,
                'std_temp' => $request->std_temp,
                'temperature' => $request->temperature,
                'fan_amount' => $request->fan_amount,
                'kipas' => $request->kipas,
                'is_alarm_on' => $request->is_alarm_on,
                'is_lamp_on' => $request->is_lamp_on,
                'note' => $request->note,
                'foto' => $photoPath,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'comid' => $request->comid,
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
                'error' => 'Gangguan Server/Offline Mode',
            ]);
        }
    }

    public function getDataKandang(Request $request)
    {
        $request->validate([
            'comid' => 'required',
        ]);

        $data = Kandang::where('comid', $request->comid)->where('is_active', 1)->get();
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function getDataEkspedisi(Request $request)
    {
        $request->validate([
            'comid' => 'required',
        ]);

        $data = Ekspedisi::where('comid', $request->comid)->get();
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }


    public function getDataJenisBox(Request $request)
    {
        $request->validate([
            'comid' => 'required',
        ]);

        $data = DocBoxOption::where('comid', $request->comid)->get();
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function getDataMesin(Request $request)
    {
        $request->validate([
            'comid' => 'required',
        ]);

        $data = Mesin::where('comid', $request->comid)->get();
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function syncSuhuKandang(Request $request)
    {
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
                    mkdir($folder, 0755, true);
                }

                // Buat nama unik
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $savePath = $folder . '/' . $filename;

                // Simpan langsung (sekali saja)
                $image->save($savePath, 80); // kualitas 80% untuk kompres <1MB

                // Simpan path relatif ke database
                $photoPath = 'kandang/' . $filename;
            }

            $hh = Kandang::find($request->kandang_id);
            $comid = $hh->comid;

            $patroli = KandangSuhu::create([
                'uuid' => $request->uuid,
                'tanggal' => $request->tanggal,
                'jam' => $request->jam,
                'kandang_id' => $request->kandang_id,
                'satpam_id' => $request->satpam_id,
                'std_temp' => $request->std_temp,
                'temperature' => $request->temperature,
                'note' => $request->note,
                'foto' => $photoPath,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'comid' => $comid,
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
                'error' => 'Gangguan Server/Offline Mode',
            ]);
        }
    }

    public function syncKipasKandang(Request $request)
    {
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
                    mkdir($folder, 0755, true);
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
                'uuid' => $request->uuid,
                'tanggal' => $request->tanggal,
                'jam' => $request->jam,
                'kandang_id' => $request->kandang_id,
                'satpam_id' => $request->satpam_id,
                'kipas' => $request->kipas,
                'note' => $request->note,
                'foto' => $photoPath,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'comid' => $request->comid,
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
                'error' => 'Gangguan Server/Offline Mode',
            ]);
        }
    }

    public function syncAlarmKandang(Request $request)
    {
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
                    mkdir($folder, 0755, true);
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
                'uuid' => $request->uuid,
                'tanggal' => $request->tanggal,
                'jam' => $request->jam,
                'kandang_id' => $request->kandang_id,
                'satpam_id' => $request->satpam_id,
                'is_alarm_on' => $request->is_alarm_on ? 1 : 0,
                'note' => $request->note,
                'foto' => $photoPath,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'comid' => $request->comid,
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
                'error' => 'Gangguan Server/Offline Mode',
            ]);
        }
    }

    public function syncLampuKandang(Request $request)
    {
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
                    mkdir($folder, 0755, true);
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
                'uuid' => $request->uuid,
                'tanggal' => $request->tanggal,
                'jam' => $request->jam,
                'kandang_id' => $request->kandang_id,
                'satpam_id' => $request->satpam_id,
                'is_lamp_on' => $request->is_lamp_on ? 1 : 0,
                'note' => $request->note,
                'foto' => $photoPath,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'comid' => $request->comid,
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
                'error' => 'Gangguan Server/Offline Mode',
            ]);
        }
    }

    public function syncDocReport(Request $request)
    {
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
            'total_ekor' => 'nullable',
            'nama_supir' => 'nullable',
            'nomor_segel' => 'nullable',
            'doc_box_option' => 'nullable|string',
            'foto.*' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);


        $doc = DocChick::where('uuid', $request->uuid)->first();
        if($doc) {
            return response()->json([
                "success" => false,
                 "message" => "Data sudah pernah di sync"   
            ]);
        }

        try {
            
            $photoPaths = [];

            // =========================
            // MULTI FOTO + KOMPRES
            // =========================
            if ($request->hasFile('foto')) {
                $manager = new ImageManager(new Driver());

                foreach ($request->file('foto') as $file) {
                    $image = $manager->read($file->getRealPath());

                    if ($image->width() > 1280) {
                        $image->scale(width: 1280);
                    }

                    $folder = storage_path('app/public/doc');
                    if (!file_exists($folder)) {
                        mkdir($folder, 0755, true);
                    }

                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $savePath = $folder . '/' . $filename;

                    $image->save($savePath, 80);

                    $photoPaths[] = 'doc/' . $filename;
                }
            }

            // =========================
            // SIMPAN KE DATABASE
            // =========================
            $doc = DocChick::create([
                'uuid' => $request->uuid,
                'tanggal' => $request->tanggal,
                'jam' => $request->jam,
                'satpam_id' => $request->satpam_id,
                'jumlah' => $request->jumlah,
                'ekspedisi_id' => $request->ekspedisi_id,
                'tujuan' => $request->tujuan,
                'no_polisi' => $request->no_polisi,
                'jenis' => $request->jenis,
                'note' => $request->note,
                'comid' => $request->comid,
                'input_date' => $request->input_date,
                'total_ekor' => $request->total_ekor,
                'nama_supir' => $request->nama_supir,
                'nomor_segel' => $request->nomor_segel,
                // 🔥 JSON DARI FLUTTER (Hive)
                'doc_box_option' => $request->doc_box_option,

                // 🔥 MULTI FOTO (JSON)
                'foto' => json_encode($photoPaths),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data Doc tersimpan',
                'data' => $doc,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Gangguan Server / Offline Mode',
                'error' => $th->getMessage(),
            ], 500);
        }
        
    }

    public function jadwalPatroli(Request $request)
    {
        $request->validate([
            'comid' => 'required',
        ]);

        try {
            $active = JadwalPatroli::where('comid', $request->comid)->where('is_active', 1)->first();
            if ($active) {

                $data = JadwalPatroliDetail::where('patroli_id', $active->id)
                ->whereHas('location', function ($q) {
                    $q->where('is_active', 1);
                })
                ->get();
                return response()->json([
                    'success' => true,
                    'data' => $data,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada jadwal patroli aktif',
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Gangguan Server/Offline Mode',
            ]);
        }
    }
}
