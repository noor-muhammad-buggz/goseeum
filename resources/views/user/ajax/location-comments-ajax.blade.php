@foreach($comments as $cmnt)
<li>
    <div class="comment-photo">
        @if(!empty($cmnt->commentuser->profilephoto()))
            <img src="{{ asset('uploads/'.$cmnt->commentuser->profilephoto()->photo_url) }}" alt="profile" style="width: 40px; height: 40px;">
        @else
            <img alt="author" src="{{ asset('img/no-profile-photo.jpg') }}" class="profile">
        @endif
        <p></p>
        <small>{{$cmnt->created_at->diffForHumans()}}</small>
    </div>
    <div class="comment-body">
        <p class="comment-name">{{$cmnt->commentuser->first_name.' '.$cmnt->commentuser->last_name}}</p>
        <p class="comment-content">
            {{$cmnt->comment_body}}
        </p>
    </div>
</li>
@endforeach