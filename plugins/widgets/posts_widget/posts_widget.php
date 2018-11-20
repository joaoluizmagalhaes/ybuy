<?php 

	class Posts_Widget extends WP_widget {

		function __construct() {
			parent::__construct(
				'posts_widget',
				'Widget de Conteúdo',
				array('description' => __('Widget que mostra as últimas postagens de conteúdo cadastradas.'))
			);
		}

		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['numberOfListings'] = strip_tags($new_instance['numberOfListings']);
			
			if(count($new_instance['category']) > 1) {
				$instance['category'] = implode(',', $new_instance['category']);

			} else {
				$instance['category'] = strip_tags($new_instance['category'][0]);
			}
			
			return $instance;
		}

		function form($instance) {
			$category_array = array();

			if($instance) {
				$title = esc_attr($instance['title']);
				$numberOfListings = esc_attr($instance['numberOfListings']);
				$category = esc_attr($instance['category']);
				
				if ('' !== $category) {
					$category_array = explode(',', $category);
				} else {
					$category_array = array();
				}

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
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Título', 'posts_widget'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('numberOfListings'); ?>"><?php _e('Número de listagem', 'posts_widget'); ?></label>
				<select id="<?php echo $this->get_field_id('numberOfListings'); ?>" name="<?php echo $this->get_field_name('numberOfListings'); ?>">
					<?php for($x=1;$x<=10;$x++): ?>
					<option <?php echo $x == $numberOfListings ? 'selected="selected"' : '';?> value="<?php echo $x;?>"><?php echo $x; ?></option>
					<?php endfor;?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('category'); ?>"> <?php _e('Categoria do conteúdo', 'posts_widget'); ?></label>
				<select id="<?php echo $this->get_field_id('category'); ?>" name="<?php echo $this->get_field_name('category'); ?>[]" multiple="multiple">
					<option <?php echo empty($category_array) ? 'selected="selected"' : ''; ?> value="">Todas as categorias</option>
					<?php foreach ($categories as $cat) { ?>
						<option  <?php echo in_array( $cat->slug, $category_array) ? 'selected="selected"' : ''; ?> value="<?php echo esc_attr_e($cat->slug); ?>" ><?php echo esc_attr_e($cat->name); ?></option>
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
				echo '<section id="home-content-posts" class="carousel">';
					echo '<h3 class="list-title pull-left">' . $title . '</h3>';
					//echo '<a class="pull-right" href="#">Ver todos <img width="10" class="img-svg" src="'. IMG_DIR .'/icon-arrow-right.svg" alt=""></a>';
					echo '<div class="row">';
						echo '<div class="carousel-wrapper" data-carousel-last-visible>'; 
							$this->get_product_list($numberOfListings, $category, $title);
						echo '</div>';
					echo '</div>';
				echo '</section>';
			echo $after_widget;
		}

		function get_product_list($numberOfListings, $category, $title) {
			global $post;

			add_image_size('posts_widget_size');

			$args = array (
				'post_type'      => 'post',
				'posts_per_page' => $numberOfListings,
				'category_name'  => $category

			);

			$posts = new WP_Query($args);	

			if($posts->have_posts()) { 
					while($posts->have_posts()) {
						$posts->the_post();
						$post_meta = get_post_meta($post->ID);

						$category = get_the_category();
						$category_name = ('Uncategorized' === $category[0]->name ) ? '' : $category[0]->name;

						$image = get_the_post_thumbnail_url();
						
						// $listItem = '<article class="col-xs-3">';
						$listItem = '<article class="col-xs-6 col-sm-6 col-md-4 col-lg-3">';
						$listItem .=	'<a href="' . get_permalink() .'">'; 
						$listItem .=		'<div>';
						$listItem .=			'<h4>' . $category_name . '</h4>';
						$listItem .=			'<footer>';
						$listItem .=				'<h5>' . get_the_title() . '</h5>';
						/*$listItem .=				'<strong class="pull-left"><span>' . get_comments_number(0) . '</span> Comentários</strong>';*/
						/*$listItem .=				'<strong class="pull-right"><img width="17" class="img-svg" src="<?php echo IMG_DIR; ?>/icon-like-up.svg" alt=""><span>13</span></strong>';*/
						$listItem .=			'</footer>';
						$listItem .=		'</div>';
						$listItem .=	 	'<img class="img-responsive" src="' . $image . '">'; 
						$listItem .= 	'</a>';     			
						$listItem .='</article>';

						echo $listItem;
					}
				wp_reset_postdata();

			} else {
				echo '<p style="padding:25px;"> Nenhum conteúdo encontrado!</p>';
			}

		}
	}
	register_widget('Posts_Widget');