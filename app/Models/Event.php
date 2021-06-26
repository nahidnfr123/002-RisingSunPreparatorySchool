<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'description', 'user_id', 'update_at', 'event_end_date', 'event_start_date',
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

    public function event_image(){
        return $this->hasMany(EventImage::class, 'event_id');
    }

    public function event_image_Trashed(){
        return $this->hasMany(EventImage::class, 'event_id')->onlyTrashed();
    }
}
