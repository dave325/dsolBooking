<?php

class RO_Widget_Mini_Cart extends WC_Widget {

	/**
	 * Constructor
	 */
	function __construct() {
		$this->widget_cssclass    = 'woocommerce ro_widget_mini_cart';
		$this->widget_description = __( "Display the user's Cart in the sidebar.", 'slova' );
		$this->widget_id          = 'ro_widget_mini_cart';
		$this->widget_name        = __( 'Mini Cart', 'slova' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => __( 'Cart', 'slova' ),
				'label' => __( 'Title', 'slova' )
			),
			'hide_if_empty' => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Hide if cart is empty', 'slova' )
			)
		);

		parent::__construct();
	}

	/**
	 * widget function.
	 *
	 * @see WP_Widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ) {
		if ( apply_filters( 'woocommerce_widget_cart_is_hidden', is_cart() || is_checkout() ) ) {
			return;
		}

		$hide_if_empty = empty( $instance['hide_if_empty'] ) ? 0 : 1;

		$this->widget_start( $args, $instance );
		
		if ( $hide_if_empty ) {
			echo '<div class="hide_cart_widget_if_empty">';
		}
		
		echo '<a href="javascript:void(0)"><i class="icon icon-basket"></i><span class="cart_total" ></span></a>';
		
		// Insert cart widget placeholder - code in woocommerce.js will update this on page load
		echo '<div class="ro-cart-content"><div class="ro-statust">You have <span class="cart_total" ></span> item(s) in your shopping bag</div><div class="widget_shopping_cart_content"></div></div>';

		if ( $hide_if_empty ) {
			echo '</div>';
		}

		$this->widget_end( $args );
	}
}
add_filter('add_to_cart_fragments', 'woocommerce_icon_add_to_cart_fragment');
if(!function_exists('woocommerce_icon_add_to_cart_fragment')){
	function woocommerce_icon_add_to_cart_fragment( $fragments ) {
		global $woocommerce;
		ob_start();
		?>
		<span class="cart_total"><?php echo $woocommerce->cart->cart_contents_count; ?></span>
		<?php
		$fragments['span.cart_total'] = ob_get_clean();
		return $fragments;
	}
}

/**
 * Class RO_Widget_Mini_Cart
 */
function register_ro_widget_mini_cart() {
    register_widget('RO_Widget_Mini_Cart');
}
add_action('widgets_init', 'register_ro_widget_mini_cart');
