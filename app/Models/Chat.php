<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'messages';

	protected $primaryKey = 'msg_id';

    public $timestamps = true;

    protected $fillable = ['is_admin','conversation_id','sender_id','message','media','is_read','sender_deleted','reciever_deleted'];

    public function get_chat($conv_id, $user_id, $other_id, $offset = '') {
    	$chat = self::join('users as u', 'messages.sender_id', 'u.id')
		->whereRaw("conversation_id='$conv_id' AND (CASE WHEN messages.sender_id = '$user_id' THEN messages.sender_deleted = 0 ELSE messages.reciever_deleted = 0 END)")
		->selectRaw("messages.*, (CASE WHEN messages.sender_id = '$user_id' THEN 'Sender' ELSE 'Reciever' END) AS role, (CASE WHEN messages.sender_id = '$user_id' THEN $other_id ELSE $user_id END) AS reciever_id, COALESCE((SELECT photo_url FROM user_photos AS up WHERE up.photo_user_id = (CASE WHEN messages.sender_id = '$user_id' THEN $user_id ELSE $other_id END) AND up.photo_type = 'profile' ORDER BY up.created_at DESC LIMIT 1), '') AS sender_photo, CONCAT(u.first_name,' ',u.last_name) AS sender_name, u.is_online, (CASE WHEN messages.media IS NOT NULL THEN messages.media ELSE '' END) AS media_url");
        if(!empty($offset)) {
            $chat->where('messages.msg_id', '>', $offset);
        }
		return $chat;
    }
}
