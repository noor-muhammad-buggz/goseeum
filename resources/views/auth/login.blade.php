@php
$pageTitle = 'Login';
@endphp
@extends('layouts.login')
@section('content')
<div class="container">
	<div class="row display-flex">
		<div class="col-xl-7 col-lg-7 col-md-12 col-sm-12 col-xs-12">
			<img src="{{asset('img/logo1.png')}}" alt="Goseeum" style="width:160px;">
			<div class="landing-content">
				<h3>Find Your Locations and Share it with your friends</h3>
				<p>We are the best and biggest social network with 5 billion active users all around the world. Share you thoughts, write blog posts, show your favourite music via Stopify, earn badges and much more!
				</p>
				<a href="{{url('register')}}" class="btn btn-md btn-border c-white">Register Now!</a>
			</div>
		</div>

		<div class="col-xl-4 col-lg-5 col-md-12 col-sm-12 col-xs-12">
			<!-- Login-Registration Form  -->
			<div class="registration-login-form">
				<div class="title h6">Login</div>
				<form method="POST" action="{{ url('login') }}" class="content">
					{{csrf_field()}}
					<div class="row">
						<div class="col-xl-12 col-lg-12 col-md-12">
							<div class="form-group  is-empty">
								<label class="control-label">Your Email</label>
								<input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
							</div>
							<div class="form-group  is-empty">
								<label class="control-label">Your Password</label>
								<input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
							</div>

							<div class="remember">
								<div class="checkbox">
									<label>
										<input name="optionsCheckboxes" type="checkbox">
										Remember Me
									</label>
								</div>
								<a href="{{ route('password.request') }}" class="forgot">Forgot my Password</a>
							</div>

							<button type="submit" class="btn btn-orange full-width">Login</button>
						</div>
					</div>
				</form>
			</div>
			<!-- ... end Login-Registration Form  -->		
		</div>
	</div>
</div>
@endsection