<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\AbsenLocation;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

use Illuminate\Support\Str;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('email', $googleUser->email)->first();

        if (!$user) {
            $company = Company::create([
                'company_name' => uniqid() . '- Company',
                'is_peternakan' => 99,
                'referal_code' => session('referal_code')
            ]);

            $company_id = $company->id;

            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'email_verified_at' => Carbon::now(),
                'gogole_id' => $googleUser->id,
                'password' => bcrypt(Str::random(16)),
                'is_active' => 1,
                'whatsapp' => uniqid(),
                'level' => 'owner',
                'company_id' => $company_id,
            ]);

            AbsenLocation::create([
                'location_name' => 'Pos Utama',
                'latitude' => 0,
                'longitude' => 0,
                'max_distance' => 1,
                'comid' => $company_id,
            ]);
        }

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
