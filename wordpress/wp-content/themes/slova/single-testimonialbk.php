<?php get_header(); ?>
<?php
global $tb_options;
$tb_show_page_title = isset($tb_options['tb_post_show_page_title']) ? $tb_options['tb_post_show_page_title'] : 1;
$tb_show_page_breadcrumb = isset($tb_options['tb_post_show_page_breadcrumb']) ? $tb_options['tb_post_show_page_breadcrumb'] : 1;
$tb_post_show_post_nav = (int) isset($tb_options['tb_post_show_post_nav']) ?  $tb_options['tb_post_show_post_nav']: 1;
ro_theme_title_bar($tb_show_page_title, $tb_show_page_breadcrumb, $tb_post_show_post_nav);

?>
	<div class="main-content">
		<div class="container">
			<div class="row">
				<!-- Start Content -->
				<div class="col-md-8 ro-content">
					<?php
					while ( have_posts() ) { the_post(); $post_id = get_the_ID();
						?>
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<div class="ro-sub-article">
								<?php if ( has_post_thumbnail() ) the_post_thumbnail('full'); ?>
								<h4 class="ro-uppercase"><?php the_title(); ?></h4>
								<div class="ro-meta">
									<span><?php _e( '<strong>Age:</strong> ', 'slova' ); echo get_post_meta( get_the_ID(), 'tb_testimonial_age', true ); ?></span>
									<span><?php _e( '<strong>Company:</strong> ', 'slova' ); echo get_post_meta( get_the_ID(), 'tb_testimonial_company', true ); ?></span>
								</div>
								<?php the_content(); ?>
							</div>
						</article>
						<?php
					}
					?>
				</div>
				<!-- End Content -->
				<!-- Start Right Sidebar -->
				<div class="col-md-4 sidebar-right">
					<h6 class="ro-related-title"><?php _e('TESTIMONIAL lIST', 'slova') ?></h6>
					<div class="ro-testimonial-related">
						<?php 
							// arguments
							$args = array(
							'post_type' => 'testimonial',
							'post_status' => 'publish',
							'posts_per_page' => 4,
							'post__not_in' => array ($post_id),
							);
							$related_items = new WP_Query( $args );
							// loop over query
							if ($related_items->have_posts()) {
								echo '<ul>';
									while ( $related_items->have_posts() ) { $related_items->the_post();
										?>
											<li class="clearfix">
												<?php if(has_post_thumbnail()) the_post_thumbnail('thumbnail'); ?>
												<div class="ro-content">
													<h6><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
													<span><?php echo get_post_meta( get_the_ID(), 'tb_testimonial_age', true ).' - '.get_post_meta( get_the_ID(), 'tb_testimonial_company', true ); ?></span>
												</div>
											</li>
										<?php
									}
								echo '</ul>';
							}
							// Reset Post Data
							wp_reset_postdata();
						?>
					</div>
				</div>
				<!-- End Right Sidebar -->
			</div>
		</div>
	</div>
<?php get_footer(); ?>