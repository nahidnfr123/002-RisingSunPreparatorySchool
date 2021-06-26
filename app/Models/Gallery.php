<?php

namespace App\Models;


use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];


    public function user(){
        return $this->belongsTo(User::class);
    }

    public function gallery_image(){
        return $this->hasMany(GalleryImage::class, 'gallery_id');
    }

    public function gallery_image_Trashed(){
        return $this->hasMany(GalleryImage::class, 'gallery_id')->onlyTrashed();
    }
}
