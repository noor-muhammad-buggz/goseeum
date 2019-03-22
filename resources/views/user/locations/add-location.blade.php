@php
$pageTitle = ($type == 'edit') ? 'Edit Location' : 'Add Location';
@endphp
@extends('layouts.user')
<style type="text/css">
span.file-item{
    width: auto;
    height: auto;
    display: inline-block;
}

span.file-item .loc-image, p.ar_preview .inner_ar_preview{
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

p.ar_preview {
    width: auto;
    border: 1px solid #e8a93f;
    background: #e8a93f;
    margin-top: 10px;
    color: white;
    border-radius: 3px;
    padding: 5px 0px 5px 15px;
}
</style>
@section('content')
<div class="container">
    <div class="row">
        <div class="ui-block-title">
            <h4 class="title">{{ucfirst($type)}} Location</h4>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-xl-12 order-xl-2 col-lg-12 order-lg-2 col-md-12 order-md-1 col-sm-12 col-xs-12">
            <div class="ui-block">
                <div class="ui-block-title">
                    <h6 class="title">{{ucfirst($type)}} Location</h6>
                </div>
                <div class="ui-block-content">
                    <!-- Change Password Form -->
                    <form action="{{url('user/location/save')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <input type="hidden" name="google_place_id" value="{{($type == 'edit') ? (old('google_place_id') ?: $location->google_place_id) : (old('google_place_id') ?: '') }}">
                        <div class="row">
                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group is-empty">
                                    <label for="location_name" class="control-label">Name</label>
                                    <input type="text" id="loc_autocomplete" name="location_name" class="form-control{{ $errors->has('location_name') ? ' is-invalid' : '' }}" value="{{($type == 'edit') ? old('location_name') ?: $location->location_name : old('location_name')}}">
                                    @if ($errors->has('location_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('location_name') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group is-empty">
                                    <label for="location_tags" class="control-label">Tags <code class="text-info pull-right">(comma seperated words)</code></label>
                                    <input type="text" name="location_tags" class="form-control{{ $errors->has('location_tags') ? ' is-invalid' : '' }}" value="{{($type == 'edit') ? old('location_tags') ?: $location->location_tags : old('location_tags')}}">
                                    @if ($errors->has('location_tags'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('location_tags') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group is-empty">
                                    <label for="location_lat" class="control-label">Latitude</label>
                                    <input type="text" name="location_lat" class="form-control{{ $errors->has('location_lat') ? ' is-invalid' : '' }}" value="{{($type == 'edit') ? old('location_lat') ?: $location->location_lat : old('location_lat')}}">
                                    @if ($errors->has('location_lat'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('location_lat') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group is-empty">
                                    <label for="location_lang" class="control-label">Longitude</label>
                                    <input type="text" name="location_lang" class="form-control{{ $errors->has('location_lang') ? ' is-invalid' : '' }}" value="{{($type == 'edit') ? old('location_lang') ?: $location->location_lang : old('location_lang')}}">
                                    @if ($errors->has('location_lang'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('location_lang') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group is-empty">
                                    <label for="location_photos" class="control-label">Photos</label>
                                    @if($type == 'add')
                                    <input type="file" accept=".jpeg, .png, .jpg" multiple="true" onchange="uploadandpreview(this)" name="location_photos[]" class="form-control{{ $errors->has('location_photos') ? ' is-invalid' : '' }}">
                                    @else
                                    <input type="file" accept=".jpeg, .png, .jpg" multiple="true" onchange="directupload(this)" class="form-control">
                                    @endif
                                    @if ($errors->has('location_photos'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('location_photos') }}</strong>
                                    </span>
                                    @endif
                                    <p class="file-error text-danger"></p>
                                    <p class="file-success text-success"></p>
                                    <p class="file-previews">
                                        @if($type=='edit' && count($location->images) > 0)
                                        @foreach($location->images as $locimg)
                                        <span class="file-item">
                                            <i class="fa fa-times loc-image" data-target='{{$locimg->id}}'></i>
                                            <img style="width: 60px;height: 60px;margin: 5px;border-radius: 3px;" src="{{asset('uploads/'.$locimg->location_image_url)}}" />
                                        </span>
                                        @endforeach
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="form-group is-empty">
                                 <label for="location_ar_view" class="control-label">AR View</label>
                                 <input type="file" name="location_ar_view" class="form-control{{ $errors->has('location_ar_view') ? ' is-invalid' : '' }}"  onChange="uploadandpreviewarfile(this)">
                                 @if ($errors->has('location_ar_view'))
                                 <span class="invalid-feedback">
                                    <strong>{{ $errors->first('location_ar_view') }}</strong>
                                </span>
                                @endif

                                @if($type == 'edit' && !empty($location->location_ar_view))
                                <p class="ar-error text-danger"></p>
                                <p class="ar-success text-success"></p>
                                <p class="ar_preview">
                                    <i class="fa fa-times inner_ar_preview" data-target=''></i>
                                    {{ $location->location_ar_view }}
                                </p>
                                @endif 
                            </div>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group is-empty">
                                <label for="location_address" class="control-label">Address</label>
                                <input type="text" name="location_address" class="form-control{{ $errors->has('location_address') ? ' is-invalid' : '' }}" value="{{($type == 'edit') ? old('location_address') ?: $location->location_address : old('location_address')}}">
                                @if ($errors->has('location_address'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('location_address') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group is-empty">
                                <label for="location_description" class="control-label">Description</label>
                                <textarea name="location_description" class="form-control{{ $errors->has('location_description') ? ' is-invalid' : '' }}" rows="5">{{($type == 'edit') ? old('location_description') ?: $location->location_description : old('location_description')}}</textarea>
                                @if ($errors->has('location_description'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('location_description') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="form-group is-empty">
                                <label for="location_type" class="control-label">Type</label>
                                <select name="location_type" class="form-control selectpicker{{ $errors->has('location_type') ? ' is-invalid' : '' }}">
                                    <option value="historical" {{($type == 'edit') ? ((old('location_type') && old('location_type') == 'historical') ? 'selected' : ($location->location_type && $location->location_type == 'historical') ? 'selected' : '' ) : (old('location_type') && old('location_type') == 'historical') ? 'selected' : '' }}>Historical</option>
                                    <option value="buisness" {{($type == 'edit') ? ((old('location_type') && old('location_type') == 'buisness') ? 'selected' : ($location->location_type && $location->location_type == 'buisness') ? 'selected' : '' ) : (old('location_type') && old('location_type') == 'buisness') ? 'selected' : '' }}>Buisness</option>
                                </select>
                                @if ($errors->has('location_type'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('location_type') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 hidden" id="hours">
                            <div class="form-group is-empty">
                                <table class="table hours_table">
                                    <thead>
                                        <tr>
                                            <td>
                                                <label class="control-label">Day</label>
                                            </td>
                                            <td>
                                                <label class="control-label">Opening Time</label>
                                            </td>
                                            <td>
                                                <label class="control-label">Closing Time</label>
                                            </td>
                                            <td>
                                                <label class="control-label">Holiday</label>
                                            </td>
                                        </tr>
                                    </thead>                                        
                                    @foreach($days as $day => $name)
                                    <tr>
                                        <td>
                                            <label class="control-label" style="margin-top: 50%;">{{$name}}</label>
                                        </td>
                                        <td>
                                            @php
                                            if($type == 'edit')
                                            $locday = $location->getday($location->id, $day);
                                            @endphp
                                            <input type="text" class="form-control open_time" name="hours[{{$day}}][open]" value="{{ ($type == 'edit') ? ((!empty($locday) && $locday->lh_is_holiday == 0) ? $locday->lh_open : '') : ''}}">
                                        </td>
                                        <td>
                                            <input type="text" class="form-control close_time" name="hours[{{$day}}][close]" value="{{ ($type == 'edit') ? ((!empty($locday) && $locday->lh_is_holiday == 0) ? $locday->lh_close : '') : ''}}">
                                        </td>
                                        <td>
                                            <div class="togglebutton" style="margin-top: 15%;">
                                                <label>
                                                    <input type="checkbox" {{ ($type == 'edit') ? ((!empty($locday) && $locday->lh_is_holiday == 1) ? 'checked' : '') : ''}} name="hours[{{$day}}][holiday]" value="1">
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </table>
                            </div>
                        </div>

                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="hidden" name="id" value="{{($type == 'edit') ? $location->id : ''}}">
                            <input type="hidden" name="type" value="{{$type}}">
                            <button class="btn btn-orange btn-sm" type="submit">Save</button>
                            <a class="btn btn-danger btn-sm" href="{{url('user/locations')}}">Back</a>
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
<script src="{{ asset('js/user-scripts.js') }}" defer></script>
<script type="text/javascript">
    $(document).ready(function(){

        if($('select[name=location_type]').val() == 'historical') {
                $('#hours').hide();
        }
        else if($('select[name=location_type]').val() == 'buisness') {
            $('#hours').show();
        }

        $(document).on('change', 'select[name=location_type]', function(){
            if($(this).val() == 'historical') {
                $('#hours').hide();
            }
            else if($(this).val() == 'buisness') {
                $('#hours').show();
            }
        });

        // initilize timepickers
        $(function(){
            $( ".open_time" ).datetimepicker({
                format: 'hh:mm A',
                sideBySide: true,
                keepOpen : true,
                widgetPositioning: {
                    horizontal: 'right',
                    vertical: 'bottom'
                }
            });

            $( ".close_time" ).datetimepicker({
                format: 'hh:mm A',
                sideBySide: true,
                keepOpen : true,
                widgetPositioning: {
                    horizontal: 'right',
                    vertical: 'bottom'
                }
            });
        });

        // initialize google autocomplete for location name
        function initialize() {
            var options = {
                // types: ['(cities)'],
                // componentRestrictions: {country: "us"}
            };
            var input = document.getElementById('loc_autocomplete');
            var autocomplete = new google.maps.places.Autocomplete(input, options);
            google.maps.event.addListener(autocomplete, 'place_changed', function(){
                place = autocomplete.getPlace();
                console.log(place);
                $('input[name=location_name]').val(place.name);
                $('input[name=google_place_id]').val(place.place_id);
                $('input[name=location_lat]').val(place.geometry.location.lat());
                $('input[name=location_lang]').val(place.geometry.location.lng());
                $('input[name=location_address]').val(place.formatted_address);
            });
        }
        google.maps.event.addDomListener(window, 'load', initialize);

    });

    /*
    |----------------------------------------------------------------------
    | locations section here
    |----------------------------------------------------------------------
    */

    function uploadandpreviewarfile(files) {
        var validFileTypes = ["video/mp4", "video/flv", "video/wvm"];
        var totalFiles = files.files.length;
        var invalidcount = 0;
        for (var i = 0; i < totalFiles; i++) {
            if ($.inArray(files.files[i].type, validFileTypes) === -1) {
                $('input[name=location_ar_view]').val(null);
                $.growl.error({title : 'Warning!', message: 'Please upload only video files'});
                return;
            }
        }
    }
</script>
@endsection
