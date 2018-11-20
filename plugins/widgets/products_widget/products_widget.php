<?php 

	class Products_Widget extends WP_widget {

		function __construct() {
			parent::__construct(
				'products_widget',
				'Widget de Produtos',
				array('description' => __('Widget que mostra os últimos produtos cadastrados.'))
			);
		}

		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['numberOfListings'] = strip_tags($new_instance['numberOfListings']);
			$instance['category'] = strip_tags($new_instance['category']);

			return $instance;
		}

		function form($instance) {
			if($instance) {
				$title = esc_attr($instance['title']);
				$numberOfListings = esc_attr($instance['numberOfListings']);
				$category = esc_attr($instance['category']);


			} else {
				$title = '';
				$numberOfListings = '';
				$category = '';
			}

			$categories = get_terms(array(
					'taxonomy'   => 'category',
					'hide_empty' => false,
					'orderby'    => 'name',
					'order'      => 'ASC'
			));

			?>

			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Título', 'products_widget'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('numberOfListings'); ?>"><?php _e('Número de listagem', 'products_widget'); ?></label>
				<select id="<?php echo $this->get_field_id('numberOfListings'); ?>" name="<?php echo $this->get_field_name('numberOfListings'); ?>">
					<?php for($x=1;$x<=10;$x++): ?>
					<option <?php echo $x == $numberOfListings ? 'selected="selected"' : '';?> value="<?php echo $x;?>"><?php echo $x; ?></option>
					<?php endfor;?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('category'); ?>"> <?php _e('Categoria dos produtos', 'products_widget'); ?></label>
				<select id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>">
					<?php foreach ($categories as $cat) { ?>
						<option  <?php echo $cat->slug == $category ? 'selected="selected"' : ''; ?> value="<?php echo esc_attr_e($cat->slug); ?>" ><?php echo esc_attr_e($cat->name); ?></option>
					<?php } ?>
				</select>
			</p>
			<?php 
		}

		function widget($args, $instance) {
			extract($args);
			$title = apply_filters('widget_title', $instance['title']);
			$numberOfListings = $instance['numberOfListings'];
			$category = $instance['category'];

			echo $before_widget;

			echo '<section id="list-best-rated" class="carousel">';
			echo	'<h3 class="list-title pull-left">' . $title . '</h3>';
			//echo	'<a class="pull-right" href="' . get_bloginfo('url') . '/produtos">Ver todos <img width="10" class="img-svg" src="'.  IMG_DIR . '/icon-arrow-right.svg" alt=""></a>';
			echo	'<div class="row">';
			echo 		'<div class="carousel-wrapper" data-carousel-last-visible>'; 
							$this->get_product_list($numberOfListings, $category);
			echo 		'</div>';
			echo 	'</div>';
			echo '</section>';
			echo $after_widget;
		}

		function get_product_list($numberOfListings, $category) {
			
			add_image_size('products_widget_size', 280, 280, true);

			$args = array (
				'post_type'      => 'product',
				'posts_per_page' => $numberOfListings,
				'category_name'  => $category

			);

			$products = new WP_Query($args);

			if($products->have_posts()) {
				while($products->have_posts()) : $products->the_post();

					
					$terms = get_the_terms(get_the_ID(), 'brands');
					$image = (has_post_thumbnail(get_the_ID())) ? get_the_post_thumbnail(get_the_ID(), 'products_widget_size') : '<div class="noThumb"></div>';

					//$reviews = get_total_product_reviews(get_the_ID());
		
					$star = '';

					for($i=1;$i<=5;$i++) {
						($i <= round($reviews->rating)) ? $star .= '<img width="30" class="img-svg full" src="'. IMG_DIR . '/rating-star-empty.svg" alt="">' : $star .= '<img width="30" class="img-svg empty" src="'. IMG_DIR . '/rating-star-empty.svg" alt="">';
					}

					($reviews->count === 1) ? $review_text = ' Resenha</strong>' : $review_text = ' Resenhas</strong>'; 

					$listItem = '<article class="col-xs-12 col-lg-3">'; 
					$listItem .=	'<a href="' . get_permalink() . '">'; 
					$listItem .=		 $image; 
					$listItem .=	'</a>'; 
					$listItem .=	'<footer>';
					$listItem .=		'<h4>' . get_the_title() . '</h4>';
					$listItem .=		'<h5><a href="' . get_bloginfo('url') . '/marcas/' . $terms[0]->slug . '">' . $terms[0]->name . '</a></h5>';
					/*$listItem .= 		'<div class="review-element">';
					$listItem .= 			$star;					
					$listItem .=			'<strong><span>'. round($reviews->rating) . '</span>/5</strong>';
					$listItem .=		'</div>'; 
					$listItem .=		'<strong><span>' . $reviews->count . '</span>' . $review_text;
					$listItem .=		'<img width="30" class="img-svg" src="' . IMG_DIR . '/icon-heart.svg" alt="">';*/
					$listItem .=	'</footer>';
					$listItem .= '</article>'; 

					echo $listItem;
				endwhile;

				wp_reset_postdata();

			} else {
				echo '<p style="padding:25px;"> Nenhum produto encontrado!</p>';
			}

		}
	}
	register_widget('Products_Widget');

	function get_total_product_reviews($product_id) {

		 //get the 'reviews rate' for the current product
	    $request = wp_remote_get(get_site_url() . '/return-reviews-rate/' . $product_id);
	    $response = json_decode(wp_remote_retrieve_body($request));
		
		return $response;

	}