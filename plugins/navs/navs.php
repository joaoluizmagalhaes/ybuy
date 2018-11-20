<?php

	// This theme uses wp_nav_menu() in two locations.  
	register_nav_menus( array(  
	  'header' => __('Menu de cabeçalho', 'ybuy-tema'),  
	  'footer' => __('Menu de rodapé', 'ybuy-tema')  
	) );