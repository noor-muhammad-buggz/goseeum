<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationCheckin extends Model
{
    protected $table = 'locations_checkins';

    protected $primaryKey = 'lc_id';

    protected $fillable = [
    	'lc_location_id', 'lc_user_id', 'lc_status'
    ];

    public function user() {
    	return $this->belongsTo('App\User', 'lc_user_id', 'id');
    }

    public function location() {
    	return $this->belongsTo('App\Models\Locations', 'lc_location_id', 'id');
    }
}
