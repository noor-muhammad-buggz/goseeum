<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Models\UserPhotos;
use App\Models\Locations;
use App\Models\LocationImages;
use App\Models\Posts;
use App\Models\PostsMeta;
use App\Models\Alerts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Hash;

class ProfileController extends Controller
{
    /*
    |----------------------------------------------------------------------
    | get profile for requested user
    | @params [token]
    |----------------------------------------------------------------------
    */
    public function GetProfile(Request $request) {
        $baseUrl = \Config::get('app.base_url');
        $noProfilePic = \Config::get('app.no_profile_pic');
        $noCoverPic = \Config::get('app.no_cover_pic');
        $params = $request->all();
        $response = \Config::get('app.api_response');
        $user_id = $request->user->id;
        // perform insertion if new record
        try {
            $user = User::where(['id' => $user_id, 'role_id' => 2])->first();
            if(empty($user)) {
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'Unable to find requested profile';
                return \response()->json($response);        
            }
            unset($user->role_id, $user->token, $user->device_type, $user->device_token);
            $user->coverphoto = (!empty($user->coverphoto())) ? ($baseUrl.'/'.$user->coverphoto()->photo_url) : $noProfilePic;
	    	$user->profilephoto = (!empty($user->profilephoto())) ? ($baseUrl.'/'.$user->profilephoto()->photo_url) : $noCoverPic;
            $response['status'] = \Config::get('app.success_status');
            $response['message'] = 'Here is your profile';
            $response['data']->user = $user;
        }
        catch (\PDOException $e) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to get your profile';
        }
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | update profile for requested user
    | @params [token, first_name, last_name, gender, dob]
    |----------------------------------------------------------------------
    */
    public function UpdateProfile(Request $request) {
        $params = $request->all();
        // required parameters
    	$rules = array(
	        'first_name' => 'required|string|max:255',
            'last_name' => 'string|max:255',
            'dob' => 'required|date_format:"d/m/Y"',
            'gender' => 'required'
	    );
	    $messages = array(
	        'first_name.required' => 'Please provide first name',
	        'first_name.max' => 'Please provide maximum 255 characters for first name',
	        'last_name.max' => 'Please provide maximum 255 characters for last name',
	        'dob.required' => 'Please provide date of birth',
	        'dob.date_format' => 'Please provide date of birth with valid format e.g day/month/year',
	        'gender.required' => 'Please provide gender',
	    );

	    $validate = $this->ValidateParams($params, $rules, $messages);
	    if($validate != 1) {
	    	return \response()->json($validate);
        }
        
        $baseUrl = \Config::get('app.base_url');
        $noProfilePic = \Config::get('app.no_profile_pic');
        $noCoverPic = \Config::get('app.no_cover_pic');
        $response = \Config::get('app.api_response');
        $user_id = $request->user->id;
        $data = $request->only(['first_name','last_name','dob','gender']);
        // perform insertion if new record
        try {
            $exists = User::where(['id' => $user_id, 'role_id' => 2])->first();
            if(empty($exists)) {
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'Unable to find requested profile';
                return \response()->json($response);        
            }
            // check if profile updated
            $result = User::where(['id' => $user_id, 'role_id' => 2])->update($data);
            if($result) {
                $user = User::where(['id' => $user_id, 'role_id' => 2])->first();
                unset($user->role_id, $user->token, $user->device_type, $user->device_token);
                $user->coverphoto = (!empty($user->coverphoto())) ? ($baseUrl.'/'.$user->coverphoto()->photo_url) : $noProfilePic;
                $user->profilephoto = (!empty($user->profilephoto())) ? ($baseUrl.'/'.$user->profilephoto()->photo_url) : $noCoverPic;
                $response['status'] = \Config::get('app.success_status');
                $response['message'] = 'Profile updated successfully';
                $response['data']->user = $user;
            }
            else {
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'Unable to update your profile';
            }
        }
        catch (\PDOException $e) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to get your profile';
            $response['ex'] = $e->getMessage();
        }
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | change password for requested user
    | @params [token, old_password, new_password]
    |----------------------------------------------------------------------
    */
    public function UpdatePassword(Request $request) {
        $params = $request->all();
        // required parameters
    	$rules = array(
	        'old_password' => 'required',
            'new_password' => 'required|string|min:6'
	    );
	    $messages = array(
	        'old_password.required' => 'Please provide old password',
	        'new_password.required' => 'Please provide new password',
	        'new_password.min' => 'Please provide minimum 6 characters for password'
	    );

	    $validate = $this->ValidateParams($params, $rules, $messages);
	    if($validate != 1) {
	    	return \response()->json($validate);
        }
        
        $response = \Config::get('app.api_response');
        $user_id = $request->user->id;
        // perform insertion if new record
        try {
            $user = User::where(['id' => $user_id, 'role_id' => 2])->first();
            if(empty($user)) {
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'Unable to find requested profile';
                return \response()->json($response);        
            }
            // check if old password is correct
            if(Hash::check($params['old_password'], $user->password)) {
                $user->password = Hash::make($params['new_password']);
                $result = $user->save();
                if($result) {
                    $response['status'] = \Config::get('app.success_status');
		    	    $response['message'] = 'Password changed successfully';
                }
                else {
                    $response['status'] = \Config::get('app.failure_status');
	    		    $response['message'] = 'Unable to change password at the moment';
                }
	    	}
	    	else {
	    		$response['status'] = \Config::get('app.failure_status');
	    		$response['message'] = 'Please provide correct old password';
	    	}
        }
        catch (\PDOException $e) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to change password at the moment';
            $response['ex'] = $e->getMessage();
        }
        return \response()->json($response);
    }

	/*
    |--------------------------------------------------------------------------
    | upload cover photo
    | @params[token, photo]
    |--------------------------------------------------------------------------
    */
    public function UploadCoverPhoto(Request $request){
        $params = $request->all();
        $response = \Config::get('app.api_response');
        // required parameters
        $rules = array(
            'photo' => 'required'
        );
        $messages = array(
            'photo.required' => 'Please provide a photo to upload'
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }

        $response = \Config::get('app.api_response');
        $user_id = $request->user->id;
        $photo = $request->file('photo');
        $type = 'cover';

        if (!$photo->isValid()) {
        	$response['status'] = \Config::get('app.failure_status');
        	$response['message'] = 'Please upload a valid image';
        	return \response()->json($response);
        }
        elseif (!in_array($photo->getClientOriginalExtension(), array('jpeg', 'JPEG', 'jpg', 'JPG', 'png', 'PNG'))) {
        	$response['status'] = \Config::get('app.failure_status');
        	$response['message'] = 'Only jpeg and png images are allowed';
        	return \response()->json($response);
        }

        $photoname = uniqid().".".$photo->getClientOriginalExtension();
        $photo->move("uploads", $photoname);

        $photoObj = UserPhotos::create(['photo_type' => $type, 'photo_url' => $photoname, 'photo_status' => 1, 'photo_user_id' => $user_id]);
        if($photoObj) {
            // create post when cover or profile upated
            try{
                $post_content = ($type == 'profile') ? 'Updated profile photo' : 'Upated cover photo';
                $post = Posts::create(['post_content' => $post_content, 'user_id' => $user_id]);
                if($post) {
                    PostsMeta::create(['post_id' => $post->post_id, 'meta_url' => $photoname]);
                }
            }
            catch(\Exception $ex){}
        	$response['status'] = \Config::get('app.success_status');
        	$response['message'] = 'Cover photo uploaded successfully';
        	$response['data']->coverphoto = url('uploads/'.$photoname);
            return \response()->json($response);
        }
        else {
            $response['status'] = \Config::get('app.failure_status');
        	$response['message'] = 'Unable to upload cover at the moment';
        	return \response()->json($response);
        }
    }

    /*
    |----------------------------------------------------------------------
    | get cover photo for requested user
    | @params [token]
    |----------------------------------------------------------------------
    */
    public function GetCoverPhoto(Request $request) {
        $params = $request->all();
        $response = \Config::get('app.api_response');

        // perform insertion if new record
        try {
            $photo = UserPhotos::where(['photo_user_id' => $request->user->id, 'photo_status' => 1, 'photo_type' => 'cover'])->orderBy('created_at', 'desc')->first();
            $response['status'] = \Config::get('app.success_status');
            $response['message'] = 'Here is your profile cover';
            $response['data']->coverphoto = (!empty($photo)) ? url('uploads/'.$photo->photo_url) : '';
        }
        catch (\PDOException $e) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to get profile cover';
        }
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | get profile photo for requested user
    | @params [token]
    |----------------------------------------------------------------------
    */
    public function GetProfilePhoto(Request $request) {
        $params = $request->all();
        $response = \Config::get('app.api_response');

        // perform insertion if new record
        try {
            $photo = UserPhotos::where(['photo_user_id' => $request->user->id, 'photo_status' => 1, 'photo_type' => 'profile'])->orderBy('created_at', 'desc')->first();
            $response['status'] = \Config::get('app.success_status');
            $response['message'] = 'Here is your profile photo';
            $response['data']->profilephoto = (!empty($photo)) ? url('uploads/'.$photo->photo_url) : '';
        }
        catch (\PDOException $e) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to get profile photo';
        }
        return \response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | upload profile photo
    | @params[token, photo]
    |--------------------------------------------------------------------------
    */
    public function UploadProfilePhoto(Request $request){
        $params = $request->all();
        $response = \Config::get('app.api_response');
        // required parameters
        $rules = array(
            'photo' => 'required'
        );
        $messages = array(
            'photo.required' => 'Please provide a photo to upload'
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }

        $response = \Config::get('app.api_response');
        $user_id = $request->user->id;
        $photo = $request->file('photo');
        $type = 'profile';

        if (!$photo->isValid()) {
        	$response['status'] = \Config::get('app.failure_status');
        	$response['message'] = 'Please upload a valid image';
        	return \response()->json($response);
        }
        elseif (!in_array($photo->getClientOriginalExtension(), array('jpeg', 'JPEG', 'jpg', 'JPG', 'png', 'PNG'))) {
        	$response['status'] = \Config::get('app.failure_status');
        	$response['message'] = 'Only jpeg and png images are allowed';
        	return \response()->json($response);
        }

        $photoname = uniqid().".".$photo->getClientOriginalExtension();
        $photo->move("uploads", $photoname);

        $photoObj = UserPhotos::create(['photo_type' => $type, 'photo_url' => $photoname, 'photo_status' => 1, 'photo_user_id' => $user_id]);
        if($photoObj) {
            // create post when cover or profile upated
            try{
                $post_content = ($type == 'profile') ? 'Updated profile photo' : 'Upated cover photo';
                $post = Posts::create(['post_content' => $post_content, 'user_id' => $user_id]);
                if($post) {
                    PostsMeta::create(['post_id' => $post->post_id, 'meta_url' => $photoname]);
                }
            }
            catch(\Exception $ex){}
        	$response['status'] = \Config::get('app.success_status');
        	$response['message'] = 'Profile photo uploaded successfully';
        	$response['data']->profilephoto = url('uploads/'.$photoname);
            return \response()->json($response);
        }
        else {
            $response['status'] = \Config::get('app.failure_status');
        	$response['message'] = 'Unable to upload profile photo at the moment';
        	return \response()->json($response);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | get user photos of all types
    | @params[token, photo]
    |--------------------------------------------------------------------------
    */
    public function GetPhotos(Request $request){
        $params = $request->all();
        $response = \Config::get('app.api_response');
        $user_id = $request->user->id;

        $baseUrl = url('uploads');
        $profiles = UserPhotos::where(['photo_type' => 'profile', 'photo_status' => 1, 'photo_user_id' => $user_id])->selectRaw("photo_id as id, CONCAT('$baseUrl', '/', photo_url) as url")->get();

        $covers = UserPhotos::where(['photo_type' => 'cover', 'photo_status' => 1, 'photo_user_id' => $user_id])->selectRaw("photo_id as id, CONCAT('$baseUrl', '/', photo_url) as url")->get();

        $posts = PostsMeta::join('posts as p', 'p.post_id', 'posts_meta.post_id')->where(['p.user_id' => $user_id])->selectRaw("posts_meta.meta_id as id, CONCAT('$baseUrl', '/', posts_meta.meta_url) as url")
        ->get();

        $locations = LocationImages::where(['poster_id' => $user_id])->selectRaw("id, CONCAT('$baseUrl', '/', location_image_url) as url")
        ->get();

        $response['status'] = \Config::get('app.success_status');
        $response['message'] = 'Your photos list';
        $response['data']->profiles = $profiles;
        $response['data']->covers = $covers;
        $response['data']->posts = $posts;
        $response['data']->locations = $locations;
        return \response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | get user profile photos
    | @params[token]
    |--------------------------------------------------------------------------
    */
    public function GetProfilePhotos(Request $request){
        $params = $request->all();
        $response = \Config::get('app.api_response');
        $user_id = $request->user->id;
        $baseUrl = url('uploads');
        $profiles = UserPhotos::where(['photo_type' => 'profile', 'photo_status' => 1, 'photo_user_id' => $user_id])->selectRaw("photo_id as id, CONCAT('$baseUrl', '/', photo_url) as url")->paginate(20);
        $response['status'] = \Config::get('app.success_status');
        $response['message'] = 'Your profile photos list';
        $response['data']->profiles = $profiles->items();
        return \response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | get user cover photos
    | @params[token]
    |--------------------------------------------------------------------------
    */
    public function GetCoverPhotos(Request $request){
        $params = $request->all();
        $response = \Config::get('app.api_response');
        $user_id = $request->user->id;
        $baseUrl = url('uploads');
        $covers = UserPhotos::where(['photo_type' => 'cover', 'photo_status' => 1, 'photo_user_id' => $user_id])->selectRaw("photo_id as id, CONCAT('$baseUrl', '/', photo_url) as url")->paginate(20);
        $response['status'] = \Config::get('app.success_status');
        $response['message'] = 'Your cover photos list';
        $response['data']->covers = $covers->items();
        return \response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | get user post photos
    | @params[token]
    |--------------------------------------------------------------------------
    */
    public function GetPostPhotos(Request $request){
        $params = $request->all();
        $response = \Config::get('app.api_response');
        $user_id = $request->user->id;
        $baseUrl = url('uploads');
        $posts = PostsMeta::join('posts as p', 'p.post_id', 'posts_meta.post_id')->where(['p.user_id' => $user_id])->selectRaw("posts_meta.meta_id as id, CONCAT('$baseUrl', '/', posts_meta.meta_url) as url")
        ->paginate(20);
        $response['status'] = \Config::get('app.success_status');
        $response['message'] = 'Your post photos list';
        $response['data']->posts = $posts->items();
        return \response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | get user location photos
    | @params[token]
    |--------------------------------------------------------------------------
    */
    public function GetLocationPhotos(Request $request){
        $params = $request->all();
        $response = \Config::get('app.api_response');
        $user_id = $request->user->id;
        $baseUrl = url('uploads');
        $locations = LocationImages::where(['poster_id' => $user_id])->selectRaw("id, CONCAT('$baseUrl', '/', location_image_url) as url")
        ->paginate(20);
        $response['status'] = \Config::get('app.success_status');
        $response['message'] = 'Your location photos list';
        $response['data']->locations = $locations->items();
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | get privacy policy
    | @params [token]
    |----------------------------------------------------------------------
    */
    public function GetPrivacy(Request $request) {
        $params = $request->all();
        $response = \Config::get('app.api_response');

        // perform insertion if new record
        try {
            $privacy = \DB::table('terms_and_privacy')->where(['type' => 'privacy'])->select('title','content')->first();
            $response['status'] = \Config::get('app.success_status');
            $response['message'] = 'Here is privacy policy';
            $response['data']->privacy = (empty($privacy) ? "" : $privacy);
        }
        catch (\PDOException $e) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to get privacy policy';
        }
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | get terms and conditions
    | @params [token]
    |----------------------------------------------------------------------
    */
    public function GetTerms(Request $request) {
        $params = $request->all();
        $response = \Config::get('app.api_response');

        // perform insertion if new record
        try {
            $terms = \DB::table('terms_and_privacy')->where(['type' => 'terms'])->select('title','content')->first();
            $response['status'] = \Config::get('app.success_status');
            $response['message'] = 'Here are terms and conditions';
            $response['data']->terms = (empty($terms) ? "" : $terms);
        }
        catch (\PDOException $e) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to get terms and conditions';
        }
        return \response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | get notifications detail function
    | @params [token]
    |--------------------------------------------------------------------------
    */
    public function GetNotifications(Request $request) {
        $params = $request->all();
        $baseUrl = \Config::get('app.base_url');
        $noProfilePic = \Config::get('app.no_profile_pic');
        $noCoverPic = \Config::get('app.no_cover_pic');
        $response = \Config::get('app.api_response');
        // required parameters
        $rules = array();
        $messages = array();
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }

        $user_id = $request->user->id;
        // get notifications data
        $notifications = Alerts::where(['alerts.user_id' => $user_id])
            ->selectRaw("alerts.alert_id, alerts.payload, alerts.type, alerts.is_read, (SELECT CASE WHEN up.photo_url IS NOT NULL THEN CONCAT('$baseUrl', '/', up.photo_url) ELSE '$noProfilePic' END FROM user_photos as up WHERE up.photo_user_id = alerts.sender_id ORDER BY up.created_at LIMIT 1) as sender_pic, (SELECT u.is_online FROM users as u WHERE u.id=alerts.sender_id) as is_online, alerts.created_at, alerts.is_read")
            ->orderBy('alerts.created_at', 'desc')->get();

        // return response to mobile
        $response['status'] = \Config::get('app.success_status');
    	$response['message'] = 'Here is notifications list';
    	$response['data']->notifications = $notifications;
        return response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | delete notification function
    | @params [token, noti_id]
    |--------------------------------------------------------------------------
    */
    public function DeleteNotification(Request $request) {
        $params = $request->all();
        $response = \Config::get('app.api_response');
        // validate request
        $rules = [
            'noti_id' => 'required'
        ];
        $messages = [
            'noti_id.required' => 'Please provide notificaton id to delete'
        ];
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }

        $user_id = $request->user->id;
        // get previously added data
        $noti = Alerts::where(['user_id' => $user_id, 'alert_id' => $params['noti_id']])->first();
        if(empty($noti)) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Requested record not found';
            return \response()->json($response);
        }
        
        // delete requested record for notifiacation
        $result = $noti->delete();
        if($result) {
            $response['status'] = \Config::get('app.success_status');
            $response['message'] = 'Requested record deleted successfully';
            return \response()->json($response);
        }
        else {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to delete requested record at the moment';
            return \response()->json($response);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | mark notification as read function
    | @params [token, noti_id]
    |--------------------------------------------------------------------------
    */
    public function MarkNotificationRead(Request $request) {
        $params = $request->all();
        $response = \Config::get('app.api_response');
        // validate request
        $rules = [
            'noti_id' => 'required'
        ];
        $messages = [
            'noti_id.required' => 'Please provide notificaton id to delete'
        ];
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }

        $user_id = $request->user->id;
        // get previously added data
        $noti = Alerts::where(['user_id' => $user_id, 'alert_id' => $params['noti_id'], 'is_read' => 0])->first();
        if(empty($noti)) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Requested record not found';
            return \response()->json($response);
        }
        
        // update requested record for notifiacation
        $noti->is_read = 1;
        $result = $noti->save();
        if($result) {
            $response['status'] = \Config::get('app.success_status');
            $response['message'] = 'Requested record updated successfully';
            return \response()->json($response);
        }
        else {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to update requested record at the moment';
            return \response()->json($response);
        }
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
