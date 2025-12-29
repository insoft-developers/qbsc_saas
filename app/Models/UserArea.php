<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserArea extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }

    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class, 'comid', 'id');
    }

    public function user_monitoring():BelongsTo
    {
        return $this->belongsTo(User::class, 'monitoring_userid', 'id');
    }

    public function company_monitoring():BelongsTo
    {
        return $this->belongsTo(Company::class, 'monitoring_comid', 'id');
    }
}
