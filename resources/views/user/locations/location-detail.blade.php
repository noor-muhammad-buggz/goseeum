@php
$pageTitle = 'Search Locations';
@endphp
@extends('layouts.user')
<style type="text/css">
.padding-0 {
    padding: 0px !important;
}

.padding-20 {
    padding: 20px !important;
}

.hidden{
    display: none;
}

.border-0 {
    border: 0px !important;
}

span.file-item{
    width: auto;
    height: auto;
    display: inline-block;
}

span.file-item .scene-inner-file-item{
    float: right;
    width: 15px;
    height: 15px;
    top: 2px;
    position: relative;
    background: #e8a93f;
    color: #fff;
    border-radius: 50%;
    left: -16px;
    cursor: pointer;
}
.mt50{
   margin-top:  30px !important;
}
</style>
@section('content')

@if(empty($location))
    <h4 class="text-center padding-20">Requested Location Not Found</h4>
@else
<span class="l_id" data-target="{{$location->id}}"></span>
<span class="l_share_url" data-target="{{url('user/locations/share/social/'.str_replace(' ', '_', $location->location_name).'_'.$location->id.'/'.$place_id)}}"></span>

<!-- Top Header-Profile -->
<div class="container">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="ui-block mb0">
                <div class="visits-top-header">
                    <div class="top-header-thumb">
                        <div class="loc_detail">
                            <div class="loc_name">
                                <p>
                                    {{$location->location_name}}
                                    <button class="l_subscribe btn btn-blue pull-right {{($location->is_subscribed == 1) ? 'active-orange' : ''}}"><i class="fa fa-bookmark"></i>&nbsp;<span>{{($location->is_subscribed == 1) ? 'Subscribed' : 'Subscribe'}}</span></button>
                                </p>
                            </div>
                            <div class="loc_rating">{{bcdiv($location->rating, 1, 1) }}</div>
                            <div class="loc_address">
                                <div class="address">
                                    {{$location->location_address}}
                                </div>
                                <div class="distance">
                                    62km
                                </div>
                            </div>
                        </div>
                        @if(count($location->images) > 0)
                            <img src="{{ asset('uploads/'.$location->images[0]->location_image_url) }}" alt="nature">
                        @else
                            <img src="{{ asset('img/top-header1.jpg') }}" alt="nature">
                        @endif
                    </div>
                    <div class="visits-section">
                        <div class="row">
                            <div class="m-auto col-lg-9 col-md-9 col-sm-12">
                                <ul class="visits-header">
                                    <li>
                                        <span>Visits</span><br/>
                                        <span>{{$location->visits}}</span>
                                    </li>
                                    <li>
                                        <span>Scenes</span><br/>
                                        <span>{{$location->scenes}}</span>
                                    </li>
                                    <li>
                                        <span>Reviews</span><br/>
                                        <span>{{$location->reviews}}</span>
                                    </li>
                                    <li>
                                    	<span>Subscribers</span><br/>
                                    	<span>{{$location->subscribers}}</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="next_address">
                        <div class="row">
                            <div class="m-auto col-lg-9 col-md-9 col-sm-12">
                                <div class="next_address_content">
                                    {{$location->location_address}}    
                                </div>
                                <div class="next_address_icon">
                                    <i class="fa fa-phone"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ... end Top Header-Profile -->
<div class="container">
    <div class="row">
        <!-- Main Content -->
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div id="newsfeed-items-grid">
                <div class="ui-block mb0">
                    <!-- start time slots -->
                    <article class="grey-block" style="background-color: #f5f5f5 !important;">
                        @if($location->location_type == 'buisness')
                        <div class="row">
                            <div class="col-sm-12">
                                <ul class="mb0 time-blocks">
                                    @if(!empty($location->workingdays))
                                        @foreach($location->workingdays as $openhour)
                                    	<li>
                                            @if($openhour->lh_is_holiday == 0)
                                                <span>{{$days[$openhour->lh_day]}}</span>
                                        		<span>{{$openhour->lh_open}} - {{$openhour->lh_close}}</span>
                                            @else
                                                @php $holidays[] = $days[$openhour->lh_day]; @endphp
                                            @endif
                                    	</li>
                                        @endforeach
                                    @endif

                                    @if(!empty($location->offdays))
                                    @php
                                        $offd = array_column($location->offdays->toArray(), 'lh_day');
                                        $day = array_filter($days, function($key) use($offd) {
                                            if(in_array($key, $offd)) {
                                                return true;
                                            }
                                        }, ARRAY_FILTER_USE_KEY);
                                    @endphp
                                    <li>
                                        <span>{{implode(',', $day)}}</span>
                                        <span>close</span>
                                    </li>
                                    @endif                                    

                                </ul>
                            </div>
                        </div>
                        @endif
                        <!-- start time slots -->
                        <div class="row">
                            <div class="col-sm-12">
        	                    <ul class="mb0 tour-blocks">
        	                    	<li>
        	                    		<p class="l_checkin {{($location->is_checkin == 1) ? 'active-orange' : ''}}"><i class="fa fa-map-marker-alt"></i></p>
        	                    		<p>{{($location->is_checkin == 1) ? 'Checked-In' : 'Check-In'}}</p>
        	                    	</li>
        	                    	<li>
        	                    		<p class="l_save {{($location->is_saved == 1) ? 'active-orange' : ''}}"><i class="fa fa-download"></i></p>
        	                    		<p>{{($location->is_saved == 1) ? 'Saved' : 'Save'}}</p>
        	                    	</li>
        	                    	<li> 
        	                    		<p class="l_rate {{($location->is_rated >= 1) ? 'active-orange' : ''}}"><i class="fa fa-star"></i></p>
        	                    		<p>Rate</p>
                                        <div class="wrapper" style="position: absolute;top: 50%;left: 43%;">
                                            <!-- popup for give rating to location -->
                                            <div class="modal" style="position: relative; width: 55%;" id="rate-location">
                                                <div class="modal-dialog ui-block window-popup update-header-photo">
                                                    <a href="javascript:;" class="close icon-close" data-dismiss="modal" aria-label="Close">
                                                        <svg class="olymp-close-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-close-icon') }}"></use></svg>
                                                    </a>
                                                    <div class="ui-block border-0">
                                                        <div class="loader col-md-12 padding-20 text-center hidden">
                                                            <img src="{{ asset('img/loader.gif') }}" alt="please wait...">
                                                        </div>
                                                        <!-- start rating form -->
                                                        <div class="news-feed-form rate_location_form hidden">
                                                            <div class="row">
                                                                <div class="col-md-12 m-auto">
                                                                    <div class="form-group mt20 text-center">
                                                                        <div id="rating"></div>
                                                                    </div>
                                                                    <div class="form-group mt20 text-center">
                                                                        <button class="btn btn-orange" id="rate_location_btn">Save</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- ... end popup for give rating -->
                                        </div> 
        	                    	</li>
        	                    	<li>
        	                    		<p class="l_share" data-toggle="modal" data-target="#share-mod"><i class="fa fa-share"></i></p>
        	                    		<p>Share</p>
        	                    	</li>
        	                    </ul>
                            </div>
                        </div>
	                    <!-- .. end time slots -->
	                    <div class="clear"></div>
	                    <!-- start of description section -->
                        <div class="row">
                            <div class="col-sm-12">
        	                    <h5 class="mb10">Description</h5>
        	                    <p class="mb10">{{$location->location_description}}</p>
                                <p class="mb30 mt20">
        	                       <!-- <a href="" class="btn btn-success bg-light-brown normal-font">Read more...</a> -->
                                </p>
                                <p class="bb2-brown"></p>
                            </div>
                        </div>
	                    <!-- end of description section -->
                        <div class="clear"></div>
                        <!-- start of description section -->
                        <div class="row mb30">
                            <div class="col-sm-12">
                                <h5 class="mb10 mt50">Close by services</h5>
                                <ul class="service-blocks">
                                    <li class="mb30">
                                        <p class="nearby" data-target="hospital"><i class="fa fa-bed"></i></p>
                                        <p>Hospitals</p>
                                    </li>
                                    <li class="mb30">
                                        <p class="nearby" data-target="restaurant"><i class="fa fa-utensils"></i></p>
                                        <p>Resturants</p>
                                    </li>
                                    <li class="mb30">
                                        <p class="nearby" data-target="cafe"><i class="fa fa-coffee"></i></p>
                                        <p>Cafes</p>
                                    </li>
                                    <li class="mb30">
                                        <p class="nearby" data-target="gas_station"><i class="fa fa-home"></i></p>
                                        <p>Gas Stations</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- end of description section -->
                        <!-- start of weather section -->
                        @if(!empty($location->weather))
                        <div class="row mb30">
                            <div class="col-sm-12">
                                <ul class="weather-blocks">
                                    <li>
                                        <ul>
                                            <li>Weather</li>
                                            <li>{{(int)$location->weather[0]->temp}}<sup>c</sup></li>        
                                        </ul>
                                    </li>
                                    <li>
                                        <ul>
                                            @foreach($location->weather as $we)
                                            @php
                                            $dateObj = new \DateTime($we->valid_date);
                                            @endphp
                                            <li class="mb30">
                                                <p><img width="47" src="https://www.weatherbit.io/static/img/icons/{{$we->weather->icon.'.png'}}" data-toggle="tooltip" title="{{$we->weather->description}}"></p>
                                                <p>{{ucfirst($dateObj->format('D'))}}</p>
                                                <small>{{(int)$we->min_temp}}<sup>c</sup>-{{(int)$we->max_temp}}<sup>c</sup></small>
                                            </li>
                                            @endforeach
                                        </ul>   
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @endif
                        <!-- end of weather section -->
                        <!-- start of description section -->
                        {{-- <div class="row mb30">
                            <div class="col-sm-12">
                                <ul class="timeline-blocks">
                                    <li>
                                        <div class="timeline-photo">
                                            <img src="{{asset('img/last-phot11.jpg')}}">
                                        </div>
                                        <div class="timeline-header">
                                            <h4>New York City Park</h4>
                                            <h2>1600</h2>
                                        </div>
                                        <div class="timeline-footer">
                                            dummy text dummy text dummy text dummy text dummy text dummy text dummy text dummy text dummy text dummy text dummy text dummy text
                                        </div>
                                    </li>
                                    <li>
                                        <div class="timeline-header">
                                            <h4>New York City Park</h4>
                                            <h2>1600</h2>
                                        </div>
                                        <div class="timeline-footer">
                                            dummy text dummy text dummy text dummy text dummy text dummy text dummy text dummy text dummy text dummy text dummy text dummy text
                                        </div>
                                        <div class="timeline-photo">
                                            <img src="{{asset('img/last-phot11.jpg')}}">
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div> --}}
                        <!-- end of description section -->
                        <!-- start of description section -->
                        <div class="row mb30">
                            <div class="col-sm-12">
                                <h5>
                                    Scenes
                                    <button class="btn bg-blue pull-right" data-toggle="modal" data-target="#upload-scenes">
                                        <i class="fa fa-image"></i> Upload Scenes</button>
                                </h5>
                                <ul class="scenes-blocks">
                                    @foreach($location->images as $img)
                                    <li>
                                        <div class="scenes-photo" data-src="{{url('uploads/'.$img->location_image_url)}}">
                                            <img src="{{url('uploads/'.$img->location_image_url)}}">
                                            <p class="scenes-caption">{{$img->location_caption}}</p>
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <!-- end of description section -->
                        <!-- start of description section -->
                        <div class="row">
                            <div class="col-sm-12">
                                <h5>Comments </h5>
                                <ul class="comment-blocks">
                                @if(count($location->comments) > 0)
                                    @foreach($location->comments as $cmnt)
                                    <li>
                                        <div class="comment-photo">
                                            @if(!empty($cmnt->commentuser->profilephoto()))
                                                <img src="{{ asset('uploads/'.$cmnt->commentuser->profilephoto()->photo_url) }}" alt="profile" style="width: 40px; height: 40px;">
                                            @else
                                                <img alt="author" src="{{ asset('img/no-profile-photo.jpg') }}" class="profile">
                                            @endif
                                            <p></p>
                                            <small>{{$cmnt->created_at->diffForHumans()}}</small>
                                        </div>
                                        <div class="comment-body">
                                            <p class="comment-name">{{$cmnt->commentuser->first_name.' '.$cmnt->commentuser->last_name}}</p>
                                            <p class="comment-content">
                                                {{$cmnt->comment_body}}
                                            </p>
                                        </div>
                                    </li>
                                    @endforeach
                                @endif
                                </ul>
                            </div>
                        </div>
                        <!-- end of description section -->
                        <!-- start of description section -->
                        <div class="row">
                            <div class="col-sm-12">
                                <ul class="add-comment-blocks">
                                    <li>
                                        <div class="add-comment-photo">
                                            @if(!empty(Auth::user()->profilephoto()))
                                                <img src="{{ asset('uploads/'.Auth::user()->profilephoto()->photo_url) }}" alt="profile">
                                            @else
                                                <img alt="author" src="{{ asset('img/no-profile-photo.jpg') }}" class="profile">
                                            @endif
                                            <!-- <img src="{{asset('img/last-phot16.jpg')}}"> -->
                                        </div>
                                        <div class="add-comment-body">
                                            <input class="comment_body" placeholder="write comment here..." data-target="{{$location->id}}">
                                            <input type="hidden" name="cflag" value="" class="cflag" data-target="{{$location->id}}">
                                            <div class="btn-group">
                                                <button class="btn send_cmnt">Send</button>
                                                {{-- <button class="btn">Reply</button> --}}
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- end of description section -->
                        <!-- start of description section -->
                        <div class="row mb30">
                            <div class="col-md-12 col-sm-12">
                                <div class="last-buttons">
                                    {{-- <div class="col-md-4 col-sm-12">
                                        <button class="btn full-width btn-choco">History</button>
                                    </div> --}}
                                    <div class="col-md-4 col-sm-12">
                                        <button class="btn full-width btn-blue" data-toggle="modal" data-target="#arvideo">AR View</button>
                                    </div>
                                    {{-- <div class="col-md-4 col-sm-12">
                                        <button class="btn full-width btn-choco">Incidents</button>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                        <!-- end of description section -->
                    </article>
                    <!-- .. end time slots -->
                </div>
            </div>
        </div>
        <!-- ... end Main Content -->
    </div>
</div>

<!-- popup for give rating to location -->
<div class="modal" id="loader-mod">
    <div class="modal-dialog modal-sm ui-block" style="width: 70px;top: 40vh;">
        <div class="m-auto padding-20 text-center">
            <img src="{{ asset('img/loader.gif') }}" alt="please wait...">
        </div>
    </div>
</div>
<!-- ... end popup for give rating -->

<!-- popup for social media sharing -->
<div class="modal" id="share-mod">
    <div class="modal-dialog ui-block">
        <a href="javascript:;" class="close icon-close" data-dismiss="modal" aria-label="Close">
            <svg class="olymp-close-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-close-icon') }}"></use></svg>
        </a>
        <div class="m-auto padding-20 text-center row">
            <div class="col-md-12">
                <a href="javascript:;" id="fb-share" class="mb30 btn btn-md bg-facebook full-width"><i class="fa fa-share" aria-hidden="true"></i>&nbsp; Facebook</a>
                <a href="javascript:;" id="tw-share" class="mb30 btn btn-md bg-twitter full-width"><i class="fa fa-share" aria-hidden="true"></i>&nbsp; Twitter</a>
                <a href="javascript:;" id="ldin-share" class="mb30 btn btn-md bg-purple full-width"><i class="fa fa-share" aria-hidden="true"></i>&nbsp;LinkedIn</a>
            </div>
        </div>
    </div>
</div>
<!-- ... end popup for social media sharing -->

<!-- popup for give rating to location -->
<div class="modal" id="nearby-mod">
    <div class="modal-dialog modal-lg ui-block">
        <a href="javascript:;" class="close icon-close" data-dismiss="modal" aria-label="Close">
            <svg class="olymp-close-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-close-icon') }}"></use></svg>
        </a>
        <div class="m-auto padding-20 text-center nearby-loader hidden">
            <img src="{{ asset('img/loader.gif') }}" alt="please wait...">
        </div>
        <div id="nearby-map" class="hidden" style="width: 100%; height: 70vh;"></div>
    </div>
</div>
<!-- ... end popup for give rating -->

<!-- popup for uploading scenes -->
<div class="modal fade" id="upload-scenes">
    <div class="modal-dialog ui-block window-popup update-header-photo">
        <a href="javascript:;" class="close icon-close" data-dismiss="modal" aria-label="Close">
            <svg class="olymp-close-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-close-icon') }}"></use></svg>
        </a>
        <div class="ui-block-title">
            <h6 class="title">Upload Scenes</h6>
        </div>
        <div class="ui-block border-0">
            <div class="loader col-md-12 padding-20 text-center hidden">
                <img src="{{ asset('img/loader.gif') }}" alt="please wait...">
            </div>
            <!-- start post form -->
            <div class="news-feed-form upload_scenes_form padding-20">
                <div class="form-group">
                    <label class="control-label">Caption</label>
                    <input type="text" class="form-control" placeholder="" id="scenes_caption" />
                </div>
                <div class="add-options-message">
                    <!-- post photos section -->
                    <a href="javascript:;" class="options-message" data-toggle="tooltip" data-placement="top" data-original-title="ADD SCENES" onclick="$('#location_scenes').trigger('click')">
                        <svg class="olymp-camera-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-camera-icon') }}"></use></svg>
                    </a>
                    <br>
                    <div class="scenes-previews"></div>
                    <input type="file" name="location_scenes" id="location_scenes" accept=".jpeg, .png, .jpg" multiple="true" style="display: none;" onchange="storescenesmedia(this)">
                </div>
                <div class="add-options-message">
                    <button class="btn btn-orange" id="upload_scenes_btn">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ... end popup for add post photos -->

<!-- start of embed ar file video if exists -->
@if(!empty($location->location_ar_view))
<div class="modal fade" id="arvideo">
    <div class="modal-dialog ui-block window-popup update-header-photo">
        <a href="javascript:;" class="close icon-close" data-dismiss="modal" aria-label="Close">
            <svg class="olymp-close-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-close-icon') }}"></use></svg>
        </a>
        <div class="ui-block-title">
            <h6 class="title">AR View</h6>
        </div>
        <div class="ui-block border-0" style="margin:0px;padding:5px;">
            <div class="container" style="margin:0px;padding:0px;">
                <div class="row" style="margin:0px;padding:0px;">
                    <div class="col-md-12" style="margin:0px;padding:0px;">
                        <video id="video-container" class="video-js vjs-default-skin" preload="true" crossOrigin="anonymous" data-setup="{}" controls playsinline webkit-playsinline style="width:100%;height:350px;">
                            <source src="{{url('uploads/'.$location->location_ar_view)}}" type="video/mp4">
                            Your browser does not support HTML5 video.
                        </video>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<!-- end of embed ar file video if exists -->

@endif
@endsection

@section('scripts')
@if(!empty($location))
<script src="{{ asset('vrvideo/dist/player-skin.js') }}"></script>
<script src="{{ asset('vrvideo/dist/player.full.js') }}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=
AIzaSyDl9W3SYUq9zeRwCAWPuJwGXXsJ72vHajQ
"></script>
<script type="text/javascript">
    window.fbAsyncInit = function() {
      FB.init({
        appId      : '228073684622954',
        cookie     : true,
        xfbml      : true,
        version    : 'v2.8'
      });
    };
    // Load the SDK asynchronously
    (function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/all.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
</script>

<script type="text/javascript">
    var currentscenes = [];
    var validFileTypes = ["image/jpeg", "image/png", "image/jpg", "video/mp4", "video/flv", "video/wvm", "video/mp3", "video/wav"];
    $(document).ready(function(){    
        $('#rating').rateit({ min: 0, max: 5, step: .5, resetable: false, starwidth: 64});
        /*
        |----------------------------------------------------------------------
        | location subscribe section
        |----------------------------------------------------------------------
        */

        $(document).on('click touchstart', '.l_subscribe', function(event) {
            $('#loader-mod').modal('show');
            var formData = new FormData();
            formData.append('target', $('.l_id').attr('data-target'));
            // create comment
            $.ajax({
                url: URL + "/user/locations/subscribe/save",
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('#loader-mod').modal('hide');
                    if(data.status == 200) {
                        if(data.subscribe == 1) {
                            $('.l_subscribe').addClass('active-orange');
                            $('.l_subscribe').children('span').text('Subscribed');
                        }
                        else {
                            $('.l_subscribe').removeClass('active-orange');
                            $('.l_subscribe').children('span').text('Subscribe');
                        }
                        $.growl.notice({title: 'Success' ,message: data.message });
                    }
                    else {
                        $.growl.error({title: 'Error', message: data.message });
                    }
                }
            });
        });

        /*
        |----------------------------------------------------------------------
        | location rating section
        |----------------------------------------------------------------------
        */
        $(document).on('click touchstart', '.l_rate', function(event) {
            $('.rate_location_form').addClass('hidden');
            $('.loader').removeClass('hidden');
            var formData = new FormData();
            formData.append('target', $('.l_id').attr('data-target'));
            // create comment
            $.ajax({
                url: URL + "/user/locations/rating/get",
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('.rate_location_form').removeClass('hidden');
                    $('.loader').addClass('hidden');
                    $('#rate-location').modal('show');
                    $('select[name=rating]').val(data.rating);
                    $('#rating').rateit('value', data.rating);
                }
            });

        });

        $(document).on('click touchstart', '#rate_location_btn', function(event) {
            $('.rate_location_form').addClass('hidden');
            $('.loader').removeClass('hidden');
            var formData = new FormData();
            formData.append('target', $('.l_id').attr('data-target'));
            formData.append('rating', $('#rating').rateit('value'));
            // create comment
            $.ajax({
                url: URL + "/user/locations/rating/save",
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('.rate_location_form').removeClass('hidden');
                    $('.loader').addClass('hidden');
                    $('#rate-location').modal('hide');
                    $('select[name=rating]').val(1);
                    if(data.status == 200) {
                        $('.l_rate').addClass('active-orange');
                        $.growl.notice({title: 'Success' ,message: data.message });
                    }
                    else {
                        $.growl.error({title: 'Error', message: data.message });
                    }
                }
            });
        });
        /*
        |----------------------------------------------------------------------
        | location comments section
        |----------------------------------------------------------------------
        */
        $(document).on('keyup touchstart', '.comment_body', function(event) {
            var keyCode = event.keyCode || event.which;
            var _self = $(this);
            if(keyCode == 27 || event.key == 'Escape') {
                _self.val('');
                $('.cflag[data-target="'+_self.attr('data-target')+'"]').val('');        
            }
            else if(keyCode == 13 && _self.val().length > 0) {
                postComment(_self);
            }
        });

        $(document).on('click touchstart', '.send_cmnt', function(event) {
            var _self = $('.comment_body');
            if(_self.val().length > 0) {
                postComment(_self);
            }
        });
        
        /*
        |----------------------------------------------------------------------
        | save location for favourites
        |----------------------------------------------------------------------
        */
        $(document).on('click touchstart', '.l_save', function(event) {
            $('#loader-mod').modal('show');
            var formData = new FormData();
            formData.append('target', $('.l_id').attr('data-target'));
            // create comment
            $.ajax({
                url: URL + "/user/locations/saved/create",
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('#loader-mod').modal('hide');
                    if(data.status == 200) {
                        if(data.is_saved == 1) {
                            $('.l_save').addClass('active-orange');
                            $('.l_save').next('p').text('Saved');
                        }
                        else {
                            $('.l_save').removeClass('active-orange');
                            $('.l_save').next('p').text('Save');
                        }
                        $.growl.notice({title: 'Success' ,message: data.message });
                    }
                    else {
                        $.growl.error({title: 'Error', message: data.message });
                    }
                }
            });
        });

        /*
        |----------------------------------------------------------------------
        | check-in to location
        |----------------------------------------------------------------------
        */
        $(document).on('click touchstart', '.l_checkin', function(event) {
            $('#loader-mod').modal('show');
            var formData = new FormData();
            formData.append('target', $('.l_id').attr('data-target'));
            // create comment
            $.ajax({
                url: URL + "/user/locations/checkin/create",
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $('#loader-mod').modal('hide');
                    if(data.status == 200) {
                        if(data.is_saved == 1) {
                            $('.l_checkin').addClass('active-orange');
                            $('.l_checkin').next('p').text('Checked-In');
                        }
                        else {
                            $('.l_checkin').removeClass('active-orange');
                            $('.l_checkin').next('p').text('Check-In');
                        }
                        $.growl.notice({title: 'Success' ,message: data.message });
                    }
                    else {
                        $.growl.error({title: 'Error', message: data.message });
                    }
                }
            });
        });

        // delete scene image
        $(document).on('click', '.scene-inner-file-item', function(){
            var _self = $(this);
            // delete requested photo
            currentscenes.splice(parseInt(_self.attr('data-target')),1);
            _self.parent().remove();
        });

        $('#upload-scenes').on('hidden.bs.modal', function () {
            $('#location_scenes').val(null);
            currentscenes = [];
            $('.loader').addClass('hidden');
            $('.upload_scenes_form').removeClass('hidden');
        });

        // send request to update post
        $(document).on('click touchstart', '#upload_scenes_btn', function() {
            uploadlocationscenes();
        });
    });

    function postComment(_self) {
        _self.attr('disabled', 'disabled');
        $('.send_cmnt').attr('disabled', 'disabled');
        var formData = new FormData();
        formData.append('target', _self.attr('data-target'));
        formData.append('content', _self.val());
        formData.append('flag', $('.cflag[data-target="'+_self.attr('data-target')+'"]').val());
        // create comment
        $.ajax({
            url: URL + "/user/locations/comments/create",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $(_self).removeAttr('disabled');
                $('.send_cmnt').removeAttr('disabled');
                if(data.status == 200) {
                    $('.cflag[data-target="'+_self.attr('data-target')+'"]').val('');
                    $.growl.notice({title: 'Success' ,message: data.message });
                    getComments(_self.attr('data-target'));
                    $(_self).val('');
                }
                else {
                    $.growl.error({title: 'Error', message: data.message });
                }
            }
        });
    }

    function getComments(target) {
        var formData = new FormData();
        formData.append('target', target);
        // create comment
        $.ajax({
            url: URL + "/user/locations/comments/get",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $('.comment-blocks').html(data.html);
            }
        });
    }

    // display nearby places
    var gl = '';
    $(document).ready(function(){
        $(document).on('click touchstart', '.nearby', function(){
            $('#nearby-mod').modal('show');
            $('.nearby-loader').removeClass('hidden');
            $('#nearby-map').addClass('hidden');
            var target = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location={{$location->location_lat}},{{$location->location_lang}}&radius=50000&type='+$(this).attr('data-target')+'&key=AIzaSyDl9W3SYUq9zeRwCAWPuJwGXXsJ72vHajQ';
            var formData = new FormData();
            formData.append('target', target);
            // create comment
            $.ajax({
                url: URL + "/user/search/nearby/locations",
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    gl = data;
                    if(data.status = 'OK') {
                        $('.nearby-loader').addClass('hidden');
                        $('#nearby-map').removeClass('hidden');
                        DisplayNearByMarkers(data[0].results);
                    }
                }
            });
        });
    });

    // display nearby markers
    function DisplayNearByMarkers(response) {
        var latlng = new google.maps.LatLng('{{$location->location_lat}}', '{{$location->location_lang}}');
        var origin = "{{$location->location_lat}},{{$location->location_lang}}";
        var myOptions = {
            zoom: 10,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            //ROADMAP,SATELLITE,HYBRID,TERRAIN 
            mapTypeControl: true
        };

        var map = new google.maps.Map(document.getElementById("nearby-map"),myOptions);
        var geocoder = new google.maps.Geocoder();
        var infowindow = new google.maps.InfoWindow(), marker, lat, lng;
        var bounds = new google.maps.LatLngBounds();

        for(var o in response) {
            lat = response[o].geometry.location.lat;
            lng = response[o].geometry.location.lng;
            pos = lat+','+lng;
            name = response[o].name;
            rating = parseFloat(response[o].rating).toFixed(1);
            var icon = {
                url: response[o].icon,
                scaledSize: new google.maps.Size(30, 30)
            };

            // if(response[o].photos.length > 0) {
            //     pic = URL+'/img/avatar31-sm.jpg';
            // }
            // else {
            //     pic = URL+'/img/avatar31-sm.jpg';
            // }
            pic = URL+'/img/avatar31-sm.jpg';

            // get formatted address to show
            var url = 'https://maps.googleapis.com/maps/api/geocode/json?place_id='+response[o].place_id+'&key=AIzaSyDl9W3SYUq9zeRwCAWPuJwGXXsJ72vHajQ';

            var content = '<div class="panel panel-success mapinfo" data-self="'+pos+'" data-target="'+origin+'">'+
                '<div class="panel-body">'+
                    '<div class="row">'+
                        '<div class="col-md-4 col-sm-12 mapinfo_img">'+
                            '<img src="'+pic+'" alt="'+name+'" class="img-responsive">'+
                        '</div>'+
                        '<div class="col-md-8 col-sm-12 mapinfo_desc" data-target='+url+'>'+
                            '<h5>'+name+'</h5>'+
                            '<p class="mapinfo_address"></p>'+
                            '<h5><span class="mapinfo_dis"></span></h5>'+
                        '</div>'+
                    '</div>'+
                '</div>';
            // set marker properties
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(lat,lng),
                name:name,
                content: content,
                map: map,
                icon : icon
            });
            bounds.extend(marker.getPosition());
            // set info window properties
            google.maps.event.addListener(marker, 'click', function(e){
                infowindow.setContent(this.content); //this.name
                infowindow.open(map, this);
            }.bind(marker));

            google.maps.event.addListener(infowindow, 'domready', function() {
                $('.gm-style-iw').prev().addClass('mapinfo_bk');
                $('div.mapinfo_bk > div:nth-child(2), div.mapinfo_bk > div:nth-child(4)').addClass('mapinfo_bk_ch');
                $('div.mapinfo_bk > div:nth-child(3) > div > div').css('background','rgba(57, 158, 236, 0.8)');
                $('.gm-style-iw').next().css({'border-radius':'50%', 'opacity':1});
                getFormattedAddress($('.mapinfo_desc').attr('data-target'));
                var d = getDistance($('.mapinfo').attr('data-self'), $('.mapinfo').attr('data-target'));
                $('.mapinfo_dis').text(d+' kms');
            }.bind(marker));
        }
        map.setCenter(bounds.getCenter());
        map.fitBounds(bounds);
        map.setZoom(map.getZoom()-1);
    }

    // function to get formatted address
    function getFormattedAddress(url) {
        $.getJSON(url).then(function (data) {
            if(data.status == "OK") {
                $('.mapinfo_address').text(data.results[0].formatted_address);
            }
            else {
                $('.mapinfo_address').text('');
            }   
        });
    }

    // calculate distance b/w 2 points
    function rad(x) {
      return x * Math.PI / 180;
    }

    function getDistance(pos, origin) {
        pos = pos.split(',');
        origin = origin.split(',');
        var p1 = new google.maps.LatLng(pos[0], pos[1]);
        var p2 = new google.maps.LatLng(origin[0], origin[1]);
        var R = 6378137;
        var dLat = rad(p2.lat() - p1.lat());
        var dLong = rad(p2.lng() - p1.lng());
        var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
        Math.cos(rad(p1.lat())) * Math.cos(rad(p2.lat())) *
        Math.sin(dLong / 2) * Math.sin(dLong / 2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        var d = R * c;
        return (d/1000).toFixed(2); // returns the distance in meter
    }

    // direct upload media files for post
    function uploadlocationscenes() {
        var formData = new FormData();
        var valid = 0;
        formData.append('target', $('.l_id').attr('data-target'));
        formData.append('caption', $('#scenes_caption').val());
        for (var i = 0; i < currentscenes.length; i++) {
            if ($.inArray(currentscenes[i].type, validFileTypes) >= 0) {
                formData.append('photos[]', currentscenes[i]);
                valid++;
            }
        }
        
        if(valid <= 0) {
            $.growl.error({title: 'Error' ,message: 'Please upload at least one scene' });
            return;
        }
        
        $('.loader').removeClass('hidden');
        $('.upload_scenes_form').addClass('hidden');
        // upload photos
        $.ajax({
            url: URL + "/user/locations/scenes/upload",
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
                    $('#location_scenese').val(null);
                    currentscenes = [];
                    $.growl.notice({title: 'Success' ,message: data.message });
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                }
                else {
                    $('.loader').addClass('hidden');
                    $('.upload_scenes_form').removeClass('hidden');
                    $.growl.error({title: 'Error' ,message: data.message });
                }
            }
        });
    }

    // store current media files for location scenes
    function storescenesmedia(files) {
        var totalFiles = files.files.length;
        var currentIndex = currentscenes.length;
        for (var i = 0; i < totalFiles; i++) {
            if ($.inArray(files.files[i].type, validFileTypes) >= 0) {
                currentscenes[currentscenes.length] = files.files[i];

                // preview section
                var fileReader = new FileReader();
                fileReader.onload = function(file){
                    $('.scenes-previews').append('<span class="file-item"><i class="fa fa-times scene-inner-file-item" data-target="'+currentIndex+'"></i><img style="width:100px;height: 100px;margin: 5px 5px 5px 0px;border-radius:3px;" src='+file.target.result+' /><span>');
                    currentIndex = currentIndex+1;
                };
                fileReader.readAsDataURL(files.files[i]);
            }
        }
        $('#location_scenes').val(null);
    }

    // share on facebook
    $(document).ready(function() {
        $(document).on('click', '#fb-share', function(){
            var number = Math.floor(Math.random()*100000);
            shareUrl = 'https://www.facebook.com/sharer/sharer.php?u='+window.location.href;
            var shareLink = window.location.href;
            
            FB.ui({
              method: 'feed',
              link: shareLink,
              caption: '{{$location->location_name}}',
              source: '{{ (count($location->images) > 0) ? $location->images[0]->location_image_url : "" }}',
              description: '{{$location->location_description}}'
            }, function(response){});
            // window.open(shareUrl, 'Share on Facebook','width=700,height=350,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');
        });

        $(document).on('click', '#ldin-share', function(){
            //params
            var params = {
                url : window.location.href,
                title : "{{$location->location_name}}",
                summary : "<?php echo $location->location_description; ?>",
                source : window.location.href,
                mini : true
            }

            shareURL = 'https://www.linkedin.com/shareArticle?';
            for(var prop in params) shareURL += '&' + prop + '=' + encodeURIComponent(params[prop]);
            window.open(shareURL,'Share on LinkedIn','width=700,height=350,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');
        });

        $(document).on('click', '#tw-share', function(){
            //var shareURL = "http://twitter.com/share?"; //url base
            var shareURL = "https://twitter.com/intent/tweet?";

              //params
              var params = {
                url: window.location.href, 
                text: "<?php echo $location->location_description; ?>",
                hashtags: "{{$location->search_tags}}"
              }
            for(var prop in params) shareURL += '&' + prop + '=' + encodeURIComponent(params[prop]);
            window.open(shareURL, '', 'left=0,top=0,width=550,height=450,personalbar=0,toolbar=0,scrollbars=0,resizable=0');
        });

        $('[data-toggle="tooltip"]').tooltip();

        // init light galery
        @if(count($location->images) > 0)
            initLightGallery('.scenes-blocks', '.scenes-photo');
        @endif

        // init vr video
        @if(!empty($location->location_ar_view))
            var player;
            (function(){
                player = videojs( '#video-container', {
                    techOrder: ['html5']
                });
                player.vr({projection: "360"});

                player.on('fullscreenchange', function(){
                    console.log('fullscreen triggered');
                    if (player.isFullscreen()) {
                        player.exitFullscreen();
                    }
                });

            })();

            $('body').on('hidden.bs.modal', '#arvideo', function () {
                $('video').trigger('pause');
            });
        @endif
    });

    function initLightGallery(container, selector) {
        $(container).lightGallery({
            selector : selector
        });
    }
</script>
@endif
@endsection