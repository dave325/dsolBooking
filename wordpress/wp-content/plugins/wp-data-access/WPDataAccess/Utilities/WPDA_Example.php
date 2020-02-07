<?php

/**
 * Suppress "error - 0 - No summary was found for this file" on phpdoc generation
 *
 * @package WPDataAccess\Utilities
 */

namespace WPDataAccess\Utilities {

	/**
	 * Class WPDA_Example
	 *
	 * Show downloadable example code.
	 *
	 * @author  Peter Schulz
	 * @since   1.0.0
	 */
	class WPDA_Example {

		/**
		 * Send example file
		 *
		 * @since   1.0.0
		 */
		public function get_example() {

			if ( isset( $_REQUEST['filename'] ) ) {
				$filename = sanitize_text_field( wp_unslash( $_REQUEST['filename'] ) ); // input var okay.
				if ( 'wpda-test' === $filename ) {
					$this->send_file( $filename );
				} else {
					wp_die( __( 'ERROR: Wrong arguments', 'wp-data-access' ) );
				}
			} else {
				wp_die( __( 'ERROR: Wrong arguments', 'wp-data-access' ) );
			}

		}

		/**
		 * Send content of example file
		 *
		 * @param string $example_name Name of example file without file extention.
		 *
		 * @since   1.0.0
		 *
		 */
		protected function send_file( $example_name ) {

			$example_file_name   = plugin_dir_path( dirname( __FILE__ ) ) . '../tutorials/' . $example_name . '.php.txt';
			$example_file_handle = fopen( $example_file_name, 'r' );
			if ( $example_file_handle ) {
				// Read file content and close handle.
				$example_file_content = fread( $example_file_handle, filesize( $example_file_name ) );
				fclose( $example_file_handle );

				// Send file content.
				header( 'Content-type: text/plain; charset=utf-8' );
				header( "Content-Disposition: attachment; filename=$example_name.php.txt" );
				header( 'Pragma: no-cache' );
				header( 'Expires: 0' );
				echo $example_file_content;
			}

		}

	}

}
