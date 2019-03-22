@php
$pageTitle = 'Locations';
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
<!-- start page sidemenu responsive menu -->
<!-- <div class="profile-settings-responsive">
    <a href="#" class="js-profile-settings-open profile-settings-open">
        <i class="fa fa-angle-right" aria-hidden="true"></i>
        <i class="fa fa-angle-left" aria-hidden="true"></i>
    </a>
    <div class="mCustomScrollbar" data-mcs-theme="dark">
        <div class="ui-block">
            <div class="your-profile">
                <div class="ui-block-title ui-block-title-small">
                    <h6 class="title">Locations</h6>
                </div>
                <div class="ui-block-title">
                    <a href="{{url('locations')}}" class="h6 title">Locations List</a>
                </div>
                <div class="ui-block-title">
                    <a href="{{url('locations/add')}}" class="h6 title">Add Location</a>
                </div>
            </div>
        </div>
    </div>
</div> -->
<!-- end page sidemenu responsive menu -->

<div class="container">
    <div class="row">
        <div class="ui-block-title">
            <h4 class="title">Locations</h4>
            <a href="{{url('locations/add')}}" class="btn btn-orange pull-right"><i class="fa fa-plus"> </i> Add Location</a>
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
                            <th width="5%">Sr#</th>
                            <th width="10%">Image</th>
                            <th width="30%">Name</th>
                            <th width="5%">Latitude</th>
                            <th width="5%">Longitude</th>
                            <th width="15%">Status</th>
                            <th width="30%">Action</th>
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
                                            <small class="bg-orange c-white padding3 radius-5">Pending</small>
                                        @elseif ($location->status == 1)
                                            <small class="bg-success c-white padding3 radius-5">Approved</small>
                                        @elseif ($location->status == 2)
                                            <small class="bg-danger c-white padding3 radius-5">Rejected <i class="fa fa-info-circle show-reason" data-reason="{{$location->reject_reason}}" style="cursor:pointer;"></i></small>
                                        @endif
                                    </td>
                                    <td>
                                        @if($location->status == 0 || $location->status == 2)
                                        <a class="btn btn-success" href="{{url('locations/approve/'.$location->id)}}" title="Approve Location"><span class="fa fa-check"></span></a>
                                        @endif
                                        @if($location->status == 0)
                                        <a class="btn btn-blue reject_location" href="javascript:;" title="Reject Location" data-target="{{$location->id}}"><span class="fa fa-ban"></span> </a>
                                        @endif
                                        <a class="btn btn-orange" href="{{url('locations/edit/'.$location->id)}}" title="Edit Location"><span class="fa fa-pencil-alt"></span></a>
                                        <a class="btn btn-danger del-location" href="javascript:;" data-href="{{url('locations/delete/'.$location->id)}}" title="Delete Location"><span class="fa fa-trash"></span></a>
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

        <!-- start desktop page side menu -->
        <!-- <div class="col-xl-3 order-xl-1 col-lg-3 order-lg-1 col-md-12 order-md-2 col-sm-12 col-xs-12 responsive-display-none">
            <div class="ui-block">
                <div class="your-profile">
                    <div class="ui-block-title ui-block-title-small">
                        <h6 class="title">Locations</h6>
                    </div>
                    <div class="ui-block-title">
                        <a href="{{url('locations')}}" class="h6 title">Locations List</a>
                    </div>
                    <div class="ui-block-title">
                        <a href="{{url('locations/add')}}" class="h6 title">Add Location</a>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- end desktop page side menu -->

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

<!-- popup for add reject reason -->
<div class="modal fade" id="reject-location-modal">
    <div class="modal-dialog ui-block window-popup update-header-photo">
        <a href="javascript:;" class="close icon-close" data-dismiss="modal" aria-label="Close">
            <svg class="olymp-close-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-close-icon') }}"></use></svg>
        </a>
        <div class="ui-block-title">
            <h6 class="title">Reject Reason</h6>
        </div>
        <div class="ui-block border-0">
            <div class="loader col-md-12 padding-20 text-center hidden">
                <img src="{{ asset('img/loader.gif') }}" alt="please wait...">
            </div>
            <!-- start post form -->
            <form method="post" id="reject_location_form" action="{{url('locations/reject')}}">
                <div class="news-feed-form padding-20">
                    {{csrf_field()}}
                    <input type="hidden" name="location_id" />
                    <div class="form-group">
                        <label class="control-label">Reason</label>
                        <textarea class="form-control" name="reject_reason" style="width:100%;height:70px;resize:none;"></textarea>
                        <span class="text-danger reject_reason_error"></span>
                    </div>
                    <div class="add-options-message">
                        <button class="btn btn-orange">Save</button>
                    </div>
                </div>
            </form>
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
        | location reject reason
        |----------------------------------------------------------------------
        */
        $(document).on('click touchstart', '.reject_location', function(event) {
            $('input[name=location_id]').val($(this).attr('data-target'));
            $('#reject-location-modal').modal('show');
        });
        /*
        |----------------------------------------------------------------------
        | on reject form submit
        |----------------------------------------------------------------------
        */
        $(document).on('submit', '#reject_location_form', function(event) {
            if($('textarea[name=reject_reason]').val().length <= 0) {
                event.preventDefault();
                $('.reject_reason_error').text('Please enter reason for rejection');
            }
        });

        $(document).on('keyup', 'textarea[name=reject_reason]', function(event) {
            $('.reject_reason_error').text('');
        });
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
