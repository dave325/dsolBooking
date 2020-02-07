<?PHP

/**
 * Suppress "error - 0 - No summary was found for this file" on phpdoc generation
 *
 * @package WPDataAccess\Utilities
 */

namespace WPDataAccess\Utilities {

	use WPDataAccess\Data_Dictionary\WPDA_Dictionary_Exist;
	use WPDataAccess\Plugin_Table_Models\WPDA_Logging_Model;
	use WPDataAccess\Plugin_Table_Models\WPDA_Media_Model;
	use WPDataAccess\Plugin_Table_Models\WPDA_Publisher_Model;
	use WPDataAccess\Plugin_Table_Models\WPDA_Design_Table_Model;
	use WPDataAccess\Plugin_Table_Models\WPDA_Table_Settings_Model;
	use WPDataAccess\Plugin_Table_Models\WPDA_User_Menus_Model;
	use WPDataAccess\Plugin_Table_Models\WPDP_Page_Model;
	use WPDataAccess\Plugin_Table_Models\WPDP_Project_Model;
	use WPDataAccess\Plugin_Table_Models\WPDP_Project_Design_Table_Model;
	use WPDataAccess\WPDA;

	/**
	 * Class WPDA_Repository
	 *
	 * Recreate repository objects.
	 *
	 * @author  Peter Schulz
	 * @since   1.0.0
	 */
	class WPDA_Repository {

		const CREATE_TABLE = [
			'wpda_logging'        => [
				'create_table_logging.sql'
			],
			'wpda_menus'          => [
				'create_table_menus.sql',
			],
			'wpda_table_settings' => [
				'create_table_table_settings.sql',
			],
			'wpda_table_design'   => [
				'create_table_table_design.sql',
				'create_table_table_design_alter1.sql',
				'create_table_table_design_alter3.sql',
				'create_table_table_design_alter3.sql'
			],
			'wpda_publisher'      => [
				'create_table_publisher.sql'
			],
			'wpda_media'          => [
				'create_table_media.sql'
			],
			'wpda_project'        => [
				'create_table_project.sql'
			],
			'wpda_project_page'   => [
				'create_table_project_page.sql'
			],
			'wpda_project_table'  => [
				'create_table_project_table.sql'
			]
		];

		const DROP_TABLE = [
			'wpda_logging'        => [
				'drop_table_logging.sql'
			],
			'wpda_menus'          => [
				'drop_table_menus.sql'
			],
			'wpda_table_settings' => [
				'drop_table_table_settings.sql'
			],
			'wpda_table_design'   => [
				'drop_table_table_design.sql'
			],
			'wpda_publisher'      => [
				'drop_table_publisher.sql'
			],
			'wpda_media'          => [
				'drop_table_media.sql'
			],
			'wpda_project'        => [
				'drop_table_project.sql'
			],
			'wpda_project_page'   => [
				'drop_table_project_page.sql'
			],
			'wpda_project_table'  => [
				'drop_table_project_table.sql'
			]
		];

		protected $sql_repository_dir = '';

		public function __construct() {
			$this->sql_repository_dir = plugin_dir_path( dirname( __FILE__ ) ) . '../admin/repository/';
		}

		/**
		 * Recreate repository (save as much data as possible)
		 *
		 * @since   2.0.11
		 */
		public function recreate() {
			global $wpdb;

			$suppress = $wpdb->suppress_errors( true );

			foreach ( static::CREATE_TABLE as $key => $value ) {
				$table_name   = $wpdb->prefix . $key;
				$create_table = true;
				$bck_postfix  = '_BACKUP_' . date( 'YmdHis' );

				// Check if table exists
				$table_exists = new WPDA_Dictionary_Exist( $wpdb->dbname, $table_name );

				$bck_table_name = null;
				$same_cols      = null;

				if ( $table_exists->table_exists( false ) ) {
					// Create backup table
					$bck_table_name   = $wpdb->prefix . $key . $bck_postfix;
					$sql_create_table = "create table $bck_table_name as select * from $table_name";
					$wpdb->query( $sql_create_table );

					// Create new table just to check for changes
					// We only need to process the first script as this creates the table
					if ( $this->run_script( $value[0], '_new' ) ) {
						// Check structure old table
						$sql_check_table =
							"select column_name " .
							"from information_schema.columns " .
							"where table_schema = '{$wpdb->dbname}' " .
							"  and table_name   = '{$table_name}' ";
						$wpdb->get_results( $sql_check_table, 'ARRAY_A' );
						$nocols_old_table = $wpdb->num_rows;

						// Check structure new table
						$sql_check_table =
							"select column_name " .
							"from information_schema.columns " .
							"where table_schema = '{$wpdb->dbname}' " .
							"  and table_name   = '{$table_name}_new' ";
						$wpdb->get_results( $sql_check_table, 'ARRAY_A' );
						$nocols_new_table = $wpdb->num_rows;

						// Check if columns old and new are the same
						$sql_check_table =
							"select c1.column_name AS column_name " .
							"from information_schema.columns c1 " .
							"where c1.table_schema = '{$wpdb->dbname}' " .
							"  and c1.table_name   = '$table_name' " .
							"  and c1.column_name in ( " .
							"		select c2.column_name " .
							"		from   information_schema.columns c2 " .
							"		where  c2.table_schema = '{$wpdb->dbname}' " .
							"		  and  c2.table_name   = '{$table_name}_new' " .
							"	 )";
						$same_cols       = $wpdb->get_results( $sql_check_table, 'ARRAY_A' );
						$nocols_both     = $wpdb->num_rows;

						// Repository tables already available with right structure, create table not necessary
						$create_table = $nocols_new_table !== $nocols_old_table || $nocols_new_table !== $nocols_both;

						// Drop check table
						$this->run_script( static::DROP_TABLE[ $key ][0], '_new' );
					}
				}

				if ( $create_table ) {
					// Drop table
					$this->run_script( static::DROP_TABLE[ $key ][0], '' );

					// Create table
					foreach ( $value as $sql_file ) {
						$this->run_script( $sql_file );
					}

					// Restore data
					if ( null !== $same_cols ) {
						$selected_columns = '';
						foreach ( $same_cols as $same_col ) {
							$selected_columns .= $same_col['column_name'] . ',';
						}
						$selected_columns = substr( $selected_columns, 0, strlen( $selected_columns ) - 1 );
						$sql_restore      =
							"insert into $table_name ($selected_columns) " .
							"select $selected_columns from $bck_table_name";
						$wpdb->query( $sql_restore );
					}
				}

				if ( 'on' !== WPDA::get_option( WPDA::OPTION_MR_KEEP_BACKUP_TABLES ) ) {
					// Drop backup table
					$this->run_script( static::DROP_TABLE[ $key ][0], $bck_postfix );
				}

			}

			// Add hyperlink to media type options (needed for older releases)
			$wpdb->query(
				"ALTER TABLE {$wpdb->prefix}wpda_media MODIFY media_type ENUM('Image', 'Attachment', 'Hyperlink');"
			);

			$this->cleanup();

			$wpdb->suppress_errors( $suppress );
		}

		/**
		 * Cleanup plugin repository tables
		 */
		protected function cleanup() {
			global $wpdb;

			if ( WPDA_Publisher_Model::table_exists() ) {
				$query = "select column_type from information_schema.columns " .
				         "where table_schema = '{$wpdb->dbname}' and table_name = '" .
				         WPDA_Publisher_Model::get_base_table_name() . "' and " . "column_name = 'pub_responsive_type'";
				$column_type = $wpdb->get_results( $query, 'ARRAY_A' );
				if ( false !== strpos( $column_type[0]['column_type'], 'Collaped' ) ) {
					// Update collaped to collapsed (form post #12275882 - dizwell)
					// Add new value to enum
					$alter = "alter table " . WPDA_Publisher_Model::get_base_table_name() .
					         " modify pub_responsive_type enum('Modal', 'Collaped', 'Collapsed', 'Expanded')";
					$wpdb->query( $alter );
					// Update old values
					$update = "update " . WPDA_Publisher_Model::get_base_table_name() .
					          " set pub_responsive_type = 'Collapsed' where pub_responsive_type = 'Collaped'";
					$wpdb->query( $update );
					// Remove old value from enum
					$alter = "alter table " . WPDA_Publisher_Model::get_base_table_name() .
					         " modify pub_responsive_type enum('Modal', 'Collapsed', 'Expanded')";
					$wpdb->query( $alter );
				}
				$alter = "alter table " . WPDA_Publisher_Model::get_base_table_name() .
				         " modify pub_table_options_advanced text";
				$wpdb->query( $alter );
			}

			if ( WPDP_Page_Model::table_exists() ) {
				$alter = "alter table " . WPDP_Page_Model::get_base_table_name() .
				         " modify page_allow_insert enum('yes','no','only')";
				$wpdb->query( $alter );
			}

			if ( WPDA_Media_Model::table_exists() ) {
				$alter = "alter table " . WPDA_Media_Model::get_base_table_name() .
				         " modify media_type enum('Image', 'Attachment', 'Hyperlink', 'Audio', 'Video')";
				$wpdb->query( $alter );
			}

			// Cleanup repository table (set empty schema_name to WordPress schema)
			$updates = [];
			$updates[] = "update " . WPDA_Table_Settings_Model::get_base_table_name() . " set wpda_schema_name = '{$wpdb->dbname}' where wpda_schema_name = ''";
			$updates[] = "update " . WPDA_User_Menus_Model::get_base_table_name() . " set menus_schema_name = '{$wpdb->dbname}' where menus_schema_name = ''";
			$updates[] = "update " . WPDA_Media_Model::get_base_table_name() . " set media_schema_name = '{$wpdb->dbname}' where media_schema_name = ''";
			$updates[] = "update " . WPDA_Publisher_Model::get_base_table_name() . " set pub_schema_name = '{$wpdb->dbname}' where pub_schema_name = ''";
			$updates[] = "update " . WPDA_Design_Table_Model::get_base_table_name() . " set wpda_schema_name = '{$wpdb->dbname}' where wpda_schema_name = ''";
			foreach ( $updates as $update ) {
				$wpdb->query( $update );
			}

			// Cleanup old menu table (if (still) available)
			$old_menu_table_name   = $wpdb->prefix . 'wpda_menu_items';
			$old_menu_table_exists = new WPDA_Dictionary_Exist( $wpdb->dbname, $old_menu_table_name );
			if ( $old_menu_table_exists->table_exists( false ) ) {
				$query = "select * from $old_menu_table_name";
				$rows  = $wpdb->get_results( $query, 'ARRAY_A' );

				$num_rows_old = $wpdb->num_rows;
				$num_rows_new = 0;

				global $wp_roles;
				foreach ( $rows as $row ) {
					$roles_authorized_array = [];
					foreach ( $wp_roles->roles as $role => $val ) {
						if ( isset( $val['capabilities'][ $row['menu_capability'] ] ) ) {
							array_push( $roles_authorized_array, $role ); // Authorize role
						}
					}
					$roles_authorized = implode( ',', $roles_authorized_array );

					$new_menu_table_name   = $wpdb->prefix . 'wpda_menus';
					$new_menu_table_exists = new WPDA_Dictionary_Exist( $wpdb->dbname, $new_menu_table_name );
					if ( $new_menu_table_exists->table_exists( false ) ) {
						// Add old menu to new plugin table
						$num_rows_new += $wpdb->insert(
							$new_menu_table_name,
							[
								'menu_table_name' => $row['menu_table_name'],
								'menu_name'       => $row['menu_name'],
								'menu_slug'       => $row['menu_slug'],
								'menu_role'       => $roles_authorized,
							]
						);
					}
				}

				if ( $num_rows_new === $num_rows_old ) {
					$wpdb->query( "drop table $old_menu_table_name" );
				}
			}

			// Cleanup old project table (if (still) available)
			$old_project_table_name   = $wpdb->prefix . 'wpdp_project';
			$old_project_table_exists = new WPDA_Dictionary_Exist( $wpdb->dbname, $old_project_table_name );
			if ( $old_project_table_exists->table_exists( false ) ) {
				$query = "insert into {$wpdb->prefix}wpda_project " .
				         "(project_id, project_name , project_description, add_to_menu, menu_name, project_sequence) " .
				         "select project_id, project_name , project_description, add_to_menu, menu_name, project_sequence " .
				         " from $old_project_table_name";

				$wpdb->query( $query );
				$wpdb->get_results( "select * from {$wpdb->prefix}wpda_project" );
				$num_rows_new = $wpdb->num_rows;

				$wpdb->query( $query );
				$wpdb->get_results( "select * from $old_project_table_name" );
				$num_rows_old = $wpdb->num_rows;

				if ( $num_rows_new === $num_rows_old ) {
					$wpdb->query( "drop table $old_project_table_name" );
				}
			}

			// Cleanup old project page table (if (still) available)
			$old_page_table_name   = $wpdb->prefix . 'wpdp_page';
			$old_page_table_exists = new WPDA_Dictionary_Exist( $wpdb->dbname, $old_page_table_name );
			if ( $old_page_table_exists->table_exists( false ) ) {
				$query = "insert into {$wpdb->prefix}wpda_project_page " .
				         "(project_id, page_id, add_to_menu, page_name, page_type, page_table_name, page_mode, page_allow_insert, " .
				         "page_allow_delete, page_content, page_title , page_subtitle , page_role , page_where , page_sequence) " .
				         "select project_id, page_id, add_to_menu, page_name, page_type, page_table_name, page_mode, page_allow_insert, " .
				         "page_allow_delete, page_content, page_title , page_subtitle , page_role , page_where , page_sequence " .
				         " from $old_page_table_name";
				$wpdb->query( $query );

				$wpdb->query( $query );
				$wpdb->get_results( "select * from {$wpdb->prefix}wpda_project_page" );
				$num_rows_new = $wpdb->num_rows;

				$wpdb->query( $query );
				$wpdb->get_results( "select * from $old_page_table_name" );
				$num_rows_old = $wpdb->num_rows;

				if ( $num_rows_new === $num_rows_old ) {
					$wpdb->query( "drop table $old_page_table_name" );
				}
			}

			// Cleanup old project table table (if (still) available)
			$old_project_table_table_name   = $wpdb->prefix . 'wpdp_table';
			$old_project_table_table_exists = new WPDA_Dictionary_Exist( $wpdb->dbname, $old_project_table_table_name );
			if ( $old_project_table_table_exists->table_exists( false ) ) {
				$query = "insert into {$wpdb->prefix}wpda_project_table " .
				         "(wpda_table_name, wpda_table_design) " .
				         "select wpda_table_name, wpda_table_design " .
				         " from $old_project_table_table_name";
				$wpdb->query( $query );

				$wpdb->query( $query );
				$wpdb->get_results( "select * from {$wpdb->prefix}wpda_project_table" );
				$num_rows_new = $wpdb->num_rows;

				$wpdb->query( $query );
				$wpdb->get_results( "select * from $old_project_table_table_name" );
				$num_rows_old = $wpdb->num_rows;

				if ( $num_rows_new === $num_rows_old ) {
					$wpdb->query( "drop table $old_project_table_table_name" );
				}
			}
		}

		/**
		 * Create repository
		 *
		 * @since   1.0.0
		 */
		public function create() {
			foreach ( static::CREATE_TABLE as $key => $value ) {
				foreach ( $value as $sql_file ) {
					$this->run_script( $sql_file );
				}
			}
		}

		/**
		 * Drop repository
		 *
		 * @since   1.0.0
		 */
		public function drop() {
			foreach ( static::DROP_TABLE as $key => $value ) {
				foreach ( $value as $sql_file ) {
					$this->run_script( $sql_file );
				}
			}
		}

		/**
		 * Run SQL script file
		 *
		 * @param string $sql_file SQL script file name
		 * @param string $wpda_postfix WPDA postfix
		 *
		 * @return mixed Result of the query taken from the SQL script file
		 *
		 * @since   2.0.11
		 */
		protected function run_script( $sql_file, $wpda_postfix = '' ) {
			$sql_repository_file   = $this->sql_repository_dir . $sql_file;
			$sql_repository_handle = fopen( $sql_repository_file, 'r' );

			if ( $sql_repository_handle ) {
				// Read file content and close handle.
				$sql_repository_file_content = fread( $sql_repository_handle, filesize( $sql_repository_file ) );
				fclose( $sql_repository_handle );

				global $wpdb;

				// Replace WP prefix and WPDA prefix.
				$sql_repository_file_content = str_replace( '{wp_prefix}', $wpdb->prefix, $sql_repository_file_content );
				$sql_repository_file_content = str_replace( '{wpda_prefix}', 'wpda', $sql_repository_file_content ); // for backward compatibility
				$sql_repository_file_content = str_replace( '{wpda_postfix}', $wpda_postfix, $sql_repository_file_content );

				// Run script.
				return $wpdb->query( $sql_repository_file_content ); // WPCS: unprepared SQL OK; db call ok; no-cache ok.
			}
		}


		/**
		 * Inform user if repository is invalid
		 *
		 * @since   1.0.0
		 */
		public function inform_user() {
			if ( ! is_admin() ) {
				return;
			}

			if ( isset( $_REQUEST['setup_error'] ) && 'off' === $_REQUEST['setup_error'] ) {
				// Turn off menu management not available message.
				WPDA::set_option( WPDA::OPTION_WPDA_SETUP_ERROR, 'off' );
			} else {
				if ( 'off' !== WPDA::get_option( WPDA::OPTION_WPDA_SETUP_ERROR ) ) {
					// Check if repository tables exist.
					if ( ! WPDA_User_Menus_Model::table_exists() ||
					     ! WPDA_Design_Table_Model::table_exists() ||
					     ! WPDP_Project_Design_Table_Model::table_exists() ||
					     ! WPDA_Publisher_Model::table_exists() ||
					     ! WPDA_Logging_Model::table_exists() ||
					     ! WPDA_Media_Model::table_exists() ||
					     ! WPDP_Project_Model::table_exists() ||
					     ! WPDP_Page_Model::table_exists() ||
					     ! WPDA_Table_Settings_Model::table_exists()
					) {
						$msg = new WPDA_Message_Box(
							[
								'message_text' =>
									__( 'Some features of WP Data Access are currently not available.', 'wp-data-access' ) .
									' ' .
									__( 'ACTION', 'wp-data-access' ) .
									': ' .
									'<a href="?page=wpda_settings&tab=repository">' . __( 'Recreate repository', 'wp-data-access' ) . '</a>' .
									' ' .
									__( 'to to solve this problem.', 'wp-data-access' ) .
									' [' .
									'<a href="?' . $_SERVER['QUERY_STRING'] . '&setup_error=off">' . __( 'do not show this message again', 'wp-data-access' ) . '</a>' .
									']',
							]
						);

						$msg->box();
					}
				}
			}

			if ( isset( $_REQUEST['whats_new'] ) && 'off' === $_REQUEST['whats_new'] ) {
				// Turn off what's new message.
				WPDA::set_option( WPDA::OPTION_WPDA_SHOW_WHATS_NEW, 'off' );
			}
			if ( 'off' !== WPDA::get_option( WPDA::OPTION_WPDA_SHOW_WHATS_NEW ) ) {
				$msg = new WPDA_Message_Box(
					[
						'message_text' =>
							__( 'See the', 'wp-data-access' ) .
							' ' .
							'<span class="dashicons dashicons-external"></span>' .
							'<a href="https://wpdataaccess.com/docs/documentation/getting-started/whats-new/" target="_blank">' . __( 'what\'s new', 'wp-data-access' ) . '</a>' .
							' ' .
							__( 'page for new features added to WP Data Access.', 'wp-data-access' ) .
							' [' .
							'<a href="?' . $_SERVER['QUERY_STRING'] . '&whats_new=off">' . __( 'do not show this message again', 'wp-data-access' ) . '</a>' .
							']',
					]
				);

				$msg->box();
			}
		}

	}

}