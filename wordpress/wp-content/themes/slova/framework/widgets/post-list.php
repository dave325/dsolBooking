<?php
class RO_Post_List_Widget extends RO_Widget {
	public function __construct() {
		$this->widget_cssclass    = 'ro-post ro-widget-post-list';
		$this->widget_description = __( 'Display a list of your posts on your site.', 'slova' );
		$this->widget_id          = 'ro_post_list';
		$this->widget_name        = __( 'Post List', 'slova' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => __( 'Post List', 'slova' ),
				'label' => __( 'Title', 'slova' )
			),
			'category' => array(
				'type'   => 'tb_taxonomy',
				'std'    => '',
				'label'  => __( 'Categories', 'slova' ),
			),
			'posts_per_page' => array(
				'type'  => 'number',
				'step'  => 1,
				'min'   => 1,
				'max'   => '',
				'std'   => 2,
				'label' => __( 'Number of posts to show', 'slova' )
			),
			'orderby' => array(
				'type'  => 'select',
				'std'   => 'none',
				'label' => __( 'Order by', 'slova' ),
				'options' => array(
					'none'   => __( 'None', 'slova' ),
					'comment_count'  => __( 'Comment Count', 'slova' ),
					'title'  => __( 'Title', 'slova' ),
					'date'   => __( 'Date', 'slova' ),
					'ID'  => __( 'ID', 'slova' ),
				)
			),
			'order' => array(
				'type'  => 'select',
				'std'   => 'none',
				'label' => __( 'Order', 'slova' ),
				'options' => array(
					'none'  => __( 'None', 'slova' ),
					'asc'  => __( 'ASC', 'slova' ),
					'desc' => __( 'DESC', 'slova' ),
				)
			),
			'tpl' => array(
				'type'  => 'select',
				'std'   => 'tpl1',
				'label' => __( 'Template', 'slova' ),
				'options' => array(
					'tpl1'  => __( 'Template 1', 'slova' ),
					'tpl2'  => __( 'Template 2', 'slova' ),
				)
			),
			'el_class'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Extra Class', 'slova' )
			)
		);
		parent::__construct();
		add_action('admin_enqueue_scripts', array($this, 'widget_scripts'));
	}
        
	public function widget_scripts() {
		wp_enqueue_script('widget_scripts', URI_PATH . '/framework/widgets/widgets.js');
	}

	public function widget( $args, $instance ) {
		
		global $post;
		extract( $args );
                
		$title                  = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$category               = isset($instance['category'])? $instance['category'] : '';
		$posts_per_page         = absint( $instance['posts_per_page'] );
		$orderby                = sanitize_text_field( $instance['orderby'] );
		$order                  = sanitize_text_field( $instance['order'] );
		$tpl                  	= sanitize_text_field( $instance['tpl'] );
		$el_class               = sanitize_text_field( $instance['el_class'] );
        
		// no 'class' attribute - add one with the value of width
        if (strpos($before_widget, 'class') === false) {
            $before_widget = str_replace('>', 'class="' . esc_attr($el_class) . '"', $before_widget);
        }
        // there is 'class' attribute - append width value to it
        else {
            $before_widget = str_replace('class="', 'class="' . esc_attr($el_class) . ' ', $before_widget);
        }
		
        ob_start();
		   
		echo ''.$before_widget;

		if ( $title )
				echo ''.$before_title . $title . $after_title;
		
		$query_args = array(
			'posts_per_page' => $posts_per_page,
			'orderby' => $orderby,
			'order' => $order,
			'post_type' => 'post',
			'post_status' => 'publish');
		if (isset($category) && $category != '') {
			$cats = explode(',', $category);
			$category = array();
			foreach ((array) $cats as $cat) :
			$category[] = trim($cat);
			endforeach;
			$query_args['tax_query'] = array(
									array(
										'taxonomy' => 'category',
										'field' => 'id',
										'terms' => $category
									)
							);
		}
		
		$wp_query = new WP_Query($query_args);                
		
		if ($wp_query->have_posts()){
			?>
			<ul class="ro-post-list <?php echo esc_attr($tpl); ?>">
				<?php ?>
				<?php while ($wp_query->have_posts()){ $wp_query->the_post(); ?>
					<li>
						<?php include 'post-list/'.$tpl.'.php'; ?>
					</li>
				<?php } ?>
			</ul>
		<?php 
		}
		
		wp_reset_postdata();

		echo ''.$after_widget;
                
		$content = ob_get_clean();

		echo ''.$content;

	}
}
/* Class RO_Post_List_Widget */
function register_post_list_widget() {
    register_widget('RO_Post_List_Widget');
}

add_action('widgets_init', 'register_post_list_widget');
