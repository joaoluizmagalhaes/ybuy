<?php 

	//order the search results by post_type
	add_filter('posts_orderby','order_search_post_type',10,2);
	function order_search_post_type( $orderby, $query ){
	    global $wpdb;

	    if(!is_admin() && is_search()) 
	        $orderby =  $wpdb->prefix."posts.post_type DESC, {$wpdb->prefix}posts.post_date DESC";

    	return  $orderby;
    }
