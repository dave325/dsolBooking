<?php
class RO_Fancy_List_Widget extends RO_Widget {
	public function __construct() {
		$this->widget_cssclass    = 'ro-fancy ro-widget-fancy-list';
		$this->widget_description = __( 'Display a fancy list on your site.', 'slova' );
		$this->widget_id          = 'ro_fancy_list';
		$this->widget_name        = __( 'Fancy List', 'slova' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => __( 'Fancy List', 'slova' ),
				'label' => __( 'Title', 'slova' )
			),
			'icon1'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Icon 1', 'slova' )
			),
			'text1'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Text 1', 'slova' )
			),
			'icon2'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Icon 2', 'slova' )
			),
			'text2'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Text 2', 'slova' )
			),
			'icon3'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Icon 3', 'slova' )
			),
			'text3'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Text 3', 'slova' )
			),
			'icon4'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Icon 4', 'slova' )
			),
			'text4'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Text 4', 'slova' )
			),
			'icon5'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Icon 5', 'slova' )
			),
			'text5'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Text 5', 'slova' )
			),
			'icon6'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Icon 6', 'slova' )
			),
			'text6'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Text 6', 'slova' )
			),
			'icon7'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Icon 7', 'slova' )
			),
			'text7'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Text 7', 'slova' )
			),
			'icon8'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Icon 8', 'slova' )
			),
			'text8'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Text 8', 'slova' )
			),
			'icon9'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Icon 9', 'slova' )
			),
			'text9'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Text 9', 'slova' )
			),
			'icon10'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Icon 10', 'slova' )
			),
			'text10'  => array(
				'type'  => 'text',
				'std'   => '',
				'label' => __( 'Text 10', 'slova' )
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
		$icon1                	= sanitize_text_field( $instance['icon1'] );
		$text1                  = sanitize_text_field( $instance['text1'] );
		$icon2                	= sanitize_text_field( $instance['icon2'] );
		$text2                  = sanitize_text_field( $instance['text2'] );
		$icon3                	= sanitize_text_field( $instance['icon3'] );
		$text3                  = sanitize_text_field( $instance['text3'] );
		$icon4                	= sanitize_text_field( $instance['icon4'] );
		$text4                  = sanitize_text_field( $instance['text4'] );
		$icon5                	= sanitize_text_field( $instance['icon5'] );
		$text5                  = sanitize_text_field( $instance['text5'] );
		$icon6                	= sanitize_text_field( $instance['icon6'] );
		$text6                  = sanitize_text_field( $instance['text6'] );
		$icon7                	= sanitize_text_field( $instance['icon7'] );
		$text7                  = sanitize_text_field( $instance['text7'] );
		$icon8                	= sanitize_text_field( $instance['icon8'] );
		$text8                  = sanitize_text_field( $instance['text8'] );
		$icon9                	= sanitize_text_field( $instance['icon9'] );
		$text9                  = sanitize_text_field( $instance['text9'] );
		$icon10                	= sanitize_text_field( $instance['icon10'] );
		$text10                 = sanitize_text_field( $instance['text10'] );
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
		
		?>
			<ul class="ro-fancy-list">
				<?php if($text1) { ?>
					<li class="ro-fancy-item clearfix">
						<?php if($icon1) echo '<i class="'.esc_attr($icon1).'"></i> '; echo esc_html($text1 ); ?>
					</li>
				<?php } ?>
				<?php if($text2) { ?>
					<li class="ro-fancy-item clearfix">
						<?php if($icon2) echo '<i class="'.esc_attr($icon2).'"></i> '; echo esc_html($text2 ); ?>
					</li>
				<?php } ?>
				<?php if($text3) { ?>
					<li class="ro-fancy-item clearfix">
						<?php if($icon3) echo '<i class="'.esc_attr($icon3).'"></i> '; echo esc_html($text3 ); ?>
					</li>
				<?php } ?>
				<?php if($text4) { ?>
					<li class="ro-fancy-item clearfix">
						<?php if($icon4) echo '<i class="'.esc_attr($icon4).'"></i> '; echo esc_html($text4 ); ?>
					</li>
				<?php } ?>
				<?php if($text5) { ?>
					<li class="ro-fancy-item clearfix">
						<?php if($icon5) echo '<i class="'.esc_attr($icon5).'"></i> '; echo esc_html($text5 ); ?>
					</li>
				<?php } ?>
				<?php if($text6) { ?>
					<li class="ro-fancy-item clearfix">
						<?php if($icon6) echo '<i class="'.esc_attr($icon6).'"></i> '; echo esc_html($text6 ); ?>
					</li>
				<?php } ?>
				<?php if($text7) { ?>
					<li class="ro-fancy-item clearfix">
						<?php if($icon7) echo '<i class="'.esc_attr($icon7).'"></i> '; echo esc_html($text7 ); ?>
					</li>
				<?php } ?>
				<?php if($text8) { ?>
					<li class="ro-fancy-item clearfix">
						<?php if($icon8) echo '<i class="'.esc_attr($icon8).'"></i> '; echo esc_html($text8 ); ?>
					</li>
				<?php } ?>
				<?php if($text9) { ?>
					<li class="ro-fancy-item clearfix">
						<?php if($icon9) echo '<i class="'.esc_attr($icon9).'"></i> '; echo esc_html($text9 ); ?>
					</li>
				<?php } ?>
				<?php if($text10) { ?>
					<li class="ro-fancy-item clearfix">
						<?php if($icon10) echo '<i class="'.esc_attr($icon10).'"></i> '; echo esc_html($text10 ); ?>
					</li>
				<?php } ?>
			</ul>
		<?php 
		
		wp_reset_postdata();

		echo ''.$after_widget;
                
		$content = ob_get_clean();

		echo ''.$content;

	}
}
/* Class RO_Fancy_List_Widget */
function register_fancy_list_widget() {
    register_widget('RO_Fancy_List_Widget');
}

add_action('widgets_init', 'register_fancy_list_widget');
