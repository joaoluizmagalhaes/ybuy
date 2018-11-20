<?php 

	class Reviews_Widget extends WP_widget {

		function __construct() {
			parent::__construct(
				'reviews_widget',
				'Widget de Reviews',
				array('description' => __('Widget que mostra os últimos reviews cadastrados.'))
			);
		}

		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['numberOfListings'] = strip_tags($new_instance['numberOfListings']);
			return $instance;
		}

		function form($instance) {
			if($instance) {
				$title = esc_attr($instance['title']);
				$numberOfListings = esc_attr($instance['numberOfListings']);
			} else {
				$title = '';
				$numberOfListings = '';
			}
			?>

			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Título', 'reviews_widget'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('numberOfListings'); ?>"><?php _e('Número de listagem', 'reviews_widget'); ?></label>
				<select id="<?php echo $this->get_field_id('numberOfListings'); ?>"  name="<?php echo $this->get_field_name('numberOfListings'); ?>">
					<?php for($x=1;$x<=10;$x++): ?>
					<option <?php echo $x == $numberOfListings ? 'selected="selected"' : '';?> value="<?php echo $x;?>"><?php echo $x; ?></option>
					<?php endfor;?>
				</select>
			</p>
			<?php 
		}

		function widget($args, $instance) {
			extract($args);
			$title = apply_filters('widget_title', $instance['title']);
			$numberOfListings = $instance['numberOfListings'];
			echo $before_widget;
			if($title) {
				echo $before_title . $title . $after_title;
			}

			$this->get_review_list($numberOfListings);
			echo $after_widget;
		}

		function get_review_list($numberOfListings) {
			global $post;

			add_image_size('reviews_widget_size', 85, 45, false);

			$reviews = new WP_Query();
			$reviews->query('post_type=review&posts_per_page='. $numberOfListings);

			if($reviews->found_posts > 0) {
				echo '<ul class="reviews_list">';
					while($reviews->have_posts()) {
						$reviews->the_post();
						$image = (has_post_thumbnail($post->ID)) ? get_the_post_thumbnail($post->ID, 'reviews_widget_size') : '<div class="noThumb"></div>';
						$listItem = '<li>' . $image;
						$listItem .= ' <a href="' . get_permalink() . '">';
						$listItem .= get_the_title() . '</a>';
						$listItem .= '<span> Em: '. get_the_date('d/m/Y') . '</span></li>';
						echo $listItem;
					}
				echo '</ul>';
				wp_reset_postdata();

			} else {
				echo '<p style="padding:25px;"> Nenhum produto encontrado!</p>';
			}

		}

	}



	register_widget('Reviews_Widget');