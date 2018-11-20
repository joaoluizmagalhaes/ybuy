<?php

/**
 * Template Name: Perfil
 *
 */
	if(!is_user_logged_in()) {
		wp_redirect(get_bloginfo('url') . '/login');
	} else {
		get_header();

		global $current_user;

		$user_meta = get_user_meta($current_user->ID);	

		$args_comments_count = array(
			'user_id' => $current_user->ID,
			'status'  => 'approve',
			'count'   => true
		);

		$args_main_reviews = array(
			'post_type'     => 'review',
			'post_per_page' => 3,
			'author'		=> $current_user->ID,
			'meta_key'      => 'like_reviews',
			'order_by'      => 'meta_value',
			'order'         => 'ASC'
		);

		$args_reviews = array(
			'post_type'     => 'review',
			'author'		=> $current_user->ID,
			'post_per_page' => 6
		);

		$main_reviews = new WP_Query($args_main_reviews); 
		wp_reset_postdata();

		$total_reviews = new WP_Query($args_reviews);
		wp_reset_postdata();

		$user_comments_count = get_comments($args_comments_count);
		wp_reset_postdata();

		?>

		<main id="profile">
			<section id="profile-header">
				<div class="container container-reset">
					<div class="pull-left">
						<img class="pull-left" src="<?php echo $user_meta['user_profile_picture'][0]; ?>" alt="YBUY">
						<div class="pull-left">
							<h3><?php echo $user_meta['first_name'][0] . ' ' . $user_meta['last_name'][0];?></h3>
							<!-- <h4><img width="20" class="img-svg crown-reviewer" src="<?php echo IMG_DIR; ?>/icon-crown-reviewer.svg" alt=""> Reviewer Rei</h4>
							<div id="reviewer-level">
								<span></span>
							</div> -->
							<strong><span><?php echo esc_html(count(get_posts($args_reviews))); echo (count(get_posts($args_reviews)) > 1) ? '</span> Resenhas</strong>' : '</span> Resenha</strong>' ; ?>
							<!-- <p>Faltam <span>5</span> resenhas para o próximo nível!</p> -->
							<?php if(isset($_GET['msg'])) echo '<br><span class="edit-msg">'.$_GET['msg'].'!</span>'; ?>
						</div>
					</div>
					<div class="pull-right">
						<a href="<?php echo get_bloginfo('url') . '/editar-perfil/' ?>" class="btn btn-middle white">Editar Perfil</a>
					</div>
				</div>
				<footer>
					<nav class="container container-reset">
						<div class="col-xs-4 col-xs-offset-4" data-tabs="profile">
							<a href="#" class="active" data-tab="summary">Resumo</a>
							<a href="#" data-tab="comments">Comentários <span><?php echo esc_html($user_comments_count); ?></span></a>
							<a href="#" data-tab="reviews">Resenhas <span><?php echo esc_html(count(get_posts($args_reviews))); ?></span></a>
						</div>
					</nav>
				</footer>
			</section>
			<div class="container container-reset" data-tab-name="profile">
				<div class="tab-container">
					<div data-tab-anchor="summary">
						<section class="summary">
							<section id="reviews-on-product">
								<h3 class="list-title">Principais Resenhas</h3>
								<div class="row">
									<?php

										if($main_reviews->have_posts() ) :

											while($main_reviews->have_posts() ) : $main_reviews->the_post();

												include (get_stylesheet_directory() . '/template-parts/tpl_reviews/tpl_review_card.php');
											
											endwhile;

										endif; 
									?>
									<a href="#" class="visible-xs btn btn-middle havelock-blue">Ver todas as Resenhas</a>
								</div>
							</section>
						</section>
					</div>
					<div data-tab-anchor="comments">
						<section class="comments">
							
						</section>
					</div>
					<div data-tab-anchor="reviews">
						<section class="reviews">
							<section id="reviews-on-product">
								<h3 class="list-title"><?php echo esc_html(count_user_posts($current_user->ID, 'review')); ?> Resenhas</h3>
								<div class="row">
									<?php
										if($total_reviews->have_posts() ) :

											while($total_reviews->have_posts() ) : $total_reviews->the_post();

												include (get_stylesheet_directory() . '/template-parts/tpl_reviews/tpl_review_card.php');
											
											endwhile;

										endif; 
									?>
									<a href="#" class="visible-xs btn btn-middle havelock-blue">Ver todas as Resenhas</a>
								</div>
							</section>
						</section>
					</div>
				</div>
			</div>
		</main>

		<?php

		get_footer();

	}