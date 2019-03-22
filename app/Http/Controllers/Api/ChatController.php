<?php

namespace App\Http\Controllers\Api;

use Auth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\User\NotificationController;
use App\Models\Conversations;
use App\Models\Chat;

class ChatController extends Controller
{
    private $convObj;
	private $chatObj;
	private $uploadsUrl;

	public function __construct() {
		$this->convObj = new Conversations();
		$this->chatObj = new Chat();
		$this->uploadsUrl = url('uploads');
	}

	/*
    |----------------------------------------------------------------------
    | get all conversations list far requested user
    | @params [token]
    |----------------------------------------------------------------------
    */
    public function GetConversations(Request $request) {
    	$response = \Config::get('app.api_response');
        $params = $request->all();
        $user_id = $request->user->id;
        $conversations = $this->convObj->coversation_summary($user_id);
        $response['status'] = \Config::get('app.success_status');
        $response['message'] = 'Here is conversation list';
        $response['data']->conversations = $conversations->items();
        $response['data']->base_url = $this->uploadsUrl;
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | get specific chat for a pair of users
    | @params [token, user_id, friend_id]
    |----------------------------------------------------------------------
    */
    public function GetChat(Request $request) {
    	$params = $request->all();
        $response = \Config::get('app.api_response');
        $baseUrl = url('uploads');
        // required parameters
        $rules = array(
            'user_id' => 'required',
            'friend_id' => 'required'
        );
        $messages = array(
            'user_id.required' => "Please provide current user id",
            'friend_id.required' => "Please provide other user id"
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }

    	$user_id = $params['user_id'];
    	$friend_id = $params['friend_id'];
    	$conversation = $this->CreateConvIfNotExist($user_id, $friend_id);

        $chat = $this->chatObj->get_chat($conversation->conv_id, $user_id, $friend_id)->orderBy('created_at', 'desc')->paginate(15);
        $response['status'] = \Config::get('app.success_status');
    	$response['message'] = 'Here is your chat history';
        $response['data']->conversation = $conversation;
        $response['data']->chat = (!empty($chat->items())) ? array_reverse($chat->items()) : $chat->items();
        $response['data']->base_url = $baseUrl;
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | get specific chat for a pair of users
    | @params [token, user_id, conversation_id]
    |----------------------------------------------------------------------
    */
    public function GetUnreadChat(Request $request) {
    	$params = $request->all();
        $response = \Config::get('app.api_response');
        $baseUrl = url('uploads');
        // required parameters
        $rules = array(
            'user_id' => 'required',
            'conversation_id' => 'required',
            'offset' => 'numeric'
        );
        $messages = array(
            'user_id.required' => "Please provide current user id",
            'conversation_id.required' => "Please provide conversation id",
            'offset.numeric' => "Please provide only numeric value for offset",
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }

    	$user_id = $params['user_id'];
        $conv_id = $params['conversation_id'];
        $offset = (isset($params['offset'])) ? $params['offset'] : 0;

        $conversation = $this->convObj->get_coversation($conv_id, $user_id);
        if(empty($conversation)) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Requested conversation not found';
            return \response()->json($response);
        }

        $other_id = $conversation->other_user_id;
        $chat = $this->chatObj->get_chat($conv_id, $user_id, $other_id, $offset)->orderBy('created_at', 'desc');
        
        if(count($chat) > 0) {
			Chat::whereIn('msg_id', array_column($chat->toArray(), 'msg_id'))->where('sender_id', '!=', $user_id)->update(array('is_read' => 1));
        }
        $response['status'] = \Config::get('app.success_status');
        $response['message'] = 'Here is unread chat history';
        $response['data']->chat = $chat;
        $response['data']->base_url = $baseUrl;
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | send message between two users
    | @params [token, sender_id, conversation_id, body, media]
    |----------------------------------------------------------------------
    */
    public function SendChatMessage(Request $request) {
    	$params = $request->all();
        $response = \Config::get('app.api_response');
        $baseUrl = url('uploads');
        // required parameters
        $rules = array(
            'sender_id' => 'required',
            'conversation_id' => 'required',
          	'body' => 'required_without:media',
          	'media' => 'required_without:body'
        );
        $messages = array(
            'sender_id.required' => "Please provide sender id",
            'conversation_id.required' => "Please provide conversation id",
            'body.required_without' => "Please provide body or media to send message",
            'media.required_without' => "Please provide body or media to send message",
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }

    	$user_id = $params['sender_id'];
        $conv_id = $params['conversation_id'];
        $body = $params['body'];
        $conversation = $this->convObj->get_coversation($conv_id, $user_id);

        if(empty($conversation)) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Requested conversation not found';
            return \response()->json($response);
        }

        $other_id = $conversation->other_user_id;
        // upload file if exists
        $media = '';
        if(isset($params['media'])) {
            $file = $request->file('media');
            if(isset($file)) {
                if($request->file('media')->isValid()) {
                    $extension = $file->getClientOriginalExtension(); 
                    $directory = 'uploads/';
                    $filename = "message_" . substr(sha1(time().time()), 0, 15) . str_random(10).".{$extension}";
                    $upload_success = $request->file('media')->move($directory, $filename);
                    if($upload_success) {
                        $media = $filename;
                    }
                }
            }
        }

        $msgdata = array(
        	'conversation_id' => $conv_id,
        	'sender_id' => $user_id,
        	'message' => (isset($params['body'])) ? $params['body'] : '',
        	'media' => $media
        );

        $chat = Chat::create($msgdata);
        if(!empty($chat)) {
            // send notification to reciever
            (new NotificationController())->SendChatPushNotification($other_id, $chat);
            $response['status'] = \Config::get('app.success_status');
            $response['message'] = 'Your message sent';
            $response['data']->message_id = (string)$chat->msg_id;
        }
        else {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Your message not sent';
        }
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | delete single message
    | @params [token, user_id, conversation_id, message_id]
    |----------------------------------------------------------------------
    */
    public function DeleteSingleChatMessage(Request $request) {
    	$params = $request->all();
        $response = \Config::get('app.api_response');
        $baseUrl = url('uploads');
        // required parameters
        $rules = array(
            'user_id' => 'required',
            'conversation_id' => 'required',
          	'message_id' => 'required'
        );
        $messages = array(
            'sender_id.required' => "Please provide user id",
            'conversation_id.required' => "Please provide conversation id",
            'message_id.required' => "Please provide message id"
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }

    	$user_id = $params['user_id'];
        $conv_id = $params['conversation_id'];
        $msg_id = $params['message_id'];
        $conversation = $this->convObj->get_coversation($conv_id, $user_id);

        if(empty($conversation)) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Requested conversation not found';
            return \response()->json($response);
        }

        $message = Chat::where(['msg_id' => $msg_id, 'conversation_id' => $conv_id])->first();
        if(empty($message)) {
        	$response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Requested message not found';
            return \response()->json($response);
        }

        if($message->sender_id = $user_id) {
        	$message->sender_deleted = 1;
        }
        else {
        	$message->reciever_deleted = 1;	
        }

        $is_updated = $message->save();
        if($is_updated) {
            $response['status'] = \Config::get('app.success_status');
            $response['message'] = 'Rrequested message deleted successfully';
        }
        else {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to delete requested message at the moment';
        }
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | delete single message
    | @params [token, user_id, conversation_id, message_id]
    |----------------------------------------------------------------------
    */
    public function DeleteAllChatMessage(Request $request) {
    	$params = $request->all();
        $response = \Config::get('app.api_response');
        $baseUrl = url('uploads');
        // required parameters
        $rules = array(
            'user_id' => 'required',
            'conversation_id' => 'required'
        );
        $messages = array(
            'sender_id.required' => "Please provide user id",
            'conversation_id.required' => "Please provide conversation id"
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }

    	$user_id = $params['user_id'];
        $conv_id = $params['conversation_id'];
        $conversation = $this->convObj->get_coversation($conv_id, $user_id);

        if(empty($conversation)) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Requested conversation not found';
            return \response()->json($response);
        }

        Chat::where('conversation_id', '=', $conv_id)->where('sender_id', '=', $user_id)->update(['sender_deleted' => 1]);
        Chat::where('conversation_id', '=', $conv_id)->where('sender_id', '!=', $user_id)->update(['reciever_deleted' => 1]);

        $response['status'] = \Config::get('app.success_status');
        $response['message'] = 'Rrequested chat history removed successfully';
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
