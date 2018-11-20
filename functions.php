<?php 
	@ini_set( 'upload_max_size' , '64M' );

	require_once __DIR__ . '/plugins/plugins.php';

	//enable featured image on the theme
	add_theme_support( 'post-thumbnails' );
	show_admin_bar(false);

	// All Braziliam states for select usage
	$states = array (
		'AC' => 'Acre',
		'AL' => 'Alagoas',
		'AP' => 'Amapá',
		'AM' => 'Amazonas',
		'BA' => 'Bahia',
		'CE' => 'Ceará',
		'DF' => 'Distrito Federal',
		'ES' => 'Espírito Santo',
		'GO' => 'Goiás',
		'MA' => 'Maranhão',
		'MT' => 'Mato Grosso',
		'MS' => 'Mato Grosso do Sul',
		'MG' => 'Minas Gerais',
		'PA' => 'Pará',
		'PB' => 'Paraíba',
		'PR' => 'Paraná',
		'PE' => 'Pernambuco',
		'PI' => 'Piauí',
		'RJ' => 'Rio de Janeiro',
		'RN' => 'Rio Grande do Norte',
		'RS' => 'Rio Grande do Sul',
		'RO' => 'Rondônia',
		'RR' => 'Roraima',
		'SC' => 'Santa Catariana',
		'SP' => 'São Paulo',
		'SE' => 'Sergipe',
		'TO' => 'Tocantins',
	);

	//show to users only their own media files
	add_action('pre_get_posts','users_own_attachments');
	function users_own_attachments( $wp_query_obj ) {

	    global $current_user, $pagenow;

	    $is_attachment_request = ($wp_query_obj->get('post_type')=='attachment');

	    if( !$is_attachment_request )
	        return;

	    if( !is_a( $current_user, 'WP_User') )
	        return;

	    if( !in_array( $pagenow, array( 'upload.php', 'admin-ajax.php' ) ) )
	        return;

	    if( !current_user_can('delete_pages') )
	        $wp_query_obj->set('author', $current_user->ID );

	    return;
	}

	//show to admin and editors all posts
	add_filter('pre_get_posts', 'posts_for_current_author');
	function posts_for_current_author($query) {
	    global $pagenow;
	 
	    if( 'edit.php' != $pagenow || !$query->is_admin )
	        return $query;
	 
	    if( !current_user_can( 'edit_others_posts' ) ) {
	        global $user_ID;
	        $query->set('author', $user_ID );
	    }
	    return $query;
	}

	//enqueue styles and js
	add_action('wp_enqueue_scripts','ybuy_enqueue_styles',7);
    function ybuy_enqueue_styles(){
        wp_enqueue_style('font-awesome-style',get_template_directory_uri().'/__assets/css/font-awesome-4.7.0/css/font-awesome.css');
    	wp_enqueue_style('bootstrap-css',get_template_directory_uri().'/__assets/css/bootstrap.css');
    	wp_enqueue_style('twentytwenty-css',get_template_directory_uri().'/__assets/css/twentytwenty.css');
        wp_enqueue_style('main-style',get_template_directory_uri().'/style.css');
    }

    add_action('wp_enqueue_scripts','ybuy_enqueue_scripts');
    function ybuy_enqueue_scripts(){

    	wp_deregister_script('jquery');
    	wp_register_script('ybuy-jquery', get_stylesheet_directory_uri().'/__assets/js/vendors/jquery.js', $in_footer = true);
    	wp_enqueue_script('ybuy-jquery');

        wp_register_script('ybuy-jquery-iu', get_stylesheet_directory_uri().'/__assets/js/vendors/jquery-ui.min.js', $in_footer = true);
    	wp_enqueue_script('ybuy-jquery-iu');

    	wp_register_script('ybuy-jquery-iu-touch-punch', get_stylesheet_directory_uri().'/__assets/js/vendors/jquery-ui-touch-punch.min.js',  $in_footer = true);
    	wp_enqueue_script('ybuy-jquery-iu-touch-punch');

        wp_register_script('event_move_ybuy-js',get_stylesheet_directory_uri().'/__assets/js/vendors/jquery.event.move.js', $in_footer = true);
        wp_enqueue_script('event_move_ybuy-js');

        wp_register_script('drag_ybuy-js',get_stylesheet_directory_uri().'/__assets/js/vendors/jquery.twentytwenty.js', $in_footer = true);
        wp_enqueue_script('drag_ybuy-js');

        wp_register_script('price-format-js',get_stylesheet_directory_uri().'/__assets/js/vendors/jquery.priceformat.min.js', $in_footer = true);
        wp_enqueue_script('price-format-js');

    	wp_register_script('ybuy-js',get_stylesheet_directory_uri().'/__assets/js/YBUY.js', $in_footer = true);
        wp_enqueue_script('ybuy-js');

    }

    /** REMOVE EMPTY PARAGRAPHS
	============================================ */	

	function remove_empty_p( $content ) {

	    $content = force_balance_tags( $content );
	    $content = preg_replace( '#<p>\s*+(<br\s*/*>)?\s*</p>#i', '', $content );
	    $content = preg_replace( '~\s?<p>(\s|&nbsp;)+</p>\s?~', '', $content );
	    return $content;

	}
	add_filter('the_content', 'remove_empty_p', 20, 1);

	function ybuy_html_allowed() {
	    $parameters = array(
	        'alt'              => array(),
	        'autocomplete'     => array(),
	        'class'            => array(),
	        'data-filter-star' => array(),
	        'data-filter-type' => array(),
	        'data-sizes'       => array(),
	        'data-src'         => array(),
	        'data-srcset'      => array(),
	        'data-value'       => array(),
	        'hidden'           => array(),
	        'href'             => array(),
	        'height'		   => array(),
	        'id'               => array(),
	        'meta-value'       => array(),
	        'rel'              => array(),
	        'selected'         => array(),
	        'src'              => array(),
	        'srcset'		   => array(),
	        'style'	  		   => array(),
	        'sizes'			   => array(),
	        'title'            => array(),
	        'value'            => array(),
	        'width'			   => array(),
	    );

	    $tags = array(
	        'a'        => $parameters,
	        'article'  => $parameters,
	        'b'        => $parameters,
	        'button'   => $parameters,
	        'div'      => $parameters,
	        'figure'   => $parameters,
	        'footer'   => $parameters,
	        'form'     => $parameters,
	        'h1'       => $parameters,
	        'h2'       => $parameters,
	        'h3'       => $parameters,
	        'h4'       => $parameters,
	        'h5'       => $parameters,
	        'h6'       => $parameters,
	        'i'        => $parameters,
	        'img'      => $parameters,
	        'input'    => $parameters,
	        'label'    => $parameters,
	        'li'       => $parameters,
	        'option'   => $parameters,
	        'p'        => $parameters,
	        'select'   => $parameters,
	        'span'     => $parameters,
	        'textarea' => $parameters,
	        'ul'       => $parameters,
	    );

	    return $tags;
	}


	//handle failed login
	add_action( 'wp_login_failed', 'my_front_end_login_fail' );  // hook failed login

	function my_front_end_login_fail( $username ) {
	   $referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
	   // if there's a valid referrer, and it's not the default log-in screen
	   if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
	      wp_redirect( $referrer . '?failed=true' );  // let's append some information (login=failed) to the URL for the theme to use
	      exit;
	   }
	}

	function tinymce_init() {
	    // Hook to tinymce plugins filter
	    add_filter( 'mce_external_plugins', 'tinymce_plugin' );
	}
	add_filter('init', 'tinymce_init');

	function tinymce_plugin($init) {
	    // We create a new plugin... linked to a js file.
	    // Mine was created from a plugin... but you can change this to link to a file in your plugin
	    $init['keyup_event'] = get_template_directory_uri().'/plugins/default_post/js/default_post.js';
	    return $init;
	}