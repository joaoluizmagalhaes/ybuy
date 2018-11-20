var $ = jQuery.noConflict();
$(document).ready(function() {
	$('.upload_image_button').click(function() {
		var button = $(this);

		wp.media.editor.send.attachment = function(props, attachment) {

			console.log(button);
			console.log($(button).find('.user_profile_picture').val(attachment.url))

			$('.user_profile_picture').val(attachment.url);
			$('.user_profile_picture_src').attr('src', attachment.url);
		}
		wp.media.editor.open(this);
		return false;
	});

	 $(document).on('click', '.remove-image' ,function(e){

        e.preventDefault();

        if (confirm('Você tem certeza que deseja excluir esta imagem de perfil?')) {
           $('.user_profile_picture_src').attr('src', '');
           
        }
    }); 
});