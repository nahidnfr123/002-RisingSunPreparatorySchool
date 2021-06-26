<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // Default ....
        /*if (Auth::guard($guard)->check()) {
            return redirect('/home');
        }

        return $next($request);*/


        // Custom ....
        if (Auth::guard($guard)->check()) {
            //return redirect('/home');
            switch ($guard){
                case 'admin':
                    if(Auth::guard($guard)->user()->email_verified_at === null){
                        Auth::logout();
                        return redirect()->route('admin.login')->with('Error', 'Your Email address is not verified.');
                    }else{
                        if(Auth::guard($guard)->user()->roles->pluck('name')->first() !== 'User'){
                            return redirect()->route('admin.dashboard'); // Auth User ia not a normal user ... Show Dashboard ...
                        }
                    }
                    break;
                default:
                    return redirect()->intended(route('home')); // If Authenticated user ia not an admin ... or is a normal user redirect to home
                    break;
            }
        }
        return $next($request);
    }
}
