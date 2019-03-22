<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPhotos extends Model
{
    protected $table = 'user_photos';

    protected $primaryKey = 'photo_id';

    protected $fillable = [
    	'photo_type', 'photo_url', 'photo_status', 'photo_user_id'
    ];

    public function user() {
    	return $this->belongsTo('App\User', 'photo_user_id', 'id');
    }
}
