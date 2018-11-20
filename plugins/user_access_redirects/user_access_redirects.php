<?php 
	
	//add a query var value to create the custom url
	add_filter('query_vars', 'add_query_vars_user_registration');
	function add_query_vars_user_registration($aVars) {
		$aVars[] = 'create_user';
	    $aVars[] = 'update_user';
	    $aVars[] = 'login_user';
	    $aVars[] = 'profile_user';
	    $aVars[] = 'recovery_pass_user';
	    $aVars[] = 'pass_recovered';
	
		return $aVars;
	}
	
	// add the rewrite rule to create a cadastro url
	add_action('init', 'custom_rewrite_user_registration', 10, 0);
	function custom_rewrite_user_registration() {
		add_rewrite_rule( 'cadastro', 'index.php?create_user=true', 'top' );
		add_rewrite_rule( 'editar-perfil', 'index.php?update_user=true', 'top' );
		add_rewrite_rule( 'login', 'index.php?login_user=true', 'top' );
		add_rewrite_rule( 'recuperar-senha', 'index.php?recovery_pass_user=true', 'top');
		add_rewrite_rule( 'perfil', 'index.php?profile_user=true', 'top' );
		add_rewrite_rule( 'recuperada', 'index.php?pass_recovered=true', 'top');
	}
	
	//if the url exits, load the template
	add_filter( 'template_include', 'template_filter_user_pages', 99 );
	function template_filter_user_pages( $template_edition ) {

		$is_user_edit_page_loaded = (bool)get_query_var('update_user'); 
		$is_user_creation_page_loaded = (bool)get_query_var('create_user');
		$is_user_login_page_loaded = (bool)get_query_var('login_user');
		$is_user_profile_page_loaded = (bool)get_query_var('profile_user');
		$is_user_recovery_pass_loaded = (bool)get_query_var('recovery_pass_user');
		$is_user_recovered_pass_loaded = (bool)get_query_var('pass_recovered');
	   
	    if ($is_user_edit_page_loaded){
			$template = locate_template(array('page-user_edit.php'));
			if ($template == '') $template = 'page-user_edit.php';
			return $template;
		}

		if ($is_user_creation_page_loaded){
			$template = locate_template(array('page-user_registration.php'));
			if ($template == '') $template = 'page-user_registration.php';
			return $template;
		}

		if ($is_user_login_page_loaded){
			$template = locate_template(array('page-user_login.php'));
			if ($template == '') $template = 'page-user_login.php';
			return $template;
		}

		if ($is_user_profile_page_loaded){
			$template = locate_template(array('page-user_profile.php'));
			if ($template == '') $template = 'page-user_profile.php';
			return $template;
		}

		if ($is_user_recovery_pass_loaded){
			$template = locate_template(array('page-user_pass_recovery.php'));
			if ($template == '') $template = 'page-user_pass_recovery.php';
			return $template;
		}

		if ($is_user_recovered_pass_loaded){
			$template = locate_template(array('page-user_pass_recovered.php'));
			if ($template == '') $template = 'page-user_pass_recovered.php';
			return $template;
		}

		return $template_edition;
  	}