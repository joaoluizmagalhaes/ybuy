<?php 

	add_action('current_screen', 'myScreen_ybuy_default_post_type');
	function myScreen_ybuy_default_post_type() {

		 if ('post' === get_current_screen()->id) {

		 	add_action( 'admin_enqueue_scripts', 'my_admin_load_styles_and_scripts' );
			function my_admin_load_styles_and_scripts() {
		        wp_enqueue_media();

		        wp_register_script('ybuy_default_post_js',get_stylesheet_directory_uri().'/plugins/default_post/js/default_post.js',array('jquery'));
		        wp_enqueue_script('ybuy_default_post_js');

		        wp_register_style('ybuy_default_post_css',get_stylesheet_directory_uri().'/plugins/default_post/css/default_post.css');
		        wp_enqueue_style('ybuy_default_post_css');
		    }


		}
	}


	//add a metabox to default post type.
	// Add the Meta Box for custom fields
	add_action('add_meta_boxes', 'default_post_add_custom_meta_box');
	function default_post_add_custom_meta_box() {
		add_meta_box(
	        'custom_fields_meta_box', // $id
	        'Hero Image', // $title
	        'default_post_custom_fields_show_custom_meta_box', // $callback
	        'post', // $page
	        'normal', // $context
	        'high'); // $priority
	}

	// The Callback for gallery
	function default_post_custom_fields_show_custom_meta_box($post) { ?>
        <table class="form-table">
            <tr>
            	<th>
            		<span>Selecione a Hero Image.</span>
            		<br>
            		<span>(Resolução ideal: 1920px X 400px)</span>
            	</th>
        		<td>
				<?php
		            // get value of this field if it exists for this post
		            $widget_image = get_post_meta($post->ID, 'widget_image'); ?>
		            <ul class="product_gallery_list"><?php
			           	if("" === $widget_image[0] || NULL === $widget_image[0]) {
			           		echo "Esse post ainda não possui uma Hero Image.";
			           	} else { ?>
           					<li>
           						<div class="product_gallery_container">
           							<div class="product_gallery_close">
           								<img src="<?php echo esc_url($widget_image[0]); ?>" style="max-width: 150px;object-fit: cover;">
           								<span class="gallery-item-hover-close">X</span>
           							</div>
           							<input type="hidden" name="widget_image" value="<?php echo esc_url($widget_image[0]); ?>">
           						</div>
           					</li>
			           <?php } ?>
		           	</ul>
            		<div class="product_gallery_button_container">
            			<input id="product_gallery_button" class="button" type="button" value="Adicionar Imagem" />
            		</div>
		        </td>
		    </tr>
		</table>
	<?php } 

	add_action('save_post', 'default_post_save_custom_fields_items');
	function default_post_save_custom_fields_items($postid) {

		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
	        return '';
	    }

	    if (isset($_POST['widget_image'])) {

	    
	    	$_POST['widget_image'] = sanitize_text_field($_POST['widget_image']);
	    	
	    	update_post_meta($postid, 'widget_image', $_POST['widget_image']);
			
		}
	}