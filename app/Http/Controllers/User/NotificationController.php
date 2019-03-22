<?php

namespace App\Http\Controllers\User;

use Auth;
use App\User;
use App\Models\Friends;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Notifications;
use Illuminate\Support\Facades\Log;
use App\Models\Conversations;
use App\Models\Alerts;

class NotificationController extends Controller
{
    private $convObj;
    private $uploadsUrl;
    public function __construct() {
        $this->convObj = new Conversations();
        $this->uploadsUrl = url('uploads');
    }
    /*
    |----------------------------------------------------------------------
    | check for user if notifications recieved
    |----------------------------------------------------------------------
    */
    public function CheckNotifications(Request $request) {
        if($request->ajax()) {
            $response = array();
            $response['status'] = 200;
            $user_id = Auth::user()->id;
            $friend_requests = Friends::where(['friend2_id' => $user_id, 'is_read' => 0, 'status' => 0, 'is_selected' => 0])->get()->count();
            $notifications = Alerts::where(['user_id' => $user_id, 'is_read' => 0,'is_selected' => 0])->get()->count();
            $response['friend_request'] = ($friend_requests > 0) ? 1 : 0;
            $response['notifications'] = ($notifications > 0) ? 1 : 0;
            return response()->json($response);
        }
    }

    /*
    |----------------------------------------------------------------------
    | check for user if notifications recieved
    |----------------------------------------------------------------------
    */
    public function GetUnreadNotifications(Request $request) {
        if($request->ajax()) {
            $response = array();
            $response['status'] = 200;
            $user_id = Auth::user()->id;
            $type = $request->input('target');
            // 1 for friend requests
            if($type == 1) {
                $friend_requests = User::join('friends as fr', 'users.id', 'fr.friend1_id')
                ->where(['fr.friend2_id' => $user_id, 'is_read' => 0, 'status' => 0, 'is_selected' => 0])
                ->selectRaw("fr.frd_id as id,users.id as user_id,users.first_name,users.last_name,users.created_at,(SELECT photo_url FROM user_photos as up WHERE up.photo_type = 'profile' AND up.photo_user_id = fr.friend1_id AND up.photo_status = 1 ORDER BY created_at DESC LIMIT 1) AS photo_url")
                ->orderBy('fr.created_at', 'DESC')
                ->get();
                Friends::whereIn('frd_id', array_column($friend_requests->toArray(), 'id'))->update(['is_selected' => 1]);
                $response['html'] = view('user.ajax.friend-requests-ajax', compact('friend_requests', 'type'))->render();
                $response['count'] = count($friend_requests);
            }
            elseif($type == 2) {
                $notifications = Alerts::leftjoin('users as u', 'alerts.sender_id', 'u.id')
                ->where(['alerts.user_id' => $user_id, 'is_selected' => 0, 'is_read' => 0])
                ->selectRaw("alerts.*,(SELECT photo_url FROM user_photos as up WHERE up.photo_type = 'profile' AND up.photo_user_id = alerts.sender_id AND up.photo_status = 1 ORDER BY created_at DESC LIMIT 1) AS photo_url")
                ->orderBy('alerts.created_at', 'DESC')
                ->get();

                Alerts::whereIn('alert_id', array_column($notifications->toArray(), 'alert_id'))->update(['is_selected' => 1]);
                $response['html'] = view('user.ajax.friend-requests-ajax', compact('notifications','type'))->render();
                $response['count'] = (count($notifications) > 0) ? count($notifications) : 0;
            }
            return response()->json($response);
        }
    }

    /*
    |----------------------------------------------------------------------
    | get existing notifications
    |----------------------------------------------------------------------
    */
    public function GetNotifications(Request $request) {
        if($request->ajax()) {
            $response = array();
            $response['status'] = 200;
            $user_id = Auth::user()->id;
            $type = $request->input('target');
            // 1 for friend requests
            if($type == 1) {
                $friend_requests = User::join('friends as fr', 'users.id', 'fr.friend1_id')
                ->where(function($q){ return $q->where('is_read', 0)->orWhere('is_read', 1); })
                ->where(['fr.friend2_id' => $user_id, 'status' => 0, 'is_selected' => 1])
                ->selectRaw("fr.frd_id as id,users.id as user_id,users.first_name,users.last_name,users.created_at,(SELECT photo_url FROM user_photos as up WHERE up.photo_type = 'profile' AND up.photo_user_id = fr.friend1_id AND up.photo_status = 1 ORDER BY created_at DESC LIMIT 1) AS photo_url")
                ->orderBy('fr.created_at', 'DESC')
                ->get();

                $response['html'] = view('user.ajax.friend-requests-ajax', compact('friend_requests','type'))->render();
                $response['count'] = (count($friend_requests) > 0) ? count($friend_requests) : 0;
            }
            elseif($type == 2) {
                $notifications = Alerts::leftjoin('users as u', 'alerts.sender_id', 'u.id')
                ->where(function($q){ return $q->where('is_read', 0)->orWhere('is_read', 1); })
                ->where(['alerts.user_id' => $user_id, 'is_selected' => 1])
                ->selectRaw("alerts.*,(SELECT photo_url FROM user_photos as up WHERE up.photo_type = 'profile' AND up.photo_user_id = alerts.sender_id AND up.photo_status = 1 ORDER BY created_at DESC LIMIT 1) AS photo_url")
                ->orderBy('alerts.created_at', 'DESC')
                ->get();

                $response['html'] = view('user.ajax.friend-requests-ajax', compact('notifications','type'))->render();
                $response['count'] = (count($notifications) > 0) ? count($notifications) : 0;
            }
            return response()->json($response);
        }
    }

    /*
    |----------------------------------------------------------------------
    | send push notification to reciever in chat
    |----------------------------------------------------------------------
    */
    public function SendChatPushNotification($rec_id, $message) {
        $user = User::where(['id' => $rec_id])->first();
        $sender = User::where(['id' => $message->sender_id])->first();
        $baseUrl = url('uploads');
        $message->media = (!empty($message->media)) ? $baseUrl.'/'.$message->media : '';
        // get conversation for reciever
        $conversation = $this->convObj->get_coversation($message->conversation_id, $rec_id);
        if(!empty($sender) && !empty($user) && !empty($user->device_token) && !empty($user->device_type) && !empty($conversation)) {
            $created_at = new \DateTime($message->created_at);
            $payload = array(
                "title" => "Goseeum Message",
                "message" => "New Message From ".ucfirst($sender->first_name)." ".ucfirst($sender->last_name),
                "unread_count" => $conversation->unread_count,
                "type" => "MessageRecieved",
                "conversation" => (string)$message->conversation_id,
                "message" => $message->message,
                "sender_id" => (string)$message->sender_id,
                "reciever_id" => (string)$rec_id,
                "message_id" => (string)$message->msg_id,
                "media" => $message->media,
                "created_at" => (string)$created_at->format('Y-m-d H:i:s'),
                "sender_image" => (!empty($sender->profilephoto())) ? ($baseUrl.'/'.$sender->profilephoto()->photo_url) : url('img/no-profile-photo.jpg')
            );
            // save into database
            Alerts::create(array('user_id' => $rec_id, 'sender_id' => $sender->id, 'type' => $payload['type'], 'payload' => json_encode($payload)));
            // send android notification
            if($user->device_type == 'android') {
                // payload for notification
                $fields = array(
                    "to" => $user->device_token,
                    "data" => $payload
                );
                $this->SendPushNotification($fields);
            }
        }
    }

    /*
    |----------------------------------------------------------------------
    | send push notification to device id
    |----------------------------------------------------------------------
    */
    public function SendPushNotification($fields) {
        $server_key = "AAAA-dU6b8Y:APA91bH4PWG1-HDe04MG22nEEGySsnoK5o105Bp9mfHDe-tqi_e9cSWXG7lmJy5JgsbqthKGmGmzjs3EmuIsVyU4_vcJ4P0aXQQf-pDlLtbK4GHqW4VFmSG_RxqbEBFk5cxW0VULeIsG";
        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';
        $headers = array(
            'Authorization: key='.$server_key,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {}
        Log::info('NOTIFICATION RESPONSE : '.json_encode($result));
        curl_close($ch);
    }
}
