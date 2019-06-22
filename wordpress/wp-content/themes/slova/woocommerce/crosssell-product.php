<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<li>
	<article <?php post_class(); ?>>
		<div class="ro-product-item">
			<div class="ro-thumb">
				<?php 
					do_action( 'woocommerce_show_product_loop_sale_flash' );
					do_action( 'woocommerce_template_loop_product_thumbnail' ); 
				?>
				<div class="ro-readmore">
					<a href="<?php the_permalink(); ?>"><i class="fa fa-share"></i></a>
				</div>
			</div>
			<div class="ro-content">
				<h6 class="ro-text-ellipsis"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h6>
				<div class="ro-price">
					<?php do_action( 'woocommerce_template_loop_price' ); ?>
				</div>
				<div class="ro-rating">
					<?php do_action( 'woocommerce_template_loop_rating' ); ?>
				</div>
				<div class="ro-excerpt"><?php echo ro_custom_excerpt(20, '');//$post->post_excerpt; ?></div>
				<div class="ro-btns">
					<?php do_action( 'woocommerce_template_loop_add_to_cart' ); ?>
					<?php //post_favorite(); ?>
					<?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>
				</div>
			</div>
		</div>
	</article>
</li>
