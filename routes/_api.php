<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::group(['namespace'=>'Api'] ,function() {
	// post request routes
	Route::group(['middleware'=>'checkrequest'] ,function() {
		Route::any('/user/register', 'RegisterController@RegisterUser')->name('user/register');
		Route::any('/user/login', 'RegisterController@LoginUser')->name('user/login');
		Route::any('/user/locations/search', 'LocationController@LocationSearch')->name('user/locations/search');
		Route::any('/user/locations/detail', 'LocationController@LocationDetail')->name('user/locations/detail');
		Route::any('/user/locations/comments/create', 'LocationController@CreateComment')->name('user/locations/comments/create');
		Route::any('/user/locations/comments/get', 'LocationController@GetComments')->name('user/locations/comments/get');
		Route::any('/user/locations/rating/create', 'LocationController@RateLocation')->name('user/locations/rating/create');
		Route::any('/user/locations/rating/get', 'LocationController@GetLocationRating')->name('user/locations/rating/get');
		Route::any('/user/locations/subscribers/create', 'LocationController@SubscribeLocation')->name('user/locations/subscribers/create');
		Route::any('/user/locations/subscribers/get', 'LocationController@GetLocationSubscribers')->name('user/locations/subscribers/get');
		Route::any('/user/locations/saved/create', 'LocationController@SaveLocation')->name('user/locations/saved/create');
		Route::any('/user/locations/saved/get', 'LocationController@GetSavedLocations')->name('user/locations/saved/get');

		Route::any('/user/locations/images/upload', 'LocationController@UploadLocationImages')->name('user/locations/images/upload');
		Route::any('/user/locations/images/get', 'LocationController@GetLocationImages')->name('user/locations/images/get');

		Route::any('/user/locations/save', 'LocationController@AddLocation')->name('user/locations/save');
		Route::any('/user/locations/delete', 'LocationController@DeleteLocation')->name('user/locations/delete');

		Route::any('/user/profile/cover/upload', 'ProfileController@UploadCoverPhoto')->name('user/profile/cover/upload');
		Route::any('/user/profile/cover/get', 'ProfileController@GetCoverPhoto')->name('user/profile/cover/get');
		Route::any('/user/profile/photo/upload', 'ProfileController@UploadProfilePhoto')->name('user/profile/photo/upload');
		Route::any('/user/profile/photo/get', 'ProfileController@GetProfilePhoto')->name('user/profile/photo/get');

		Route::any('/user/photos/get', 'ProfileController@GetPhotos')->name('user/photos/get');

		Route::any('/user/conversations/get', 'ChatController@GetConversations')->name('user/conversations/get');
		Route::any('/user/chat/get', 'ChatController@GetChat')->name('user/chat/get');
		Route::any('/user/chat/unread/get', 'ChatController@GetUnreadChat')->name('user/chat/unread/get');
		Route::any('/user/chat/send', 'ChatController@SendChatMessage')->name('user/chat/send');
		Route::any('/user/chat/delete/single', 'ChatController@DeleteSingleChatMessage')->name('user/chat/delete/single');
		Route::any('/user/chat/delete/all', 'ChatController@DeleteAllChatMessage')->name('user/chat/delete/all');
        
        Route::any('/user/locations/checkin/create', 'LocationController@CheckinLocation')->name('user/locations/checkin/create');
	});

	// get request routes
	Route::group(['middleware'=>'checkgetrequest'] ,function() {
		Route::any('/user/cities', 'LocationController@GetCities')->name('user/cities');
	});
});
