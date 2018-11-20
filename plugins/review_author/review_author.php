<?php 
	//created the review_author role without any 
	add_role('review_author',
        __('Autor de Resenhas'),
        array(
            'read' => true,
            'edit_posts' => false,
            'delete_posts' => false,
            'publish_posts' => false,
            'upload_files' => true,
        )
    );

    //kick 'review_author' for the admin dashboard
    add_action( 'init', 'block_review_author_for_admin' );
	function block_review_author_for_admin() {
		if ( is_admin() && !( defined( 'DOING_AJAX' ) && DOING_AJAX )  ) {
			$user = get_userdata( get_current_user_id() );
			$caps = ( is_object( $user) ) ? array_keys($user->allcaps) : array();

			$block_access_to = array('review_author');

			if(array_intersect($block_access_to, $caps)) {
				wp_redirect( home_url() );
				exit;
			}
		}
	}

	//disable admin bar for review_author
	add_action('after_setup_theme', 'remove_admin_bar');
	function remove_admin_bar() {
		if (current_user_can( 'review_author' ) && !is_admin()) {
		  show_admin_bar(false);
		}
	}

    //enqueue scripts for admin if is profile page.
    add_action('current_screen', 'myScreen_ybuy_profile_edit');
	function myScreen_ybuy_profile_edit() {
		global $pagenow;

		 if ('profile.php' ===  $pagenow || 'user-edit.php' ===  $pagenow) {

		 	add_action( 'admin_enqueue_scripts', 'my_admin_load_styles_and_scripts' );
			function my_admin_load_styles_and_scripts() {
		        wp_enqueue_media();

		        wp_register_script('ybuy_review_author_js',get_stylesheet_directory_uri().'/plugins/review_author/js/review_author.js',array('jquery'));
		        wp_enqueue_script('ybuy_review_author_js');

		        wp_register_style('ybuy_review_author_css',get_stylesheet_directory_uri().'/plugins/review_author/css/review_author.css');
		        wp_enqueue_style('ybuy_review_author_css');
		        
		    }
		}
	}

	//showing the custom fields for review_author
    add_action('show_user_profile', 'review_author_custom_fields');
    add_action('edit_user_profile', 'review_author_custom_fields');
	function review_author_custom_fields($user) {

		$user_meta = get_user_meta($user->ID);
		global $states;
		
	    ?>
			<h2>Dados Profissionais</h2>
	    	<table class="form-table">
	    		<tr class="form-field">  
	    			<th>
	    				<label for="user_company_name"><?php _e( 'Empresa' ); ?></label>
	    			</th>
	    			<td>
						<input type="text" name="user_company_name" id="user_company_name" class="user_company_name" value="<?php echo ($user_meta['user_company_name'][0] !== "" ? esc_html($user_meta['user_company_name'][0]) : ""); ?>">
	    			</td>
	    		</tr>
    			<tr class="form-field">
	    			<th>
	    				<label for="user_company_job_position"><?php _e( 'Cargo' ); ?></label>
	    			</th>
	    			<td>
						<input type="text" name="user_company_job_position" id="user_company_job_position" class="user_company_job_position" value="<?php echo ($user_meta['user_company_job_position'][0] !== "" ? esc_html($user_meta['user_company_job_position'][0]) : ""); ?>">
	    			</td>
				</tr>
	    	</table>
	    	<h2>Imagem de Perfil</h2>
	    	<table class="form-table">
	    		<tr class="form-field">  
	    			<th>
	    				<label for="user_profile_picture"><?php _e( 'Foto do perfil' ); ?></label>
	    			</th>
	    			<td>
	    				<img src="<?php echo ($user_meta['user_profile_picture'][0] !== "" ? esc_html($user_meta['user_profile_picture'][0]) : ""); ?>" alt="" class="user_profile_picture_src">
						<input type="hidden" name="user_profile_picture" id="user_profile_picture" class="user_profile_picture" value="<?php echo ($user_meta['user_profile_picture'][0] !== "" ? esc_html($user_meta['user_profile_picture'][0]) : ""); ?>">
						<input class="upload_image_button button" name="_add_term_meta" id="_add_term_meta" type="button" value="Select/Upload Image" /> 
	    			</td>
				</tr>
	    	</table>
	    	<h2>Localização</h2>
	    	<table class="form-table">
	       		<tr class="form-field"> 
		       		<th>
		       			<label for="user_city"><?php _e('Cidade'); ?></label>
		       		</th>
			        <td>
			        	<input type="text" name="user_city" id="user_city" value="<?php echo ($user_meta['user_city'][0] !== "" ? esc_html($user_meta['user_city'][0]) : ""); ?>"><br />
			        </td>
				</tr>
				<tr class="form-field"> 
		       		<th>
		       			<label for="user_states"><?php _e('Estado'); ?></label>
		       		</th>
			        <td>
						<select name="user_states">
							<option <?php echo ('' === $user_meta['user_states'][0] || NULL === $user_meta['user_states'] ? 'selected="selected"' : ''); ?> disabled>Selecione o Estado</option>
							<?php foreach ($states as $state => $state_name) { ?>
								<option <?php echo ($state === $user_meta['user_states'][0] ? 'selected="selected"' : ''); ?> value="<?php echo $state; ?>"><?php echo $state_name; ?></option>	
							<?php } ?>
						</select>
			        </td>
				</tr>
			</table>
			<h2>Mídias Sociais</h2>
			<table class="form-table">
				<tr class="form-field"> 
		       		<th>
		       			<label for="user_facebook"><?php _e('Facebook'); ?></label>
		       		</th>
			        <td>
			        	<input type="text" name="user_facebook" id="user_facebook" value="<?php echo ($user_meta['user_facebook'][0] !== "" ? esc_html($user_meta['user_facebook'][0]) : ""); ?>"><br />
			        	<p class="description"><?php _e('URL do perfil do Facebook.'); ?></p>  
			        </td>
				</tr>
				<tr class="form-field"> 
		       		<th>
		       			<label for="user_instagram"><?php _e('Instagram'); ?></label>
		       		</th>
			        <td>
			        	<input type="text" name="user_instagram" id="user_instagram" value="<?php echo ($user_meta['user_instagram'][0] !== "" ? esc_html($user_meta['user_instagram'][0]) : ""); ?>"><br />
			        	<p class="description"><?php _e('URL do perfil do Instagram.'); ?></p>  
			        </td>
				</tr>
				<tr class="form-field"> 
		       		<th>
		       			<label for="user_twitter"><?php _e('Twitter'); ?></label>
		       		</th>
			        <td>
			        	<input type="text" name="user_twitter" id="user_twitter" value="<?php echo ($user_meta['user_twitter'][0] !== "" ? esc_html($user_meta['user_twitter'][0]) : ""); ?>"><br />
			        	<p class="description"><?php _e('URL do perfil do Twitter.'); ?></p>  
			        </td>
				</tr>
				<tr class="form-field"> 
		       		<th>
		       			<label for="user_youtube"><?php _e('YouTube'); ?></label>
		       		</th>
			        <td>
			        	<input type="text" name="user_youtube" id="user_youtube" value="<?php echo ($user_meta['user_youtube'][0] !== "" ? esc_html($user_meta['user_youtube'][0]) : ""); ?>"><br />
			        	<p class="description"><?php _e('URL do canal do YouTube.'); ?></p>  
			        </td>
				</tr>
			</table>
	    <?php
	}

	//saving the data from custom fields of review_author
	add_action( 'personal_options_update', 'review_author_save_meta' );
	add_action( 'edit_user_profile_update', 'review_author_save_meta' );
	function review_author_save_meta($user) {

		if ( !current_user_can( 'edit_user', $user ) )
			return false;

		if ('' !== $_POST) {

			update_user_meta($user, 'user_company_name', sanitize_text_field($_POST['user_company_name']));
			update_user_meta($user, 'user_company_job_position', sanitize_text_field($_POST['user_company_job_position']));
			update_user_meta($user, 'user_profile_picture', sanitize_text_field($_POST['user_profile_picture']));
			update_user_meta($user, 'user_city', sanitize_text_field($_POST['user_city']));
			update_user_meta($user, 'user_states', sanitize_text_field($_POST['user_states']));
			update_user_meta($user, 'user_facebook', sanitize_text_field($_POST['user_facebook']));
			update_user_meta($user, 'user_instagram', sanitize_text_field($_POST['user_instagram']));
			update_user_meta($user, 'user_twitter', sanitize_text_field($_POST['user_twitter']));
			update_user_meta($user, 'user_youtube', sanitize_text_field($_POST['user_youtube']));
		} 
	}
	

	//enabling review author, editor and admin to create reviews  
  	add_action('admin_init','add_role_caps',999);
    function add_role_caps() {
 
		// Add the roles you'd like to administer the custom post types
		$roles = array('review_author','editor','administrator');

		// Loop through each role and assign capabilities
		foreach($roles as $the_role) { 

			$role = get_role($the_role);
	 
			$role->add_cap( 'read' );
			$role->add_cap( 'read_review');
			$role->add_cap( 'read_private_reviews' );
			$role->add_cap( 'edit_review' );
			$role->add_cap( 'edit_reviews' );
			$role->add_cap( 'edit_others_reviews' );
			$role->add_cap( 'edit_published_reviews' );
			$role->add_cap( 'publish_reviews' );
			$role->add_cap( 'delete_others_reviews' );
			$role->add_cap( 'delete_private_reviews' );
			$role->add_cap( 'delete_published_reviews' );
	    }
	}