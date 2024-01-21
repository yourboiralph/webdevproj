<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyEmail
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !Auth::user()->is_verified) {
            // Redirect or return an error response for unverified users
            return response()->json(['error' => 'Email not verified.'], 403);
        }

        return $next($request);
    }
}
