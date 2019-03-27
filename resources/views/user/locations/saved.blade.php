@php
$pageTitle = 'Saved Locations';
@endphp
@extends('layouts.user')
<style type="text/css">
.pull-right {
    float: right;
}
.padding3{
    padding:3px;
}
.ml-2{
    margin-left: 2px;
}
a.btn.btn-orange, a.btn.btn-danger.del-location {
    padding: 5px 7px !important;
}
</style>
@section('content')

{{-- LIST TYPE IMPLEMENTATION --}}
<div class="container">
    <div class="ui-block-title">
        <h4 class="title">Saved Locations</h4>
    </div>
    <div class="col-xl-12 order-xl-2 col-lg-12 order-lg-2 col-md-12 order-md-1 col-sm-12 col-xs-12">
        <div class="ui-block">
            <div class="ui-block-title">
                <h6 class="title">Saved Locations</h6>
            </div>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <!-- Notification List Chat Messages -->
                    <span class="current-conv" data-id=""></span>
                    @if(count($saved) > 0)
                    <ul class="notification-list">
                        @foreach($saved as $sv)
                        <li class="">
                            <div class="">
                                @if(count($sv->location->images) > 0)
                                    <img style="width: 55px;height: 55px;border-radius: 5px;" src="{{asset('uploads/'.$sv->location->images[0]->location_image_url)}}" />
                                @else
                                    No Image
                                @endif
                            </div>
                            <div class="notification-event">
                                <p class="h4 notification-friend">{{$sv->location->location_name}}</p>
                                <p style="margin-bottom:0px;"><b>Rating</b> : <span>{{$sv->location->rating}}</span></p>
                                <span class="">{{$sv->location->location_address}}</span>
                                <span class="notification-date">
                                    <time class="entry-date updated" datetime="">
                                        
                                    </time>
                                </span>
                            </div>

                            <a class="btn btn-danger del-location pull-right ml-2" href="javascript:;" data-target="{{$sv->location->id}}" title="Remove Location"><span class="fa fa-trash"></span></a>
                            <a class="btn btn-orange pull-right" href="{{url('user/locations/'.urlencode(strtolower(str_replace(' ', '_', $sv->location->location_name)).'_'.$sv->location->id).'/'.$sv->location->google_place_id) }}" title="View Location"><span class="fa fa-eye"></span></a>

                        </li>
                        @endforeach
                    </ul>
                    @endif
                    <!-- ... end Notification List Chat Messages -->
                </div>
            </div>
        </div>
    </div>
</div>
{{-- ED LIST TYPE IMPLEMENTATION --}}

{{-- TABLE TYPE IMPLEMENTATION --}}

{{-- <div class="container">
    <div class="row">
        <div class="ui-block-title">
            <h4 class="title">Saved Locations</h4>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-xl-12 order-xl-2 col-lg-12 order-lg-2 col-md-12 order-md-1 col-sm-12 col-xs-12">
            <div class="ui-block">
                <div class="ui-block-title">
                    <h6 class="title">Saved Locations</h6>
                </div>
                <div class="ui-block-content table-responsive">
                    @include('errors.form_errors')
                    <table class="table">
                        <thead>
                            <th width="5%">Sr#</th>
                            <th width="10%">Image</th>
                            <th width="20%">Name</th>
                            <th width="40%">Address</th>
                            <th width="10%">Rating</th>
                            <th width="15%">Action</th>
                        </thead>
                        <tbody>
                        @if (count($saved) > 0)
                            @foreach($saved as $loc)
                                <tr>
                                    <td>{{$loc->location->id}}</td>
                                    <td>
                                        @if(count($loc->location->images) > 0)
                                            <img style="width: 35px;height: 35px;border-radius: 5px;" src="{{asset('uploads/'.$loc->location->images[0]->location_image_url)}}" />
                                        @else
                                            No Image
                                        @endif
                                    </td>
                                    <td>{{$loc->location->location_name}}</td>
                                    <td>{{$loc->location->location_address}}</td>
                                    <td>{{$loc->location->rating}}</td>
                                    <td>
                                        <a class="btn btn-orange" href="{{url('user/locations/'.urlencode(strtolower(str_replace(' ', '_', $loc->location->location_name)).'_'.$loc->location->id).'/'.$loc->location->google_place_id) }}" title="View Location"><span class="fa fa-eye"></span></a>
                                        <a class="btn btn-danger del-location" href="javascript:;" data-target="{{$loc->location->id}}" title="Remove Location"><span class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="text-center">
                                <td colspan="5">No save locations found yet</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
</div> --}}

{{-- END TABLE TYPE IMPLEMENTATION --}}

<!-- popup for give rating to location -->
<div class="modal" id="loader-mod">
    <div class="modal-dialog modal-sm ui-block" style="width: 70px;top: 40vh;">
        <div class="m-auto padding-20 text-center">
            <img src="{{ asset('img/loader.gif') }}" alt="please wait...">
        </div>
    </div>
</div>
<!-- ... end popup for give rating -->

@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        /*
        |----------------------------------------------------------------------
        | remove location from favourites
        |----------------------------------------------------------------------
        */
        $(document).on('click', '.del-location', function(event){
            event.preventDefault();
            var target = $(this).attr('data-target');
            swal({
                title: "Are you sure?",
                text: "You want to remove selected location from favourites?",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: 'Yes sure!',
                cancelButtonText: "No cancel",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function(flag){
                if(flag){
                    $('#loader-mod').modal('show');
                    var formData = new FormData();
                    formData.append('target', target);
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
                                $.growl.notice({title: 'Success' ,message: data.message });
                                setTimeout(function(){
                                    location.reload();
                                }, 1000);
                            }
                            else {
                                $.growl.error({title: 'Error', message: data.message });
                            }
                        }
                    });
                }
            });
        });

    });
</script>
@endsection