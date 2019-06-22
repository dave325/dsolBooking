<?php
function ro_portfolio_slider_func($atts, $content = null) {
    extract(shortcode_atts(array(
        'category' => '',
		'posts_per_page' => -1,
		'orderby' => 'none',
        'order' => 'none',
        'el_class' => '',
        'show_title' => 0,
        'show_category' => 0,
        'show_favorite' => 0,
        'show_readmore' => 0
    ), $atts));
			
    $class = array();
    $class[] = 'ro-portfolio-slider-wrapper clearfix';
    $class[] = $el_class;
	
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    
    $args = array(
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        'orderby' => $orderby,
        'order' => $order,
        'post_type' => 'portfolio',
        'post_status' => 'publish');
    if (isset($category) && $category != '') {
        $cats = explode(',', $category);
        $category = array();
        foreach ((array) $cats as $cat) :
        $category[] = trim($cat);
        endforeach;
        $args['tax_query'] = array(
                                array(
                                    'taxonomy' => 'portfolio_category',
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
		<div class="ro-portfolio-slider flexslider">
			<ul class="slides">
				<?php while ( $wp_query->have_posts() ) { $wp_query->the_post(); ?>
					<li class="ro-portfolio-item ro-col-3">
						<div class="ro-thumb">
							<?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?>
							<?php if($show_readmore) { ?>
								<div class="ro-readmore">
									<a href="<?php the_permalink(); ?>"><i class="fa fa-chevron-right"></i></a>
								</div>
							<?php } ?>
						</div>
						<div class="ro-content">
							<?php if($show_title) { ?>
								<h5 class="ro-title"><?php the_title(); ?></h5>
							<?php } ?>
							<?php if($show_category) { ?>
								<span class="ro-categories"><?php the_terms(get_the_ID(), 'portfolio_category', '' , ', ' ); ?></span>
							<?php } ?>
							<?php if($show_favorite) post_favorite(); ?>
						</div>
					</li>
				<?php } ?>
			</ul>
		</div>
	</div>
    <?php
	}
    return ob_get_clean();
}

if(function_exists('insert_shortcode')) { insert_shortcode('portfolio_slider', 'ro_portfolio_slider_func'); }
