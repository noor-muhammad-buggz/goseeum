@php
$pageTitle = 'Search Locations';
@endphp
@extends('layouts.user')
@section('content')

@if(isset($_GET['location']) && count($locations) > 0)
<div class="a_radius">
	{{-- <p onclick="$('.a_radius_form').toggle()">Radius <i class="fa fa-chevron-down"></i></p> --}}
	<div class="a_radius_form">
		<select class="a_radius_elem">
			<option value="<= 5" {{($radius == '<= 5') ? 'selected' : ''}}>5 kms</option>
			<option value="<= 10" {{($radius == '<= 10') ? 'selected' : ''}}>10 kms</option>
			<option value="<= 15" {{($radius == '<= 15') ? 'selected' : ''}}>15 kms</option>
			<option value="<= 20" {{($radius == '<= 20') ? 'selected' : ''}}>20 kms</option>
			<option value="<= 50" {{($radius == '<= 50') ? 'selected' : ''}}>50 kms</option>
			<option value="<= 100" {{($radius == '<= 100') ? 'selected' : ''}}>100 kms</option>
			<option value=">=0" {{($radius == '>= 0') ? 'selected' : ''}}> Any</option>
		</select>
		<input type="hidden" id="qparam1" value="{{$_GET['subject']}}">
		<input type="hidden" id="qparam2" value="{{$_GET['location']}}">
	</div>
</div>
<div class="container-fluid a_loc_search">
	<div class="row">
		<div class="col-md-12">
            <div id="locations" style="width: 100%; height: 90vh;"></div>
        </div>
	</div>
</div>

<!-- ... start loader popup -->
<div class="modal" id="loader-mod">
    <div class="modal-dialog modal-sm ui-block" style="width: 70px;top: 40vh;">
        <div class="m-auto padding-20 text-center">
            <img src="{{ asset('img/loader.gif') }}" alt="please wait...">
        </div>
    </div>
</div>
<!-- ... end loader popup -->
@else
<div class="a_location">
	<div class="content-bg"></div>
</div>

<div class="container">
	<div class="row display-flex mt100">
		@if(isset($_GET['location']))
			<div class="m-auto col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12">
				<div class="alert alert-danger">
					<div class="text-center">No locations found</div>
				</div>
			</div>
		@endif
	</div>

	<div class="row display-flex">
		<div class="m-auto col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12">
			<!-- Login-Registration Form  -->
			<div class="registration-login-form">
				<div class="title h6">Search locations</div>
				<form method="GET" action="" class="content form-horizontal">
					<div class="row">
						<div class="col-xl-5 col-lg-5 col-md-5 col-sm-12">
							<div class="form-group  is-empty">
								<label class="control-label">Subject</label>
								<input type="text" class="form-control{{ $errors->has('subject') ? ' is-invalid' : '' }}" name="subject" value="{{ (isset($_GET['subject'])) ? $_GET['subject'] : '' }}" autofocus>
							</div>
						</div>
						<div class="col-xl-5 col-lg-5 col-md-5 col-sm-12">
							<div class="form-group is-empty">
								<label class="control-label">Location</label>
								<select class="form-control{{ $errors->has('location') ? ' is-invalid' : '' }}" name="location">
									@foreach($cities as $city)
									<option value="{{$city->city_name.'-'.$city->city_id}}" {{ (isset($_GET['location']) && $_GET['location'] == $city->city_name.'-'.$city->city_id) ? 'selected' : '' }}>{{ucfirst($city->city_name)}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="col-xl-2 col-lg-2 col-md-2 col-sm-12">
							<div class="form-group">
								<label class="control-label"><span style="visibility: hidden;">Search</span></label>
								<button type="submit" class="btn btn-orange">GO</button>
							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- ... end Login-Registration Form  -->		
		</div>
	</div>
</div>
@endif

@if(count($locations) > 0)
@php
$markers = urldecode(json_encode($locations));
$minmax = json_encode($params);
@endphp
<script src="https://maps.googleapis.com/maps/api/js?key=
AIzaSyDl9W3SYUq9zeRwCAWPuJwGXXsJ72vHajQ
"></script>
<script type="text/javascript">
	var markers = '<?php echo $markers; ?>';
	var params = '<?php echo $minmax; ?>';
	window.address = '';
    DisplayMarkers(markers, params);
	// function to dispay markers
    function DisplayMarkers(markers, params) {
        var json = JSON.parse(markers);
        var minLat = parseFloat(params.minLat);
        var maxLat = parseFloat(params.maxLat);
        var minLng = parseFloat(params.minLng);
        var maxLng = parseFloat(params.maxLng);
        
        centerLat = (minLat+maxLat)/2.0;
        centerLng = (minLng+maxLng)/2.0;

        var latlng = new google.maps.LatLng(centerLat, centerLng);
        var myOptions = {
            zoom: 13,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            //ROADMAP,SATELLITE,HYBRID,TERRAIN 
            mapTypeControl: true
        };

        var map = new google.maps.Map(document.getElementById("locations"),myOptions);
        var geocoder = new google.maps.Geocoder();
        var infowindow = new google.maps.InfoWindow(), marker, lat, lng;
        var bounds = new google.maps.LatLngBounds();

        for(var o in json) {
            lat = json[o].location_lat;
            lng = json[o].location_lang;
            name = json[o].location_name;
            distance = parseFloat(json[o].distance).toFixed(1)+' <span>miles</span>';
            link = URL+"/user/locations/"+encodeURIComponent(json[o].location_name.split(" ").join("_").toLowerCase())+'_'+json[o].id

            if(json[o].google_place_id != '' && json[o].google_place_id != null) {
                link += '/'+json[o].google_place_id;
            }
            
            if(json[o].images.length > 0) {
            	pic = URL+'/uploads/'+json[o].images[0].location_image_url;
            }
            else {
            	pic = URL+'/img/avatar31-sm.jpg';
            }

            // get formatted address to show
            var url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='+json[o].location_lat+','+json[o].location_lang+'&key=AIzaSyDl9W3SYUq9zeRwCAWPuJwGXXsJ72vHajQ';

            var content = '<div class="panel panel-success mapinfo">'+
				'<div class="panel-body">'+
					'<div class="row">'+
						'<div class="col-md-4 col-sm-12 mapinfo_img">'+
							'<img src="'+pic+'" alt="'+name+'" class="img-responsive">'+
						'</div>'+
						'<div class="col-md-8 col-sm-12 mapinfo_desc" data-target='+url+'>'+
							'<h5>'+name+'</h5>'+
							'<p class="mapinfo_address">'+json[o].location_address+'</p>'+
							'<h5>'+distance+
							'<a href="'+link+'"><i class="fa fa-chevron-right"></i></a></h5>'+
						'</div>'+
					'</div>'+
				'</div>';
            // set marker properties
            var iconFile = "<?php echo url('img/marker.png') ?>";
            marker = new google.maps.Marker({
                position: new google.maps.LatLng(lat,lng),
                name:name,
                content: content,
                map: map,
                icon: {url:iconFile, scaledSize: new google.maps.Size(35, 45)}
            });
            bounds.extend(marker.getPosition());
            // set info window properties
            google.maps.event.addListener(marker, 'click', function(e){
                infowindow.setContent(this.content); //this.name
                infowindow.open(map, this);
            }.bind(marker));

            google.maps.event.addListener(infowindow, 'domready', function() {
                // $('.gm-style-iw').prev().addClass('mapinfo_bk');
                $('.gm-style-iw').addClass('mapinfo_bk');
			    $('.mapinfo_bk').addClass('mapinfo_bk_ch');
			    $('div.mapinfo_bk > div:nth-child(2), div.mapinfo_bk > div:nth-child(4)').addClass('mapinfo_bk_ch');
			    $('div.mapinfo_bk > div:nth-child(3) > div > div').css('background','rgba(57, 158, 236, 0.8)');
                $('.mapinfo_bk').children(1).css({'overflow' : 'hidden'});
			    $('.gm-style-iw').next().css({'border-radius':'50%', 'opacity':1});
			}.bind(marker));
        }
        map.setCenter(bounds.getCenter());
        // map.fitBounds(bounds);
        // map.setZoom(map.getZoom()-1);
    }

    $(document).ready(function(){
    	$(document).on('change', '.a_radius_elem', function(){
    		$('#loader-mod').modal('show');
    		var _self = $(this);
    		var formData = new FormData();
            formData.append('subject', $('#qparam1').val());
            formData.append('location', $('#qparam2').val());
            formData.append('radius', _self.val());
            // create comment
            $.ajax({
                url: URL + "/user/radiussearch/locations",
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
                    DisplayMarkers(data.markers, data.minmax);
                }
            });
    	});
    });
    
</script>
@endif
@endsection