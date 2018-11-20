<?php get_header(); if (have_posts()) : the_post(); ?>

	<main id="post">
		<article>
			<header>
				<h1><?php echo get_the_title(); ?></h1>
				<?php $heroImage = get_field('hero_image'); ?>
				<img class="img-responsive" src="<?php echo $heroImage['url']; ?>" />
			</header>
			<div class="container container-reset">
				<div class="row">
					<section class="col-xs-12 col-xs-offset-0 col-lg-8 col-lg-offset-2">
						<header>
							<?php $author = 'user_' . get_the_author_meta('ID'); ?>
							<img src="<?php the_field('foto_dos_autores', $author); ?>" />
							<div class="pull-left">
								<h2><?php echo get_the_author(); ?></h2>
								<time pubdate><?php echo get_the_date(); ?></time>
							</div>
						</header>
						<article>
							<?php the_content(); ?>
						</article>
					</section>
					<!-- <?php  
						//$seller = get_post_meta( get_the_ID(), 'seller', true );
						//if ( ! empty( $seller ) ) {
							//echo $seller;
						//} else {
							//echo 'FALSE';
						//}
					?> -->
				</div>
			</div>
		</article>
	</main>

<?php endif; get_footer(); ?>

