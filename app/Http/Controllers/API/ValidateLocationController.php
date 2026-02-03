<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AbsenLocation;
use App\Models\Absensi;
use App\Models\Lokasi;
use App\Models\Satpam;
use App\Models\SatpamLocation;
use App\Traits\CommonTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ValidateLocationController extends Controller
{
    use CommonTrait;
    public function testing()
    {
        $clockIn = '21:13:40';
        $comid = 1;
        $data = $this->shiftDetection($clockIn, $comid);

        $shift_id = $data['id'] ?? null;
        $nama_shift = $data['name'] ?? null;
        $jam_masuk_shift = $data['jam_masuk'] ?? null;
        $jam_pulang_shift = $data['jam_pulang'] ?? null;

        Absensi::create(['tanggal' => date('Y-m-d'), 'satpam_id' => 1, 'latitude' => 0.2, 'longitude' => 0.2, 'jam_masuk' => date('Y-m-d H:i:s'), 'shift_id' => $shift_id, 'shift_name' => $nama_shift, 'jam_setting_masuk' => $jam_masuk_shift, 'jam_setting_pulang' => $jam_pulang_shift, 'status' => 1, 'description' => 'Absensi Berhasil', 'comid' => 1]);

        return response()->json([
            'id' => $shift_id,
            'nama' => $nama_shift,
            'masuk' => $jam_masuk_shift,
            'pulang' => $jam_pulang_shift,
        ]);
    }

    public function updateCoordinates(Request $request)
    {
        $request->validate([
            'nama_lokasi' => 'required',
            'comid' => 'required',
            'qrcode' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ]);

        $data = Lokasi::where('qrcode', $request->qrcode)->where('comid', $request->comid)->first();

        if ($data) {
            $data->nama_lokasi = $request->nama_lokasi;
            $data->latitude = $request->lat;
            $data->longitude = $request->lng;
            $data->updated_at = Carbon::now();
            $data->save();
            return response()->json([
                'success' => true,
                'message' => 'Upload Coordinat Berhasil',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan',
            ]);
        }
    }

    public function getDataLocation(Request $request)
    {
        $request->validate([
            'comid' => 'required',
        ]);

        $data = Lokasi::where('comid', $request->comid)->where('is_active', 1)->get();
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function locationData(Request $request)
    {
        $request->validate([
            'satpam_id' => 'required',
        ]);

        $satpam = Satpam::find($request->satpam_id);

        $data = AbsenLocation::where('comid', $satpam->comid)->first();
        if ($data) {
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Lokasi Absen tidak ditemukan!',
            ]);
        }
    }


    public function updateLastPosition(Request $request) {
        $request->validate([
            'uuid' => 'required|unique:satpam_locations,uuid',
            'satpam_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'accuracy' => 'required|numeric|min:0|max:50',
        ]);


        $satpam = Satpam::find($request->satpam_id);

        $satpam->last_latitude = $request->latitude;
        $satpam->last_longitude = $request->longitude;
        $satpam->last_seen_at = now();
        $satpam->save();
        // 🔹 Simpan history perjalanan
        SatpamLocation::create([
            'uuid' => $request->uuid,
            'satpam_id' => $satpam->id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy' => $request->accuracy,
            'recorded_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }


    public function updateSatpamLocation(Request $request)
    {
        $request->validate([
            'uuid' => 'required|unique:satpam_locations,uuid',
            'satpam_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'accuracy' => 'required|numeric|min:0|max:50',
        ]);

        $satpam = Satpam::find($request->satpam_id);

        SatpamLocation::create([
            'uuid' => $request->uuid,
            'satpam_id' => $satpam->id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'accuracy' => $request->accuracy,
            'recorded_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function kinerja(Request $request)
    {
        $bulan = date('m');
        $tahun = date('Y');
        $comid = $request->comid;

        $satpams = Satpam::with([
            'absensi' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            },
            'patroli' => function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)->whereYear('tanggal', $tahun);
            },
            'company:id,company_name',
        ])
            ->where('comid', $comid)
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $satpams->map(function ($row) {
            // ===== HADIR =====
            $hadir = $row->absensi->where('status', 2);

            // ===== TERLAMBAT =====
            $totalTerlambat = 0;
            foreach ($hadir as $abs) {
                if (strpos($abs->catatan_masuk, 'terlambat') !== false) {
                    preg_match('/\d+/', $abs->catatan_masuk, $m);
                    $totalTerlambat += (int) ($m[0] ?? 0);
                }
            }

            // ===== PULANG CEPAT =====
            $totalCepatPulang = 0;
            foreach ($hadir as $abs) {
                if (strpos($abs->catatan_keluar, 'pulang lebih cepat') !== false) {
                    preg_match('/\d+/', $abs->catatan_keluar, $m);
                    $totalCepatPulang += (int) ($m[0] ?? 0);
                }
            }

            // ===== PATROLI DI LUAR JADWAL =====
            $patroliDiluar = $row->patroli
                ->filter(function ($p) {
                    if (empty($p->jam_awal_patroli) || empty($p->jam_akhir_patroli)) {
                        return true;
                    }

                    $jamPatroli = Carbon::createFromFormat('H:i:s', $p->jam);
                    $jamMulai = Carbon::createFromFormat('H:i', $p->jam_awal_patroli);
                    $jamAkhir = Carbon::createFromFormat('H:i', $p->jam_akhir_patroli);

                    return !$jamPatroli->between($jamMulai, $jamAkhir, true);
                })
                ->count();

            return [
                'satpam_id' => $row->id,
                'nama' => $row->name,
                'jabatan' => $row->is_danru ? 'Danru' : 'Anggota',
                'foto' => $row->face_photo_path ? asset('storage/' . $row->face_photo_path) : null,

                'hadir' => $hadir->count(),
                'tepat_waktu' => $hadir->where('is_terlambat', 0)->where('is_pulang_cepat', 0)->count(),

                'terlambat' => [
                    'jumlah' => $hadir->where('is_terlambat', 1)->count(),
                    'menit' => $totalTerlambat,
                ],

                'cepat_pulang' => [
                    'jumlah' => $hadir->where('is_pulang_cepat', 1)->count(),
                    'menit' => $totalCepatPulang,
                ],

                'total_patroli' => $row->patroli->count(),
                'patroli_diluar_jadwal' => $patroliDiluar,

                'company' => $row->company->company_name ?? '',
            ];
        });

        return response()->json([
            'success' => true,
            'periode' => [
                'bulan' => $bulan,
                'tahun' => $tahun,
            ],
            'total_satpam' => $data->count(),
            'data' => $data,
        ]);
    }
}
