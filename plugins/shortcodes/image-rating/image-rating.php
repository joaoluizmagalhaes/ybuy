<?php
 
	/** IMAGENS COM RATING **/
	// [imgs-ybuy position='' img='' score-number='' score-text='' stars='' ]LEGENDA[/imgs-ybuy]
	add_shortcode( 'ybuy-img', 'imgsWithRating' );
	function imgsWithRating( $atts, $content = null ) {

		$options = shortcode_atts( array(
			'position'     => null,
			'img'          => '',
			'score-number' => '',
			'score-text'   => '',
			'stars'        => '',
			'class'		   => '',
		), $atts );

		$position = intval($options['position']);

		$stars = intval($options['stars']);

		$output = '<div class="' . $options['class'] . '">';
		$output .= 		'<div class="img-wrapper">';
		
							if(!$position === 0 || $options['position']) {
								$output .= '<span>' . $options['position'] . 'º</span>';
							}

		$output .= 			'<div class="review">';
 		$output .= 				'<strong>' . $options['score-number'] . '</strong>';
 		$output .=				'<span>' . $options['score-text'] . '</span>';
 		$output .=  			'<div class="review-element">';

							 		for($i = 1; $i <= $stars; $i++) {
							 			$output .= '<span></span>';
							 		}

							 		for($i = $stars; $i < 5; $i++) {
							 			$output .= '<span class="e"></span>';
							 		}
		$output .= 				'</div>';					 	
 		$output .= 			'</div>';
 		$output .= 			'<img src=' . $options['img'] . '" alt="">';
 		$output .= 		'</div>';
 		$output .= 		'<p class="wp-caption-text"><em>' . $content . '</em></p>';
 		$output .= '</div>';

		return $output;

	}

	add_action('media_buttons', 'rating_button', 996);
	function rating_button() {
		$form = <<<EOF
			<script>
				function rating_insert(){
				tinyMCE.activeEditor.selection.setContent('[ybuy-img class=\\"'+jQuery('#TB_ajaxContent .edit-shortcode-box [name=\\"class\\"]').val()+'\\" position=\\"'+jQuery('#TB_ajaxContent .edit-shortcode-box [name=\\"position\\"]').val()+'\\" img=\\"'+jQuery('#TB_ajaxContent .edit-shortcode-box [name=\\"rating_image\\"]').val()+'\\" score-number=\\"'+jQuery('#TB_ajaxContent .edit-shortcode-box [name=\\"score-number\\"]').val()+'\\" score-text=\\"'+jQuery('#TB_ajaxContent .edit-shortcode-box [name=\\"score-text\\"]').val()+'\\" stars=\\"'+jQuery('#TB_ajaxContent .edit-shortcode-box [name=\\"stars\\"]').val()+'\\"]'+jQuery('#TB_ajaxContent .edit-shortcode-box [name=\\"text\\"]').val()+'\\[/ybuy-img]'); 

				jQuery("#TB_closeWindowButton").trigger("click");

				return false;
				}
			</script>
			<div id="rating-form" style="display:none;">
				<form class="edit-shortcode-form">
					<div class="modal-external-video edit-shortcode-box">
						<div class="input_modal_row">
							<label for="class">Tamanho da imagem</label>
			                <select name="class" id="">
								<option value="img-bf">Tela inteira</option>
								<option value="img-hs">1/3 da tela de desktop (ex: Livro)</option>
			                </select>
			                <br>
			                <label for="position">Posição</label>
			                <input type="text" style="width:100%" class="regular-text" name="position" value="" placeholder="">
			                <div class="rating-wrapper">
								<button class="button button-primary button-large rating">Inserir Imagem</button>
							</div>
							<label for="score-number">Pontuação</label>
			                <input type="text" style="width:100%" class="regular-text" name="score-number" value="" placeholder="">
			                <label for="score-text">Avaliação</label>
			                <input type="text" style="width:100%" class="regular-text" name="score-text" value="" placeholder="">
			                <label for="stars">Estrelas</label>
			                <select name="stars" id="">
								<option value="1">1</option>
								<option value="2">2</option>
								<option value="3">3</option>
								<option value="4">4</option>
								<option value="5">5</option>
			                </select>
			                <br>
			                <label for="text">Texto</label>
               				<textarea style="width:100%" class="regular-text" name="text" value="" placeholder=""></textarea>
						</div>
						<div style="text-align:right;padding-top:15px;">
							<button class="button button-primary button-large" onclick="return rating_insert();">Inserir</button>
						</div>                                
					</div>
				</form>
			</div>
EOF;

		$current = get_current_screen();

		if($current->id == 'post'){
			echo $form.'<a href="/?TB_inline&inlineId=rating-form" id="modal-box-insert-rating" class="button add_media hide-if-no-js thickbox"><span class="dashicons dashicons-star-empty"></span> Rating</a>';
		}
	}