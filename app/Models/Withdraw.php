<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdraw extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function reseller():BelongsTo
    {
        return $this->belongsTo(Reseller::class, 'reseller_id', 'id');
    }
}
