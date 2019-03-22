@php
$pageTitle = 'Friend Requests';
@endphp
@extends('layouts.user')
<style type="text/css">
.padding-0 {
    padding: 0px !important;
}
.border-0 {
    border: 0px !important;
}
span.file-item{
    width: auto;
    height: auto;
    display: inline-block;
}

span.file-item .inner-file-item, span.file-item .post-inner-file-item{
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

</style>
@section('content')

<!-- start caption area section -->
<div class="container mt20">
	<div class="row">
		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="ui-block responsive-flex">
				<div class="ui-block-title">
					<div class="h6 title">Requests recieved</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end caption area section -->

<!-- start search results area section -->
<div class="container mt20">
	<div class="row">
        @if(count($requests) > 0)
            @foreach($requests as $user)
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-6 fr_req_items_{{$user->id}}">
                    <div class="ui-block">
                        <!-- Friend Item -->
                        <div class="friend-item">
                            <div class="friend-header-thumb">
                                @if(!empty($user->cover_url))
                                    <img src="{{ asset('uploads/'.$user->cover_url) }}" alt="cover">
                                @else
                                    <img src="{{ asset('img/no-cover-photo.jpg') }}" alt="cover">
                                @endif
                            </div>
                            <div class="friend-item-content">
                                <div class="friend-avatar">
                                    <div class="author-thumb">
                                        @if(!empty($user->photo_url))
                                            <img src="{{ asset('uploads/'.$user->photo_url) }}" alt="profile" style="width: 100%;height: 100%;">
                                        @else
                                            <img src="{{ asset('img/no-profile-photo.jpg') }}" alt="author" style="width: 100%;height: 100%;">
                                        @endif
                                    </div>
                                    <div class="author-content">
                                        <a href="{{url('user/profile/'.$user->user_id)}}" class="h5 author-name">{{ucfirst($user->first_name).' '.ucfirst($user->last_name)}}</a>
                                        <div class="country">{{ucfirst($user->gender)}}</div>
                                        <div class="friend-since">
                                            <span>Member Since:</span>
                                            <span>{{(new \DateTime($user->created_at))->format('F Y')}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-block-button" data-swiper-parallax="-100">
                                    <button class="btn bg-blue acc_fr_req" data-target="{{$user->id}}">Accept</button>
                                    <button class="btn bg-danger dec_fr_req" data-target="{{$user->id}}">Decline</button>
                                </div>
                            </div>
                        </div>	
                        <!-- ... end Friend Item -->
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-xl-12">
                <p class="text-center">No friend requests recieved</b></p>
            </div>
        @endif
	</div>
</div>
<!-- end search results area section -->

<!-- start caption area section -->
<div class="container mt20">
	<div class="row">
		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="ui-block responsive-flex">
				<div class="ui-block-title">
					<div class="h6 title">Requests sent</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end caption area section -->

<!-- start search results area section -->
<div class="container mt20">
	<div class="row">
        @if(count($sent) > 0)
            @foreach($sent as $sen)
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-6 fr_req_sent_items_{{$sen->id}}">
                    <div class="ui-block">
                        <!-- Friend Item -->
                        <div class="friend-item">
                            <div class="friend-header-thumb">
                                @if(!empty($sen->cover_url))
                                    <img src="{{ asset('uploads/'.$sen->cover_url) }}" alt="cover">
                                @else
                                    <img src="{{ asset('img/no-cover-photo.jpg') }}" alt="cover">
                                @endif
                            </div>
                            <div class="friend-item-content">
                                <div class="friend-avatar">
                                    <div class="author-thumb">
                                        @if(!empty($sen->photo_url))
                                            <img src="{{ asset('uploads/'.$sen->photo_url) }}" alt="profile" style="width: 100%;height: 100%;">
                                        @else
                                            <img src="{{ asset('img/no-profile-photo.jpg') }}" alt="author" style="width: 100%;height: 100%;">
                                        @endif
                                    </div>
                                    <div class="author-content">
                                        <a href="{{url('user/profile/'.$sen->user_id)}}" class="h5 author-name">{{ucfirst($sen->first_name).' '.ucfirst($sen->last_name)}}</a>
                                        <div class="country">{{ucfirst($sen->gender)}}</div>
                                        <div class="friend-since">
                                            <span>Member Since:</span>
                                            <span>{{(new \DateTime($sen->created_at))->format('F Y')}}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-block-button" data-swiper-parallax="-100">
                                    <button class="btn bg-danger can_fr_req" data-target="{{$sen->id}}">Cancel</button>
                                </div>
                            </div>
                        </div>	
                        <!-- ... end Friend Item -->
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-xl-12">
                <p class="text-center">No friend requests sent</b></p>
            </div>
        @endif
	</div>
</div>
<!-- end search results area section -->
@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        // send friend request
        $(document).on('click touchstart', '.send-friend-request', function(event) {
            var _self = $(this)
            var formData = new FormData();
            formData.append('target', _self.attr('data-target'));
            // get requested post images
            $.ajax({
                url: URL + "/user/send/friend/request",
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    if(data.status == 200) {
                        _self.removeClass('send-friend-request');
                        _self.removeClass('bg-blue');
                        _self.addClass('bg-orange');
                        _self.text('Request Sent');
                        $.growl.notice({title: "Success", message: data.message});
                    }
                    else {
                        $.growl.error({title: "Error", message: data.message});
                    }
                }
            });
        });

        // cancel request
        // check if decline request button clicked
		$(document).on('click touchstart', '.can_fr_req', function(){
			blockSection('.fr_req_container');
			var _self = $(this);
			var formData = new FormData();
			formData.append('target', _self.attr('data-target'));
			$.ajax({
				url: URL + "/user/cancel/friend/request",
				type: "POST",
				data: formData,
				contentType: false,
				cache: false,
				processData: false,
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				success: function (data) {
					unblockSection('.fr_req_container');
					if(data.status == 200) {
						$.growl.notice({title: "Success", message: data.message});
						$('.fr_req_sent_items_'+_self.attr('data-target')).slideUp('slow');
					}
					else {
						$.growl.error({title: "Error", message: data.message});
					}
				}
			});
		});

    });
</script>
@endsection