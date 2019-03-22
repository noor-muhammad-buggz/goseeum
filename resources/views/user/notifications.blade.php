@php
$pageTitle = 'Notifications';
@endphp
@extends('layouts.user')
@section('content')
<div class="container">
    <div class="row">
        <div class="ui-block-title">
            <h4 class="title">Notifications</h4>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="ui-block">
                <ul class="notification-list">
                    @if(count($notifications) > 0)
                        @foreach($notifications as $noti)
                            @php
                            $types = array('FriendRequestRecieved','FriendRequestAccepted', 'FriendRequestRejected');
                            $payload = json_decode($noti->payload);
                            $link = 'javascript:;';
                            if(in_array($payload->type, $types)) {
                                $link = url('user/profile/'.$noti->sender_id);
                            }
                            elseif($payload->type == 'LocationAlert') {
                                $link = url('user/locations');
                            }
                            @endphp
                            <a href="{{$link}}" style="color: unset;">
                                <li class="{{($noti->is_read == 0) ? 'un-read' : ''}}">
                                    <div class="author-thumb">
                                        @if(!empty($noti->photo_url))
                                            <img src="{{ asset('uploads/'.$noti->photo_url) }}">
                                        @else
                                            <img src="{{ asset('img/no-profile-photo.jpg') }}" class="profile">
                                        @endif
                                    </div>
                                    
                                    <div class="notification-event">
                                        <div><?php echo $payload->message ?></div>
                                        <span class="notification-date"><time class="entry-date updated">{{$noti->created_at->diffForHumans()}}</time></span>
                                    </div>
                                </li>
                            </a>
                        @endforeach
                    @else
                        <li>
                            <div class="notification-event">
                                <div>No notifications found yet</div>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@endsection