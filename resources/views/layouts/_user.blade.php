<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>

	<meta name="csrf-token" content="{{ csrf_token() }}">
    <script type="text/javascript">
        URL = "{{url('/')}}";
    </script>

    <title>{{$pageTitle}} - {{ config('app.name', 'Goseeum') }}</title>

	<!-- Required meta tags always come first -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="x-ua-compatible" content="ie=edge">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" type="text/css" href="{{ asset('Bootstrap/dist/css/bootstrap-reboot.css')}}">
	<link rel="stylesheet" type="text/css" href="{{ asset('Bootstrap/dist/css/bootstrap.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('Bootstrap/dist/css/bootstrap-grid.css') }}">

	<!-- Main Styles CSS -->
	<link rel="stylesheet" type="text/css" href="{{ asset('css/main.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/fonts.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap-datetimepicker.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('css/rateit.css') }}">
	<link rel="shortcut icon" href="{{ asset('favicon.png') }}">
	<!-- Main Font -->
	<script src="{{ asset('js/webfontloader.min.js') }}"></script>
	<script src="{{ asset('js/jquery-3.2.1.js') }}"></script>
	<script src="{{ asset('js/jquery.rateit.min.js') }}"></script>
	<script src="{{ asset('js/jquery.growl.js') }}" type="text/javascript"></script>
	<link href="{{ asset('css/jquery.growl.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('lightgallery/dist/css/lightGallery.css') }}" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert.css') }}">
	<script>
		WebFont.load({
			google: {
				families: ['Roboto:300,400,500,700:latin']
			}
		});
	</script>
	<link rel="stylesheet" type="text/css" href="{{ asset('css/font-awesome.min.css') }}">
</head>
<body>
<!-- Fixed Sidebar Left -->
<div class="fixed-sidebar">
	<div class="fixed-sidebar-left sidebar--small" id="sidebar-left">

		<a href="#" class="logo bg-orange">
			<div class="img-wrap">
				<img src="{{ asset('img/logo1.png') }}" alt="Goseeum">
			</div>
		</a>

		<div class="mCustomScrollbar" data-mcs-theme="dark">
			<ul class="left-menu">
				<li>
					<a href="#" class="js-sidebar-open">
						<svg class="olymp-menu-icon left-menu-icon"  data-toggle="tooltip" data-placement="right"   data-original-title="OPEN MENU"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-menu-icon') }}"></use></svg>
					</a>
				</li>
				<li>
					<a href="{{url('user/profile')}}">
						<svg class="olymp-stats-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Profile"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-stats-icon') }}"></use></svg>
					</a>
				</li>
				<li>
					<a href="{{url('user/news-feed')}}">
						<svg class="olymp-stats-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="News Feed"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-cupcake-icon') }}"></use></svg>
					</a>
				</li>
				<li>
					<a href="{{url('user/search/locations')}}">
						<svg class="olymp-stats-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Search Locations"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-magnifying-glass-icon') }}"></use></svg>
					</a>
				</li>
				<li>
					<a href="{{url('user/locations')}}">
						<svg class="olymp-stats-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Manage Locations"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-small-pin-icon') }}"></use></svg>
					</a>
				</li>
				<li>
					<a href="{{url('user/search/people')}}">
						<svg class="olymp-stats-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Search People"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-happy-face-icon') }}"></use></svg>
					</a>
				</li>
			</ul>
		</div>
	</div>

	<div class="fixed-sidebar-left sidebar--large" id="sidebar-left-1">
		<a href="{{url('profile')}}" class="logo bg-orange">
			<div class="img-wrap">
				<img src="{{ asset('img/logo.png') }}" alt="Goseeum">
			</div>
			<div class="title-block">
				<h6 class="logo-title"></h6>
			</div>
		</a>

		<div class="mCustomScrollbar" data-mcs-theme="dark">
			<ul class="left-menu">
				<li>
					<a href="#" class="js-sidebar-open">
						<svg class="olymp-close-icon left-menu-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-close-icon') }}"></use></svg>
						<span class="left-menu-title">Collapse Menu</span>
					</a>
				</li>
				<li>
					<a href="{{url('user/profile')}}">
						<svg class="olymp-stats-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Profile"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-stats-icon') }}"></use></svg>
						<span class="left-menu-title">Profile</span>
					</a>
				</li>
				<li>
					<a href="{{url('user/news-feed')}}">
						<svg class="olymp-stats-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="News Feed"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-cupcake-icon') }}"></use></svg>
						<span class="left-menu-title">News Feed</span>
					</a>
				</li>
				<li>
					<a href="{{url('user/search/locations')}}">
						<svg class="olymp-stats-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Search Locations"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-magnifying-glass-icon') }}"></use></svg>
						<span class="left-menu-title">Search Locations</span>
					</a>
				</li>
				<li>
					<a href="{{url('user/locations')}}">
						<svg class="olymp-stats-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Manage Locations"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-small-pin-icon') }}"></use></svg>
						<span class="left-menu-title">Manage Locations</span>
					</a>
				</li>
				<li>
					<a href="{{url('user/search/people')}}">
						<svg class="olymp-stats-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Search People"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-happy-face-icon') }}"></use></svg>
						<span class="left-menu-title">Search People</span>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>
<!-- ... end Fixed Sidebar Left -->


<!-- Fixed Sidebar Left -->
<div class="fixed-sidebar fixed-sidebar-responsive">

	<div class="fixed-sidebar-left sidebar--small" id="sidebar-left-responsive">
		<a href="#" class="logo js-sidebar-open bg-orange">
			<img src="{{ asset('img/logo.png') }}" alt="Goseeum">
		</a>
	</div>

	<div class="fixed-sidebar-left sidebar--large" id="sidebar-left-1-responsive">
		<a href="#" class="logo bg-orange">
			<div class="img-wrap">
				<img src="{{ asset('img/logo.png') }}" alt="Goseeum">
			</div>
			<div class="title-block">
				<h6 class="logo-title">Goseeum</h6>
			</div>
		</a>

		<div class="mCustomScrollbar" data-mcs-theme="dark">

			<div class="control-block">
				<div class="author-page author vcard inline-items">
					<div class="top-author-thumb">
						@if(!empty(Auth::user()->profilephoto()))
                            <img src="{{ asset('uploads/'.Auth::user()->profilephoto()->photo_url) }}" alt="profile">
                        @else
                            <img alt="author" src="{{ asset('img/no-profile-photo.jpg') }}" class="profile">
                        @endif
						<span class="icon-status online"></span>
					</div>
					<a href="#" class="author-name fn">
						<div class="author-title">
							{{ Auth::user()->first_name.' '.Auth::user()->last_name }} <svg class="olymp-dropdown-arrow-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-dropdown-arrow-icon') }}"></use></svg>
						</div>
						<span class="author-subtitle"></span>
					</a>
				</div>
			</div>

			<div class="ui-block-title ui-block-title-small">
				<h6 class="title">MAIN SECTIONS</h6>
			</div>

			<ul class="left-menu">
				<li>
					<a href="#" class="js-sidebar-open">
						<svg class="olymp-close-icon left-menu-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-close-icon') }}"></use></svg>
						<span class="left-menu-title">Collapse Menu</span>
					</a>
				</li>
				<li>
					<a href="{{url('user/profile')}}">
						<svg class="olymp-stats-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Profile"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-stats-icon') }}"></use></svg>
						<span class="left-menu-title">Profile</span>
					</a>
				</li>
				<li>
					<a href="{{url('user/news-feed')}}">
						<svg class="olymp-stats-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="News Feed"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-cupcake-icon') }}"></use></svg>
						<span class="left-menu-title">News Feed</span>
					</a>
				</li>
				<li>
					<a href="{{url('user/search/locations')}}">
						<svg class="olymp-stats-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Search Locations"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-magnifying-glass-icon') }}"></use></svg>
						<span class="left-menu-title">Search Locations</span>
					</a>
				</li>
				<li>
					<a href="{{url('user/locations')}}">
						<svg class="olymp-stats-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Manage Locations"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-small-pin-icon') }}"></use></svg>
						<span class="left-menu-title">Manage Locations</span>
					</a>
				</li>
				<li>
					<a href="{{url('user/search/people')}}">
						<svg class="olymp-stats-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Search People"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-happy-face-icon') }}"></use></svg>
						<span class="left-menu-title">Search People</span>
					</a>
				</li>
			</ul>

			<div class="ui-block-title ui-block-title-small">
				<h6 class="title">YOUR ACCOUNT</h6>
			</div>

			<ul class="account-settings">
				<li>
					<a href="{{url('user/settings')}}">
						<svg class="olymp-menu-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-menu-icon') }}"></use></svg>
						<span>Profile Settings</span>
					</a>
				</li>
				<li>
					<a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
						<svg class="olymp-logout-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-logout-icon') }}"></use></svg>
						<span>Log Out</span>
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>
<!-- ... end Fixed Sidebar Left -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
@csrf
</form>
<!-- Header-BP -->
<header class="header" id="site-header">
	<div class="page-title">
		<h6></h6>
	</div>
	<div class="header-content-wrapper">

		<!-- <form class="search-bar w-search notification-list friend-requests">
			<div class="form-group with-button">
				<input class="form-control" placeholder="Search here locations..." type="text">
				<button>
					<svg class="olymp-magnifying-glass-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-magnifying-glass-icon') }}"></use></svg>
				</button>
			</div>
		</form> -->

		<div class="control-block">
			<!-- start notifications icons section -->
			<div class="control-icon more has-items">
				<svg class="olymp-happy-face-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-happy-face-icon') }}"></use></svg>
				<div class="label-avatar bg-blue fr_req_count hidden"></div>

				<div class="more-dropdown more-with-triangle triangle-top-center">
					<div class="ui-block-title ui-block-title-small">
						<h6 class="title">FRIEND REQUESTS</h6>
					</div>

					<div class="mCustomScrollbar" data-mcs-theme="dark">
						<ul class="notification-list friend-requests fr_req_container">
							
						</ul>
					</div>
				</div>
			</div>
			<!-- <div class="control-icon more has-items">
				<svg class="olymp-chat---messages-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-chat---messages-icon"></use></svg>
				<div class="label-avatar bg-purple">2</div>

				<div class="more-dropdown more-with-triangle triangle-top-center">
					<div class="ui-block-title ui-block-title-small">
						<h6 class="title">Chat / Messages</h6>
						<a href="#">Mark all as read</a>
						<a href="#">Settings</a>
					</div>

					<div class="mCustomScrollbar" data-mcs-theme="dark">
						<ul class="notification-list chat-message">
							<li class="message-unread">
								<div class="author-thumb">
									<img src="img/avatar59-sm.jpg" alt="author">
								</div>
								<div class="notification-event">
									<a href="#" class="h6 notification-friend">Diana Jameson</a>
									<span class="chat-message-item">Hi James! It’s Diana, I just wanted to let you know that we have to reschedule...</span>
									<span class="notification-date"><time class="entry-date updated" datetime="2004-07-24T18:18">4 hours ago</time></span>
								</div>
								<span class="notification-icon">
									<svg class="olymp-chat---messages-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-chat---messages-icon"></use></svg>
								</span>
								<div class="more">
									<svg class="olymp-three-dots-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use></svg>
								</div>
							</li>

							<li>
								<div class="author-thumb">
									<img src="img/avatar60-sm.jpg" alt="author">
								</div>
								<div class="notification-event">
									<a href="#" class="h6 notification-friend">Jake Parker</a>
									<span class="chat-message-item">Great, I’ll see you tomorrow!.</span>
									<span class="notification-date"><time class="entry-date updated" datetime="2004-07-24T18:18">4 hours ago</time></span>
								</div>
								<span class="notification-icon">
									<svg class="olymp-chat---messages-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-chat---messages-icon"></use></svg>
								</span>

								<div class="more">
									<svg class="olymp-three-dots-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use></svg>
								</div>
							</li>
							<li>
								<div class="author-thumb">
									<img src="img/avatar61-sm.jpg" alt="author">
								</div>
								<div class="notification-event">
									<a href="#" class="h6 notification-friend">Elaine Dreyfuss</a>
									<span class="chat-message-item">We’ll have to check that at the office and see if the client is on board with...</span>
									<span class="notification-date"><time class="entry-date updated" datetime="2004-07-24T18:18">Yesterday at 9:56pm</time></span>
								</div>
									<span class="notification-icon">
										<svg class="olymp-chat---messages-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-chat---messages-icon"></use></svg>
									</span>
								<div class="more">
									<svg class="olymp-three-dots-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use></svg>
								</div>
							</li>

							<li class="chat-group">
								<div class="author-thumb">
									<img src="img/avatar11-sm.jpg" alt="author">
									<img src="img/avatar12-sm.jpg" alt="author">
									<img src="img/avatar13-sm.jpg" alt="author">
									<img src="img/avatar10-sm.jpg" alt="author">
								</div>
								<div class="notification-event">
									<a href="#" class="h6 notification-friend">You, Faye, Ed &amp; Jet +3</a>
									<span class="last-message-author">Ed:</span>
									<span class="chat-message-item">Yeah! Seems fine by me!</span>
									<span class="notification-date"><time class="entry-date updated" datetime="2004-07-24T18:18">March 16th at 10:23am</time></span>
								</div>
									<span class="notification-icon">
										<svg class="olymp-chat---messages-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-chat---messages-icon"></use></svg>
									</span>
								<div class="more">
									<svg class="olymp-three-dots-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use></svg>
								</div>
							</li>
						</ul>
					</div>

					<a href="#" class="view-all bg-purple">View All Messages</a>
				</div>
			</div>
			<div class="control-icon more has-items">
				<svg class="olymp-thunder-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-thunder-icon"></use></svg>

				<div class="label-avatar bg-primary">8</div>

				<div class="more-dropdown more-with-triangle triangle-top-center">
					<div class="ui-block-title ui-block-title-small">
						<h6 class="title">Notifications</h6>
						<a href="#">Mark all as read</a>
						<a href="#">Settings</a>
					</div>

					<div class="mCustomScrollbar" data-mcs-theme="dark">
						<ul class="notification-list">
							<li>
								<div class="author-thumb">
									<img src="img/avatar62-sm.jpg" alt="author">
								</div>
								<div class="notification-event">
									<div><a href="#" class="h6 notification-friend">Mathilda Brinker</a> commented on your new <a href="#" class="notification-link">profile status</a>.</div>
									<span class="notification-date"><time class="entry-date updated" datetime="2004-07-24T18:18">4 hours ago</time></span>
								</div>
									<span class="notification-icon">
										<svg class="olymp-comments-post-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-comments-post-icon"></use></svg>
									</span>

								<div class="more">
									<svg class="olymp-three-dots-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use></svg>
									<svg class="olymp-little-delete"><use xlink:href="svg-icons/sprites/icons.svg#olymp-little-delete"></use></svg>
								</div>
							</li>

							<li class="un-read">
								<div class="author-thumb">
									<img src="img/avatar63-sm.jpg" alt="author">
								</div>
								<div class="notification-event">
									<div>You and <a href="#" class="h6 notification-friend">Nicholas Grissom</a> just became friends. Write on <a href="#" class="notification-link">his wall</a>.</div>
									<span class="notification-date"><time class="entry-date updated" datetime="2004-07-24T18:18">9 hours ago</time></span>
								</div>
									<span class="notification-icon">
										<svg class="olymp-happy-face-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-happy-face-icon"></use></svg>
									</span>

								<div class="more">
									<svg class="olymp-three-dots-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use></svg>
									<svg class="olymp-little-delete"><use xlink:href="svg-icons/sprites/icons.svg#olymp-little-delete"></use></svg>
								</div>
							</li>

							<li class="with-comment-photo">
								<div class="author-thumb">
									<img src="img/avatar64-sm.jpg" alt="author">
								</div>
								<div class="notification-event">
									<div><a href="#" class="h6 notification-friend">Sarah Hetfield</a> commented on your <a href="#" class="notification-link">photo</a>.</div>
									<span class="notification-date"><time class="entry-date updated" datetime="2004-07-24T18:18">Yesterday at 5:32am</time></span>
								</div>
									<span class="notification-icon">
										<svg class="olymp-comments-post-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-comments-post-icon"></use></svg>
									</span>

								<div class="comment-photo">
									<img src="img/comment-photo1.jpg" alt="photo">
									<span>“She looks incredible in that outfit! We should see each...”</span>
								</div>

								<div class="more">
									<svg class="olymp-three-dots-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use></svg>
									<svg class="olymp-little-delete"><use xlink:href="svg-icons/sprites/icons.svg#olymp-little-delete"></use></svg>
								</div>
							</li>

							<li>
								<div class="author-thumb">
									<img src="img/avatar65-sm.jpg" alt="author">
								</div>
								<div class="notification-event">
									<div><a href="#" class="h6 notification-friend">Green Goo Rock</a> invited you to attend to his event Goo in <a href="#" class="notification-link">Gotham Bar</a>.</div>
									<span class="notification-date"><time class="entry-date updated" datetime="2004-07-24T18:18">March 5th at 6:43pm</time></span>
								</div>
									<span class="notification-icon">
										<svg class="olymp-happy-face-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-happy-face-icon"></use></svg>
									</span>

								<div class="more">
									<svg class="olymp-three-dots-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use></svg>
									<svg class="olymp-little-delete"><use xlink:href="svg-icons/sprites/icons.svg#olymp-little-delete"></use></svg>
								</div>
							</li>

							<li>
								<div class="author-thumb">
									<img src="img/avatar66-sm.jpg" alt="author">
								</div>
								<div class="notification-event">
									<div><a href="#" class="h6 notification-friend">James Summers</a> commented on your new <a href="#" class="notification-link">profile status</a>.</div>
									<span class="notification-date"><time class="entry-date updated" datetime="2004-07-24T18:18">March 2nd at 8:29pm</time></span>
								</div>
									<span class="notification-icon">
										<svg class="olymp-heart-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-heart-icon"></use></svg>
									</span>

								<div class="more">
									<svg class="olymp-three-dots-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use></svg>
									<svg class="olymp-little-delete"><use xlink:href="svg-icons/sprites/icons.svg#olymp-little-delete"></use></svg>
								</div>
							</li>
						</ul>
					</div>

					<a href="#" class="view-all bg-primary">View All Notifications</a>
				</div>
			</div> -->
			<!-- end notifications icons section -->
			<div class="author-page author vcard inline-items more">
				<div class="top-author-thumb">
					@if(!empty(Auth::user()->profilephoto()))
                        <img src="{{ asset('uploads/'.Auth::user()->profilephoto()->photo_url) }}" alt="profile">
                    @else
                        <img alt="author" src="{{ asset('img/no-profile-photo.jpg') }}" class="profile">
                    @endif
					<span class="icon-status online"></span>
					<div class="more-dropdown more-with-triangle">
						<div class="mCustomScrollbar" data-mcs-theme="dark">
							<div class="ui-block-title ui-block-title-small">
								<h6 class="title">Your Account</h6>
							</div>

							<ul class="account-settings">
								<li>
									<a href="{{url('user/profile-settings')}}">
										<svg class="olymp-menu-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-menu-icon') }}"></use></svg>

										<span>Profile Settings</span>
									</a>
								</li>
								<li>
									<a href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
										<svg class="olymp-logout-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-logout-icon') }}"></use></svg>

										<span>Log Out</span>
									</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
				<a href="#" class="author-name fn">
					<div class="author-title">
						{{ Auth::user()->first_name.' '.Auth::user()->last_name }} <svg class="olymp-dropdown-arrow-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-dropdown-arrow-icon') }}"></use></svg>
					</div>
					<span class="author-subtitle"></span>
				</a>
			</div>

		</div>
	</div>
</header>
<!-- ... end Header-BP -->

<!-- Responsive Header-BP -->
<header class="header header-responsive" id="site-header-responsive">
	<div class="header-content-wrapper">
		<!-- <form class="search-bar w-search notification-list friend-requests">
			<div class="form-group with-button">
				<input class="form-control" placeholder="Search here locations..." type="text">
				<button>
					<svg class="olymp-magnifying-glass-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-magnifying-glass-icon') }}"></use></svg>
				</button>
			</div>
		</form> -->
	</div>
</header>
<!-- ... end Responsive Header-BP -->
<div class="header-spacer header-spacer-small"></div>

@yield('content')

<!-- JS Scripts -->
<script src="{{ asset('lightgallery/dist/js/lightgallery.min.js') }}"></script>
<!-- lightgallery plugins -->
<script src="{{ asset('lightgallery/modules/lg-thumbnail.min.js') }}"></script>
<script src="{{ asset('lightgallery/modules/lg-fullscreen.min.js') }}"></script>
<script src="{{ asset('js/jquery.appear.js') }}"></script>
<script src="{{ asset('js/jquery.mousewheel.js') }}"></script>
<script src="{{ asset('js/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('js/jquery.matchHeight.js') }}"></script>
<script src="{{ asset('js/svgxuse.js') }}"></script>
<script src="{{ asset('js/imagesloaded.pkgd.js') }}"></script>
<script src="{{ asset('js/Headroom.js') }}"></script>
<script src="{{ asset('js/velocity.js') }}"></script>
<script src="{{ asset('js/ScrollMagic.js') }}"></script>
<script src="{{ asset('js/jquery.waypoints.js') }}"></script>
<script src="{{ asset('js/jquery.countTo.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/material.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-select.js') }}"></script>
<script src="{{ asset('js/smooth-scroll.js') }}"></script>
<script src="{{ asset('js/selectize.js') }}"></script>
<script src="{{ asset('js/swiper.jquery.js') }}"></script>
<script src="{{ asset('js/moment.js') }}"></script>
<script src="{{ asset('js/daterangepicker.js') }}"></script>
<script src="{{ asset('js/bootstrap-datetimepicker.js') }}"></script>
<script src="{{ asset('js/simplecalendar.js') }}"></script>
<script src="{{ asset('js/fullcalendar.js') }}"></script>
<script src="{{ asset('js/isotope.pkgd.js') }}"></script>
<script src="{{ asset('js/ajax-pagination.js') }}"></script>
<script src="{{ asset('js/Chart.js') }}"></script>
<script src="{{ asset('js/chartjs-plugin-deferred.js') }}"></script>
<script src="{{ asset('js/circle-progress.js') }}"></script>
<script src="{{ asset('js/loader.js') }}"></script>
<script src="{{ asset('js/run-chart.js') }}"></script>
<script src="{{ asset('js/jquery.magnific-popup.js') }}"></script>
<script src="{{ asset('js/jquery.gifplayer.js') }}"></script>
<script src="{{ asset('js/mediaelement-and-player.js') }}"></script>
<script src="{{ asset('js/mediaelement-playlist-plugin.min.js') }}"></script>

<script src="{{ asset('js/base-init.js') }}"></script>
<script defer src="{{ asset('fonts/fontawesome-all.js') }}"></script>

<script src="{{ asset('Bootstrap/dist/js/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('js/jquery.blockUI.min.js') }}"></script>
<script src="{{ asset('js/sweetalert.min.js')}}" type="text/javascript"></script>

@yield('scripts')

<script type="text/javascript">
	var notiInterval;
    // function to block a specific section
    function blockSection(section) {
        $(section).block({
            message: "<img src='{{asset('img/loader.gif')}}' style='background: #fff;padding: 25px;border-radius: 10px;'>",
            css: {
                border:     'none',
                backgroundColor:'transparent'
            }
        });
    }
    // function to unblock specific section
    function unblockSection(section) {
        $(section).unblock();
    }

	// check for new notifications
	$(document).ready(function(){
		GetFriendRequestNotifications();
		// check if accept request button clicked
		$(document).on('click touchstart', '.acc_fr_req', function(){
			blockSection('.fr_req_container');
			var _self = $(this);
			var formData = new FormData();
			formData.append('target', _self.attr('data-target'));
			$.ajax({
				url: URL + "/user/accept/friend/request",
				type: "POST",
				data: formData,
				contentType: false,
				cache: false,
				processData: false,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				success: function (data) {
					unblockSection('.fr_req_container');
					if(data.status == 200) {
						$.growl.notice({title: "Success", message: data.message});
						$('.fr_req_items_'+_self.attr('data-target')).slideUp('slow');
					}
					else {
						$.growl.error({title: "Error", message: data.message});
					}
				}
			});
		});
		// check if decline request button clicked
		$(document).on('click touchstart', '.dec_fr_req', function(){
			blockSection('.fr_req_container');
			var _self = $(this);
			var formData = new FormData();
			formData.append('target', _self.attr('data-target'));
			$.ajax({
				url: URL + "/user/decline/friend/request",
				type: "POST",
				data: formData,
				contentType: false,
				cache: false,
				processData: false,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				success: function (data) {
					unblockSection('.fr_req_container');
					if(data.status == 200) {
						$.growl.notice({title: "Success", message: data.message});
						$('.fr_req_items_'+_self.attr('data-target')).slideUp('slow');
					}
					else {
						$.growl.error({title: "Error", message: data.message});
					}
				}
			});
		});
	});
	/*
    |----------------------------------------------------------------------
    | check for notificaitons if recieved any
    |----------------------------------------------------------------------
    */
    function CheckForNotifications() {
        $.ajax({
            url: URL + "/user/check/notifications",
            type: "GET",
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                if(data.status == 200) {
                    if(data.friend_request == 1) {
						if(notiInterval) {
							clearInterval(notiInterval);
						}
						GetUnreadFriendRequestNotifications();
					}
                }
            }
        });
    }
	/*
    |----------------------------------------------------------------------
    | get unread notificaitons
    |----------------------------------------------------------------------
    */
    function GetUnreadFriendRequestNotifications() {
        var formData = new FormData();
		formData.append('target', 1);
        $.ajax({
            url: URL + "/user/get/unread/notifications",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if(data.status == 200) {
					$('.last_fr_rec').remove();
					if(data.count > 0) {
						$('.fr_req_count').removeClass('hidden');
					}
					$('.fr_req_count').text(parseInt($('.fr_req_count').text())+data.count);
                    $('.fr_req_container').prepend(data.html);
                    $('.mCustomScrollbar').animate({scrollTop: $('.fr_req_container').prop('scrollHeight')}, 'slow');
                }
				notiInterval = setInterval(CheckForNotifications, 5000);
            }
        });
    }
	/*
    |----------------------------------------------------------------------
    | get notifications on page load
    |----------------------------------------------------------------------
    */
    function GetFriendRequestNotifications() {
        var formData = new FormData();
		formData.append('target', 1);
        $.ajax({
            url: URL + "/user/get/notifications",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if(data.status == 200) {
					if(data.count > 0) {
						$('.fr_req_count').removeClass('hidden');
					}
					$('.fr_req_count').text(data.count);
                    $('.fr_req_container').append(data.html);
                    $('.mCustomScrollbar').animate({scrollTop: $('.fr_req_container').prop('scrollHeight')}, 'slow');
                }
				if(notiInterval) {
					clearInterval(notiInterval);
				}
				notiInterval = setInterval(CheckForNotifications, 5000);
            }
        });
    }
</script>

</body>
</html>
