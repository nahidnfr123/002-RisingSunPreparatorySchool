<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AdminResetPassword;
use App\Mail\resetPassAdmin;
use App\Mail\VerifyEMailAddress;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    public  function __construct()
    {
        $this->middleware('guest:admin');
    }

    protected  function  broker(){
        return Password::broker('admins');
    }
    public function showLinkRequestForm()
    {
        return view('admin.authentication.passwords.email');
    }

    protected function validateEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
    }

    public function sendResetLinkEmail(Request $request){
        $this->validateEmail($request);

        // Check for user account in database ....
        $User = User::where('email', '=', $request->email)->first();
        if($User === null){
            return back()->withInput()->withErrors('We could not find any User with this Email Address.');
        }
        else if($User->roles->pluck('name')->first() === 'User'){
            return back()->withInput()->withErrors('We could not find any User with this Email Address.');
        }

        $getPreviousResets = DB::table('password_resets')->where('email', $request->email)->get();

        if(count($getPreviousResets) > 0){
            foreach ($getPreviousResets as $getPreviousReset){
                $Time = Carbon::parse($getPreviousReset->created_at);
                $TimeAnd30 = Carbon::parse($Time)->addMinutes(30); // Add 30 min to the created at time ....
                $diff_in_minutes = $Time->diffInMinutes($TimeAnd30);
                if(Carbon::now() < $TimeAnd30){
                    // time less then 30 .... then don't accept reset request ....
                    return back()->with('Info', 'We have already send a Password Reset link to your Email.');
                }
            }
        }

        $Token = str_shuffle(Str::random(32));
        // Store token in reset password table....
        DB::table('password_resets')->insert([
            'email' => $User->email,
            'token' => $Token,
            'created_at' => Carbon::now(),
        ]);

        // Send link to Email ...
        Mail::to($User->email)->send(new resetPassAdmin($User, $Token));


        return back()->with('Success', 'Password reset link is successfully sent to your Email Address.');
    }
}
