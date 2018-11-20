<?php 

	get_header();

	$author_id = get_the_author_meta('id');

	$author_meta = get_user_meta($author_id);

	echo '<p>';
	echo $author_meta['first_name'][0] . ' ' . $author_meta['last_name'][0];
	echo '</p>';

	echo '<p>';
	echo $author_meta['user_company_job_position'][0] . ' na ' . $author_meta['user_company_name'][0];
	echo '</p>';

	echo '<p>';
	echo '<a href="'. $author_meta['user_facebook'][0] . '">Facebook</a>';
	echo '</p>';

	echo '<p>';
	echo '<a href="'. $author_meta['user_twitter'][0] . '">Twitter</a>';
	echo '</p>';

	echo '<p>';
	echo '<a href="'. $author_meta['user_youtube'][0] . '">YouTube</a>';
	echo '</p>';

	echo '<p>';
	echo '<a href="'. $author_meta['user_instagram'][0] . '">Instagram</a>';
	echo '</p>';

	echo '<p>';
	echo get_the_author_meta('description');
	echo '</p>';


     //get the 'posts' for the current author
    $request = wp_remote_get(get_site_url() . '/author_posts/' . $author_id);
    $response = json_decode(wp_remote_retrieve_body($request));
    echo wp_kses($response->html, html_allowed());

    get_footer();