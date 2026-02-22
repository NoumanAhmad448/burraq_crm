<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RedirectIfInstructor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // If the authenticated user is an instructor, redirect
        if (auth()->check() && auth()->user()->isInstructor()) {
            return redirect()->route('admin.groups.index');
        }

        // Otherwise, continue request
        return $next($request);
    }
}