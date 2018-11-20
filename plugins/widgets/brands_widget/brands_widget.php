<?php 

	class Brands_Widget extends WP_widget {

		function __construct() {
			parent::__construct(
				'brands_widget',
				'Widget de Marcas',
				array('description' => __('Widget que mostra as últimas Marcas cadastrados.'))
			);
		}

		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['numberOfListings'] = strip_tags($new_instance['numberOfListings']);
			$instance['orderby'] = strip_tags($new_instance['orderby']);
			return $instance;
		}

		function form($instance) {
			if($instance) {
				$title = esc_attr($instance['title']);
				$numberOfListings = esc_attr($instance['numberOfListings']);
				$orderby = esc_attr($instance['orderby']);
			} else {
				$title = '';
				$numberOfListings = '';
				$orderby = '';
			}
			?>

			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Título', 'brands_widget'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('numberOfListings'); ?>"><?php _e('Número de listagem', 'brands_widget'); ?></label>
				<select id="<?php echo $this->get_field_id('numberOfListings'); ?>"  name="<?php echo $this->get_field_name('numberOfListings'); ?>">
					<?php for($x=1;$x<=10;$x++): ?>
					<option <?php echo $x == $numberOfListings ? 'selected="selected"' : '';?> value="<?php echo $x;?>"><?php echo $x; ?></option>
					<?php endfor;?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('orderby'); ?>"><?php _e('Ordenação', 'brands_widget'); ?></label>
				<select id="<?php echo $this->get_field_id('orderby'); ?>"  name="<?php echo $this->get_field_name('orderby'); ?>">
					<option <?php echo 'count' === $orderby ? 'selected="selected"' : '';?> value="count">Contagem</option>
					<option <?php echo 'name' === $orderby ? 'selected="selected"' : '';?> value="name">Nome</option>
				</select>
			</p>
			<?php 
		}

		function widget($args, $instance) {
			extract($args);
			$title = apply_filters('widget_title', $instance['title']);
			$numberOfListings = $instance['numberOfListings'];
			$orderby = $instance['orderby'];
			echo $before_widget;

			echo '<section id="list-most-popular-manufacturers" class="carousel">';
			echo	'<h3 class="list-title pull-left">' . $title . '</h3>';
			//echo	'<a class="pull-right" href="' . get_bloginfo('url') . '/marcas">Ver todas as empresas <img width="10" class="img-svg" src="' . IMG_DIR . '/icon-arrow-right.svg" alt=""></a>';
			echo	'<div class="row">';
			echo 		'<div class="carousel-wrapper" data-carousel-last-visible>'; 
							$this->get_brands_list($numberOfListings, $orderby);
			echo 		'</div>';
			echo 	'</div>';
			echo '</section>';

			echo $after_widget;
		}

		function get_brands_list($numberOfListings, $orderby) {
			
			$brands = get_terms( array(
					'taxonomy' 	 => 'brands',
					'number'     => $numberOfListings, 
					'orderby'    => $orderby,
					'order'      => ($orderby === 'count' ? 'DESC' : 'ASC') ,
					'hide_empty' => false
				));

			foreach ($brands as $key => $brand) {
				$term_meta = get_term_meta($brand->term_id);

				$image = '<img src="'. esc_url($term_meta['brands_logo'][0]) .'" alt="YBUY">';

				$reviews = get_total_brand_reviews($brand->term_id);

				$star = '';

				for($i=1;$i<=5;$i++) {
					($i <= round($reviews['average_rating'])) ? $star .= '<img width="30" class="img-svg full" src="'. IMG_DIR . '/rating-star-empty.svg" alt="">' : $star .= '<img width="30" class="img-svg empty" src="'. IMG_DIR . '/rating-star-empty.svg" alt="">';
				}

				($brand->count === 1) ? $product_text = ' Produto</a>' : $product_text = ' Produtos</a>';
				($reviews['count'] === 1) ? $review_text = ' Resenha</strong>' : $review_text = ' Resenhas</strong>'; 

				// $listItem = '<article class="col-xs-2">';
				$listItem = '<article class="col-xs-6 col-sm-6 col-lg-2">';
				$listItem .=	'<a href="' . get_bloginfo('url') .'/marcas/' . $brand->slug .'">';
				$listItem .=		'<figure>';
				$listItem .=			$image;
				$listItem .=		'</figure>';
				$listItem .=	'</a>';
				$listItem .=	'<footer>'; 
				$listItem .=		'<h4>' . $brand->name . '</h4>';
				$listItem .=		'<h5>' . $term_meta['subtitle'][0] . '</h5>';
				$listItem .=		'<a href="' . get_bloginfo('url') . '/marcas"><span>'. $brand->count . '</span>' . $product_text;
				/*$listItem .= 		'<div class="review-element">';
				$listItem .= 			$star;					
				$listItem .=			'<strong><span>'. round($reviews['average_rating']) . '</span>/5</strong>';
				$listItem .=		'</div>'; 
				$listItem .=		'<strong><span>'. $reviews['count'] . '</span>' . $review_text; */
				$listItem .=	'</footer>';
				$listItem .= '</article>';
				
				echo $listItem;

			}

		}

	}

	register_widget('Brands_Widget');

	function get_total_brand_reviews($brand_id) {

		//get the 'reviews rate' for the current brand

		$args = array(
			'post_type' => 'review',
			'post_per_page' => -1,
			'meta_key' => 'id_brand',
			'meta_value' => $brand_id
		);

		$query_brand = new WP_Query($args);

		if($query_brand->have_posts()) {
			$total_rating = 0;
			$count = 0;

			while ($query_brand->have_posts()) : $query_brand->the_post();
				$meta = get_post_meta(get_the_ID());

				$total_rating += $meta['product_review_rate'][0];
				$count++;

			
			endwhile;
			$average_rating = $total_rating/$count;
			wp_reset_postdata();
	
		}

		$response = array(
			'count' => $count,
			'average_rating' => $average_rating
		);

		return $response;

	}