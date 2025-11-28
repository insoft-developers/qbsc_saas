<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Mail\ActivationMail;
use App\Models\AbsenLocation;
use App\Models\Company;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register_new');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        $token = Str::random(64);

        $company = Company::create([
            "company_name" => $request->company_name,
            "is_peternakan" => $request->is_peternakan
        ]);

        $company_id = $company->id;


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'whatsapp' => $request->whatsapp,
            'password' => Hash::make($request->password),
            'activation_token' => $token,
            'is_active' => 0,
            'company_id' => $company_id,
            'level' => 'owner'
        ]);

        AbsenLocation::create([
            "location_name" => "Pos Utama",
            "latitude" => 0,
            "longitude" => 0,
            "max_distance" => 1,
            "comid" => $company_id
        ]);

        Mail::to($user->email)->send(new ActivationMail($user, $token));

        return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Cek email Anda untuk aktivasi akun.');
    }

    public function activate($token)
    {
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->update([
            'is_active' => 1,
            'activation_token' => null,
            'email_verified_at' => Carbon::now()
        ]);

        return redirect()->route('login')->with('success', 'Akun Anda telah aktif! Silakan login.');
    }
}
