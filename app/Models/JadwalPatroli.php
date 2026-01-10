<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JadwalPatroli extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function  patroli_detail():BelongsTo
    {
        return $this->belongsTo(JadwalPatroliDetail::class, 'patroli_id', 'id');
    }

    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class, 'comid', 'id');
    }
}
