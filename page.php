<?php 
	get_header();

	while (have_posts()): the_post(); ?>

		<main id="page">
			<div class="container container-reset">
				<div class="row">
					<section class="col-xs-10 col-xs-offset-1 col-lg-8 col-lg-offset-2">
						<header>
							<h1><?php echo get_the_title(); ?></h1>
						</header>
						<article>
							<?php the_content();?>
						</article>
					</section>
				</div>
			</div>
		</main>

	<?php endwhile;

	get_footer();
