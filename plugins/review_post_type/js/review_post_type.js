var $ = jQuery.noConflict();
$(document).ready(function() {
	$(document).on('click', '.add_input', function() {	
		if($(this).siblings('.review_input').length < 5) {
        	$(this).closest('.form-field').append($(this).prev('.review_input').clone().val(''));
        }
    });

    var meta_gallery_frame;
    // Runs when the image button is clicked.
    $('#review_gallery_button').on('click', function(e){

        //Attachment.sizes.full.url/ Prevents the default action from occuring.
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
                console.log(attachment.attributes);
                if('image' === attachment.attributes.type) {
                    imageHTML += '<li><div class="review_gallery_container"><div class="review_gallery_close"><img id="'+attachment.attributes.id+'" src="'+attachment.attributes.sizes.full.url+'"><span class="gallery-item-hover-close">X</span></div><input type="hidden" name="gallery_item['+attachment.attributes.id+']" value="'+attachment.attributes.sizes.full.url+'"></div></li>';
                } else if ('video' === attachment.attributes.type) {
                    imageHTML += '<li><div class="review_gallery_container"><div class="review_gallery_close"><img class="video-thumb" id="'+attachment.attributes.id+'" src="'+attachment.attributes.image.src+'"><span class="gallery-video-title">'+attachment.attributes.name+'</span><span class="gallery-item-hover-close">X</span></div><input type="hidden" name="gallery_item['+attachment.attributes.id+']" value="'+attachment.attributes.url+'"></div></li>';
                }
                    
            });
            metadataString = imageIDArray.join(",");

            if (metadataString) {  
                $(".review_gallery_list").append(imageHTML);
        
            }
        });

        // Finally, open the modal
        meta_gallery_frame.open();

    }); 

    $(document).on('click', '.review_gallery_close' ,function(e){

        e.preventDefault();

        if (confirm('Você tem certeza que deseja excluir este item da galeria?')) {
           $(this).closest('li').remove();
           
        }
    }); 
});