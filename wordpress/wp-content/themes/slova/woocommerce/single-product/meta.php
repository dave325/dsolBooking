<?php
/**
 * Single Product Meta
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post, $product;

$cat_count = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
$tag_count = sizeof( get_the_terms( $post->ID, 'product_tag' ) );

?>
<div class="product_meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

		<span class="sku_wrapper"><?php _e( 'SKU:', 'slova' ); ?> <span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : __( 'N/A', 'slova' ); ?></span></span>

	<?php endif; ?>

	<?php //echo $product->get_categories( '', '<div class="posted_in">' . _n( '<h3>Category:</h3>', '<h3>Categories:</h3>', $cat_count, 'slova' ) . ' ', '</div>' ); ?>

	<?php echo wc_get_product_tag_list ($product->get_id() , '', '<div class="tagged_as">' . _n( '<h3>Tags:</h3>', '<h3>Tags:</h3>', $tag_count, 'slova' ) . ' ', '</div>' ); ?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>
	
	<div class="ro-share">
		<a href="javascript:void()"><i class="fa fa-share-alt"></i> <?php _e('Share', 'slova'); ?></a>
		<ul>
			<li><a href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>"><i class="fa fa-facebook"></i></a></li>
			<li><a href="https://twitter.com/home?status=<?php the_permalink(); ?>"><i class="fa fa-twitter"></i></a></li>
			<li><a href="https://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>"><i class="fa fa-pinterest"></i></a></li>
		</ul>
	</div>

</div>
