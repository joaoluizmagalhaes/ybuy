<?php

	require_once __DIR__ . '/drag-image/drag-image.php';
	require_once __DIR__ . '/image-rating/image-rating.php';
	require_once __DIR__ . '/sellers-price/sellers-price.php';

	add_action( 'shortcode_ui_before_do_shortcode', 'shortcode_define_preview_constant' );
	function shortcode_define_preview_constant( $shortcode ) {
	// shortcode ui version used don't implements this constant to help build shortcode preview at editor
		if ( ! defined( 'SHORTCODE_UI_DOING_PREVIEW' ) ) {
			define( 'SHORTCODE_UI_DOING_PREVIEW', true );
		}
		return $shortcode;
	}