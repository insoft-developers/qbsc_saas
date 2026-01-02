<?php

namespace Database\Seeders;

use App\Models\QbscSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QbscSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        QbscSetting::create([
            'com_name' => 'QBSC',
            'whatsapp' => '6282379096235',
            'logo' => 'qbsc-logo.png',
            'address' => 'Jl. Contoh Alamat No.123, Kota Contoh, Negara Contoh',
            'email' => 'mail@qbsc.cloud',
        ]);
    }
}
