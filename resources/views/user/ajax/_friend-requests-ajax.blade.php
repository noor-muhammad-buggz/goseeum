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
        <a href="javascript:;" class="h6 notification-friend">{{ucfirst($fr->first_name).' '.ucfirst($fr->last_name)}}</a>
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
<!-- <li class="last_fr_rec" data-target="{{(count($friend_requests) > 0) ? $friend_requests[0]->id : 0}}"></li> -->