<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostsMeta extends Model
{
    protected $table = 'posts_meta';

    protected $fillable = [
    	'meta_url', 'meta_type', 'post_id'
    ];
}
