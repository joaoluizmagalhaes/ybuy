<?php 
	
	if(have_posts()) {
		$count = 0;

		while (have_posts()) : the_post();
			$meta = get_post_meta(get_the_ID());
			$postID = get_the_ID();

			the_title();

			the_content();

			the_post_thumbnail('large');

			echo get_the_author_meta('first_name') . ' ' . get_the_author_meta('last_name');

			$count++;
		
		endwhile;

		echo $count;
	}