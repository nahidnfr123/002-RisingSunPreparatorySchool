<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //dd(Auth::user());
        if(Auth::check()){
            if(Auth::user()->role()->pluck('name')->first() !== 'User') {
                $this->redirectTo = route('admin.dashboard');
            } else{
                $this->redirectTo = route('home');
            }
        }else{
            if(!Auth::guard('admin')){
                $this->redirectTo = route('login');
            }
        }
        $this->middleware('guest')->except('logout');
    }
}
