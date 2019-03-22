<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationImages extends Model
{
    protected $table = 'location_images';

    protected $fillable = [
    	'location_id', 'location_image_url', 'location_caption', 'poster_id'
    ];
}
