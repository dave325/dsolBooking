<?php
function ro_team_func($atts, $content = null) {
     extract(shortcode_atts(array(
        'category' => '',
		'posts_per_page' => -1,
		'show_pagination' => 0,
		'columns' =>  4,
		'orderby' => 'none',
        'order' => 'none',
        'el_class' => '',
        'show_title' => 0,
        'show_position' => 0,
        'show_social' => 0,
        'show_excerpt' => 0,
        'readmore_text' => 'View details',
    ), $atts));
	
    $class = array();
    $class[] = 'ro-team-wrapper clearfix';
    $class[] = $el_class;
	
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    
    $args = array(
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        'orderby' => $orderby,
        'order' => $order,
        'post_type' => 'team',
        'post_status' => 'publish');
    if (isset($category) && $category != '') {
        $cats = explode(',', $category);
        $category = array();
        foreach ((array) $cats as $cat) :
        $category[] = trim($cat);
        endforeach;
        $args['tax_query'] = array(
                                array(
                                    'taxonomy' => 'team_category',
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
				$class_columns[] = 'col-xs-12 col-sm-6 col-md-6 col-lg-3';
				break;
			default:
				$class_columns[] = 'col-xs-12 col-sm-6 col-md-6 col-lg-3';
				break;
		}
    ?>
	<div class="<?php echo esc_attr(implode(' ', $class)); ?>">
		<div class="ro-team">
			<div class="row">
				<?php while ( $wp_query->have_posts() ) { $wp_query->the_post(); ?>
				<div class="<?php echo esc_attr(implode(' ', $class_columns)); ?>">
					<div class="ro-team-item">
						<div class="ro-thumb">
							<?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?>
							<div class="ro-content-overlay">
								<?php if($show_excerpt) { ?>
									<h5 class="ro-title"><?php esc_html_e('About', 'slova') ?></h5>
									<div class="ro-excerpt"><?php the_excerpt(); ?></div>
								<?php } ?>
								<?php if($readmore_text) { ?>
									<a class="ro-btn-small ro-view-details" href="<?php the_permalink(); ?>"><?php echo $readmore_text; ?></a>
								<?php } ?>
							</div>
						</div>
						<div class="ro-content">
							<?php if($show_title) { ?>
								<h5 class="ro-title"><?php the_title(); ?></h5>
							<?php } ?>
							<?php if($show_position) { ?>
								<?php $position = get_post_meta(get_the_ID(), 'tb_team_position', true); ?>
								<span class="ro-position"><?php echo $position; ?></span>
							<?php } ?>
							<?php
							if($show_social) {
								$social = array();
								$social_list = array('tb_team_facebook', 'tb_team_twitter', 'tb_team_tumblr', 'tb_team_youtube', 'tb_team_rss');
								foreach($social_list as $social_item) {
									$social_link = get_post_meta( get_the_ID(), $social_item, true );
									$social_icon = get_post_meta( get_the_ID(), $social_item.'_icon', true );
									if($social_link) {
										$social[] = '<li><a href="'.esc_url($social_link).'"><i class="'.esc_attr($social_icon).'"></i></a></li>';
									}
								}
								if ( !empty($social) ) {
									echo '<ul class="ro-social">'.implode(' ',$social).'</ul>';
								}
							}
							?>
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

if(function_exists('insert_shortcode')) { insert_shortcode('team', 'ro_team_func'); }
