<?php

namespace App\Traits;

use App\Models\Company;
use App\Models\JamShift;
use App\Models\PaketLangganan;
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

    public function isOwner()
    {
        $user = User::find(Auth::user()->id);
        if ($user->level == 'owner') {
            return true;
        } else {
            return false;
        }
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
        if (!$jamKontrol || !$jam_awal || !$jam_akhir) {
            return false;
        }

        $kontrol = Carbon::parse(trim($jamKontrol));
        $awal = Carbon::parse(trim($jam_awal));
        $akhir = Carbon::parse(trim($jam_akhir));

        return $kontrol->between($awal, $akhir);
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
                return $shift; // return object shift lengkap
            }
        }

        return null;
    }

    public function what_paket($comid)
    {
        $com = Company::find($comid);
        $paket = PaketLangganan::find($com->paket_id);
        return $paket;
    }
}
