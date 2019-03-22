@php
$pageTitle = ($type == 'edit') ? 'Edit City' : 'Add City';
@endphp
@extends('layouts.admin')
@section('content')
<div class="container">
    <div class="row">
        <div class="ui-block-title">
            <h4 class="title">{{ucfirst($type)}} City</h4>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-xl-12 order-xl-2 col-lg-12 order-lg-2 col-md-12 order-md-1 col-sm-12 col-xs-12">
            <div class="ui-block">
                <div class="ui-block-title">
                    <h6 class="title">{{ucfirst($type)}} City</h6>
                </div>
                <div class="ui-block-content">
                    <!-- Change Password Form -->
                    <form action="{{url('cities/save')}}" method="post">
                        {{csrf_field()}}
                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group is-empty">
                                    <label for="city_name" class="control-label">Name</label>
                                    <input type="text" id="city_autocomplete" name="city_name" class="form-control{{ $errors->has('city_name') ? ' is-invalid' : '' }}" value="{{($type == 'edit') ? old('city_name') ?: $city->city_name : old('city_name')}}">
                                    @if ($errors->has('city_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('city_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group is-empty">
                                    <label for="city_lat" class="control-label">Latitude</label>
                                    <input type="text" name="city_lat" class="form-control{{ $errors->has('city_lat') ? ' is-invalid' : '' }}" value="{{($type == 'edit') ? old('city_lat') ?: $city->city_lat : old('city_lat')}}">
                                    @if ($errors->has('city_lat'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('city_lat') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group is-empty">
                                    <label for="city_lang" class="control-label">Longitude</label>
                                    <input type="text" name="city_lang" class="form-control{{ $errors->has('city_lang') ? ' is-invalid' : '' }}" value="{{($type == 'edit') ? old('city_lang') ?: $city->city_lang : old('city_lang')}}">
                                    @if ($errors->has('city_lang'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('city_lang') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="hidden" name="id" value="{{($type == 'edit') ? $city->city_id : ''}}">
                            <input type="hidden" name="type" value="{{$type}}">
                            <button class="btn btn-orange btn-sm" type="submit">Save</button>
                            <a class="btn btn-danger btn-sm" href="{{url('cities')}}">Back</a>
                        </div>

                    </div>
                </form>

                <!-- ... end Change Password Form -->
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?libraries=places&key=
AIzaSyDl9W3SYUq9zeRwCAWPuJwGXXsJ72vHajQ
"></script>
<script src="{{ asset('js/admin-scripts.js') }}" defer></script>
<script type="text/javascript">
    $(document).ready(function(){

        // initialize google autocomplete for location name
        function initialize() {
            var options = {
                types: ['(cities)'],
                // componentRestrictions: {country: "us"}
            };
            var input = document.getElementById('city_autocomplete');
            var autocomplete = new google.maps.places.Autocomplete(input, options);
            google.maps.event.addListener(autocomplete, 'place_changed', function(){
                place = autocomplete.getPlace();
                $('input[name=city_name]').val(place.name);
                $('input[name=city_lat]').val(place.geometry.location.lat());
                $('input[name=city_lang]').val(place.geometry.location.lng());
            });
        }
        google.maps.event.addDomListener(window, 'load', initialize);

    });
</script>
@endsection
