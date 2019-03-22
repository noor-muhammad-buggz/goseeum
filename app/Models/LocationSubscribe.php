<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationSubscribe extends Model
{
    protected $table = 'locations_subscribed';

    protected $primaryKey = 'lsb_id';

    protected $fillable = [
    	'lsb_location_id', 'lsb_user_id', 'lsb_status'
    ];

    public function user() {
    	return $this->belongsTo('App\User', 'lsb_user_id', 'id');
    }

    public function location() {
    	return $this->belongsTo('App\Models\Locations', 'lsb_location_id', 'id');
    }
}
