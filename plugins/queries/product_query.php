<?php 

	//add a query var value to create the custom url
	add_filter('query_vars', 'add_query_vars_query_products');
	function add_query_vars_query_products($aVars) {
		$aVars[] = 'query_products';
	    $aVars[] = 'category_name';
	
		return $aVars;
	}
	
	// add the rewrite rule to create a cadastro url
	add_action('init', 'custom_rewrite_query_products', 10, 0);
	function custom_rewrite_query_products() {
		add_rewrite_rule( 'query_products/(.+)/?', 'index.php?query_products=true&category_name=$matches[1]', 'top' );

	}

	//create the query for the reviews rate
	add_action('pre_get_posts', 'set_query_for_query_products', 100);
	function set_query_for_query_products($query) {

		$is_query_products_page_loaded = (bool)get_query_var('query_products');

		if($is_query_products_page_loaded) {
			
			if(!$query->is_main_query())
            	return;

            $category_name = get_query_var('category_name');

            set_query_var('post_type', 'product');
            set_query_var('post_per_page', 8);
            set_query_var('category_name', $category_name);

		}

	}
	
	//if the url exits, load the template
	add_filter( 'template_include', 'template_filter_query_products', 99 );
	function template_filter_query_products( $template_edition ) {

		$is_query_products_page_loaded = (bool)get_query_var('query_products'); 
		
	    if ($is_query_products_page_loaded){
			
			if(have_posts()) {
				ob_start();

					get_template_part('template-parts/tpl_products/tpl_product_card', 'none');

					$template = ob_get_contents();
				
				ob_end_clean();

				wp_reset_postdata();

           		wp_send_json(array('html'=>$template));
			}
		}

		return $template_edition;
  	}