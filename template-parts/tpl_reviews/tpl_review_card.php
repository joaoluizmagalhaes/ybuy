<?php 

	$meta = get_post_meta(get_the_ID());
	$postID = get_the_ID();

	?>

	<article class="review col-md-12 col-lg-4">
		<header>
			<div class="pull-left">
				<img src="<?php echo esc_html(get_the_author_meta('user_profile_picture')); ?>" alt="YBUY">
				<div class="pull-left">
					<h3><?php echo esc_html(get_the_author_meta('first_name')) . ' '. esc_html(get_the_author_meta('last_name')); ?></h3>
					<!-- <h4><img width="12" class="img-svg crown-reviewer" src="<?php echo IMG_DIR; ?>/icon-crown-reviewer.svg" alt=""> Reviewer Rei</h4> -->
				</div>
			</div>
			<div class="pull-right">
				<span>Avaliou</span>
				<div class="review-element">
					<strong><span><?php echo esc_html($meta['product_review_rate'][0]); ?></span>/5</strong>
					<?php 

					$star = '';

					for($i=1;$i<=5;$i++) {
						($i <= esc_html($meta['product_review_rate'][0])) ? $star .= '<img width="30" class="img-svg full" src="'. IMG_DIR . '/rating-star-empty.svg" alt="">' : $star .= '<img width="30" class="img-svg empty" src="'. IMG_DIR . '/rating-star-empty.svg" alt="">';
					}

					echo $star;
					?>
					
				</div>
			</div>
		</header>
		<div>
			<strong>
				<img width="31" class="img-svg" src="<?php echo IMG_DIR; ?>/icon-quote.svg" alt="">	
				“<?php echo esc_html(get_the_title()); ?>”
			</strong>
			<h3>Prós</h3>
			<ul class="pros">
				<?php foreach (unserialize($meta['pros_reviews'][0]) as $value) { ?>
					<li><?php echo esc_html($value); ?></li>
				<?php } ?>
			</ul>
			<h3>Contras</h3>
			<ul class="against">
				<?php foreach (unserialize($meta['cons_reviews'][0]) as $value) { ?>
					<li><?php echo esc_html($value); ?></li>
				<?php } ?>
			</ul>
			<h3>Sobre o Produto</h3>
			<p><?php echo esc_html(substr(get_the_content(),0 , 300)); echo (strlen(get_the_content())) > 300 ? '... <a href="' . get_permalink() . '">leia mais</a>' : '' ;?></p>
			<h3>Fotos e Vídeos</h3>
			<ul class="media">
				<li><img src="<?php echo IMG_DIR; ?>/examples/review/1.png" alt="YBUY"></li>
				<li><img src="<?php echo IMG_DIR; ?>/examples/review/2.png" alt="YBUY"></li>
				<li><img src="<?php echo IMG_DIR; ?>/examples/review/3.png" alt="YBUY"></li>
			</ul>
		</div>
		<footer>
			<span class="pull-left"><?php get_the_date('d/m/Y'); ?></span>
			<div class="pull-right">
				<strong>
					<img width="17" class="img-svg" src="<?php echo IMG_DIR; ?>/icon-review-like.svg" alt="">
					<?php echo '<span class="like" data-user="" data-post="'. esc_attr($postID) . '" data-nonce="'. wp_create_nonce('like-nonce') .'">'. esc_html($meta['like_reviews'][0]) .'</span>'; ?>
					<span class="hidden-xs">concordam</span>
				</strong>
				<strong>
					<img width="17" class="img-svg" src="<?php echo IMG_DIR; ?>/icon-review-deslike.svg" alt="">
					<?php echo '<span class="dislike" data-user="" data-post="'. esc_attr($postID) . '" data-nonce="'. wp_create_nonce('dislike-nonce') .'">' . esc_html($meta['dislike_reviews'][0]) . '</span>'; ?> 
					<span class="hidden-xs">disconcordam</span>
				</strong>
			</div>
		</footer>
	</article>
		
