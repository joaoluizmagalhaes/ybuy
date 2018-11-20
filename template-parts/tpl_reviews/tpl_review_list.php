<?php 

	if(have_posts()) {
		while (have_posts()) : the_post();
			include (get_stylesheet_directory() . '/template-parts/tpl_reviews/tpl_review_card.php');
		endwhile;
	}