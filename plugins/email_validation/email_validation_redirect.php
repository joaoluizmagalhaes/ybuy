<?php 

	add_filter('query_vars', 'add_query_email_validation');
	function add_query_email_validation($aVars) {
		$aVars[] = 'email_validation';
		$aVars[] = 'registration_sucess';
		return $aVars;
	}
	
	// add the rewrite rule to create a cadastro url
	add_action('init', 'custom_rewrite_email_validation', 10, 0);
	function custom_rewrite_email_validation() {
		add_rewrite_rule( 'validacao', 'index.php?email_validation=true', 'top' );
		add_rewrite_rule( 'cadastrado', 'index.php?registration_sucess=true', 'top' );
	}
	
	//if the url exits, load the template
	add_filter( 'template_include', 'template_filter_email_validation', 99 );
	function template_filter_email_validation( $template_edition ) {

		$is_email_validation_loaded = (bool)get_query_var('email_validation'); 
	   
	    if ($is_email_validation_loaded){
			$template = locate_template(array('email_validation.php'));
			if ($template == '') $template = 'email_validation.php';
			return $template;
		}

		$is_email_registration = (bool)get_query_var('registration_sucess'); 
	   
	    if ($is_email_registration){
			$template = locate_template(array('registration_sucess.php'));
			if ($template == '') $template = 'registration_sucess.php';
			return $template;
		}


		return $template_edition;
  	}