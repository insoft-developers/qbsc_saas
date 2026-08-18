<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatroliFileScan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'scanned_at' => 'datetime',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(
            Company::class,
            'company_id'
        );
    }
}