<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{
    protected $table = 'posts';
    
    protected $primaryKey = 'post_id';

    protected $fillable = [
    	'post_content', 'user_id'
    ];

    public function postmeta() {
    	return $this->hasMany('App\Models\PostsMeta', 'post_id', 'post_id');
    }

    public function media() {
        $baseUrl = url('uploads');
    	return $this->hasMany('App\Models\PostsMeta', 'post_id', 'post_id')->selectRaw("meta_id as id, CONCAT('$baseUrl', '/', meta_url) as url, post_id");
    }

    public function postcomments() {
        return $this->hasMany('App\Models\Comments', 'comment_parent_id', 'post_id')->orderBy('created_at', 'desc');
    }

    public function postlikes() {
        return $this->hasMany('App\Models\Likes', 'like_parent_id', 'post_id')->orderBy('created_at', 'desc');
    }

    public function postuser() {
    	return $this->belongsTo('App\User', 'user_id', 'id');
    }
}
