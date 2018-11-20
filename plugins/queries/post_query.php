<?php 

	//add a query var value to create the custom url
	add_filter('query_vars', 'add_query_vars_query_posts');
	function add_query_vars_query_posts($aVars) {
		$aVars[] = 'query_posts';
	    $aVars[] = 'category_name';
	
		return $aVars;
	}
	
	// add the rewrite rule to create a cadastro url
	add_action('init', 'custom_rewrite_query_posts', 10, 0);
	function custom_rewrite_query_posts() {
		add_rewrite_rule( 'query_posts/(.+)/?', 'index.php?query_posts=true&category_name=$matches[1]', 'top' );

	}

	//create the query for the reviews rate
	add_action('pre_get_posts', 'set_query_for_query_posts', 100);
	function set_query_for_query_posts($query) {

		$is_query_posts_page_loaded = (bool)get_query_var('query_posts');

		if($is_query_posts_page_loaded) {
			
			if(!$query->is_main_query())
            	return;

            $category_name = get_query_var('category_name');

            set_query_var('post_type', 'post');
            set_query_var('post_per_page', 8);
            set_query_var('category_name', $category_name);

		}

	}
	
	//if the url exits, load the template
	add_filter( 'template_include', 'template_filter_query_posts', 99 );
	function template_filter_query_posts( $template_edition ) {

		$is_query_posts_page_loaded = (bool)get_query_var('query_posts'); 
		
	    if ($is_query_posts_page_loaded){
			
			if(have_posts()) {
				ob_start();

					get_template_part('template-parts/tpl_posts/tpl_posts_card', 'none');

					$template = ob_get_contents();
				
				ob_end_clean();

				wp_reset_postdata();

           		wp_send_json(array('html'=>$template));
			}
		}

		return $template_edition;
  	}