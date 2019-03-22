@foreach($comments as $cmnt)
<li class="cmnt_list_item_{{$cmnt->comment_parent_id}}">
    <div class="post__author author vcard inline-items">
        <img src="{{ asset('img/author-page.jpg') }}" alt="author">

        <div class="author-date">
            <a class="h6 post__author-name fn" href="#">{{$cmnt->commentuser->first_name.' '.$cmnt->commentuser->last_name}}</a>
            <div class="post__date">
                <time class="published" datetime="">
                    {{$cmnt->created_at->diffForHumans()}}
                </time>
            </div>
        </div>

        @if($cmnt->commentuser->id == Auth::user()->id)
        <div class="more">
            <svg class="olymp-three-dots-icon">
                <use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-three-dots-icon') }}"></use>
            </svg>
            <ul class="more-dropdown padding-0 font-normal">
                <li>
                    <a href="#" class="editComment" data-target="{{$cmnt->comment_id.'-'.$cmnt->comment_parent_id}}">Edit Comment</a>
                </li>
                <li>
                    <a href="#" class="delComment" data-target="{{$cmnt->comment_id.'-'.$cmnt->comment_parent_id}}">Delete Comment</a>
                </li>
            </ul>
        </div>
        @endif
    </div>

    <p class="cmnt_list_item_body_{{$cmnt->comment_id}}_{{$cmnt->comment_parent_id}}">{{$cmnt->comment_body}}</p>

    <a href="#" class="post-add-icon inline-items">
        <svg class="olymp-heart-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-heart-icon') }}"></use></svg>
        <span>3</span>
    </a>
    <!-- <a href="#" class="reply">Reply</a> -->
</li>
@endforeach