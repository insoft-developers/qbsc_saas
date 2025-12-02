<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembelian extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class, 'comid','id');
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'userid','id');
    }

    public function paket():BelongsTo
    {
        return $this->belongsTo(PaketLangganan::class, 'paket_id','id');
    }
}
