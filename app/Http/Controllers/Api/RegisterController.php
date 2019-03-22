<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Models\UserPhotos;
use App\User;
use Hash;

class RegisterController extends Controller
{
    /*
    |----------------------------------------------------------------------
    | register requested user
    | @params [first_name, last_name, email, dob, gender, password]
    |----------------------------------------------------------------------
    */
    
    public function RegisterUser(Request $request) {
    	$params = $request->all();
    	// required parameters
    	$rules = array(
	        'first_name' => 'required|string|max:255',
            'last_name' => 'string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'dob' => 'required|date_format:"d/m/Y"',
            'gender' => 'required',
            'password' => 'required|string|min:6',
	    );
	    $messages = array(
	        'first_name.required' => 'Please provide first name',
	        'first_name.max' => 'Please provide maximum 255 characters for first name',
	        'last_name.max' => 'Please provide maximum 255 characters for last name',
	        'email.required' => 'Please provide email',
	        'email.email' => 'Please provide a valid email',
	        'email.unique' => 'Email already taken, please provide another email',
	        'email.max' => 'Please provide maximum 255 characters for email',
	        'dob.required' => 'Please provide date of birth',
	        'dob.date_format' => 'Please provide date of birth with valid format e.g day/month/year',
	        'gender.required' => 'Please provide gender',
	        'password.required' => 'Please provide password',
	        'password.min' => 'Please provide minimum 6 characters for password',
	    );

	    $validate = $this->ValidateParams($params, $rules, $messages);
	    if($validate != 1) {
	    	return \response()->json($validate);
	    }

	    // create user in db with token
	    $response = \Config::get('app.api_response');
	    $insertData = $request->except('token');
	    $insertData['password'] = Hash::make($insertData['password']);
	    $insertData['role_id'] = 2;
	    $insertData['token'] = 'auth0|'.uniqid();
	    $user = User::create($insertData);
	    if($user) {
	        // check if image exists in request
			if($request->has('photo')) {
                $photo = Input::file('photo');
                if($photo->isValid()) {				
					$photoname = uniqid().".".$photo->getClientOriginalExtension();
					$uploadData = $photo->move('uploads', $photoname);
					UserPhotos::create(['photo_type' => 'profile', 'photo_url' => $photoname, 'photo_status' => 1, 'photo_user_id' => $user->id]);
                }
            }
	    	$response['status'] = \Config::get('app.success_status');
	    	$response['message'] = 'You have registered successfully';
	    	$response['data']->user = $user;
	    }
	    else {
	    	$response['status'] = \Config::get('app.failure_status');
	    	$response['message'] = 'Your registration failed, please try again';
	    }
	    return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | register requested user
    | @params [email, password]
    |----------------------------------------------------------------------
    */
    
    public function LoginUser(Request $request) {
    	$params = $request->all();
    	// required parameters
    	$rules = array(
	        'email' => 'required',
            'password' => 'required',
	    );
	    $messages = array(
	        'email.required' => 'Please provide email',
	        'password.required' => 'Please provide password',
	    );

	    $validate = $this->ValidateParams($params, $rules, $messages);
	    if($validate != 1) {
	    	return \response()->json($validate);
	    }

	    // create user in db with token
	    $response = \Config::get('app.api_response');
	    $user = User::where(['email' => $params['email'], 'role_id' => 2])->first();
	    if($user) {
	    	if(Hash::check($params['password'], $user->password)) {
	    		// check if device token and type are mentioned
				if(isset($params['device_type']) && !empty($params['device_type']) && isset($params['device_token']) && !empty($params['device_token']) && in_array($params['device_type'], array('android', 'ios'))) {
					$user->device_type = $params['device_type'];
					$user->device_token = $params['device_token'];
					$user->save();
				}
				// check if token is empty
	    		if(empty($user->token)) {
	    			$user->token = 'auth0|'.uniqid();
	    			$user->save();
	    		}
	    		$user->coverphoto = (!empty($user->coverphoto())) ? url('uploads/'.$user->coverphoto()->photo_url) : '';
	    		$user->profilephoto = (!empty($user->profilephoto())) ? url('uploads/'.$user->profilephoto()->photo_url) : '';
		    	$response['status'] = \Config::get('app.success_status');
		    	$response['message'] = 'You have loggedin successfully';
		    	$response['data']->user = $user;
	    	}
	    	else {
	    		$response['status'] = \Config::get('app.failure_status');
	    		$response['message'] = 'Incorrect password, please provide correct password';
	    	}
	    }
	    else {
	    	$response['status'] = \Config::get('app.failure_status');
	    	$response['message'] = 'Incorrect email, please provide correct email';
	    }
	    return \response()->json($response);
    }
    
    /*
    |----------------------------------------------------------------------
    | logout requested user
    | @params [token]
    |----------------------------------------------------------------------
    */
    
    public function UserLogout(Request $request) {
    	$params = $request->all();
	    // create user in db with token
	    $response = \Config::get('app.api_response');
	    $user = User::where(['id' => $request->user->id])->first();
	    if($user) {
			// check if device token and type are mentioned
			$user->device_type = '';
			$user->device_token = '';
			$result = $user->save();
			if($result) {
				$response['status'] = \Config::get('app.success_status');
				$response['message'] = 'You have logout successfully';
			}
			else {
				$response['status'] = \Config::get('app.failure_status');
	    		$response['message'] = 'Unable to logout at the moment';	
			}
	    }
	    else {
	    	$response['status'] = \Config::get('app.failure_status');
	    	$response['message'] = 'Unable to logout at the moment';
	    }
	    return \response()->json($response);
    }

	/*
    |----------------------------------------------------------------------
    | online/offline requested user
    | @params [token]
    |----------------------------------------------------------------------
    */
    
    public function UserOnline(Request $request) {
		$params = $request->all();
		// required parameters
    	$rules = array(
	        'state' => 'required'
	    );
	    $messages = array(
	        'state.required' => 'Please provide user state'
	    );

	    $validate = $this->ValidateParams($params, $rules, $messages);
	    if($validate != 1) {
	    	return \response()->json($validate);
		}
		
	    $response = \Config::get('app.api_response');
	    $user = User::where(['id' => $request->user->id])->first();
	    if($user) {
			// check if device token and type are mentioned
			$user->is_online = $params['state'];
			$result = $user->save();
			if($result) {
				$response['status'] = \Config::get('app.success_status');
				$response['message'] = 'State changed successfully';
			}
			else {
				$response['status'] = \Config::get('app.failure_status');
	    		$response['message'] = 'Unable to change state at the moment';	
			}
	    }
	    else {
	    	$response['status'] = \Config::get('app.failure_status');
	    	$response['message'] = 'Unable to change state at the moment';
	    }
	    return \response()->json($response);
	}
	
    /*
    |----------------------------------------------------------------------
    | validate request params
    |----------------------------------------------------------------------
    */
    private function ValidateParams($params, $rules, $messages) {
        // validate request parameters
        $validator = \Validator::make($params, $rules, $messages);

        if ($validator->fails()) {
        	$response = \Config::get('app.api_response');
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'some information is missing';
            $response['errors'] = $validator->errors();
            return $response;
        }
        return 1;
    }
}
