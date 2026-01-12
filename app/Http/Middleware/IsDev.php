<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsDev
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and has the 'dev' role
        if (Auth::check() && (Auth::user()?->role === "hr") || Auth::user()?->is_admin) {
            return $next($request);
        }

        // Redirect or abort if the user is not a 'dev'
        return abort(403, 'Unauthorized access.');
    }
}