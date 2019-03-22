<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    protected $table = 'post_comments';
    
    protected $primaryKey = 'comment_id';

    protected $fillable = [
    	'comment_body', 'comment_user_id', 'comment_parent_id', 'comment_parent_type'
    ];

    public function commentuser() {
    	return $this->belongsTo('App\User', 'comment_user_id', 'id');
    }

}
