<?php 

	//require the widgets plugins
	require_once __DIR__ . '/products_widget/products_widget.php';
	require_once __DIR__ . '/brands_widget/brands_widget.php';
	require_once __DIR__ . '/reviews_widget/reviews_widget.php';
	require_once __DIR__ . '/posts_widget/posts_widget.php';
	require_once __DIR__ . '/category_widget/category_widget.php';

	if(function_exists('register_sidebar')) {
		register_sidebar(array(
			'name'          => 'Widget - Home',
			'before_widget' => '<div class="widget_area">',
			'after_widget'  => '</div>',
 		));

 		register_sidebar(array(
 			'name' 			=> 'Sendgrid',
 			'before_widget' => '<div class="widget_area">',
 			'after_widget'  => '</div>',
 		));

 		register_sidebar(array(
			'name'          => 'Widget - Produtos (antes de lojas)',
			'before_widget' => '<div class="widget_area">',
			'after_widget'  => '</div>',
 		));

 		register_sidebar(array(
			'name'          => 'Widget - Produtos (depois de lojas)',
			'before_widget' => '<div class="widget_area">',
			'after_widget'  => '</div>',
 		));

 		register_sidebar(array(
			'name'          => 'Widget carrossel - Home',
			'before_widget' => '<div class="widget_area">',
			'after_widget'  => '</div>',
 		));
 		register_sidebar(array(
 			'name' 			=> 'Widget - Marcas',
 			'before_widget' => '<div class="widget_area">',
 			'after_widget'  => '</div>',
 		));
 		register_sidebar(array(
 			'name' 			=> 'Widget - Categorias',
 			'before_widget' => '<div class="widget_area">',
 			'after_widget'  => '</div>',
 		));
 		register_sidebar(array(
 			'name' 			=> 'Widget - Sub-Categorias',
 			'before_widget' => '<div class="widget_area">',
 			'after_widget'  => '</div>',
 		));
 	}

 	//disabled the defalt widgets for the WP
	add_action('widgets_init', 'unregister_default_widgets', 11);
	function unregister_default_widgets() {
		unregister_widget('WP_Widget_Pages');
		unregister_widget('WP_Widget_Calendar');
		unregister_widget('WP_Widget_Archives');
		unregister_widget('WP_Widget_Links');
		unregister_widget('WP_Widget_Meta');
		unregister_widget('WP_Widget_Top_Rated');
		unregister_widget('WP_Widget_Search');
		unregister_widget('WP_Widget_Categories');
		unregister_widget('WP_Widget_Recent_Posts');
		unregister_widget('WP_Widget_Recent_Comments');
		unregister_widget('WP_Widget_Tag_Cloud');
		unregister_widget('WP_Nav_Menu_Widget');
		unregister_widget('Twenty_Eleven_Ephemera_Widget');
		unregister_widget('WP_Widget_RSS'); 
		unregister_widget('WP_Widget_Media_Video');
		unregister_widget('WP_Widget_Media_Gallery');
		unregister_widget('WP_Widget_Custom_HTML');
		unregister_widget('WP_Widget_Media_Image');
		unregister_widget('WP_Widget_Media_Audio');
		unregister_widget('WP_Widget_Text');
	}
