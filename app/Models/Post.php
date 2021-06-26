<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'body', 'user_id', 'update_at', 'publish_date',
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function post_image(){
        return $this->hasMany(PostImage::class, 'post_id');
    }
    public function post_image_Trashed(){
        return $this->hasMany(PostImage::class, 'post_id')->onlyTrashed();
    }
}
