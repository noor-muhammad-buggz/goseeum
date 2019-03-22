<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    protected $table = 'notifications';

	protected $primaryKey = 'noti_id';

    public $timestamps = true;

    protected $fillable = ['noti_body','noti_url','is_read','is_selected','user_id','reciever_id'];
}
