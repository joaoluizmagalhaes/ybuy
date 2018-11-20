<?php 

	class Category_Widget extends WP_widget {

		function __construct() {
			parent::__construct(
				'category_widget',
				'Widget de Categorias',
				array('description' => __('Widget que mostra as Categorias por quantidade de posts.'))
			);
		}

		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			return $instance;
		}

		function form($instance) {
			if($instance) {
				$title = esc_attr($instance['title']);
			} else {
				$title = '';

			}
			?>

			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Título', 'category_widget'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
			</p>
			<?php 
		}

		function widget($args, $instance) {
			extract($args);
			$title = apply_filters('widget_title', $instance['title']);

			echo $before_widget;
				echo '<section id="list-category-trending">';
					echo '<h3 class="list-title">' . $title . '</h3>';
					echo '<div class="row">';
						$this->get_category_list();
					echo '</div>';
				echo '</section>';
			echo $after_widget;
		}

		function get_category_list() {
			
			$categories = get_terms( array(
					'taxonomy'   => 'category',
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
				}

				$listItem = '<div class="col-xs-12 col-md-4">';


				$listItem .= 	(!$category->parent > 0) ? '<a href="' . get_bloginfo('url') . '/categoria/' . $category->slug . '">' : '<a href="' . get_bloginfo('url') . '/categoria/'. esc_html($parent->slug) . '/' . $category->slug . '">';
				$listItem .=		'<span>' . $category->name . '</span>';
				$listItem .=		'<img src="'. $image . '" alt="YBUY">';
				$listItem .=	'</a>';
				$listItem .= '</div>'; 

				echo $listItem;

			}
		}
	}

	register_widget('Category_Widget');