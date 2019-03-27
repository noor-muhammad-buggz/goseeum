@php
$pageTitle = 'News Feed';
@endphp
@extends('layouts.user')
<style type="text/css">
.padding-0 {
    padding: 0px !important;
}

.padding-20 {
    padding: 20px !important;
}

.hidden{
    display: none;
}

.border-0 {
    border: 0px !important;
}

.font-normal {
    font-weight: normal !important;
}
.padding-0 li {
    padding: 5px 5px 5px 25px !important;
}

.active {
    fill: #e8a93f !important;
    color: #e8a93f !important;
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
.post-add-icon {
    fill: #715959 !important;
    color: #442b2b !important;
}
.post-additional-info {
    padding: 10px 0 0 !important;
}
.post {
    padding: 25px 25px 10px !important;
}
@media only screen and (max-width: 768px){
    button#preview_btn {
    float: left;
    margin-left: 6px;
}
button#post_btn {
    float: left;
}
a.options-message {
    margin-left: 10px;
}
}
</style>
@section('content')

<!-- ... end Top Header-Profile -->
<div class="container">
    <div class="row">
        <!-- Main Content -->
        <div class="col-xl-6 order-xl-2 col-lg-12 order-lg-1 col-md-12 col-sm-12 col-xs-12">
            <div id="newsfeed-items-grid">
                <div class="ui-block">
                    <!-- start post form -->
                    <div class="news-feed-form">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active inline-items" data-toggle="tab" href="#home-1" role="tab" aria-expanded="true">
                                    <svg class="olymp-status-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-status-icon') }}"></use></svg>
                                    <span></span>
                                </a>
                            </li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="home-1" role="tabpanel" aria-expanded="true">
                                <div class="author-thumb" style="top: unset;margin-top: 5px;">
                                    @if(!empty(Auth::user()->profilephoto()))
                                        <img src="{{ asset('uploads/'.Auth::user()->profilephoto()->photo_url) }}" alt="profile" style="width: 40px; height: 40px;">
                                    @else
                                        <img alt="author" src="{{ asset('img/author-page.jpg') }}" class="profile">
                                    @endif
                                </div>
                                <div class="form-group with-icon label-floating is-empty">
                                    <label class="control-label">Share what you are thinking here...</label>
                                    <textarea class="form-control" placeholder="" id="post_body"></textarea>
                                </div>
                                <div class="add-options-message">
                                    <a href="javascript:;" class="options-message" data-toggle="tooltip" data-placement="top"   data-original-title="ADD MULTIMEDIA">
                                        <svg class="olymp-camera-icon" data-toggle="modal" data-target="#update-header-photo"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-camera-icon') }}"></use></svg>
                                    </a>
                                    <a class="options-message text-danger post-error"></a>
                                    <a class="options-message text-success post-success"></a>
                                    <button class="btn btn-orange" id="post_btn">Post</button>
                                    <button class="btn btn-purple" id="preview_btn">Preview</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ... end post form  -->
                </div>

                <div id="posts_content_section">
                    @if(count($posts) > 0)
                    @foreach($posts as $post)
                    <div class="ui-block" id="post_list_{{$post->post_id}}">
                        <!-- main post content section -->
                        <article class="hentry post">
                            <div class="post__author author vcard inline-items">
                                @if(!empty($post->postuser->profilephoto()))
                                    <img src="{{ asset('uploads/'.$post->postuser->profilephoto()->photo_url) }}" alt="profile" style="width: 40px; height: 40px;">
                                @else
                                    <img alt="author" src="{{ asset('img/author-page.jpg') }}" class="profile">
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

                                    <a href="javascript:;" class="post-add-icon inline-items">
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
                                            <p class="cmnt_list_item_body_{{$cmnt->comment_id}}_{{$cmnt->comment_parent_id}}">{{$cmnt->comment_body}}</p>
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

                                    <!-- <p class="cmnt_list_item_body_{{$cmnt->comment_id}}_{{$cmnt->comment_parent_id}}">{{$cmnt->comment_body}}</p> -->

                                    <!-- <a href="#" class="post-add-icon inline-items">
                                        <svg class="olymp-heart-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-heart-icon') }}"></use></svg>
                                        <span>3</span>
                                    </a> -->
                                    <!-- <a href="#" class="reply">Reply</a> -->
                                </li>
                                @endforeach
                                @if(count($post->postcomments) < $post->postcomments()->count())
                                <p class="text-center p-0 m-0 load_more_comments_{{$post->post_id}} load_more_comments" data-target="{{$post->post_id}}" data-page="1">
                                    <a href="#" class="post-add-icon inline-items">
                                        <span>Load More Comments</span>
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
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>

            <a id="load-more-button" href="#" class="btn btn-control btn-more" data-load-link="items-to-load.html" data-container="newsfeed-items-grid">
                <svg class="olymp-three-dots-icon">
                    <use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-three-dots-icon') }}"></use>
                </svg>
            </a>
        </div>
        <!-- ... end Main Content -->

        <!-- Left Sidebar -->
        <div class="col-xl-3 order-xl-1 col-lg-6 order-lg-2 col-md-6 col-sm-12 col-xs-12">
            <div class="ui-block">
                <div class="ui-block-title">
                    <h6 class="title">About <a href="{{url('user/profile-settings')}}" class="pull-right"><i class="fa fa-edit"></i></a></h6>
                </div>
                <div class="ui-block-content">
                    <!-- W-Personal-Info -->
                    <ul class="widget w-personal-info item-block">
                        <li>
                            <span class="text">Hi, I’m {{$user->first_name.' '.$user->last_name}}, I’m {{ucfirst($user->gender)}}.</span>
                        </li>
                    </ul>
                    <!-- .. end W-Personal-Info -->
                </div>
            </div>
        </div>
        <!-- ... end Left Sidebar -->

        <!-- Right Sidebar -->
        <div class="col-xl-3 order-xl-3 col-lg-6 order-lg-3 col-md-6 col-sm-12 col-xs-12">
            <div class="ui-block">
                <div class="ui-block-title">
                    <h6 class="title">Friends</h6>
                </div>
                <div class="ui-block-content">
                    <!-- W-Faved-Page -->
                    <!-- <ul class="widget w-faved-page js-zoom-gallery">
                        <li>
                            <a href="#">
                                <img src="{{ asset('img/avatar38-sm.jpg') }}" alt="author">
                            </a>
                        </li>
                        <li class="all-users">
                            <a href="#">+74</a>
                        </li>
                    </ul> -->
                    <!-- .. end W-Faved-Page -->
                </div>
            </div>
        </div>
        <!-- ... end Right Sidebar -->
    </div>
</div>

<!-- popup for add post photos -->
<div class="modal fade" id="update-header-photo">
    <div class="modal-dialog ui-block window-popup update-header-photo">
        <a href="javascript:;" class="close icon-close" data-dismiss="modal" aria-label="Close">
            <svg class="olymp-close-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-close-icon') }}"></use></svg>
        </a>
        <div class="ui-block-title">
            <h6 class="title">Add Multimedia</h6>
        </div>
        <a href="javascript:;" class="upload-photo-item" onclick="$('#post_media').trigger('click')">
            <svg class="olymp-computer-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-computer-icon') }}"></use></svg>
            <h6>Upload Multimedia</h6>
            <span>Browse your computer.</span>
        </a>
        <div class="file-previews"></div>
        <input type="file" name="post_media" id="post_media" accept=".jpeg, .png, .jpg, .mp4, .flv, .wvm, .mp3, .wav" multiple="true" style="display: none;" onchange="storepostmedia(this)">
    </div>
</div>
<!-- ... end popup for add post photos -->

<!-- popup for edit post -->
<div class="modal fade" id="edit-post">
    <div class="modal-dialog ui-block window-popup update-header-photo">
        <a href="javascript:;" class="close icon-close" data-dismiss="modal" aria-label="Close">
            <svg class="olymp-close-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-close-icon') }}"></use></svg>
        </a>
        <div class="ui-block-title">
            <h6 class="title">Edit Post</h6>
        </div>
        <div class="ui-block border-0">
            <div class="loader col-md-12 padding-20 text-center hidden">
                <img src="{{ asset('img/loader.gif') }}" alt="please wait...">
            </div>
            <!-- start post form -->
            <div class="news-feed-form edit_post_form hidden">
                <div class="author-thumb" style="top: unset;margin-top: 5px;">
                    @if(!empty($user->profilephoto()))
                        <img src="{{ asset('uploads/'.$user->profilephoto()->photo_url) }}" alt="profile">
                    @else
                        <img alt="author" src="{{ asset('img/no-profile-photo.jpg') }}" class="profile">
                    @endif
                </div>
                <div class="form-group with-icon label-floating is-empty is-focused">
                    <label class="control-label">Share what you are thinking here...</label>
                    <textarea class="form-control" placeholder="" id="edit_post_body"></textarea>
                </div>
                <div class="add-options-message">
                    <!-- post photos section -->
                    <a href="javascript:;" class="options-message" data-toggle="tooltip" data-placement="top" data-original-title="ADD MULTIMEDIA" onclick="$('#edit_post_media').trigger('click')">
                        <svg class="olymp-camera-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-camera-icon') }}"></use></svg>
                    </a>
                    <br>
                    <div class="file-previews-edit"></div>
                    <input type="file" name="edit_post_media" id="edit_post_media" accept=".jpeg, .png, .jpg, .mp4, .flv, .wvm, .mp3, .wav" multiple="true" style="display: none;" onchange="directuploadpostmedia(this)">
                </div>
                <div class="add-options-message">
                    <input type="hidden" name="edit_post_id" value="" id="edit_post_id">
                    <button class="btn btn-orange" id="edit_post_btn">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- ... end popup for add post photos -->

<!-- start modal for post preview -->
<div class="modal fade" id="post-content-preview">
    <div class="modal-dialog ui-block">
        <a href="javascript:;" class="close icon-close" data-dismiss="modal" aria-label="Close">
            <svg class="olymp-close-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-close-icon') }}"></use></svg>
        </a>
        <div class="ui-block-title">
            <h6 class="title">Post Preview</h6>
        </div>
        <div class="ui-block border-0">
            <article class="hentry post">
                <div class="post__author author vcard inline-items">
                    @if(!empty(Auth::user()->profilephoto()))
                        <img src="{{ asset('uploads/'.Auth::user()->profilephoto()->photo_url) }}" alt="profile" style="width: 40px; height: 40px;">
                    @else
                        <img alt="author" src="{{ asset('img/no-profile-photo.jpg') }}" class="profile">
                    @endif

                    <div class="author-date">
                        <a class="h6 post__author-name fn" href="#">{{Auth::user()->first_name.' '.Auth::user()->last_name}}</a>
                        <div class="post__date">
                            <time class="published" datetime="2017-03-24T18:18">
                                2 seconds ago
                            </time>
                        </div>
                    </div>    
                </div>

                <p class="post-content-preview-text"></p>
                <div class="post-thumb">
                    <a href="javascript:;" target="_blank" class="post-content-preview-thumb">
                        
                    </a>
                </div>
            </article>
        </div>
    </div>
</div>
<!-- end modal for post preview -->
<div class="post-photos-items" style="display:none;"></div>
@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('js/user-scripts.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(document).on('click', '.goToCommentForm', function(){
            var _self = $(this);
            var _cm_scroll_to = _self.attr('data-target');
            var _cm_position = $("input[data-target="+_cm_scroll_to+"]").offset();
            $("input[data-target="+_cm_scroll_to+"]").focus();
            $("html, body").animate({
                scrollTop: _cm_position.top-100
            }, 500);
        });

        // delete post image
        $(document).on('click', '.post-inner-file-item', function(){
            var _self = $(this);
            // delete requested photo
            currentpostmedia.splice(parseInt(_self.attr('data-target')),1);
            _self.parent().remove();
        });
        
        // preview post before creation
        $(document).on('click', '#preview_btn', function(){
            var pre_text = $('#post_body').val();
            var pre_media = currentpostmedia.length;
            if(pre_text.length > 0 || pre_media > 0) {
                $('.post-content-preview-text').html(pre_text);
                $('.post-content-preview-thumb').html('');
                // preview section
                if(pre_media > 0) {
                    var pre_fileReader = new FileReader();
                    pre_fileReader.onload = function(pre_file){
                        console.log(pre_file);
                        $('.post-content-preview-thumb').html('<img alt="photo" src="'+pre_file.target.result+'">');
                    };
                    pre_fileReader.readAsDataURL(currentpostmedia[0]);
                }
                $('#post-content-preview').modal('show');
            }
        });
        
        // get required post images
        $(document).on('click touchstart', '.post-thumb-item', function(event) {
            var formData = new FormData();
            formData.append('target', $(this).attr('data-target'));
            $('.post-photos-items').html('');
            // get requested post images
            $.ajax({
                url: URL + "/user/post/get",
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
                        if(data.post.postmeta.length > 0) {
                            var postImages = data.post.postmeta;
                            for(var j = 0; j<postImages.length;j++) {
                                $('.post-photos-items').append('<div class="post-photos-item" data-src="{{url("uploads")}}/'+postImages[j].meta_url+'"><img src="{{url("uploads")}}/'+postImages[j].meta_url+'"></div>');
                            }
                            initLightGallery('.post-photos-items', '.post-photos-item');
                            $('.post-photos-item').click();
                        }
                    }
                }
            });
        });

    });

    function initLightGallery(container, selector) {
        $(container).lightGallery({
            selector : selector
        }).one("onCloseAfter.lg", function() {
            $(container).data('lightGallery').destroy(true);
        });
    }

</script>
@endsection
