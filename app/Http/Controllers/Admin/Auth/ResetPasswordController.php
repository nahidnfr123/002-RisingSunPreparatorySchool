<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Auth;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/admin';


    public  function __construct()
    {
        $this->middleware('guest:admin');
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    protected  function  broker(){
        return Password::broker('admins');
    }

    public function showResetPasswordForm($token){
        $PassToken = DB::table('password_resets')->where('token', $token)->first();
        if($PassToken === null){
            //return redirect(route('admin.password.request'))->withErrors('Invalid password reset request.');
            return back()->withErrors('Invalid password reset request.');
        } else{
            $Time = Carbon::parse($PassToken->created_at);
            $TimeAnd30 = Carbon::parse($Time)->addMinutes(30); // Add 30 min to the created at time ....
            if(Carbon::now() > $TimeAnd30){
                // time less then 30 .... then don't accept reset request ....
                return redirect(route('admin.password.request'))->withErrors('Password reset link has expired. Please request another password reset link.');
            }
        }

        $Email = $PassToken->email;
        return view('admin.authentication.passwords.reset', compact('Email', 'token'));
    }

    public function resetPassword(Request $request){

        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|min:8|max:60|confirmed',
        ]);

        $getPreviousData = DB::table('password_resets')->where('email', $request->email)->where('token', $request->token)->first();

        if($getPreviousData === null){
            return back()->withErrors('Invalid password reset request.');
        }
        //return redirect(route('admin.password.request'));
        //dd($request->all(), $getPreviousData);

        $User_password = User::where('email', $getPreviousData->email)->first()->password;

        if ($User_password !== null) {
            if (Hash::check($request->password, $User_password)) {
                return back()->withInput()->withErrors('New password cannot be same as Old password.');
            }
            else{
                User::where('email', $getPreviousData->email)->update(['password' => Hash::Make($request->password)]);
                return redirect(route('admin.login'))->with('Success', 'Password successfully changed. Please login.');
            }
        }else{
            return redirect(route('admin.login'))->with('ErrorMsg', 'Password reset failed. We could not find a user with this email.');
        }
    }
}
