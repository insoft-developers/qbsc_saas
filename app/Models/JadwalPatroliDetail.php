<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalPatroliDetail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function jadwal():BelongsTo
    {
        return $this->belongsTo(JadwalPatroli::class, 'patroli_id','id');
    }

    public function location():BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'location_id', 'id');
    }

    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class, 'comid', 'id');
    }
}
