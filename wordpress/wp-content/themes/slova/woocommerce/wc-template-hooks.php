<?php
/**
 * Content Wrappers
 *
 * @see woocommerce_output_content_wrapper()
 * @see woocommerce_output_content_wrapper_end()
 */
add_action( 'woocommerce_output_content_wrapper', 'woocommerce_output_content_wrapper', 10 );
add_action( 'woocommerce_output_content_wrapper_end', 'woocommerce_output_content_wrapper_end', 10 );
/**
 * Breadcrumbs
 *
 * @see woocommerce_breadcrumb()
 */
add_action( 'woocommerce_breadcrumb', 'woocommerce_breadcrumb', 20, 0 );
/**
 * Product Loop Items
 *
 * @see woocommerce_template_loop_add_to_cart()
 * @see woocommerce_template_loop_product_thumbnail()
 * @see woocommerce_template_loop_price()
 * @see woocommerce_template_loop_rating()
 */
add_action( 'woocommerce_template_loop_add_to_cart', 'woocommerce_template_loop_add_to_cart', 10 );
add_action( 'woocommerce_template_loop_product_thumbnail', 'woocommerce_template_loop_product_thumbnail', 10 );
add_action( 'woocommerce_template_loop_product_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_template_loop_price', 'woocommerce_template_loop_price', 10 );
add_action( 'woocommerce_template_loop_rating', 'woocommerce_template_loop_rating', 5 );

/**
 * Cart
 *
 * @see woocommerce_cross_sell_display()
 * @see woocommerce_cart_totals()
 * @see woocommerce_button_proceed_to_checkout()
 */
add_action( 'woocommerce_cross_sell_display', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_cart_totals', 'woocommerce_cart_totals', 10 );
add_action( 'woocommerce_button_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );

/**
 * Sale flashes
 *
 * @see woocommerce_show_product_loop_sale_flash()
 * @see woocommerce_show_product_sale_flash()
 */
add_action( 'woocommerce_show_product_loop_sale_flash', 'woocommerce_show_product_loop_sale_flash', 10 );
add_action( 'woocommerce_show_product_sale_flash', 'woocommerce_show_product_sale_flash', 10 );

/**
 * Product Summary Box
 *
 * @see woocommerce_template_single_title()
 * @see woocommerce_template_single_price()
 * @see woocommerce_template_single_excerpt()
 * @see woocommerce_template_single_meta()
 * @see woocommerce_template_single_sharing()
 */
add_action( 'woocommerce_template_single_title', 'woocommerce_template_single_title', 5 );
add_action( 'woocommerce_template_single_rating', 'woocommerce_template_single_rating', 10 );
add_action( 'woocommerce_template_single_price', 'woocommerce_template_single_price', 10 );
add_action( 'woocommerce_template_single_excerpt', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_template_single_meta', 'woocommerce_template_single_meta', 40 );
add_action( 'woocommerce_template_single_sharing', 'woocommerce_template_single_sharing', 50 );

/**
 * Product Add to cart
 *
 * @see woocommerce_template_single_add_to_cart()
 * @see woocommerce_simple_add_to_cart()
 * @see woocommerce_grouped_add_to_cart()
 * @see woocommerce_variable_add_to_cart()
 * @see woocommerce_external_add_to_cart()
 */
add_action( 'woocommerce_template_single_add_to_cart', 'woocommerce_template_single_add_to_cart', 30 );
add_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
add_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
add_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
add_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );
add_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
add_action( 'woocommerce_single_variation_add_to_cart_button', 'woocommerce_single_variation_add_to_cart_button', 20 );
