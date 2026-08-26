<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AbsenPulangCommand extends Command
{
    protected $signature = 'app:absen-pulang-cron';

    protected $description = 'Memproses status pulang absensi';

    public function handle()
    {
        Absensi::whereNull('catatan_keluar')
            ->where('status', 2)
            ->with('company:id,timezone')
            ->chunkById(500, function ($absensis) {

                foreach ($absensis as $pulang) {

                    // Timezone perusahaan
                    $timezone = $pulang->company?->timezone ?? 'Asia/Jakarta';

                    // Jam masuk aktual
                    $jamMasuk = Carbon::parse(
                        $pulang->jam_masuk,
                        $timezone
                    );

                    // Jam pulang aktual
                    $jamPulang = Carbon::parse(
                        $pulang->jam_keluar,
                        $timezone
                    );

                    // Jam setting pulang
                    $jamSetting = Carbon::parse(
                        $pulang->jam_setting_pulang,
                        $timezone
                    )->setDate(
                        $jamMasuk->year,
                        $jamMasuk->month,
                        $jamMasuk->day
                    );

                    // Jika jam pulang lebih kecil dari jam masuk,
                    // berarti shift melewati tengah malam.
                    if ($jamSetting->lt($jamMasuk)) {
                        $jamSetting->addDay();
                    }

                    // Tentukan status pulang
                    if ($jamPulang->gte($jamSetting)) {

                        $status = 'tepat-waktu';
                        $isPulangCepat = 0;

                    } else {

                        $lebihCepat = $jamSetting->diffInMinutes($jamPulang);

                        $status = "pulang lebih cepat {$lebihCepat} menit";
                        $isPulangCepat = 1;
                    }

                    // Simpan hasil
                    $pulang->update([
                        'catatan_keluar' => $status,
                        'is_pulang_cepat' => $isPulangCepat,
                    ]);
                }
            });

        $this->info('Proses absensi pulang selesai.');

        return Command::SUCCESS;
    }
}