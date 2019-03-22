@extends('layouts.app')
<style type="text/css">
    .alert > p {
        margin: 0px;
    }
</style>
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    Locations
                    <a class="btn btn-primary btn-sm" style="float:right;" href="{{route('locations/add')}}">Add New</a>
                </div>

                <div class="card-body">
                    @include('errors.form_errors')
                    <table class="table table-striped">
                        <thead>
                            <th>Sr#</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                        @if (!$locations->isEmpty())
                            @foreach($locations as $location)
                                <tr>
                                    <td>{{$location->id}}</td>
                                    <td>{{$location->location_name}}</td>
                                    <td>
                                        @if(count($location->images) > 0)
                                            <img style="width: 50px;height: 50px;" src="{{asset('uploads/'.$location->images[0]->first()->location_image_url)}}" />
                                        @else
                                            No Image
                                        @endif
                                    </td>
                                    <td>{{$location->location_lat}}</td>
                                    <td>{{$location->location_lang}}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm" href="{{url('locations/edit/'.$location->id)}}">Edit</a>
                                        <a class="btn btn-danger btn-sm" href="{{url('locations/delete/'.$location->id)}}">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="text-center">
                                <td colspan="6">No locations found yet</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
