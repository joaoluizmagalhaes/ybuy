<?php
	if ("product" === get_post_type()) {
		add_image_size('products_widget_size', 280, 280, true);

		$terms = get_the_terms(get_the_ID(), 'brands');
		$image = (has_post_thumbnail(get_the_ID())) ? get_the_post_thumbnail(get_the_ID(), 'products_widget_size') : '<div class="noThumb"></div>';

		//$reviews = get_total_product_reviews(get_the_ID());

		/*$star = '';

		for($i=1;$i<=5;$i++) {
			($i <= round($reviews->rating)) ? $star .= '<img width="30" class="img-svg full" src="'. IMG_DIR . '/rating-star-empty.svg" alt="">' : $star .= '<img width="30" class="img-svg empty" src="'. IMG_DIR . '/rating-star-empty.svg" alt="">';
		}*/

		($reviews->count === 1) ? $review_text = ' Resenha</strong>' : $review_text = ' Resenhas</strong>';

		?>
		<section id="list-best-rated">
			<article class="col-xs-12 col-lg-3">
				<a href="<?php echo get_permalink(); ?>">
					<?php echo $image; ?>
				</a>
				<footer>
					<h4><?php echo get_the_title(); ?></h4>
					<h5><a href="<?php echo get_bloginfo('url') . '/marcas/' . $terms[0]->slug ?>"> <?php echo $terms[0]->name; ?></a></h5>
					<div class="review-element">
						<?php //echo $star; ?>
					</div>
					<strong><span><?php //echo esc_html($reviews->count); ?></span><?php //echo $review_text; ?></strong>
					<?php /*<img width="30" class="img-svg" src="<?php echo IMG_DIR; ?>/icon-heart.svg" alt=""> */?>
				</footer>
			</article>
		</section>
	<?php } else if("post" === get_post_type()) {
		$post_meta = get_post_meta(get_the_ID());

		$category = get_the_category();
		$category_name = ('Uncategorized' === $category[0]->name ) ? '' : $category[0]->name;

		$image = get_the_post_thumbnail_url(); ?>

		<section id="home-content-posts">
			<article class="col-xs-6 col-sm-6 col-md-4 col-lg-3">
				<a href="<?php echo get_permalink(); ?>">
					<div>
						<h4><?php echo esc_html($category_name); ?></h4>
						<footer>
							<h5><?php echo get_the_title(); ?></h5>
							<?php /*<strong class="pull-left"><span><?php echo get_comments_number(0); ?></span> Comentários</strong> */ ?>
							<?php /*<strong class="pull-right"><img width="17" class="img-svg" src="<?php echo IMG_DIR; ?>/icon-like-up.svg" alt=""><span>13</span></strong> */ ?>
						</footer>
					</div>
					<img class="img-responsive" src="<?php echo $image; ?>" alt="YBUY">
				</a>
			</article>
		</section>
	<?php }