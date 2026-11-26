<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaketLangganan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_paket',
        'subtitle',
        'harga',
        'periode',
        'hari',
        'jumlah_satpam',
        'jumlah_lokasi',
        'jumlah_farm',
        'is_broadcast',
        'jumlah_user_admin',
        'is_scan_tamu',
        'is_user_area',
        'is_mobile_app',
        'is_google_meet',
        'is_request_feature',
        'company_type',
        'is_active',
        'urutan',
        'is_show',
        'comids',
    ];

    public function companies()
    {
        return $this->hasMany(Company::class, 'paket_id', 'id');
    }
}
