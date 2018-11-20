<?php

/**
 * Template Name: Editar perfil
 *
 */
	if(!is_user_logged_in()) {
		wp_redirect(get_bloginfo('url') . '/login');
	} else {
		// Get user info
		global $current_user, $wp_roles, $wp;
		
		$user_meta = get_user_meta($current_user->ID);

		//Load the registration File
		require_once( ABSPATH . WPINC . '/registration.php' );
		$error = array();

		// If profile was saved, update profile.
		if ( 'POST' === $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] === 'update-user' && wp_verify_nonce( $_POST['user_edit_nonce'], 'user_edit_form' )) {

			// Update user password.
		    if ( !empty($_POST['pass1'] ) && !empty( $_POST['pass2'] ) ) {
		        if ( $_POST['pass1'] === $_POST['pass2'] )
		            wp_update_user( array( 'ID' => $current_user->ID, 'user_pass' => esc_attr( $_POST['pass1'] ) ) );
		        else
		            $error['password'] = __('As senhas informadas não conferem. Sua senha não foi atualizada.', 'profile');
		    }
		    
		    $email_exits = email_exists(sanitize_email($_POST['user_email'])); 
	 		if ( !empty( $_POST['user_email'] ) ){
		        if (!is_email(sanitize_email( $_POST['user_email'] )))
		            $error['email'] = __('O email informado não é válido, por favor tente novamente.', 'profile');
		       elseif ($email_exits && $email_exits != $current_user->id)
		            $error['email'] = __('Este email já está sendo usado por outro usuário, por favor tente outro.', 'profile');
		        else{
		            wp_update_user( array ('ID' => $current_user->ID, 'user_email' => sanitize_email( $_POST['user_email'] )));
		        }
		    }

		    if ( !empty( $_POST['first_name'] ) )
		        update_user_meta( $current_user->ID, 'first_name', sanitize_text_field( $_POST['first_name'] ) );

		    if ( !empty( $_POST['last_name'] ) )
		        update_user_meta($current_user->ID, 'last_name', sanitize_text_field( $_POST['last_name'] ) );
/*
		    if ( !empty( $_POST['description'] ) )
		        update_user_meta( $current_user->ID, 'description', sanitize_text_field( $_POST['description'] ) );*/

		    if ( !empty( $_POST['user_profile_picture'] ) ) 
				update_user_meta($user, 'user_profile_picture', sanitize_text_field($_POST['user_profile_picture']));

			/*if ( !empty( $_POST['user_city'] ) ) 
				update_user_meta($user, 'user_city', sanitize_text_field($_POST['user_city']));

			if ( !empty( $_POST['user_states'] ) ) 
				update_user_meta($user, 'user_states', sanitize_text_field($_POST['user_states']));

			if ( !empty( $_POST['user_facebook'] ) ) 
				update_user_meta($user, 'user_facebook', sanitize_text_field($_POST['user_facebook']));

			if ( !empty( $_POST['user_instagram'] ) ) 
				update_user_meta($user, 'user_instagram', sanitize_text_field($_POST['user_instagram']));

			if ( !empty( $_POST['user_twitter'] ) ) 
				update_user_meta($user, 'user_twitter', sanitize_text_field($_POST['user_twitter']));

			if ( !empty( $_POST['user_youtube'] ) ) 
				update_user_meta($user, 'user_youtube', sanitize_text_field($_POST['user_youtube']));*/

			// Redirect so the page will show updated info.
		    if ( count($error) == 0 ) {
		        //action hook for plugins and extra fields saving
		        do_action('edit_user_profile_update', $current_user->ID);
		        wp_redirect( get_bloginfo('url') .'/perfil?msg=Perfil%20atualizado%20com%20sucesso');
		    } else {
		    	$_POST['error'] = $error;
		    }
		}

		$current_url = home_url(add_query_arg(array(),$wp->request));

		if( get_bloginfo('url') . '/editar-perfil' === $current_url) {
			
			add_action( 'wp_enqueue_scripts', 'my_load_styles_and_scripts_user' );
			function my_load_styles_and_scripts_user() {

		        wp_enqueue_media();
		        wp_enqueue_style( 'wp-admin' );

		        wp_register_script('ybuy_edit_profile_js',get_stylesheet_directory_uri().'/__assets/js/plugins/profile_edit.js');
		        wp_enqueue_script('ybuy_edit_profile_js');
		    }
		}
	} 

	get_header(); ?>

	<main id="edit-profile">
		<section class="profile-form">
			<?php
				//call the front end form
				include (get_stylesheet_directory().'/template-parts/tpl_user/tpl_user_edit_profile.php'); 
			?>
		</section>	
	</main>
</body>
<?php wp_footer(); ?>
</html>
	