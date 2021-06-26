<?php

namespace App;

use App\Models\Admin;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Permission;
use App\Models\Post;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes;

    protected $guard = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'dob', 'phone', 'gender', 'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getdobAttribute($value){ // Set default end date format ...
        return Carbon::parse($value)->format('Y-M-d');
    }

    public function scopeVerified_active($query){
        return $query->where('email_verified_at','!=', null)->where('status', '!=', 'blocked');
    }

    public function userIsOnline()
    {
        return Cache::has('user-is-online-' . $this->id);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class,'role_user');
    }

    public function post()
    {
        return $this->hasMany(Post::class);
    }

    public function event()
    {
        return $this->hasMany(Event::class);
    }

    public function gallery()
    {
        return $this->hasMany(Gallery::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class,'user_permissions');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'user_id');
    }

    public function hasRole(...$roles)
    {
        foreach($roles as $role)
        {
            if($this->roles->contains('name',$role))
            {
                return true;
            }
        }
        return false;
    }
}
