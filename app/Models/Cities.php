<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cities extends Model
{
    protected $table = 'cities';

    protected $primaryKey = 'city_id';

    protected $fillable = [
    	'city_name', 'city_lat', 'city_lang'
    ];
}
