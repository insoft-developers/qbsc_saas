<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::if('comtype', function ($type) {
            $user = User::find(Auth::user()->id);
            $com = Company::find($user->company_id);
            $comType = $com->is_peternakan;
            return Auth::check() && $comType == $type;
        });
    }
}
