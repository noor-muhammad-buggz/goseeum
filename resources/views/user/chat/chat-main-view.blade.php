@php
$pageTitle = 'Chat/Messages';
@endphp
@extends('layouts.user')
@section('content')
<!-- sidemenu responsive menu -->
<div class="profile-settings-responsive">
    <a href="#" class="js-profile-settings-open profile-settings-open">
        <i class="fa fa-angle-right" aria-hidden="true"></i>
        <i class="fa fa-angle-left" aria-hidden="true"></i>
    </a>
    <div class="mCustomScrollbar" data-mcs-theme="dark">
        <div class="ui-block">
            <div class="your-profile">
                <div class="ui-block-title ui-block-title-small">
                    <h6 class="title">YOUR PROFILE</h6>
                </div>
                <div class="ui-block-title">
                    <a href="{{url('user/profile-settings')}}" class="h6 title">Profile Settings</a>
                </div>
                <div class="ui-block-title">
                    <a href="{{url('user/change-password')}}" class="h6 title">Change Password</a>
                </div>
                <div class="ui-block-title">
                    <a href="{{url('user/chat-messages')}}" class="h6 title">Chat / Messages</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end sidemenu responsive menu -->
<div class="container">
    <div class="row">
        <div class="ui-block-title">
            @include('errors.form_errors')
        </div>
    </div>
</div>

<div class="container">
    <div class="row">

        <div class="col-xl-9 order-xl-2 col-lg-9 order-lg-2 col-md-12 order-md-1 col-sm-12 col-xs-12">
            <div class="ui-block chat-main-wrapper">
                <div class="ui-block-title">
                    <h6 class="title">Chat / Messages</h6>
                    <a href="#" class="more"><svg class="olymp-three-dots-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-three-dots-icon') }}"></use></svg></a>
                </div>

                <div class="row">
                    <div class="col-xl-5 col-lg-6 col-md-12 col-sm-12 col-xs-12 padding-r-0">
                        <!-- Notification List Chat Messages -->
                        <span class="current-conv" data-id=""></span>
                        @if(!empty($conversations))
                        <ul class="notification-list chat-message">
                            @foreach($conversations as $conv)
                            <li class="load-chat" data-id="{{$conv->conv_id}}">
                                <div class="author-thumb">
                                    @if(!empty($conv->other_user_photo))
                                    <img src="{{ asset('uploads/'.$conv->other_user_photo) }}" alt="author">
                                    @else
                                    <img src="{{ asset('img/avatar2-sm.jpg') }}" alt="author">
                                    @endif
                                </div>
                                <div class="notification-event">
                                    <p class="h6 notification-friend">{{$conv->other_user_name}}</p>
                                    @if(!empty($conv->last_message_text))
                                    <span class="chat-message-item">{{$conv->last_message_text}}</span>
                                    <span class="notification-date"><time class="entry-date updated" datetime="">{{$conv->created_at->diffForHumans()}}</time></span>
                                    @else
                                    <span class="chat-message-item">No chat history</span>
                                    @endif
                                </div>
                                <span class="notification-icon">
                                    <svg class="olymp-chat---messages-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-chat---messages-icon') }}"></use></svg>
                                </span>

                                <div class="more">
                                    <svg class="olymp-three-dots-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-three-dots-icon') }}"></use></svg>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                        @endif
                        <!-- ... end Notification List Chat Messages -->
                    </div>

                    <div class="col-xl-7 col-lg-6 col-md-12 col-sm-12 col-xs-12 padding-l-0">
                        <!-- Chat Field -->
                        <div class="chat-field">
                            <div class="chat-container">
                                <ul class="notification-list chat-message chat-message-field chat-container-items">
                                    <li>
                                        <p>Select a conversation to view chat history</p>
                                    </li>
                                </ul>
                            </div>
                            {{-- start chat form section --}}
                            <div class="chat-form-container">
                                <form id="chat-form" method="post">
                                    <div class="form-group label-floating is-empty">
                                        <label class="control-label">Write your reply here...</label>
                                        <textarea class="form-control" placeholder="" name="body"></textarea>
                                    </div>
                                </form>
                                <div class="add-options-message">
                                    <button class="btn btn-orange btn-sm send-chat-message" type="button">Reply</button>
                                </div>
                            </div>
                            {{-- end chat form section --}}
                        </div>
                        <!-- ... end Chat Field -->
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 order-xl-1 col-lg-3 order-lg-1 col-md-12 order-md-2 col-sm-12 col-xs-12 responsive-display-none">
            <div class="ui-block">
                <!-- Your Profile  -->
                <div class="your-profile">
                    <div class="ui-block-title ui-block-title-small">
                        <h6 class="title">YOUR ACCOUNT</h6>
                    </div>
                    <div class="ui-block-title">
                        <a href="{{url('user/profile-settings')}}" class="h6 title">Profile Settings</a>
                    </div>
                    <div class="ui-block-title">
                        <a href="{{url('user/change-password')}}" class="h6 title">Change Password</a>
                    </div>
                    <div class="ui-block-title">
                        <a href="{{url('user/chat-messages')}}" class="h6 title">Chat / Messages</a>
                    </div>
                </div>
                <!-- ... end Your Profile  -->
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script type="text/javascript">
$(document).ready(function(){
    var chatinterval;
    /*
    |----------------------------------------------------------------------
    | load specific chat section
    |----------------------------------------------------------------------
    */
    $(document).on('click touchstart', '.load-chat', function(event) {
        blockSection('.chat-main-wrapper');
        if(chatinterval) {
            clearInterval(chatinterval);
        }
        var _self = $(this);
        var formData = new FormData();
        formData.append('target', _self.data('id'));
        $('.current-conv').data('id', _self.data('id'));
        // create comment
        $.ajax({
            url: URL + "/user/chat/get",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $('.load-chat').removeClass('bg-blue-lighter');
                _self.addClass('bg-blue-lighter');
                unblockSection('.chat-main-wrapper');
                $('.chat-container').html(data.data.html);
                $('.mCustomScrollbar').perfectScrollbar({wheelPropagation:false});
                $('.mCustomScrollbar').animate({scrollTop: $('.chat-container-items').prop('scrollHeight')}, 'slow');
                chatinterval = setInterval(ReloadChat, 5000);
            }
        });
    });

    /*
    |----------------------------------------------------------------------
    | reload specific chat after 3 seconds
    |----------------------------------------------------------------------
    */
    function ReloadChat() {
        var formData = new FormData();
        formData.append('target', $('.current-conv').data('id'));
        formData.append('offset', $('.chat-container-item').last().data('id'));
        $.ajax({
            url: URL + "/user/chat/get/unread",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                if(data.status == 200 && data.data.count > 0) {
                    $('.chat-container-items').append(data.data.html);
                    $('.mCustomScrollbar').animate({scrollTop: $('.chat-container-items').prop('scrollHeight')}, 'slow');
                }
            }
        });
    }

    /*
    |----------------------------------------------------------------------
    | send new message
    |----------------------------------------------------------------------
    */
    $(document).on('click', '.send-chat-message', function(event) {
        blockSection('.chat-form-container');
        if(chatinterval) {
            clearInterval(chatinterval);
        }
        var _self = $(this);
        var formData = new FormData($('#chat-form')[0]);
        formData.append('target', $('.current-conv').data('id'));
        // create comment
        $.ajax({
            url: URL + "/user/chat/send",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                unblockSection('.chat-form-container');
                if(data.status == 200) {
                    $('#chat-form')[0].reset();
                    $.growl.notice({title: 'Success' ,message: data.message });
                    ReloadChat();
                }
                else {
                    $.growl.error({title: 'Error' ,message: data.message });
                }
                chatinterval = setInterval(ReloadChat, 5000);
            }
        });
    });

});
</script>
@endsection
