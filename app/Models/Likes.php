<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Likes extends Model
{
    protected $table = 'post_likes';

    protected $primaryKey = 'like_id';

    protected $fillable = [
    	'like_status', 'like_user_id', 'like_parent_id', 'like_parent_type'
    ];

    public function likeuser() {
    	return $this->belongsTo('App\User', 'like_user_id', 'id');
    }
}
