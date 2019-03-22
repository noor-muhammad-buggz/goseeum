<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\User\NotificationController;
use App\Models\Cities;
use App\User;
use DB;

class CityController extends Controller
{
    public function __construct() {   
    }

    /*
    |--------------------------------------------------------------------------
    | get all cities list
    |--------------------------------------------------------------------------
    */
    public function index() {
    	$cities = Cities::orderBy('created_at', 'desc')->get();
        return view('admin.cities.list', compact('cities'));
    }

    /*
    |--------------------------------------------------------------------------
    | get add city view
    |--------------------------------------------------------------------------
    */
    public function add() {
    	$type = 'add';
        return view('admin.cities.add', compact('type'));
    }

    /*
    |--------------------------------------------------------------------------
    | get edit location view
    |--------------------------------------------------------------------------
    */
    public function edit($id) {
    	$type = 'edit';
    	$city = Cities::where('city_id', $id)->first();
    	if(empty($city)) {
    		return redirect()->to('cities')->with('error', 'Requested city not found');
    	}
        return view('admin.cities.add', compact('type','city'));
    }

    /*
    |--------------------------------------------------------------------------
    | save requested location
    |--------------------------------------------------------------------------
    */
    public function save(Request $request) {
        $data = $request->except('_token');
    	// required parameters
    	$rules = array(
	        'city_name' => 'required|min:6',
            'city_lat' => 'required',
            'city_lang' => 'required',
	    );
	    $messages = array(
	        'city_name.required' => 'Please provide city name',
	        'city_name.min' => 'Please provide minimum 6 characters for city name',
	        'city_lat.required' => 'Please provide city latitude',
	        'city_lang.required' => 'Please provide city longitude',
        );
        $validator = \Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withInput($data)->withErrors($validator->errors());
        }
        
        $insertData = $request->except('_token','id','type');
        // perform insertion if new record
        if($data['type'] == 'add') {
            try {
                DB::beginTransaction();
                // create location record in db
                $city = Cities::create($insertData);
                DB::commit();
                if($city) {
                    return redirect()->to('cities')->with('message', 'City added successfully');
                }
                else {
                    return redirect()->back()->withInput($data)->with('error', 'Unable to add city at the moment');
                }
            } catch (\PDOException $e) {
                DB::rollBack();
                return redirect()->back()->withInput($data)->with('error', 'Unable to add city at the moment');
            }
        }
        elseif($data['type'] == 'edit') {
            try {
                $result = Cities::where('city_id', $data['id'])->update($insertData);
                if($result) {
                    return redirect()->to('cities')->with('message', 'City updated successfully');
                }
                else {
                    return redirect()->back()->withInput($data)->with('error', 'Unable to update city at the moment');
                }
            } catch (\PDOException $e) {
                return redirect()->back()->withInput($data)->with('error', 'Unable to update city at the moment');
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | delete requested city
    |--------------------------------------------------------------------------
    */
    public function delete($id) {
        try {
            $city = Cities::where('city_id', $id)->first();
            if(empty($city)) {
                return redirect()->to('cities')->with('error', 'Requested city not found');
            }
            
            $result = Cities::where('city_id', $id)->delete();
            if($result) {
                return redirect()->to('cities')->with('message', 'City deleted successfully');
            }
            else {
                return redirect()->to('cities')->with('error', 'Unable to delete city at the moment');
            }
        } catch (\Exception $e) {
            return redirect()->to('cities')->with('error', 'Unable to delete city at the moment');
        }
    }

}
