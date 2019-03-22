<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Locations;
use App\Models\LocationImages;

class HomeController extends Controller
{
    /*
    |----------------------------------------------------------------------
    | load landing page
    |----------------------------------------------------------------------
    */
    public function index(Request $request) {
    	return view('home');
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
        $location = Locations::with(['images'])->where(['id' => $loc_id, 'location_name' => $loc_name])->first();
        
        if(!empty($location)) {
            header('Content-Type: text/html');
            echo '<html prefix="og: http://ogp.me/ns#">
            <head>
              <meta prefix="og: http://ogp.me/ns#" property="og:title" content="'.$location->location_name.'" />
              <meta prefix="og: http://ogp.me/ns#" property="og:description" content="'.$location->location_description.'" />
              <meta prefix="og: http://ogp.me/ns#" property="og:url" content="'.url('user/locations/'.$id.'/'.$place).'" />';

            if(count($location->images) > 0)
            echo '<meta prefix="og: http://ogp.me/ns#" property="og:image" content="'.url('uploads/'.$location->images[0]->location_image_url).'" />';

            echo '</head><body></body></html>';
        }
        else {
            echo '<h4>Requested location not found</h4>';
        }
    }
}
