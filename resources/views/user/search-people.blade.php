@php
$pageTitle = 'Search People';
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

<!-- start search area section -->
<div class="container mt20">
	<div class="row">
		<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="ui-block responsive-flex">
				<div class="ui-block-title">
					<div class="h6 title">Search People</div>
					<form class="w-search" method="GET">
						<div class="form-group with-button">
							<input class="form-control" type="text" placeholder="Search People..." name="search" value="{{$search}}">
							<button>
								<svg class="olymp-magnifying-glass-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-magnifying-glass-icon') }}"></use></svg>
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- end search area section -->

<!-- start search results area section -->
<div class="container">
	<div class="row">
        @if(empty($search))
            <div class="col-xl-12">
                <p class="text-center">Please type something and search</p>
            </div>
        @elseif(!empty($search))
            @if(count($people) > 0)
                @foreach($people as $user)
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                        <div class="ui-block">
                            <!-- Friend Item -->
                            <div class="friend-item">
                                <div class="friend-header-thumb">
                                    @if(!empty($user->coverphoto()))
                                        <img src="{{ asset('uploads/'.$user->coverphoto()->photo_url) }}" alt="cover">
                                    @else
                                        <img src="{{ asset('img/no-cover-photo.jpg') }}" alt="cover">
                                    @endif
                                </div>
                                <div class="friend-item-content">
                                    <div class="friend-avatar">
                                        <div class="author-thumb">
                                            @if(!empty($user->profilephoto()))
                                                <img src="{{ asset('uploads/'.$user->profilephoto()->photo_url) }}" alt="profile" style="width: 100%;height: 100%;">
                                            @else
                                                <img src="{{ asset('img/no-profile-photo.jpg') }}" alt="author" style="width: 100%;height: 100%;">
                                            @endif
                                        </div>
                                        <div class="author-content">
                                            <a href="{{url('user/profile/'.$user->id)}}" class="h5 author-name">{{ucfirst($user->first_name).' '.ucfirst($user->last_name)}}</a>
                                            <div class="country">{{ucfirst($user->gender)}}</div>
                                            <div class="friend-since">
                                                <span>Member Since:</span>
                                                <span>{{(new \DateTime($user->created_at))->format('F Y')}}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="control-block-button" data-swiper-parallax="-100">
                                        <button class="btn bg-blue send-friend-request" data-target="{{$user->id}}">Send Request</button>
                                    </div>
                                </div>
                            </div>	
                            <!-- ... end Friend Item -->
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-xl-12">
                    <p class="text-center">No results found against <b>"{{$search}}"</b></p>
                </div>
            @endif
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
    });
</script>
@endsection