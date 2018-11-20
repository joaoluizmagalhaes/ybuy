<?php 

	//load the subacategory page if it exists
	add_filter( 'category_template', 'wpd_subcategory_template' );
	function wpd_subcategory_template( $template ) {
	    $cat = get_queried_object();
	    if( 0 < $cat->category_parent )
	        $template = locate_template( 'subcategory.php' );
	    return $template;
	}

	//load admin css and js to edit category page
	add_action('current_screen', 'myScreen_ybuy_category');
	function myScreen_ybuy_category() {
		if('edit-category' === get_current_screen()->id) {

			function my_admin_load_styles_and_scripts() {
		        wp_enqueue_media();

		        wp_register_script('ybuy_category_js',get_stylesheet_directory_uri().'/plugins/category-functionalities/js/category.js',array('jquery'));
		        wp_enqueue_script('ybuy_category_js');

		        wp_register_style('ybuy_category_css',get_stylesheet_directory_uri().'/plugins/category-functionalities/css/category.css');
		        wp_enqueue_style('ybuy_category_css');
		    }
		    add_action( 'admin_enqueue_scripts', 'my_admin_load_styles_and_scripts' );
		}
			
	}

	//load the add category image form
	add_action( 'category_add_form_fields', 'my_category_add_meta_fields', 10, 2 );
	function my_category_add_meta_fields( $taxonomy ) { ?>
	    <div class="form-field">  
			<label for="category_image"><?php _e( 'Imagem destacada da Categoria:', 'journey' ); ?></label>
			<img src="" alt="" class="category_image_src category_image">
			<input type="hidden" name="term_meta[category_image]" id="term_meta[category_image]" class="category_thumb" value="">
			<input class="upload_image_button button" name="_add_term_meta" id="_add_term_meta" type="button" value="Select/Upload Image" /> 
		</div>
		<?php
	}
	
	//load the edit category image form
	add_action( 'category_edit_form_fields', 'my_category_edit_meta_fields', 10, 2 );
	function my_category_edit_meta_fields( $term, $taxonomy ) {

	    $category_image = get_term_meta( $term->term_id, 'category_image'); ?>

	    <div class="form-field">  
			<label for="category_image"><?php _e( 'Imagem destacada da Categoria:', 'journey' ); ?></label>
			<img src="<?php echo esc_url($category_image[0]); ?>" alt="" class="category_image_src category_image">
			<input type="hidden" name="term_meta[category_image]" id="term_meta[category_image]" class="category_thumb" value="<?php echo esc_url($category_image[0]); ?>">
			<input class="upload_image_button button" name="_add_term_meta" id="_add_term_meta" type="button" value="Select/Upload Image" /> 
		</div>
	    <?php
	}

	//save and update the term meta for category image
	add_action('created_category', 'my_category_save_meta', 10, 2);
	add_action('edited_category', 'my_category_save_meta', 10, 2);
	function my_category_save_meta($term_id, $tag_id) {
	
		if(isset ($_POST['term_meta']['category_image'])) {
			$category_image = sanitize_text_field($_POST['term_meta']['category_image']);
			update_term_meta($term_id, 'category_image', $category_image);
		} else {
			update_term_meta($term_id, 'category_image', '');
		}
	}