<?php 

	/** YBUY PRICE **/	
	// [ybuy-price price='' seller='' url='' text='']
	add_shortcode( 'ybuy-price', 'ybuyPriceOnPost' );
	function ybuyPriceOnPost( $atts ) {

		$options = shortcode_atts( array(
			'product-id'  => '',
			'number-of-offers' => '',
		), $atts );

		$output = '<div class="half-content">';
			$output .= '<div class="product-offer-id" style="display: none" data-product-id="'. $options['product-id'] .'" data-number-of-offers="'. $options['number-of-offers'] .'"></div>';
			/*$output .= '<ul>';
				if( !$qt || $qt < 1 ) {
					$output .= '<li>';
						$output .= '<strong>R$ ' . number_format( $options['price'], 2, ',', '.' ) . '</strong>';
						$output .= '<span>' . $options['seller'] . '</span>';
						$output .= '<a href="' . $options['url'] . '" target="_blank">Comprar</a>';
					$output .= '</li>';
				
			$output .= '</ul>';*/
		$output .= '</div>';

		return $output;

	}

	add_action('media_buttons', 'price_button', 996);
	function price_button() {
		$form = <<<EOF
			<script>
				function price_insert(){
				tinyMCE.activeEditor.selection.setContent('[ybuy-price product-id=\\"'+jQuery('#TB_ajaxContent .edit-shortcode-form [name=\\"product-id\\"]').val()+'\\" number-of-offers=\\"'+jQuery('#TB_ajaxContent .edit-shortcode-form [name=\\"number-of-offers\\"]').val()+'\\" ]');

				jQuery("#TB_closeWindowButton").trigger("click");

				return false;
				}
			</script>
			<div id="price-form" style="display:none;">
				<form class="edit-shortcode-form">
					<table class="form-table product_data">
						<tbody class="model_wrapp">
							<tr class="form-field term-group-wrap">
				            	<th scope="row">
				            		<label for="product_category">Categoria:</label>
				            	</th>
				        		<td>
				        			<div class="form-field term-group seller-form">
				        				<select name="seller-category" id="seller-category">
				        					<option value="" selected="selected" disabled="disabled">Selectione uma categoria:</option>
				        				</select>
				        			</div>
				        		</td>
				        	</tr>
						    <tr>
				            	<th scope="row">
				            		<label for="product_item">Digite o produto:</label>
				            	</th>
				        		<td>
				        			<div class="form-field term-group seller-form">
				        				<input type="text" name="product_item" id="product_item" placeholder="Digite o nome do produto" class="product_input" autocomplete="off">
				        				<button class="search_product button button-large">Buscar</button>
				        			</div>
				        			<div class="product_list_wrapper">
				        				<ul class="list-group" id="result"></ul>
				        			</div>
				        		</td>
						    </tr>

						    <tr>
				            	<th scope="row">
				            		<label for="product_item">Produto escolhido:</label>
				            	</th>
				        		<td>
				        			<div class="product_list_wrapper">
				        				<ul class="product_chosen">
				        				</ul>
				        			</div>
				        		</td>
						    </tr>
						    <tr class="form-field term-group-wrap">
				            	<th scope="row">
				            		<label for="product_category">Quandas ofertas deseja mostrar:</label>
				            	</th>
				        		<td>
				        			<div class="form-field term-group seller-form">
				        				<select name="number-of-offers" id="number-of-offers">
				        					<option selected="selected" value="1">1</option>
				        					<option value="2">2</option>
				        					<option value="3">3</option>
				        					<option value="4">4</option>
				        					<option value="5">5</option>
				        				</select>
				        			</div>
				        		</td>
				        	</tr>
			        	</tbody>
					</table>
					<div style="text-align:right;padding-top:15px;">
						<button class="button button-primary button-large" onclick="return price_insert();">Inserir</button>
					</div>                                
				</form>
			</div>
EOF;

		$current = get_current_screen();

		if($current->id == 'post'){
			echo $form.'<a href="/?TB_inline&inlineId=price-form" id="modal-box-insert-price" class="button add_media hide-if-no-js thickbox"><span class="dashicons dashicons-cart"></span> Preço</a>';
		}
	}
