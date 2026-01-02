<?php

namespace Database\Seeders;

use App\Models\RunningText;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RunningTextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RunningText::create([
            'text' => 'Selamat datang di aplikasi QBSC. Hubungi admin kami di WhatsApp 082379096235 untuk bantuan lebih lanjut.',
            'comid' => 1,
        ]);
    }
}
