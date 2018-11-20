<?php 

	if(have_posts()) { ?>
		<h3 class="list-title">
			Produtos mais bem avaliados
			<?php /* <a class="pull-right hidden-xs" href="#">Ver todos <img width="10" class="img-svg" src="<?php echo IMG_DIR; ?>/icon-arrow-right.svg" alt=""></a> */ ?>
		</h3>
		<div class="row">
			<div class="carousel-wrapper" data-carousel-last-visible>
				<?php while(have_posts()) : the_post();

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
				<?php endwhile; ?>
			</div>
		</div>
	<?php }