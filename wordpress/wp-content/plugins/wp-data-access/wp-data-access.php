<?php
/**
 * Plugin Name:       WP Data Access
 * Plugin URI:        https://wpdataaccess.com/
 * Description:       WP Data Access helps you to manage your local and remote data and databases from the WordPress dashboard and to publish your local and remote data on your website.
 * Version:           3.0.0
 * Author:            Peter Schulz
 * Author URI:        https://www.linkedin.com/in/peterschulznl/
 * Text Domain:       wp-data-access
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 *
 * @package plugin
 * @author  Peter Schulz
 * @since   1.0.0
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

const DONATION_URL = 'https://www.paypal.me/kpsch';
const SETTINGS_URL = 'options-general.php?page=wpdataaccess';
const REVIEW_URL   = 'https://wordpress.org/support/plugin/wp-data-access/reviews/#new-post';
const STAR_IMG     = '<span class="dashicons dashicons-star-filled" style="color:#ffb900;height:18px;"></span>';
const DONATION_IMG = '<img style="vertical-align:bottom;height:18px;" src="https://www.paypal.com/en_US/i/btn/btn_donate_SM.gif">';

//function wpda_action_links( $links ) {
//	// Add donation link
//	$donation_url  = esc_url ( DONATION_URL );
//	$donation_link = "<a href=\"$donation_url\">Donate</a>";
//	array_unshift( $links, $donation_link );
//
//	// Add settings link
//	$settings_url  = esc_url ( get_admin_url() . SETTINGS_URL );
//	$settings_link = "<a href=\"$settings_url\">Settings</a>";
//	array_unshift( $links, $settings_link );
//
//	return $links;
//}
//add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wpda_action_links' );

function wpda_row_meta( $links, $file ) {
	if ( strpos( $file, plugin_basename(__FILE__) ) !== false ) {
		// Add settings link
		$settings_url  = esc_url( get_admin_url() . SETTINGS_URL );
		$settings_link = "<a href=\"$settings_url\">Settings</a>";
		array_push( $links, $settings_link );

		// Add review link
		$review_url  = esc_url( REVIEW_URL );
		$review_link = sprintf( "<a href=\"$review_url\">Leave a review %s%s%s%s%s</a>", STAR_IMG, STAR_IMG, STAR_IMG, STAR_IMG, STAR_IMG);
		array_push( $links, $review_link );

		// Add donation link
		$donation_url  = esc_url( DONATION_URL );
		$donation_link = sprintf( "<a href=\"$donation_url\">Thank you for supporting me %s</a>", DONATION_IMG );
		array_push( $links, $donation_link );
	}

	return $links;
}
add_filter( 'plugin_row_meta', 'wpda_row_meta', 10, 2 );

// Load WPDataAccess namespace.
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

/**
 * Activate plugin
 *
 * @author  Peter Schulz
 * @since   1.0.0
 */
function activate_wp_data_access() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-data-access-switch.php';
	WP_Data_Access_Switch::activate();
}

register_activation_hook( __FILE__, 'activate_wp_data_access' );

/**
 * Deactivate plugin
 *
 * @author  Peter Schulz
 * @since   1.0.0
 */
function deactivate_wp_data_access() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-data-access-switch.php';
	WP_Data_Access_Switch::deactivate();
}

register_deactivation_hook( __FILE__, 'deactivate_wp_data_access' );

/**
 * Check if database needs to be updated
 *
 * @author  Peter Schulz
 * @since   1.5.2
 */
function wpda_update_db_check() {
	if ( WPDataAccess\WPDA::OPTION_WPDA_VERSION[1] !== get_option( WPDataAccess\WPDA::OPTION_WPDA_VERSION[0] ) ) {
		activate_wp_data_access();
	}
}

add_action( 'plugins_loaded', 'wpda_update_db_check' );

require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-data-access.php';
/**
 * Start plugin
 *
 * @author  Peter Schulz
 * @since   1.0.0
 */
function run_wp_data_access() {
	$wpdataaccess = new WP_Data_Access();
	$wpdataaccess->run();
}

run_wp_data_access();
