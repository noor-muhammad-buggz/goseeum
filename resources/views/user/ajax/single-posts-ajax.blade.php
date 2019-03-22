<!-- main post content section -->
<article class="hentry post">
    <div class="post__author author vcard inline-items">
        @if(!empty($post->postuser->profilephoto()))
            <img src="{{ asset('uploads/'.$post->postuser->profilephoto()->photo_url) }}" alt="profile" style="width: 40px; height: 40px;">
        @else
            <img alt="author" src="{{ asset('img/no-profile-photo.jpg') }}" class="profile">
        @endif

        <div class="author-date">
            <a class="h6 post__author-name fn" href="#">{{$post->postuser->first_name.' '.$post->postuser->last_name}}</a>
            <div class="post__date">
                <time class="published" datetime="2017-03-24T18:18">
                    {{$post->created_at->diffForHumans()}}
                </time>
            </div>
        </div>

        @if($post->postuser->id == Auth::user()->id)
        <div class="more">
            <svg class="olymp-three-dots-icon">
                <use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-three-dots-icon') }}"></use>
            </svg>
            <ul class="more-dropdown">
                <li>
                    <a href="javascript:;" data-target="{{$post->post_id}}" class="edit_post">Edit Post</a>
                </li>
                <li>
                    <a href="javascript:;" data-target="{{$post->post_id}}" class="delete_post">Delete Post</a>
                </li>
            </ul>
        </div>
        @endif
    </div>

    <p>{{$post->post_content}}</p>

    @if(count($post->postmeta) > 0)
    <div class="post-thumb">
        <a href="javascript:;" target="_blank">
            <img src="{{ asset('uploads/'.$post->postmeta[0]->meta_url) }}" alt="photo" class="post-thumb-item" data-target="{{$post->post_id}}">
        </a>
    </div>
    @endif

    <div class="post-additional-info inline-items">

        <a href="#" class="post-add-icon inline-items likePost {{ (in_array(Auth::user()->id, array_column($post->postlikes->toArray(), 'like_user_id'))) ? 'active' : '' }}" data-target="{{$post->post_id}}">
            <svg class="olymp-heart-icon">
                <use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-heart-icon') }}"></use>
            </svg>
            <span>{{count($post->postlikes)}}</span>
        </a>

        <!-- <ul class="friends-harmonic">
            <li>
                <a href="#">
                    <img src="{{ asset('img/friend-harmonic7.jpg') }}" alt="friend">
                </a>
            </li>
        </ul> -->

        <div class="names-people-likes">
            @if(count($post->postlikes) > 0)
                @if(count($post->postlikes) > 1)
                    @if($post->postlikes[0]->likeuser->id == Auth::user()->id)
                        <a href="javascript:;">You</a> and<br>{{count($post->postlikes)-1}} more liked this
                    @else
                        <a href="javascript:;">{{$post->postlikes[0]->likeuser->first_name.' '.$post->postlikes[0]->likeuser->last_name}}</a> and<br>{{count($post->postlikes)-1}} more liked this
                    @endif
                @else
                
                    @if($post->postlikes[0]->likeuser->id == Auth::user()->id)
                        <a href="javascript:;">You</a> liked this
                    @else
                    <a href="javascript:;">{{$post->postlikes[0]->likeuser->first_name.' '.$post->postlikes[0]->likeuser->last_name}}</a> liked this
                    @endif
                @endif
            @endif
        </div>


        <div class="comments-shared">
            <a href="javascript:;" class="post-add-icon inline-items goToCommentForm" data-target="{{$post->post_id}}">
                <svg class="olymp-speech-balloon-icon">
                    <use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-speech-balloon-icon') }}"></use>
                </svg>
                <span>{{$post->postcomments()->count()}}</span>
            </a>

            <a href="#" class="post-add-icon inline-items">
                <svg class="olymp-share-icon">
                    <use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-share-icon') }}"></use>
                </svg>
                <span></span>
            </a>
        </div>
    </div>
</article>
<!-- .. end main post content section -->

<!-- start comments section -->
<ul class="comments-list" id="cmnt_list_{{$post->post_id}}">
    @if(count($post->postcomments) > 0)
        @foreach($post->postcomments as $cmnt)
        <li class="cmnt_list_item_{{$post->post_id}}">
            <div class="post__author author vcard inline-items">
                @if(!empty($cmnt->commentuser->profilephoto()))
                    <img src="{{ asset('uploads/'.$cmnt->commentuser->profilephoto()->photo_url) }}" alt="profile" style="width: 40px; height: 40px;">
                @else
                    <img alt="author" src="{{ asset('img/no-profile-photo.jpg') }}" class="profile">
                @endif

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
        </li>
        @endforeach
        @if(count($post->postcomments) < $post->postcomments()->count())
        <p class="text-center p-0 m-0 load_more_comments_{{$post->post_id}} load_more_comments" data-target="{{$post->post_id}}" data-page="1">
            <a href="#" class="post-add-icon inline-items">
                <span>load more comments</span>
            </a>
        </p>
        @endif
    @endif
</ul>
<!-- ... end comments section -->

<!-- <a href="#" class="more-comments">View more comments <span>+</span></a> -->
<!-- start comment Form  -->
<div class="comment-form inline-items">
    <div class="post__author author vcard inline-items">
        @php $user = Auth::user(); @endphp
        @if(!empty($user->profilephoto()))
            <img src="{{ asset('uploads/'.$user->profilephoto()->photo_url) }}" alt="profile">
        @else
            <img alt="author" src="{{ asset('img/no-profile-photo.jpg') }}" class="profile">
        @endif
        <div class="form-group with-icon-right">
            <input class="form-control comment_body" placeholder="write comment here..." data-target="{{$post->post_id}}">
            <input type="hidden" name="cflag" data-target="{{$post->post_id}}" value="" class="cflag">
        </div>
    </div>
</div>
<!-- end comment form -->