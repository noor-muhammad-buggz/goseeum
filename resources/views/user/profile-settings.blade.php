@php
$pageTitle = 'Profile Settings';
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

<div class="container">
    <div class="row">
        <div class="col-xl-9 order-xl-2 col-lg-9 order-lg-2 col-md-12 order-md-1 col-sm-12 col-xs-12">
            <div class="ui-block">
                <div class="ui-block-title">
                    <h6 class="title">Profile Settings</h6>
                </div>
                <div class="ui-block-content">
                    <!-- Change Password Form -->
                    <form action="{{url('user/profile-settings')}}" method="post">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group is-empty">
                                    <label for="location_name" class="control-label">First Name</label>
                                    <input type="text" class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" name="first_name" value="{{ old('first_name') ?: $user->first_name }}" autofocus>
                                    @if ($errors->has('first_name'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                    
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group is-empty">
                                    <label class="control-label">Last Name</label>
                                    <input type="text" class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" name="last_name" value="{{ old('last_name') ?: $user->last_name }}">
                                    @if ($errors->has('last_name'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group is-empty">
                                    <label class="control-label">Your Email</label>
                                    <input id="email" type="email" class="form-control" value="{{ $user->email }}" readonly="readonly">
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group date-time-picker ">
                                    <label class="control-label">Your Birthday</label>
                                    <input type="text" class="form-control{{ $errors->has('dob') ? ' is-invalid' : '' }}" name="dob" value="{{ old('dob') ?: $user->dob }}">
                                    <span class="input-group-addon">
                                        <svg class="olymp-calendar-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-calendar-icon') }}"></use></svg>
                                    </span>
                                    @if ($errors->has('dob'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('dob') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group  is-select">
                                    <label class="control-label">Your Gender</label>
                                    <select class="selectpicker form-control" name="gender">
                                        <option value="Male" {{ (old('gender') && old('gender') == 'Male') ? 'selected' : ($user->gender == 'Male') ? 'selected' : ''}}>Male</option>
                                        <option value="Female" {{ (old('gender') && old('gender') == 'Female') ? 'selected' : ($user->gender == 'Female') ? 'selected' : ''}}>Female</option>
                                    </select>
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

@section('scripts')
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
@endsection