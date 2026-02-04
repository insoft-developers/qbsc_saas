<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Kandang extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $appends = ['suhu', 'kipas', 'alarm', 'lampu','kipas_image']; // ⬅️ WAJIB
    protected $hidden = ['suhuData', 'kipasData', 'alarmData', 'lampuData']; // ⬅️ optional tapi disarankan

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'comid', 'id');
    }

    public function pics(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pic', 'id');
    }

    public function suhuData(): HasOne
    {
        return $this->hasOne(KandangSuhu::class, 'kandang_id', 'id')->orderBy('tanggal', 'desc')->orderBy('jam', 'desc');
    }

    // ✅ ACCESSOR (INI YANG KELUAR KE JSON)
    public function getSuhuAttribute()
    {
        if (!$this->suhuData) {
            return 'NO-CHECK';
        }

        return $this->suhuData->temperature;
    }

    public function kipasData(): HasOne
    {
        return $this->hasOne(KandangKipas::class, 'kandang_id', 'id')->orderBy('tanggal', 'desc')->orderBy('jam', 'desc');
    }

    // ✅ ACCESSOR (INI YANG KELUAR KE JSON)

    public function getKipasAttribute()
    {
        if (!$this->kipasData) {
            return 'NO-CHECK';
        }

        $kno = 1;
        $on = [];
        $k_arr = explode(',', $this->kipasData->kipas);

        foreach ($k_arr as $kipas) {
            if ($kipas == '1') {
                array_push($on, $kno);
            }
            $kno++;
        }

        $hidup = implode(',', $on);
        return $hidup . ' ON';

        // return $this->kipasData?->kipas;
    }

    public function getKipasImageAttribute()
    {
        // kalau tidak ada data kipas terbaru
        if (!$this->kipasData || !$this->kipasData->foto) {
            return NULL;
        }

        // foto dari tabel kandang_kipas
        $fotoPath = $this->kipasData->foto;

        // cek file ada di storage
        // if (Storage::exists('public/' . $fotoPath)) {
        //     // cukup return relative path
        //     return $fotoPath;
        // }

        if($fotoPath) {
            return url('storage/'.$fotoPath);
        }

        // fallback jika file tidak ada
        return NULL;
    }

    public function alarmData(): HasOne
    {
        return $this->hasOne(KandangAlarm::class, 'kandang_id', 'id')->orderBy('tanggal', 'desc')->orderBy('jam', 'desc');
    }

    // ✅ ACCESSOR (INI YANG KELUAR KE JSON)
    public function getAlarmAttribute()
    {
        if (!$this->alarmData) {
            return 'NO-CHECK';
        }

        return $this->alarmData->is_alarm_on == 1 ? 'ON' : 'OFF';
    }

    public function lampuData(): HasOne
    {
        return $this->hasOne(KandangLampu::class, 'kandang_id', 'id')->orderBy('tanggal', 'desc')->orderBy('jam', 'desc');
    }

    // ✅ ACCESSOR (INI YANG KELUAR KE JSON)
    public function getLampuAttribute()
    {
        if (!$this->lampuData) {
            return 'NO-CHECK';
        }

        return $this->lampuData->is_lamp_on == 1 ? 'ON' : 'OFF';
    }
}
