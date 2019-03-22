/*
|----------------------------------------------------------------------
| post comments section
|----------------------------------------------------------------------
*/

$(document).on('keyup touchstart', '.comment_body', function(event) {
    var keyCode = event.keyCode || event.which;
    var _self = $(this);

    if(keyCode == 27 || event.key == 'Escape') {
        _self.val('');
        $('.cflag[data-target='+_self.attr('data-target')+']').val('');        
    }
    else if(keyCode == 13 && _self.val().length > 0) {
		_self.attr('disabled', 'disabled');
		var formData = new FormData();
		formData.append('target', _self.attr('data-target'));
		formData.append('content', _self.val());
        formData.append('flag', $('.cflag[data-target='+_self.attr('data-target')+']').val());
        // create comment
        $.ajax({
            url: URL + "/user/comments/create",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $(_self).removeAttr('disabled');
                if(data.status == 200) {
                    $('.cflag[data-target='+_self.attr('data-target')+']').val('');
                    $.growl.notice({title: 'Success' ,message: data.message });
                	// getComments(_self.attr('data-target'));
                	getSinglePost(_self.attr('data-target'));
                	$(_self).val('');
                }
                else {
                    $.growl.error({title: 'Error', message: data.message });
                }
            }
        });
	}
});

$(document).on('click touchstart', '.delComment', function(event) {
    event.preventDefault();
    var _self = $(this);
    swal({
        title: "Are you sure?",
        text: "You want to delete selected comment",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: 'Yes sure!',
        cancelButtonText: "No cancel",
        closeOnConfirm: true,
        closeOnCancel: true
    },function(flag){
        if(flag){
            _self.attr('disabled', 'disabled');
            var formData = new FormData();
            formData.append('target', _self.attr('data-target'));
            // create comment
            $.ajax({
                url: URL + "/user/comments/delete",
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    $(_self).removeAttr('disabled');
                    if(data.status == 200) {
                        $.growl.notice({title: 'Success', message: data.message });
                        // getComments(_self.attr('data-target').split('-')[1]);
                        getSinglePost(_self.attr('data-target').split('-')[1]);
                    }
                    else {
                        $.growl.error({title: 'Error', message: data.message });
                    }
                }
            }); 
        }
    });

});

$(document).on('click touchstart', '.editComment', function(event) {
    event.preventDefault();
    var _self = $(this);
    $('html, body').animate({
        scrollTop : $('.comment_body[data-target='+_self.attr('data-target').split('-')[1]+']').offset().top-150
    }, 500);
    $('.comment_body[data-target='+_self.attr('data-target').split('-')[1]+']').val($('.cmnt_list_item_body_'+_self.attr('data-target').split('-').join('_')).text());
    $('.cflag[data-target='+_self.attr('data-target').split('-')[1]+']').val(_self.attr('data-target').split('-')[0]);
    $.growl.warning({title: 'Note', message: 'press <b>ESC</b> to cancel'});
});

$(document).on('click touchstart', '.load_more_comments', function(event) {
    event.preventDefault();
    var _self = $(this);
    getComments(_self.attr('data-target'), _self.attr('data-page'));
});

function getComments(target, page = 1) {
    var formData = new FormData();
    formData.append('target', target);
    formData.append('page', page);
    // create comment
    $.ajax({
        url: URL + "/user/comments/get",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            $('#cmnt_list_'+target).html(data.html);
        }
    });
}


/*
|----------------------------------------------------------------------
| posts like here
|----------------------------------------------------------------------
*/
$(document).on('click touchstart', '.likePost', function(event) {
    event.preventDefault();
    var _self = $(this);
    _self.attr('disabled', 'disabled');
    var formData = new FormData();
    formData.append('target', _self.attr('data-target'));
    // create comment
    $.ajax({
        url: URL + "/user/likes/create",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            $(_self).removeAttr('disabled');
            if(data.status == 200) {
                if(data.class == 1) {
                    _self.addClass('active');
                }
                else {
                    _self.removeClass('active');
                }
                // _self.find('span').text(data.likes);
                $.growl.notice({title: 'Success' ,message: data.message });
                getSinglePost(_self.attr('data-target'));
            }
            else {
                $.growl.error({title: 'Error', message: data.message });
            }
        }
    });
});

/*
|----------------------------------------------------------------------
| posts section here
|----------------------------------------------------------------------
*/

var currentpostmedia = [];
// store current media files for post
function storepostmedia(files) {
    var validFileTypes = ["image/jpeg", "image/png", "image/jpg", "video/mp4", "video/flv", "video/wvm", "video/mp3", "video/wav"];
    var totalFiles = files.files.length;
    var currentIndex = currentpostmedia.length;
    for (var i = 0; i < totalFiles; i++) {
        if ($.inArray(files.files[i].type, validFileTypes) >= 0) {
            currentpostmedia[currentpostmedia.length] = files.files[i];

            // preview section
            var fileReader = new FileReader();
            fileReader.onload = function(file){
                $('.file-previews').append('<span class="file-item"><i class="fa fa-times post-inner-file-item" data-target="'+currentIndex+'"></i><img style="width:100px;height: 100px;margin: 5px 5px 10px 10px;border-radius:3px;" src='+file.target.result+' /><span>');
                currentIndex = currentIndex+1;
            };
            fileReader.readAsDataURL(files.files[i]);
        }
    }
    $('#post_media').val(null);
}

// direct upload media files for post
function directuploadpostmedia(files) {
    var validFileTypes = ["image/jpeg", "image/png", "image/jpg", "video/mp4", "video/flv", "video/wvm", "video/mp3", "video/wav"];
    var totalFiles = files.files.length;
    var _postId = $('.edit_post_form input[id=edit_post_id]').val();
    var formData = new FormData();
    formData.append('target', $('.edit_post_form input[id=edit_post_id]').val());
    for (var i = 0; i < totalFiles; i++) {
        if ($.inArray(files.files[i].type, validFileTypes) >= 0) {
            formData.append('photos[]', files.files[i]);
        }
    }
    // $('.file-previews-edit').show();
    $(files).val(null);
    // upload photos
    $.ajax({
        url: URL + "/user/post/photos/create",
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            $.growl.notice({title: 'Success' ,message: 'Image(s) uploaded successfully' });
            getSinglePost(_postId);
            $('.edit_post_form input[id=edit_post_id]').val(_postId);
            $('.file-previews-edit').prepend(data);
        }
    });
}

// send request to update post
$(document).on('click touchstart', '#edit_post_btn', function() {
    // validate post form first
    if($('span.file-item').length <= 0 && $('#edit_post_body').val().length <= 0) {
        $.growl.error({title: 'Error',message: 'Please add some content to post'});
        return false;
    }
    else {
        $('#edit_post_btn').attr('disabled', 'disabled');
        var formData = new FormData();
        formData.append('content', $('#edit_post_body').val());
        formData.append('target', $('#edit_post_id').val());
        // create post
        $.ajax({
            url: URL + "/user/posts/update",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $('#edit_post_btn').removeAttr('disabled');
                if(data.status == 200) {
                    $.growl.notice({title: 'Success' ,message: data.message });
                    $('#edit-post').modal('hide');
                    $('.file-previews-edit').html('');
                    $('#edit_post_body').val('');
                    $('#edit_post_media').val(null);
                    getSinglePost($('#edit_post_id').val());
                }
                else {
                    $.growl.error({title: 'Error', message: data.message });
                }
            }
        });
    }
});

// send request to create post
$(document).on('click touchstart', '#post_btn', function() {
    // validate post form first
    if(currentpostmedia.length <= 0 && $('#post_body').val().length <= 0) {
        $('.post-error').text('Please add some content to post');
        return false;
    }
    else {
        $('#post_btn').attr('disabled', 'disabled');
        $('.post-error').text('');
        var formData = new FormData();
        if(currentpostmedia.length > 0) {
            for (var i = 0; i < currentpostmedia.length; i++) {
                formData.append('media[]', currentpostmedia[i]);
            }
        }
        else {
            formData.append('media[]', currentpostmedia);
        }
        formData.append('content', $('#post_body').val());
        // create post
        $.ajax({
            url: URL + "/user/posts/create",
            type: "POST",
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $('#post_btn').removeAttr('disabled');
                if(data.status == 200) {
                    $('.file-previews').html('');
                    $('#post_body').val('');
                    $('#post_media').val(null);
                    currentpostmedia = [];
                    // $('.post-success').text(data.message);
                    $.growl.notice({title: 'Success' ,message: data.message });
                    setTimeout(function(){
                        getPosts();
                    }, 3000);
                }
                else {
                    $.growl.error({title: 'Error', message: data.message });
                }
            }
        });
    }
});

// get required pot content
$(document).on('click touchstart', '.edit_post', function(event) {
    var _self = $(this);
    var formData = new FormData();
    formData.append('target', _self.attr('data-target'));
    $('.loader').removeClass('hidden');
    $('.edit_post_form textarea[id=edit_post_body]').val('');
    $('.edit_post_form input[id=edit_post_id]').val('');
    $('.edit_post_form').addClass('hidden');
    $('#edit-post').modal('show');
    // get requested post content
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
                $('.loader').addClass('hidden');
                $('.edit_post_form textarea[id=edit_post_body]').val(data.post.post_content);
                $('.edit_post_form input[id=edit_post_id]').val(data.post.post_id);
                $('.edit_post_form').removeClass('hidden');
                $('.file-previews-edit').html(data.post.photoshtml);
            }
            else {
                $('#edit-post').modal('hide');
                $.growl.error({title: 'Error' ,message: data.message });
            }
        }
    });
});

// delete post image
$(document).on('click', '.inner-file-item', function(){
    var _self = $(this);
    var _postId = $('.edit_post_form input[id=edit_post_id]').val();
    var formData = new FormData();
    formData.append('target', _self.attr('data-target'));
    // delete requested photo
    $.ajax({
        url: URL + "/user/post/delete/photos",
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
                $.growl.notice({title: 'Success', message: data.message});
                getSinglePost(_postId);
                $('.edit_post_form input[id=edit_post_id]').val(_postId);
                _self.parent().remove();
            }
            else {
                $.growl.error({title: 'Error', message: data.message});
            }
        }
    });
});

// delete requested post
$(document).on('click', '.delete_post', function(){
    var _self = $(this);
    swal({
        title: "Are you sure?",
        text: "You want to delete selected post",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: 'Yes sure!',
        cancelButtonText: "No cancel",
        closeOnConfirm: true,
        closeOnCancel: true
    },function(flag){
        if(flag){
            var formData = new FormData();
            formData.append('target', _self.attr('data-target'));
            // delete requested post
            $.ajax({
                url: URL + "/user/post/delete",
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
                        $.growl.notice({title: 'Success', message: data.message});
                        $('#post_list_'+_self.attr('data-target')).remove();
                    }
                    else {
                        $.growl.error({title: 'Error', message: data.message});
                    }
                }
            });
        }
    });
});

// get all posts
function getPosts(target) {
	// get posts
    $.ajax({
        url: URL + "/user/posts/get",
        type: "POST",
        data: new FormData(),
        contentType: false,
        cache: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            if(data.status == 200) {
            	$('#posts_content_section').html(data.html);
            }
        }
    });
}

// get single post on update
function getSinglePost(target) {
    $('#edit_post_id').val('');
    var formData = new FormData();
    formData.append('target', target);
    // get posts
    $.ajax({
        url: URL + "/user/post/get/"+target,
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
                $('#post_list_'+target).html(data.html);
            }
        }
    });
}

/*
|----------------------------------------------------------------------
| locations section here
|----------------------------------------------------------------------
*/

function uploadandpreview(files) {
    $('.file-previews').hide();
    var validFileTypes = ["image/jpeg", "image/png", "image/jpg"];
    var totalFiles = files.files.length;
    var invalidcount = 0;
    for (var i = 0; i < totalFiles; i++) {
        // preview section
        var fileReader = new FileReader();
        fileReader.onload = (evt) => {
            $('.file-previews').append('<img style="width: 60px;height: 60px;margin: 5px;border-radius: 3px;" src='+evt.target.result+' />');
        };
        fileReader.readAsDataURL(files.files[i]);

        if ($.inArray(files.files[i].type, validFileTypes) === -1) {
            invalidcount += 1;
        }
    }

    if(invalidcount > 0) {
        $('.file-previews').hide();
        $('.file-previews').html('');
        $('input[name=location_photos]').addClass('is-invalid');
        $('.file-error').text('Please upload only jpeg and png files');
        $('input[name=location_photos]').val(null);
    }
    else {
        $('.file-previews').show();
        $('input[name=location_photos]').removeClass('is-invalid');
        $('.file-error').text('');
    }
}

// locations related section
function directupload(files) {
    var validFileTypes = ["image/jpeg", "image/png", "image/jpg"];
    var totalFiles = files.files.length;
    var myFormData = new FormData();
    myFormData.append('location', $('input[name=id]').val());
    var invalidcount = 0;
    for (var i = 0; i < totalFiles; i++) {
        myFormData.append('photos[]', files.files[i]);
        if ($.inArray(files.files[i].type, validFileTypes) === -1) {
            invalidcount += 1;
        }
    }

    if(invalidcount > 0) {
        $(files).addClass('is-invalid');
        $('.file-error').text('Please upload only jpeg and png files');
        $(files).val(null);
    }
    else {
        $('.file-previews').show();
        $(files).removeClass('is-invalid');
        $(files).val(null);
        $('.file-error').text('');
        // upload photos
        $.ajax({
            url: URL + "/user/location/upload/photos",
            type: "POST",
            data: myFormData,
            contentType: false,
            cache: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                $('.file-success').text("Image(s) uploaded successfully");
                $('.file-previews').prepend(data);
                setTimeout(function(){
                    $('.file-success').text("");
                }, 3000);
            }
        });
    }
}

// delete location image
$(document).on('click', '.loc-image', function(){
    $('.file-success').text("");
    $('.file-error').text("");
    var _self = $(this);
    var myFormData = new FormData();
    myFormData.append('target', _self.attr('data-target'));
    // delete requested photo
    $.ajax({
        url: URL + "/user/location/delete/photos",
        type: "POST",
        data: myFormData,
        contentType: false,
        cache: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            if(data.status == 200) {
                $('.file-success').text(data.message);
                _self.parent().remove();
                setTimeout(function(){
                    $('.file-success').text("");
                }, 3000);
            }
            else {
                $('.file-error').text(data.message);
                setTimeout(function(){
                    $('.file-error').text("");
                }, 3000);
            }
        }
    });
});

// delete location ar file
$(document).on('click', '.inner_ar_preview', function(){
    $('.ar-success').text("");
    $('.ar-error').text("");
    var _self = $(this);
    var myFormData = new FormData();
    myFormData.append('location', $('input[name=id]').val());
    // delete requested photo
    $.ajax({
        url: URL + "/user/location/delete/arfile",
        type: "POST",
        data: myFormData,
        contentType: false,
        cache: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (data) {
            if(data.status == 200) {
                $('.ar-success').text(data.message);
                _self.parent().remove();
                setTimeout(function(){
                    $('.ar-success').text("");
                }, 3000);
            }
            else {
                $('.ar-error').text(data.message);
                setTimeout(function(){
                    $('.ar-error').text("");
                }, 3000);
            }
        }
    });
});


/*
|----------------------------------------------------------------------
| user profile section here
|----------------------------------------------------------------------
*/
function uploadandpreviewprofile(files) {
    $('.file-previews').hide();
    var validFileTypes = ["image/jpeg", "image/png", "image/jpg"];
    var totalFiles = files.files.length;
    var myFormData = new FormData();
    var invalidcount = 0;
    for (var i = 0; i < totalFiles; i++) {
        if ($.inArray(files.files[i].type, validFileTypes) === -1) {
            invalidcount += 1;
        }
        else {
            myFormData.append('photo', files.files[i]);
        }
    }

    if(invalidcount > 0) {
        $.growl.error({title: 'Error' ,message: 'Please upload only jpeg and png files' });
        $('input[name=profile_photo]').val(null);
    }
    else {
        myFormData.append('type', $('input[name=profile_target]').val());
        // send upload request
        $.ajax({
            url: URL + "/user/profile/photo/upload",
            type: "POST",
            data: myFormData,
            contentType: false,
            cache: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (data) {
                var target = $('input[name=after_upload_target]').val();
                if(data.status == 200) {
                    $.growl.notice({title: 'Success', message : data.message});
                    $('#update-photo').modal('hide');
                    if($('input[name=profile_target]').val() == 'profile') {
                        $('.profile-'+target+' img').attr('src', data.url);
                        $('.top-'+target+' img').attr('src', data.url);
                        getPosts();
                    }
                    else {
                        $('.'+target+' img').attr('src', data.url);
                    }
                }
                else {
                    $.growl.notice({title: 'Success', message : data.message});
                }
            }
        }); 
    }
}

$('#update-photo').on('hidden.bs.modal', function () {
    $('input[name=profile_target]').val(null);
    $('input[name=after_upload_target]').val(null);
    $('input[name=profile_photo]').val(null);
});