<?php
function ro_blog_masonry_func($atts, $content = null) {
     extract(shortcode_atts(array(
        'category' => '',
		'posts_per_page' => -1,
		'show_pagination' => 0,
		'columns' =>  3,
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
    $class[] = 'ro-blog-wrapper clearfix';
    $class[] = $el_class;
	
	wp_enqueue_script('isotope.pkgd', URI_PATH . '/assets/js/isotope.pkgd.js',array('jquery'),'2.2.2');
	
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
		$class_columns = array();
		switch ($columns) {
			case 1:
				$class_columns[] = 'col-xs-12 col-sm-12 col-md-12 col-lg-12';
				break;
			case 2:
				$class_columns[] = 'col-xs-12 col-sm-6 col-md-6 col-lg-6';
				break;
			case 3:
				$class_columns[] = 'col-xs-12 col-sm-6 col-md-4 col-lg-4';
				break;
			default:
				$class_columns[] = 'col-xs-12 col-sm-6 col-md-4 col-lg-4';
				break;
		}
    ?>
	<div class="<?php echo esc_attr(implode(' ', $class)); ?>">
		<div class="ro-blog">
			<div class="row grid-masonry">
				<?php while ( $wp_query->have_posts() ) { $wp_query->the_post(); ?>
				<div class="grid-item <?php echo esc_attr(implode(' ', $class_columns)); ?>">
					<?php 
						$post_format = get_post_format();
						include 'tpl/entry'.$post_format.'.php'; 
					?>
				</div>
				<?php } ?>
			</div>
			<div style="clear: both;"></div>
			<?php if($show_pagination){ ?>
				<nav class="pagination ro-pagination" role="navigation">
					<?php
						$big = 999999999; // need an unlikely integer

						echo paginate_links( array(
							'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
							'format' => '?paged=%#%',
							'current' => max( 1, get_query_var('paged') ),
							'total' => $wp_query->max_num_pages,
							'prev_text' => __( 'Previous', 'slova' ),
							'next_text' => __( 'Next', 'slova' ),
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

if(function_exists('insert_shortcode')) { insert_shortcode('blog_masonry', 'ro_blog_masonry_func'); }
