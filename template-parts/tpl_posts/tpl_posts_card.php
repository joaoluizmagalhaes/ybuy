<?php 

	if(have_posts()) { ?>
		<h3 class="list-title pull-left">Artigos mais populares</h3>
		<?php /* <a class="pull-right" href="#">Ver todas as resenhas <img width="10" class="img-svg" src="<?php echo IMG_DIR; ?>/icon-arrow-right.svg" alt=""></a> */ ?>
		<div class="row">
			<div class="carousel-wrapper" data-carousel-last-visible><?php

				while(have_posts()) : the_post();
			
					$post_meta = get_post_meta(get_the_ID());

					$category = get_the_category();
					$category_name = ('Uncategorized' === $category[0]->name ) ? '' : $category[0]->name;

					$image = get_the_post_thumbnail_url(); ?>
			
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
				<?php endwhile; ?>
			</div>
		</div><?php
	}