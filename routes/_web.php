<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home');

// Route::get('/', function(){
// 	return redirect()->to('login');
// });

Auth::routes();

Route::group(['namespace' => 'Admin','middleware' => ['auth', 'role:admin']], function () {  
	Route::get('/dashboard', 'HomeController@index')->name('dashboard');
	Route::get('/locations', 'LocationController@index')->name('locations');
	Route::get('/locations/add', 'LocationController@add')->name('locations/add');
	Route::get('/locations/edit/{id}', 'LocationController@edit')->name('locations/edit');
	Route::get('/locations/delete/{id}', 'LocationController@delete')->name('locations/delete');
	Route::post('/locations/save', 'LocationController@save')->name('locations/save');
	Route::post('/locations/upload/photos', 'LocationController@uploadphotos')->name('locations/uploadphotos');
	Route::post('/locations/delete/photos', 'LocationController@deletephotos')->name('locations/deletephotos');
	Route::post('/locations/delete/arfile', 'LocationController@deletearfile')->name('locations/deletearfile');
});

Route::group(['namespace' => 'User', 'prefix' => 'user', 'middleware' => ['auth', 'role:user']], function () {
	Route::get('profile', 'ProfileController@index')->name('profile');
	Route::get('profile-settings', 'ProfileController@ProfileSettings')->name('profile-settings');
	Route::post('profile-settings', 'ProfileController@PostProfileSettings')->name('post-profile-settings');
	Route::post('profile/photo/upload', 'ProfileController@UploadProfilePhoto')->name('profile/photo/upload');
	Route::get('change-password', 'ProfileController@ChangePassword')->name('change-password');
	Route::post('change-password', 'ProfileController@PostChangePassword')->name('post-change-password');

	Route::get('chat-messages', 'ChatController@index')->name('chat-messages');
	Route::post('chat/get', 'ChatController@GetChat')->name('chat/get');
	Route::post('chat/get/unread', 'ChatController@GetUnreadChat')->name('chat/get/unread');
	Route::post('chat/send', 'ChatController@SendChatMessage')->name('chat/send');

	Route::get('news-feed', 'NewsfeedController@index')->name('news-feed');
	Route::post('posts/create', 'NewsfeedController@CreatePost')->name('create-post');
	Route::post('posts/update', 'NewsfeedController@UpdatePost')->name('update-post');
	Route::post('posts/get', 'NewsfeedController@GetPosts')->name('get-posts');
	Route::post('post/get/{id?}', 'NewsfeedController@GetPost')->name('get-post');
	Route::post('post/photos/create', 'NewsfeedController@CreatePostPhotos')->name('create-photos');
	Route::post('comments/create', 'NewsfeedController@CreateComment')->name('create-comment');
	Route::post('comments/delete', 'NewsfeedController@DeleteComment')->name('delete-comment');
	Route::post('comments/get', 'NewsfeedController@GetComments')->name('get-comments');
	Route::post('likes/create', 'NewsfeedController@CreateLike')->name('create-like');
	Route::post('post/delete/photos', 'NewsfeedController@DeletePostPhotos')->name('post/delete/photos');
	Route::post('post/delete', 'NewsfeedController@DeletePost')->name('post/delete');
	Route::get('search/locations', 'LocationController@index')->name('search/locations');
	Route::post('search/nearby/locations', 'LocationController@NearBySearch')->name('search/nearby/locations');
	Route::post('radiussearch/locations', 'LocationController@RadiusSearch')->name('radiussearch/locations');

	Route::get('locations/{id}/{place?}', 'LocationController@LocationDetail')->name('search/locations/detail');
	Route::post('locations/comments/create', 'LocationController@CreateComment')->name('locations/comments/create');
	Route::post('locations/comments/get', 'LocationController@GetComments')->name('locations/comments/get');
	Route::post('locations/saved/create', 'LocationController@SaveLocations')->name('locations/saved/create');
	Route::post('locations/rating/save', 'LocationController@RateLocation')->name('locations/rating/save');
	Route::post('locations/rating/get', 'LocationController@GetLocationRating')->name('locations/rating/get');
	Route::post('locations/subscribe/save', 'LocationController@SubscribeLocation')->name('locations/subscribe/save');
	Route::post('locations/scenes/upload', 'LocationController@UploadLocationScenes')->name('locations/scenes/upload');
	Route::get('locations/share/social/{id}/{place?}', 'LocationController@LocationShare')->name('locations/share/social');

	Route::get('search/people', 'FriendsController@SearchPeople')->name('search/people');
	Route::post('send/friend/request', 'FriendsController@SendFriendRequest')->name('send/friend/request');
	Route::post('accept/friend/request', 'FriendsController@AcceptFriendRequest')->name('accept/friend/request');
	Route::post('decline/friend/request', 'FriendsController@DeclineFriendRequest')->name('decline/friend/request');
	Route::get('check/notifications', 'NotificationController@CheckNotifications')->name('check/notifications');
	Route::post('get/unread/notifications', 'NotificationController@GetUnreadNotifications')->name('get/unread/notifications');
	Route::post('get/notifications', 'NotificationController@GetNotifications')->name('get/notifications');

	// locations crud routes for user
	Route::get('/locations', 'LocationController@list')->name('list/locations');
	Route::get('/location/add', 'LocationController@add')->name('add/location');
	Route::get('/location/edit/{id}', 'LocationController@edit')->name('edit/location');
	Route::get('/location/delete/{id}', 'LocationController@delete')->name('delete/location');
	Route::post('/location/save', 'LocationController@save')->name('save/location');
	Route::post('/location/upload/photos', 'LocationController@uploadphotos')->name('uploadphotos/location');
	Route::post('/location/delete/photos', 'LocationController@deletephotos')->name('deletephotos/location');
	Route::post('/location/delete/arfile', 'LocationController@deletearfile')->name('deletearfile/location');
});