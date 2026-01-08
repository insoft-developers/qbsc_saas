<?php

namespace App\Http\Controllers\Reseller;

use App\Http\Controllers\Controller;
use App\Models\Reseller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\ResellerActivation;

class ResellerAuthController extends Controller
{
    public function showLogin()
    {
        return view('reseller.auth.login');
    }

    public function register()
    {
        return view('reseller.auth.register');
    }

    public function register_post(Request $request)
    {
        $token = Str::random(64);
        
        $request->validate([
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:resellers,email',
            'password' => 'required|min:6|confirmed',
        ]);

        $reseller = Reseller::create([
            'name' => $request->name,
            'alamat' => uniqid() . ' alamat',
            'whatsapp' => uniqid(),
            'email' => $request->email,
            'percent_fee' => 20,
            'token' => $token,
            'password' => Hash::make($request->password),
            'referal_code' => strtoupper(Str::random(8)),
        ]);

        // Auth::guard('reseller')->login($reseller);
        Mail::to($reseller->email)->send(new ResellerActivation($reseller, $token));

        return redirect()->route('reseller.login')->with('success', 'Pendaftaran Reseller berhasil! Cek email Anda untuk aktivasi akun.');
    }

    public function login(Request $request)
    {
        $reseller = Reseller::where('email', $request->email)->first();

        if (!$reseller) {
            return back()->withErrors([
                'error' => 'Email atau password salah',
            ]);
        }

        // cek password manual
        if (!Hash::check($request->password, $reseller->password)) {
            return back()->withErrors([
                'error' => 'Email atau password salah',
            ]);
        }

        // cek status aktif
        if ($reseller->is_active != 1) {
            return back()->withErrors([
                'error' => 'Akun Anda belum aktif. Silakan hubungi admin.',
            ]);
        }

        // login reseller
        Auth::guard('reseller')->login($reseller);

        return redirect('/reseller/dashboard');
    }

    public function logout()
    {
        Auth::guard('reseller')->logout();
        return redirect('/reseller/login');
    }


    public function activate($token)
    {
        $user = Reseller::where('token', $token)->firstOrFail();

        $user->update([
            'is_active' => 1,
            'token' => null,
        ]);

        return redirect()->route('reseller.login')->with('success', 'Akun Reseller Anda telah aktif! Silakan login.');
    }
}
