<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' ); ?>
	<?php
	global $tb_options;
	$tb_show_page_title = isset($tb_options['tb_post_show_page_title']) ? $tb_options['tb_post_show_page_title'] : 1;
	$tb_show_page_breadcrumb = isset($tb_options['tb_post_show_page_breadcrumb']) ? $tb_options['tb_post_show_page_breadcrumb'] : 1;
	ro_theme_title_bar($tb_show_page_title, $tb_show_page_breadcrumb);
	?>
	<div class="container">
		<div class="row">
			<div class="col-md-8">
			<?php
				/**
				 * woocommerce_before_main_content hook
				 *
				 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
				 * @hooked woocommerce_breadcrumb - 20
				 */
				//do_action( 'woocommerce_output_content_wrapper' );
			?>

				<?php while ( have_posts() ) : the_post(); ?>
					
					<?php wc_get_template_part( 'content', 'single-product' ); ?>
						
				<?php endwhile; // end of the loop. ?>

			<?php
				/**
				 * woocommerce_after_main_content hook
				 *
				 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
				 */
				//do_action( 'woocommerce_output_content_wrapper_end' );
			?>
			</div>

			<div class="col-md-4">
				<div class="sidebar-right">
					<?php if(is_active_sidebar('tbtheme-detail-product-sidebar')) dynamic_sidebar( 'tbtheme-detail-product-sidebar' ); ?>
				</div>
			</div>
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

<?php get_footer( 'shop' ); ?>
