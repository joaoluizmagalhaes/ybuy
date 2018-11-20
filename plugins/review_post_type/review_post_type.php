<?php 

	add_action('init', 'init_review_post_type');
	function init_review_post_type() {

		$labels = array(
		    'add_new'            => _x('Nova Resenha', 'review'),
	        'add_new_item'       => _x('Adicionar nova Resenha', 'review'),
	        'edit_item'          => _x('Editar Resenha', 'review'),
	        'menu_name'          => _x('Resenhas', 'review'),
	        'name'               => _x('Resenhas', 'review'),
	        'new_item'           => _x('Nova Resenha', 'review'),
	        'not_found'          => _x('Resenha não encontrada', 'review'),
	        'not_found_in_trash' => _x('Resenha não encontrada na lixeira', 'review'),
	        'search_items'       => _x('Pesquisar Resenhas', 'review'),
	        'singular_name'      => _x('Resenha', 'review'),
	        'view_item'          => _x('Ver Resenha', 'review')
	    );

	    $args = array(
	        'labels'             => $labels,
	        'menu_icon'          => 'dashicons-list-view',
	        'menu_position'      => 4,
	        'public'             => true,
	        'has_archive'        => true,
	        'supports'           => array('title', 'author', 'comments'),
	        'show_ui'            => true,
	        'rewrite'			 => $rewrite,
	        						'capability_type' => array('review', 'reviews'),
	        						'map_meta_cap'    => true,
	    );
	    register_post_type('review', $args);
	}

	add_action('current_screen', 'myScreen_ybuy_review_post_type');
	function myScreen_ybuy_review_post_type() {

		 if ('review' === get_current_screen()->id) {

		 	add_action( 'admin_enqueue_scripts', 'my_admin_review_load_styles_and_scripts' );
			function my_admin_review_load_styles_and_scripts() {
		        wp_enqueue_media();

		        wp_register_script('ybuy_review_post_type_js',get_stylesheet_directory_uri().'/plugins/review_post_type/js/review_post_type.js',array('jquery'));
		        wp_enqueue_script('ybuy_review_post_type_js');

		        wp_register_style('ybuy_review_post_type_css',get_stylesheet_directory_uri().'/plugins/review_post_type/css/review_post_type.css');
		        wp_enqueue_style('ybuy_review_post_type_css');
		    }
		}
	}
	
	// Add the Meta Box for custom fields
	add_action('add_meta_boxes', 'review_add_custom_meta_box');
	function review_add_custom_meta_box() {
		add_meta_box(
	        'custom_fields_meta_box', // $id
	        'Sobre a Resenha', // $title
	        'review_custom_fields_show_custom_meta_box', // $callback
	        'review', // $page
	        'normal', // $context
	        'high'); // $priority
		add_meta_box(
	        'custom_gallery_meta_box', // $id
	        'Galeria de imagens e vídeos', // $title
	        'review_show_custom_meta_box', // $callback
	        'review', // $page
	        'normal', // $context
	        'high'); // $priority
	}
	
	function review_custom_fields_show_custom_meta_box($post) {

		$product_review_rate = get_post_meta($post->ID, 'product_review_rate');
		$product_id_reviews = get_post_meta($post->ID, 'product_id_reviews');
		$pros_reviews = get_post_meta($post->ID, 'pros_reviews');
		$cons_reviews = get_post_meta($post->ID, 'cons_reviews');
		$like_reviews = get_post_meta($post->ID, 'like_reviews');
		$dislike_reviews = get_post_meta($post->ID, 'dislike_reviews');
		$id_category = get_post_meta($post->ID, 'id_category');
		$id_brand = get_post_meta($post->ID, 'id_brand');

		?>
			<table class="form-table">
				<tbody>
					<tr class="term-group-wrap">
		            	<th scope="row">
		            		<label for="product_review_rate">Avaliação:</label>
		            	</th>
		        		<td>
		        			<div class="form-field term-group">
		        				<?php if(NULL === $product_review_rate[0] || '' === $product_review_rate[0]){ ?>
		        					<input type="text" id="product_review_rate" name="product_review_rate" class="review_input" readonly="readonly">
		        				<?php } else { ?>
		        					<input type="text" id="product_review_rate" name="product_review_rate" class="review_input" value="<?php echo esc_html($product_review_rate[0]); ?>" readonly="readonly">
		        				<?php } ?>
		        			</div>
		        		</td>
		        	</tr>
					<tr class="term-group-wrap">
		            	<th scope="row">
		            		<label for="product_id_reviews">Produto:</label>
		            	</th>
		        		<td>
		        			<div class="form-field term-group">
		        				<?php if(NULL === $product_id_reviews[0] || '' === $product_id_reviews[0]){ ?>
		        					<input type="text" id="product_id_reviews" name="product_id_reviews" class="review_input" readonly="readonly">
		        				<?php } else { ?>
		        					<input type="text" id="product_id_reviews" name="product_id_reviews" class="review_input" value="<?php echo esc_html($product_id_reviews[0]); ?>" readonly="readonly">
		        				<?php } ?>
		        			</div>
		        		</td>
		        	</tr>
					<tr class="term-group-wrap">
		            	<th scope="row">
		            		<label for="pros_reviews">Prós:</label>
		            	</th>
		        		<td>
		        			<div class="form-field term-group">
		        				<?php if(NULL === $pros_reviews[0] || '' === $pros_reviews[0]){ ?>
		        					<input type="text" id="pros_reviews" name="pros_reviews[]" class="review_input">
		        				<?php } else { 
		        					foreach ($pros_reviews[0] as $pros_review) { ?>
		        						<input type="text" id="pros_reviews" name="pros_reviews[]" class="review_input" value="<?php echo esc_html($pros_review);?>">
		        					<?php } 
	        					} ?>
		        				<span class="add_input">+</span>
		        			</div>
		        		</td>
		        	</tr>
		        	<tr class="term-group-wrap">
		            	<th scope="row">
		            		<label for="cons_reviews">Contras:</label>
		            	</th>
		        		<td>
		        			<div class="form-field term-group">
		        				<?php if(NULL === $cons_reviews[0] || '' === $cons_reviews[0]){ ?>
		        					<input type="text" id="cons_reviews" name="cons_reviews[]" class="review_input">
		        				<?php } else { 
		        					foreach ($cons_reviews[0] as $cons_review) { ?>
		        						<input type="text" id="cons_reviews" name="cons_reviews[]" class="review_input" value="<?php echo esc_html($cons_review);?>">
		        					<?php } 
	        					} ?>
		        				<span class="add_input">+</span>
		        			</div>
		        		</td>
		        	</tr>
		        	<tr class="term-group-wrap">
		            	<th scope="row">
		            		<label for="like_reviews">Concordam:</label>
		            	</th>
		        		<td>
		        			<div class="form-field term-group">
		        				<?php if(NULL === $like_reviews[0] || '' === $like_reviews[0]){ ?>
		        					<input type="text" id="like_reviews" name="like_reviews" class="review_input" readonly="readonly">
		        				<?php } else { ?>
		        					<input type="text" id="like_reviews" name="like_reviews" class="review_input" value="<?php echo esc_html($like_reviews[0]); ?>" readonly="readonly">
		        				<?php } ?>
		        			</div>
		        		</td>
		        	</tr>
		        	<tr class="term-group-wrap">
		            	<th scope="row">
		            		<label for="dislike_reviews">Discordam:</label>
		            	</th>
		        		<td>
		        			<div class="form-field term-group">
		        				<?php if(NULL === $dislike_reviews[0] || '' === $ldisike_reviews[0]){ ?>
		        					<input type="text" id="dislike_reviews" name="dislike_reviews" class="review_input" readonly="readonly">
		        				<?php } else { ?>
		        					<input type="text" id="dislike_reviews" name="dislike_reviews" class="review_input" value="<?php echo esc_html($dislike_reviews[0]); ?>" readonly="readonly">
		        				<?php } ?>
		        			</div>
		        		</td>
		        	</tr>
		        	<tr class="term-group-wrap">
		            	<th scope="row">
		            		<label for="id_category">ID Categoria:</label>
		            	</th>
		        		<td>
		        			<div class="form-field term-group">
		        				<?php if(NULL === $id_category[0] || '' === $id_category[0]){ ?>
		        					<input type="text" id="id_category" name="id_category" class="review_input" >
		        				<?php } else { ?>
		        					<input type="text" id="id_category" name="id_category" class="review_input" value="<?php echo esc_html($id_category[0]); ?>">
		        				<?php } ?>
		        			</div>
		        		</td>
		        	</tr>
		        	<tr class="term-group-wrap">
		            	<th scope="row">
		            		<label for="id_brand">ID Marca:</label>
		            	</th>
		        		<td>
		        			<div class="form-field term-group">
		        				<?php if(NULL === $id_brand[0] || '' === $id_brand[0]){ ?>
		        					<input type="text" id="id_brand" name="id_brand" class="review_input">
		        				<?php } else { ?>
		        					<input type="text" id="id_brand" name="id_brand" class="review_input" value="<?php echo esc_html($id_brand[0]); ?>">
		        				<?php } ?>
		        			</div>
		        		</td>
		        	</tr>
		        </tbody>
		    </table>
		<?php
	}

	// The Callback for gallery
	function review_show_custom_meta_box($post) {
    
    	?>
	        <table class="form-table">
	            <tr>
	            	<th>
	            		<span>Selecione as imagens e/ou vídeos para a galeria.</span>
	            	</th>
	        		<td>
					<?php
			            // get value of this field if it exists for this post
			            $gallery_items = get_post_meta($post->ID, 'gallery_item');?>
			            <ul class="review_gallery_list"><?php
				           	if("" === $gallery_items[0] || NULL === $gallery_items[0]) {
				           	} else {
				           		foreach ($gallery_items[0] as $item => $value) {
				           			
				           			//getting the type of the media
				           			$media_type = get_post_mime_type($item);

				           			//testing the media type, video or image
				           			if(false !== strpos($media_type, 'video')) {
			           				?>
			           					<li>
			           						<div class="review_gallery_container">
			           							<div class="review_gallery_close">
			           								<img class="video-thumb" id="<?php echo esc_attr($item); ?>" src="http://localhost:8888/yBuy/wordpress/wp-includes/images/media/video.png">
													<span class="gallery-video-title"><?php echo esc_html(get_the_title($item)); ?></span>
													<span class="gallery-item-hover-close">X</span>
			           							</div>
			           							<input type="hidden" name="gallery_item[<?php echo esc_attr($item); ?>]" value="<?php echo esc_attr($value); ?>">
			           						</div>
			           					</li>
				           			<?php
				           			} else if(false !== strpos($media_type, 'image')) {
			           				?>
			           					<li>
			           						<div class="review_gallery_container">
			           							<div class="review_gallery_close">
			           								<img id="<?php echo esc_attr($item); ?>" src="<?php echo esc_attr($value); ?>">
			           								<span class="gallery-item-hover-close">X</span>
			           							</div>
			           							<input type="hidden" name="gallery_item[<?php echo esc_attr($item); ?>]" value="<?php echo esc_attr($value); ?>">
			           						</div>
			           					</li>
			           				<?php
				           			}
				           		}
				           	} ?>
			           	</ul>
                		<div class="review_gallery_button_container">
                			<input id="review_gallery_button" class="button" type="button" value="Adicionar Galeria" />
                		</div>
			        </td>
			    </tr>
			</table>
		<?php
		// end table
	}

	//saving the custom fields for review
	add_action('save_post_review', 'review_save_gallery_items');
	function review_save_gallery_items($postid) {
		

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
	        return '';
	    }

	    if ('' !== $_POST) {
	    
	    	if(is_array($_POST['gallery_item'])) {
	    		foreach ($_POST['gallery_item'] as $key => $gallery_item) {
	    			$_POST['gallery_item'][$key] = sanitize_text_field($gallery_item);
	    		}
	    	}

	    	if(is_array($_POST['pros_reviews'][0])) {
	    		foreach ($_POST['pros_reviews'][0] as $key => $pros_reviews) {
	    			$_POST['pros_reviews'][0][$key] = sanitize_text_field($pros_reviews);
	    		}
	    	}

	    	if(is_array($_POST['cons_reviews'][0])) {
	    		foreach ($_POST['cons_reviews'][0] as $key => $cons_reviews) {
	    			$_POST['cons_reviews'][0][$key] = sanitize_text_field($cons_reviews);
	    		}
	    	}

	    	$_POST['product_id_reviews'] = sanitize_text_field($_POST['product_id_reviews']);
	    	$_POST['like_reviews'] = sanitize_text_field($_POST['like_reviews']);
	    	$_POST['dislike_reviews'] = sanitize_text_field($_POST['dislike_reviews']);
	    	$_POST['product_review_rate'] = sanitize_text_field($_POST['product_review_rate']);
	    	$_POST['id_category'] = sanitize_text_field($_POST['id_category']);
	    	$_POST['id_brand'] = sanitize_text_field($_POST['id_brand']);
	    	
	    }
		update_post_meta($postid, 'gallery_item', $_POST['gallery_item']);
		update_post_meta($postid, 'product_review_rate', $_POST['product_review_rate']);
		update_post_meta($postid, 'product_id_reviews', $_POST['product_id_reviews']);
		update_post_meta($postid, 'cons_reviews', $_POST['cons_reviews']);
		update_post_meta($postid, 'pros_reviews', $_POST['pros_reviews']);
		update_post_meta($postid, 'like_reviews', $_POST['like_reviews']);
		update_post_meta($postid, 'dislike_reviews', $_POST['dislike_reviews']);
		update_post_meta($postid, 'id_category', $_POST['id_category']);
		update_post_meta($postid, 'id_brand', $_POST['id_brand']);
	}

	//create query vars to load reviews list
	add_filter('query_vars', 'add_query_vars_to_review_list');
	function add_query_vars_to_review_list($aVars) {
		$aVars[] = 'product-id';
		$aVars[] = 'load-reviews-list';
		$aVars[] = 'load-like-dislike';
		$aVars[] = 'load-reviews-rate';
		$aVars[] = 'return-reviews-rate';

		return $aVars;
	}

	//create custom rewrite rules for the reviews list
	add_action('init', 'custom_rewrite_rules_for_reviews_list');
	function custom_rewrite_rules_for_reviews_list() {
		add_rewrite_rule('reviews-list-custom/(.+)/?', 'index.php?load-reviews-list=true&product-id=$matches[1]', 'top');
		add_rewrite_rule('like-dislike-custom', 'index.php?load-like-dislike=true', 'top');
		add_rewrite_rule('reviews-rate-custom/(.+)/?', 'index.php?load-reviews-rate=true&product-id=$matches[1]', 'top');
		add_rewrite_rule('return-reviews-rate/(.+)/?', 'index.php?return-reviews-rate=true&product-id=$matches[1]', 'top');
	}

	//create the query for the reviews list
	add_action('pre_get_posts', 'set_query_for_reviews_list', 100);
	function set_query_for_reviews_list($query) {

		$is_product_page_review_loaded = (bool)get_query_var('load-reviews-list');

		if($is_product_page_review_loaded) {
			
			if(!$query->is_main_query())
            	return;

            $product_id = get_query_var('product-id');

            set_query_var('post_type', 'review');
            set_query_var('post_per_page', 5);
            set_query_var('meta_key', 'product_id_reviews');
            set_query_var('meta_value', $product_id);

		}

	}

	//create the query for the reviews rate
	add_action('pre_get_posts', 'set_query_for_reviews_rate', 100);
	function set_query_for_reviews_rate($query) {

		$is_product_page_review_loaded = (bool)get_query_var('load-reviews-rate');

		if($is_product_page_review_loaded) {
			
			if(!$query->is_main_query())
            	return;

            $product_id = get_query_var('product-id');

            set_query_var('post_type', 'review');
            set_query_var('post_per_page', -1);
            set_query_var('meta_key', 'product_id_reviews');
            set_query_var('meta_value', $product_id);

		}

	}

	//create the query to reviews rate
	add_action('pre_get_posts', 'set_query_to_return_reviews_rate', 100);
	function set_query_to_return_reviews_rate($query) {

		$is_return_review_loaded = (bool)get_query_var('return-reviews-rate');

		if($is_return_review_loaded) {
			
			if(!$query->is_main_query())
            	return;

            $product_id = get_query_var('product-id');

            set_query_var('post_type', 'review');
            set_query_var('post_per_page', -1);
            set_query_var('meta_key', 'product_id_reviews');
            set_query_var('meta_value', $product_id);

		}

	}

	//create the list and pass it trough an json
	add_action('template_include', 'template_filter_product_review', 99);
	function template_filter_product_review($template_edition) {

		$is_product_page_review_loaded = (bool)get_query_var('load-reviews-list');
		$is_like_dislike_review_loaded = (bool)get_query_var('load-like-dislike');
		$is_product_rate_review_loaded = (bool)get_query_var('load-reviews-rate');
		$is_return_review_loaded = (bool)get_query_var('return-reviews-rate');

		//if is product single page, load the reviews for the current product
		if($is_product_page_review_loaded) {

			if(have_posts()) {
				ob_start();

					get_template_part('template-parts/tpl_reviews/tpl_review_list', 'none');

					$template = ob_get_contents();
				
				ob_end_clean();

				wp_reset_postdata();

           		wp_send_json(array('html'=>$template));
			}
		}

		//if the route is called, add likes and dislikes for the product review
		if($is_like_dislike_review_loaded) {

			$review_id = $_POST['review_id'];
			$user_id = $_POST['user_id'];
			$like_dislike = $_POST['like_dislike'];
			$like_dislike_nonce = $_POST['like_nonce'];

			$posts_user_likes = get_user_meta($user_id, 'posts_user_likes');

			if('like' === $like_dislike) {
				$users_post_likeDislike = get_post_meta($review_id, 'users_post_likes');
			} else if('dislike' === $like_dislike){
				$users_post_likeDislike = get_post_meta($review_id, 'users_post_dislikes');
			}
			
			$response = update_like_dislike_meta($like_dislike_nonce, $like_dislike, $review_id, $user_id, $posts_user_likes, $users_post_likeDislike);

			echo json_encode($response);
			return;
		}

		//if is product single page, load the reviews rate for the current product
		if($is_product_rate_review_loaded) {

			if(have_posts()) {
				ob_start();

					get_template_part('template-parts/tpl_reviews/tpl_review_rate', 'none');

					$template = ob_get_contents();
				
				ob_end_clean();

				wp_reset_postdata();

           		wp_send_json(array('html'=>$template));
			}
		}

		//if this rout is called, return the reviews rate and count
		if($is_return_review_loaded) {
			
			if(have_posts()) {
				$total_rating = 0;
				$count = 0;

				while (have_posts()) : the_post();
					$meta = get_post_meta(get_the_ID());

					$total_rating += $meta['product_review_rate'][0];
					$count++;

				endwhile;
				wp_reset_postdata();
					
				$average_rating = $total_rating/$count;
			}

			wp_send_json(array('count'=>$count,'rating'=>$average_rating));
		}

		return $template_edition;
	}

	//function to save a like or a dislik in a review
	function update_like_dislike_meta($like_dislike_nonce, $like_dislike, $review_id, $user_id, $posts_user_likes, $users_post_likeDislike) {

		if(wp_verify_nonce($like_dislike_nonce, 'like-nonce') || wp_verify_nonce($like_dislike_nonce, 'dislike-nonce')) {

			if(!in_array($review_id, $posts_user_likes[0]) && !in_array($user_id, $users_post_likeDislike[0])) {

				array_push($posts_user_likes[0], $review_id);
				array_push($users_post_likeDislike[0], $user_id);

				update_user_meta($user_id, 'posts_user_likes', $posts_user_likes[0]);
				
				if('like' === $like_dislike) {

					$previous_like_dislike = get_post_meta($review_id, 'like_reviews');
					update_post_meta($review_id, 'like_reviews',($previous_like_dislike[0] + 1));
					update_post_meta($review_id, 'users_post_likes', $users_post_likeDislike[0]);

				} else if('dislike' === $like_dislike){

					$previous_like_dislike = get_post_meta($review_id, 'dislike_reviews');
					update_post_meta($review_id, 'dislike_reviews',($previous_like_dislike[0] + 1));
					update_post_meta($review_id, 'users_post_dislikes', $users_post_likeDislike[0]);

				}
				
				//return the number of likes or dislikes to update in front end
				$response['response'] = $previous_like_dislike[0] + 1;

			} else {

				//if the user have alredy liked or disliked a reviewm return its status
				$response['response'] = $like_dislike . 'd';

			}
		}

		return $response;
	}

	function html_allowed(){
	    $parameters = array(
	        'alt'              => array(),
	        'autocomplete'     => array(),
	        'class'            => array(),
	        'data-filter-star' => array(),
	        'data-filter-type' => array(),
	        'data-nonce'       => array(),
	        'data-post'        => array(),
	        'data-sizes'       => array(),
	        'data-src'         => array(),
	        'data-srcset'      => array(),
	        'data-user'        => array(),
	        'data-value'       => array(),
	        'hidden'           => array(),
	        'href'             => array(),
	        'id'               => array(),
	        'meta-value'       => array(),
	        'rel'              => array(),
	        'selected'         => array(),
	        'src'              => array(),
	        'style'			   => array(),
	        'title'            => array(),
	        'value'            => array(),
	    );

	    $tags = array(
	        'a'        => $parameters,
	        'b'        => $parameters,
	        'button'   => $parameters,
	        'div'      => $parameters,
	        'figure'   => $parameters,
	        'form'     => $parameters,
	        'h1'       => $parameters,
	        'h2'       => $parameters,
	        'h3'       => $parameters,
	        'h4'       => $parameters,
	        'h5'       => $parameters,
	        'h6'       => $parameters,
	        'i'        => $parameters,
	        'img'      => $parameters,
	        'input'    => $parameters,
	        'label'    => $parameters,
	        'li'       => $parameters,
	        'option'   => $parameters,
	        'p'        => $parameters,
	        'select'   => $parameters,
	        'span'     => $parameters,
	        'textarea' => $parameters,
	        'ul'       => $parameters,
	    );

	    return $tags;
	}
