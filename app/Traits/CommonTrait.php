<?php

namespace App\Traits;

use App\Models\JamShift;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait CommonTrait
{
    public function comid()
    {
        $user = User::find(Auth::user()->id);
        return $user->company_id;
    }

    public function codeGenerate($table, $field = 'badge_id', $prefix = 'SEC', $length = 8)
    {
        $last = DB::table($table)->orderBy('id', 'desc')->value($field);
        $number = 1;

        if ($last) {
            $number = (int) substr($last, strlen($prefix)) + 1;
        }

        return $prefix . str_pad($number, $length, '0', STR_PAD_LEFT);
    }

    public function jamDalamRange($jamKontrol, $jam_awal, $jam_akhir)
    {
        $awal = array_map('trim', explode(',', $jam_awal));
        $akhir = array_map('trim', explode(',', $jam_akhir));

        $patroli = [];
        foreach ($awal as $i => $start) {
            $patroli[] = [
                'start' => $start,
                'end' => $akhir[$i] ?? null,
            ];
        }

        foreach ($patroli as $item) {
            if ($jamKontrol >= $item['start'] && $jamKontrol <= $item['end']) {
                return true; // jam ada dalam salah satu interval
            }
        }

        return false; // tidak dalam range manapun
    }

    public function shiftDetection($clockIn, $comid)
    {
        $clockIn = Carbon::parse($clockIn);

        $shifts = JamShift::where('comid', $comid)->get();

        foreach ($shifts as $shift) {
            $start = Carbon::parse($shift->jam_masuk_awal);
            $end = Carbon::parse($shift->jam_masuk_akhir);

            // Jika shift lintas tanggal (misal 23:45 → 00:15)
            if ($end->lessThan($start)) {
                $end->addDay();
            }

            // Jika clock-in sebelum jam masuk awal, cek hari sebelumnya
            // agar 00:05 tetap masuk SHIFT MALAM
            $clockInAdjusted = $clockIn->copy();
            if ($clockInAdjusted->lessThan($start)) {
                $clockInAdjusted->addDay();
            }

            // Cek apakah clock-in ada dalam range shift ini
            if ($clockInAdjusted->between($start, $end)) {
                return $shift['name']; // return object shift lengkap
            }
        }

        return null;
    }
}
