@php
$pageTitle = 'Cities';
@endphp
@extends('layouts.admin')
<style type="text/css">
.pull-right {
    float: right;
}
.padding3{
    padding:3px;
}
</style>
@section('content')
<div class="container">
    <div class="row">
        <div class="ui-block-title">
            <h4 class="title">Cities</h4>
            <a href="{{url('cities/add')}}" class="btn btn-orange pull-right"><i class="fa fa-plus"> </i> Add City</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-xl-12 order-xl-2 col-lg-12 order-lg-2 col-md-12 order-md-1 col-sm-12 col-xs-12">
            <div class="ui-block">
                <div class="ui-block-title">
                    <h6 class="title">Cities</h6>
                </div>
                <div class="ui-block-content table-responsive">
                    @include('errors.form_errors')
                    <table class="table">
                        <thead>
                            <th width="">Sr#</th>
                            <th width="">Name</th>
                            <th width="">Latitude</th>
                            <th width="">Longitude</th>
                            <th width="">Action</th>
                        </thead>
                        <tbody>
                        @if (!$cities->isEmpty())
                            @foreach($cities as $city)
                                <tr>
                                    <td>{{$city->city_id}}</td>
                                    <td>{{$city->city_name}}</td>
                                    <td>{{$city->city_lat}}</td>
                                    <td>{{$city->city_lang}}</td>
                                    <td>
                                        <a class="btn btn-orange" href="{{url('cities/edit/'.$city->city_id)}}" title="Edit City"><span class="fa fa-pencil-alt"></span></a>
                                        <a class="btn btn-danger del-city" href="javascript:;" data-href="{{url('cities/delete/'.$city->city_id)}}" title="Delete City"><span class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="text-center">
                                <td colspan="5">No cities found yet</td>
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
@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        /*
        |----------------------------------------------------------------------
        | confirm delete before perform
        |----------------------------------------------------------------------
        */
        $(document).on('click', '.del-city', function(event){
            event.preventDefault();
            var url = $(this).attr('data-href');
            swal({
                title: "Are you sure?",
                text: "You want to delete selected city",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: 'Yes sure!',
                cancelButtonText: "No cancel",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(flag){
                if(flag){
                    location.href = url;
                }
            });
        });

    });
</script>
@endsection
