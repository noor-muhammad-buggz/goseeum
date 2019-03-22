<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\UserPhotos;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'dob', 'gender', 'password','role_id', 'token', 'device_type', 'device_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at'
    ];

    public function hasRole($role) {
        $is_exist = User::join('roles', 'roles.id', 'users.role_id')->where('users.id', $this->id)->where('roles.id', $this->role_id)->where('roles.role_name', $role)->get();

        if($is_exist->isEmpty())
            return false;
        else
            return true;
    }

    public function coverphoto() {
        return UserPhotos::where(['photo_type' => 'cover', 'photo_user_id' => $this->id, 'photo_status' => 1])->orderBy('created_at', 'desc')->first();
    }

    public function profilephoto() {
        return UserPhotos::where(['photo_type' => 'profile', 'photo_user_id' => $this->id, 'photo_status' => 1])->orderBy('created_at', 'desc')->first();
    }

}
