<?php

namespace App\Http\Controllers\User;

use Auth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserChangePassword;
use App\Http\Requests\UserProfileSettings;
use App\Models\UserPhotos;
use Illuminate\Support\Facades\Input;
use App\Models\Locations;
use App\Models\LocationImages;
use App\Models\Posts;
use App\Models\PostsMeta;
use App\Models\Alerts;

class ProfileController extends Controller
{
    /*
    |----------------------------------------------------------------------
    | load user profile page
    |----------------------------------------------------------------------
    */
    public function index($id = '') {
        $user_id = (!empty($id)) ? $id : Auth::user()->id;
        $user = User::where(['id' => $user_id])->first();
        
        $posts = Posts::with(['postmeta', 'postuser',
        	'postcomments' => function($q) {
                return $q->take(5)->get();
            }, 
			'postlikes' => function($q) {
				return $q->with('likeuser')->where('like_status', 1);
			}
        ])
        ->where(['user_id' => $user_id])
        ->orderBy('created_at', 'desc')->get();
        return view('user.profile', compact('user', 'posts'));
    }

    /*
    |----------------------------------------------------------------------
    | load user change password form
    |----------------------------------------------------------------------
    */
    public function ChangePassword() {
        return view('user.change-password');
    }

    /*
    |----------------------------------------------------------------------
    | save requested change password
    |----------------------------------------------------------------------
    */
    public function PostChangePassword(Request $request, UserChangePassword $userrequest) {
    	$data = $request->except('_token');
        $user = User::where('id' ,Auth::user()->id)->first();
        
        if(!Hash::check($data['current_password'], $user->password)) {
        	return redirect()->back()->withErrors(['current_password' => 'Please enter correct current password']);
        }
        
        $user->password = Hash::make($data['password']);
        $is_saved = $user->save();
        
        if($is_saved) {
        	return redirect()->back()->with('message', 'Your password has been changed');	
        }
        else {
        	return redirect()->back()->with('error', 'Unable to change your pasword at the moment');
        }
    }

    /*
    |----------------------------------------------------------------------
    | load user profile information form
    |----------------------------------------------------------------------
    */
    public function ProfileSettings() {
    	$user = User::where('id' ,Auth::user()->id)->first();
        return view('user.profile-settings', compact('user'));
    }

    /*
    |----------------------------------------------------------------------
    | save requested change in profile information
    |----------------------------------------------------------------------
    */
    public function PostProfileSettings(Request $request, UserProfileSettings $userrequest) {
    	$data = $request->except('_token');
        $is_saved = User::where('id' ,Auth::user()->id)->update($data);
                
        if($is_saved) {
        	return redirect()->back()->with('message', 'Your profile has been updated');	
        }
        else {
        	return redirect()->back()->with('error', 'Unable to update your profile at the moment');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | upload profile cover/ profile photo
    |--------------------------------------------------------------------------
    */
    public function UploadProfilePhoto(Request $request){
        $response = [];
        $user_id = Auth::user()->id;
        $photo = Input::file('photo');
        $type = Input::get('type');

        $photoname = uniqid().".".$photo->getClientOriginalExtension();
        $photo->move("uploads", $photoname);

        $photoObj = UserPhotos::create(['photo_type' => $type, 'photo_url' => $photoname, 'photo_status' => 1, 'photo_user_id' => Auth::user()->id]);
        if($photoObj) {
            try{
                $post_content = ($type == 'profile') ? 'Updated profile photo' : 'Upated cover photo';
                $post = Posts::create(['post_content' => $post_content, 'user_id' => $user_id]);
                if($post) {
                    PostsMeta::create(['post_id' => $post->post_id, 'meta_url' => $photoname]);
                }
            }
            catch(\Exception $ex){}
            return \response()->json(['status' => 200, 'message' => ucfirst($type).' photo uploaded successfully', 'url' => url('uploads/'.$photoname)]);
        }
        else {
            return \response()->json(['status' => 500, 'message' => 'Unable to upload '.$type.' photo at the moment']);
        }
    }

    /*
    |----------------------------------------------------------------------
    | get existing notifications
    |----------------------------------------------------------------------
    */
    public function GetNotifications(Request $request) {
        $response = array();
        $response['status'] = 200;
        $user_id = Auth::user()->id;
        $notifications = Alerts::join('users as u', 'alerts.user_id', 'u.id')
        ->where(function($q){ return $q->where('is_read', 0)->orWhere('is_read', 1); })
        ->where(['alerts.user_id' => $user_id])
        ->selectRaw("alerts.*, (SELECT photo_url FROM user_photos as up WHERE up.photo_type = 'profile' AND up.photo_user_id = alerts.sender_id AND up.photo_status = 1 ORDER BY created_at DESC LIMIT 1) AS photo_url")
        ->orderBy('alerts.created_at', 'DESC')
        ->get();
        return view('user.notifications', compact('notifications'));
    }

    /*
    |--------------------------------------------------------------------------
    | get user photos by type
    |--------------------------------------------------------------------------
    */
    public function GetPhotos(Request $request){
        $types = array('profile', 'cover', 'post', 'location');
        $type = $request->get('type');
        if(!in_array($type, $types)) {
            $type = 'profile';
        }
        $userId = Auth::user()->id;
        $baseUrl = url('uploads');
        $photos = array();
        try{
            switch ($type) {
                case 'profile':
                    $photos = UserPhotos::where(['photo_type' => 'profile', 'photo_status' => 1, 'photo_user_id' => $userId])
                                ->selectRaw("photo_id as id, CONCAT('$baseUrl', '/', photo_url) as url, created_at")
                                ->paginate(20);
                    break;

                case 'cover':
                    $photos = UserPhotos::where(['photo_type' => 'cover', 'photo_status' => 1, 'photo_user_id' => $userId])
                                ->selectRaw("photo_id as id, CONCAT('$baseUrl', '/', photo_url) as url, created_at")
                                ->paginate(20);
                    break;

                case 'post':
                    $photos = PostsMeta::join('posts as p', 'p.post_id', 'posts_meta.post_id')
                                ->where(['p.user_id' => $userId])->selectRaw("posts_meta.meta_id as id, CONCAT('$baseUrl', '/', posts_meta.meta_url) as url, created_at")
                                ->paginate(20);
                    break;

                case 'location':
                    $photos = LocationImages::where(['poster_id' => $userId])
                                ->selectRaw("id, CONCAT('$baseUrl', '/', location_image_url) as url, created_at")
                                ->paginate(20);
                    break;
                
                default:
                    $photos = array();
                    break;
            }
        }
        catch(\Exception $ex) {
            $photos = array();
        }
        return view('user.photos', compact('photos', 'type'));
    }
    
    /*
    |----------------------------------------------------------------------
    | get privacy policy
    |----------------------------------------------------------------------
    */
    public function GetPrivacy(Request $request) {
        $pageTitle = "Privacy Policy";
        $content = array();
        // perform insertion if new record
        try {
            $content = \DB::table('terms_and_privacy')->where(['type' => 'privacy'])->select('title','content')->first();
        }
        catch (\PDOException $e) {}
        return view('user.privacy', compact('pageTitle', 'content'));
    }

    /*
    |----------------------------------------------------------------------
    | get terms and conditions
    |----------------------------------------------------------------------
    */
    public function GetTerms(Request $request) {
        $pageTitle = "Terms of use";
        $content = array();
        // perform insertion if new record
        try {
            $content = \DB::table('terms_and_privacy')->where(['type' => 'terms'])->select('title','content')->first();
        }
        catch (\PDOException $e) {}
        return view('user.privacy', compact('pageTitle', 'content'));
    }

}
