<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Models\PostsMeta;
use App\Models\Comments;
use App\Models\Likes;
use App\Models\Posts;
use App\User;
use Auth;
use DB;

class NewsFeedController extends Controller
{
    /*
    |----------------------------------------------------------------------
    | get requeste user posts
    | @params [token]
    |----------------------------------------------------------------------
    */
    public function GetPosts(Request $request) {
        $baseUrl = url('uploads');
        $noProfilePic = url('img/no-profile-photo.jpg');
        $params = $request->all();
        $response = \Config::get('app.api_response');
        $user_id = $request->user->id;
        $posts = Posts::with(['media',
            'postuser' => function($q) use($baseUrl, $noProfilePic){
                return $q->selectRaw("id,first_name,last_name, is_online, COALESCE((SELECT CASE WHEN up.photo_url IS NOT NULL THEN CONCAT('$baseUrl','/',up.photo_url) ELSE '$noProfilePic' END FROM user_photos up WHERE up.photo_user_id = users.id AND up.photo_type = 'profile' ORDER BY up.created_at DESC LIMIT 1), '$noProfilePic') as profile_photo");
            },
            'postcomments' => function($q) use($baseUrl, $noProfilePic){
                return $q->with(['commentuser' => function($q) use($baseUrl, $noProfilePic){
                    return $q->selectRaw("id,first_name,last_name, is_online, COALESCE((SELECT CASE WHEN up.photo_url IS NOT NULL THEN CONCAT('$baseUrl','/',up.photo_url) ELSE '$noProfilePic' END FROM user_photos up WHERE up.photo_user_id = users.id AND up.photo_type = 'profile' ORDER BY up.created_at DESC LIMIT 1), '$noProfilePic') as profile_photo");
                }])->select('comment_id','comment_body','comment_user_id','comment_parent_id','updated_at as created_at')->take(2);
            }
        ])
        ->where('user_id', $user_id)
        ->select('post_id','post_content','created_at','user_id')
        ->orderBy('created_at', 'desc')->paginate(10);
        
        $posts = $posts->items();
        if(count($posts) > 0) {
            foreach($posts as $key => $p) {
                $posts[$key]->postlikes = $p->postlikes()->count();
                $posts[$key]->commentscount = $p->postcomments()->count();
                $posts[$key]->is_liked = $p->postlikes()->where(['like_status' => 1, 'like_user_id' => $user_id])->count();
            }
        }
        $response['status'] = \Config::get('app.success_status');
        $response['message'] = 'Here are posts';
        $response['data']->posts = $posts;
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | create requested post by user
    | @params [token, post_content]
    |----------------------------------------------------------------------
    */
    public function CreatePost(Request $request) {
        $params = $request->all();
        $response = \Config::get('app.api_response');
        // required parameters
        $rules = array(
            'content' => 'required_without:media',
            'media' => 'required_without:content',
        );
        $messages = array(
            'content.required_without' => 'Please provide content or media',
            'media.required_without' => 'Please provide content or media',
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }
        $user_id = $request->user->id;
        $data = $request->except('_token');

        // perform insertion if new record
        try {
            DB::beginTransaction();
            // create location record in db
            $content = (isset($data['content'])) ? $data['content'] : '';
            $post = Posts::create(['post_content' => $content, 'user_id' => $user_id]);
            // check if location has files
            if($request->has('media') && count($data['media']) > 0) {
                $photos = Input::file('media');
                if(count($photos) > 0) {
                    foreach ($photos as $photo) {
                        $photoname = uniqid().".".$photo->getClientOriginalExtension();
                        $uploadData = $photo->move('uploads', $photoname);
                        PostsMeta::create(['post_id' => $post->post_id, 'meta_url' => $photoname]);
                    }
                }
            }
            DB::commit();
            if($post) {
                $response['status'] = \Config::get('app.success_status');
                $response['message'] = 'Post created successfully';
                $response['data']->post = $this->GetPost($post->post_id);
            }
            else {
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'Unable to created post at the moment';
            }
            return \response()->json($response);
        }
        catch (\PDOException $e) {
            DB::rollBack();
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to created post at the moment';
            return \response()->json($response);
        }
    }

    /*
    |----------------------------------------------------------------------
    | update requested post by user
    | @params [token, content, media, post_id]
    |----------------------------------------------------------------------
    */
    public function UpdatePost(Request $request) {
        $baseUrl = url('uploads');
        $noProfilePic = url('img/no-profile-photo.jpg');
        $params = $request->all();
        $response = \Config::get('app.api_response');
        // required parameters
        $rules = array(
            'post_id' => 'required'
        );
        $messages = array(
            'post_id.required' => 'Please provide post id to update'
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }
        $user_id = $request->user->id;
        $data = $request->except('_token');

        try {
            DB::beginTransaction();
            $post = $this->GetPost($data['post_id']);
            // check if post exists or not
            if(empty($post)) {
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'Unable to find requested post';
                return \response()->json($response);
            }

            // check if everything vanishes already from post
            if((!isset($data['content']) || empty($data['content'])) && (!$request->has('media')) && !empty($post->content) && count($post->media) <= 0) {
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'Please provide content or media to update post';
                return \response()->json($response);
            }
            // create location record in db
            $content = (isset($data['content'])) ? $data['content'] : '';
            $post = Posts::where(['post_id' => $data['post_id']])->update(['post_content' => $content]);
            // check if location has files
            if($request->has('media') && count($data['media']) > 0) {
                $photos = Input::file('media');
                if(count($photos) > 0) {
                    foreach ($photos as $photo) {
                        $photoname = uniqid().".".$photo->getClientOriginalExtension();
                        $uploadData = $photo->move('uploads', $photoname);
                        PostsMeta::create(['post_id' => $data['post_id'], 'meta_url' => $photoname]);
                    }
                }
            }
            DB::commit();
            if($post) {
                $postObj = $this->GetPost($data['post_id']);
                $postObj->postcomments = $postObj->postcomments()->with(['commentuser' => function($q) use($baseUrl, $noProfilePic){
                    return $q->selectRaw("id, first_name, last_name, is_online, COALESCE((SELECT CASE WHEN up.photo_url IS NOT NULL THEN CONCAT('$baseUrl','/',up.photo_url) ELSE '$noProfilePic' END FROM user_photos up WHERE up.photo_user_id = users.id AND up.photo_type = 'profile' ORDER BY up.created_at DESC LIMIT 1), '$noProfilePic') as profile_photo");
                }])->select('comment_id','comment_body','comment_user_id','comment_parent_id','updated_at as created_at')->take(2)->get();
                $postObj->postuser = $postObj->postuser()->selectRaw("id,first_name,last_name,is_online, COALESCE((SELECT CASE WHEN up.photo_url IS NOT NULL THEN CONCAT('$baseUrl','/',up.photo_url) ELSE '$noProfilePic' END FROM user_photos up WHERE up.photo_user_id = users.id AND up.photo_type = 'profile' ORDER BY up.created_at DESC LIMIT 1), '$noProfilePic') as profile_photo")->first();
                $postObj->postlikes = $postObj->postlikes()->count();
                $postObj->commentscount = $postObj->postcomments()->count();
                $postObj->is_liked = $postObj->postlikes()->where(['like_status' => 1, 'like_user_id' => $user_id])->count();

                $response['status'] = \Config::get('app.success_status');
                $response['message'] = 'Post updated successfully';
                $response['data']->post = $postObj;
            }
            else {
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'Unable to update post at the moment';
            }
            return \response()->json($response);
        }
        catch (\PDOException $e) {
            DB::rollBack();
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to update post at the moment';
            return \response()->json($response);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | delete requested post photos
    | @params [token, media_id[], post_id]
    |--------------------------------------------------------------------------
    */
    public function DeletePostPhotos(Request $request){
        $params = $request->all();
        $response = \Config::get('app.api_response');
        // required parameters
        $rules = array(
            'media_id' => 'required|array',
            'post_id' => 'required',
        );
        $messages = array(
            'media_id.required' => 'Please provide media to delete',
            'media_id.array' => 'Please provide array of media ids to delete',
            'post_id.required' => 'Please provide post against which meia to be deleted',
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }
        $user_id = $request->user->id;
        $data = $request->except('_token');

        $post = $this->GetPost($data['post_id']);
        // check if post exists or not
        if(empty($post)) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to find requested post';
            return \response()->json($response);
        }

        // check if media exists against post
        $photo_id = $data['media_id'];
        $is_exist = PostsMeta::where(['post_id' => $data['post_id']])->whereIn('meta_id',$photo_id)->get();
        if(count($is_exist) < count($photo_id)) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Requested media not found';
            return \response()->json($response);
        }

        // check if post has nothing except that media
        if(empty($post->content) && (count($post->media) <= 1 || count($post->media) == count($photo_id))) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Post cant be empty';
            return \response()->json($response);
        }
        $result = PostsMeta::whereIn('meta_id', $photo_id)->delete();
        if($result) {
            try {
                foreach($is_exist as $pm) {
                    $unlink = public_path().'/uploads/'.$pm->meta_url;
                    unlink($unlink);
                }
            }
            catch(\Exception $ex) {}
            $response['status'] = \Config::get('app.success_status');
            $response['message'] = 'Requested media deleted successfully';
        }
        else {
            $response['status'] = 500;
            $response['message'] = 'Unable to delete requested media at the moment';
        }
        return \response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | delete requested post by user
    | @params [token, post_id]
    |--------------------------------------------------------------------------
    */
    public function DeletePost(Request $request){
        $params = $request->all();
        $response = \Config::get('app.api_response');
        // required parameters
        $rules = array(
            'post_id' => 'required',
        );
        $messages = array(
            'post_id.required' => 'Please provide post id to be deleted'
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }
        $user_id = $request->user->id;
        $data = $request->except('_token');

        $post = $this->GetPost($data['post_id']);
        // check if post exists or not
        if(empty($post)) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to find requested post';
            return \response()->json($response);
        }

        $result = Posts::where('post_id', $data['post_id'])->delete();
        if($result) {
            // check if media exists against post
            $postMedia = PostsMeta::where(['post_id' => $data['post_id']])->get();
            if(count($postMedia) > 0) {
                foreach($postMedia as $pm) {
                    try {
                        $unlink = public_path().'/uploads/'.$pm->meta_url;
                        unlink($unlink);
                    }
                    catch(\Exception $ex) {continue;}
                }
            }
            $response['status'] = \Config::get('app.success_status');
            $response['message'] = 'Requested post deleted successfully';
        }
        else {
            $response['status'] = 500;
            $response['message'] = 'Unable to delete requested post at the moment';
        }
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | get single post requested by user
    | @params [token, post_id]
    |----------------------------------------------------------------------
    */
    public function GetSinglePost(Request $request) {
        $baseUrl = url('uploads');
        $noProfilePic = url('img/no-profile-photo.jpg');
        $params = $request->all();
        $response = \Config::get('app.api_response');
        // required parameters
        $rules = array(
            'post_id' => 'required'
        );
        $messages = array(
            'post_id.required' => 'Please provide post id to get',
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }
        $user_id = $request->user->id;
        $data = $request->except('_token');

        // perform insertion if new record
        try {
            $post = $this->GetPost($data['post_id']);
            if(!empty($post)) {
                $post->postcomments = $post->postcomments()->with(['commentuser' => function($q) use($baseUrl, $noProfilePic){
                    return $q->selectRaw("id, first_name, last_name, is_online, COALESCE((SELECT CASE WHEN up.photo_url IS NOT NULL THEN CONCAT('$baseUrl','/',up.photo_url) ELSE '$noProfilePic' END FROM user_photos up WHERE up.photo_user_id = users.id AND up.photo_type = 'profile' ORDER BY up.created_at DESC LIMIT 1), '$noProfilePic') as profile_photo");
                }])->select('comment_id','comment_body','comment_user_id','comment_parent_id','updated_at as created_at')->take(2)->get();
                $post->postuser = $post->postuser()->selectRaw("id,first_name,last_name, is_online, COALESCE((SELECT CASE WHEN up.photo_url IS NOT NULL THEN CONCAT('$baseUrl','/',up.photo_url) ELSE '$noProfilePic' END FROM user_photos up WHERE up.photo_user_id = users.id AND up.photo_type = 'profile' ORDER BY up.created_at DESC LIMIT 1), '$noProfilePic') as profile_photo")->first();
                $post->postlikes = $post->postlikes()->count();
                $post->commentscount = $post->postcomments()->count();
                $post->is_liked = $post->postlikes()->where(['like_status' => 1, 'like_user_id' => $user_id])->count();
                $response['status'] = \Config::get('app.success_status');
                $response['message'] = 'Here are post details';
                $response['data']->post = $post;
            }
            else {
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'Requested post not found';
            }
            return \response()->json($response);
        }
        catch (\PDOException $e) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to get requested post at the moment';
            return \response()->json($response);
        }
    }


    /*
    |----------------------------------------------------------------------
    | get requested post by id
    | @params [post_id]
    |----------------------------------------------------------------------
    */
    public function GetPost($post_id) {
        $baseUrl = url('uploads');
        $post = Posts::where(['post_id' => $post_id])->selectRaw("post_id, COALESCE(post_content) as content, created_at, user_id")->first();
        if(!empty($post)) {
            $post['media'] = array();
            $media = $post->postmeta()->selectRaw("meta_id as id, CONCAT('$baseUrl', '/', meta_url) as url, post_id")->get();
            if(count($media) > 0) {
                $post['media'] = array_map(function($p){ return ['url' => $p['url'], 'id' => $p['id']]; }, $media->toArray());
            }
        }
        return $post;
    }

    /*
    |----------------------------------------------------------------------
    | create requested like against post by user
    | @params [token, post_id]
    |----------------------------------------------------------------------
    */
    public function CreateLike(Request $request) {
    	$params = $request->all();
        $response = \Config::get('app.api_response');
        // required parameters
        $rules = array(
            'post_id' => 'required',
        );
        $messages = array(
            'post_id.required' => 'Please provide post id to like'
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }
        $user_id = $request->user->id;
        $data = $request->except('_token');

        $post = $this->GetPost($data['post_id']);
        // check if post exists or not
        if(empty($post)) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to find requested post';
            return \response()->json($response);
        }

        // perform insertion if new record
        $is_exist = Likes::where(['like_user_id' => $user_id, 'like_parent_id' => $data['post_id']])->first();
        if(empty($is_exist)) {
            try {
                $likes_count = Likes::where(['like_parent_id' => $data['post_id'], 'like_status' => 1])->get();
                $response['status'] = \Config::get('app.success_status');
                $response['message'] = 'You liked the post successfully';
                $response['data']->is_liked = $post->postlikes()->where(['like_status' => 1, 'like_user_id' => $user_id])->count();
                $response['data']->postlikes = $post->postlikes()->count();
            }
            catch (\PDOException $e) {
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'Unable to like the post at the moment';
            }
            return \response()->json($response);
        }
        // update if record exists
        else {
            $status_text = ($is_exist->like_status == 1) ? 'disliked' : 'liked';
            try {
                $is_exist->like_status = ($is_exist->like_status == 1) ? 0 : 1;
                $result = $is_exist->save();
                if($result) {
                    $response['status'] = \Config::get('app.success_status');
                    $response['message'] = 'You '.$status_text.' the post successfully';
                    $response['data']->is_liked = $post->postlikes()->where(['like_status' => 1, 'like_user_id' => $user_id])->count();
                    $response['data']->postlikes = $post->postlikes()->count();
                    
                }
                else {
                    $response['status'] = \Config::get('app.failure_status');
                    $response['message'] = 'Unable to '.$status_text.' the post at the moment';
                }
            } catch (\PDOException $e) {
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'Unable to '.$status_text.' the post at the moment';
            }
            return \response()->json($response);
        }
    }

    /*
    |----------------------------------------------------------------------
    | create requested comment against post by user
    | @params [token, post_id, body, comment_id]
    |----------------------------------------------------------------------
    */
    public function CreateComment(Request $request) {
        $baseUrl = url('uploads');
        $noProfilePic = url('img/no-profile-photo.jpg');
        $params = $request->all();
        $response = \Config::get('app.api_response');
        // required parameters
        $rules = array(
            'post_id' => 'required',
            'body' => 'required'
        );
        $messages = array(
            'post_id.required' => 'Please provide post id to comment',
            'body.required' => 'Please provide comment body'
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }
        $user_id = $request->user->id;
        $data = $request->except('_token');

        $post = $this->GetPost($data['post_id']);
        // check if post exists or not
        if(empty($post)) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to find requested post';
            return \response()->json($response);
        }

        // perform insertion if new record
        if(empty($data['comment_id'])) {
            try {
                $comment = Comments::create(['comment_body' => $data['body'] ,'comment_parent_id' => $data['post_id'], 'comment_user_id' => $user_id]);
                if($comment) {
                    $response['status'] = \Config::get('app.success_status');
                    $response['message'] = 'Comment posted successfully';
                    $commentObj = Comments::with(['commentuser' => function($q) use($baseUrl, $noProfilePic){
                                return $q->selectRaw("id,first_name,last_name,is_online, COALESCE((SELECT CASE WHEN up.photo_url IS NOT NULL THEN CONCAT('$baseUrl','/',up.photo_url) ELSE '$noProfilePic' END FROM user_photos up WHERE up.photo_user_id = users.id AND up.photo_type = 'profile' ORDER BY up.created_at DESC LIMIT 1), '$noProfilePic') as profile_photo");
                            }
                        ])
                        ->where(['comment_parent_id' => $data['post_id'], 'comment_id' => $comment->comment_id])->orderBy('created_at', 'desc')->select('comment_id','comment_body','comment_user_id','comment_parent_id','updated_at as created_at')->first();
                    $response['data']->comment = $commentObj;
                    $response['data']->commentscount = $post->postcomments()->count();
                }
                else {
                    $response['status'] = \Config::get('app.failure_status');
                    $response['message'] = 'Unable to post comment at the moment';
                }
            } catch (\PDOException $e) {
                DB::rollBack();
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'Unable to post comment at the moment';
            }
            return \response()->json($response);
        }
        // update if record exists
        elseif(!empty($data['comment_id'])) {
            try {
                $comment = Comments::where(['comment_parent_id' => $data['post_id'], 'comment_user_id' => $user_id, 'comment_id' => $data['comment_id']])->update(['comment_body' => $data['body']]);
                if($comment) {
                    $response['status'] = \Config::get('app.success_status');
                    $response['message'] = 'Comment updated successfully';
                    $commentObj = Comments::with(['commentuser' => function($q) use($baseUrl, $noProfilePic){
                                return $q->selectRaw("id,first_name,last_name, is_online, COALESCE((SELECT CASE WHEN up.photo_url IS NOT NULL THEN CONCAT('$baseUrl','/',up.photo_url) ELSE '$noProfilePic' END FROM user_photos up WHERE up.photo_user_id = users.id AND up.photo_type = 'profile' ORDER BY up.created_at DESC LIMIT 1), '$noProfilePic') as profile_photo");
                            }
                        ])
                        ->where(['comment_parent_id' => $data['post_id'], 'comment_id' => $data['comment_id']])->orderBy('created_at', 'desc')->select('comment_id','comment_body','comment_user_id','comment_parent_id','updated_at as created_at')->first();
                    $response['data']->comment = $commentObj;
                    $response['data']->commentscount = $post->postcomments()->count();
                }
                else {
                    $response['status'] = \Config::get('app.failure_status');
                    $response['message'] = 'Unable to update comment at the moment';
                }
            } catch (\PDOException $e) {
                DB::rollBack();
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'Unable to update comment at the moment';
            }
            return \response()->json($response);
        }
    }

    /*
    |----------------------------------------------------------------------
    | get requested post comments
    | @params [token, post_id]
    |----------------------------------------------------------------------
    */
    public function GetComments(Request $request) {
        $baseUrl = url('uploads');
        $noProfilePic = url('img/no-profile-photo.jpg');
        $params = $request->all();
        $response = \Config::get('app.api_response');
        // required parameters
        $rules = array(
            'post_id' => 'required'
        );
        $messages = array(
            'post_id.required' => 'Please provide post id to get comments',
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }
        $user_id = $request->user->id;
        $data = $request->except('_token');

        $post = $this->GetPost($data['post_id']);
        // check if post exists or not
        if(empty($post)) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to find requested post';
            return \response()->json($response);
        }

        // perform insertion if new record
        try {
            $comments = Comments::with(['commentuser' => function($q) use($baseUrl, $noProfilePic){
                    return $q->selectRaw("id,first_name,last_name, is_online, COALESCE((SELECT CASE WHEN up.photo_url IS NOT NULL THEN CONCAT('$baseUrl','/',up.photo_url) ELSE '$noProfilePic' END FROM user_photos up WHERE up.photo_user_id = users.id AND up.photo_type = 'profile' ORDER BY up.created_at DESC LIMIT 1), '$noProfilePic') as profile_photo");
                }
            ])
            ->where(['comment_parent_id' => $data['post_id']])->orderBy('created_at', 'desc')->select('comment_id','comment_body','comment_user_id','comment_parent_id','updated_at as created_at')->paginate(50);
            
            $response['status'] = \Config::get('app.success_status');
            $response['message'] = 'Here are comments list';
            $response['data']->comments = $comments->items();
        }
        catch (\Exception $e) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to retrieve comments for requested post';
        }
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | delete requested comment against post by user
    | @params [token, post_id, comment_id]
    |----------------------------------------------------------------------
    */
    public function DeleteComment(Request $request) {
        $params = $request->all();
        $response = \Config::get('app.api_response');
        // required parameters
        $rules = array(
            'post_id' => 'required',
            'comment_id' => 'required',
        );
        $messages = array(
            'post_id.required' => 'Please provide comment post id',
            'comment_id.required' => 'Please provide comment id to delete'
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }
        $user_id = $request->user->id;
        $data = $request->except('_token');

        $post = $this->GetPost($data['post_id']);
        // check if post exists or not
        if(empty($post)) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to find requested post';
            return \response()->json($response);
        }

        try {
            $is_exist = Comments::where(['comment_parent_id' => $data['post_id'], 'comment_id' => $data['comment_id'], 'comment_user_id' => $user_id])->first();
            if(!empty($is_exist)) {
                $result = $is_exist->delete();
                if($result) {
                    $response['status'] = \Config::get('app.failure_status');
                    $response['message'] = 'Requested comment deleted successfully';
                }
                else {
                    $response['status'] = \Config::get('app.failure_status');
                    $response['message'] = 'Unable to delete requested comment at the moment hw';
                }
            }
            else {
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'Requested comment not found';
            }
        } catch (\PDOException $e) {
            DB::rollBack();
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to delete requested comment at the moment';
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
