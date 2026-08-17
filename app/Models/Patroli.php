<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Patroli extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function satpam(): BelongsTo
    {
        return $this->belongsTo(Satpam::class, 'satpam_id', 'id');
    }


    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class, 'location_id', 'id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'comid', 'id');
    }


    public function archive():HasOne
    {
        return $this->hasOne(PatroliArchive::class);
    }


    protected $casts = [
         'tanggal' => 'date:Y-m-d',
    ];
}
