<?php

    $current_category = get_category($cat);
	$category_parent = get_category($current_category->parent);
	$category_image = get_term_meta($current_category->term_id, 'category_image');

	/*if(empty($categories)) {
		wp_redirect(home_url());
		exit();
	}*/

	get_header();
	?>

	<section id="sub-header">
		<div class="pull-left">
			<strong>
				<?php         
	                echo $current_category->cat_name . ' <span> Em <a href="' . get_bloginfo('url') . '/categoria/' . $category_parent->slug . '">' . $category_parent->name .' </a></span>';
                ?>
			</strong>
		</div>
		<?php /* <nav class="pull-right">
            <?php foreach ($dynamicAnchorMenu as $value) { ?>
                <a href="#"><?php echo $value; ?> </a>
            <?php } ?>
            <?php if ($buttonWhereToFind) { ?>
                <a class="btn btn-small havelock-blue" href="#">Onde Encontrar</a>  
            <?php } ?>
        </nav> */ ?>
	</section>
	<section id="category-header">
		<h2><?php single_cat_title(''); ?></h2>
		
		<img class="img-responsive" src="<?php echo $category_image[0]; ?>" alt="<?php single_cat_title(''); ?>">
	</section>

	<main id="subcategory">
		<div class="container container-reset">
			<section id="list-best-rated" class="carousel">
				<?php

					$request = wp_remote_get(get_bloginfo('url') . '/query_products/' . $current_category->slug);

	                $response = json_decode(wp_remote_retrieve_body($request));

	                echo wp_kses($response->html, ybuy_html_allowed());

				?>
			
			</section>
			<section id="list-most-popular-articles" class="carousel">
				<?php

					$request = wp_remote_get(get_bloginfo('url') . '/query_posts/' . $current_category->slug);

	                $response = json_decode(wp_remote_retrieve_body($request));

	                echo wp_kses($response->html, ybuy_html_allowed());

				?>
			</section>
			<section class="category-widget">
				<div class="container container-reset">
					<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar("Widget - Sub-Categorias") ) :
					endif; ?>
				</div>
			</section>
		</div>
	</main>

<h1 class="page-title"><?php $current_category->name; ?></h1>

<?php get_footer() ?>