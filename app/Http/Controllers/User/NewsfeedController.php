<?php

namespace App\Http\Controllers\User;

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

class NewsfeedController extends Controller
{
    /*
    |----------------------------------------------------------------------
    | load user news feed page
    |----------------------------------------------------------------------
    */
    public function index() {
        $user = User::where(['id' => Auth::user()->id])->first();
    	$posts = Posts::with(['postmeta', 'postuser',
    		'postcomments' => function($q) {
                return $q->take(5)->get();
            },
			'postlikes' => function($q) {
				return $q->with('likeuser')->where('like_status', 1);
			}
		])->orderBy('created_at', 'desc')->get();
        return view('user.newsfeed.news-feed', compact('posts', 'user'));
    }
    
    /*
    |----------------------------------------------------------------------
    | get posts with comments
    |----------------------------------------------------------------------
    */
    public function GetPosts(Request $request) {
    	if($request->ajax()) {
            $posts = Posts::with(['postmeta', 'postuser',
            'postcomments' => function($q) {
                return $q->take(5)->get();
            }])->orderBy('created_at', 'desc')->get();
    		$html = view('user.ajax.posts-ajax',compact('posts'))->render();
			return \response()->json(['status' => 200, 'html' => $html]);
    	}
    }

    /*
    |----------------------------------------------------------------------
    | get post function
    |----------------------------------------------------------------------
    */
    public function GetPost(Request $request, $id = '') {
        if($request->ajax()) {
            $data = $request->except('_token');
            $post = Posts::with(['postmeta', 'postuser',
                'postcomments' => function($q) {
                    return $q->take(5)->get();
                }, 
                'postlikes' => function($q) {
                    return $q->with('likeuser')->where('like_status', 1);
                }
            ])
            ->where(['post_id' => $data['target']])->first();

            if(!empty($id)) {
                $html = view('user.ajax.single-posts-ajax',compact('post'))->render();
                return \response()->json(['status' => 200, 'html' => $html]);
            }

            if(empty($post)) {
                return \response()->json(['status' => 500, 'message' => 'Requested post not found']);
            }

            $photoshtml = '';
            if(!empty($post->postmeta)) {
                foreach ($post->postmeta as $meta) {
                    $photoshtml .= '<span class="file-item"><i class="fa fa-times inner-file-item" data-target="'.$meta->meta_id.'"></i><img style="width:100px;height: 100px;margin: 5px 5px 10px 0px;border-radius:3px;" src="'.asset("uploads/$meta->meta_url").'" /></span>';
                }
                $post->photoshtml = $photoshtml;
            }
            return \response()->json(['status' => 200, 'post' => $post]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | upload requested post photos
    |--------------------------------------------------------------------------
    */
    public function CreatePostPhotos(Request $request){
        $response = [];
        $post_id = Input::get('target');
        $photos = Input::file('photos');
        foreach($photos as $photo) {
            $photoname = uniqid().".".$photo->getClientOriginalExtension();
            $photo->move("uploads", $photoname);

            $photoObj = PostsMeta::create(['post_id' => $post_id, 'meta_url' => $photoname]);
            $response[] = '<span class="file-item"><i class="fa fa-times inner-file-item" data-target="'.$photoObj->id.'"></i>
                <img style="width:100px;height: 100px;margin: 5px 5px 10px 0px;border-radius:3px;" src="'.asset("uploads/$photoname").'" />
            </span>';
        }
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | create requested post by user
    |----------------------------------------------------------------------
    */
    public function CreatePost(Request $request) {
    	if($request->ajax()) {
    		$data = $request->except('_token');

        	// perform insertion if new record
    		try {
    			DB::beginTransaction();
                // create location record in db
    			$post = Posts::create(['post_content' => $data['content'], 'user_id' => Auth::user()->id]);
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
    				return \response()->json(['status' => 200, 'message' => 'Post created successfully']);
    			}
    			else {
    				return \response()->json(['status' => 500, 'message' => 'Unable to created post at the moment']);
    			}
    		} catch (\PDOException $e) {
    			DB::rollBack();
    			return \response()->json(['status' => 500, 'message' => 'Unable to created post at the moment']);
    		}
    	}
    }

    /*
    |----------------------------------------------------------------------
    | update requested post by user
    |----------------------------------------------------------------------
    */
    public function UpdatePost(Request $request) {
        if($request->ajax()) {
            $data = $request->except('_token');
            // perform insertion if new record
            try {
                DB::beginTransaction();
                // create location record in db
                $is_exist = Posts::where(['post_id' => $data['target'], 'user_id' => Auth::user()->id])->first();
                if(empty($is_exist)) {
                    return \response()->json(['status' => 500, 'message' => 'Requested post does not exist']);
                }

                $post = Posts::where(['post_id' => $data['target'], 'user_id' => Auth::user()->id])->update(['post_content' => $data['content']]);
                DB::commit();
                if($post) {
                    return \response()->json(['status' => 200, 'message' => 'Post updated successfully']);
                }
                else {
                    return \response()->json(['status' => 500, 'message' => 'Unable to update post at the moment']);
                }
            } catch (\PDOException $e) {
                DB::rollBack();
                return \response()->json(['status' => 500, 'message' => 'Unable to update post at the moment']);
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | delete requested post photos
    |--------------------------------------------------------------------------
    */
    public function DeletePostPhotos(Request $request){
        $response = [];
        $photo_id = Input::get('target');
        $is_exist = PostsMeta::where('meta_id', $photo_id)->first();
        if(empty($is_exist)) {
            $response['status'] = 500;
            $response['message'] = 'Requested image not found';
        }
        else {
            $result = PostsMeta::where('meta_id', $photo_id)->delete();
            if($result) {
                $response['status'] = 200;
                $response['message'] = 'Requested image deleted successfully';
            }
            else {
                $response['status'] = 500;
                $response['message'] = 'Unable to delete requested image at the moment';
            }
        }
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | delete requested post by user
    |----------------------------------------------------------------------
    */
    public function DeletePost(Request $request) {
        if($request->ajax()) {
            $data = $request->except('_token');
            try {
                $is_exist = Posts::where(['post_id' => $data['target'], 'user_id' => Auth::user()->id])->first();
                if(!empty($is_exist)) {
                    $response = Posts::where(['post_id' => $data['target'], 'user_id' => Auth::user()->id])->delete();
                    if($response) {
                        PostsMeta::where(['post_id' => $data['target']])->delete();
                        return \response()->json(['status' => 200, 'message' => 'Post deleted successfully']);
                    }
                    else {
                        return \response()->json(['status' => 500, 'message' => 'Unable to delete post at the moment']);
                    }
                }
                else {
                    return \response()->json(['status' => 500, 'message' => 'Unable to delete post at the moment']);
                }
            } catch (\PDOException $e) {
                return \response()->json(['status' => 500, 'message' => 'Unable to delete post at the moment']);
            }
        }
    }

    /*
    |----------------------------------------------------------------------
    | create requested comment against post by user
    |----------------------------------------------------------------------
    */
    public function CreateComment(Request $request) {
    	if($request->ajax()) {
    		$data = $request->except('_token');
        	// perform insertion if new record
        	if(empty($data['flag'])) {
	    		try {
	    			$comment = Comments::create(['comment_body' => $data['content'] ,'comment_parent_id' => $data['target'], 'comment_user_id' => Auth::user()->id]);
	    			if($comment) {
	    				return \response()->json(['status' => 200, 'message' => 'Comment posted successfully']);
	    			}
	    			else {
	    				return \response()->json(['status' => 500, 'message' => 'Unable to post comment at the moment']);
	    			}
	    		} catch (\PDOException $e) {
	    			DB::rollBack();
	    			return \response()->json(['status' => 500, 'message' => 'Unable to post comment at the moment']);
	    		}
    		}
    		// update if record exists
    		elseif(!empty($data['flag'])) {
    			try {
	    			$comment = Comments::where(['comment_parent_id' => $data['target'], 'comment_user_id' => Auth::user()->id, 'comment_id' => $data['flag']])->update(['comment_body' => $data['content']]);
	    			if($comment) {
	    				return \response()->json(['status' => 200, 'message' => 'Comment updated successfully']);
	    			}
	    			else {
	    				return \response()->json(['status' => 500, 'message' => 'Unable to update comment at the moment']);
	    			}
	    		} catch (\PDOException $e) {
	    			DB::rollBack();
	    			return \response()->json(['status' => 500, 'message' => 'Unable to update comment at the moment']);
	    		}	
    		}
    	}
    }

    /*
    |----------------------------------------------------------------------
    | delete requested comment against post by user
    |----------------------------------------------------------------------
    */
    public function DeleteComment(Request $request) {
    	if($request->ajax()) {
    		$data = $request->except('_token');
    		$targetArr = explode('-', $data['target']);
        	try {
    			$is_exist = Comments::where(['comment_parent_id' => $targetArr[1], 'comment_id' => $targetArr[0]]);
    			if($is_exist) {
    				$response = $is_exist->delete();
    				if($response) {
    					return \response()->json(['status' => 200, 'message' => 'Comment deleted successfully']);
    				}
    				else {
    					return \response()->json(['status' => 500, 'message' => 'Unable to delete comment at the moment']);
    				}
    			}
    			else {
    				return \response()->json(['status' => 500, 'message' => 'Unable to delete comment at the moment']);
    			}
    		} catch (\PDOException $e) {
    			DB::rollBack();
    			return \response()->json(['status' => 500, 'message' => 'Unable to delete comment at the moment']);
    		}
    	}
    }

    /*
    |----------------------------------------------------------------------
    | get requested post comments
    |----------------------------------------------------------------------
    */
    public function GetComments(Request $request) {
    	if($request->ajax()) {
            $data = $request->except('_token');
            $page = (isset($data['page']) && !empty($data['page'])) ? $data['page'] : 1;
        	// perform insertion if new record
    		try {
                $query = Comments::where(['comment_parent_id' => $data['target']])->orderBy('created_at', 'desc');
                $totalcomments = $query->count();
                $comments = $query->take(($page+1)*5)->get();
    			if(count($comments) > 0) {
    				$html = view('user.ajax.comments-ajax',compact('comments', 'page', 'totalcomments'))->render();
    				return \response()->json(['status' => 200, 'html' => $html]);
    			}
    			else {
    				return \response()->json(['status' => 500, 'html' => '']);
    			}
    		} catch (\PDOException $e) {
    			DB::rollBack();
    			return \response()->json(['status' => 500, 'message' => '']);
    		}
    	}
    }

    /*
    |----------------------------------------------------------------------
    | create requested like against post by user
    |----------------------------------------------------------------------
    */
    public function CreateLike(Request $request) {
    	if($request->ajax()) {
    		$data = $request->except('_token');
        	// perform insertion if new record
        	$is_exist = Likes::where(['like_user_id' => Auth::user()->id, 'like_parent_id' => $data['target']])->first();
        	if(empty($is_exist)) {
	    		try {
	    			$like = Likes::create(['like_status' => 1 ,'like_parent_id' => $data['target'], 'like_user_id' => Auth::user()->id]);
	    			if($like) {
	    				$likes_count = Likes::where(['like_parent_id' => $data['target'], 'like_status' => 1])->get();
	    				return \response()->json(['status' => 200, 'message' => 'You liked the post', 'class' => 1, 'likes' => count($likes_count)]);
	    			}
	    			else {
	    				return \response()->json(['status' => 500, 'message' => 'Unable to like the post at the moment']);
	    			}
	    		} catch (\PDOException $e) {
	    			return \response()->json(['status' => 500, 'message' => 'Unable to like the post at the moment']);
	    		}
    		}
    		// update if record exists
    		else {
    			try {
    				$status_text = ($is_exist->like_status == 1) ? 'dislike' : 'like';
	    			$is_exist->like_status = ($is_exist->like_status == 1) ? 0 : 1;
	    			$response = $is_exist->save();
	    			if($response) {
	    				$likes_count = Likes::where(['like_parent_id' => $data['target'], 'like_status' => 1])->get();
	    				return \response()->json(['status' => 200, 'message' => 'You '.$status_text.' the post successfully', 'class' => ($status_text == 'like') ? 1 : 0, 'likes' => count($likes_count)]);
	    			}
	    			else {
	    				return \response()->json(['status' => 500, 'message' => 'Unable to '.$status_text.' the post at the moment']);
	    			}
	    		} catch (\PDOException $e) {
	    			return \response()->json(['status' => 500, 'message' => 'Unable to '.$status_text.' the post at the moment']);
	    		}	
    		}
    	}
    }

}
