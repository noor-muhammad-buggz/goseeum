@php
$pageTitle = 'Change Password';
@endphp
@extends('layouts.user')
@section('content')
<!-- sidemenu responsive menu -->
<div class="profile-settings-responsive">
    <a href="#" class="js-profile-settings-open profile-settings-open">
        <i class="fa fa-angle-right" aria-hidden="true"></i>
        <i class="fa fa-angle-left" aria-hidden="true"></i>
    </a>
    <div class="mCustomScrollbar" data-mcs-theme="dark">
        <div class="ui-block">
            <div class="your-profile">
                <div class="ui-block-title ui-block-title-small">
                    <h6 class="title">YOUR PROFILE</h6>
                </div>
                <div class="ui-block-title">
                    <a href="{{url('user/profile-settings')}}" class="h6 title">Profile Settings</a>
                </div>
                <div class="ui-block-title">
                    <a href="{{url('user/change-password')}}" class="h6 title">Change Password</a>
                </div>
                <div class="ui-block-title">
                    <a href="{{url('user/chat-messages')}}" class="h6 title">Chat / Messages</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end sidemenu responsive menu -->
<div class="container">
    <div class="row">
        <div class="ui-block-title">
            @include('errors.form_errors')
        </div>
    </div>
</div>
<!-- <div class="container">
    <div class="row">
        <div class="ui-block-title">
            <h4 class="title">{{$pageTitle}}</h4>
        </div>
    </div>
</div> -->

<div class="container">
    <div class="row">
        <div class="col-xl-9 order-xl-2 col-lg-9 order-lg-2 col-md-12 order-md-1 col-sm-12 col-xs-12">
            <div class="ui-block">
                <div class="ui-block-title">
                    <h6 class="title">Change Password</h6>
                </div>
                <div class="ui-block-content">
                    <!-- Change Password Form -->
                    <form action="{{url('user/change-password')}}" method="post">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group is-empty">
                                    <label for="location_name" class="control-label">Current Password</label>
                                    <input type="password" name="current_password" class="form-control{{ $errors->has('current_password') ? ' is-invalid' : '' }}">
                                    @if ($errors->has('current_password'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('current_password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                    
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group is-empty">
                                    <label for="location_lat" class="control-label">New Password</label>
                                    <input type="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}">
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group is-empty">
                                    <label for="location_lang" class="control-label">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" value="">
                                    @if ($errors->has('password_confirmation'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <button class="btn btn-orange btn-sm" type="submit">Save</button>
                            </div>
                    
                        </div>
                    </form>
                    <!-- ... end Change Password Form -->
                </div>
            </div>
        </div>

        <div class="col-xl-3 order-xl-1 col-lg-3 order-lg-1 col-md-12 order-md-2 col-sm-12 col-xs-12 responsive-display-none">
            <div class="ui-block">
                <!-- Your Profile  -->
                <div class="your-profile">
                    <div class="ui-block-title ui-block-title-small">
                        <h6 class="title">YOUR ACCOUNT</h6>
                    </div>
                    <div class="ui-block-title">
                        <a href="{{url('user/profile-settings')}}" class="h6 title">Profile Settings</a>
                    </div>
                    <div class="ui-block-title">
                        <a href="{{url('user/change-password')}}" class="h6 title">Change Password</a>
                    </div>
                    <div class="ui-block-title">
                        <a href="{{url('user/chat-messages')}}" class="h6 title">Chat / Messages</a>
                    </div>
                </div>
                <!-- ... end Your Profile  -->
            </div>
        </div>
    </div>
</div>
@endsection
