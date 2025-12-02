<?php

namespace Database\Seeders;

use App\Models\PaketLangganan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaketLangganan::create([
            "nama_paket" => "Paket Farm M-Basic",
            "subtitle" => "Paket ideal untuk kebutuhan dasar Kontrol Keamanan Anda.",
            "harga" => 249000,
            "periode" => 1,
            "jumlah_satpam" => 4,
            "jumlah_lokasi" => 5,
            "jumlah_farm" => 6,
            "is_broadcast" => 0,
            "jumlah_user_admin" => 1,
            "is_scan_tamu" => 0,
            "is_user_area" => 0,
            "is_mobile_app" => 0,
            "is_google_meet" => 0,
            "is_request_feature" => 0,
            "company_type" => 1
        ]);


        PaketLangganan::create([
            "nama_paket" => "Paket Farm M-Medium",
            "subtitle" => "Paket ideal untuk kebutuhan lanjut Kontrol Keamanan Anda.",
            "harga" => 369000,
            "periode" => 1,
            "jumlah_satpam" => 8,
            "jumlah_lokasi" => 8,
            "jumlah_farm" => 8,
            "is_broadcast" => 0,
            "jumlah_user_admin" => 5,
            "is_scan_tamu" => 0,
            "is_user_area" => 0,
            "is_mobile_app" => 1,
            "is_google_meet" => 0,
            "is_request_feature" => 0,
            "company_type" => 1
        ]);

        PaketLangganan::create([
            "nama_paket" => "Paket Farm M-Ultimate",
            "subtitle" => "Paket ideal untuk kebutuhan lengkap Kontrol Keamanan Anda.",
            "harga" => 499000,
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


        PaketLangganan::create([
            "nama_paket" => "Paket Farm T-Basic",
            "subtitle" => "Paket ideal untuk kebutuhan dasar Kontrol Keamanan Anda.",
            "harga" => 2490000,
            "periode" => 2,
            "jumlah_satpam" => 4,
            "jumlah_lokasi" => 5,
            "jumlah_farm" => 6,
            "is_broadcast" => 1,
            "jumlah_user_admin" => 1,
            "is_scan_tamu" => 0,
            "is_user_area" => 0,
            "is_mobile_app" => 0,
            "is_google_meet" => 1,
            "is_request_feature" => 1,
            "company_type" => 1
        ]);


        PaketLangganan::create([
            "nama_paket" => "Paket Farm T-Medium",
            "subtitle" => "Paket ideal untuk kebutuhan lanjut Kontrol Keamanan Anda.",
            "harga" => 3690000,
            "periode" => 2,
            "jumlah_satpam" => 8,
            "jumlah_lokasi" => 8,
            "jumlah_farm" => 8,
            "is_broadcast" => 1,
            "jumlah_user_admin" => 5,
            "is_scan_tamu" => 0,
            "is_user_area" => 0,
            "is_mobile_app" => 1,
            "is_google_meet" => 1,
            "is_request_feature" => 1,
            "company_type" => 1
        ]);

        PaketLangganan::create([
            "nama_paket" => "Paket Farm T-Ultimate",
            "subtitle" => "Paket ideal untuk kebutuhan lengkap Kontrol Keamanan Anda.",
            "harga" => 4990000,
            "periode" => 2,
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


        PaketLangganan::create([
            "nama_paket" => "Paket M-Basic",
            "subtitle" => "Paket ideal untuk kebutuhan dasar Kontrol Keamanan Anda.",
            "harga" => 149000,
            "periode" => 1,
            "jumlah_satpam" => 4,
            "jumlah_lokasi" => 5,
            "jumlah_farm" => 0,
            "is_broadcast" => 0,
            "jumlah_user_admin" => 1,
            "is_scan_tamu" => 0,
            "is_user_area" => 0,
            "is_mobile_app" => 0,
            "is_google_meet" => 0,
            "is_request_feature" => 0,
            "company_type" => 2
        ]);


        PaketLangganan::create([
            "nama_paket" => "Paket M-Medium",
            "subtitle" => "Paket ideal untuk kebutuhan lanjut Kontrol Keamanan Anda.",
            "harga" => 269000,
            "periode" => 1,
            "jumlah_satpam" => 8,
            "jumlah_lokasi" => 8,
            "jumlah_farm" => 0,
            "is_broadcast" => 0,
            "jumlah_user_admin" => 5,
            "is_scan_tamu" => 0,
            "is_user_area" => 0,
            "is_mobile_app" => 1,
            "is_google_meet" => 0,
            "is_request_feature" => 0,
            "company_type" => 2
        ]);

        PaketLangganan::create([
            "nama_paket" => "Paket M-Ultimate",
            "subtitle" => "Paket ideal untuk kebutuhan lengkap Kontrol Keamanan Anda.",
            "harga" => 399000,
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
            "nama_paket" => "Paket T-Basic",
            "subtitle" => "Paket ideal untuk kebutuhan dasar Kontrol Keamanan Anda.",
            "harga" => 1490000,
            "periode" => 2,
            "jumlah_satpam" => 4,
            "jumlah_lokasi" => 5,
            "jumlah_farm" => 0,
            "is_broadcast" => 1,
            "jumlah_user_admin" => 1,
            "is_scan_tamu" => 0,
            "is_user_area" => 0,
            "is_mobile_app" => 0,
            "is_google_meet" => 1,
            "is_request_feature" => 1,
            "company_type" => 2
        ]);


        PaketLangganan::create([
            "nama_paket" => "Paket T-Medium",
            "subtitle" => "Paket ideal untuk kebutuhan lanjut Kontrol Keamanan Anda.",
            "harga" => 2690000,
            "periode" => 2,
            "jumlah_satpam" => 8,
            "jumlah_lokasi" => 8,
            "jumlah_farm" => 0,
            "is_broadcast" => 1,
            "jumlah_user_admin" => 5,
            "is_scan_tamu" => 0,
            "is_user_area" => 0,
            "is_mobile_app" => 1,
            "is_google_meet" => 1,
            "is_request_feature" => 1,
            "company_type" => 2
        ]);

        PaketLangganan::create([
            "nama_paket" => "Paket T-Ultimate",
            "subtitle" => "Paket ideal untuk kebutuhan lengkap Kontrol Keamanan Anda.",
            "harga" => 3990000,
            "periode" => 2,
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
    }
}
