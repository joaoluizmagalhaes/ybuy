var $ = jQuery.noConflict();
$(document).ready(function() {

	var url = window.location.href;
	var meta_gallery_frame;

	$('#add_review').on('click', function(){

		if('' == $(this).data('logged')) {
			window.location = '/ybuy/wordpress/login?next_page='+url;
		} else if (1 == $(this).data('logged')) {
			$('.review_modal').slideToggle();
		}
	});

	$('.image_picker').on('click', function(e) {

		//Attachment.sizes.thumbnail.url/ Prevents the default action from occuring.
        e.preventDefault();

        // If the frame already exists, re-open it.
        if ( meta_gallery_frame ) {
            meta_gallery_frame.open();
            return;
        }

        // Sets up the media library frame
        meta_gallery_frame = wp.media.frames.meta_gallery_frame = wp.media({
            multiple: true
        });            

        // When an image is selected, run a callback.
        meta_gallery_frame.on('select', function() {
            var imageIDArray = [];
            var imageHTML = '';
            var metadataString = '';
            images = meta_gallery_frame.state().get('selection');
            images.each(function(attachment) {
                imageIDArray.push(attachment.attributes.id);
         
                if('image' === attachment.attributes.type) {
                    imageHTML += '<li><div class="product_gallery_container"><div class="product_gallery_close"><img id="'+attachment.attributes.id+'" src="'+attachment.attributes.sizes.thumbnail.url+'"><span class="gallery-item-hover-close">X</span></div><input type="hidden" name="review_gallery['+attachment.attributes.id+']" value="'+attachment.attributes.sizes.thumbnail.url+'"></div></li>';
                } else if ('video' === attachment.attributes.type) {
                    imageHTML += '<li><div class="product_gallery_container"><div class="product_gallery_close"><img class="video-thumb" id="'+attachment.attributes.id+'" src="'+attachment.attributes.image.src+'"><span class="gallery-video-title">'+attachment.attributes.name+'</span><span class="gallery-item-hover-close">X</span></div><input type="hidden" name="review_gallery['+attachment.attributes.id+']" value="'+attachment.attributes.url+'"></div></li>';
                }
                    
            });
            metadataString = imageIDArray.join(",");

            if (metadataString) {  
                $(".image_gallery_wrapper").append(imageHTML);
        
            }
        });

        // Finally, open the modal
        meta_gallery_frame.open();
	});

    $(document).on('click', '.add_field', function() {  
        if($(this).siblings('.review_input').length < 5) {
            $(this).closest('.form-field').append($(this).prev('.review_input').clone().val(''));
        }
    });

    //add the user ID to the like dislike button, after the page is loaded
    $(window).on('load', function(){
        var yuui = $('.yuui').data('yuui');
        $('.like, .dislike').data('user', yuui);
    });

    //when a like or dislike is clicked, call the ajax to save this information on the database
    $('.like, .dislike').on('click', function() {
        var userID      = $(this).data('user'),
            reviewID    = $(this).data('post'),
            nonce       = $(this).data('nonce'),
            likeDislike = $(this).attr('class');
            elementClicked = $(this);

        setLikeDislike(reviewID, userID, likeDislike, nonce, elementClicked);
      
    });

    //the ajax function
    var setLikeDislike = function(reviewID, userID, likeDislike, nonce, elementClicked) {
         $.ajax({
            url: '/ybuy/wordpress/like-dislike-custom/',
            type: 'POST',
            data: {
                like_dislike : likeDislike,
                user_id : userID,
                review_id : reviewID,
                like_nonce : nonce
            },
            dataType: 'JSON',
            success: function(dataReturn) {

                if('liked' == dataReturn.response || 'disliked' == dataReturn.response) {
                    $(elementClicked).closest('.like').attr('disabled', 'disabled');
                    $(elementClicked).closest('.dislike').attr('disabled', 'disabled');
                } else {
                
                    if('like' == likeDislike) {
                        $(elementClicked).closest('.like').html(dataReturn.response + ' concordam');
                    } else if ('dislike' == likeDislike) {
                        $(elementClicked).closest('.dislike').html(dataReturn.response + ' discordam');
                    }
                }
            }
        });
    }
});