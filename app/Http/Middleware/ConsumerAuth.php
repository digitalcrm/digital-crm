<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ConsumerAuth
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
        // echo $request->path();
        // echo "<br>";
        // echo json_encode($request->session()->has('user'));
        // exit();

        if (($request->path() == 'cart') && ($request->session()->has('user'))) {
            // return $next($request);
            return redirect('/cart/products');
        } else {
            $request->session()->flush();
            $request->session()->regenerate();
            return redirect('/cart');
        }

        // return $next($request);
    }
}
