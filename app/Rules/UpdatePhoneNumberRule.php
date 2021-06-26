<?php

namespace App\Rules;

use App\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdatePhoneNumberRule implements Rule
{
    public $PhoneNumber;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($PhoneNumber)
    {
        $this->PhoneNumber = $PhoneNumber;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // Check unique phone number while update ...
        if(Auth::guard('admin')->check()){
            $User = User::where('id', '!=', Auth::guard('admin')->user()->id)
                ->where('phone', '=', $this->PhoneNumber)
                ->first();
            if($User !== null){
                return false;
            }
        }
        else{
            $User = User::where('id', '!=', Auth::guard('web')->user()->id)
                ->where('phone', '=', $this->PhoneNumber)
                ->first();
            if($User !== null){
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute is already taken.';
    }
}
