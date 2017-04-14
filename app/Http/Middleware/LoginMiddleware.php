<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;

class LoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!Sentinel::check()){
            return redirect('login');
        }

        return $next($request);
    }
}
