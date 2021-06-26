<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Admin extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'is_admin', 'job_title', 'user_id',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function post(){
        return $this->hasMany(Post::class, 'admin_id');
    }
}
