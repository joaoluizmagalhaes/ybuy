<?php 

add_shortcode('drag_image','drag_image');
function drag_image($atts,$content=''){
	$draggable = '<div class="covered">';
	$draggable .= 	'<img src="' . esc_url($atts['before']) .'" alt="">';
	$draggable .= 	'<img src="' . esc_url($atts['after']) . '" alt="">';
	$draggable .= '</div>';
	$draggable .= '<br>';


	return $draggable;
}

add_action('media_buttons', 'drag_button', 996);
function drag_button() {
	$form = <<<EOF
		<script>
			function drag_insert(){
			tinyMCE.activeEditor.selection.setContent('[drag_image before=\\"'+jQuery('#TB_ajaxContent .edit-shortcode-box [name=\\"widget_image_before\\"]').val()+'" after=\\"'+jQuery('#TB_ajaxContent .edit-shortcode-box [name=\\"widget_image_after\\"]').val()+'\\"]'); 

			jQuery("#TB_closeWindowButton").trigger("click");

			return false;
			}
		</script>
		<div id="drag-form" style="display:none;">
			<form class="edit-shortcode-form">
			      <div class="modal-external-video edit-shortcode-box">
			          <div class="input_modal_row">
			          	  <div class="before-wrapper">
			              	<img src="" alt="" class="before">
			              </div>
			              <button class="button button-primary button-large before shortcode" data-position="before">Inserir Imagem "Antes"</button>
			              <div class="after-wrapper">
						  	<img src="" alt="" class="after">
						  </div>
			              <button class="button button-primary button-large after shortcode" data-position="after">Inserir Imagem "Depois"</button>
			          </div>
			          <div style="text-align:right;padding-top:15px;">
			            <button class="button button-primary button-large" onclick="return drag_insert();">Inserir</button>
			          </div>                                
			      </div>
			</form>
		</div>
EOF;

  $current = get_current_screen();

  if($current->id == 'post'){
    echo $form.'
      <a href="/?TB_inline&inlineId=drag-form" id="modal-box-insert-drag" class="button add_media hide-if-no-js thickbox"><span class="dashicons dashicons-image-flip-horizontal"></span> Antes e Depois</a>';
  }
}