<?php

namespace App\Http\Controllers\User;

use Auth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Conversations;
use App\Models\Chat;

class ChatController extends Controller
{
	private $convObj;
    private $chatObj;
    private $response;

	public function __construct() {
		$this->convObj = new Conversations();
        $this->chatObj = new Chat();
        $this->response = ['status' => '', 'message' => '', 'data' => array(), 'errors' => array()];
	}
    /*
    |----------------------------------------------------------------------
    | load chat main view
    |----------------------------------------------------------------------
    */
    public function index() {
        $user_id = Auth::user()->id;
        $conversations = $this->convObj->coversation_summary($user_id);
        return view('user.chat.chat-main-view', compact('conversations'));
    }

    /*
    |----------------------------------------------------------------------
    | get specific chat for a pair of users
    |----------------------------------------------------------------------
    */
    public function GetChat(Request $request) {
        if($request->ajax()) {
        	$data = $request->all();
        	$response = $this->response;
        	$user_id = Auth::user()->id;
        	$conv_id = $data['target'];
        	$conversation = $this->convObj->get_coversation($conv_id, $user_id);

        	if(empty($conversation)) {
        		$response['status'] = \Config::get('app.failure_status');
        		$response['message'] = 'Requested conversation not found';
        		return \response()->json($response);
        	}

        	$other_id = $conversation->other_user_id;

            $chat = $this->chatObj->get_chat($conv_id, $user_id, $other_id)->orderBy('created_at', 'asc')->get();
            $response['status'] = \Config::get('app.success_status');
        	$response['message'] = 'Here is your chat history';
            $response['data']['html'] = view('user.ajax.chat-main-view-ajax', compact('conversation', 'chat'))->render();
            return \response()->json($response);
        }
    }

    /*
    |----------------------------------------------------------------------
    | get unread messages for a pair of users
    |----------------------------------------------------------------------
    */
    public function GetUnreadChat(Request $request) {
        if($request->ajax()) {
            $data = $request->all();
            $response = $this->response;
            $user_id = Auth::user()->id;
            $conv_id = $data['target'];
            $offset = (!isset($data['offset']) || $data['offset'] == 'undefined') ? 0 : $data['offset'];

            $conversation = $this->convObj->get_coversation($conv_id, $user_id);
            if(empty($conversation)) {
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'Requested conversation not found';
                return \response()->json($response);
            }

            $other_id = $conversation->other_user_id;

            $chat = $this->chatObj->get_chat($conv_id, $user_id, $other_id, $offset)->orderBy('created_at', 'asc')->get();
            if(count($chat) > 0) {
                Chat::whereIn('msg_id', array_column($chat->toArray(), 'msg_id'))->where('sender_id', '!=', Auth::user()->id)->update(array('is_read' => 1));
            }
            $response['status'] = \Config::get('app.success_status');
            $response['message'] = 'Here is your chat history';
            $response['data']['html'] = view('user.ajax.chat-main-view-unread-ajax', compact('chat'))->render();
            $response['data']['count'] = count($chat);
            return \response()->json($response);
        }
    }

    /*
    |----------------------------------------------------------------------
    | get unread messages for a pair of users
    |----------------------------------------------------------------------
    */
    public function SendChatMessage(Request $request) {
        if($request->ajax()) {
            $data = $request->all();
            $response = $this->response;
            $user_id = Auth::user()->id;
            $conv_id = $data['target'];
            $body = $data['body'];
            $conversation = $this->convObj->get_coversation($conv_id, $user_id);

            if(empty($conversation)) {
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'Requested conversation not found';
                return \response()->json($response);
            }

            $other_id = $conversation->other_user_id;
            $chat = Chat::create(array('conversation_id' => $conv_id, 'sender_id' => $user_id, 'message' => $body));
            if(!empty($chat)) {
                $response['status'] = \Config::get('app.success_status');
                $response['message'] = 'Your message sent';    
            }
            else {
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'Your message not sent';
            }
            
            return \response()->json($response);
        }
    }

}
