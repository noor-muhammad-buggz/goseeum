<?php

namespace App\Models;
use App\Models\LocationHours;

use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    protected $table = 'locations';

    protected $fillable = [
    	'location_name', 'location_lat', 'location_lang', 'loation_ar_view', 'location_description', 'location_type', 'location_adress', 'location_tags', 'user_id', 'google_place_id', 'location_tags', 'location_address', 'status'
    ];

    public function images() {
    	return $this->hasMany('App\Models\LocationImages','location_id', 'id')->orderBY('created_at', 'desc');
    }

    public function rating() {
        return $this->hasMany('App\Models\LocationRating','lr_location_id', 'id');
    }

    public function checkins() {
        return $this->hasMany('App\Models\LocationCheckin','lc_location_id', 'id');
    }

    public function savedlist() {
        return $this->hasMany('App\Models\SaveLocation','ls_location_id', 'id');
    }

    public function subscriptions() {
        return $this->hasMany('App\Models\LocationSubscribe','lsb_location_id', 'id');
    }

    public function openinghours() {
        return $this->hasMany('App\Models\LocationHours','lh_location_id', 'id');
    }

    public function getday($loc, $day) {
        $daystart = LocationHours::where(['lh_location_id' => $loc, 'lh_day' => $day])->first();
        return $daystart;
    }

    public function comments() {
    	return $this->hasMany('App\Models\LocationComments','comment_parent_id', 'id')->orderBY('created_at', 'desc');
    }
    
    public function getLocationArViewAttribute($val) {
    	return empty($val) ? "" : (string)$val;
    }

    public function getLocationDescriptionAttribute($val) {
    	return empty($val) ? "" : (string)$val;
    }

    public function getLocationAddressAttribute($val) {
    	return empty($val) ? "" : (string)$val;
    }

    public function getLocationTagsAttribute($val) {
    	return empty($val) ? "" : (string)$val;
    }

    public function getGooglePlaceIdAttribute($val) {
    	return empty($val) ? "" : (string)$val;
    }
}
