<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\LocationRequest;
use App\Models\LocationSubscribe;
use App\Models\LocationComments;
use App\Models\SaveLocation;
use App\Models\LocationRating;
use App\Models\Locations;
use App\Models\LocationImages;
use App\Models\LocationHours;
use App\Models\Cities;
use Illuminate\Support\Facades\Input;
use App\Models\LocationCheckin;
use Auth;
use DB;

class LocationController extends Controller
{
    private const DAYS = ['1' => 'Mon', '2' => 'Tue', '3' => 'Wed', '4' => 'Thu', '5' => 'Fri', '6' => 'Sat', '7' => 'Sun'];
    /*
    |----------------------------------------------------------------------
    | load locations search page
    |----------------------------------------------------------------------
    */
    public function index(Request $request) {
        $locations = array();
        $params = array();
        if($request->has('subject')) {
            $data = $request->all();
            $radius = '<= 15';
            $city_arr = explode('-', $data['location']);
            $city = Cities::where(['city_id' => $city_arr[1]])->first();
            $lat = $city->city_lat;
            $lang = $city->city_lang;
            // EARTH RADIUS IN MILES => 3959
            // EARTH RADIUS IN MILES => 6371.393
            $where = "ACOS(SIN(RADIANS(location_lat))*SIN(RADIANS('$lat'))+COS(RADIANS(location_lat))*COS(RADIANS('$lat'))*COS(RADIANS(location_lang)-RADIANS('$lang')))*3959 ".$radius;
            $locations = Locations::with(['images'])->whereRaw("$where")->where('status', 1);
            if(!empty($data['subject'])) {
                // $locations->where('location_name', 'like', '%'.$data['subject'].'%');
                $locations->whereRaw("MATCH(location_name) AGAINST('".$data['subject']."')");
            }
            $locations = $locations->selectRaw("*, (ACOS(SIN(RADIANS(location_lat))*SIN(RADIANS('$lat'))+COS(RADIANS(location_lat))*COS(RADIANS('$lat'))*COS(RADIANS(location_lang)-RADIANS('$lang')))*3959) AS distance")->get();
            if(count($locations) > 0) {
                $latarr = array_column($locations->toArray(), 'location_lat');
                $lngarr = array_column($locations->toArray(), 'location_lang');
                $params['minLat'] = min($latarr);
                $params['maxLat'] = max($latarr);
                $params['minLng'] = min($lngarr);
                $params['maxLng'] = max($lngarr);
                // remove carriage returns and line breaks otherwise it would break json parsing
                foreach ($locations as $key => $obj) {
                    $locations[$key]->location_description = str_replace(array("\r", "\n"), "", $locations[$key]->location_description);
                }
            }
        }
        $cities = Cities::all();
        return view('user.locations.locations', compact('cities','locations','params', 'radius'));
    }

    /*
    |----------------------------------------------------------------------
    | load locations within requested radius page
    |----------------------------------------------------------------------
    */
    public function RadiusSearch(Request $request) {
        $locations = array();
        $params = array();
        if($request->ajax()) {
            $data = $request->all();
            $radius = $data['radius'];
            $city_arr = explode('-', $data['location']);
            $city = Cities::where(['city_id' => $city_arr[1]])->first();
            $lat = $city->city_lat;
            $lang = $city->city_lang;

            $where = "ACOS(SIN(RADIANS(location_lat))*SIN(RADIANS('$lat'))+COS(RADIANS(location_lat))*COS(RADIANS('$lat'))*COS(RADIANS(location_lang)-RADIANS('$lang')))*3959 ".$radius;
            $locations = Locations::with(['images'])->whereRaw("$where")->where('status', 1);
            if(!empty($data['subject'])) {
                // $locations->where('location_name', 'like', '%'.$data['subject'].'%');
                $locations->whereRaw("MATCH(location_name) AGAINST('".$data['subject']."')");
            }
            $locations = $locations->selectRaw("*, (ACOS(SIN(RADIANS(location_lat))*SIN(RADIANS('$lat'))+COS(RADIANS(location_lat))*COS(RADIANS('$lat'))*COS(RADIANS(location_lang)-RADIANS('$lang')))*3959) AS distance")->get();
            if(count($locations) > 0) {
                $latarr = array_column($locations->toArray(), 'location_lat');
                $lngarr = array_column($locations->toArray(), 'location_lang');
                $params['minLat'] = min($latarr);
                $params['maxLat'] = max($latarr);
                $params['minLng'] = min($lngarr);
                $params['maxLng'] = max($lngarr);
                // remove carriage returns and line breaks otherwise it would break json parsing
                foreach ($locations as $key => $obj) {
                    $locations[$key]->location_description = str_replace(array("\r", "\n"), "", $locations[$key]->location_description);
                }
            }
            
            $markers = urldecode(json_encode($locations));
            $minmax = json_encode($params);
            return response()->json(array('markers' => $markers, 'minmax' => $minmax));
        }
    }

    /*
    |----------------------------------------------------------------------
    | load location nearby points
    |----------------------------------------------------------------------
    */
    public function NearBySearch(Request $request) {
        if($request->ajax()) {
            $data = $request->except('_token');
            
            // for local server
            // $client = new \GuzzleHttp\Client(['verify' => false ]);
            // $res = $client->request('GET', $data['target']);
            // $res_details = json_decode($res->getBody());
            // return response()->json(array($res_details));

            // for online server
            try{
                $ch = curl_init();
                curl_setopt($ch,CURLOPT_URL,$data['target']);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                $output = curl_exec($ch);
                curl_close($ch);
                $res_detail = json_decode($output);
                return response()->json(array($res_detail));
            }
            catch(\Exception $ex) {
                return response()->json(array());
            }
        }
    }

    /*
    |----------------------------------------------------------------------
    | load requested location detail
    |----------------------------------------------------------------------
    */
    public function LocationDetail(Request $request, $id, $place_id = '') {
        try {
            $userId = Auth::user()->id;
            $loc_arr = explode('_', $id);
            $loc_id = $loc_arr[count($loc_arr)-1];
            unset($loc_arr[count($loc_arr)-1]);
            $loc_name = implode(' ', $loc_arr);
            \DB::enableQueryLog();
            $location = Locations::with(['images','comments.commentuser', 'rating' ])->where(['id' => $loc_id, 'location_name' => $loc_name, 'status' => 1])->first();
            
            if(!empty($location)) {
                // remove carriage returns and line breaks otherwise it would break json parsing
                $location->location_description = str_replace(array("\r", "\n"), "", $location->location_description);
                $location->scenes = $location->images()->count();
                $location->reviews = $location->rating()->count();
                $location->visits = $location->checkins()->count();
                $location->is_checkin = $location->checkins()->where(['lc_status' => 1, 'lc_user_id' => $userId])->count();
                $location->rating = ($location->rating()->avg('lr_rating')) ? $location->rating()->avg('lr_rating') : '0.0';
                $location->is_rated = $location->rating()->where(['lr_user_id' => $userId])->count();
                $location->subscribers = $location->subscriptions()->where('lsb_status',1)->count();
                $location->is_subscribed = $location->subscriptions()->where(['lsb_status' => 1, 'lsb_user_id' => $userId])->count();
                $location->is_saved = $location->savedlist()->where(['ls_status' => 1, 'ls_user_id' => $userId])->count();
                $location->offdays = $location->openinghours()->where('lh_is_holiday', 1)->get();
                $location->workingdays = $location->openinghours()->where('lh_is_holiday', 0)->get();

                // weather api key
                // 8e97570eda804b97b1f320ed3e63e45d
                $wurl = 'https://api.weatherbit.io/v2.0/forecast/daily?lat='.$location->location_lat.'&lon='.$location->location_lang.'&days=5&key=8e97570eda804b97b1f320ed3e63e45d';
                // for live server
                // get 5 days weather for location
                try{
                    $wch = curl_init();
                    curl_setopt($wch,CURLOPT_URL,$wurl);
                    curl_setopt($wch,CURLOPT_RETURNTRANSFER,true);
                    curl_setopt($wch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($wch, CURLOPT_SSL_VERIFYPEER, 0);
                    $woutput = curl_exec($wch);
                    curl_close($wch);
                    $w_detail = json_decode($woutput);
                    $location->weather = $w_detail->data;
                }
                catch(\Exception $ex) {
                    $location->weather = array();
                }
            }
        }
        catch (\Exception $ex) {
            $location = array();
        }
        $days = self::DAYS;
        return view('user.locations.location-detail', compact('location','place_id', 'days'));
    }

    /*
    |----------------------------------------------------------------------
    | create requested comment against location by user
    |----------------------------------------------------------------------
    */
    public function CreateComment(Request $request) {
        if($request->ajax()) {
            $data = $request->except('_token');
            // perform insertion if new record
            if(empty($data['flag'])) {
                try {
                    $comment = LocationComments::create(['comment_body' => $data['content'] ,'comment_parent_id' => $data['target'], 'comment_user_id' => Auth::user()->id]);
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
                    $comment = LocationComments::where(['comment_parent_id' => $data['target'], 'comment_user_id' => Auth::user()->id, 'comment_id' => $data['flag']])->update(['comment_body' => $data['content']]);
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
    | get requested location comments
    |----------------------------------------------------------------------
    */
    public function GetComments(Request $request) {
        if($request->ajax()) {
            $data = $request->except('_token');
            try {
                $comments = LocationComments::with(['commentuser'])->where(['comment_parent_id' => $data['target']])->orderBy('created_at', 'desc')->get();
                if(count($comments) > 0) {
                    $html = view('user.ajax.location-comments-ajax',compact('comments'))->render();
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
    | save requested location against user
    |----------------------------------------------------------------------
    */
    public function SaveLocations(Request $request) {
        if($request->ajax()) {
            $data = $request->except('_token');
            $loc_id = $data['target'];
            // perform insertion if new record
            try {
                $is_exist = SaveLocation::where(['ls_location_id' => $loc_id ,'ls_user_id' => Auth::user()->id])->first();

                if(!empty($is_exist)) {
                    if($is_exist->ls_status == 1) {
                        $is_exist->ls_status = 0;
                        $result = $is_exist->save();
                        if($result) {
                            return \response()->json(['status' => 200, 'message' => 'Location removed from your list', 'is_saved' => 0]);
                        }
                        else {
                            return \response()->json(['status' => 500, 'message' => 'Unable to save location at the moment']);
                        }
                    }
                    else {
                        $is_exist->ls_status = 1;
                        $result = $is_exist->save();
                        if($result) {
                            return \response()->json(['status' => 200, 'message' => 'Location saved to your list', 'is_saved' => 1]);
                        }
                        else {
                            return \response()->json(['status' => 500, 'message' => 'Unable to save location at the moment']);
                        }
                    }
                }
                else {
                    $result = SaveLocation::create(['ls_location_id' => $loc_id, 'ls_user_id' => Auth::user()->id]);
                    if($result) {
                        return \response()->json(['status' => 200, 'message' => 'Location saved to your list', 'is_saved' => 1]);
                    }
                    else {
                        return \response()->json(['status' => 500, 'message' => 'Unable to save location at the moment']);
                    }    
                }
                
            } catch (\PDOException $e) {
                DB::rollBack();
                return \response()->json(['status' => 500, 'message' => 'Unable to save location at the moment']);
            }
        }
    }

    /*
    |----------------------------------------------------------------------
    | get saved locations for requested users
    |----------------------------------------------------------------------
    */
    public function GetSavedLocations(Request $request) {
        $params = $request->all();
        $data = $request->except('_token');
        $userId = Auth::user()->id;
        $baseUrl = url('uploads');
        $saved = array();
        // get saved locations against user
        try {
            $saved = SaveLocation::with([
                'location' => function($query) use($baseUrl){
                    return $query->with('rating')->select('locations.id', 'location_name', 'location_description', 'location_type', 'location_address', 'location_lat', 'location_lang', 'google_place_id')->selectRaw("COALESCE((SELECT CASE WHEN li.location_image_url IS NOT NULL THEN CONCAT('$baseUrl','/',li.location_image_url) ELSE '' END FROM location_images as li WHERE li.location_id = locations.id ORDER BY li.created_at DESC LIMIT 1), '') as url");
                }
            ])
            ->where(['ls_user_id' => $userId, 'ls_status' => 1])
            ->select('ls_location_id', 'ls_status as status')
            ->get();

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
        }
        catch (\PDOException $e) {
            $saved = array();
        }
        return view('user.locations.saved', compact('saved'));
    }

    /*
    |----------------------------------------------------------------------
    | save requested location against user
    |----------------------------------------------------------------------
    */
    public function CheckinLocation(Request $request) {
        if($request->ajax()) {
            $data = $request->except('_token');
            $userId = Auth::user()->id;
            $loc_id = $data['target'];
            // perform insertion if new record
            try {
                $is_exist = LocationCheckin::where(['lc_location_id' => $loc_id ,'lc_user_id' => $userId])->first();

                if(!empty($is_exist)) {
                    return \response()->json(['status' => 500, 'message' => 'You have already checked-in to location']);
                }
                else {
                    $result = LocationCheckin::create(['lc_location_id' => $loc_id, 'lc_user_id' => $userId]);
                    if($result) {
                        return \response()->json(['status' => 200, 'message' => 'You have successfully checked-in', 'is_saved' => 1]);
                    }
                    else {
                        return \response()->json(['status' => 500, 'message' => 'Unable to check-in to location at the moment']);
                    }    
                }
                
            } catch (\PDOException $e) {
                DB::rollBack();
                return \response()->json(['status' => 500, 'message' => 'Unable to check-in to location at the moment']);
            }
        }
    }

    /*
    |----------------------------------------------------------------------
    | save requested location against user
    |----------------------------------------------------------------------
    */
    public function SubscribeLocation(Request $request) {
        if($request->ajax()) {
            $data = $request->except('_token');
            $loc_id = $data['target'];
            // perform insertion if new record
            try {
                $is_exist = LocationSubscribe::where(['lsb_location_id' => $loc_id ,'lsb_user_id' => Auth::user()->id])->first();

                if(!empty($is_exist)) {
                    if($is_exist->lsb_status == 1) {
                        $is_exist->lsb_status = 0;
                        $result = $is_exist->save();
                        if($result) {
                            return \response()->json(['status' => 200, 'message' => 'You have unsubscribed location successfully', 'subscribe' => 0]);
                        }
                        else {
                            return \response()->json(['status' => 500, 'message' => 'Unable to subscribe location at the moment']);
                        }
                    }
                    else {
                        $is_exist->lsb_status = 1;
                        $result = $is_exist->save();
                        if($result) {
                            return \response()->json(['status' => 200, 'message' => 'You have subscribed location successfully', 'subscribe' => 1]);
                        }
                        else {
                            return \response()->json(['status' => 500, 'message' => 'Unable to subscribe location at the moment']);
                        }
                    }
                }
                else {
                    $result = LocationSubscribe::create(['lsb_location_id' => $loc_id, 'lsb_user_id' => Auth::user()->id]);
                    if($result) {
                        return \response()->json(['status' => 200, 'message' => 'You have subscribed location successfully', 'subscribe' => 1]);
                    }
                    else {
                        return \response()->json(['status' => 500, 'message' => 'Unable to subscribe location at the moment']);
                    }    
                }
                
            } catch (\PDOException $e) {
                DB::rollBack();
                return \response()->json(['status' => 500, 'message' => 'Unable to subscribe location at the moment']);
            }
        }
    }
    
    /*
    |----------------------------------------------------------------------
    | get requested location rating against user
    |----------------------------------------------------------------------
    */
    public function GetLocationRating(Request $request) {
        if($request->ajax()) {
            $data = $request->except('_token');
            $loc_id = $data['target'];
            // perform insertion if new record
            try {
                $is_exist = LocationRating::where(['lr_location_id' => $loc_id ,'lr_user_id' => Auth::user()->id])->first();

                if(!empty($is_exist)) {
                    return \response()->json(['status' => 200, 'rating' => $is_exist->lr_rating]);
                }
                else {
                    return \response()->json(['status' => 200, 'rating' => 0]);
                }
                
            } catch (\PDOException $e) {
                return \response()->json(['status' => 200, 'rating' => 0]);
            }
        }
    }
    
    /*
    |----------------------------------------------------------------------
    | save requested location rating against user
    |----------------------------------------------------------------------
    */
    public function RateLocation(Request $request) {
        if($request->ajax()) {
            $data = $request->except('_token');
            $loc_id = $data['target'];
            $rating = $data['rating'];
            // perform insertion if new record
            try {
                $is_exist = LocationRating::where(['lr_location_id' => $loc_id ,'lr_user_id' => Auth::user()->id])->first();

                if(!empty($is_exist)) {
                    return \response()->json(['status' => 500, 'message' => 'You have already given rating to location']);
                }
                else {
                    $result = LocationRating::create(['lr_location_id' => $loc_id, 'lr_user_id' => Auth::user()->id, 'lr_rating' => $rating]);
                    if($result) {
                        return \response()->json(['status' => 200, 'message' => 'You have given rating to location']);
                    }
                    else {
                        return \response()->json(['status' => 500, 'message' => 'Unable to give rating to location at the moment']);
                    }    
                }
                
            } catch (\PDOException $e) {
                DB::rollBack();
                return \response()->json(['status' => 500, 'message' => 'Unable to give rating to location at the moment']);
            }
        }
    }
    
    /*
    |--------------------------------------------------------------------------
    | upload requested location scenes by any user
    |--------------------------------------------------------------------------
    */
    public function UploadLocationScenes(Request $request){
        $response = [];
        $location_id = Input::get('target');
        $photos = Input::file('photos');
        $caption = Input::get('caption');
        $baseUrl = url('uploads');
        $uploads = 0;
        foreach($photos as $photo) {
            $photoname = uniqid().".".$photo->getClientOriginalExtension();
            $photo->move("uploads", $photoname);
            $photoObj = LocationImages::create(['location_id' => $location_id, 'location_image_url' => $photoname, 'location_caption' => $caption, 'poster_id' => Auth::user()->id]);
            if($photoObj) {
                $uploads++;
            }
        }

        if($uploads > 0) {
            $response['status'] = 200;
            $response['message'] = $uploads.' of '.count($photos).' photo(s) uploaded';
        }
        else {
            $response['status'] = 500;
            $response['message'] = $uploads.' of '.count($photos).' photo(s) uploaded';
        }

        $images = LocationImages::where(['location_id' => $location_id])->selectRaw("id, location_image_url")->orderBy('created_at', 'desc')->get();
        $response['html'] = view('user.ajax.location-images-ajax', compact('images'))->render();
        return \response()->json($response);
    }
    
    /*
    |----------------------------------------------------------------------
    | share location on social media
    |----------------------------------------------------------------------
    */
    public function LocationShare(Request $request, $id, $place) {
        $data = $request->all();
        $loc_arr = explode('_', $id);
        $loc_id = $loc_arr[count($loc_arr)-1];
        unset($loc_arr[count($loc_arr)-1]);
        $loc_name = implode(' ', $loc_arr);
        $location = Locations::with(['images'])->where(['id' => $loc_id, 'location_name' => $loc_name, 'status' => 1])->first();
        
        if(!empty($location)) {
            header('Content-Type: text/html');
            echo '<html prefix="og: http://ogp.me/ns#">
            <head>
              <meta property="og:title" content="'.$location->location_name.'" />
              <meta property="og:description" content="'.$location->location_description.'" />
              <meta property="og:url" content="'.url('user/locations/'.$id.'/'.$place).'" />';

            if(count($location->images) > 0)
            echo '<meta property="og:image" content="'.url('uploads/'.$location->images[0]->location_image_url).'" />';

            echo '</head><body></body></html>';
        }
        else {
            echo '<h4>Requested location not found</h4>';
        }
    }
    
    /*
    |----------------------------------------------------------------------
    | location create/edit/delete/listing for user
    |----------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | get all locations list
    |--------------------------------------------------------------------------
    */
    public function list() {
        $locations = Locations::with('images')->where(['user_id' => Auth::user()->id])->orderBy('created_at', 'desc')->get();
        return view('user.locations.list-locations', compact('locations'));
    }

    /*
    |--------------------------------------------------------------------------
    | get add location view
    |--------------------------------------------------------------------------
    */
    public function add() {
        $type = 'add';
        $days = self::DAYS;
        return view('user.locations.add-location', compact('type', 'days'));
    }

    /*
    |--------------------------------------------------------------------------
    | get edit location view
    |--------------------------------------------------------------------------
    */
    public function edit($id) {
        $type = 'edit';
        $days = self::DAYS;
        $location = Locations::with('images')->where(['id' => $id, 'user_id' => Auth::user()->id])->first();
        if(empty($location)) {
            return redirect()->to('user/locations')->with('error', 'Requested location not found');
        }
        return view('user.locations.add-location', compact('type','location', 'days'));
    }

    /*
    |--------------------------------------------------------------------------
    | save requested location
    |--------------------------------------------------------------------------
    */
    public function save(Request $request, LocationRequest $lrequest) {
        $data = $request->except('_token');
        $hours = $data['hours'];

        $insertData = $request->except('_token','id','type', 'location_photos', 'location_ar_view','hours');

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
        if($data['type'] == 'add') {
            try {
                DB::beginTransaction();
                // create location record in db
                $insertData['user_id'] = Auth::user()->id;
                $location = Locations::create($insertData);
                // check if location has files
                if($request->has('location_photos')) {
                    $photos = Input::file('location_photos');
                    if(count($photos) > 0) {
                        foreach ($photos as $photo) {
                            $photoname = uniqid().".".$photo->getClientOriginalExtension();
                            $uploadData = $photo->move('uploads', $photoname);
                            LocationImages::create(['location_id' => $location->id, 'location_image_url' => $photoname, 'poster_id' => Auth::user()->id]);
                        }
                    }
                }

                // check if timings are available in type buisness
                if($data['location_type'] == 'buisness') {
                    for ($i=1; $i <= 7; $i++) { 
                        if(empty($hours[$i]['open']) || empty($hours[$i]['close'])) {
                           LocationHours::updateOrCreate(['lh_location_id' => $location->id, 'lh_day' => $i], ['lh_open' => NULL, 'lh_close' => NULL, 'lh_is_holiday' => 1]);
                        }
                        elseif(isset($hours[$i]['holiday']) && $hours[$i]['holiday'] == 1) {
                            LocationHours::updateOrCreate(['lh_location_id' => $location->id, 'lh_day' => $i], ['lh_open' => NULL, 'lh_close' => NULL, 'lh_is_holiday' => 1]);   
                        }
                        else {
                            LocationHours::updateOrCreate(['lh_location_id' => $location->id, 'lh_day' => $i], ['lh_open' => $hours[$i]['open'], 'lh_close' => $hours[$i]['close'], 'lh_is_holiday' => 0]);
                        }
                    }
                }
                DB::commit();
                if($location) {
                    return redirect()->to('user/locations')->with('message', 'Location added successfully');
                }
                else {
                    return redirect()->back()->withInput($data)->with('error', 'Unable to add location at the moment');
                }
            } catch (\PDOException $e) {
                DB::rollBack();
                return redirect()->back()->withInput($data)->with('error', 'Unable to add location at the moment');
            }
        }
        elseif($data['type'] == 'edit') {
            $result = Locations::where('id', $data['id'])->update($insertData);
            if($result) {
                // check if timings are available in type buisness
                if($data['location_type'] == 'buisness') {
                    for ($i=1; $i <= 7; $i++) { 
                        if(empty($hours[$i]['open']) || empty($hours[$i]['close'])) {
                           LocationHours::updateOrCreate(['lh_location_id' => $data['id'], 'lh_day' => $i], ['lh_open' => NULL, 'lh_close' => NULL, 'lh_is_holiday' => 1]);
                        }
                        elseif(isset($hours[$i]['holiday']) && $hours[$i]['holiday'] == 1) {
                            LocationHours::updateOrCreate(['lh_location_id' => $data['id'], 'lh_day' => $i], ['lh_open' => NULL, 'lh_close' => NULL, 'lh_is_holiday' => 1]);   
                        }
                        else {
                            LocationHours::updateOrCreate(['lh_location_id' => $data['id'], 'lh_day' => $i], ['lh_open' => $hours[$i]['open'], 'lh_close' => $hours[$i]['close'], 'lh_is_holiday' => 0]);
                        }
                    }
                }
                return redirect()->to('user/locations')->with('message', 'Location updated successfully');
            }
            else {
                return redirect()->back()->withInput($data)->with('error', 'Unable to update location at the moment');
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | delete requested location
    |--------------------------------------------------------------------------
    */
    public function delete($id) {
        $location = Locations::where(['id' => $id, 'user_id' => Auth::user()->id])->first();
        if(empty($location)) {
            return redirect()->to('user/locations')->with('error', 'Requested location not found');
        }
        $result = Locations::where(['id' => $id, 'user_id' => Auth::user()->id])->delete();
        if($result) {
            LocationImages::where('location_id', $id)->delete();
            return redirect()->to('user/locations')->with('message', 'Location deleted successfully');
        }
        else {
            return redirect()->to('user/locations')->with('error', 'Unable to delete location at the moment');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | upload requested location photos
    |--------------------------------------------------------------------------
    */
    public function uploadphotos(Request $request){
        $response = [];
        $location_id = Input::get('location');
        $photos = Input::file('photos');
        foreach($photos as $photo) {
            $photoname = uniqid().".".$photo->getClientOriginalExtension();
            $photo->move("uploads", $photoname);

            $photoObj = LocationImages::create(['location_id' => $location_id, 'location_image_url' => $photoname, 'poster_id' => Auth::user()->id]);
            $response[] = '<span class="file-item">
                <i class="fa fa-times loc-image" data-target="'.$photoObj->id.'"></i>
                <img style="width: 60px;height: 60px;margin: 5px;border-radius: 3px;" src="'.asset('uploads/'.$photoname).'" />
            </span>';
        }
        return \response()->json($response);
    }

    /*
    |--------------------------------------------------------------------------
    | delete requested location photo
    |--------------------------------------------------------------------------
    */
    public function deletephotos(Request $request){
        $response = [];
        $photo_id = Input::get('target');
        $is_exist = LocationImages::where('id', $photo_id)->first();
        if(empty($is_exist)) {
            $response['status'] = 500;
            $response['message'] = 'Requested image not found';
        }
        else {
            $result = LocationImages::where('id', $photo_id)->delete();
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
    |--------------------------------------------------------------------------
    | delete requested location ar file
    |--------------------------------------------------------------------------
    */
    public function deletearfile(Request $request){
        $response = [];
        $location_id = Input::get('location');
        $is_exist = Locations::where('id', $location_id)->first();
        if(empty($is_exist)) {
            $response['status'] = 500;
            $response['message'] = 'Requested file not found';
        }
        else {
            $oldfile = $is_exist->location_ar_view;
            $is_exist->location_ar_view = NULL;
            $result = $is_exist->save();

            if($result) {
                if(!empty($oldfile) && file_exists('uploads/'.$oldfile)){
                    unlink('uploads/'.$oldfile);
                }
                $response['status'] = 200;
                $response['message'] = 'Requested file deleted successfully';
            }
            else {
                $response['status'] = 500;
                $response['message'] = 'Unable to delete requested file at the moment';
            }
        }
        return \response()->json($response);
    }

}