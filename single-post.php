<?php get_header(); ?>

	<main id="post">

		<?php if(have_posts()) {
			while (have_posts()) : the_post(); ?>
				<article>
					<header>
						<h1><?php echo esc_html(get_the_title()); ?></h1>
						<?php /*<img class="img-responsive" src="<?php echo the_post_thumbnail('full'); ?>" alt="YBUY"> */ ?>
						<?php 
							$post_meta = get_post_meta(get_the_ID()); 
							$hero_image = $post_meta['widget_image'][0];
						?>
						<img src="<?php echo esc_url($hero_image); ?>" alt="">
					</header>
					<div class="container container-reset">
						<div class="row">
							<section class="col-xs-10 col-xs-offset-1 col-lg-8 col-lg-offset-2">
								<header>
									<?php $author = 'user_' . get_the_author_meta('ID'); ?>
									<?php /*<img src="<?php the_field('foto_dos_autores', $author); ?>" /> */ ?> 
									<img src="<?php echo get_the_author_meta('user_profile_picture'); ?>" alt="YBUY">
									<div class="pull-left">
										<h2>Por <?php echo get_the_author_meta('first_name'); ?></h2>
										<time pubdate><?php the_date('d \d\e F \d\e Y'); ?></time>
									</div>
								</header>
								<article>
									<?php the_content(); ?>
								</article>
							</section>
						</div>
					</div>
				</article> 

			<?php endwhile;

		} ?>
	</main>
	
	<?php get_footer();