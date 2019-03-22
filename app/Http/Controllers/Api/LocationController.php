<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LocationSubscribe;
use App\Http\Requests\LocationRequest;
use App\Models\LocationComments;
use App\Models\LocationImages;
use App\Models\SaveLocation;
use App\Models\LocationCheckin;
use App\Models\LocationRating;
use App\Models\LocationHours;
use App\Models\Locations;
use App\Models\Cities;
use Illuminate\Support\Facades\Input;
use Auth;
use DB;

class LocationController extends Controller
{
	private const DAYS = ['1' => 'Mon', '2' => 'Tue', '3' => 'Wed', '4' => 'Thu', '5' => 'Fri', '6' => 'Sat', '7' => 'Sun'];
    /*
    |----------------------------------------------------------------------
    | search locations within requested radius
    | @params [radius, location, subject, token]
    |----------------------------------------------------------------------
    */
    public function LocationSearch(Request $request) {
    	$response = \Config::get('app.api_response');
        $params = $request->all();
    	// required parameters
    	$rules = array(
	        'radius' => 'numeric',
            'location' => 'required',
	    );
	    $messages = array(
	        'radius.numeric' => 'Radius can only be numeric',
	        'location.required' => 'Please provide location',
	    );

	    $validate = $this->ValidateParams($params, $rules, $messages);
	    if($validate != 1) {
	    	return \response()->json($validate);
	    }
        $locations = array();
        
        if(isset($params['radius'])) {
        	if($params['radius'] == 0) {
        		$radius = '>= 0';
        	}
        	else {
        		$radius = '<= '.$params['radius'];
        	}
        }
        else {
        	$radius = ' <= 15';
        }
        
        $city = Cities::where(['city_id' => $params['location']])->first();
        if(empty($city)) {
        	$response['status'] = \Config::get('app.failure_status');
	    	$response['message'] = 'Provided location not found';
	    	return response()->json($response);
        }
        $lat = $city->city_lat;
        $lang = $city->city_lang;
        $baseUrl = url('uploads');

        $where = "ACOS(SIN(RADIANS(location_lat))*SIN(RADIANS('$lat'))+COS(RADIANS(location_lat))*COS(RADIANS('$lat'))*COS(RADIANS(location_lang)-RADIANS('$lang')))*3959 ".$radius;
        $locations = Locations::whereRaw("$where")->where('status', 1);

        if(isset($params['subject']) && !empty($params['subject'])) {
            $locations->where('locations.location_name', 'like', '%'.$params['subject'].'%');
        }
        $locations = $locations->selectRaw("locations.id, locations.location_name, COALESCE(locations.location_type,'') AS location_type, COALESCE(locations.location_address,'') AS location_address, COALESCE(locations.google_place_id, '') AS google_place_id, locations.location_lat, locations.location_lang, CONCAT(TRUNCATE(COALESCE(ACOS(SIN(RADIANS(locations.location_lat))*SIN(RADIANS('$lat'))+COS(RADIANS(locations.location_lat))*COS(RADIANS('$lat'))*COS(RADIANS(locations.location_lang)-RADIANS('$lang')))*3959, 0), 1)) AS distance, COALESCE((SELECT CASE WHEN li.location_image_url IS NOT NULL THEN CONCAT('$baseUrl','/',li.location_image_url) ELSE '' END FROM location_images as li WHERE li.location_id = locations.id ORDER BY li.created_at DESC LIMIT 1), '') as url")->get();

        // return response to mobile
        $response['status'] = \Config::get('app.success_status');
    	$response['message'] = 'Here is locations list';
    	$response['data']->locations = $locations;
        return response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | get cities list for dropdown values
    | @params []
    |----------------------------------------------------------------------
    */
    public function GetCities(Request $request) {
    	$response = \Config::get('app.api_response');
        $cities = Cities::selectRaw("city_id, city_name")->get();
        // return response to mobile
        $response['status'] = \Config::get('app.success_status');
    	$response['message'] = 'Here is cities list';
    	$response['data']->cities = $cities;
        return response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | get requested location detail
    | @params [location_id, token]
    |----------------------------------------------------------------------
    */
    public function LocationDetail(Request $request) {
    	$params = $request->all();
    	$baseUrl = url('uploads');
    	$response = \Config::get('app.api_response');
    	// required parameters
    	$rules = array(
	        'location_id' => 'required',
            'lat' => 'required',
            'long' => 'required'
	    );
	    $messages = array(
	        'location_id.required' => 'Please provide location id',
            'lat' => 'required',
            'long' => 'required'
	    );
	    // validate request
	    $validate = $this->ValidateParams($params, $rules, $messages);
	    if($validate != 1) {
	    	return \response()->json($validate);
	    }

        $lat = $params['lat'];
        $lang = $params['long'];
        $weather = array();

        try {
            $location = Locations::with([
                'images' => function($query) use($baseUrl) {
        	       return $query->selectRaw("location_id, CONCAT('$baseUrl', '/', location_image_url) as url")->take(15);
        	    }
            ])->where(['id' => $params['location_id'], 'status' => 1])->selectRaw("*, CASE WHEN location_ar_view IS NULL OR location_ar_view = '' THEN '' ELSE CONCAT('$baseUrl', '/', location_ar_view) END as location_ar_view, CONCAT(user_id) AS user_id, CONCAT(TRUNCATE(COALESCE(ACOS(SIN(RADIANS(location_lat))*SIN(RADIANS('$lat'))+COS(RADIANS(location_lat))*COS(RADIANS('$lat'))*COS(RADIANS(location_lang)-RADIANS('$lang')))*3959, 0), 1)) AS distance, '' as location_phone")->first();
            
            if(empty($location)) {
                $response['status'] = \Config::get('app.failure_status');
                $response['message']='Unable to find your requested location';
                return \response()->json($response);
            }
            
            if(!empty($location)) {
                $location->link = url('user/locations/'.$location->id).((!empty($location->google_place_id)) ? ('/'.$location->google_place_id) : '');
                $location->scenes = $location->images()->count();
                $location->reviews = $location->rating()->count();
                $location->visits = $location->checkins()->count();
                $location->is_checkin = $location->checkins()->where(['lc_status' => 1, 'lc_user_id' => $request->user->id])->count();
                $rating = ($location->rating()->avg('lr_rating')) ? bcdiv($location->rating()->avg('lr_rating'), 1, 2) : '0.0';
                $location->rating = (float)$rating;
                $location->subscribers = $location->subscriptions()->where('lsb_status',1)->count();

                $location->is_subscribed = $location->subscriptions()->where(['lsb_status' => 1, 'lsb_user_id' => $request->user->id])->count();
                $location->is_saved = $location->savedlist()->where(['ls_status' => 1, 'ls_user_id' => $request->user->id])->count();
                $location->is_rated = $location->rating()->where(['lr_location_id' => $params['location_id'], 'lr_user_id' => $request->user->id])->count();

                if($location->location_type == 'buisness') {
                	$location->openinghours = $location->openinghours()->selectRaw("(CASE WHEN lh_day = 1 THEN 'Mon' WHEN lh_day = 2 THEN 'Tue' WHEN lh_day = 3 THEN 'Wed' WHEN lh_day = 4 THEN 'Thu' WHEN lh_day = 5 THEN 'Fri' WHEN lh_day = 6 THEN 'Sat' WHEN lh_day = 7 THEN 'Sun' END) as day, COALESCE(lh_open, '') as open, COALESCE(lh_close, '') as close, CONCAT(lh_is_holiday) as holiday")->get();
                }

                // weather api key
                // 8e97570eda804b97b1f320ed3e63e45d
                $wurl = 'https://api.weatherbit.io/v2.0/forecast/daily?lat='.$location->location_lat.'&lon='.$location->location_lang.'&days=5&key=8e97570eda804b97b1f320ed3e63e45d';
                // for live server
                // get 5 days weather for location
                try{
                    $wch = curl_init();
                    curl_setopt($wch,CURLOPT_URL,$wurl);
                    curl_setopt($wch,CURLOPT_RETURNTRANSFER,true);
                    $woutput = curl_exec($wch);
                    curl_close($wch);
                    $w_detail = json_decode($woutput);
                    // $location->weather = $w_detail->data;
                }
                catch(\Exception $ex) {
                    $w_detail = array();
                    $location->weather = array();
                }

                // for local server
                // $client = new \GuzzleHttp\Client(['verify' => false ]);
                // $wres = $client->request('GET', $wurl);
                // $w_detail = json_decode($wres->getBody());
                if(!empty($w_detail->data)) {
                    foreach ($w_detail->data as $key => $we) {
                        $weather[] = array(
                            'valid_date' => $we->valid_date,
                            'icon' => 'https://www.weatherbit.io/static/img/icons/'.$we->weather->icon.'.png',
                            'description' => $we->weather->description,
                            'temp' => (string)$we->temp,
                            'max_temp' => (string)$we->max_temp,
                            'min_temp' => (string)$we->min_temp
                        );
                    }
                }
                $location->weather = $weather;
                // $location->weather = array();
            }
        }
        catch (\Exception $ex) {
            $location = array();
        }

        $response['status'] = \Config::get('app.success_status');
        $response['message'] = 'Here is location detail';
        $response['data']->location = $location;
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | create requested comment against location by user
    | @params [location_id, comment, token]
    |----------------------------------------------------------------------
    */
    public function CreateComment(Request $request) {
        $params = $request->all();
        $response = \Config::get('app.api_response');
        // required parameters
        $rules = array(
            'location_id' => 'required',
            'comment' => 'required',
        );
        $messages = array(
            'location_id.required' => 'Please provide location id',
            'comment.required' => 'Please provide comment to post',
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }

        $data = $request->except('_token');
        // perform insertion if new record
        try {
            $comment = LocationComments::create(['comment_body' => $data['comment'] ,'comment_parent_id' => $data['location_id'], 'comment_user_id' => $request->user->id]);
            if($comment) {
                $comment->comment = $comment->comment_body;
                unset($comment->updated_at, $comment->comment_parent_type, $comment->comment_body);
                $response['status'] = \Config::get('app.success_status');
                $response['message'] = 'your comment posted successfully';
                $response['data']->comment = $comment;
            }
            else {
                $response['status'] = \Config::get('app.failure_status');
                $response['message']='unable to post your comment at the moment';
            }
        } catch (\PDOException $e) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message']='unable to post your comment at the moment';
        }
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | get requested location comments
    | @params [location_id, token]
    |----------------------------------------------------------------------
    */
    public function GetComments(Request $request) {
        $params = $request->all();
        $baseUrl = url('uploads');
        $noProfilePic = url('img/no-profile-photo.jpg');
        $response = \Config::get('app.api_response');
        // required parameters
        $rules = array(
            'location_id' => 'required',
        );
        $messages = array(
            'location_id.required' => 'Please provide location id',
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }

        $data = $request->except('_token');
        try {
            $comments = LocationComments::with([
                'commentuser' => function($query) use($baseUrl, $noProfilePic) {
                    return $query->selectRaw("id, first_name, last_name, is_online, COALESCE((SELECT CASE WHEN up.photo_url IS NOT NULL THEN CONCAT('$baseUrl','/',up.photo_url) ELSE '$noProfilePic' END FROM user_photos up WHERE up.photo_user_id = users.id AND up.photo_type = 'profile' ORDER BY up.created_at DESC LIMIT 1), '$noProfilePic') as profile_photo");
                }
            ])
            ->where(['comment_parent_id' => $data['location_id']])
            ->select('comment_body as comment', 'comment_id as id', 'created_at', 'comment_parent_id', 'comment_user_id')
            ->orderBy('created_at', 'desc')->paginate(15);
            
            $response['status'] = \Config::get('app.success_status');
            $response['message'] = 'Here is comments list';
            $response['data']->comments = $comments->items(15);
        }
        catch (\PDOException $e) {
            $response['status'] = \Config::get('app.success_status');
            $response['message'] = 'Unable to get comments list, try again';
        }
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | save requested location against user
    | @params [location_id, token]
    |----------------------------------------------------------------------
    */
    public function SaveLocation(Request $request) {
        $params = $request->all();
        $response = \Config::get('app.api_response');
        // required parameters
        $rules = array(
            'location_id' => 'required',
        );
        $messages = array(
            'location_id.required' => 'Please provide location id',
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }

        $data = $request->except('_token');
        // perform insertion if new record
        try {
            $is_exist = SaveLocation::where(['ls_location_id' => $data['location_id'] ,'ls_user_id' => $request->user->id])->first();

            if(!empty($is_exist)) {
                if($is_exist->ls_status == 1) {
                    $is_exist->ls_status = 0;
                    $result = $is_exist->save();
                    if($result) {
                        $response['status']  = \Config::get('app.success_status');
                        $response['message']  = 'Location removed from your list';
                        
                    }
                    else {
                        $response['status']  = \Config::get('app.failure_status');
                        $response['message']  = 'Unable to save location at the moment';
                    }
                }
                else {
                    $is_exist->ls_status = 1;
                    $result = $is_exist->save();
                    if($result) {
                        $response['status']  = \Config::get('app.success_status');
                        $response['message']  = 'Location saved to your list';
                    }
                    else {
                        $response['status']  = \Config::get('app.failure_status');
                        $response['message']  = 'Unable to save location at the moment';
                    }
                }
            }
            else {
                $result = SaveLocation::create(['ls_location_id' => $data['location_id'], 'ls_user_id' => $request->user->id]);
                if($result) {
                    $response['status']  = \Config::get('app.success_status');
                    $response['message']  = 'Location saved to your list';
                }
                else {
                    $response['status']  = \Config::get('app.failure_status');
                    $response['message']  = 'Unable to save location at the moment';
                }    
            }
            
        } catch (\PDOException $e) {
            $response['status']  = \Config::get('app.failure_status');
            $response['message']  = 'Unable to save location at the moment';
        }
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | get saved locations for requested users
    | @params [location_id, token]
    |----------------------------------------------------------------------
    */
    public function GetSavedLocations(Request $request) {
        $params = $request->all();
        $response = \Config::get('app.api_response');
        $data = $request->except('_token');
        $baseUrl = url('uploads');
        // perform insertion if new record
        try {
            $saved = SaveLocation::with([
                'location' => function($query) use($baseUrl){
                    return $query->with('rating')->select('locations.id', 'location_name as name', 'location_description as description', 'location_type as type', 'location_address as address', 'location_lat as lat', 'location_lang as lang')->selectRaw("COALESCE((SELECT CASE WHEN li.location_image_url IS NOT NULL THEN CONCAT('$baseUrl','/',li.location_image_url) ELSE '' END FROM location_images as li WHERE li.location_id = locations.id ORDER BY li.created_at DESC LIMIT 1), '') as url");
                }
            ])
            ->where(['ls_user_id' => $request->user->id, 'ls_status' => 1])
            ->select('ls_location_id', 'ls_status as status')
            ->paginate(15);
            $saved = $saved->items();
            if(count($saved) > 0) {
                foreach($saved as $key => $loc) {
                    $rating = '0.0';
                    $length = count($saved[$key]['location']['rating']);
                    $sum = array_sum(array_column($saved[$key]['location']['rating']->toArray(), 'lr_rating'));
                    if($length > 0 && $sum > 0) {
                        $rating = bcdiv($sum/$length, 1, 1);
                    }
                    unset($saved[$key]['location']['rating']);
                    $saved[$key]['location']['rating'] = $rating;
                }
            }
            $response['status'] = \Config::get('app.success_status');
            $response['message'] = 'Here is saved locations list';
            $response['data']->saved = $saved;
        }
        catch (\PDOException $e) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to get saved locations, try again';
        }
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | save requested location against user
    | @params [location_id, token]
    |----------------------------------------------------------------------
    */
    public function SubscribeLocation(Request $request) {

        $params = $request->all();
        $response = \Config::get('app.api_response');
        // required parameters
        $rules = array(
            'location_id' => 'required',
        );
        $messages = array(
            'location_id.required' => 'Please provide location id',
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }

        $data = $request->except('_token');
        // perform insertion if new record
        try {
            $is_exist = LocationSubscribe::where(['lsb_location_id' => $data['location_id'] ,'lsb_user_id' => $request->user->id])->first();

            if(!empty($is_exist)) {
                if($is_exist->lsb_status == 1) {
                    $is_exist->lsb_status = 0;
                    $result = $is_exist->save();
                    if($result) {
                        $response['status']  = \Config::get('app.success_status');
                        $response['message']  = 'You have unsubscribed location successfully';
                    }
                    else {
                        $response['status']  = \Config::get('app.failure_status');
                        $response['message']  = 'Unable to subscribe location at the moment';
                    }
                }
                else {
                    $is_exist->lsb_status = 1;
                    $result = $is_exist->save();
                    if($result) {
                        $response['status']  = \Config::get('app.success_status');
                        $response['message']  = 'You have subscribed location successfully';
                    }
                    else {
                        $response['status']  = \Config::get('app.failure_status');
                        $response['message']  = 'Unable to subscribe location at the moment';
                    }
                }
            }
            else {
                $result = LocationSubscribe::create(['lsb_location_id' => $data['location_id'], 'lsb_user_id' => $request->user->id]);
                if($result) {
                    $response['status']  = \Config::get('app.success_status');
                    $response['message']  = 'You have subscribed location successfully';
                }
                else {
                    $response['status']  = \Config::get('app.failure_status');
                    $response['message']  = 'Unable to subscribe location at the moment';
                }    
            }
            
        } catch (\PDOException $e) {
            $response['status']  = \Config::get('app.failure_status');
            $response['message']  = 'Unable to subscribe location at the moment';
        }

        $response['data']->subscribers  = Locations::where(['id' => $data['location_id']])->first()->subscriptions()->where('lsb_status',1)->count();
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | get requested location rating by user
    | @params [location_id, token]
    |----------------------------------------------------------------------
    */
    public function GetLocationSubscribers(Request $request) {
        $params = $request->all();
        $response = \Config::get('app.api_response');
        // required parameters
        $rules = array(
            'location_id' => 'required',
        );
        $messages = array(
            'location_id.required' => 'Please provide location id',
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }

        $data = $request->except('_token');
        // perform insertion if new record
        try {
            $location = Locations::where(['id' => $data['location_id'], 'status' => 1])->first();
            if(empty($location)) {
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'Requested location not found';
                return \response()->json($response);
            }

            $response['status'] = \Config::get('app.success_status');
            $response['message'] = 'Here is location rating';
            $response['data']->subscribers = $location->subscriptions()->where('lsb_status',1)->count();
        }
        catch (\PDOException $e) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to get location rating, try again';
        }
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | save requested location against user
    | @params [location_id, rating, token]
    |----------------------------------------------------------------------
    */
    public function RateLocation(Request $request) {
        $params = $request->all();
        $response = \Config::get('app.api_response');
        // required parameters
        $rules = array(
            'location_id' => 'required',
            'rating' => 'required|numeric'
        );
        $messages = array(
            'location_id.required' => 'Please provide location id',
            'rating.required' => 'Please provide rating to post',
            'rating.numeric' => 'Please provide only numbers for rating'
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }

        $data = $request->except('_token');
        // perform insertion if new record
        try {
            $is_exist = LocationRating::where(['lr_location_id' => $data['location_id'] ,'lr_user_id' => $request->user->id])->first();

            if(!empty($is_exist)) {
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'You have already rated this location';
            }
            else {
                $result = LocationRating::create(['lr_location_id' => $data['location_id'], 'lr_user_id' => $request->user->id, 'lr_rating' => $data['rating']]);
                if($result) {
                    $response['status'] = \Config::get('app.success_status');
                    $response['message'] = 'You have given rating to this location successfully';
                    $location = Locations::where(['id' => $data['location_id']])->first();
                    $response['data']->rating = ($location->rating()->avg('lr_rating')) ? $location->rating()->avg('lr_rating') : '0.0';
                }
                else {
                    $response['status'] = \Config::get('app.failure_status');
                    $response['message'] = 'Unable to rate this location at the moment';
                }
            }
        }
        catch (\PDOException $e) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to rate this location at the moment';
        }
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | get requested location rating by user
    | @params [location_id, token]
    |----------------------------------------------------------------------
    */
    public function GetLocationRating(Request $request) {
        $params = $request->all();
        $response = \Config::get('app.api_response');
        // required parameters
        $rules = array(
            'location_id' => 'required',
        );
        $messages = array(
            'location_id.required' => 'Please provide location id',
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }

        $data = $request->except('_token');
        // perform insertion if new record
        try {
            $location = Locations::where(['id' => $data['location_id'], 'status' => 1])->first();
            if(empty($location)) {
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'Requested location not found';
                return \response()->json($response);
            }

            $response['status'] = \Config::get('app.success_status');
            $response['message'] = 'Here is location rating';
            $response['data']->rating = ($location->rating()->avg('lr_rating')) ? $location->rating()->avg('lr_rating') : '0.0';
        }
        catch (\PDOException $e) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to get location rating, try again';
        }
        return \response()->json($response);
    }

    /*
    |----------------------------------------------------------------------
    | get requested location images by user
    | @params [location_id, token]
    |----------------------------------------------------------------------
    */
    public function GetLocationImages(Request $request) {
        $params = $request->all();
        $response = \Config::get('app.api_response');
        // required parameters
        $rules = array(
            'location_id' => 'required',
        );
        $messages = array(
            'location_id.required' => 'Please provide location id',
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }

        $data = $request->except('_token');
        $baseUrl = url('uploads');
        $location = Locations::where(['id' => $data['location_id']])->first();
        if(empty($location)) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Requested location not found';
            return \response()->json($response);
        }
        // perform insertion if new record
        try {
            $images = LocationImages::where(['location_id' => $data['location_id']])->selectRaw("id, CONCAT('$baseUrl', '/', location_image_url) as url, coalesce(location_caption, '') as caption")->paginate(15);

            $response['status'] = \Config::get('app.success_status');
            $response['message'] = 'Here is location images';
            $response['data']->images = $images->items();
        }
        catch (\PDOException $e) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to get location images, try again';
        }
        return \response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | upload location images
    | @params[token, photo, location_id]
    |--------------------------------------------------------------------------
    */
    public function UploadLocationImages(Request $request){
        $params = $request->all();
        $response = \Config::get('app.api_response');
        $baseUrl = url('uploads');
        // required parameters
        $rules = array(
            'location_id' => 'required',
            'photos' => 'required'
        );
        $messages = array(
            'location_id.required' => 'Please provide location id',
            'photos.required' => 'Please provide photo(s) to upload'
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }

        $response = \Config::get('app.api_response');
        $user_id = $request->user->id;
        $photos = $request->file('photos');
        $location_id = $request->get('location_id');
        $caption = ($request->get('caption') && !empty($request->get('caption'))) ? $request->get('caption') : '';
        $uploaded = 0;

        foreach ($photos as $photo) {
            $photoname = uniqid().".".$photo->getClientOriginalExtension();
            $uploadData = $photo->move('uploads', $photoname);
            if(!empty($caption)){
                $photoObj = LocationImages::create(['location_id' => $location_id, 'location_image_url' => $photoname, 'location_caption' => $caption, 'poster_id' => $user_id]);    
            }
            else {
                $photoObj = LocationImages::create(['location_id' => $location_id, 'location_image_url' => $photoname, 'poster_id' => $user_id]);
            }
            if($photoObj)
                $uploaded++;
        }
        $response['status'] = \Config::get('app.success_status');
        $response['message'] = $uploaded.' of '.count($photos).' photo(s) uploaded';
        $response['data']->images = LocationImages::where(['location_id' => $location_id])->selectRaw("id, CONCAT('$baseUrl', '/', location_image_url) as url, coalesce(location_caption, '') as caption")->orderBy('created_at', 'desc')->get();
        return \response()->json($response);
    }
    
    /*
    |----------------------------------------------------------------------
    | check-in to requested location against user
    | @params [location_id, token]
    |----------------------------------------------------------------------
    */
    public function CheckinLocation(Request $request) {
        $params = $request->all();
        $response = \Config::get('app.api_response');
        // required parameters
        $rules = array(
            'location_id' => 'required',
        );
        $messages = array(
            'location_id.required' => 'Please provide location id',
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }
        $data = $request->except('_token');

        // check if locaiton is valid
        $location = Locations::where(['id' => $data['location_id']])->first();
        if(empty($location)) {
            $response['status']  = \Config::get('app.failure_status');
            $response['message']  = 'Unable to find requested lcoation';
            return \response()->json($response);
        }

        // perform insertion if new record
        try {
            $is_exist = LocationCheckin::where(['lc_location_id' => $data['location_id'] ,'lc_user_id' => $request->user->id])->first();

            if(!empty($is_exist)) {
                $response['status']  = \Config::get('app.failure_status');
                $response['message']  = 'You have already checkin to location';
            }
            else {
                $result = LocationCheckin::create(['lc_location_id' => $data['location_id'], 'lc_user_id' => $request->user->id]);
                if($result) {
                    $response['status']  = \Config::get('app.success_status');
                    $response['message']  = 'You have checkedin to location successfully';
                }
                else {
                    $response['status']  = \Config::get('app.failure_status');
                    $response['message']  = 'Unable to checkin to location at the moment';
                }    
            }
            
        } catch (\PDOException $e) {
            $response['status']  = \Config::get('app.failure_status');
            $response['message']  = 'Unable to checkin to location at the moment';
        }
        return \response()->json($response);
    }
    
    /*
    |--------------------------------------------------------------------------
    | save requested location by user
    | @params[token, location_name, location_lat, location_lang, location_address, location_type, google_place_id, hours, location_ar_view, location_photos]
    |--------------------------------------------------------------------------
    */
    public function AddLocation(Request $request) {
        $data = $request->all();
        $response = \Config::get('app.api_response');
        $baseUrl = url('uploads');
        // required parameters
        $rules = array(
            'location_name' => 'required|min:8',
            'location_lat' => 'required',
            'location_lang' => 'required',
            'location_address' => 'required',
            'location_type' => 'required',
            'google_place_id' => 'required',
            'hours' => 'required_if:location_type,buisness'
        );
        $messages = array(
            'location_name.required' => "Please provide location name",
            'location_name.min' => "Please enter minimum 8 characters for location name",
            'location_lat.required' => "Please provide location latitude",
            'location_lang.required' => "Please provide location longitude",
            'location_address.required' => "Please provide location address",
            'location_type.required' => "Please provide location type",
            'google_place_id.required' => "Please provide location google place id",
        );
        // validate request
        $validate = $this->ValidateParams($data, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }

        $insertData = $request->except('id', 'location_photos', 'location_ar_view','hours', 'token', 'user');

        // upload ar file if requested
        if($request->has('location_ar_view')) {
            $arfile = Input::file('location_ar_view');
            if($arfile->isValid()) {
                $arname = uniqid().".".$arfile->getClientOriginalExtension();
                    $ardata = $arfile->move('uploads', $arname);
                    $insertData['location_ar_view'] = $arname;
            }
        }

        // perform insertion if new record
        if(!isset($data['id']) || (isset($data['id']) && empty($data['id']))) {
            try {
                DB::beginTransaction();
                // create location record in db
                $insertData['user_id'] = $request->user->id;
                $location = Locations::create($insertData);
                // check if location has files
                if($request->has('location_photos')) {
                    $photos = Input::file('location_photos');
                    if(count($photos) > 0) {
                        foreach ($photos as $photo) {
                            $photoname = uniqid().".".$photo->getClientOriginalExtension();
                            $uploadData = $photo->move('uploads', $photoname);
                            LocationImages::create(['location_id' => $location->id, 'location_image_url' => $photoname, 'poster_id' => $request->user->id]);
                        }
                    }
                }

                // check if timings are available in type buisness
                if($data['location_type'] == 'buisness') {
                    $hours = $data['hours'];
                    for ($i=0; $i < count($hours); $i++) { 
                        if(empty($hours[$i]['open']) || empty($hours[$i]['close'])) {
                           LocationHours::updateOrCreate(['lh_location_id' => $location->id, 'lh_day' => $hours[$i]['day'], 'lh_open' => NULL, 'lh_close' => NULL, 'lh_is_holiday' => 1]);
                        }
                        elseif(isset($hours[$i]['holiday']) && $hours[$i]['holiday'] == 1) {
                            LocationHours::updateOrCreate(['lh_location_id' => $location->id, 'lh_day' => $hours[$i]['day'], 'lh_open' => NULL, 'lh_close' => NULL, 'lh_is_holiday' => 1]);   
                        }
                        else {
                            LocationHours::updateOrCreate(['lh_location_id' => $location->id, 'lh_day' => $hours[$i]['day'], 'lh_open' => $hours[$i]['open'], 'lh_close' => $hours[$i]['close'], 'lh_is_holiday' => 0]);
                        }
                    }
                }
                DB::commit();
                if($location) {
                    $response['status'] = \Config::get('app.success_status');
                    $response['message'] = 'Location added successfully';
                }
                else {
                    $response['status'] = \Config::get('app.failure_status');
                    $response['message'] = 'Unable to add location at the moment';
                }
            } catch (\PDOException $e) {
                DB::rollBack();
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'Unable to add location at the moment';
                $response['ex'] = $e->getMessage();
            }
        }
        elseif(isset($data['id']) && !empty($data['id'])) {
            $result = Locations::where('id', $data['id'])->update($insertData);
            if($result) {
                // check if timings are available in type buisness
                if($data['location_type'] == 'buisness') {
                    $hours = $data['hours'];
                    for ($i=0; $i < count($hours); $i++) { 
                        if(empty($hours[$i]['open']) || empty($hours[$i]['close'])) {
                           LocationHours::updateOrCreate(['lh_location_id' => $data['id'], 'lh_day' => $hours[$i]['day'], 'lh_open' => NULL, 'lh_close' => NULL, 'lh_is_holiday' => 1]);
                        }
                        elseif(isset($hours[$i]['holiday']) && $hours[$i]['holiday'] == 1) {
                            LocationHours::updateOrCreate(['lh_location_id' => $data['id'], 'lh_day' => $hours[$i]['day'], 'lh_open' => NULL, 'lh_close' => NULL, 'lh_is_holiday' => 1]);   
                        }
                        else {
                            LocationHours::updateOrCreate(['lh_location_id' => $data['id'], 'lh_day' => $hours[$i]['day'], 'lh_open' => $hours[$i]['open'], 'lh_close' => $hours[$i]['close'], 'lh_is_holiday' => 0]);
                        }
                    }
                }
                $response['status'] = \Config::get('app.success_status');
                $response['message'] = 'Location updated successfully';
            }
            else {
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'Unable to update location at the moment';
            }
        }
        return \response()->json($response);
    }
    
    /*
    |----------------------------------------------------------------------
    | get user created locations for requested users
    | @params [location_id, token]
    |----------------------------------------------------------------------
    */
    /*
    |----------------------------------------------------------------------
    | get user created locations for requested users
    | @params [location_id, token]
    |----------------------------------------------------------------------
    */
     public function GetUserLocations(Request $request) {
        $params = $request->all();
        $response = \Config::get('app.api_response');
        $data = $request->except('_token');
        $baseUrl = url('uploads');
        // perform insertion if new record
        try {
            $locations = Locations::where(['user_id' => $request->user->id])
            ->selectRaw("locations.id, location_name as name, COALESCE(location_description, '') as description, location_type as type, COALESCE(location_address, '') as address, location_lat as lat, location_lang as lang, COALESCE((SELECT CASE WHEN li.location_image_url IS NOT NULL THEN CONCAT('$baseUrl','/',li.location_image_url) ELSE '' END FROM location_images as li WHERE li.location_id = locations.id ORDER BY li.created_at DESC LIMIT 1), '') as url, status, COALESCE(reject_reason, '') as reject_reason")
            ->paginate(15);
            $response['status'] = \Config::get('app.success_status');
            $response['message'] = 'Here is your locations list';
            $locations = $locations->items();
            if(count($locations) > 0) {
                foreach($locations as $key => $loc) {
                    $locations[$key]->rating = ($loc->rating()->avg('lr_rating')) ? (string)$loc->rating()->avg('lr_rating') : '0.0';
                }
            }
            $response['data']->locations = $locations;
        }
        catch (\PDOException $e) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to get your locations, try again';
            $response['ex'] = $e->getMessage();
        }
        return \response()->json($response);
    }
    
    /*
    |----------------------------------------------------------------------
    | get user created location detail for requested users
    | @params [location_id, token]
    |----------------------------------------------------------------------
    */
    public function GetUserLocationDetail(Request $request) {
        $params = $request->all();
        $response = \Config::get('app.api_response');
        $baseUrl = url('uploads');
        // required parameters
        $rules = array(
            'location_id' => 'required',
        );
        $messages = array(
            'location_id.required' => 'Please provide location id',
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }
        $data = $request->except('_token');
        // perform insertion if new record
        try {
            $location = Locations::with([
                'images' => function($query) use($baseUrl) {
        	       return $query->selectRaw("location_id, CONCAT('$baseUrl', '/', location_image_url) as url")->get();
        	    }
            ])
            ->where(['user_id' => $request->user->id, 'id' => $data['location_id']])->first();
            if(empty($location)) {
                $response['status'] = \Config::get('app.failure_status');
                $response['message'] = 'Unable to get requested location, try again';
                return \response()->json($response);
            }

            if($location->location_type == 'buisness') {
                $location->hours = $location->openinghours()->selectRaw("CONCAT(lh_day) as day, COALESCE(lh_open, '') as open, COALESCE(lh_close, '') as close, CONCAT(lh_is_holiday) as holiday")->get();
            }
            unset($location->created_at, $location->updated_at);
            $response['status'] = \Config::get('app.success_status');
            $response['message'] = 'Here is your locations list';
            $response['data']->location = $location;
        }
        catch (\PDOException $e) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to get requested location, try again';
        }
        return \response()->json($response);
    }
    
    /*
    |--------------------------------------------------------------------------
    | delete requested location by user
    | @params[token, location_id]
    |--------------------------------------------------------------------------
    */
    public function DeleteLocation(Request $request) {
        $data = $request->all();
        $response = \Config::get('app.api_response');
        $baseUrl = url('uploads');
        // required parameters
        $rules = array(
            'location_id' => 'required'
        );
        $messages = array(
            'location_id.required' => "Please provide location id"
        );
        // validate request
        $validate = $this->ValidateParams($data, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }
        $location = Locations::where(['id' => $data['location_id'], 'user_id' => $request->user->id])->first();
        if(empty($location)) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Requested location not found';
            return \response()->json($response);
        }
        $result = Locations::where('id', $data['location_id'])->delete();
        if($result) {
            try {
                LocationImages::where('location_id', $data['location_id'])->delete();
                LocationHours::where('lh_location_id', $data['location_id'])->delete();
            }
            catch(\Exception $ex) {}
            $response['status'] = \Config::get('app.success_status');
            $response['message'] = 'Location deleted successfully';
        }
        else {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Unable to delete location at the moment';
        }
        return \response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | delete requested location photos
    | @params [token, media_id[], location_id]
    |--------------------------------------------------------------------------
    */
    public function DeleteLocationPhotos(Request $request){
        $params = $request->all();
        $response = \Config::get('app.api_response');
        // required parameters
        $rules = array(
            'media_id' => 'required|array',
            'location_id' => 'required',
        );
        $messages = array(
            'media_id.required' => 'Please provide media to delete',
            'media_id.array' => 'Please provide array of media ids to delete',
            'post_id.required' => 'Please provide location against which meia to be deleted',
        );
        // validate request
        $validate = $this->ValidateParams($params, $rules, $messages);
        if($validate != 1) {
            return \response()->json($validate);
        }
        $user_id = $request->user->id;
        $data = $request->except('_token');

        $location = Locations::where(['id' => $params['location_id']])->first();
        if(empty($location)) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message']='Unable to find your requested location';
            return \response()->json($response);
        }

        // check if media exists against post
        $photo_id = $data['media_id'];
        $is_exist = LocationImages::where(['location_id' => $data['location_id']])->whereIn('id',$photo_id)->get();
        if(count($is_exist) < count($photo_id)) {
            $response['status'] = \Config::get('app.failure_status');
            $response['message'] = 'Requested media not found';
            return \response()->json($response);
        }

        $result = LocationImages::whereIn('id', $photo_id)->delete();
        if($result) {
            try {
                foreach($is_exist as $pm) {
                    $unlink = public_path().'/uploads/'.$pm->location_image_url;
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
