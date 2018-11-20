<?php 

	//add a query var value to create the custom url
	add_filter('query_vars', 'add_query_vars_author_posts');
	function add_query_vars_author_posts($aVars) {
		$aVars[] = 'author_posts';
	    $aVars[] = 'author_id';
	
		return $aVars;
	}
	
	// add the rewrite rule to create a cadastro url
	add_action('init', 'custom_rewrite_author_posts', 10, 0);
	function custom_rewrite_author_posts() {
		add_rewrite_rule( 'author_posts/(.+)/?', 'index.php?author_posts=true&author_id=$matches[1]', 'top' );

	}

	//create the query for the reviews rate
	add_action('pre_get_posts', 'set_query_for_author_posts', 100);
	function set_query_for_author_posts($query) {

		$is_author_posts_page_loaded = (bool)get_query_var('author_posts');

		if($is_author_posts_page_loaded) {
			
			if(!$query->is_main_query())
            	return;

            $author_id = get_query_var('author_id');

            set_query_var('post_type', 'post');
            set_query_var('post_per_page', 8);
            set_query_var('author', $author_id);

		}

	}
	
	//if the url exits, load the template
	add_filter( 'template_include', 'template_filter_author_pages', 99 );
	function template_filter_author_pages( $template_edition ) {

		$is_author_posts_page_loaded = (bool)get_query_var('author_posts'); 
		
	    if ($is_author_posts_page_loaded){
			
			if(have_posts()) {
				ob_start();

					get_template_part('template-parts/tpl_author/tpl_author_posts', 'none');

					$template = ob_get_contents();
				
				ob_end_clean();

				wp_reset_postdata();

           		wp_send_json(array('html'=>$template));
			}
		}

		return $template_edition;
  	}