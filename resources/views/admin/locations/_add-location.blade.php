@extends('layouts.app')
<style type="text/css">
span.file-item{
    width: auto;
    height: auto;
    display: inline-block;
}

span.file-item .inner-file-item, p.ar_preview .inner_ar_preview{
    float: right;
    width: 15px;
    height: 15px;
    top: 2px;
    position: relative;
    background: black;
    color: #fff;
    border-radius: 50%;
    left: -16px;
    cursor: pointer;
}

p.ar_preview {
    width: 40%;
    border: 1px solid #c5c5c5;
    background: #c5c5c5;
    margin-top: 10px;
    color: white;
    border-radius: 3px;
    padding: 5px 0px 5px 15px;
}
</style>
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ucfirst($type)}} Location</div>

                <div class="card-body">
                    @include('errors.form_errors')
                    <form class="form" action="{{url('locations/save')}}" method="post" enctype="multipart/form-data">
                        {{csrf_field()}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="location_name">Name</label>
                                <input type="text" name="location_name" class="form-control{{ $errors->has('location_name') ? ' is-invalid' : '' }}" value="{{($type == 'edit') ? old('location_name') ?: $location->location_name : old('location_name')}}">
                                @if ($errors->has('location_name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('location_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="location_lat">Latitude</label>
                                <input type="text" name="location_lat" class="form-control{{ $errors->has('location_lat') ? ' is-invalid' : '' }}" value="{{($type == 'edit') ? old('location_lat') ?: $location->location_lat : old('location_lat')}}">
                                @if ($errors->has('location_lat'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('location_lat') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="location_lang">Longitude</label>
                                <input type="text" name="location_lang" class="form-control{{ $errors->has('location_lang') ? ' is-invalid' : '' }}" value="{{($type == 'edit') ? old('location_lang') ?: $location->location_lang : old('location_lang')}}">
                                @if ($errors->has('location_lang'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('location_lang') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="location_photos">Photos</label>
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
                                                <i class="fa fa-times inner-file-item" data-target='{{$locimg->id}}'></i>
                                                <img style="width: 70px;height: 70px;margin: 5px;border: 3px solid #c5c5c5;border-radius: 3px;" src="{{asset('uploads/'.$locimg->location_image_url)}}" />
                                            </span>
                                        @endforeach
                                    @endif
                                </p>
                            </div>
                            <div class="form-group">
                                <label for="location_ar_view">AR View</label>
                                <input type="file" name="location_ar_view" class="form-control{{ $errors->has('location_ar_view') ? ' is-invalid' : '' }}">
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
                            <div class="form-group">
                                <label for="location_description">Description</label>
                                <textarea style="    min-height: 170px;max-height: 170px;min-width: 100%;max-width: 100%;" name="location_description" class="form-control{{ $errors->has('location_description') ? ' is-invalid' : '' }}" rows="5">{{($type == 'edit') ? old('location_description') ?: $location->location_description : old('location_description')}}</textarea>
                                @if ($errors->has('location_description'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('location_description') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="id" value="{{($type == 'edit') ? $location->id : ''}}">
                                <input type="hidden" name="type" value="{{$type}}">
                                <button class="btn btn-primary" type="submit">Save</button>
                                <a class="btn btn-default" href="{{url('locations')}}">Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/jquery-3.2.1.min.js') }}" defer></script>
<script src="{{ asset('js/admin-scripts.js') }}" defer></script>
<script defer src="{{asset('fonts/fontawesome-all.js')}}" defer></script>
@endsection