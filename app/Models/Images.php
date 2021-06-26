<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Images extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];


    public function user(){
        return $this->belongsTo(User::class);
    }

}
