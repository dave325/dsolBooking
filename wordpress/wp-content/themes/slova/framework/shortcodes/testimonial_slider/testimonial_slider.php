<?php
function ro_testimonial_slider_func($atts, $content = null) {
    extract(shortcode_atts(array(
        'category' => '',
		'posts_per_page' => -1,
		'orderby' => 'none',
        'order' => 'none',
        'el_class' => '',
        'show_avatar' => 0,
        'show_excerpt' => 0,
		'excerpt_lenght' => 48,
        'excerpt_more' => '',
		'show_info' => 0,
    ), $atts));
			
    $class = array();
    $class[] = 'ro-testimonial-slider-wrapper clearfix';
    $class[] = $el_class;
	
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    
    $args = array(
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        'orderby' => $orderby,
        'order' => $order,
        'post_type' => 'testimonial',
        'post_status' => 'publish');
    if (isset($category) && $category != '') {
        $cats = explode(',', $category);
        $category = array();
        foreach ((array) $cats as $cat) :
        $category[] = trim($cat);
        endforeach;
        $args['tax_query'] = array(
                                array(
                                    'taxonomy' => 'testimonial_category',
                                    'field' => 'id',
                                    'terms' => $category
                                )
                        );
    }
    $wp_query = new WP_Query($args);
	
    ob_start();
	
	if ( $wp_query->have_posts() ) {
    ?>
	<div class="<?php echo esc_attr(implode(' ', $class)); ?>">
		<div class="ro-testimonial-slider flexslider">
			<ul class="slides">
				<?php while ( $wp_query->have_posts() ) { $wp_query->the_post(); ?>
					<li class="ro-slider-item">
						<?php if($show_avatar) { ?>
							<?php $avatar = get_post_meta(get_the_ID(), 'tb_testimonial_avatar', true); ?>
							<div class="ro-avatar"><img src="<?php echo esc_url($avatar); ?>" alt="Avatar"></div>
						<?php } ?>
						<?php if($show_excerpt) { ?>
							<div class="ro-excerpt"><?php echo ro_custom_excerpt((int)$excerpt_lenght, $excerpt_more); ?></div>
						<?php } ?>
						<?php if($show_info) { ?>
							<div class="ro-info">
								<span class="ro-icon-quote"><i class="fa fa-quote-left"></i></span>
								<h5 class="ro-title"><?php the_title(); ?></h5>
								<?php $position = get_post_meta(get_the_ID(), 'tb_testimonial_position', true); ?>
								<span class="ro-position"><?php echo $position; ?></span>
							</div>
						<?php } ?>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>
    <?php
	}
    return ob_get_clean();
}

if(function_exists('insert_shortcode')) { insert_shortcode('testimonial_slider', 'ro_testimonial_slider_func'); }
