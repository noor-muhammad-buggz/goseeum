@foreach($comments as $cmnt)
<li>
    <div class="comment-photo">
        <img src="{{asset('img/last-phot16.jpg')}}">
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