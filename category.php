<?php 
	
	$the_category = get_category($cat);
	$category_image = get_term_meta( $the_category->term_id, 'category_image'); 

	get_header();
	?>

	<section id="sub-header">
		<div class="pull-left">
			<strong>
				<?php         
                    echo esc_html($the_category->cat_name);
                ?>
			</strong>
		</div>
	</section>
	<section id="category-header">
		<h2><?php single_cat_title(''); ?></h2>
		<img class="img-responsive" src="<?php echo $category_image[0]; ?>" alt="<?php single_cat_title(''); ?>">
	</section>

	<main id="category">
		<div class="container container-reset">
			<section id="list-category-trending">
				<?php if(!empty(get_term_children($the_category->term_id, 'category'))) { ?>
					<h3 class="list-title">Categorias em destaque</h3>
					<div class="row">
						<?php 
							$categories = get_categories( array(
								'parent'     => $cat,
								'number'     => 6, 
								'orderby'    => 'count',
								'order'      => 'DESC',
								'hide_empty' => false,
								'exclude'    => array(1),

							));

							foreach ($categories as $category) {
								$term_meta = get_term_meta($category->term_id);
								$image = esc_url($term_meta['category_image'][0]);

								if($category->parent > 0) {
									$parent = get_category($category->parent);
								} ?>

								<div class="col-xs-12 col-md-4">
								 	<?php echo (!$category->parent > 0) ? '<a href="' . get_bloginfo('url') . '/categoria/' . esc_attr($category->slug) . '">' : '<a href="' . get_bloginfo('url') . '/categoria/'. esc_attr($parent->slug) . '/' . esc_attr($category->slug) . '">'; ?>
										<span><?php echo esc_html($category->name); ?></span>
										<img src="<?php echo $image; ?>" alt="YBUY">
									</a>
								</div> 
							<?php }
						?>
					</div>
				<?php } ?>
			</section>
			<section id="list-best-rated" class="carousel">
				<?php

					$request = wp_remote_get(get_bloginfo('url') . '/query_products/' . $the_category->slug);

	                $response = json_decode(wp_remote_retrieve_body($request));

	                echo wp_kses($response->html, ybuy_html_allowed());

				?>
			
			</section>
			<section id="list-most-popular-articles" class="carousel">
				<?php

					$request = wp_remote_get(get_bloginfo('url') . '/query_posts/' . $the_category->slug);

	                $response = json_decode(wp_remote_retrieve_body($request));

	                echo wp_kses($response->html, ybuy_html_allowed());

				?>
			</section>
			<section class="category-widget">
				<div class="container container-reset">
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Widget - Categorias") ) :
					endif; ?>
				</div>
			</section>
		</div>
	</main>

<?php get_footer() ?>