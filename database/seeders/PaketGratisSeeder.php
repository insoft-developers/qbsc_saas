<?php

namespace Database\Seeders;

use App\Models\PaketLangganan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaketGratisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaketLangganan::create([
            "nama_paket" => "Paket Ultimate-14",
            "subtitle" => "Paket Lengkap Gratis Selama 14 Hari.",
            "harga" => 0,
            "periode" => 1,
            "jumlah_satpam" => 50,
            "jumlah_lokasi" => 50,
            "jumlah_farm" => 0,
            "is_broadcast" => 1,
            "jumlah_user_admin" => 50,
            "is_scan_tamu" => 1,
            "is_user_area" => 1,
            "is_mobile_app" => 1,
            "is_google_meet" => 1,
            "is_request_feature" => 1,
            "company_type" => 2
        ]);


        PaketLangganan::create([
            "nama_paket" => "Paket F-Ultimate-14",
            "subtitle" => "Paket Lengkap Perusahaan Peternakan Gratis Selama 14 Hari.",
            "harga" => 0,
            "periode" => 1,
            "jumlah_satpam" => 50,
            "jumlah_lokasi" => 50,
            "jumlah_farm" => 50,
            "is_broadcast" => 1,
            "jumlah_user_admin" => 50,
            "is_scan_tamu" => 1,
            "is_user_area" => 1,
            "is_mobile_app" => 1,
            "is_google_meet" => 1,
            "is_request_feature" => 1,
            "company_type" => 1
        ]);
    }
}
