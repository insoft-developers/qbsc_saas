<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocChick extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function satpam():BelongsTo
    {
        return $this->belongsTo(Satpam::class, 'satpam_id', 'id');
    }

    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class, 'comid', 'id');
    }

    public function ekspedisi():BelongsTo
    {
        return $this->belongsTo(Ekspedisi::class, 'ekspedisi_id', 'id');
    }
}
