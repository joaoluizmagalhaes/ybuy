jQuery(document).ready(function() {
	jQuery('.upload_image_button').click(function() {
		var button = jQuery(this);

		wp.media.editor.send.attachment = function(props, attachment) {
			
			jQuery(button).prev('.category_thumb').val(attachment.url);
			jQuery(button).prevAll('.category_image_src').attr('src', attachment.url);
		}
		wp.media.editor.open(this);
		return false;
	});
});