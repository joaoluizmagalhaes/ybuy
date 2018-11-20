<?php 

	get_header(); ?>

	<main class="search-result">
		<article>
			<?php if(have_posts()) : ?>
				<div class="container container-reset">
					<header class="page-header">
						<h1 class="page-title"><?php printf( esc_html__( 'Resultados de pesquisa para: %s', 'yubuy-tema' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
					</header>
					<?php
					while ( have_posts() ) : the_post(); ?>
						
							<?php get_template_part( 'template-parts/tpl_search/search', 'result' ); ?>
					<?php endwhile; ?>
				</div>
			<?php else : ?>
				<div class="container container-reset">
					<?php get_template_part( 'template-parts/tpl_search/search', 'none' ); ?>
				</div>
			<?php endif; ?>
		</article>
	</main>
	<?php get_footer();

