<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Reseller extends Authenticatable
{
    protected $table = 'resellers';

    protected $fillable = [
        'name', 'whatsapp', 'alamat', 'email', 'password', 'referal_code', 'percent_fee', 'foto', 'token', 'is_active'
    ];

    protected $hidden = ['password'];
}
