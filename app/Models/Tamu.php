<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tamu extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function satpam():BelongsTo
    {
        return $this->belongsTo(Satpam::class, 'satpam_id', 'id');
    }

    public function satpam_pulang():BelongsTo
    {
        return $this->belongsTo(Satpam::class, 'satpam_id_pulang', 'id');
    }

    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class, 'comid','id');
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
