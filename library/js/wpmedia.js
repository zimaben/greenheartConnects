
(function($) {

$(document).ready( function() {
	var file_frame; // variable for the wp.media file_frame

	// attach a click event (or whatever you want) to some element on your page
	$( '#changeProfileLink' ).on( 'click', function( event ) {
		event.preventDefault();
		// if the file_frame has already been created, just reuse it
		if ( file_frame ) {
			file_frame.open();
			return;
		}

		file_frame = wp.media.frames.file_frame = wp.media({
        title: 'Upload Your Image',
        library: {
            author: userSettings.uid, // specific user-posted attachment
            type: 'image',
            //uploadedTo: null
        },
        button: {
            text: 'Select'
        },
        multiple: false
        });
        file_frame.on( 'open', function() {
            $('.media-router').first().children().removeClass('active');
            $('.media-router').first().children().first().click();
        });

		file_frame.on( 'select', function() {
            
			attachment = file_frame.state().get('selection').first().toJSON();
       
            jQuery.post(
                ajaxurl, 
                {
                    "action": "updateUserPhoto",
                    "data":   { "userid": attachment.author, "attachmentid": attachment.id }
                }
            ).done(function( response ){
                console.log(JSON.parse(response));
                response = JSON.parse(response);
                console.log(response.status);
                if(response.status == '200'){
                    console.log('passed status');
                    $fullavatar = attachment.url.replace('.jpg', '-233x233.jpg'); //get wordpress cropped versions of image
                    $tinyavatar = attachment.url.replace('.jpg', '-32x32.jpg');
                    $('.ghc.avatar-img').attr('src', $fullavatar);
                    $('#profile-updated').html('Profile Updated');
                    $('#profile-updated').delay( 4000 ).fadeOut( 400, function() {
                        $('#profile-updated').html('').show();
                      });      
                }

            });

        });
        //make sure we're always set to upload image
        wp.media.controller.Library.prototype.defaults.contentUserSetting = false;
		file_frame.open();
    });
    $( '#changeProfileLink2' ).on( 'click', function( event ) {
		event.preventDefault();
		// if the file_frame has already been created, just reuse it
		if ( file_frame ) {
			file_frame.open();
			return;
		}

		file_frame = wp.media.frames.file_frame = wp.media({
        title: 'Upload Your Image',
        library: {
            author: userSettings.uid, // specific user-posted attachment
            type: 'image',
            //uploadedTo: null
        },
        button: {
            text: 'Select'
        },
        multiple: false
        });
        file_frame.on( 'open', function() {
            $('.media-router').first().children().removeClass('active');
            $('.media-router').first().children().first().click();
        });

		file_frame.on( 'select', function() {
            
			attachment = file_frame.state().get('selection').first().toJSON();
            console.log(attachment);
            jQuery.post(
                ajaxurl, 
                {
                    "action": "updateUserPhoto",
                    "data":   { "userid": attachment.author, "attachmentid": attachment.id }
                }
            ).done(function( response ){
                console.log(JSON.parse(response));
                response = JSON.parse(response);
                console.log(response.status);
                if(response.status == '200'){
                    console.log('passed status');
                    $fullavatar = attachment.url.replace('.jpg', '-233x233.jpg'); //get wordpress cropped versions of image
                    $tinyavatar = attachment.url.replace('.jpg', '-32x32.jpg');
                    $('.ghc.avatar-img').attr('src', $fullavatar);
                    $('#profile-updated').html('Profile Updated');
                    $('#profile-updated').delay( 4000 ).fadeOut( 400, function() {
                        $('#profile-updated').html('').show();
                      });      
                }

            });

        });
        //make sure we're always set to upload image
        wp.media.controller.Library.prototype.defaults.contentUserSetting = false;
		file_frame.open();
	});
});

})(jQuery);