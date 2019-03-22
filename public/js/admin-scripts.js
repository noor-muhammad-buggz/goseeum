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
            url: URL + "/locations/upload/photos",
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
$(document).on('click', '.inner-file-item', function(){
    $('.file-success').text("");
    $('.file-error').text("");
    var _self = $(this);
    var myFormData = new FormData();
    myFormData.append('target', _self.attr('data-target'));
    // delete requested photo
    $.ajax({
        url: URL + "/locations/delete/photos",
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
        url: URL + "/locations/delete/arfile",
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