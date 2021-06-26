<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
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
        if(Auth::guard('admin')->check() && Auth::guard('admin')->user()->roles->pluck('name')->first() !== 'User') {
            return $next($request);
        }
        else{
            //return redirect()->route('login'); // if non admin user trys to access admin page
            return redirect()->back();
        }
    }
}
