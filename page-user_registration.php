<?php
/**
 * Template Name: Cadastro de Usuários
 *
 */
	// Load registration file.
	require_once( ABSPATH . WPINC . '/registration.php' );

	
	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'adduser' && wp_verify_nonce( $_POST['user_register_nonce'], 'user_registration_form' )) {

		$email = esc_attr($_POST['email']);
		
		$user_pass = esc_attr( $_POST['user_pass'] );
		$userdata = array(
			'user_pass1' => esc_attr( $_POST['user_pass1'] ),
			'user_pass2' => esc_attr( $_POST['user_pass2'] ),
			'first_name' => esc_attr( $_POST['first_name'] ),
			'last_name'  => esc_attr( $_POST['last_name'] ),
			'user_login' => $email,
			'user_email' => $email,
			'role'       => 'review_author',
			
		);
		
		if ( username_exists($userdata['user_login']) ) {
			$error = __('<p class="error-msg" style="display: none;">Desculpe, mas este email já está cadastrado!</p>');
			echo $error;
		} elseif ( !is_email($userdata['user_email'], true) ) {
			$error = __('<p class="error-msg" style="display: none;">Você precisa cadastrar um email válido!</p>');
			echo $error;
		} elseif ( email_exists($userdata['user_email']) ) {
			$error = __('<p class="error-msg" style="display: none;">Desculpe, mas este email já está cadastrado!</p>');
			echo $error;
		} elseif ($userdata['user_pass1'] !== $userdata['user_pass2']) {
			$error = __('<p class="error-msg" style="display: none;">Desculpe, as senhas digitadas não conferem!</p>');
			echo $error;
		} else {
			$new_user = wp_insert_user( $userdata );

			if(is_wp_error( $new_user ) ){
				echo $new_user->get_error_message();
			} else {

				$user_status = esc_html( $_POST['status']);

				$user = get_user_by('login', $email);

				$user_hash = md5( rand(0,1000) );
				$headers = array('Content-Type: text/html; charset=UTF-8');

				update_user_meta($user->ID, 'status', $user_status);
				update_user_meta($user->ID, 'user_hash', $user_hash);

				$email_msg = 'Boas-vindas ao yBuy<br><br>';
				$email_msg .= 'Você acaba de se cadastrar no site yBuy.<br><br>';
				$email_msg .= 'Clique no link abaixo para confirmar sua inscrição<br><br>';
				$email_msg .= get_bloginfo('url').'/validacao?email='.$email.'&hash='.$user_hash.' <br><br>';
				$email_msg .= 'Boas-vindas e nos vemos no site!<br><br>';
				$email_msg .= 'Equipe yBuy<br><br>';
				//$email_msg .= 'Reminder: Você recebeu este email porque realizou sua inscrição no site yBuy.<br><br>';

				$mail_send = wp_mail($email, 'Confirmação cadastro yBuy', $email_msg, $headers);

				if($mail_send) {
	        		wp_redirect(get_bloginfo('url').'/cadastrado?email='.$email);
	        		exit();
	        	}
			}
		}
	}

	//call the front end form
	get_template_part('template-parts/tpl_user/tpl_user_registration') ?>
		