<?php get_header(); ?>
<?php
global $tb_options;
$tb_show_page_title = isset($tb_options['tb_post_show_page_title']) ? $tb_options['tb_post_show_page_title'] : 1;
$tb_show_page_breadcrumb = isset($tb_options['tb_post_show_page_breadcrumb']) ? $tb_options['tb_post_show_page_breadcrumb'] : 1;
ro_theme_title_bar($tb_show_page_title, $tb_show_page_breadcrumb);
?>
	<div class="main-content">
		<div class="container">
			<div class="row">
				<?php
				$tb_blog_layout = isset($tb_options['tb_blog_layout']) ? $tb_options['tb_blog_layout'] : '2cr';
				$sb_left = isset($tb_options['tb_blog_left_sidebar']) ? $tb_options['tb_blog_left_sidebar'] : 'Main Sidebar';
				$cl_sb_left = isset($tb_options['tb_blog_left_sidebar_col']) ? $tb_options['tb_blog_left_sidebar_col'] : 'col-xs-12 col-sm-4 col-md-4 col-lg-4';
				$cl_content = isset($tb_options['tb_blog_content_col']) ? $tb_options['tb_blog_content_col'] : ( is_active_sidebar('tbtheme-main-sidebar') ? 'col-xs-12 col-sm-8 col-md-8 col-lg-8' : 'col-xs-12 col-sm-12 col-md-12 col-lg-12' );
				if ( !is_active_sidebar('tbtheme-main-sidebar') && !is_active_sidebar('tbtheme-left-sidebar') && !is_active_sidebar('tbtheme-left-sidebar') ) {
					$cl_content = 'col-xs-12 col-sm-12 col-md-12 col-lg-12';
				}
				$sb_right = isset($tb_options['tb_blog_right_sidebar']) ? $tb_options['tb_blog_right_sidebar'] : 'Main Sidebar';
				$cl_sb_right = isset($tb_options['tb_blog_right_siedebar_col']) ? $tb_options['tb_blog_right_siedebar_col'] : 'col-xs-12 col-sm-4 col-md-4 col-lg-4';
				?>
				<!-- Start Left Sidebar -->
				<?php if ( $tb_blog_layout == '2cl' ) { ?>
					<div class="<?php echo esc_attr($cl_sb_left) ?> sidebar-left">
						<?php if (is_active_sidebar('tbtheme-left-sidebar') || is_active_sidebar('tbtheme-main-sidebar')) { dynamic_sidebar($sb_left); } ?>
					</div>
				<?php } ?>
				<!-- End Left Sidebar -->
				<!-- Start Content -->
				<div class="<?php echo esc_attr($cl_content) ?> content ro-blog-wrapper">
					<?php
					if( have_posts() ) {
						while ( have_posts() ) : the_post();
							get_template_part( 'framework/templates/blog/entry', get_post_format());
						endwhile;
						
						ro_theme_paging_nav();
					}else{
						get_template_part( 'framework/templates/entry', 'none');
					}
					?>
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
	</div>
<?php get_footer(); ?>