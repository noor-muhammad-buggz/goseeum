<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationHours extends Model
{
    protected $table = 'location_hours';

    protected $primaryKey = 'lh_id';

    protected $fillable = [
    	'lh_location_id', 'lh_day', 'lh_open', 'lh_close', 'lh_is_holiday'
    ];
}
