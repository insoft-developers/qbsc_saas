<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

trait CommonTrait
{
    public function comid()
    {
        $user = User::find(Auth::user()->id);
        return $user->company_id;
    }

    public function codeGenerate($table, $field = 'badge_id', $prefix = 'SEC', $length = 8)
    {
        $last = DB::table($table)->orderBy('id', 'desc')->value($field);
        $number = 1;

        if ($last) {
            $number = (int) substr($last, strlen($prefix)) + 1;
        }

        return $prefix . str_pad($number, $length, '0', STR_PAD_LEFT);
    }
    
}