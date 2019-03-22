<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\User;
use App\Models\Friends;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\NotificationController;
use App\Models\Conversations;
use App\Models\Notifications;
use App\Models\Alerts;

class FriendsController extends Controller
{
    private $convObj;
    public function __construct() {
		$this->convObj = new Conversations();
	}
    /*
    |----------------------------------------------------------------------
    | load search people page
    | @params[token, search]
    |----------------------------------------------------------------------
    */
    public function SearchPeople(Request $request) {
        $response = \Config::get('app.api_response');
        $params = $request->all();
        $baseUrl = \Config::get('app.base_url');
        $noProfilePic = \Config::get('app.no_profile_pic');
        $noCoverPic = \Config::get('app.no_cover_pic');
    	// required parameters
    	$rules = array(
	        'search' => 'required',
	    );
	    $messages = array(
	        'search.required' => 'Please provide some text to search',
	    );

	    $validate = $this->ValidateParams($params, $rules, $messages);
	    if($validate != 1) {
	    	return \response()->json($validate);
        }
        
        // params for searching
        $search = $params['search'];
        $user_id = $request->user->id;

        $people = User::whereRaw("(CONCAT(users.first_name,' ',users.last_name) LIKE '%$search%' OR users.email LIKE '%$search%')")
        ->where('id','!=', $user_id)
        ->where('role_id', 2)
        ->whereRaw("(SELECT COUNT(*) FROM friends as f WHERE (f.friend1_id = users.id AND f.friend2_id = '$user_id') OR (f.friend2_id = users.id AND f.friend1_id = '$user_id')) <= 0")
        ->selectRaw("users.id, users.first_name, users.last_name, users.gender, users.created_at as member_since, COALESCE((SELECT CONCAT('$baseUrl','/',up.photo_url) FROM user_photos as up WHERE up.photo_type = 'profile' AND up.photo_status = 1 AND up.photo_user_id = users.id ORDER BY up.created_at DESC LIMIT 1), '$noProfilePic') as profilephoto, COALESCE((SELECT CONCAT('$baseUrl','/',up.photo_url) FROM user_photos as up WHERE up.photo_type = 'cover' AND up.photo_status = 1 AND up.photo_user_id = users.id ORDER BY up.created_at DESC LIMIT 1), '$noCoverPic') as coverphoto")
        ->get();

        $response['status'] = \Config::get('app.success_status');
        $response['message'] = (count($people) > 0) ? 'Here is list of people' : 'No people found against your search';
        $response['data']->people = $people;
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | send friend request to user
    | @params[token, friend_id]
    |----------------------------------------------------------------------
    */
    public function SendFriendRequest(Request $request) {
        $response = \Config::get('app.api_response');
        $params = $request->all();
    	// required parameters
    	$rules = array(
	        'friend_id' => 'required',
	    );
	    $messages = array(
	        'friend_id.required' => 'Please provide friend id to send request',
	    );

	    $validate = $this->ValidateParams($params, $rules, $messages);
	    if($validate != 1) {
	    	return \response()->json($validate);
        }
        
        // params for searching
        $user_id = $request->user->id;
        $friend_id = $params['friend_id'];

        // check if friend user is valid
        $friend = User::where(['id' => $friend_id])->first();
        if(empty($friend)) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = "Requested user not found";
            return \response()->json($response);
        }

        // check if already friend
        $is_already_friend = Friends::where(function($q) use($user_id, $friend_id){
            return $q->where('friend1_id', $user_id)->where('friend2_id', $friend_id);
        })
        ->orWhere(function($q1) use($user_id, $friend_id){
            return $q1->where('friend2_id', $user_id)->where('friend1_id', $friend_id);
        })->first();

        if(!empty($is_already_friend)) {
            $response['status'] = \Config::get('app.failure_status');
            if($is_already_friend->friend1_id == $user_id && $is_already_friend->status == 0) {
                $response['message'] = "You have already sent request to ".ucfirst($friend->first_name)." ".ucfirst($friend->last_name);
            }
            elseif($is_already_friend->friend2_id == $user_id && $is_already_friend->status == 0) {
                $response['message'] = "You have already pending request from ".ucfirst($friend->first_name)." ".ucfirst($friend->last_name);
            }
            elseif($is_already_friend->status == 1) {
                $response['message'] = "You are already a friend with ".ucfirst($friend->first_name)." ".ucfirst($friend->last_name);
            }
        }
        else {
            $result = Friends::create(['friend1_id' => $user_id, 'friend2_id' => $friend_id]);
            if($result) {
                // start notifications save into database section
                $sender = User::where(['id' => $user_id])->first();
                if(!empty($sender) && !empty($friend) && !empty($friend->device_token) && !empty($friend->device_type)) {
                    $payload = array(
                        "title" => "Friend Request Recieved",
                        "message" => ucfirst($sender->first_name)." ".ucfirst($sender->last_name)." sent you friend request",
                        "type" => "FriendRequestRecieved"
                    );
                    // save into database
                    Alerts::create(array('user_id' => $friend->id, 'sender_id' => $sender->id, 'type' => $payload['type'], 'payload' => json_encode($payload)));
                    // if device type is android
                    if($friend->device_type == 'android') {
                        // payload for notification
                        $fields = array(
                            "to" => $friend->device_token,
                            "data" => $payload
                        );
                        (new NotificationController())->SendPushNotification($fields);
                    }
                }
                // end notifications save into database section
                $response['status'] = \Config::get('app.success_status');
                $response['message'] = "Request sent to ".ucfirst($friend->first_name)." ".ucfirst($friend->last_name);
            }
            else {
                $response['status'] = \Config::get('app.failure_status');;
                $response['message'] = "Unable to send request to ".ucfirst($friend->first_name)." ".ucfirst($friend->last_name);
            }
        }
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | accept friend request to user
    | @params[token, request_id]
    |----------------------------------------------------------------------
    */
    public function AcceptFriendRequest(Request $request) {
        $response = \Config::get('app.api_response');
        $params = $request->all();
    	// required parameters
    	$rules = array(
	        'request_id' => 'required',
	    );
	    $messages = array(
	        'request_id.required' => 'Please provide request id to accept',
	    );

	    $validate = $this->ValidateParams($params, $rules, $messages);
	    if($validate != 1) {
	    	return \response()->json($validate);
        }
        
        // params for searching
        $user_id = $request->user->id;
        $request_id = $params['request_id'];
        $response['status'] = \Config::get('app.failure_status');

        $friend_data = Friends::where(['friend2_id' => $user_id, 'frd_id' => $request_id])->first();
        if(empty($friend_data)) {
            $response['message'] = "Unable to find requested accept";
        }
        elseif($friend_data->status == 1) {
            $response['message'] = "You have already accepted the request";
        }
        elseif($friend_data->status == 2) {
            $response['message'] = "You have already declined the request";
        }
        elseif($friend_data->status == 0) {
            $friend_data->status = 1;
            $result = $friend_data->save();
            if($result) {
                $this->CreateConvIfNotExist($friend_data->friend1_id, $user_id);
                // start notifications save into database section
                $user = User::where(['id' => $user_id])->first();
                $recieverUser = User::where(['id' => $friend_data->friend1_id])->first();
                if(!empty($recieverUser) && !empty($recieverUser->device_token) && !empty($recieverUser->device_type)) {
                    $payload = array(
                        "title" => "Friend Request Accepted",
                        "message" => ucfirst($user->first_name)." ".ucfirst($user->last_name)." accepted your friend request",
                        "type" => "FriendRequestAccepted"
                    );
                    // save into database
                    Alerts::create(array('user_id' => $recieverUser->id, 'sender_id' => $user->id, 'type' => $payload['type'], 'payload' => json_encode($payload)));
                    // if device type is android
                    if($recieverUser->device_type == 'android') {
                        // payload for notification
                        $fields = array(
                            "to" => $recieverUser->device_token,
                            "data" => $payload
                        );
                        (new NotificationController())->SendPushNotification($fields);
                    }
                }
                // end notifications save into database section
                $response['status'] = \Config::get('app.success_status');
                $response['message'] = "You have accepted the request";
            }
            else {
                $response['message'] = "Unable to accept the request";
            }
        }
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | accept friend request to user
    | @params[token, request_id]
    |----------------------------------------------------------------------
    */
    public function DeclineFriendRequest(Request $request) {
        $response = \Config::get('app.api_response');
        $params = $request->all();
    	// required parameters
    	$rules = array(
	        'request_id' => 'required',
	    );
	    $messages = array(
	        'request_id.required' => 'Please provide request id to decline',
	    );

	    $validate = $this->ValidateParams($params, $rules, $messages);
	    if($validate != 1) {
	    	return \response()->json($validate);
        }
        
        // params for searching
        $user_id = $request->user->id;
        $request_id = $params['request_id'];
        $response['status'] = \Config::get('app.failure_status');

        $friend_data = Friends::where(['friend2_id' => $user_id, 'frd_id' => $request_id])->first();
        if(empty($friend_data)) {
            $response['message'] = "Unable to find requested accept";
        }
        elseif($friend_data->status == 1) {
            $response['message'] = "You have already accepted the request";
        }
        elseif($friend_data->status == 2) {
            $response['message'] = "You have already declined the request";
        }
        elseif($friend_data->status == 0) {
            $friend_data->status = 2;
            $result = $friend_data->save();
            if($result) {
                // start notifications save into database section
                $user = User::where(['id' => $user_id])->first();
                $recieverUser = User::where(['id' => $friend_data->friend1_id])->first();
                if(!empty($recieverUser) && !empty($recieverUser->device_token) && !empty($recieverUser->device_type)) {
                    $payload = array(
                        "title" => "Friend Request Rejected",
                        "message" => ucfirst($user->first_name)." ".ucfirst($user->last_name)." rejected your friend request",
                        "type" => "FriendRequestRejected"
                    );
                    // save into database
                    Alerts::create(array('user_id' => $recieverUser->id, 'sender_id' => $user->id, 'type' => $payload['type'], 'payload' => json_encode($payload)));
                    // if device type is android
                    if($recieverUser->device_type == 'android') {
                        // payload for notification
                        $fields = array(
                            "to" => $recieverUser->device_token,
                            "data" => $payload
                        );
                        (new NotificationController())->SendPushNotification($fields);
                    }
                }
                // end notifications save into database section
                $response['status'] = \Config::get('app.success_status');
                $response['message'] = "You have declined the request";
            }
            else {
                $response['message'] = "Unable to decline the request";
            }
        }
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | cancel friend request to user
    | @params[token, request_id]
    |----------------------------------------------------------------------
    */
    public function CancelFriendRequest(Request $request) {
        $response = \Config::get('app.api_response');
        $params = $request->all();
    	// required parameters
    	$rules = array(
	        'request_id' => 'required',
	    );
	    $messages = array(
	        'request_id.required' => 'Please provide request id to cancel',
	    );

	    $validate = $this->ValidateParams($params, $rules, $messages);
	    if($validate != 1) {
	    	return \response()->json($validate);
        }
        
        // params for searching
        $user_id = $request->user->id;
        $request_id = $params['request_id'];
        $response['status'] = \Config::get('app.failure_status');

        $friend_data = Friends::where(['friend1_id' => $user_id, 'frd_id' => $request_id])->first();
        if(empty($friend_data)) {
            $response['message'] = "Unable to find request";
        }
        elseif($friend_data->status == 1) {
            $response['message'] = "Request already accepted";
        }
        elseif($friend_data->status == 2) {
            $response['message'] = "Request already declined the request";
        }
        elseif($friend_data->status == 0) {
            $result = Friends::where(['frd_id' => $request_id, 'friend1_id' => $user_id])->delete();
            if($result) {
                $response['status'] = \Config::get('app.success_status');
                $response['message'] = "Request canceled successfully";
            }
            else {
                $response['message'] = "Unable to cancel request at the moment";
            }
        }
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | get friends list
    | @params[token, search]
    |----------------------------------------------------------------------
    */
    public function GetFriendsList(Request $request) {
        $response = \Config::get('app.api_response');
        $params = $request->all();
        $baseUrl = \Config::get('app.base_url');
        $noProfilePic = \Config::get('app.no_profile_pic');
        $noCoverPic = \Config::get('app.no_cover_pic');
        // params for searching
        $search = (isset($params['search']) && !empty($params['search'])) ? $params['search'] : '';
        $where = "(CONCAT(users.first_name,' ',users.last_name) LIKE '%$search%' OR users.email LIKE '%$search%')";
        $user_id = $request->user->id;

        if(!empty($search)) {
            $query = User::whereRaw($where)
            ->where('id','!=', $user_id);
        }
        else {
            $query = User::where('id','!=', $user_id);
        }
        
        $friends = $query->where('role_id', 2)
        ->whereRaw("(SELECT COUNT(*) FROM friends as f WHERE ((f.friend1_id = users.id AND f.friend2_id = '$user_id') OR (f.friend2_id = users.id AND f.friend1_id = '$user_id')) AND f.status=1) >= 1")
        ->selectRaw("users.id, users.first_name, users.last_name, users.gender, users.is_online, users.created_at as member_since, COALESCE((SELECT CONCAT('$baseUrl','/',up.photo_url) FROM user_photos as up WHERE up.photo_type = 'profile' AND up.photo_status = 1 AND up.photo_user_id = users.id ORDER BY up.created_at DESC LIMIT 1), '$noProfilePic') as profilephoto, COALESCE((SELECT CONCAT('$baseUrl','/',up.photo_url) FROM user_photos as up WHERE up.photo_type = 'cover' AND up.photo_status = 1 AND up.photo_user_id = users.id ORDER BY up.created_at DESC LIMIT 1), '$noCoverPic') as coverphoto")
        ->orderBy('users.is_online', 'desc')
        ->get();

        $response['status'] = \Config::get('app.success_status');
        $response['message'] = (count($friends) > 0) ? 'Here is your friends list' : 'No friends found against your profile';
        $response['data']->friends = $friends;
        return \response()->json($response);
    }
    
    /*
    |----------------------------------------------------------------------
    | get pending friend requests for current user
    | @params[token]
    |----------------------------------------------------------------------
    */
    public function GetFriendRequests(Request $request) {
        $response = \Config::get('app.api_response');
        $response['status'] = \Config::get('app.success_status');
        $baseUrl = \Config::get('app.base_url');
        $noProfilePic = \Config::get('app.no_profile_pic');
        $noCoverPic = \Config::get('app.no_cover_pic');
        $params = $request->all();
        $user_id = $request->user->id;

        // 1 for friend requests
        $requests = User::join('friends as fr', 'users.id', 'fr.friend1_id')
        ->where(['fr.friend2_id' => $user_id, 'status' => 0])
        ->selectRaw("fr.frd_id as request_id, users.id as user_id,users.first_name,users.last_name,users.gender,users.created_at as member_since, COALESCE((SELECT CONCAT('$baseUrl', '/', photo_url) FROM user_photos as up WHERE up.photo_type = 'profile' AND up.photo_user_id = fr.friend1_id AND up.photo_status = 1 ORDER BY created_at DESC LIMIT 1), '$noProfilePic') AS photo_url, COALESCE((SELECT CONCAT('$baseUrl', '/', photo_url) FROM user_photos as up WHERE up.photo_type = 'cover' AND up.photo_user_id = fr.friend1_id AND up.photo_status = 1 ORDER BY created_at DESC LIMIT 1), '$noCoverPic') AS cover_url")
        ->orderBy('fr.created_at', 'DESC')
        ->get();
        // 1 for friend requests
        $sent = User::join('friends as fr', 'users.id', 'fr.friend2_id')
        ->where(['fr.friend1_id' => $user_id, 'status' => 0])
        ->selectRaw("fr.frd_id as request_id, users.id as user_id,users.first_name,users.last_name,users.gender,users.created_at as member_since, COALESCE((SELECT CONCAT('$baseUrl', '/', photo_url) FROM user_photos as up WHERE up.photo_type = 'profile' AND up.photo_user_id = fr.friend2_id AND up.photo_status = 1 ORDER BY created_at DESC LIMIT 1), '$noProfilePic') AS photo_url, COALESCE((SELECT CONCAT('$baseUrl', '/', photo_url) FROM user_photos as up WHERE up.photo_type = 'cover' AND up.photo_user_id = fr.friend2_id AND up.photo_status = 1 ORDER BY created_at DESC LIMIT 1), '$noCoverPic') AS cover_url")
        ->orderBy('fr.created_at', 'DESC')
        ->get();
        // mark is read
        if(count($requests) > 0) {
            Friends::whereIn('frd_id', array_column($requests->toArray(), 'id'))->update(['is_selected' => 1, 'is_read' => 1]);
        }

        $response['message'] = 'Here are requests details';
        $response['data']->recieved = $requests;
        $response['data']->sent = $sent;
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | create conversation if not exist
    |----------------------------------------------------------------------
    */
    private function CreateConvIfNotExist($user_id, $friend_id) {
        $conversation = $this->convObj->get_coversation_with_user1_user2($user_id, $friend_id);
    	if(empty($conversation)) {
    		$convcreate = Conversations::create(['user1_id' => $user_id, 'user2_id' => $friend_id]);
    		$conversation = $this->convObj->get_coversation_with_user1_user2($user_id, $friend_id);
    	}
    	return $conversation;
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
