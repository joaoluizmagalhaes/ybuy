<?php 

	$rating_score = array(
		'excellent' => 0,
		'very_good' => 0,
		'good'      => 0,
		'bad'       => 0,
		'very_bad'  => 0
	);

	$total_rating = 0;
	$count = 0;

	if(have_posts()) {
		while (have_posts()) : the_post();
			$meta = get_post_meta(get_the_ID());
			$postID = get_the_ID();

			$total_rating += $meta['product_review_rate'][0];
			$count++;

			switch ($meta['product_review_rate'][0]) {
				case '1':
					$rating_score['very_bad'] += 1;
					break;

				case '2':
					$rating_score['bad'] += 1;
					break;

				case '3':
					$rating_score['good'] += 1;
					break;

				case '4':
					$rating_score['very_good'] += 1;
					break;

				case '5':
					$rating_score['excellent'] += 1;
					break;
			}

		endwhile;
		
	}

	$average_rating = $total_rating/$count;

	echo '<p>Média de Avaliações: ' . $average_rating .'</p>';
	echo '<p>Excelente: ' . $rating_score['excellent'] .'</p>';
	echo '<p>Muito Bom: ' . $rating_score['very_good'] .'</p>';
	echo '<p>Bom: ' . $rating_score['good'] .'</p>';
	echo '<p>Ruim: ' . $rating_score['bad'] .'</p>';
	echo '<p>Muito Ruim: ' . $rating_score['very_bad'] .'</p>';