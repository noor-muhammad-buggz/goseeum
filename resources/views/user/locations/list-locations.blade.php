@php
$pageTitle = 'Locations';
@endphp
@extends('layouts.user')
<style type="text/css">
.pull-right {
    float: right;
}
.padding3{
    padding:3px;
}
a.btn.btn-orange, a.btn.btn-danger.del-location {
    padding: 5px 7px !important;
}
</style>
@section('content')

<div class="container">
    <div class="row">
        <div class="ui-block-title">
            <h4 class="title">Locations</h4>
            <a href="{{url('user/location/add')}}" class="btn btn-orange pull-right"><i class="fa fa-plus"> </i> Add Location</a>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-xl-12 order-xl-2 col-lg-12 order-lg-2 col-md-12 order-md-1 col-sm-12 col-xs-12">
            <div class="ui-block">
                <div class="ui-block-title">
                    <h6 class="title">Locations</h6>
                </div>
                <div class="ui-block-content table-responsive">
                    @include('errors.form_errors')
                    <table class="table">
                        <thead>
                            <th>Sr#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Status</th>
                            <th>Action</th>
                        </thead>
                        <tbody>
                        @if (!$locations->isEmpty())
                            @foreach($locations as $location)
                                <tr>
                                    <td>{{$location->id}}</td>
                                    <td>
                                        @if(count($location->images) > 0)
                                            <img style="width: 35px;height: 35px;border-radius: 5px;" src="{{asset('uploads/'.$location->images[0]->location_image_url)}}" />
                                        @else
                                            No Image
                                        @endif
                                    </td>
                                    <td>{{$location->location_name}}</td>
                                    <td>{{$location->location_lat}}</td>
                                    <td>{{$location->location_lang}}</td>
                                    <td>
                                        @if ($location->status == 0)
                                            <small class="bg-orange c-white padding3">Pending</small>
                                        @elseif ($location->status == 1)
                                            <small class="bg-success c-white padding3 ">Approved</small>
                                        @elseif ($location->status == 2)
                                            <small class="bg-danger c-white padding3 ">Rejected <i class="fa fa-info-circle show-reason" data-reason="{{$location->reject_reason}}" style="cursor:pointer;"></i></small>
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-orange" href="{{url('user/location/edit/'.$location->id)}}" title="Edit Location"><span class="fa fa-pencil-alt"></span></a>
                                        <a class="btn btn-danger del-location" href="javascript:;" data-href="{{url('user/location/delete/'.$location->id)}}" title="Delete Location"><span class="fa fa-trash"></span></a>
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

<!-- display reject reason popup -->
<div class="modal fade" id="reject-reason-modal">
    <div class="modal-dialog ui-block window-popup update-header-photo">
        <a href="javascript:;" class="close icon-close" data-dismiss="modal" aria-label="Close">
            <svg class="olymp-close-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-close-icon') }}"></use></svg>
        </a>
        <div class="ui-block-title">
            <h6 class="title">Reject Reason</h6>
        </div>
        <div class="ui-block border-0">
            <div class="col-md-12">
                <p class="reject-reason-container"></p>
            </div>
        </div>
    </div>
</div>
<!-- ... end popup for add reject reason -->

@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        /*
        |----------------------------------------------------------------------
        | display reject reason
        |----------------------------------------------------------------------
        */
        $(document).on('click touchstart', '.show-reason', function(event) {
            $('.reject-reason-container').text($(this).attr('data-reason'));
            $('#reject-reason-modal').modal('show');
        });
        /*
        |----------------------------------------------------------------------
        | confirm delete before perform
        |----------------------------------------------------------------------
        */
        $(document).on('click', '.del-location', function(event){
            event.preventDefault();
            var url = $(this).attr('data-href');
            swal({
                title: "Are you sure?",
                text: "You want to delete selected location",
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