<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\NotificationController;
use App\User;
use Auth;
use DB;

class UserController extends Controller
{
    public function __construct() {   
    }

    /*
    |--------------------------------------------------------------------------
    | get all cities list
    |--------------------------------------------------------------------------
    */
    public function index() {
        $userId = Auth::user()->id;
    	$users = User::where('id', '!=', $userId)->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.users.list', compact('users'));
    }

    /*
    |--------------------------------------------------------------------------
    | delete requested user
    |--------------------------------------------------------------------------
    */
    public function delete($id) {
        try {
            $city = User::where('id', $id)->first();
            if(empty($city)) {
                return redirect()->to('users')->with('error', 'Requested user not found');
            }
            
            $result = User::where('id', $id)->delete();
            if($result) {
                return redirect()->to('users')->with('message', 'User deleted successfully');
            }
            else {
                return redirect()->to('users')->with('error', 'Unable to delete user at the moment');
            }
        } catch (\Exception $e) {
            return redirect()->to('users')->with('error', 'Unable to delete user at the moment');
        }
    }

}
