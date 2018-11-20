<?php

	add_action('init', 'init_product_post_type');
	function init_product_post_type() {

		$labels = array(
		    'add_new'            => _x('Novo Produto', 'product'),
	        'add_new_item'       => _x('Adicionar novo Produto', 'product'),
	        'edit_item'          => _x('Editar Produto', 'product'),
	        'menu_name'          => _x('Produtos', 'product'),
	        'name'               => _x('Produtos', 'product'),
	        'new_item'           => _x('Novo Produto', 'product'),
	        'not_found'          => _x('Produto não encontrado', 'product'),
	        'not_found_in_trash' => _x('Produto não encontrado na lixeira', 'product'),
	        'search_items'       => _x('Pesquisar Produtos', 'product'),
	        'singular_name'      => _x('Produto', 'product'),
	        'view_item'          => _x('Ver Produto', 'product')
	    );

	    $args = array(
	        'labels'             => $labels,
	        'menu_icon'          => 'dashicons-cart',
	        'menu_position'      => 4,
	        'public'             => true,
	        'has_archive'        => true,
	        'taxonomies'         => array('brands', 'category', 'post_tag'),
	        'supports'           => array('title', 'thumbnail', 'editor', 'excerpt', 'author', 'comments'),
	        'show_ui'            => true,
	        'rewrite'             => array( 'slug' => 'produtos')
	    );
	    register_post_type('product', $args);
	}

	//save the brands taxonomy with the custom post.
	add_action('save_post_product', 'save_brands_tax_meta_box');

	add_action('current_screen', 'myScreen_ybuy_product_post_type');
	function myScreen_ybuy_product_post_type() {

		 if ('product' === get_current_screen()->id) {

		 	add_action( 'admin_enqueue_scripts', 'my_admin_load_styles_and_scripts' );
			function my_admin_load_styles_and_scripts() {
		        wp_enqueue_media();

		        wp_register_script('ybuy_product_post_type_js',get_stylesheet_directory_uri().'/plugins/product_post_type/js/product_post_type.js',array('jquery'));
		        wp_enqueue_script('ybuy_product_post_type_js');

		        wp_register_style('ybuy_product_post_type_css',get_stylesheet_directory_uri().'/plugins/product_post_type/css/product_post_type.css');
		        wp_enqueue_style('ybuy_product_post_type_css');
		    }
		}
	}

	//enqueue js exclusive for single product
    add_action( 'wp_enqueue_scripts', 'product_load_styles_and_scripts' );
	function product_load_styles_and_scripts() {
		if (is_singular('product')) {
	        wp_enqueue_media();
	        //wp_enqueue_style( 'wp-admin' );

	        wp_register_script('ybuy_product_js',get_stylesheet_directory_uri().'/__assets/js/plugins/product.js',array('jquery'));
	        wp_enqueue_script('ybuy_product_js');

   	    }
	}
	
	// Add the Meta Box for custom fields
	add_action('add_meta_boxes', 'product_add_custom_meta_box');
	function product_add_custom_meta_box() {
		add_meta_box(
	        'custom_fields_meta_box', // $id
	        'Dados do produto', // $title
	        'product_custom_fields_show_custom_meta_box', // $callback
	        'product', // $page
	        'normal', // $context
	        'high'); // $priority
	    add_meta_box(
	        'custom_gallery_meta_box', // $id
	        'Galeria de imagens e vídeos', // $title
	        'product_show_custom_meta_box', // $callback
	        'product', // $page
	        'normal', // $context
	        'high'); // $priority
	    add_meta_box(
	        'custom_details_meta_box', // $id
	        'Ficha técnica', // $title
	        'product_details_show_custom_meta_box', // $callback
	        'product', // $page
	        'normal', // $context
	        'high'); // $priority
	    add_meta_box(
	        'custom_collections_meta_box', // $id
	        'Coleções', // $title
	        'product_collections_custom_meta_box', // $callback
	        'product', // $page
	        'normal', // $context
	        'high'); // $priority
	     add_meta_box(
	        'custom_sellers_meta_box', // $id
	        'Lojas', // $title
	        'product_sellers_custom_meta_box', // $callback
	        'product', // $page
	        'normal', // $context
	        'high'); // $priority
	}

	// The Callback for gallery
	function product_show_custom_meta_box($post) {

		$products_youtube = get_post_meta($post->ID, 'product_youtube');
    
    	?>
	        <table class="form-table">
	            <tr>
	            	<th>
	            		<span>Selecione as imagens e/ou vídeos para a galeria.<br>(Resolução ideal: 960x960)</span>
	            	</th>
	        		<td>
					<?php
			            // get value of this field if it exists for this post
			            $gallery_items = get_post_meta($post->ID, 'gallery_item'); ?>
			            <ul class="product_gallery_list"><?php
				           	if("" === $gallery_items[0] || NULL === $gallery_items[0]) {
				           		echo "Esse produto ainda não possui uma galeria";
				           	} else {
				           		foreach ($gallery_items[0] as $item => $value) {
				           			
				           			//getting the type of the media
				           			$media_type = get_post_mime_type($item);

				           			//testing the media type, video or image
				           			if(false !== strpos($media_type, 'video')) {
			           				?>
			           					<li>
			           						<div class="product_gallery_container">
			           							<div class="product_gallery_close">
			           								<img class="video-thumb" id="<?php echo esc_attr($item); ?>" src="http://localhost:8888/yBuy/wordpress/wp-includes/images/media/video.png">
													<span class="gallery-video-title"><?php echo esc_html(get_the($item)); ?></span>
													<span class="gallery-item-hover-close">X</span>
			           							</div>
			           							<input type="hidden" name="gallery_item[<?php echo esc_attr($item); ?>]" value="<?php echo esc_url($value); ?>">
			           						</div>
			           					</li>
				           			<?php
				           			} else if(false !== strpos($media_type, 'image')) {
			           				?>
			           					<li>
			           						<div class="product_gallery_container">
			           							<div class="product_gallery_close">
			           								<img id="<?php echo esc_attr($item); ?>" src="<?php echo esc_attr($value); ?>" style="max-width: 150px;">
			           								<span class="gallery-item-hover-close">X</span>
			           							</div>
			           							<input type="hidden" name="gallery_item[<?php echo esc_attr($item); ?>]" value="<?php echo esc_url($value); ?>">
			           						</div>
			           					</li>
			           				<?php
				           			}
				           		}
				           	} ?>
			           	</ul>
                		<div class="product_gallery_button_container">
                			<input id="product_gallery_button" class="button" type="button" value="Adicionar Galeria" />
                		</div>
			        </td>
			    </tr>
			    <tr class="form-field term-group-wrap">
		            	<th scope="row">
		            		<label for="product_youtube">ID vídeo YouTube:</label>
		            	</th>
		        		<td>
		        			<div class="form-field term-group">
		        				<?php if("" === $products_youtube[0] || NULL === $products_youtube[0]) { ?>
										<input type="text" id="product_youtube" name="product_youtube[]" class="product_input" value="">
				        			<?php } else {
				        				foreach ($products_youtube[0] as $product_youtube) { ?>
				        					<input type="text" id="product_youtube" name="product_youtube[]" class="product_input" value="<?php echo esc_html($product_youtube); ?>">
				        				<?php } 
				        			} 
				        		?>
		        				<span class="add_input">+</span>
		        			</div>
		        		</td>
		        	</tr>
			</table>
		<?php
		// end table
	}

	//The Callback for custom fields
	function product_custom_fields_show_custom_meta_box($post) {

		$products_capacity = get_post_meta($post->ID, 'product_capacity');
		$products_color = get_post_meta($post->ID, 'product_color');
		$products_ean = get_post_meta($post->ID, 'product_ean');

		?>
			<table class="form-table product_data">
				<tbody class="model_wrapp">
					<tr class="form-field term-group-wrap">
		            	<th scope="row">
		            		<label for="product_capacity">EAN:</label>
		            	</th>
		        		<td>
		        			<div class="form-field term-group">
		        				<?php if("" === $products_ean[0] || NULL === $products_ean[0]) { ?>
										<input type="text" id="product_ean" name="product_ean[]" class="product_input" value="">
				        			<?php } else {
				        				foreach ($products_ean[0] as $product_ean) { ?>
				        					<input type="text" id="product_ean" name="product_ean[]" class="product_input" value="<?php echo esc_html($product_ean); ?>">
				        				<?php } 
				        			} 
				        		?>
		        				<span class="add_input">+</span>
		        			</div>
		        		</td>
		        	</tr>
					<tr class="form-field term-group-wrap">
		            	<th scope="row">
		            		<label for="product_capacity">Capacidade:</label>
		            	</th>
		        		<td>
		        			<div class="form-field term-group">
		        				<?php if("" === $products_capacity[0] || NULL === $products_capacity[0]) { ?>
										<input type="text" id="product_capacity" name="product_capacity[]" class="product_input" value="">
				        			<?php } else {
				        				foreach ($products_capacity[0] as $product_capacity) { ?>
				        					<input type="text" id="product_capacity" name="product_capacity[]" class="product_input" value="<?php echo esc_html($product_capacity); ?>">
				        				<?php } 
				        			} 
				        		?>
		        				<span class="add_input">+</span>
		        			</div>
		        		</td>
		        	</tr>
		        	<tr class="form-field term-group-wrap">
		            	<th scope="row">
		            		<label for="product_color">Cor:</label>
		            	</th>
		        		<td>
		        			<div class="form-field term-group">
		        				<?php if("" === $products_color[0] || NULL === $products_color[0]) { ?>
		        					<input type="text" id="product_color" name="product_color[]" class="product_input" value=""> 
			        			<?php } else { 
			        				foreach ($products_color[0] as $product_color) { ?>
			        					<input type="text" id="product_color" name="product_color[]" class="product_input" value="<?php echo esc_html($product_color); ?>">
			        				<?php } 
			        			} ?>
		        				<span class="add_input">+</span>
		        			</div>
		        		</td>
		        	</tr>
	        	</tbody>
			</table>
		<?php
	}

	//The Callback for details field
	function product_details_show_custom_meta_box($post) {
		$details_text = get_post_meta($post->ID, 'details_text');

		if(NULL !== $details_text[0] || "" !== $details_text[0]){
			$content = html_entity_decode($details_text[0]);
		} else {
			$content = '';
		}
		
		$editor_id = 'details_text';
		wp_editor( $content, $editor_id );
	}

	//The callback for collections fields
	function product_collections_custom_meta_box($post) {
		$products_collection = get_post_meta($post->ID, 'product_collection');

		?>
			<table class="form-table product_data">
				<tbody class="model_wrapp">
					<tr class="form-field term-group-wrap">
		            	<th scope="row">
		            		<label for="product_collection_title">Título:</label>
		            	</th>
		        		<td>
		        			<div class="form-field term-group">
		        				<?php if("" === $products_collection[0]['title'] || NULL === $products_collection[0]['title']) { ?>
										<input type="text" id="product_collection_title" name="product_collection[title][]" class="product_input" value="">
				        			<?php } else {
				        				foreach ($products_collection[0]['title'] as $product_collection_title) { ?>
				        					<input type="text" id="product_collection_title" name="product_collection[title][]" class="product_input" value="<?php echo esc_html($product_collection_title); ?>">
				        				<?php } 
				        			} 
				        		?>
		        				<span class="add_input">+</span>
		        			</div>
		        		</td>
		        	</tr>
		        	<tr class="form-field term-group-wrap">
		            	<th scope="row">
		            		<label for="product_collection_link">Link:</label>
		            	</th>
		        		<td>
		        			<div class="form-field term-group">
		        				<?php if("" === $products_collection[0]['link'] || NULL === $products_collection[0]['link']) { ?>
		        					<input type="text" id="product_collection" name="product_collection[link][]" class="product_input" value=""> 
			        			<?php } else { 
			        				foreach ($products_collection[0]['link'] as $product_collection_link) { ?>
			        					<input type="text" id="product_collection" name="product_collection[link][]" class="product_input" value="<?php echo esc_html($product_collection_link); ?>">
			        				<?php } 
			        			} ?>
		        				<span class="add_input">+</span>
		        			</div>
		        		</td>
		        	</tr>
	        	</tbody>
			</table>
		<?php
	}

	//The callback for collections fields
	function product_sellers_custom_meta_box($post) {
		$product_seller_id = array_filter(get_post_meta($post->ID, 'product_seller_id'));
		$product_seller_thumb = array_filter(get_post_meta($post->ID, 'product_seller_thumb'));
		$product_seller_name = array_filter(get_post_meta($post->ID, 'product_seller_name'));

		?>
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
		        				<input type="text" name="product_item" id="product_item" placeholder="Digite o nome do produto" class="product_input">
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
		        				<?php if(!empty($product_seller_id)) { ?>
		        					<li class="list-group-item link-class" data-product-id="<?php echo esc_attr($product_seller_id[0]); ?>"><img src="<?php echo esc_url($product_seller_thumb[0]);?>" heigth="40px" width="40" class="img-thumbnail" alt=""><?php echo esc_html($product_seller_name[0]);?></li>
		        					<input type="hidden" name="product_seller_id" value="<?php echo esc_attr($product_seller_id[0]); ?>">
		        					<input type="hidden" name="product_seller_thumb" value="<?php echo esc_attr($product_seller_thumb[0]); ?>">
		        					<input type="hidden" name="product_seller_name" value="<?php echo esc_attr($product_seller_name[0]); ?>">
		        				<?php } ?>
		        				</ul>
		        			</div>
		        		</td>
				    </tr>
	        	</tbody>
			</table>
		<?php
	}

	//saving the custom fields for product
	add_action('save_post_product', 'product_save_custom_fields_items');
	function product_save_custom_fields_items($postid) {

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
	        return '';
	    }

	    if ('' !== $_POST) {
	    
	    	if(is_array($_POST['gallery_item'])) {
	    		foreach ($_POST['gallery_item'] as $key => $gallery_item) {
	    			$_POST['gallery_item'][$key] = sanitize_text_field($gallery_item);
	    		}
	    	}

	    	if(is_array($_POST['product_capacity'][0])) {
	    		foreach ($_POST['product_capacity'][0] as $key => $product_capacity) {
	    			$_POST['product_capacity'][0][$key] = sanitize_text_field($product_capacity);
	    		}
	    	}

	    	if(is_array($_POST['product_youtube'][0])) {
	    		foreach ($_POST['product_youtube'][0] as $key => $product_youtube) {
	    			$_POST['product_youtube'][0][$key] = sanitize_text_field($product_youtube);
	    		}
	    	}

	    	if(is_array($_POST['product_color'][0])) {
	    		foreach ($_POST['product_color'][0] as $key => $product_color) {
	    			$_POST['product_color'][0][$key] = sanitize_text_field($product_color);
	    		}
	    	}

	    	if(is_array($_POST['product_ean'][0])) {
	    		foreach ($_POST['product_ean'][0] as $key => $product_ean) {
	    			$_POST['product_ean'][0][$key] = sanitize_text_field($product_ean);
	    		}
	    	}

	    	if(is_array($_POST['product_collection'][0])) {
	    		foreach ($_POST['product_collection'][0] as $key => $product_collection) {
	    			$_POST['product_collection'][0][$key] = sanitize_text_field($product_collection);
	    		}
	    	}

	    	if(is_array($_POST['product_seller'][0])) {
	    		foreach ($_POST['product_seller'][0] as $key => $product_seller) {
	    			$_POST['product_seller'][0][$key] = sanitize_text_field($product_seller);
	    		}
	    	}

	    	$_POST['details_text'] = sanitize_text_field(htmlentities($_POST['details_text']));

	    	$_POST['product_seller_id'] = sanitize_text_field(htmlentities($_POST['product_seller_id']));
	    	$_POST['product_seller_name'] = sanitize_text_field(htmlentities($_POST['product_seller_name']));
	    	$_POST['product_seller_thumb'] = sanitize_text_field(htmlentities($_POST['product_seller_thumb']));
	    	
	    	update_post_meta($postid, 'gallery_item', $_POST['gallery_item']);
			update_post_meta($postid, 'product_capacity', $_POST['product_capacity']);
			update_post_meta($postid, 'product_youtube', $_POST['product_youtube']);
			update_post_meta($postid, 'product_color', $_POST['product_color']);
			update_post_meta($postid, 'product_ean', $_POST['product_ean']);
			update_post_meta($postid, 'details_text', $_POST['details_text']);
			update_post_meta($postid, 'product_collection', $_POST['product_collection']);
			update_post_meta($postid, 'product_seller_id', $_POST['product_seller_id']);
			update_post_meta($postid, 'product_seller_thumb', $_POST['product_seller_thumb']);
			update_post_meta($postid, 'product_seller_name', $_POST['product_seller_name']);
		}
	}
