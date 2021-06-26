<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckStatus
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
        //Prevent login in for inactive or blocked account ...
        if(Auth::check()){
            $User = User::find(Auth::id());
            if($User->status === 'blocked' || $User->status === 'deleted' || $User->status === 'inactive'){
                Auth::logout();
                return redirect()->route('login')->with('Error', 'Your account is '. $User->status .'. Please talk to the higher authority.');
            }
            else{
                return $next($request);
            }
        }
    }
}
