<?php get_header(); ?>
<?php
global $tb_options;
$tb_show_page_title = isset($tb_options['tb_post_show_page_title']) ? $tb_options['tb_post_show_page_title'] : 1;
$tb_show_page_breadcrumb = isset($tb_options['tb_post_show_page_breadcrumb']) ? $tb_options['tb_post_show_page_breadcrumb'] : 1;
$tb_post_show_post_nav = (int) isset($tb_options['tb_team_post_show_post_nav']) ?  $tb_options['tb_team_post_show_post_nav']: 1;
ro_theme_title_bar($tb_show_page_title, $tb_show_page_breadcrumb, $tb_post_show_post_nav);

$tb_post_show_post_tags = (int) isset($tb_options['tb_team_post_show_post_tags']) ? $tb_options['tb_team_post_show_post_tags'] : 1;
$tb_post_show_post_author = (int) isset($tb_options['tb_team_post_show_post_author']) ? $tb_options['tb_team_post_show_post_author'] : 1;
$tb_post_show_post_comment = (int) isset($tb_options['tb_team_post_show_post_comment']) ?  $tb_options['tb_team_post_show_post_comment']: 1;
?>
	<div class="main-content ro-blog-sub-article-container-3">
		<div class="container">
			<div class="row">
				<?php
				$tb_blog_layout = isset($tb_options['tb_team_post_layout']) ? $tb_options['tb_team_post_layout'] : '2cr';
				$sb_left = isset($tb_options['tb_team_left_sidebar']) ? $tb_options['tb_team_left_sidebar'] : 'Main Sidebar';
				$cl_sb_left = isset($tb_options['tb_team_post_left_sidebar_col']) ? $tb_options['tb_team_post_left_sidebar_col'] : 'col-xs-12 col-sm-4 col-md-4 col-lg-4';
				$cl_content = isset($tb_options['tb_team_post_content_col']) ? $tb_options['tb_team_post_content_col'] : 'col-xs-12 col-sm-8 col-md-8 col-lg-8';
				$sb_right = isset($tb_options['tb_team_right_sidebar']) ? $tb_options['tb_team_right_sidebar'] : 'Main Sidebar';
				$cl_sb_right = isset($tb_options['tb_team_post_right_siedebar_col']) ? $tb_options['tb_team_post_right_siedebar_col'] : 'col-xs-12 col-sm-4 col-md-4 col-lg-4';
				?>
				<!-- Start Left Sidebar -->
				<?php if ( $tb_blog_layout == '2cl' ) { ?>
					<div class="<?php echo esc_attr($cl_sb_left) ?> sidebar-left">
						<?php if (is_active_sidebar('tbtheme-left-sidebar')) { dynamic_sidebar($sb_left); } ?>
					</div>
				<?php } ?>
				<!-- End Left Sidebar -->
				<!-- Start Content -->
				<div class="<?php echo esc_attr($cl_content) ?> content tb-team">
					<?php
					while ( have_posts() ) : the_post();
						get_template_part( 'framework/templates/team/entry', get_post_format());
						
						if ( $tb_post_show_post_tags ) { the_tags('<div class="ro-blog-tag clearfix ro-uppercase"><span><h4>TAGS:</h4></span><span>', '</span><span>', '</span></div>'); }
		
						if ( $tb_post_show_post_author ) { echo ro_theme_author_render(); }
						// If comments are open or we have at least one comment, load up the comment template.
						if ( (comments_open() && $tb_post_show_post_comment) || (get_comments_number() && $tb_post_show_post_comment) ) comments_template();
					endwhile;
					?>
				</div>
				<!-- End Content -->
				<!-- Start Right Sidebar -->
				<?php if ( $tb_blog_layout == '2cr' ){ ?>
					<div class="<?php echo esc_attr($cl_sb_right) ?> sidebar-right">
						<?php if (is_active_sidebar('tbtheme-right-sidebar')) { dynamic_sidebar($sb_right); } ?>
					</div>
				<?php } ?>
				<!-- End Right Sidebar -->
			</div>
		</div>
	</div>
<?php get_footer(); ?>