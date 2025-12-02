<?php

namespace App\Http\Middleware;

use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PaketAktif
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        $user = User::find(Auth::user()->id);

        $com = Company::find($user->company_id);

        // Jika user tidak punya paket
        if (!$com->paket_id) {
            return redirect()->route('duitku.return')->with('error', 'Anda belum memiliki paket.');
        }

        // Jika paket tidak aktif
        if ($com->is_active != 1) {
            return redirect()->route('duitku.return')->with('error', 'Paket anda tidak aktif.');
        }

        // Jika paket expired
        if ($com->expired_date && Carbon::now()->gt($com->expired_date)) {
            return redirect()->route('duitku.return')->with('error', 'Paket anda sudah expired.');
        }

        
        
        return $next($request);
    }
}
