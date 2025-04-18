<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class DevAutoLogin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            $user = User::find(1); // or User::find(1)
            Auth::login($user);
        }

        return $next($request);
    }
}
