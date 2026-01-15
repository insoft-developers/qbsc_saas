<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use Illuminate\Console\Command;
use Carbon\Carbon;

class AbsenPulangCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:absen-pulang-cron';

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
        $absen_pulang = Absensi::whereNull('catatan_keluar')
            ->where('status', 2) // sudah scan pulang
            ->get();

        foreach ($absen_pulang as $pulang) {
            $jamMasuk = Carbon::parse($pulang->jam_masuk); // tanggal shift
            $jamPulang = Carbon::parse($pulang->jam_keluar); // actual pulang

            // Buat jam setting pulang → tempel tanggal dari jam masuk
            $jamSetting = Carbon::parse($pulang->jam_setting_pulang)->setDate($jamMasuk->year, $jamMasuk->month, $jamMasuk->day);

            // Jika setting < jam masuk → berarti setting ada di hari berikutnya (shift malam)
            if ($jamSetting->lt($jamMasuk)) {
                $jamSetting->addDay();
            }

            // Tentukan status pulang
            if ($jamPulang->gte($jamSetting)) {
                $status = 'tepat-waktu';
                $isPulangCepat = 0;
                // Jika ingin hitung lembur:
                // $menitLembur = $jamSetting->diffInMinutes($jamPulang);
                // if ($menitLembur > 0) {
                //     $status = "lembur $menitLembur menit";
                // }
            } else {
                // Pulang lebih cepat
                $lebihCepat = $jamSetting->diffInMinutes($jamPulang);
                $status = "pulang lebih cepat $lebihCepat menit";
                $isPulangCepat = 1;
            }

            $pulang->update([
                'catatan_keluar' => $status,
                'is_pulang_cepat' => $isPulangCepat
            ]);
        }
    }
}
