<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friends extends Model
{
    protected $table = 'friends';

	protected $primaryKey = 'frd_id';

    public $timestamps = true;

    protected $fillable = ['friend1_id','friend2_id','status','is_deleted','is_read','is_read_back','is_selected'];
}
