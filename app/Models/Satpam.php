<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class Satpam extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;


    protected $guarded = ['id'];

    protected $casts = [
        'face_embedding' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'comid', 'id');
    }

    public function getFacePhotoUrlAttribute()
    {
        // Kalau user punya foto di storage, tampilkan URL-nya
        return $this->face_photo_path ? asset('storage/' . $this->face_photo_path) : asset('images/satpam-default.png');
    }

    public function absensi():HasMany
    {
        return $this->hasMany(Absensi::class, 'satpam_id', 'id');
    }

    public function patroli():HasMany
    {
        return $this->hasMany(Patroli::class, 'satpam_id', 'id');
    }
}
