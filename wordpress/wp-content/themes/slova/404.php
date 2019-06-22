<?php
/*
Template Name: 404 Template
*/
?>
<?php get_header(); ?>
<?php
global $tb_options;
$tb_show_page_title = isset($tb_options['tb_page_show_page_title']) ? $tb_options['tb_page_show_page_title'] : 1;
$tb_show_page_breadcrumb = isset($tb_options['tb_page_show_page_breadcrumb']) ? $tb_options['tb_page_show_page_breadcrumb'] : 1;
ro_theme_title_bar($tb_show_page_title, $tb_show_page_breadcrumb);
?>
	<div class="main-content">
		<div class="error404-wrap">
			<img src="<?php echo get_template_directory_uri(); ?>/assets/images/404-page.jpg" alt="">
			<a href="<?php echo esc_url( home_url( '/'  ) );?>"><?php _e('Please head to the homepage','slova');?></a>
		</div>
	</div>
<?php get_footer(); ?>