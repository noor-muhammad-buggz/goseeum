<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationComments extends Model
{
    protected $table = 'location_comments';

    protected $fillable = [
    	'comment_body', 'comment_user_id', 'comment_parent_id', 'comment_parent_type'
    ];

    public function commentuser() {
    	return $this->belongsTo('App\User', 'comment_user_id', 'id');
    }
}
