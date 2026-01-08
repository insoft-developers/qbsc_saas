<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo($request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        // DEBUG AMAN (boleh hapus setelah yakin)
        // \Log::info('Redirect path: '.$request->path());

        if ($request->is('reseller') || $request->is('reseller/*')) {
            return route('reseller.login');
        }

        return route('login');
    }
}
