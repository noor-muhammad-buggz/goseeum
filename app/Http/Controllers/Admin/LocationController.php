<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\User\NotificationController;
use App\Http\Requests\LocationRequest;
use App\Models\Locations;
use App\Models\LocationImages;
use App\Models\LocationHours;
use Illuminate\Support\Facades\Input;
use App\Models\Alerts;
use App\User;
use DB;

class LocationController extends Controller
{	
    private const DAYS = ['1' => 'Mon', '2' => 'Tue', '3' => 'Wed', '4' => 'Thu', '5' => 'Fri', '6' => 'Sat', '7' => 'Sun'];
	public function __construct() {
        
    }

    /*
    |--------------------------------------------------------------------------
    | get all locations list
    |--------------------------------------------------------------------------
    */
    public function index() {
    	$locations = Locations::with('images')->orderBy('created_at', 'desc')->get();
        return view('admin.locations.locations', compact('locations'));
    }

    /*
    |--------------------------------------------------------------------------
    | get add location view
    |--------------------------------------------------------------------------
    */
    public function add() {
    	$type = 'add';
        $days = self::DAYS;
        return view('admin.locations.add-location', compact('type', 'days'));
    }

    /*
    |--------------------------------------------------------------------------
    | get edit location view
    |--------------------------------------------------------------------------
    */
    public function edit($id) {
    	$type = 'edit';
        $days = self::DAYS;
    	$location = Locations::with('images')->where('id', $id)->first();
    	if(empty($location)) {
    		return redirect()->to('locations')->with('error', 'Requested location not found');
    	}
        return view('admin.locations.add-location', compact('type','location', 'days'));
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
                $insertData['status'] = 1;
                $location = Locations::create($insertData);
                // check if location has files
                if($request->has('location_photos')) {
                    $photos = Input::file('location_photos');
                    if(count($photos) > 0) {
                        foreach ($photos as $photo) {
                            $photoname = uniqid().".".$photo->getClientOriginalExtension();
                            $uploadData = $photo->move('uploads', $photoname);
                            LocationImages::create(['location_id' => $location->id, 'location_image_url' => $photoname]);
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
                    return redirect()->to('locations')->with('message', 'Location added successfully');
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
        		return redirect()->to('locations')->with('message', 'Location updated successfully');
        	}
        	else {
        		return redirect()->back()->withInput($data)->with('error', 'Unable to update location at the moment');
        	}
        }
    }

    /*
    |--------------------------------------------------------------------------
    | approve requested location
    |--------------------------------------------------------------------------
    */
    public function approve($id) {
    	$location = Locations::where(['id' => $id])->where(function($q){
            return $q->where('status', 0)->orWhere('status', 2);
        })->first();
    	if(empty($location)) {
    		return redirect()->to('locations')->with('error', 'Requested location not found');
    	}
    	$result = Locations::where('id', $id)->update(['status' => 1, 'reject_reason' => '']);
    	if($result) {
            // get user details
            if($location->user_id != 0) {
                $user = User::where(['id' => $location->user_id])->first();
                if(!empty($user)) {
                    $payload = array(
                        "title" => "Goseeum Alert",
                        "message" => "Your location named ".$location->location_name." has been approved by admin",
                        "type" => "LocationAlert"
                    );
                    // save into database
                    Alerts::create(array('user_id' => $user->id, 'sender_id' => 0, 'type' => $payload['type'], 'payload' => json_encode($payload)));
                    // if device type is android
                    if(!empty($user->device_token) && !empty($user->device_type) && $user->device_type == 'android') {
                        // payload for notification
                        $fields = array(
                            "to" => $user->device_token,
                            "data" => $payload
                        );
                        (new NotificationController())->SendPushNotification($fields);
                    }
                }
            }
    		return redirect()->to('locations')->with('message', 'Location approved successfully');
    	}
     	else {
     		return redirect()->to('locations')->with('error', 'Unable to approve location at the moment');
     	}
    }

    /*
    |--------------------------------------------------------------------------
    | reject requested location
    |--------------------------------------------------------------------------
    */
    public function reject(Request $request) {
        $params = $request->except('_token');
    	$location = Locations::where(['id' => $params['location_id'], 'status' => 0])->first();
    	if(empty($location)) {
    		return redirect()->to('locations')->with('error', 'Requested location not found');
    	}
    	$result = Locations::where('id', $params['location_id'])->update(['status' => 2, 'reject_reason' => $params['reject_reason']]);
    	if($result) {
            // get user details
            if($location->user_id != 0) {
                $user = User::where(['id' => $location->user_id])->first();
                if(!empty($user)) {
                    $payload = array(
                        "title" => "Goseeum Alert",
                        "message" => "Your location named ".$location->location_name." has been rejected by admin",
                        "type" => "LocationAlert"
                    );
                    // save into database
                    Alerts::create(array('user_id' => $user->id, 'sender_id' => 0, 'type' => $payload['type'], 'payload' => json_encode($payload)));
                    // if device type is android
                    if(!empty($user->device_token) && !empty($user->device_type) && $user->device_type == 'android') {
                        // payload for notification
                        $fields = array(
                            "to" => $user->device_token,
                            "data" => $payload
                        );
                        (new NotificationController())->SendPushNotification($fields);
                    }
                }
            }
    		return redirect()->to('locations')->with('message', 'Location rejected successfully');
    	}
     	else {
     		return redirect()->to('locations')->with('error', 'Unable to reject location at the moment');
     	}
    }

    /*
    |--------------------------------------------------------------------------
    | delete requested location
    |--------------------------------------------------------------------------
    */
    public function delete($id) {
    	$location = Locations::where('id', $id)->first();
    	if(empty($location)) {
    		return redirect()->to('locations')->with('error', 'Requested location not found');
    	}
    	$result = Locations::where('id', $id)->delete();
    	if($result) {
            LocationImages::where('location_id', $id)->delete();
    		return redirect()->to('locations')->with('message', 'Location deleted successfully');
    	}
     	else {
     		return redirect()->to('locations')->with('error', 'Unable to delete location at the moment');
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

            $photoObj = LocationImages::create(['location_id' => $location_id, 'location_image_url' => $photoname]);
            $response[] = '<span class="file-item">
                <i class="fa fa-times inner-file-item" data-target="'.$photoObj->id.'"></i>
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
