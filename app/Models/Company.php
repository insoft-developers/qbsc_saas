<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Company extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function paket(): BelongsTo
    {
        return $this->belongsTo(PaketLangganan::class, 'paket_id', 'id');
    }

    public function owner(): HasOne
    {
        return $this->hasOne(User::class, 'company_id', 'id')->where('level', 'owner');
    }

    
}
