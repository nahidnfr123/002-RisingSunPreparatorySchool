<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => ['logout']])->except('logout'); // Logout function is omitted because
    }

    public function showAdminLoginForm(){
        return view('admin.authentication.login');
    }

    public function login(Request $request){
        // Validate form data ...
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:8|max:60',
        ]);

        // Attempt to login the user ...
        if(Auth::guard('admin')->attempt(['email'=>$request->email, 'password' => $request->password], $request->remember)){
            // On success ... Check Account status ...
            if(Auth::guard('admin')->user()->roles->pluck('name')->first() === 'User'){ // user tried to login to the admin panel ....
                Auth::guard('admin')->logout();
                return back()->with('Error', 'Wrong Email or Password.')->withInput();
            }
            else{ // Admin is trying to login ....
                $Status = Auth::guard('admin')->user()->status;
                if(Auth::guard('admin')->user()->email_verified_at === null){ // Check email is validated ...
                    Auth::guard('admin')->logout();
                    return back()->with('Error', 'Please verify your email address first.');
                }
                else{
                    if($Status === 'Blocked' || $Status === 'Deleted' ||  $Status === 'Inactive'){
                        Auth::guard('admin')->logout();
                        return redirect()->route('admin.login')->with('Error', 'Your account is '.$Status.'. Please talk to higher authority.');
                    }
                }
            }
            return redirect()->intended(route('admin.dashboard'))->with('Success', 'Login successful.'); // Admin is successfully logged in ...
        }
        // Not success ... return back with data ... // invalid password or not admin user ...
        return redirect()->back()->with('Error', 'Wrong Email or Password.')->exceptInput('password'); // Incorrect email or password ...

        //return redirect()->back()->withInput($request->Input::only('email', 'remember'));
    }

    public function logout(){
        Auth::guard('admin')->logout();
        session()->flush();
        return redirect('/');
    }
}
