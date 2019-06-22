<?php
function ro_blog_special_carousel_func($atts, $content = null) {
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
    $class[] = 'ro-blog-special-carousel-wrapper clearfix';
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
		<div id="ro-blog-special-carousel" class="carousel slide" data-ride="carousel">
			<div class="carousel-inner" role="listbox">
				<?php $count = 0; while ( $wp_query->have_posts() ) { $wp_query->the_post(); $count++; ?>
					<?php
						if($count == 1) $prev_post_id = get_the_ID();
						if($count == 3) $next_post_id = get_the_ID();
						
						$active_class = '';
						if($count == 2) $active_class = 'active';
						$data_date = get_the_date('M d, Y');
						
						$thumb_id = get_post_thumbnail_id();
						$thumb_url = wp_get_attachment_image_src($thumb_id,'slova-blog-special-small', true);
						$data_thumb = $thumb_url[0];
						
						$attr_slide = array( "date" => $data_date, "thumb" => $data_thumb );
					?>
					<div class="item ro-blog-item <?php echo esc_attr($active_class); ?>" data-slide="<?php echo esc_attr(json_encode( $attr_slide )); ?>">
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
				<?php } ?>
			</div>

			<!-- Controls -->
			<a class="left carousel-control" href="#ro-blog-special-carousel" role="button" data-slide="prev">
				<div class="ro-blog-item">
					<div class="ro-arrow-left"><span><i class="fa fa-long-arrow-left"></i></span></div>
					<?php 
						$prev_post_img = wp_get_attachment_image_src( get_post_thumbnail_id($prev_post_id), 'slova-blog-special-small');
						echo '<img src="'.esc_url($prev_post_img[0]).'" alt="">';
						//echo get_the_post_thumbnail ( $prev_post_id, 'slova-blog-special-small', true );
						echo '<div class="ro-meta-btn"><i class="fa fa-calendar"></i><span>'.get_the_date( 'M d, Y', $prev_post_id ).'</span></div>'; 
					?>
				</div>
			</a>
			<a class="right carousel-control" href="#ro-blog-special-carousel" role="button" data-slide="next">
				<div class="ro-blog-item">
					<div class="ro-arrow-right"><span><i class="fa fa-long-arrow-right"></i></span></div>
					<?php 
						$next_post_img = wp_get_attachment_image_src( get_post_thumbnail_id($next_post_id), 'slova-blog-special-small');
						echo '<img src="'.esc_url($next_post_img[0]).'" alt="">';
						//echo get_the_post_thumbnail ( $next_post_id, 'slova-blog-special-small', true );
						echo '<div class="ro-meta-btn"><i class="fa fa-calendar"></i><span>'.get_the_date( 'M d, Y', $next_post_id ).'</span></div>'; 
						?>
				</div>
			</a>
		</div>
	</div>
    <?php
	}
    return ob_get_clean();
}

if(function_exists('insert_shortcode')) { insert_shortcode('blog_special_carousel', 'ro_blog_special_carousel_func'); }
