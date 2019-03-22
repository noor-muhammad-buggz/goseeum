@php
$pageTitle = 'Register';
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
				<a href="{{url('login')}}" class="btn btn-md btn-border c-white">Login Now!</a>
			</div>
		</div>

		<div class="col-xl-4 col-lg-5 col-md-12 col-sm-12 col-xs-12">

			<!-- Login-Registration Form  -->

			<div class="registration-login-form">
				<div class="title h6">Register</div>
				<form class="content" method="post" action="{{ url('register') }}">
					{{csrf_field()}}
					<div class="row">
						<div class="col-lg-6 col-md-6">
							<div class="form-group is-empty">
								<label class="control-label">First Name</label>
								<input type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name') }}" autofocus>
								@if ($errors->has('first_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                @endif
							</div>
						</div>
						<div class="col-lg-6 col-md-6">
							<div class="form-group  is-empty">
								<label class="control-label">Last Name</label>
								<input type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') }}" autofocus>
								@if ($errors->has('last_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                @endif
							</div>
						</div>
						<div class="col-xl-12 col-lg-12 col-md-12">
							<div class="form-group  is-empty">
								<label class="control-label">Your Email</label>
								<input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}">

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

							<div class="form-group date-time-picker ">
								<label class="control-label">Your Birthday</label>
								<input type="text" class="form-control{{ $errors->has('dob') ? ' is-invalid' : '' }}" name="dob" value="{{ old('dob') }}">
								<span class="input-group-addon">
									<svg class="olymp-calendar-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-calendar-icon"></use></svg>
								</span>
								@if ($errors->has('dob'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('dob') }}</strong>
                                    </span>
                                @endif
							</div>

							<div class="form-group  is-select">
								<label class="control-label">Your Gender</label>
								<select class="selectpicker form-control" name="gender">
									<option value="male">Male</option>
									<option value="female">Female</option>
								</select>
							</div>

							<button type="submit" class="btn btn-orange full-width">Register</button>
						</div>
					</div>
				</form>
			</div>
			<!-- ... end Login-Registration Form  -->		
		</div>
	</div>
</div>
@endsection