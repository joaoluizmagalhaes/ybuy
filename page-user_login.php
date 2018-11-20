<?php
/**
 * Template Name: Login 
 *
 */

	if (is_user_logged_in()) {
		// redirect to home if user is logged in
		wp_redirect(home_url());
		exit();
	} else {
		//load the front end form
		get_template_part('template-parts/tpl_user/tpl_user_login');
	}