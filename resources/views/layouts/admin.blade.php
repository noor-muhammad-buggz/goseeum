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
	<link rel="stylesheet" type="text/css" href="{{ asset('css/sweetalert.css') }}">
	<!-- bootstrap wysihtml5 - text editor -->
	<!-- <link rel="stylesheet" href="{{ asset('wysihtml5/bootstrap3-wysihtml5.min.css') }}"> -->
	<link rel="shortcut icon" href="{{ asset('favicon.png') }}">
	<!-- Main Font -->
	<script src="{{ asset('js/webfontloader.min.js') }}"></script>
	<script>
		WebFont.load({
			google: {
				families: ['Roboto:300,400,500,700:latin']
			}
		});
	</script>

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
					<a href="{{url('dashboard')}}">
						<svg class="olymp-stats-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Dashboard"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-stats-icon') }}"></use></svg>
					</a>
				</li>
				<li>
					<a href="{{url('locations')}}">
						<svg class="olymp-newsfeed-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Locations"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-stats-arrow') }}"></use></svg>
					</a>
				</li>
				<li>
					<a href="{{url('users')}}">
						<svg class="olymp-happy-face-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Users"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-happy-face-icon') }}"></use></svg>
					</a>
				</li>
				<li>
					<a href="{{url('cities')}}">
						<svg class="olymp-stats-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Cities"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-stats-icon') }}"></use></svg>
					</a>
				</li>
				
				<li>
					<a href="{{url('terms')}}">
						<svg class="olymp-settings-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Terms And Conditions"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-settings-icon') }}"></use></svg>
					</a>
				</li>
				<li>
					<a href="{{url('privacy')}}">
						<svg class="olymp-settings-v2-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Privacy Policy"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-settings-v2-icon') }}"></use></svg>
					</a>
				</li>

			</ul>
		</div>
	</div>

	<div class="fixed-sidebar-left sidebar--large" id="sidebar-left-1">
		<a href="{{url('dashboard')}}" class="logo bg-orange">
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
					<a href="{{url('dashboard')}}">
						<svg class="olymp-stats-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Dashboard"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-stats-icon') }}"></use></svg>
						<span class="left-menu-title">Dashboard</span>
					</a>
				</li>
				<li>
					<a href="{{url('locations')}}">
						<svg class="olymp-newsfeed-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Locations"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-stats-arrow') }}"></use></svg>
						<span class="left-menu-title">Locations</span>
					</a>
				</li>
				<li>
					<a href="{{url('users')}}">
						<svg class="olymp-happy-face-icon left-menu-icon" data-toggle="tooltip" data-placement="right" data-original-title="Users"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-happy-face-icon') }}"></use></svg>
						<span class="left-menu-title">Users</span>
					</a>
				</li>
				<li>
					<a href="{{url('cities')}}">
						<svg class="olymp-stats-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Cities"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-stats-icon') }}"></use></svg>
						<span class="left-menu-title">Cities</span>
					</a>
				</li>
				<li>
					<a href="{{url('terms')}}">
						<svg class="olymp-settings-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Terms And Conditions"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-settings-icon') }}"></use></svg>
						<span class="left-menu-title">Terms And Conditions</span>
					</a>
				</li>
				<li>
					<a href="{{url('privacy')}}">
						<svg class="olymp-settings-v2-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Privacy Policy"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-settings-v2-icon') }}"></use></svg>
						<span class="left-menu-title">Privacy Policy</span>
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
					<div class="author-thumb">
						<img alt="author" src="{{ asset('img/author-page.jpg') }}" class="avatar">
						<span class="icon-status online"></span>
					</div>
					<a href="#" class="author-name fn">
						<div class="author-title">
							{{ Auth::user()->first_name }} <svg class="olymp-dropdown-arrow-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-dropdown-arrow-icon') }}"></use></svg>
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
					<a href="{{url('Dashboard')}}">
						<svg class="olymp-stats-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Dashboard"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-stats-icon') }}"></use></svg>
						<span class="left-menu-title">Dashboard</span>
					</a>
				</li>
				<li>
					<a href="{{url('locations')}}">
						<svg class="olymp-newsfeed-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Locations"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-stats-arrow') }}"></use></svg>
						<span class="left-menu-title">Locations</span>
					</a>
				</li>
				<li>
					<a href="{{url('users')}}">
						<svg class="olymp-happy-face-icon left-menu-icon" data-toggle="tooltip" data-placement="right" data-original-title="Users"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-happy-face-icon') }}"></use></svg>
						<span class="left-menu-title">Users</span>
					</a>
				</li>
				<li>
					<a href="{{url('cities')}}">
						<svg class="olymp-stats-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Cities"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-stats-icons') }}"></use></svg>
						<span class="left-menu-title">Cities</span>
					</a>
				</li>
				<li>
					<a href="{{url('terms')}}">
						<svg class="olymp-settings-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Terms And Conditions"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-settings-icon') }}"></use></svg>
					</a>
				</li>
				<li>
					<a href="{{url('privacy')}}">
						<svg class="olymp-settings-v2-icon left-menu-icon" data-toggle="tooltip" data-placement="right"   data-original-title="Privacy Policy"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-settings-v2-icon') }}"></use></svg>
					</a>
				</li>

			</ul>

			<div class="ui-block-title ui-block-title-small">
				<h6 class="title">YOUR ACCOUNT</h6>
			</div>

			<ul class="account-settings">
				<li>
					<a href="#">

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
		<div class="control-block">
			<div class="author-page author vcard inline-items more">
				<div class="author-thumb">
					<img alt="author" src="{{ asset('img/author-page.jpg') }}" class="avatar">
					<span class="icon-status online"></span>
					<div class="more-dropdown more-with-triangle">
						<div class="mCustomScrollbar" data-mcs-theme="dark">
							<div class="ui-block-title ui-block-title-small">
								<h6 class="title">Your Account</h6>
							</div>

							<ul class="account-settings">
								<li>
									<a href="#">
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
						{{ Auth::user()->first_name }} <svg class="olymp-dropdown-arrow-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-dropdown-arrow-icon') }}"></use></svg>
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
	<div class="header-content-wrapper"></div>
</header>
<!-- ... end Responsive Header-BP -->
<div class="header-spacer header-spacer-small"></div>

@yield('content')

<!-- JS Scripts -->
<script src="{{ asset('js/jquery-3.2.1.js') }}"></script>
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
<!-- <script src="{{ asset('wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script> -->

<script src="{{ asset('js/base-init.js') }}"></script>
<script defer src="{{ asset('fonts/fontawesome-all.js') }}"></script>
<script src="{{ asset('js/sweetalert.min.js')}}" type="text/javascript"></script>
<script src="{{ asset('Bootstrap/dist/js/bootstrap.bundle.js') }}"></script>

@yield('scripts')

</body>
</html>
