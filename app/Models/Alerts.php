<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alerts extends Model
{
    protected $table = 'alerts';

	protected $primaryKey = 'alert_id';

    public $timestamps = true;

    protected $fillable = ['payload','user_id','sender_id','type','is_read'];

    public function reciever() {
    	return $this->belongsTo('App\User', 'user_id', 'id');
    }

    public function sender() {
    	return $this->belongsTo('App\User', 'sender_id', 'id');
    }
}
