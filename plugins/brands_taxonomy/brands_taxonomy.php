<?php 

	//hook into the init action and call create_book_taxonomies when it fires and create a custom taxonomy name it brands for your posts
	add_action( 'init', 'create_brands_taxonomy', 0 );
	function create_brands_taxonomy() {

		// Add new taxonomy, make it non hierarchical 
		//first do the translations part for GUI

		$labels = array(
			'name' => _x( 'Marcas', 'taxonomy general name' ),
			'singular_name' => _x( 'Marca', 'taxonomy singular name' ),
			'search_items' =>  __( 'Procurar Marcas' ),
			'all_items' => __( 'Todas Marcas' ),
			'add_item' => __( 'Editar Marca' ), 
			'update_item' => __( 'Atualizar Marca' ),
			'add_new_item' => __( 'Adicionar Nova Marca' ),
			'new_item_name' => __( 'Nova Marca' ),
			'menu_name' => __( 'Marcas' ),
		);    

		// Now register the taxonomy

		register_taxonomy('brands',array('product'), array(
			'hierarchical' => false,
			'labels' => $labels,
			'show_ui' => true,
			'show_admin_column' => true,
			'query_var' => true,
			'rewrite' => array( 'slug' => 'marcas' ),
			'meta_box_cb' => 'brands_tax_in_post_type_meta_box'
		));

	}

	//Getting marcas terms into post edit
    function brands_tax_in_post_type_meta_box($post) {
        $terms = get_terms('brands', array('hide_empty' => false, 'orderby' => 'name', 'order' => 'ASC'));
        $post = get_post();
        $brand_term = get_the_terms($post->ID, 'brands');
        $name = '';
        if (!is_wp_error($brand_term) || $brand_term) {
            if (isset($brand_term[0]) && isset($brand_term[0]->name)) {
                $name = $brand_term[0]->name;
            }
        }
        wp_nonce_field('save_brands_tax_meta_box', 'custom_taxonomy_brands_tax_data_entry_nonce');
        ?>

        <select name="brand">
            <option value="">Selecione a marca</option>
            <?php if(is_array($terms)) { ?>
                <?php foreach ($terms as $term): ?>
                    <option
                        value='<?php esc_attr_e($term->name); ?>' <?php selected($term->name, $name); ?> ><?php esc_attr_e($term->name); ?></option>
                <?php endforeach; ?>
            <?php } ?>
        </select>

        <?php
    }

    // Saving brands term to post save
    function save_brands_tax_meta_box($post_id) {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!isset($_POST['brand'])) {
            return;
        }

        if (
            !isset($_POST['custom_taxonomy_brands_tax_data_entry_nonce'])
            || !wp_verify_nonce($_POST['custom_taxonomy_brands_tax_data_entry_nonce'], 'save_brands_tax_meta_box')
        ) {
            print 'Sorry, your nonce did not verify.';
            exit;
        }

        $brand = sanitize_text_field($_POST['brand']);
        if (empty($brand)) {
            wp_set_object_terms($post_id, null, 'brands', false);
        } else {
            $brandTerm = get_term_by('name', $brand, 'brands');
            if (!empty($brandTerm) && !is_wp_error($brandTerm)) {
                wp_set_object_terms($post_id, $brandTerm->term_id, 'brands', false);
            }
        }
    }

	//enqueue scripts for admin if is brands taxonomy
	add_action('current_screen', 'myScreen_ybuy_brands_taxonomy');
	function myScreen_ybuy_brands_taxonomy() {

		 if ('edit-brands' ===  get_current_screen()->id) {

			function my_admin_load_styles_and_scripts() {
		        wp_enqueue_media();

		        wp_register_script('ybuy_brands_taxonomy_js',get_stylesheet_directory_uri().'/plugins/brands_taxonomy/js/brands_taxonomy.js',array('jquery'));
		        wp_enqueue_script('ybuy_brands_taxonomy_js');

		        wp_register_style('ybuy_brands_taxonomy_css',get_stylesheet_directory_uri().'/plugins/brands_taxonomy/css/brands_taxonomy.css');
		        wp_enqueue_style('ybuy_brands_taxonomy_css');
		    }
		    add_action( 'admin_enqueue_scripts', 'my_admin_load_styles_and_scripts' );
		}
	}
	

	// A callback function to add a custom field to our "marcas" taxonomy  
	add_action( 'brands_add_form_fields', 'brands_taxonomy_custom_fields', 10, 2 );  
	function brands_taxonomy_custom_fields($taxonomy) {  

		//nonce for the custom fields of brands taxonomy
		wp_nonce_field('save_brands_taxonomy', 'brands_taxonomy_fields_nonce');
		?>  
			<div class="form-field">  
		        <label for="subtitle"><?php _e('Sub título'); ?></label>  
		        <input type="text" name="term_meta[subtitle]" id="term_meta[subtitle]" value="<?php echo esc_html($term_meta['subtitle'] ? $term_meta['subtitle'] : ''); ?>"><br />  
		        <p class="description"><?php _e('Texto que aparece em baixo do nome da marca.'); ?></p>  
			</div>
			<div class="form-field">  
				<label for="brands_logo"><?php _e( 'Marca Logo:', 'journey' ); ?></label>
				<img src="" alt="" class="brands_image_src brands_logo">
				<input type="hidden" name="term_meta[brands_logo]" id="term_meta[brands_logo]" class="brands_thumb" value="">
				<input class="upload_image_button button" name="_add_term_meta" id="_add_term_meta" type="button" value="Select/Upload Image" /> 
			</div>
			<div class="form-field">  
		        <label for="hint"><?php _e('Curiosidade'); ?></label>  
		        <input type="text" name="term_meta[hint]" id="term_meta[hint]" value="<?php echo esc_html($term_meta['hint'] ? $term_meta['hint'] : ''); ?>"><br />  
		        <p class="description"><?php _e('Curiosidade sobre a marca'); ?></p>  
			</div>
			<div class="form-field">  
				<label for="brands_image"><?php _e( 'Imagem destacada da Marca:', 'journey' ); ?></label>
				<img src="" alt="" class="brands_image_src brands_image">
				<input type="hidden" name="term_meta[brands_image]" id="term_meta[brands_image]" class="brands_thumb" value="">
				<input class="upload_image_button button" name="_add_term_meta" id="_add_term_meta" type="button" value="Select/Upload Image" /> 
			</div>
			
		  
		<?php 

	} 
	
	// Save the changes made on the "Brands" taxonomy, using our callback function
	add_action( 'create_brands', 'save_brands_taxonomy', 10, 2 ); 
	function save_brands_taxonomy($term_id, $tt_id) {

		if (!isset($_POST['brands_taxonomy_fields_nonce']) || !wp_verify_nonce($_POST['brands_taxonomy_fields_nonce'], 'save_brands_taxonomy')) {
            print 'Sorry, your nonce did not verify.';
            exit;
        } else {
            if ('' !== $_POST) {
            	$subtitle = sanitize_text_field($_POST['term_meta']['subtitle']);
                add_term_meta($term_id, 'subtitle', $subtitle, true);

                $brands_logo = sanitize_text_field($_POST['term_meta']['brands_logo']);
                add_term_meta($term_id, 'brands_logo', $brands_logo, true);

                $hint = sanitize_text_field($_POST['term_meta']['hint']);
                add_term_meta($term_id, 'hint', $hint, true);

                $brands_image = sanitize_text_field($_POST['term_meta']['brands_image']);
                add_term_meta($term_id, 'brands_image', $brands_image, true);
            }
        }
	}
	
	//Edit the mostra Taxonomy
	add_action('brands_edit_form_fields', 'edit_brands_taxonomy_fields', 10, 2);
    function edit_brands_taxonomy_fields($term, $taxonomy) {

        $brands_subtitle = get_term_meta($term->term_id, 'subtitle', true);
        $brands_brands_logo = get_term_meta($term->term_id, 'brands_logo', true);
        $brands_hint = get_term_meta($term->term_id, 'hint', true);
        $brands_brands_image = get_term_meta($term->term_id, 'brands_image', true);
        
        wp_nonce_field('save_brands_taxonomy', 'brands_taxonomy_fields_nonce');
        ?>
		
		<tr class="form-field term-group-wrap">
			<th scope="row"><label for="subtitle"><?php _e('Sub título'); ?></label></th>
			<td>
		        <div class="form-field term-group">
			        <input type="text" name="term_meta[subtitle]" id="term_meta[subtitle]" value="<?php echo esc_html($brands_subtitle); ?>"><br />  
			        <p class="description"><?php _e('Texto que aparece em baixo do nome da marca.'); ?></p>  
				</div>
			</td>
		</tr>
		<tr class="form-field term-group-wrap">
			<th scope="row"><label for="brands_logo"><?php _e( 'Marca Logo:', 'journey' ); ?></label></th>
			<td>
		        <div class="form-field term-group">
					<img src="<?php echo esc_html($brands_brands_logo); ?>" alt="" class="brands_image_src brands_logo">
					<input type="hidden" name="term_meta[brands_logo]" id="term_meta[brands_logo]" class="brands_thumb" value="<?php echo esc_html($brands_brands_logo); ?>">
					<input class="upload_image_button button" name="_add_term_meta" id="_add_term_meta" type="button" value="Select/Upload Image" /> 
				</div>
			</td>
		</tr>
		<tr class="form-field term-group-wrap">
			<th scope="row"><label for="hint"><?php _e('Curiosidade'); ?></label></th>
			<td>
		        <div class="form-field term-group">
		        	<input type="text" name="term_meta[hint]" id="term_meta[hint]" value="<?php echo esc_html($brands_hint); ?>"><br />  
		        	<p class="description"><?php _e('Curiosidade sobre a marca'); ?></p>
				</div>
			</td>
		</tr>
		<tr class="form-field term-group-wrap">
			<th scope="row"><label for="brands_image"><?php _e( 'Imagem destacada da Marca:', 'journey' ); ?></label></th>
			<td>
		        <div class="form-field term-group">
		        	<img src="<?php echo esc_html($brands_brands_image);?>" alt="" class="brands_image_src brands_image">
		        	<input type="hidden" name="term_meta[brands_image]" id="term_meta[brands_image]" class="brands_thumb" value="<?php echo esc_html($brands_brands_image); ?>">
					<input class="upload_image_button button" name="_add_term_meta" id="_add_term_meta" type="button" value="Select/Upload Image" /> 
				</div>
			</td>
		</tr>

        <?php
    }

    // Edit Save the mostra Taxonomy
    add_action('edited_brands', 'update_brands_taxonomy_meta', 10, 2);
    function update_brands_taxonomy_meta($term_id, $tt_id) {

        if (!isset($_POST['brands_taxonomy_fields_nonce']) || !wp_verify_nonce($_POST['brands_taxonomy_fields_nonce'], 'save_brands_taxonomy')) {
            print 'Sorry, your nonce did not verify.';
            exit;
        } else {
            if ('' !== $_POST) {

            	$subtitle = sanitize_text_field($_POST['term_meta']['subtitle']);
                update_term_meta($term_id, 'subtitle', $subtitle);

                $brands_logo = sanitize_text_field($_POST['term_meta']['brands_logo']);
                update_term_meta($term_id, 'brands_logo', $brands_logo);

                $hint = sanitize_text_field($_POST['term_meta']['hint']);
                update_term_meta($term_id, 'hint', $hint);

                $brands_image = sanitize_text_field($_POST['term_meta']['brands_image']);
                update_term_meta($term_id, 'brands_image', $brands_image);
            }
        }
    }