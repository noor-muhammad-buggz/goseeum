<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversations extends Model
{
    protected $table = 'conversations';

	protected $primaryKey = 'conv_id';

    public $timestamps = true;

    protected $fillable = ['user1_id','user2_id','last_updated'];

    /*
    |----------------------------------------------------------------------
    | get conversation list for specific user
    |----------------------------------------------------------------------
    */
    public function coversation_summary($user_id) {
    	$rawWhereConversation = "user1_id = '$user_id' OR user2_id = '$user_id'";
    	return self::join('users as u', 'conversations.user1_id', 'u.id')
		->join('users as u1', 'conversations.user2_id', 'u1.id')
		->whereRaw($rawWhereConversation)
		->selectRaw("conversations.conv_id, (CASE WHEN conversations.user1_id = '$user_id' THEN u.id ELSE u1.id END) AS me_id, (CASE WHEN conversations.user1_id != '$user_id' THEN u.id ELSE u1.id END) AS other_user_id, (CASE WHEN conversations.user1_id != '$user_id' THEN u.is_online ELSE u1.is_online END) AS is_online, (CASE WHEN conversations.user1_id = '$user_id' THEN CONCAT(u.first_name, ' ', u.last_name) ELSE CONCAT(u1.first_name, ' ', u1.last_name) END) AS me_name, (CASE WHEN conversations.user1_id != '$user_id' THEN CONCAT(u.first_name, ' ', u.last_name) ELSE CONCAT(u1.first_name, ' ', u1.last_name) END) AS other_user_name, COALESCE((SELECT message from messages as m WHERE m.conversation_id = conversations.conv_id AND (CASE WHEN m.sender_id = '$user_id' THEN m.sender_deleted = 0 ELSE m.reciever_deleted = 0 END) ORDER BY m.created_at DESC LIMIT 1), '') AS last_message_text, (SELECT created_at from messages as m WHERE m.conversation_id = conversations.conv_id AND (CASE WHEN m.sender_id = '$user_id' THEN m.sender_deleted = 0 ELSE m.reciever_deleted = 0 END) ORDER BY m.created_at DESC LIMIT 1) AS created_at, COALESCE((SELECT photo_url FROM user_photos AS up WHERE up.photo_user_id = (CASE WHEN conversations.user1_id = '$user_id' THEN u1.id ELSE u.id END) AND up.photo_type = 'profile' ORDER BY up.created_at DESC LIMIT 1), '') AS other_user_photo,(SELECT COUNT(*) from messages as m WHERE m.conversation_id = conversations.conv_id AND m.sender_id != '$user_id' AND m.reciever_deleted = 0 AND m.is_read = 0) AS unread_count")
		->paginate(15);
    }

    /*
    |----------------------------------------------------------------------
    | get conversation detail with id and user id
    |----------------------------------------------------------------------
    */
    public function get_coversation($conv_id, $user_id) {
    	$rawWhereConversation = "(user1_id = '$user_id' OR user2_id = '$user_id') AND conv_id = '$conv_id'";
    	return self::join('users as u', 'conversations.user1_id', 'u.id')
		->join('users as u1', 'conversations.user2_id', 'u1.id')
		->whereRaw($rawWhereConversation)
		->selectRaw("conversations.conv_id, (CASE WHEN conversations.user1_id != '$user_id' THEN u.id ELSE u1.id END) AS other_user_id, (CASE WHEN conversations.user1_id != '$user_id' THEN u.is_online ELSE u1.is_online END) AS is_online, (CASE WHEN conversations.user1_id != '$user_id' THEN CONCAT(u.first_name, ' ', u.last_name) ELSE CONCAT(u1.first_name, ' ', u1.last_name) END) AS other_user_name, COALESCE((SELECT photo_url FROM user_photos AS up WHERE up.photo_user_id = (CASE WHEN conversations.user1_id = '$user_id' THEN u1.id ELSE u.id END) AND up.photo_type = 'profile' ORDER BY up.created_at DESC LIMIT 1), '') AS other_user_photo,(SELECT COUNT(*) from messages as m WHERE m.conversation_id = conversations.conv_id AND m.sender_id != '$user_id' AND m.reciever_deleted = 0 AND m.is_read = 0) AS unread_count")
		->first();
    }

    /*
    |----------------------------------------------------------------------
    | get conversation detail with user1_id and user2_id
    |----------------------------------------------------------------------
    */
    public function get_coversation_with_user1_user2($user1_id, $user2_id) {
        $rawWhereConversation = "(user1_id = '$user1_id' AND user2_id = '$user2_id') OR (user1_id = '$user2_id' AND user2_id = '$user1_id')";
        return self::join('users as u', 'conversations.user1_id', 'u.id')
        ->join('users as u1', 'conversations.user2_id', 'u1.id')
        ->whereRaw($rawWhereConversation)
        ->selectRaw("conversations.conv_id, (CASE WHEN conversations.user1_id != '$user1_id' THEN u.id ELSE u1.id END) AS other_user_id, (CASE WHEN conversations.user1_id != '$user1_id' THEN u.is_online ELSE u1.is_online END) AS is_online, (CASE WHEN conversations.user1_id != '$user1_id' THEN CONCAT(u.first_name, ' ', u.last_name) ELSE CONCAT(u1.first_name, ' ', u1.last_name) END) AS other_user_name, COALESCE((SELECT photo_url FROM user_photos AS up WHERE up.photo_user_id = (CASE WHEN conversations.user1_id = '$user1_id' THEN u1.id ELSE u.id END) AND up.photo_type = 'profile' ORDER BY up.created_at DESC LIMIT 1), '') AS other_user_photo")
        ->first();
    }
}
