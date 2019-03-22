@php
$pageTitle = 'Home';
@endphp
@extends('layouts.app')
@section('content')
{{-- home search form --}}
<section class="home-search-section mb60">
	<div class="home-search-bg">
        <div class="home-search-content-bg"></div>
    </div>
	<div class="container-fluid">
		<div class="row display-flex">
			<div class="col-xl-9 m-auto col-lg-9 col-md-12 col-sm-12 col-xs-12">
				<div class="landing-content">
					<h3 class="mb10">Find Nearby Attractions</h3>
					<p class="mb0">Explore top-rated attrections, activities and more</p>
				</div>

				<div class="home-search-form">
					<form class="form-inline">
						<div class="form-group">
							<div class="input-group">
								<input class="form-control col-md-4" type="text" placeholder="What are you looking for?">
								<select class="form-control">
									<option value="">Location</option>
									<option value="">Lahore</option>
								</select>
								<select class="form-control">
									<option value="">All categories</option>s
								</select>

								<button type="submit" class="btn btn-orange">Search</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</section>
{{-- end home search form --}}

<!-- section categories -->
<section class="categories-section">
	<div class="container-fluid">
		<div class="row mb30">
			<div class="col-xs-12 m-auto">
				<h4 class="text-center">Popular Categories</h4>
				<p class="text-center">Browse the most desirable categories</p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-5 col-xs-12 half-images">
				<img class="img-responsive" src="img/photo-item3.jpg" alt="screen">
				<img class="img-responsive" src="img/photo-item4.jpg" alt="screen">
			</div>
			<div class="col-md-2 col-xs-12 full-images">
				<img class="img-responsive" src="img/photo-item5.jpg" alt="screen">
				<img class="img-responsive" src="img/photo-item7.jpg" alt="screen">
			</div>
			<div class="col-md-5 col-xs-12 half-images">
				<img class="img-responsive" src="img/photo-item8.jpg" alt="screen">
				<img class="img-responsive" src="img/photo-item9.jpg" alt="screen">
			</div>
		</div>
	</div>
</section>
<!-- ... end section categories -->

{{-- section most visited --}}
<section class="medium-padding80">
	<div class="container">
		<div class="row mb30">
			<div class="col-xl-4 col-lg-4 m-auto col-md-12 col-sm-12 col-xs-12">
				<div class="crumina-module crumina-heading align-center mb0">
					<h4 class="h4 heading-title mb0">Most Visited Places</h4>
					<p class="heading-text mt0">Discover top-rated local buisnesses</p>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
				<!-- Post -->
				<article class="hentry blog-post">
					<div class="post-thumb">
						<img src="img/photo-item10.jpg" alt="photo">
					</div>
				
					<div class="post-content">
						<img src="img/icon-chat26.png">
						<img src="img/icon-chat26.png">
						<img src="img/icon-chat26.png">
						<img src="img/icon-chat26.png">
						<img src="img/icon-chat26.png">
						<div class="post__date">
							<time class="published" datetime="2017-03-24T18:18">(12 Reviews)</time>
						</div>
					</div>
				</article>	
				<!-- ... end Post -->
			</div>

			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
				<!-- Post -->
				<article class="hentry blog-post">
					<div class="post-thumb">
						<img src="img/photo-item11.jpg" alt="photo">
					</div>
				
					<div class="post-content">
						<img src="img/icon-chat26.png">
						<img src="img/icon-chat26.png">
						<img src="img/icon-chat26.png">
						<img src="img/icon-chat26.png">
						<img src="img/icon-chat26.png">
						<div class="post__date">
							<time class="published" datetime="2017-03-24T18:18">(10 Reviews)</time>
						</div>
					</div>
				</article>	
				<!-- ... end Post -->
			</div>

			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
				<!-- Post -->
				<article class="hentry blog-post">
					<div class="post-thumb">
						<img src="img/photo-item9.jpg" alt="photo">
					</div>
				
					<div class="post-content">
						<img src="img/icon-chat26.png">
						<img src="img/icon-chat26.png">
						<img src="img/icon-chat26.png">
						<img src="img/icon-chat26.png">
						<img src="img/icon-chat26.png">
						<div class="post__date">
							<time class="published" datetime="2017-03-24T18:18">(7 Reviews)</time>
						</div>
					</div>
				</article>	
				<!-- ... end Post -->
			</div>

		</div>
	</div>
</section>
{{-- end section most visited --}}

{{-- section plan vacation --}}
<section class="medium-padding80 bg-grey-lime">
	<div class="container">
		<div class="row mb30">
			<div class="col-xl-6 col-lg-6 m-auto col-md-12 col-sm-12 col-xs-12">
				<div class="crumina-module crumina-heading align-center mb0">
					<h4 class="h4 heading-title mb0">Plan The Vacation of Your Dreams</h4>
					<p class="heading-text mt0">Plan the vacations of your dreams at best and attractive places</p>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
				<!-- Post -->
				<article class="hentry blog-post">
					<div class="post-thumb mb30 text-center h3 c-twitter">
						<i class="fa fa-map-pin"></i>
					</div>
					<p class="c-grey bold text-center">Find Intresting Place</p>
					<p class="text-center">Plan the vacations of your dreams at best and attractive places</p>
				</article>	
				<!-- ... end Post -->
			</div>

			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
				<!-- Post -->
				<article class="hentry blog-post">
					<div class="post-thumb mb30 text-center h3 c-twitter">
						<i class="fa fa-envelope"></i>
					</div>
					<p class="c-grey bold text-center">Contact a Few Owners</p>
					<p class="text-center">Plan the vacations of your dreams at best and attractive places</p>
				</article>	
				<!-- ... end Post -->
			</div>

			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
				<!-- Post -->
				<article class="hentry blog-post">
					<div class="post-thumb mb30 text-center h3 c-twitter">
						<i class="fa fa-user"></i>
					</div>
					<p class="c-grey bold text-center">Make a Reservation</p>
					<p class="text-center">Plan the vacations of your dreams at best and attractive places</p>
				</article>	
				<!-- ... end Post -->
			</div>

		</div>
	</div>
</section>
{{-- end section plan vacation --}}

{{-- section news --}}
<section class="medium-padding80">
	<div class="container">
		<div class="row mb30">
			<div class="col-xl-4 col-lg-4 m-auto col-md-12 col-sm-12 col-xs-12">
				<div class="crumina-module crumina-heading align-center mb0">
					<h3 class="h3 heading-title mb0">Our Latest News</h3>
					<p class="heading-text mb0 mt0">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore</p>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12 bg-red-light no-padding outline10">
				<!-- Post -->
				<article class="hentry blog-post">
					<div class="post-thumb">
						<img src="img/photo-item3.jpg" alt="photo">
					</div>
					<div class="post-content">
						<a href="#" class="h5 post-title c-white">Here’s the Featured Urban photo of August! </a>
						<p class="c-white">Here’s a photo from last month’s photoshoot. We got really awesome shots for the new catalog.</p>
				
						<div class="post-additional-info inline-items">
							<div class="author-date">
								<a class="h6 post__author-name fn c-white" href="#">Maddy Simmons</a>
							</div>
							<div class="comments-shared">
								<a href="#" class="post-add-icon inline-items c-white">
									<svg class="olymp-speech-balloon-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-speech-balloon-icon"></use></svg>
									<span>0</span>
								</a>
							</div>
						</div>
					</div>
				</article>
				<!-- ... end Post -->
			</div>

			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12 bg-twitter no-padding outline10">
				<!-- Post -->
				<article class="hentry blog-post">
					<div class="post-thumb">
						<img src="img/photo-item11.jpg" alt="photo">
					</div>
					<div class="post-content">
						<a href="#" class="h5 post-title c-white">Here’s the Featured Urban photo of August! </a>
						<p class="c-white">Here’s a photo from last month’s photoshoot. We got really awesome shots for the new catalog.</p>
				
						<div class="post-additional-info inline-items">
							<div class="author-date">
								<a class="h6 post__author-name fn c-white" href="#">Maddy Simmons</a>
							</div>
							<div class="comments-shared">
								<a href="#" class="post-add-icon inline-items c-white">
									<svg class="olymp-speech-balloon-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-speech-balloon-icon"></use></svg>
									<span>0</span>
								</a>
							</div>
						</div>
					</div>
				</article>
				<!-- ... end Post -->
			</div>

			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12 bg-red-light no-padding outline10">
				<!-- Post -->
				<article class="hentry blog-post">
					<div class="post-thumb">
						<img src="img/photo-item10.jpg" alt="photo">
					</div>
					<div class="post-content">
						<a href="#" class="h5 post-title c-white">Here’s the Featured Urban photo of August! </a>
						<p class="c-white">Here’s a photo from last month’s photoshoot. We got really awesome shots for the new catalog.</p>
				
						<div class="post-additional-info inline-items">
							<div class="author-date">
								<a class="h6 post__author-name fn c-white" href="#">Maddy Simmons</a>
							</div>
							<div class="comments-shared">
								<a href="#" class="post-add-icon inline-items c-white">
									<svg class="olymp-speech-balloon-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-speech-balloon-icon"></use></svg>
									<span>0</span>
								</a>
							</div>
						</div>
					</div>
				</article>
				<!-- ... end Post -->
			</div>

		</div>
	</div>
</section>
{{-- end section news --}}

{{-- section donloads and views --}}
<section>
	<div class="container-fluid mb60">
		<div class="row">
			<div class="col-md-6 col-xs-6 bg-orange padding-20 text-center">
				<h4 class="h4 heading-title mb0 c-white bold">950 890</h4>
				<p class="mt0 c-white">Downloads</p>
			</div>
			<div class="col-md-6 col-xs-6 bg-twitter padding-20 text-center">
				<h4 class="h4 heading-title mb0 c-white bold">3 950 500</h4>
				<p class="mt0 c-white">Daily Views</p>
			</div>
		</div>
	</div>
</section>
{{-- end section donloads and views --}}

{{-- section peaople say --}}
<section class="medium-padding60">
	<div class="container">
		<div class="row mb30">
			<div class="col-xl-4 col-lg-4 m-auto col-md-12 col-sm-12 col-xs-12">
				<div class="crumina-module crumina-heading align-center mb0">
					<h4 class="h4 heading-title mb0">What People Say</h4>
					<p class="heading-text mt0">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore</p>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
				<!-- Post -->
				<article class="hentry blog-post">
					<p class="bg-twitter radius-5 c-white padding-20">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore	
					</p>
				
					<div class="post-content no-padding">
						<div class="author-thumb">
							<img src="img/photo-item10.jpg" alt="photo" width="90">
						</div>
						<div class="post__date">
							<p class="bold c-grey mb0">Jack Smith</p>
							<p class="c-grey-light mb0">Founder at Smith</p>
						</div>
					</div>
				</article>	
				<!-- ... end Post -->
			</div>

			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
				<!-- Post -->
				<article class="hentry blog-post">
					<p class="bg-twitter radius-5 c-white padding-20">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore	
					</p>
				
					<div class="post-content no-padding">
						<div class="author-thumb">
							<img src="img/photo-item11.jpg" alt="photo" width="90">
						</div>
						<div class="post__date">
							<p class="bold c-grey mb0">Jack Smith</p>
							<p class="c-grey-light mb0">Founder at Smith</p>
						</div>
					</div>
				</article>	
				<!-- ... end Post -->
			</div>

			<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-xs-12">
				<!-- Post -->
				<article class="hentry blog-post">
					<p class="bg-twitter radius-5 c-white padding-20">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore	
					</p>
				
					<div class="post-content no-padding">
						<div class="author-thumb">
							<img src="img/photo-item9.jpg" alt="photo" width="90">
						</div>
						<div class="post__date">
							<p class="bold c-grey mb0">Jack Smith</p>
							<p class="c-grey-light mb0">Founder at Smith</p>
						</div>
					</div>
				</article>	
				<!-- ... end Post -->
			</div>

		</div>
	</div>
</section>
{{-- end section peaople say --}}

{{-- section mobile apps --}}
<section class="medium-padding60 bg-grey-lime">
	<div class="container">
		<div class="row">

			<div class="col-xl-6 col-lg-6 m-auto col-md-12 col-sm-12 col-xs-12">
				<div class="crumina-module crumina-heading align-justify mb0">
					<h4 class="h4 heading-title mb0">Available on iOS and Android</h4>
					<p class="heading-text mt0">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore</p>
					<a href="javascript:;" class="btn bg-choco text-left">
						<span class="weight-light">AVAILABLE ON THE</span><br>
						<span class="bold">APP STORE</span>
					</a>
					<a href="javascript:;" class="btn bg-twitter text-left">
						<span class="weight-light">AVAILABLE ON THE</span><br>
						<span class="bold">GOOGLE PLAY</span>
					</a>
				</div>
			</div>

			<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<!-- Post -->
				<article class="hentry blog-post">
					<div class="post-thumb text-left c-twitter">
						<img src="img/image4.png" alt="screen" style="width: 300px;">
					</div>
				</article>	
				<!-- ... end Post -->
			</div>

		</div>

	</div>
</section>
{{-- end section mobile apps --}}

{{-- section integration --}}
<section class="medium-padding60">
	<div class="container">
		<div class="row">

			<div class="col-xl-6 col-lg-6 m-auto col-md-12 col-sm-12 col-xs-12">
				<div class="crumina-module crumina-heading align-center mb0 c-grey">
					<h4 class="h4 heading-title mb0">Integration</h4>
					<p class="heading-text mt0">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.</p>
					<a href="javascript:;" class="btn bg-twitter">
						<img src="{{ asset('svg-icons/google.svg') }}" alt="arrow" width="25">
						<span class="bold">Google</span>
					</a>
					<a href="javascript:;" class="btn bg-twitter">
						<img src="{{ asset('svg-icons/facebook.svg') }}" alt="arrow" width="25">
						<span class="bold">Facebook</span>
					</a>
					<a href="javascript:;" class="btn bg-twitter">
						<img src="{{ asset('svg-icons/twitter.svg') }}" alt="arrow" width="25">
						<span class="bold">Twitter</span>
					</a>
					<a href="javascript:;" class="btn bg-twitter">
						<img src="{{ asset('svg-icons/instagram.svg') }}" alt="arrow" width="25">
						<span class="bold">Instagram</span>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>
{{-- end section integration --}}

{{-- section newsletter --}}
<section class="medium-padding60 bg-white">
	<div class="container">
		<div class="row">
			<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12 m-auto">
				<div class="crumina-module crumina-heading c-grey-light">
					<p class="heading-text">Subscribe to be the first one to know about updates, new features and much more!
					</p>
				</div>	
			</div>

			<div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-xs-12">
				<form class="form-inline subscribe-form" method="post">
					<div class="form-group label-floating is-empty">
						<label class="control-label">Enter your email</label>
						<input class="form-control bg-white" placeholder="" type="email">
					</div>
					<button class="btn btn-blue btn-lg">Send</button>
				</form>
			</div>
		</div>
	</div>
</section>
{{-- end section newsletter --}}
@endsection