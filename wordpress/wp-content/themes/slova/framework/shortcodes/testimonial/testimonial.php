<?php
function ro_testimonial_func($atts, $content = null) {
     extract(shortcode_atts(array(
        'category' => '',
		'posts_per_page' => -1,
		'show_pagination' => 0,
		'columns' =>  4,
		'orderby' => 'none',
        'order' => 'none',
        'el_class' => '',
        'show_avatar' => 0,
        'show_excerpt' => 0,
        'excerpt_lenght' => 20,
        'excerpt_more' => '',
		'show_info' => 0,
    ), $atts));
	
    $class = array();
    $class[] = 'ro-testimonial-wrapper clearfix';
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
		$class_columns = array();
		switch ($columns) {
			case 1:
				$class_columns[] = 'col-xs-12 col-sm-12 col-md-12 col-lg-12';
				break;
			case 2:
				$class_columns[] = 'col-xs-12 col-sm-12 col-md-6 col-lg-6';
				break;
			case 3:
				$class_columns[] = 'col-xs-12 col-sm-12 col-md-4 col-lg-4';
				break;
			case 4:
				$class_columns[] = 'col-xs-12 col-sm-6 col-md-6 col-lg-3 no-padding';
				break;
			default:
				$class_columns[] = 'col-xs-12 col-sm-6 col-md-6 col-lg-3 no-padding';
				break;
		}
    ?>
	<div class="<?php echo esc_attr(implode(' ', $class)); ?>">
		<div class="ro-testimonial">
			<div class="row">
				<?php while ( $wp_query->have_posts() ) { $wp_query->the_post(); ?>
				<div class="<?php echo esc_attr(implode(' ', $class_columns)); ?>">
					<div class="ro-testimonial-item">
						<div class="ro-thumb">
							<?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?>
						</div>
						<div class="ro-content-overlay">
							<div class="ro-content">
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
							</div>
						</div>
						
					</div>
				</div>
				<?php } ?>
			</div>
			<div style="clear: both;"></div>
			<?php if($show_pagination){ ?>
				<nav class="pagination ro-pagination ?>" role="navigation">
					<?php
						$big = 999999999; // need an unlikely integer

						echo paginate_links( array(
							'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
							'format' => '?paged=%#%',
							'current' => max( 1, get_query_var('paged') ),
							'total' => $wp_query->max_num_pages,
							'prev_text' => __( '<i class="fa fa-angle-left"></i>', 'slova' ),
							'next_text' => __( '<i class="fa fa-angle-right"></i>', 'slova' ),
						) );
					?>
				</nav>
			<?php } ?>
		</div>
	</div>
    <?php
	}
    return ob_get_clean();
}

if(function_exists('insert_shortcode')) { insert_shortcode('testimonial', 'ro_testimonial_func'); }
