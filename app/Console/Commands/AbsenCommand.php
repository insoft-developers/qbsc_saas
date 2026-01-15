<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AbsenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:absen-cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $absen_masuk = Absensi::whereNull('catatan_masuk')->get();

        foreach ($absen_masuk as $masuk) {
            // Waktu karyawan datang
            $jamMasuk = Carbon::parse($masuk->jam_masuk);

            // Jam setting shift (tanpa tanggal → tempel tanggal jam masuk)
            $jamSetting = Carbon::parse($masuk->jam_setting_masuk)->setDateFrom($jamMasuk);

            // Jika melewati tengah malam
            if ($jamSetting->lt($jamMasuk) && $jamMasuk->diffInHours($jamSetting) >= 6) {
                $jamSetting->addDay();
            }

            if ($jamMasuk->lte($jamSetting)) {
                $status = 'tepat-waktu';
                $isTerlambat = 0;
            } else {
                // Hitung keterlambatan dalam menit
                $menitTerlambat = $jamMasuk->diffInMinutes($jamSetting);
                $status = "terlambat $menitTerlambat menit";
                $isTerlambat = 1;
            }

            // Simpan
            $masuk->update([
                'catatan_masuk' => $status,
                'is_terlambat' => $isTerlambat
            ]);
        }
    }
}
