<?php

namespace App\Http\Controllers\User;

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
    |----------------------------------------------------------------------
    */
    public function SearchPeople(Request $request) {
        $people = '';
        $search = '';
        $user_id = Auth::user()->id;
        if(isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $people = User::whereRaw("(CONCAT(users.first_name,' ',users.last_name) LIKE '%$search%' OR users.email LIKE '%$search%')")
            ->where('id','!=', $user_id)
            ->where('role_id', 2)
            ->whereRaw("(SELECT COUNT(*) FROM friends as f WHERE (f.friend1_id = users.id AND f.friend2_id = '$user_id') OR (f.friend2_id = users.id AND f.friend1_id = '$user_id')) <= 0")
            ->selectRaw("users.*, (SELECT COUNT(*) FROM friends as f WHERE (f.friend1_id = users.id AND f.friend2_id = '$user_id') OR (f.friend2_id = users.id AND f.friend1_id = '$user_id')) as is_friend")
            ->get();
        }
        return view('user.search-people', compact('people', 'search'));
    }

    /*
    |----------------------------------------------------------------------
    | sen friend request to user
    |----------------------------------------------------------------------
    */
    public function SendFriendRequest(Request $request) {
        $response = array();
        $user_id = Auth::user()->id;
        $friend_id = $request->input('target');

        $friend = User::where(['id' => $friend_id])->first();
        if(empty($friend)) {
            $response['status'] = 500;
            $response['message'] = "Requested user not found";
            return response()->json($response);
            exit(0);
        }

        $is_already_friend = Friends::where(function($q) use($user_id, $friend_id){
            return $q->where('friend1_id', $user_id)->where('friend2_id', $friend_id);
        })
        ->orWhere(function($q1) use($user_id, $friend_id){
            return $q1->where('friend2_id', $user_id)->where('friend1_id', $friend_id);
        })->first();

        if(!empty($is_already_friend)) {
            $response['status'] = 500;
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
                $response['status'] = 200;
                $response['message'] = "Request sent to ".ucfirst($friend->first_name)." ".ucfirst($friend->last_name);
            }
            else {
                $response['status'] = 500;
                $response['message'] = "Unable to send request to ".ucfirst($friend->first_name)." ".ucfirst($friend->last_name);
            }
        }
        return response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | accept friend request to user
    |----------------------------------------------------------------------
    */
    public function AcceptFriendRequest(Request $request) {
        $response = array();
        $response['status'] = 500;
        $user_id = Auth::user()->id;
        $target_id = $request->input('target');

        $friend_data = Friends::where(['friend2_id' => $user_id, 'frd_id' => $target_id])->first();
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
                // create notification entry for action
                $user = User::where(['id' => $user_id])->first();
                $nt_url = url('user/profile/'.$user->id);
                $nt_body = "<a href='".$nt_url."' class='notification-friend'>".ucfirst($user->first_name)." ".ucfirst($user->last_name)."</a> accepted your friend request";
                Notifications::create(['noti_url' => $nt_url, 'noti_body' => $nt_body, 'user_id' => $user_id, 'reciever_id' => $friend_data->friend1_id]);
                // start notifications save into database section
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
                $response['status'] = 200;
                $response['message'] = "You have accepted the request";
            }
            else {
                $response['message'] = "Unable to accept the request";
            }
        }
        return response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | accept friend request to user
    |----------------------------------------------------------------------
    */
    public function DeclineFriendRequest(Request $request) {
        $response = array();
        $response['status'] = 500;
        $user_id = Auth::user()->id;
        $target_id = $request->input('target');

        $friend_data = Friends::where(['friend2_id' => $user_id, 'frd_id' => $target_id])->first();
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
                // create notification entry for action
                $user = User::where(['id' => $user_id])->first();
                $nt_url = url('user/profile/'.$user->id);
                $nt_body = "<a href='".$nt_url."' class='notification-friend'>".ucfirst($user->first_name)." ".ucfirst($user->last_name)."</a> declined your friend request";
                Notifications::create(['noti_url' => $nt_url, 'noti_body' => $nt_body, 'user_id' => $user_id, 'reciever_id' => $friend_data->friend1_id]);
                // start notifications save into database section
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
                $response['status'] = 200;
                $response['message'] = "You have declined the request";
            }
            else {
                $response['message'] = "Unable to decline the request";
            }
        }
        return response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | cancel friend request to user
    |----------------------------------------------------------------------
    */
    public function CancelFriendRequest(Request $request) {
        $response = array();
        $response['status'] = 500;
        $user_id = Auth::user()->id;
        $target_id = $request->input('target');

        $friend_data = Friends::where(['friend1_id' => $user_id, 'frd_id' => $target_id])->first();
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
            $result = Friends::where(['frd_id' => $target_id, 'friend1_id' => $user_id])->delete();
            if($result) {
                $response['status'] = 200;
                $response['message'] = "Request canceled successfully";
            }
            else {
                $response['message'] = "Unable to cancel request at the moment";
            }
        }
        return response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | get pending friend requests for current user
    |----------------------------------------------------------------------
    */
    public function GetFriendRequests(Request $request) {        
        $response = array();
        $user_id = Auth::user()->id;
        // 1 for friend requests
        $requests = User::join('friends as fr', 'users.id', 'fr.friend1_id')
        ->where(['fr.friend2_id' => $user_id, 'status' => 0])
        ->selectRaw("fr.frd_id as id, users.id as user_id,users.first_name,users.last_name,users.gender,users.created_at,(SELECT photo_url FROM user_photos as up WHERE up.photo_type = 'profile' AND up.photo_user_id = fr.friend1_id AND up.photo_status = 1 ORDER BY created_at DESC LIMIT 1) AS photo_url,(SELECT photo_url FROM user_photos as up WHERE up.photo_type = 'cover' AND up.photo_user_id = fr.friend1_id AND up.photo_status = 1 ORDER BY created_at DESC LIMIT 1) AS cover_url")
        ->orderBy('fr.created_at', 'DESC')
        ->get();
        // 1 for friend requests
        $sent = User::join('friends as fr', 'users.id', 'fr.friend2_id')
        ->where(['fr.friend1_id' => $user_id, 'status' => 0])
        ->selectRaw("fr.frd_id as id, users.id as user_id,users.first_name,users.last_name,users.gender,users.created_at,(SELECT photo_url FROM user_photos as up WHERE up.photo_type = 'profile' AND up.photo_user_id = fr.friend2_id AND up.photo_status = 1 ORDER BY created_at DESC LIMIT 1) AS photo_url,(SELECT photo_url FROM user_photos as up WHERE up.photo_type = 'cover' AND up.photo_user_id = fr.friend2_id AND up.photo_status = 1 ORDER BY created_at DESC LIMIT 1) AS cover_url")
        ->orderBy('fr.created_at', 'DESC')
        ->get();
        // mark is read
        Friends::whereIn('frd_id', array_column($requests->toArray(), 'id'))->update(['is_selected' => 1, 'is_read' => 1]);
        return view('user.friend-requests', compact('requests', 'sent'));
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

}
