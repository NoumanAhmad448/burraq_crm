<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Hr
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        // dd($user);
        if (!$user || !$user?->role != "hr") {
            // return redirect()->route('index');
            // show 403 page
            abort(403, 'Unauthorized action.');
        }
        return $next($request);
    }
}
