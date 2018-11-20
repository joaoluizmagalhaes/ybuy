<?php 
	
	$brand = $wp_query->queried_object;
	
	$term_meta = get_term_meta($brand->term_id);

	$rating = get_brand_reviews($brand->term_id);

	function get_brand_reviews($brand_id) {

		$rating_score = array(
			'excellent' => 0,
			'very_good' => 0,
			'good'      => 0,
			'bad'       => 0,
			'very_bad'  => 0
		);
		//get the 'reviews rate' for the current brand

		$args = array(
			'post_type' => 'review',
			'post_per_page' => -1,
			'meta_key' => 'id_brand',
			'meta_value' => $brand_id
		);

		$query_brand = new WP_Query($args);

		if($query_brand->have_posts()) {
			$total_rating = 0;
			$count = 0;

			while ($query_brand->have_posts()) : $query_brand->the_post();
				$meta = get_post_meta(get_the_ID());

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
			$average_rating = $total_rating/$count;
			wp_reset_postdata();
	
		}

		$response = array(
			'count' => $count,
			'average_rating' => $average_rating,
			'rating_score' => $rating_score
		);

		return $response;
	}

	switch (round($rating['average_rating'])) {
		case 5 :
			$rating_name = 'Excelente';
			break;

		case 4 :
			$rating_name = 'Muito Bom';
			break;

		case 3 :
			$rating_name = 'Bom';
			break;

		case 2 :
			$rating_name = 'Ruim';
			break;

		case 1 :
			$rating_name = 'Muito Ruim';
			break;
		
		default:
			$rating_name = 'Produto ainda não avaliado!';
			break;
	}

	get_header();

	?>

	<main id="brand">

		<section id="brand-header">
			<div class="container container-reset">
				<section class="col-xs-6 col-xs-offset-3">
					<img src="<?php echo esc_url($term_meta['brands_logo'][0]);?>" alt="YBUY">
					<h2><?php echo esc_html($brand->name); ?> <img width="20" class="img-svg" src="<?php echo IMG_DIR; ?>/icon-brand-verified.svg" alt=""></h2>
					<h3><?php echo esc_html($term_meta['subtitle'][0]); ?></h3>
					<a href="#"><span><?php echo esc_html($brand->count); ?></span><?php echo ($brand->count > 1) ? ' produtos cadastrados</a>' : ' produto cadastrado</a>' ; ?>
				</section>
			</div>
		</section>

		<section id="brand-content">
			<div class="container container-reset">
				<section class="col-xs-12 col-md-6 col-md-offset-3">
					<?php /*<header>
						<strong><span><?php echo esc_html($rating['count']) ;?></span><?php echo ($rating->count > 1) ? ' Avaliações nos produtos</strong>' : ' Avaliação nos produtos</strong>' ; ?>
						<section>
							<div class="pull-left review">
								<strong><?php echo esc_html($rating['average_rating']); ?></strong>
								<span><?php  echo esc_html($rating_name); ?></span>
								<footer>
									<div class="review-element">
										<?php

										$star = '';

										for($i=1;$i<=5;$i++) {
											($i <= round($rating['average_rating'])) ? $star .= '<img width="30" class="img-svg full" src="'. IMG_DIR . '/rating-star-empty.svg" alt="">' : $star .= '<img width="30" class="img-svg empty" src="'. IMG_DIR . '/rating-star-empty.svg" alt="">';
										}
										echo $star;
										?>
									</div>
								</footer>
							</div>
							<div class="pull-left line-chart">
								<div>
									<h6>Excelente</h6>
									<div><span <?php echo 'style="width:' . (($rating['rating_score']['excellent'] * 100) / $rating['count']) . '%"'; ?>></span></div>
									<span><?php echo number_format($rating['rating_score']['excellent'], 0, ',', '.'); ?></span>
								</div>
								<div>
									<h6>Muito Bom</h6>
									<div><span <?php echo 'style="width:' . (($rating['rating_score']['very_good'] * 100) / $rating['count']) . '%"'; ?>></span></div>
									<span><?php echo number_format($rating['rating_score']['very_good'], 0, ',', '.'); ?></span>
								</div>
								<div>
									<h6>Bom</h6>
									<div><span <?php echo 'style="width:' . (($rating['rating_score']['good'] * 100) / $rating['count']) . '%"'; ?>></span></div>
									<span><?php echo number_format($rating['rating_score']['good'], 0, ',', '.'); ?></span>
								</div>
								<div>
									<h6>Ruim</h6>
									<div><span <?php echo 'style="width:' . (($rating['rating_score']['bad'] * 100) / $rating['count']) . '%"'; ?>></span></div>
									<span><?php echo number_format($rating['rating_score']['bad'], 0, ',', '.'); ?></span>
								</div>
								<div>
									<h6>Muito Ruim</h6>
									<div><span <?php echo 'style="width:' . (($rating['rating_score']['very_bad'] * 100) / $rating['count']) . '%"'; ?>></span></div>
									<span><?php echo number_format($rating['rating_score']['very_bad'], 0, ',', '.'); ?></span>
								</div>
							</div>
						</section>

						<p>
							<img width="24" class="img-svg" src="<?php echo IMG_DIR; ?>/product-coin.svg" alt="">
							<span>Valor <b>alto</b> em comparação a produtos semelhantes</span>
						</p> 
					</header> */ ?>
					<section>
						<?php if("" !== $term_meta['hint'][0]) { ?>
							<div>
								<b>Você sabia?</b>
								<p><?php echo esc_html($term_meta['hint'][0]); ?></p>
							</div>
						<?php } ?>
						<p>
							<?php echo esc_html($brand->description) ; ?>
							<!-- <a href="#">Ler mais</a> -->
						</p>
					</section>
				</section>
			</div>
		</section>
		<div class="container container-reset">
			<section id="list-best-rated">
				<h3 class="list-title pull-left">Produtos</h3>
				<div class="row">
					<?php
				
						add_image_size('products_widget_size', 280, 280, true);

						$args = array (
							'post_type'      => 'product',
							'posts_per_page' => 4,
							'tax_query'  	 => array(
								array(
									'taxonomy' => 'brands',
									'filed'    => 'term_id',
									'terms'    => $brand->term_id
								)
							)
						);

						$products = new WP_Query($args);

						if($products->have_posts()) {
							while($products->have_posts()) : $products->the_post();
								
								$terms = get_the_terms(get_the_ID(), 'brands');
								$image = (has_post_thumbnail(get_the_ID())) ? get_the_post_thumbnail(get_the_ID(), 'products_widget_size') : '<div class="noThumb"></div>';

								$reviews = get_total_product_reviews(get_the_ID());
					
								$star = '';

								for($i=1;$i<=5;$i++) {
									($i <= round($reviews->rating)) ? $star .= '<img width="30" class="img-svg full" src="'. IMG_DIR . '/rating-star-empty.svg" alt="">' : $star .= '<img width="30" class="img-svg empty" src="'. IMG_DIR . '/rating-star-empty.svg" alt="">';
								}

								($reviews->count === 1) ? $review_text = ' Resenha</strong>' : $review_text = ' Resenhas</strong>'; 

								$listItem = '<article class="col-xs-12 col-lg-3">'; 
								$listItem .=	'<a href="' . get_permalink() . '">'; 
								$listItem .=		 $image; 
								$listItem .=	'</a>'; 
								$listItem .=	'<footer>';
								$listItem .=		'<h4>' . get_the_title() . '</h4>';
								$listItem .=		'<h5><a href="' . get_bloginfo('url') . '/brands/' . $terms[0]->slug . '">' . $terms[0]->name . '</a></h5>';
								//$listItem .= 		'<div class="review-element">';
								//$listItem .= 			$star;					
								//$listItem .=			'<strong><span>'. round($reviews->rating) . '</span>/5</strong>';
								//$listItem .=		'</div>'; 
								//$listItem .=		'<strong><span>' . $reviews->count . '</span>' . $review_text;
								//$listItem .=		'<img width="30" class="img-svg" src="' . IMG_DIR . '/icon-heart.svg" alt="">';
								$listItem .=	'</footer>';
								$listItem .= '</article>'; 

								echo $listItem;
							endwhile;

							wp_reset_postdata();

						} else {
							echo '<p style="padding:25px;"> Nenhum produto encontrado!</p>';
						}
					?>
				</div>
			</section>
		</div>
		<div class="container container-reset">

			<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Widget - Marcas") ) :
				endif; ?>
		</div>
	</main>

	<?php get_footer();