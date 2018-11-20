<?php 
	 get_header(); ?>

	<div class="home-hero-img"></div>
	<main id="home" class="new">
		 <?php /*if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Widget carrossel - Home") ) :
		endif; */ ?> 
		<section id="new-home">
			<div class="wrapper">
				<div class="container container-reset">
					<div class="row">
						<div class="col-xs-12 col-sm-6 pull-left">
							<?php
								$my_id = 19;
								$post_id_19 = get_post($my_id);
								$content = $post_id_19->post_content;
								echo $content;
								wp_reset_query();
							?>
						</div>
						<div class="col-xs-12 col-sm-6 pull-right">
							<div>
								<p>Este site é só a nossa primeira versão, mas tem muito mais vindo por aí. Nos próximos meses, novas funcionalidades vão te ajudar ainda mais a escolher melhor (e, a gente espera, voltar sempre!). Para receber as novidades em primeira mão, deixe seu e-mail aqui embaixo.</p>
								<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Sendgrid") ) : endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<section id="new-home-bottom">
			<?php /*<div class="container container-reset">
				<section id="home-content-posts">
					<h3 class="list-title">Leia mais:</h3>
					<div class="row">
						<?php while ( have_posts() ) : the_post(); ?>
							<article class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
								<a href="<?php the_permalink(); ?>">
									<div>
										<footer>
											<h5><?php echo get_the_title(); ?></h5>
										</footer>
									</div>
									<img class="img-responsive" src="<?php the_post_thumbnail_url(); ?>" alt="">
								</a>
							</article>
						<?php endwhile; wp_reset_query(); ?>
					</div> 
				</section>
			</div> */ ?>
			<div class="container container-reset">
				<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Widget - Home") ) :
				endif; ?>
			</div>
		</section>
		<img id="logo-big" src="<?php bloginfo('template_url'); ?>/__assets/img/logo_ybuy-home-new.svg" alt="YBUY" class="img-svg">
	</main>

	<?php get_footer();
