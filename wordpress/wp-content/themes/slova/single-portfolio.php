<?php get_header(); ?>
<?php
global $tb_options;
$tb_show_page_title = isset($tb_options['tb_post_show_page_title']) ? $tb_options['tb_post_show_page_title'] : 1;
$tb_show_page_breadcrumb = isset($tb_options['tb_post_show_page_breadcrumb']) ? $tb_options['tb_post_show_page_breadcrumb'] : 1;
$tb_post_show_post_nav = (int) isset($tb_options['tb_recipes_post_show_post_nav']) ?  $tb_options['tb_recipes_post_show_post_nav']: 1;
ro_theme_title_bar($tb_show_page_title, $tb_show_page_breadcrumb, $tb_post_show_post_nav);

?>
	<div class="main-content ro-portfolio">
		<div class="container">
			<div class="row">
				<!-- Start Content -->
				<div class="col-md-12">
					<?php while ( have_posts() ) { the_post(); $post_id = get_the_ID(); ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<div class="ro-portfolio-item ro-pb-90">
								<div class="ro-header">
									<?php echo ro_theme_post_portfolio_nav(); ?>
									<h3 class="ro-title"><span><?php the_title(); ?></span></h3>
									<span class="ro-categories"><?php the_terms(get_the_ID(), 'portfolio_category', '' , ', ' ); ?></span>
								</div>
								<div class="ro-thumb">
									<?php if(has_post_thumbnail()) the_post_thumbnail(); ?>
								</div>
								<div class="ro-content">
									<div class="row">
										<div class="col-md-8">
											<h4 class="ro-sub-title"><?php _e('DESCRIPTION', 'slova'); ?></h4>
											<?php the_content(); ?>
										</div>
										<div class="col-md-4">
											<h4 class="ro-sub-title"><?php _e('SUMMARY', 'slova'); ?></h4>
											<ul class="ro-info">
												<li><span><?php _e('Create: ', 'slova'); ?></span><?php echo get_post_meta(get_the_ID(), 'tb_portfolio_create', true); ?></li>
												<li><span><?php _e('Client: ', 'slova'); ?></span><?php echo get_post_meta(get_the_ID(), 'tb_portfolio_client', true); ?></li>
												<li><span><?php _e('Skill: ', 'slova'); ?></span><?php echo get_post_meta(get_the_ID(), 'tb_portfolio_skill', true); ?></li>
												<li><span><?php _e('Date: ', 'slova'); ?></span><?php echo get_post_meta(get_the_ID(), 'tb_portfolio_date', true); ?></li>
												<li><span><?php _e('Socials: ', 'slova'); ?></span><?php echo get_post_meta(get_the_ID(), 'tb_portfolio_share', true); ?></li>
											</ul>
										</div>
									</div>
								</div>
							</div>
							
						</article>
					<?php } ?>
					
				</div>
				<!-- End Content -->
			</div>
		</div>
		<!-- Related -->
		<div class="ro-portfolio-related ro-pt-90 ro-pb-90">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<?php
							echo do_shortcode('[icon_box icon="fa fa-file-text"]');
							echo do_shortcode('[heading text="RELATED WORKS" sub_text="Mirum est notare quam littera gothica, quam nunc putamus parum claram"]');
						?>
					
						<?php 
							// get the custom post type's taxonomy terms
							$custom_taxterms = wp_get_object_terms( $post_id, 'portfolio_category', array('fields' => 'ids') );
							
							// arguments
							$args = array(
							'post_type' => 'portfolio',
							'post_status' => 'publish',
							'posts_per_page' => 3,
							'tax_query' => array(
								array(
									'taxonomy' => 'portfolio_category',
									'field' => 'id',
									'terms' => $custom_taxterms
								)
							),
							'post__not_in' => array ($post_id),
							);
							$related_items = new WP_Query( $args );
							// loop over query
							if ($related_items->have_posts()) {
								echo '<div class="row">';
									while ( $related_items->have_posts() ) { $related_items->the_post();
										?>
											<div class="col-md-4">
												<div class="ro-portfolio-item">
													<div class="ro-thumb">
														<?php if ( has_post_thumbnail() ) { the_post_thumbnail(); } ?>
														<div class="ro-readmore">
															<a href="<?php the_permalink(); ?>"><i class="fa fa-chevron-right"></i></a>
														</div>
													</div>
													<div class="ro-content">
														<h5 class="ro-title"><?php the_title(); ?></h5>
														<span class="ro-categories"><?php the_terms(get_the_ID(), 'portfolio_category', '' , ', ' ); ?></span>
														<?php post_favorite(); ?>
													</div>
												</div>
											</div>
										<?php
									}
								echo '</div>';
							}
							// Reset Post Data
							wp_reset_postdata();
						?>
					</div>
				</div>
			</div>
		</div>
		<!-- Related -->
		<div class="ro-client-section ro-pt-90 ro-pb-90">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<?php if (is_active_sidebar('tbtheme-after-content-post')) { dynamic_sidebar('tbtheme-after-content-post'); } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php get_footer(); ?>