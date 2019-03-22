<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationRating extends Model
{
    protected $table = 'locations_rating';

    protected $primaryKey = 'lr_id';

    protected $fillable = [
    	'lr_location_id', 'lr_user_id', 'lr_rating'
    ];

    public function user() {
    	return $this->belongsTo('App\User', 'lr_user_id', 'id');
    }

    public function location() {
    	return $this->belongsTo('App\Models\Locations', 'lr_location_id', 'id');
    }
}
