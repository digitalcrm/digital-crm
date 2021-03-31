<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserActive
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
        if (Auth::check() && auth()->user()->active !== 1) {
            Auth::logout();

            return redirect('login')->with('error','you\'r account has been blocked 😟! plz contact to administrator');
        }
        return $next($request);
    }
}
