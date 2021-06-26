<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class WordCountRule implements Rule
{
    public $Attr_name, $min, $max;

    /**
     * Create a new rule instance.
     *
     * @param $Attr_name
     * @param $min
     * @param $max
     */
    public function __construct($Attr_name, $min, $max)
    {
        $this->Attr_name = $Attr_name;
        $this->min = $min;
        $this->max = $max;
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
        if(!empty($value)){
            $WordCount = count(explode(' ', $value));
            if($WordCount >= $this->min && $WordCount <= $this->max ){
                return true;
            }
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->Attr_name . ' should be within '.$this->min.' to '.$this->max.' words.';
    }
}
