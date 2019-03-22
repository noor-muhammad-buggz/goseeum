<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Locations;
use App\Models\Cities;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $locations = Locations::all()->count();
        $users = User::all()->count();
        $cities = Cities::all()->count();
        return view('admin.dashboard', compact('locations','users','cities'));
    }

    /*
    |--------------------------------------------------------------------------
    | get terms content from db
    |--------------------------------------------------------------------------
    */
    public function terms()
    {
        $terms = \DB::table('terms_and_privacy')->where(['type' => 'terms'])->first();
        return view('admin.settings.terms', compact('terms'));
    }

    /*
    |--------------------------------------------------------------------------
    | save terms content from db
    |--------------------------------------------------------------------------
    */
    public function saveterms(Request $request)
    {
        $data = $request->except('_token');
        // required parameters
        $rules = array(
            'title' => 'required|min:10|max:255',
            'content' => 'required|string',
        );
        $messages = array(
            'title.required' => 'Please provide terms title',
            'title.min' => 'Please provide minimum 10 characters for terms title',
            'title.max' => 'Please provide maximum 255 characters for terms title',
            'content.required' => 'Please provide terms content',
        );
        // validate request parameters
        $validator = \Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            // var_dump($validator->errors());
            return redirect()->back()->withErrors($validator->errors());
        }

        $terms = \DB::table('terms_and_privacy')->where(['type' => 'terms'])->first();
        if(empty($terms)) {
            $result = \DB::table('terms_and_privacy')->insert(['type' => 'terms', 'title' => $data['title'], 'content' => $data['content']]);
            if($result) {
                return redirect()->back()->with('message', 'Terms updated successfully');
            }
             else {
                 return redirect()->back()->with('error', 'Unable to update terms at the moment');
             }
        }
        else {
            $result = \DB::table('terms_and_privacy')->where(['type' => 'terms', 'id' => $terms->id])->update(['title' => $data['title'], 'content' => $data['content']]);
            if($result) {
                return redirect()->back()->with('message', 'Terms updated successfully');
            }
            else {
                 return redirect()->back()->with('error', 'Unable to update terms at the moment');
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | get privacy content from db
    |--------------------------------------------------------------------------
    */
    public function privacy()
    {
        $privacy = \DB::table('terms_and_privacy')->where(['type' => 'privacy'])->first();
        return view('admin.settings.privacy', compact('privacy'));
    }

    /*
    |--------------------------------------------------------------------------
    | save terms content from db
    |--------------------------------------------------------------------------
    */
    public function saveprivacy(Request $request)
    {
        $data = $request->except('_token');
        // required parameters
        $rules = array(
            'title' => 'required|min:10|max:255',
            'content' => 'required|string',
        );
        $messages = array(
            'title.required' => 'Please provide privacy title',
            'title.min' => 'Please provide minimum 10 characters for privacy title',
            'title.max' => 'Please provide maximum 255 characters for privacy title',
            'content.required' => 'Please provide privacy content',
        );
        // validate request parameters
        $validator = \Validator::make($data, $rules, $messages);
        if ($validator->fails()) {
            // var_dump($validator->errors());
            return redirect()->back()->withErrors($validator->errors());
        }

        $terms = \DB::table('terms_and_privacy')->where(['type' => 'privacy'])->first();
        if(empty($terms)) {
            $result = \DB::table('terms_and_privacy')->insert(['type' => 'privacy', 'title' => $data['title'], 'content' => $data['content']]);
            if($result) {
                return redirect()->back()->with('message', 'Privacy policy updated successfully');
            }
             else {
                 return redirect()->back()->with('error', 'Unable to update privacy policy at the moment');
             }
        }
        else {
            $result = \DB::table('terms_and_privacy')->where(['type' => 'privacy', 'id' => $terms->id])->update(['title' => $data['title'], 'content' => $data['content']]);
            if($result) {
                return redirect()->back()->with('message', 'Privacy policy updated successfully');
            }
            else {
                 return redirect()->back()->with('error', 'Unable to update privacy policy at the moment');
            }
        }
    }
}
