@php
$pageTitle = 'Users';
@endphp
@extends('layouts.admin')
<style type="text/css">
.pull-right {
    float: right;
}
.padding3{
    padding:3px;
}
.page-link:hover {
    background-color: #e8a93f !important;
}
</style>
@section('content')
<div class="container">
    <div class="row">
        <div class="ui-block-title">
            <h4 class="title">Users</h4>
            <!-- <a href="{{url('cities/add')}}" class="btn btn-orange pull-right"><i class="fa fa-plus"> </i> Add City</a> -->
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-xl-12 order-xl-2 col-lg-12 order-lg-2 col-md-12 order-md-1 col-sm-12 col-xs-12">
            <div class="ui-block">
                <div class="ui-block-title">
                    <h6 class="title">Users</h6>
                </div>
                <div class="ui-block-content table-responsive">
                    @include('errors.form_errors')
                    <table class="table">
                        <thead>
                            <th width="">Sr#</th>
                            <th width="">Name</th>
                            <th width="">Email</th>
                            <th width="">DOB</th>
                            <th width="">Gender</th>
                            <th width="">Action</th>
                        </thead>
                        <tbody>
                        @if (!$users->isEmpty())
                            @foreach($users as $user)
                                <tr>
                                    <td>{{$user->id}}</td>
                                    <td>{{$user->first_name.' '.$user->last_name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->dob}}</td>
                                    <td>{{$user->gender}}</td>
                                    <td>
                                        <a class="btn btn-danger del-user" href="javascript:;" data-href="{{url('users/delete/'.$user->id)}}" title="Delete User"><span class="fa fa-trash"></span></a>
                                    </td>
                                </tr>
                            @endforeach
                                <tr>
                                    <td colspan="6">
                                        {{ $users->links() }}
                                    </td>
                                </tr>
                        @else
                            <tr class="text-center">
                                <td colspan="6">No users found yet</td>
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
        $(document).on('click', '.del-user', function(event){
            event.preventDefault();
            var url = $(this).attr('data-href');
            swal({
                title: "Are you sure?",
                text: "You want to delete selected user",
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
