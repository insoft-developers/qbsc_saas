<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KandangKipas extends Model
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

    public function kandang():BelongsTo
    {
        return $this->belongsTo(Kandang::class, 'kandang_id', 'id');
    }
}
