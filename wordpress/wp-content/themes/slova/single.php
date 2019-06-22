<?php get_header(); ?>
<?php
global $tb_options;
$tb_show_page_title = isset($tb_options['tb_post_show_page_title']) ? $tb_options['tb_post_show_page_title'] : 1;
$tb_show_page_breadcrumb = isset($tb_options['tb_post_show_page_breadcrumb']) ? $tb_options['tb_post_show_page_breadcrumb'] : 1;
$tb_post_show_post_nav = (int) isset($tb_options['tb_post_show_post_nav']) ?  $tb_options['tb_post_show_post_nav']: 1;
ro_theme_title_bar($tb_show_page_title, $tb_show_page_breadcrumb, $tb_post_show_post_nav);

$tb_post_show_post_tags = (int) isset($tb_options['tb_post_show_post_tags']) ? $tb_options['tb_post_show_post_tags'] : 1;
$tb_post_show_post_author = (int) isset($tb_options['tb_post_show_post_author']) ? $tb_options['tb_post_show_post_author'] : 1;
$tb_post_show_post_comment = (int) isset($tb_options['tb_post_show_post_comment']) ?  $tb_options['tb_post_show_post_comment']: 1;
?>
	<div class="main-content ro-blog-sub-article-container-3">
		<div class="container">
			<div class="row">
				<?php
				$tb_blog_layout = isset($tb_options['tb_post_layout']) ? $tb_options['tb_post_layout'] : '2cr';
				$sb_left = isset($tb_options['tb_post_left_sidebar']) ? $tb_options['tb_post_left_sidebar'] : 'Main Sidebar';
				$cl_sb_left = isset($tb_options['tb_post_left_sidebar_col']) ? $tb_options['tb_post_left_sidebar_col'] : 'col-xs-12 col-sm-12 col-md-4 col-lg-4';
				$cl_content = isset($tb_options['tb_post_content_col']) ? $tb_options['tb_post_content_col'] : ( is_active_sidebar('tbtheme-main-sidebar') ? 'col-xs-12 col-sm-12 col-md-8 col-lg-8' : 'col-xs-12 col-sm-12 col-md-12 col-lg-12' );
				if ( !is_active_sidebar('tbtheme-main-sidebar') && !is_active_sidebar('tbtheme-left-sidebar') && !is_active_sidebar('tbtheme-left-sidebar') ) {
					$cl_content = 'col-xs-12 col-sm-12 col-md-12 col-lg-12';
				}
				$sb_right = isset($tb_options['tb_post_right_sidebar']) ? $tb_options['tb_post_right_sidebar'] : 'Main Sidebar';
				$cl_sb_right = isset($tb_options['tb_post_right_siedebar_col']) ? $tb_options['tb_post_right_siedebar_col'] : 'col-xs-12 col-sm-12 col-md-4 col-lg-4';
				?>
				<!-- Start Left Sidebar -->
				<?php if ( $tb_blog_layout == '2cl' ) { ?>
					<div class="<?php echo esc_attr($cl_sb_left) ?> sidebar-left">
						<?php if (is_active_sidebar('tbtheme-left-sidebar') || is_active_sidebar('tbtheme-main-sidebar')) { dynamic_sidebar($sb_left); } ?>
					</div>
				<?php } ?>
				<!-- End Left Sidebar -->
				<!-- Start Content -->
				<div class="<?php echo esc_attr($cl_content) ?> content ro-blog">
					<?php while ( have_posts() ) { the_post(); ?>
						<div class="ro-blog-item">
							<?php
								get_template_part( 'framework/templates/blog/single/entry', get_post_format());
								?>
								<div class="ro-blog-meta">
									<div class="row">
										<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
											<?php if ( $tb_post_show_post_tags ) { the_tags('<div class="ro-blog-tag clearfix"><h5>TAGS:</h5><span>', '</span><span>', '</span></div>'); } ?>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
											<div class="ro-share">
												<a href="javascript:void()"><i class="fa fa-share-alt"></i> <?php _e('Share', 'slova'); ?></a>
												<ul>
													<li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>"><i class="fa fa-facebook"></i></a></li>
													<li><a href="https://twitter.com/home?status=<?php the_permalink(); ?>"><i class="fa fa-twitter"></i></a></li>
													<li><a href="https://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>"><i class="fa fa-pinterest"></i></a></li>
												</ul>
												<div class="ro-comment-count"><span><?php comments_number( '0 comment', '1 comment', '% comments' ); ?></span><i class="fa fa-comments"></i></div>
												<?php post_favorite(); ?>
											</div>
										</div>
									</div>
								</div>
								<?php
								if ( $tb_post_show_post_author ) { echo ro_theme_author_render(); }
								
								echo ro_theme_post_nav();
							?>
						</div>
						<div class="ro-blog-comment">
							<?php
								// If comments are open or we have at least one comment, load up the comment template.
								if ( (comments_open() && $tb_post_show_post_comment) || (get_comments_number() && $tb_post_show_post_comment) ) comments_template();
							?>
						</div>
					<?php } ?>
				</div>
				<!-- End Content -->
				<!-- Start Right Sidebar -->
				<?php if ( $tb_blog_layout == '2cr' ) { ?>
					<div class="<?php echo esc_attr($cl_sb_right) ?> sidebar-right">
						<?php if (is_active_sidebar('tbtheme-right-sidebar') || is_active_sidebar('tbtheme-main-sidebar')) { dynamic_sidebar($sb_right); } ?>
					</div>
				<?php } ?>
				<!-- End Right Sidebar -->
			</div>
		</div>
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