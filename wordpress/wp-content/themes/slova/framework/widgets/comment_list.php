<?php
class RO_Comment_List_Widget extends RO_Widget {
	public function __construct() {
		$this->widget_cssclass    = 'ro-comment ro-widget-comment-list';
		$this->widget_description = __( 'Display a list of your comments on your site.', 'slova' );
		$this->widget_id          = 'ro_comment_list';
		$this->widget_name        = __( 'Comment List', 'slova' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => __( 'Comment List', 'slova' ),
				'label' => __( 'Title', 'slova' )
			),
			'posts_per_page' => array(
				'type'  => 'number',
				'step'  => 1,
				'min'   => 1,
				'max'   => '',
				'std'   => 2,
				'label' => __( 'Number of comments to show', 'slova' )
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
		$posts_per_page         = absint( $instance['posts_per_page'] );
		$orderby                = sanitize_text_field( $instance['orderby'] );
		$order                  = sanitize_text_field( $instance['order'] );
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
			'number' => $posts_per_page,
			'type' => 'comment',
			'status' => 'approve',
			'order'          => 'DESC',
			'orderby'        => 'comment_date',
		);
		
		$comment_query = new WP_Comment_Query;
		$comments = $comment_query->query( $query_args );   

		?>
			<ul class="ro-comment-list">
				<?php foreach ($comments as $comment) { ?>
					<li>
						<h6 class="ro-title">
							<a href="<?php echo get_comment_link($comment->comment_ID); ?>"><?php echo $comment->post_title; ?></a>
						</h6>
						<div class="ro-meta">
							<span class="ro-author"><i class="fa fa-pencil"></i> <?php echo $comment->comment_author; ?></span>
							<span class="ro-public"><i class="fa fa-clock-o"></i> <?php echo human_time_diff( strtotime($comment->comment_date), current_time('timestamp') ) . ' ago'; ?></span>
						</div>
					</li>
				<?php } ?>
			</ul>
		<?php 

		echo ''.$after_widget;
                
		$content = ob_get_clean();

		echo ''.$content;

	}
}
/* Class RO_Comment_List_Widget */
function register_comment_list_widget() {
    register_widget('RO_Comment_List_Widget');
}

add_action('widgets_init', 'register_comment_list_widget');
