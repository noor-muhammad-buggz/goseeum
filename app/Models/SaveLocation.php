<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaveLocation extends Model
{
    protected $table = 'locations_saved';

    protected $primaryKey = 'ls_id';

    protected $fillable = [
    	'ls_location_id', 'ls_user_id', 'ls_status'
    ];

    public function user() {
    	return $this->belongsTo('App\User', 'ls_user_id', 'id');
    }

    public function location() {
    	return $this->belongsTo('App\Models\Locations', 'ls_location_id', 'id');
    }
}
