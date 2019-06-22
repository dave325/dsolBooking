<?php
function ro_blog_special_func($atts, $content = null) {
     extract(shortcode_atts(array(
        'category' => '',
		'posts_per_page' => 3,
		'orderby' => 'none',
        'order' => 'none',
        'el_class' => '',
        'show_title' => 0,
        'show_excerpt' => 0,
        'excerpt_lenght' => 21,
        'excerpt_more' => '',
		'show_meta' => 0,
    ), $atts));
	
    $class = array();
    $class[] = 'ro-blog-special-wrapper clearfix';
    $class[] = $el_class;
	
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    
    $args = array(
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
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
        $args['tax_query'] = array(
                                array(
                                    'taxonomy' => 'category',
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
		<div class="ro-blog">
			<div class="row">
				<?php $count= 0; while ( $wp_query->have_posts() ) { $wp_query->the_post(); $count++; ?>
				
					<?php if($count== 2) { ?>
						<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 ro-active">
							<div class="ro-blog-item">
								<div class="ro-thumb">
									<?php if ( has_post_thumbnail() ) { the_post_thumbnail('slova-blog-special-large'); } ?>
								</div>
								<div class="ro-content">
									<?php if($show_title) { ?>
										<h4 class="ro-title ro-text-ellipsis"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
									<?php } ?>
									<?php if($show_excerpt) { ?>
										<div class="ro-excerpt"><?php echo ro_custom_excerpt((int)$excerpt_lenght, $excerpt_more); ?></div>
									<?php } ?>
									<?php if($show_meta) { ?>
									<div class="ro-meta">
										<div class="ro-author"><div class="ro-meta-btn"><i class="fa fa-pencil"></i><span><?php echo get_the_author(); ?></span></div></div>
										<div class="ro-comment"><div class="ro-meta-btn"><i class="fa fa-comments-o"></i><span><?php comments_number( '0 Comment', '1 Comment', '% Comments' ); ?></span></div></div>
										<div class="ro-publish"><div class="ro-meta-btn"><i class="fa fa-calendar"></i><span><?php echo get_the_date('M d, Y'); ?></span></div></div>
										<a href="<?php the_permalink(); ?>" class="ro-readmore pull-right"><span><?php _e('Read more', 'slova'); ?></span></a>
									</div>
									<?php } ?>
								</div>
								
							</div>
						</div>
					<?php } else { ?>
						<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
							<div class="ro-blog-item ro-small">
								<div class="ro-thumb">
									<?php if ( has_post_thumbnail() ) { the_post_thumbnail('slova-blog-special-small'); } ?>
									<div class="ro-info">
										<div class="ro-meta-btn"><i class="fa fa-calendar"></i><span><?php echo get_the_date('M d, Y'); ?></span></div>
										<div class="ro-readmore"><a href="<?php the_permalink(); ?>"><i class="fa fa-long-arrow-right"></i></a></div>
									</div>
								</div>
							</div>
						</div>
					<?php }	?>
				
				<?php } ?>
			</div>
		</div>
	</div>
    <?php
	}
    return ob_get_clean();
}

if(function_exists('insert_shortcode')) { insert_shortcode('blog_special', 'ro_blog_special_func'); }
