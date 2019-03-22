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
		Route::any('/user/locations/get', 'LocationController@GetUserLocations')->name('user/locations/get');
		Route::any('/user/locations/get/detail', 'LocationController@GetUserLocationDetail')->name('user/locations/get/detail');
		Route::any('/user/locations/delete', 'LocationController@DeleteLocation')->name('user/locations/delete');

		Route::any('/user/profile/cover/upload', 'ProfileController@UploadCoverPhoto')->name('user/profile/cover/upload');
		Route::any('/user/profile/cover/get', 'ProfileController@GetCoverPhoto')->name('user/profile/cover/get');
		Route::any('/user/profile/photo/upload', 'ProfileController@UploadProfilePhoto')->name('user/profile/photo/upload');
		Route::any('/user/profile/photo/get', 'ProfileController@GetProfilePhoto')->name('user/profile/photo/get');

		Route::any('/user/photos/get', 'ProfileController@GetPhotos')->name('user/photos/get');
		Route::any('/user/photos/profile/get', 'ProfileController@GetProfilePhotos')->name('user/photos/profile/get');
		Route::any('/user/photos/covers/get', 'ProfileController@GetCoverPhotos')->name('user/photos/covers/get');
		Route::any('/user/photos/posts/get', 'ProfileController@GetPostPhotos')->name('user/photos/posts/get');
		Route::any('/user/photos/locations/get', 'ProfileController@GetLocationPhotos')->name('user/photos/locations/get');
		
		Route::any('/user/profile/get', 'ProfileController@GetProfile')->name('user/profile/get');
		Route::any('/user/profile/update', 'ProfileController@UpdateProfile')->name('user/profile/update');
		Route::any('/user/password/change', 'ProfileController@UpdatePassword')->name('user/password/change');

		Route::any('/user/conversations/get', 'ChatController@GetConversations')->name('user/conversations/get');
		Route::any('/user/chat/get', 'ChatController@GetChat')->name('user/chat/get');
		Route::any('/user/chat/unread/get', 'ChatController@GetUnreadChat')->name('user/chat/unread/get');
		Route::any('/user/chat/send', 'ChatController@SendChatMessage')->name('user/chat/send');
		Route::any('/user/chat/delete/single', 'ChatController@DeleteSingleChatMessage')->name('user/chat/delete/single');
		Route::any('/user/chat/delete/all', 'ChatController@DeleteAllChatMessage')->name('user/chat/delete/all');

		Route::any('/user/locations/checkin/create', 'LocationController@CheckinLocation')->name('user/locations/checkin/create');
		
		Route::any('/user/posts/create', 'NewsFeedController@CreatePost')->name('user/posts/create');
		Route::any('/user/posts/update', 'NewsFeedController@UpdatePost')->name('user/posts/update');
		Route::any('/user/posts/delete', 'NewsFeedController@DeletePost')->name('user/posts/delete');
		Route::any('/user/posts/media/delete', 'NewsFeedController@DeletePostPhotos')->name('user/posts/media/delete');
		Route::any('/user/posts/get/single', 'NewsFeedController@GetSinglePost')->name('user/posts/get/single');
		Route::any('/user/posts/like/create', 'NewsFeedController@CreateLike')->name('user/posts/like/create');
		Route::any('/user/posts/comment/create', 'NewsFeedController@CreateComment')->name('user/posts/comment/create');
		
		Route::any('/user/posts/get/all', 'NewsFeedController@GetPosts')->name('user/posts/get/all');
		Route::any('/user/posts/comments/get/all', 'NewsFeedController@GetComments')->name('user/posts/comments/get/all');
		Route::any('/user/posts/comments/delete', 'NewsFeedController@DeleteComment')->name('user/posts/comments/delete');
		
		Route::any('/get/privacy', 'ProfileController@GetPrivacy')->name('get/privacy');
		Route::any('/get/terms', 'ProfileController@GetTerms')->name('get/terms');
		
		Route::any('/user/logout', 'RegisterController@UserLogout')->name('user/logout');
		Route::any('/user/online', 'RegisterController@UserOnline')->name('user/online');
		
		Route::any('/user/get/notifications', 'ProfileController@GetNotifications');
		Route::any('/user/mark/notification/read', 'ProfileController@MarkNotificationRead');
		Route::any('/user/delete/notification', 'ProfileController@DeleteNotification');

		Route::any('/user/search/people', 'FriendsController@SearchPeople');
		Route::any('/user/send/request', 'FriendsController@SendFriendRequest');
		Route::any('/user/accept/request', 'FriendsController@AcceptFriendRequest');
		Route::any('/user/decline/request', 'FriendsController@DeclineFriendRequest');
		Route::any('/user/cancel/request', 'FriendsController@CancelFriendRequest');
		Route::any('/user/friend/requests', 'FriendsController@GetFriendRequests');
		Route::any('/user/friend/list', 'FriendsController@GetFriendsList');

		Route::any('/user/locations/media/delete', 'LocationController@DeleteLocationPhotos')->name('user/locations/media/delete');
	});

	// get request routes
	Route::group(['middleware'=>'checkgetrequest'] ,function() {
		Route::any('/user/cities', 'LocationController@GetCities')->name('user/cities');
	});
});
