@if(count($chat) > 0)
    @foreach($chat as $cht)
    <li class="chat-container-item" data-id="{{$cht->msg_id}}">
        <div class="author-thumb">
            @if(!empty($cht->sender_photo))
            <img src="{{ asset('uploads/'.$cht->sender_photo) }}" alt="sender">
            @else
            <img src="{{ asset('img/avatar2-sm.jpg') }}" alt="author">
            @endif
        </div>
        <div class="notification-event">
            <a href="#" class="h6 notification-friend">{{$cht->sender_name}}</a>
            <span class="notification-date pull-right"><time class="entry-date updated" datetime="2004-07-24T18:18">{{$cht->created_at->diffForHumans()}}</time></span>
            <span class="chat-message-item">{{$cht->message}}</span>
            <div class="added-photos">
                @if(!empty($cht->media_url))
                <img src="{{ asset('uploads/'.$cht->media_url) }}" alt="{{$cht->media_url}}">
                <span class="photos-name">{{$cht->media_url}}</span>
                @endif
            </div>
        </div>
    </li>
    @endforeach
@endif