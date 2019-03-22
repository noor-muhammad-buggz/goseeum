@if($type == 1)
    @foreach($friend_requests as $fr)
    <li class="fr_req_items_{{$fr->id}}">
        <div class="author-thumb">
            @if(!empty($fr->photo_url))
                <img src="{{ asset('uploads/'.$fr->photo_url) }}">
            @else
                <img src="{{ asset('img/no-profile-photo.jpg') }}" class="profile">
            @endif
        </div>
        <div class="notification-event">
            <a href="{{url('user/profile/'.$fr->user_id)}}" class="h6 notification-friend">{{ucfirst($fr->first_name).' '.ucfirst($fr->last_name)}}</a>
            <!-- <span class="chat-message-item">Mutual Friend: Sarah Hetfield</span> -->
        </div>
        <span class="notification-icon">
            <a class="accept-request bg-blue c-white acc_fr_req" data-target="{{$fr->id}}">
                <span class="fa fa-check"></span>
            </a>
            <a class="accept-request bg-danger c-white dec_fr_req" data-target="{{$fr->id}}">
                <span class="fa fa-times"></span>
            </a>
        </span>
    </li>
    @endforeach
@elseif($type == 2)
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
@endif