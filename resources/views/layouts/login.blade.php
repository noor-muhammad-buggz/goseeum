<!DOCTYPE html>
<html lang="en">
<head>
	<title>{{$pageTitle}} - {{ config('app.name', 'Goseeum') }}</title>
	<!-- Required meta tags always come first -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="x-ua-compatible" content="ie=edge">

	<!-- Main Font -->
	<script src="{{asset('js/webfontloader.min.js')}}"></script>

	<script>
		WebFont.load({
			google: {
				families: ['Roboto:300,400,500,700:latin']
			}
		});
	</script>

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" type="text/css" href="{{asset('Bootstrap/dist/css/bootstrap-reboot.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('Bootstrap/dist/css/bootstrap.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('Bootstrap/dist/css/bootstrap-grid.css')}}">
	<!-- Main Styles CSS -->
	<link rel="stylesheet" type="text/css" href="{{asset('css/main.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('css/fonts.min.css')}}">
	<link rel="shortcut icon" href="{{ asset('favicon.png') }}">
</head>

<body class="landing-page">
	<div class="content-bg-wrap">
		<div class="content-bg"></div>
	</div>
	<!-- Header Standard Landing  -->
	<div class="header--standard header--standard-landing" id="header--standard">
		<div class="container">
			<div class="header--standard-wrap">

				<a href="{{url('/')}}" class="logo">
					<div class="img-wrap">
						<img src="{{asset('img/logo.png')}}" alt="Goseeum" style="width:160px;">
					</div>
					<div class="title-block">
						<!-- <h6 class="logo-title">Goseeum</h6>
						<div class="sub-title">SOCIAL NETWORK</div> -->
					</div>
				</a>

				<a href="#" class="open-responsive-menu js-open-responsive-menu">
					<svg class="olymp-menu-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-menu-icon') }}"></use></svg>
				</a>

				<div class="nav nav-pills nav1 header-menu expanded-menu">
					<div class="mCustomScrollbar">
						<ul>
							<li class="nav-item">
								<a href="{{url('/')}}" class="nav-link">Home</a>
							</li>
							<li class="nav-item">
								<a href="#" class="nav-link">How It Works</a>
							</li>
							<li class="nav-item">
								<a href="#" class="nav-link">Features</a>
							</li>
							<li class="nav-item">
								<a href="#" class="nav-link">Blog</a>
							</li>
							<li class="nav-item">
								<a href="#" class="nav-link">Contact</a>
							</li>
							<li class="nav-item">
								<a href="{{url('login')}}" class="nav-link">Login</a>
							</li>
							<li class="nav-item">
								<a href="{{url('register')}}" class="nav-link btn-orange-rounded">Sign up</a>
							</li>
							<li class="close-responsive-menu js-close-responsive-menu">
								<svg class="olymp-close-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-close-icon"></use></svg>
							</li>
							
						</ul>
					</div>
				</div>

			</div>
		</div>
	</div>

	<!-- ... end Header Standard Landing  -->
	<div class="header-spacer--standard"></div>

	@yield('content')

	<!-- JS Scripts -->
	<script src="{{asset('js/jquery-3.2.1.js')}}"></script>
	<script src="{{asset('js/jquery.appear.js')}}"></script>
	<script src="{{asset('js/jquery.mousewheel.js')}}"></script>
	<script src="{{asset('js/perfect-scrollbar.js')}}"></script>
	<script src="{{asset('js/jquery.matchHeight.js')}}"></script>
	<script src="{{asset('js/svgxuse.js')}}"></script>
	<script src="{{asset('js/imagesloaded.pkgd.js')}}"></script>
	<script src="{{asset('js/Headroom.js')}}"></script>
	<script src="{{asset('js/velocity.js')}}"></script>
	<script src="{{asset('js/ScrollMagic.js')}}"></script>
	<script src="{{asset('js/jquery.waypoints.js')}}"></script>
	<script src="{{asset('js/jquery.countTo.js')}}"></script>
	<script src="{{asset('js/popper.min.js')}}"></script>
	<script src="{{asset('js/material.min.js')}}"></script>
	<script src="{{asset('js/bootstrap-select.js')}}"></script>
	<script src="{{asset('js/smooth-scroll.js')}}"></script>
	<script src="{{asset('js/selectize.js')}}"></script>
	<script src="{{asset('js/swiper.jquery.js')}}"></script>
	<script src="{{asset('js/moment.js')}}"></script>
	<script src="{{asset('js/daterangepicker.js')}}"></script>
	<script src="{{asset('js/simplecalendar.js')}}"></script>
	<script src="{{asset('js/fullcalendar.js')}}"></script>
	<script src="{{asset('js/isotope.pkgd.js')}}"></script>
	<script src="{{asset('js/ajax-pagination.js')}}"></script>
	<script src="{{asset('js/Chart.js')}}"></script>
	<script src="{{asset('js/chartjs-plugin-deferred.js')}}"></script>
	<script src="{{asset('js/circle-progress.js')}}"></script>
	<script src="{{asset('js/loader.js')}}"></script>
	<script src="{{asset('js/run-chart.js')}}"></script>
	<script src="{{asset('js/jquery.magnific-popup.js')}}"></script>
	<script src="{{asset('js/jquery.gifplayer.js')}}"></script>
	<script src="{{asset('js/mediaelement-and-player.js')}}"></script>
	<script src="{{asset('js/mediaelement-playlist-plugin.min.js')}}"></script>
	<script src="{{asset('js/base-init.js')}}"></script>
	<script defer src="{{asset('fonts/fontawesome-all.js')}}"></script>
	<script src="{{asset('Bootstrap/dist/js/bootstrap.bundle.js')}}"></script>
	<script type="text/javascript">
		var date_select_field = $('input[name="dob"]');
		if (date_select_field.length) {
			var start = moment().subtract(29, 'days');

			date_select_field.daterangepicker({
				startDate: start,
				autoUpdateInput: false,
				singleDatePicker: true,
				showDropdowns: true,
				locale: {
					format: 'DD/MM/YYYY'
				}
			});
			date_select_field.on('focus', function () {
				$(this).closest('.form-group').addClass('is-focused');
			});
			date_select_field.on('apply.daterangepicker', function (ev, picker) {
				$(this).val(picker.startDate.format('DD/MM/YYYY'));
				$(this).closest('.form-group').addClass('is-focused');
			});
			date_select_field.on('hide.daterangepicker', function () {
				if ('' === $(this).val()){
					$(this).closest('.form-group').removeClass('is-focused');
				}
			});

		}
	</script>
</body>
</html>