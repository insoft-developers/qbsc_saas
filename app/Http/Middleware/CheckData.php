<?php

namespace App\Http\Middleware;

use App\Models\AbsenLocation;
use App\Models\Company;
use App\Models\JadwalPatroli;
use App\Models\JamShift;
use App\Models\Kandang;
use App\Models\Lokasi;
use App\Models\Satpam;
use App\Traits\CommonTrait;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckData
{
    use CommonTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $comid = $this->comid();
        $jumlah_satpam = Satpam::where('comid', $comid)->count();

        if ($jumlah_satpam < 1) {
            return redirect()->to('/satpam')->with('error', 'Anda harus menambahkan data satpam terlebih dahulu!');
        }

        $jumlah_lokasi = Lokasi::where('comid', $comid)->count();

        if ($jumlah_lokasi < 1) {
            return redirect()->to('/lokasi')->with('error', 'Anda harus menambahkan data Lokasi terlebih dahulu!');
        }

        $absenlocation = AbsenLocation::where('comid', $comid)->first();
        if ($absenlocation) {
            if ($absenlocation->latitude == null || $absenlocation->longitude == null || $absenlocation->latitude == 0 || $absenlocation->longitude == 0 || $absenlocation->latitude == '' || $absenlocation->longitude == '') {
                return redirect()->to('/absen_location')->with('error', 'Anda harus mengisi koordinat latitude dan longitude dengan lokasi real untuk keperluan absen satpam! masuk ke aplikasi qbsc satpam dengan user satpam jabatan danru. Masuk ke Pengaturan Menu Pos Absen');
            }
        }

        

        $jumlah_shift_kerja = JamShift::where('comid', $comid)->count();

        if ($jumlah_shift_kerja < 1) {
            return redirect()->to('/jam_shift')->with('error', 'Anda harus membuat data shift kerja terlebih dahulu!');
        }

        $jadwal_count = JadwalPatroli::where('comid', $comid)->where('is_active', 1)->count();


        if ($jadwal_count != 1) {   
            return redirect()->to('/jadwal_patroli')->with('error', 'Buat 1 Jadwal Patroli Aktif terlebih dahulu dan tidak boleh ada lebih dari 1 Jadwal yang aktif!');
        }

        $co = Company::find($comid);

        if ($co->company_address == null || $co->company_email == null || $co->company_phone == null || $co->company_pic == null) {
            return redirect()->to('/perusahaan')->with('error', 'Anda harus melengkapi data perusahaan terlebih dahulu!');
        }

        if ($co->is_peternakan == 1) {
            $jumlah_kandang = Kandang::where('comid', $comid)->count();
            if ($jumlah_kandang < 1) {
                return redirect()->to('/kandang')->with('error', 'Anda harus membuat data kandang terlebih dahulu!');
            }
        }

        

        return $next($request);
    }
}
