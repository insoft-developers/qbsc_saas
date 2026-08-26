<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AbsenCommand extends Command
{
    protected $signature = 'app:absen-cron';

    protected $description = 'Memproses status keterlambatan absensi';

    public function handle()
    {
        Absensi::whereNull('catatan_masuk')
            ->with('company:id,timezone')
            ->chunkById(500, function ($absensis) {

                foreach ($absensis as $masuk) {

                    // Timezone perusahaan
                    $timezone = $masuk->company?->timezone ?? 'Asia/Jakarta';

                    // Jam masuk aktual
                    $jamMasuk = Carbon::parse(
                        $masuk->jam_masuk,
                        $timezone
                    );

                    // Jam masuk dari setting shift
                    $jamSetting = Carbon::parse(
                        $masuk->jam_setting_masuk,
                        $timezone
                    )->setDate(
                        $jamMasuk->year,
                        $jamMasuk->month,
                        $jamMasuk->day
                    );

                    // Jika shift melewati tengah malam
                    if (
                        $jamSetting->lt($jamMasuk) &&
                        $jamMasuk->diffInHours($jamSetting) >= 6
                    ) {
                        $jamSetting->addDay();
                    }

                    // Cek terlambat
                    if ($jamMasuk->lte($jamSetting)) {

                        $status = 'tepat-waktu';
                        $isTerlambat = 0;

                    } else {

                        $menitTerlambat = $jamMasuk->diffInMinutes($jamSetting);

                        $status = "terlambat {$menitTerlambat} menit";
                        $isTerlambat = 1;
                    }

                    // Simpan hasil
                    $masuk->update([
                        'catatan_masuk' => $status,
                        'is_terlambat' => $isTerlambat,
                    ]);
                }
            });

        $this->info('Proses absensi selesai.');

        return Command::SUCCESS;
    }
}