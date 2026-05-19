<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MustChangePassword
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->must_change_password) {
            if (!$request->routeIs('password.change') && !$request->routeIs('password.change.update')) {
                return redirect()->route('password.change');
            }
        }

        return $next($request);
    }
}