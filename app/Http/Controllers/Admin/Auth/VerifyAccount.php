<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VerifyAccount extends Controller
{
    public function verification($id, $token){
        $id = $this->decryptID($id);
        $User = User::where('id','=', $id);

        if($User->first() !== null){
            if($User->first()->admin->token !== $token){
                return redirect()->route('admin.login')->with('Error', 'Your activation request is invalid.');
            } else{
                $User->update(['email_verified_at' => Carbon::now()]);
                return redirect()->route('admin.login')->with('Success', 'Account successfully verified. Please login.');
            }
        }
        return redirect()->route('admin.login')->with('Error', 'Your activation request is invalid.');
    }
}
