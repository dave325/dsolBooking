<?php

/**
 * Suppress "error - 0 - No summary was found for this file" on phpdoc generation
 *
 * @package WPDataAccess\Utilities
 */

namespace WPDataAccess\Plugin_Table_Models {

	/**
	 * Class WPDP_Page_Model
	 *
	 * Model for plugin table 'wpda_project_page'
	 *
	 * @author  Peter Schulz
	 * @since   2.6.0
	 */
	class WPDP_Page_Model extends WPDA_Plugin_Table_Base_Model {

		const BASE_TABLE_NAME = 'wpda_project_page';

		/**
		 * Method overwritten for different table name handling
		 *
		 * @return string Table name
		 */
		public static function get_base_table_name() {
			static::check_base_table_name();

			global $wpdb;
			return $wpdb->prefix . static::BASE_TABLE_NAME;
		}

	}

}