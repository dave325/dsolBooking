<?php

/**
 * Suppress "error - 0 - No summary was found for this file" on phpdoc generation
 *
 * @package WPDataProjects\Project
 */

namespace WPDataProjects\Project {

	use WPDataProjects\Parent_Child\WPDP_Child_List_Table;

	/**
	 * Class WPDP_Project_Page_List extends WPDP_Child_List_Table
	 *
	 * @see WPDP_Child_List_Table
	 *
	 * @author  Peter Schulz
	 * @since   2.0.0
	 */
	class WPDP_Project_Page_List extends WPDP_Child_List_Table {

		/**
		 * WPDP_Project_Page_List constructor
		 *
		 * Add specific column headers to list table
		 *
		 * @param array $args
		 */
		public function __construct( array $args = [] ) {
			// Add column labels.
			$args['column_headers'] = self::column_headers_labels();

			// Show action links in column page_name
			$this->first_display_column = 'page_id';

			parent::__construct( $args );
		}

		/**
		 * Overwrites method column_default to support static pages
		 *
		 * @param array  $item
		 * @param string $column_name
		 *
		 * @return mixed|string
		 */
		public function column_default( $item, $column_name ) {
			if (
				'static' === $item['page_type'] &&
				(
					'page_table_name' === $column_name ||
					'page_mode' === $column_name ||
					'page_allow_insert' === $column_name ||
					'page_allow_delete' === $column_name
				)
			) {
				return '';
			} else {
				if (
					'static' !== $item['page_type'] &&
					(
						'page_content' === $column_name
					)
				) {
					return '';
				} else {
					return parent::column_default( $item, $column_name );
				}
			}
		}

		/**
		 * Add action "show shortcode"
		 *
		 * @param array  $item
		 * @param string $column_name
		 * @param array  $actions
		 */
		protected function column_default_add_action( $item, $column_name, &$actions ) {
			$actions['shortcode'] = sprintf(
				'<a href="javascript:void(0)" 
                                    class="view"  
                                    onclick=\'prompt("%s", "[wpdadiehard project_id=\"%s\" page_id=\"%s\"]")\'>
                                    %s
                                </a>
                                ',
				__( 'Project Page Shortcode', 'wp-data-access' ),
				$item['project_id'],
				$item['page_id'],
				__( 'Show Shortcode', 'wp-data-access' )
			);
		}

		public static function column_headers_labels() {
			return [
				'project_id'        => __( 'Project ID', 'wp-data-access' ),
				'page_id'           => __( 'Page ID', 'wp-data-access' ),
				'page_name'         => __( 'Menu Name', 'wp-data-access' ),
				'add_to_menu'       => __( 'Add To Menu', 'wp-data-access' ),
				'page_type'         => __( 'Page Type', 'wp-data-access' ),
				'page_schema_name'  => __( 'Database', 'wp-data-access' ),
				'page_table_name'   => __( 'Table Name', 'wp-data-access' ),
				'page_setname'      => __( 'Options Set Name', 'wp-data-access' ),
				'page_mode'         => __( 'Mode', 'wp-data-access' ),
				'page_allow_insert' => __( 'Allow insert?', 'wp-data-access' ),
				'page_allow_delete' => __( 'Allow delete?', 'wp-data-access' ),
				'page_content'      => __( 'Post', 'wp-data-access' ),
				'page_title'        => __( 'Title', 'wp-data-access' ),
				'page_subtitle'     => __( 'Subtitle', 'wp-data-access' ),
				'page_role'         => __( 'Role', 'wp-data-access' ),
				'page_where'        => __( 'Default WHERE', 'wp-data-access' ),
				'page_sequence'     => __( 'Seq#', 'wp-data-access' ),
			];
		}

	}

}