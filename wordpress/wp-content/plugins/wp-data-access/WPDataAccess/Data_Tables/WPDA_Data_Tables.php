<?php

/**
 * Suppress "error - 0 - No summary was found for this file" on phpdoc generation
 *
 * @package WPDataAccess\Data_Tables
 */

namespace WPDataAccess\Data_Tables {

	use WPDataAccess\Connection\WPDADB;
	use WPDataAccess\Data_Dictionary\WPDA_Dictionary_Exist;
	use WPDataAccess\Data_Dictionary\WPDA_List_Columns_Cache;
	use WPDataAccess\List_Table\WPDA_List_Table;
	use WPDataAccess\Plugin_Table_Models\WPDA_Publisher_Model;
	use WPDataAccess\Plugin_Table_Models\WPDA_Media_Model;
	use WPDataAccess\WPDA;

	/**
	 * Class WPDA_Data_Tables
	 *
	 * @author  Peter Schulz
	 * @since   1.0.0
	 */
	class WPDA_Data_Tables {

		protected $wpda_list_columns;

		/**
		 * Generate jQuery DataTable code
		 *
		 * Table and column names provided are checked for existency and access to prevent hacking the DataTable code
		 * and SQL injection.
		 *
		 * @param int    $pub_id Publication ID.
		 * @param string $database Database name.
		 * @param string $table_name Database table name.
		 * @param string $column_names Comma seperated list of column names.
		 * @param string $responsive Yes = responsive mode, No = No responsive mode.
		 * @param int    $responsive_cols Number of columns to be displayd in responsive mode.
		 * @param string $responsive_type Modal, Collaped or Expanded (only if $responsive = Yes).
		 * @param string $responsive_icon Yes = show icon, No = do not show icon (only if $responsive = Yes).
		 * @param string $sql_where SQL default where clause
		 * @param string $sql_orderby SQL default order by
		 *
		 * @return Shortcode response
		 *
		 * @since   1.0.0
		 *
		 */
		public function show(
			$pub_id, $database, $table_name, $column_names, $responsive, $responsive_cols,
			$responsive_type, $responsive_icon, $sql_where, $sql_orderby
		) {
			// Activate scripts and styles
			wp_enqueue_script( 'jquery_datatables' );
			wp_enqueue_script( 'jquery_datatables_responsive' );
			wp_enqueue_script( 'purl' );
			wp_enqueue_script( 'wpda_datatables' );
			wp_enqueue_style( 'jquery_datatables' );
			wp_enqueue_style( 'jquery_datatables_responsive' );

			if ( '' === $pub_id && '' === $table_name ) {
				return '<p>' . __( 'ERROR: Missing argument [need at least pub_id or table argument]', 'wp-data-access' ) . '</p>';
			}

			if ( '' !== $pub_id ) {
				// Get publication information
				$publication = WPDA_Publisher_Model::get_publication( $pub_id );
				if ( false === $publication ) {
					// Querying tables in other schema's is not allowed!
					return '<p>' . __( 'ERROR: Publication ID not found', 'wp-data-access' ) . '</p>';
				}
				$database                    = $publication[0]['pub_schema_name'];
				$table_name                  = $publication[0]['pub_table_name'];
				$column_names                = $publication[0]['pub_column_names'];
				$responsive                  = strtolower( $publication[0]['pub_responsive'] );
				$responsive_popup_title      = $publication[0]['pub_responsive_popup_title'];
				$responsive_cols             = $publication[0]['pub_responsive_cols'];
				$responsive_type             = strtolower( $publication[0]['pub_responsive_type'] );
				$responsive_icon             = strtolower( $publication[0]['pub_responsive_icon'] );
				$pub_format                  = $publication[0]['pub_format'];
				$sql_where                   = $publication[0]['pub_default_where'];
				$sql_orderby                 = $publication[0]['pub_default_orderby'];
				$pub_table_options_searching = $publication[0]['pub_table_options_searching'];
				$pub_table_options_ordering  = $publication[0]['pub_table_options_ordering'];
				$pub_table_options_paging    = $publication[0]['pub_table_options_paging'];
				$pub_table_options_advanced  = $publication[0]['pub_table_options_advanced'];
				$pub_table_options_advanced  = str_replace( ["\r\n", "\r", "\n", "\t"], '', $pub_table_options_advanced );
			} else {
				$responsive_popup_title      = '';
				$pub_format                  = '';
				$pub_table_options_searching = '';
				$pub_table_options_ordering  = '';
				$pub_table_options_paging    = '';
				$pub_table_options_advanced  = '';
			}

			if ( 'off' === $pub_table_options_searching ) {
				$pub_table_options_searching = 'false';
			} else {
				$pub_table_options_searching = 'true';
			}

			if ( 'off' === $pub_table_options_ordering ) {
				$pub_table_options_ordering = 'false';
			} else {
				$pub_table_options_ordering = 'true';
			}

			if ( 'off' === $pub_table_options_paging ) {
				$pub_table_options_paging = 'false';
			} else {
				$pub_table_options_paging = 'true';
			}

			// WP database is the default
			if ( '' === $database ) {
				global $wpdb;
				$database = $wpdb->dbname;
			}

			// Check if table exists (prevent sql injection).
			$wpda_dictionary_checks = new WPDA_Dictionary_Exist( $database, $table_name );
			if ( ! $wpda_dictionary_checks->table_exists( true, false ) ) {
				// Table not found.
				return '<p>' . __( 'ERROR: Invalid table name or not authorized', 'wp-data-access' ) . '</p>';
			}

			// Set columns to be queried.
			$this->wpda_list_columns = WPDA_List_Columns_Cache::get_list_columns( $database, $table_name );
			if ( '*' === $column_names ) {
				// Get all column names from table.
				$columns = []; // Create column ARRAY ***.
				foreach ( $this->wpda_list_columns->get_table_columns() as $column ) {
					$columns[] = $column['column_name'];
				}
			} else {
				$columns = explode( ',', $column_names ); // Create column ARRAY.
				// Check if columns exist (prevent sql injection).
				foreach ( $columns as $column ) {
					if ( ! $wpda_dictionary_checks->column_exists( $column ) ) {
						// Column not found.
						return __( 'ERROR: Column', 'wp-data-access' ) . ' ' . esc_attr( $column ) . ' ' . __( 'not found', 'wp-data-access' );
					}
				}
			}

			if ( ! is_numeric( $responsive_cols ) ) {
				$responsive_cols = 0;
			}

			$wpda_database_columns = '';
			for ( $i = 0; $i < count( $columns ); $i ++ ) {
				$wpda_database_columns .= '{ "className": "' . $columns[ $i ] . '", "targets": [' . $i . '] }';
				if ( $i < count( $columns ) - 1 ) {
					$wpda_database_columns .= ',';
				}
			}

			if ( "" === $pub_id ) {
				$pub_id = '0';
			}

			// Get language for jQuery DataTables
			$language   = WPDA::get_option( WPDA::OPTION_DP_LANGUAGE );
			$columnsvar = 'wpdaDbColumns' . preg_replace( '/[^a-zA-Z0-9]/', '', $table_name ) . $pub_id;

			return
				"<table id=\"" . esc_attr( $table_name ) . "$pub_id\" class=\"display nowrap\" cellspacing=\"0\">" .
				"	<thead>" . $this->show_header( $columns, $responsive, $responsive_cols, $pub_format ) . "</thead>" .
				"	<tfoot>" . $this->show_header( $columns, $responsive, $responsive_cols, $pub_format ) . "</tfoot>" .
				"</table>" .
				"<script language='javascript'>" .
				"var $columnsvar = [" . $wpda_database_columns . "];" .
				"jQuery(document).ready(function () {" .
				"	wpda_datatables_ajax_call(" .
				"		" . $columnsvar . "," .
				"		\"" . esc_attr( $database ) . "\"," .
				"		\"" . esc_attr( $table_name ) . "\"," .
				"		\"" . esc_attr( $column_names ) . "\"," .
				"		\"" . esc_attr( $responsive ) . "\"," .
				"		\"" . esc_attr( $responsive_popup_title ) . "\"," .
				"		\"" . esc_attr( $responsive_type ) . "\"," .
				"		\"" . esc_attr( $responsive_icon ) . "\"," .
				"		\"" . esc_attr( $pub_format ) . "\"," .
				"		\"" . esc_attr( $language ) . "\"," .
				"		\"" . htmlentities( $sql_where ) . "\"," .
				"		\"" . htmlentities( $sql_orderby ) . "\"," .
				"		" . $pub_table_options_searching . "," .
				"	    " . $pub_table_options_ordering . "," .
				"		" . $pub_table_options_paging . "," .
				"		\"" . esc_attr( $pub_table_options_advanced ) . "\"," .
				"		" . $pub_id . "," .
				"	);" .
				"});" .
				"</script>";
		}

		/**
		 * Show table header (footer as well)
		 *
		 * @param string $columns Comma seperated list of column names.
		 * @param string $responsive Yes = responsive mode, No = No responsive mode.
		 * @param int    $responsive_cols Number of columns to be displayd in responsive mode.
		 * @param string $pub_format Formatting options.
		 *
		 * @return HTML output
		 */
		protected function show_header( $columns, $responsive, $responsive_cols, $pub_format ) {
			$count       = 0;
			$html_output = '';

			$column_labels = null;
			$pub_format    = json_decode( $pub_format, true );
			if ( isset( $pub_format['pub_format']['column_labels'] ) ) {
				$column_labels = $pub_format['pub_format']['column_labels'];
			} else {
				$column_labels = $this->wpda_list_columns->get_table_column_headers();
			}

			foreach ( $columns as $column ) {
				if ( 'yes' !== $responsive ) {
					$class = '';
				} else {
					if ( $count >= 1 && $count >= $responsive_cols ) {
						$class = 'none';
					} else {
						$class = 'all';
					}
				}
				$html_output .= "<th class=\"$class\">" . ( isset( $column_labels[ $column ] ) ? $column_labels[ $column ] : $column ) . '</th>';
				$count ++;
			}

			return $html_output;
		}

		/**
		 * Performs jQuery DataTable query
		 *
		 * Once a jQuery DataTable is build using {@see WPDA_Data_Tables::show()}, the DataTable is filled according
		 * to the search criteria and pagination settings on the Datable. The query is performed through this function.
		 * The query result is returned (echo) in JSON format. Table and column names are checked for existence and
		 * access to prevent hacking the DataTable code and SQL injection.
		 *
		 * @since   1.0.0
		 *
		 * @see WPDA_Data_Tables::show()
		 */
		public function get_data() {
			if ( ! isset( $_REQUEST['database'] ) || ! isset( $_REQUEST['table_name'] ) ) { // input var okay.
				// Database and table name must be set!
				wp_die();
			} else {
				// Set table name.
				$table_name = sanitize_text_field( wp_unslash( $_REQUEST['table_name'] ) ); // input var okay.
				$database   = sanitize_text_field( wp_unslash( $_REQUEST['database'] ) ); // input var okay.
				$sql_where  = html_entity_decode( sanitize_text_field( wp_unslash( $_REQUEST['sql_where'] ) ) ); // input var okay.

				$wpdadb = WPDADB::get_db_connection( $database );

				if ( strpos( $table_name, '.' ) ) {
					// Querying tables in other schema's is not allowed!
					wp_die();
				}

				// Check if table exists (prevent sql injection).
				$wpda_dictionary_checks = new WPDA_Dictionary_Exist( $database, $table_name );
				if ( ! $wpda_dictionary_checks->table_exists( true, false ) ) {
					// Table not found.
					wp_die();
				}

				// Get all column names from table (must be comma seperated string).
				$this->wpda_list_columns = WPDA_List_Columns_Cache::get_list_columns( $database, $table_name );
				$table_columns           = $this->wpda_list_columns->get_table_columns();

				// Set columns to be queried.
				$columns = '*';
				if ( isset( $_REQUEST['columns'] ) ) {
					// Use columns from shortcode arguments.
					$columns = str_replace( ' ', '', sanitize_text_field( wp_unslash( $_REQUEST['columns'] ) ) ); // input var okay.
				}

				if ( '*' === $columns ) {
					// Get all column names from table (must be comma seperated string).
					$column_array = [];
					foreach ( $table_columns as $column ) {
						$column_array[] = $column['column_name'];
					}
					$columns = implode( ',', $column_array );
				} else {
					// Check if columns exist (prevent sql injection).
					$wpda_dictionary_checks = new WPDA_Dictionary_Exist( $database, $table_name );
					$column_array           = explode( ',', $columns );
					foreach ( $column_array as $column ) {
						if ( ! $wpda_dictionary_checks->column_exists( $column ) ) {
							// Column not found.
							wp_die();
						}
					}
				}

				// Set pagination values.
				$offset = 0;
				if ( isset( $_REQUEST['start'] ) ) {
					$offset = sanitize_text_field( wp_unslash( $_REQUEST['start'] ) ); // input var okay.
				}
				$limit = -1; // jQuery DataTables default.
				if ( isset( $_REQUEST['length'] ) ) {
					$limit = sanitize_text_field( wp_unslash( $_REQUEST['length'] ) ); // input var okay.
				}

				// Set order by.
				$orderby = '';
				if ( isset( $_REQUEST['order'] ) && is_array( $_REQUEST['order'] ) ) { // input var okay.
					$orderby_columns = [];
					$orderby_args    = [];
					// Sanitize argument array and write result to termporary sanatizes array for processing:
					foreach ( $_REQUEST['order'] as $order_column ) { // input var okay.
						$orderby_args[] = [
							'column' => sanitize_text_field( wp_unslash( $order_column['column'] ) ),
							'dir'    => sanitize_text_field( wp_unslash( $order_column['dir'] ) ),
						];
					}
					foreach ( $orderby_args as $order_column ) { // input var okay.
						$column_index      = $order_column['column'];
						$column_name       = $column_array[ $column_index ];
						$column_dir        = $order_column['dir'];
						$orderby_columns[] = "`$column_name` $column_dir";
					}
					$orderby = 'order by ' . implode( ',', $orderby_columns );
				}

				// Add search criteria.
				$where = '';
				if ( '' !== $sql_where ) {
					if ( 'where' === strtolower( trim( substr( $sql_where, 0, 5 ) ) ) ) {
						$where = $sql_where;
					} else {
						$where = "where $sql_where";
					}
				}

				if ( isset( $_REQUEST['search'] ) && '' !== sanitize_text_field( wp_unslash( $_REQUEST['search']['value'] ) ) ) { // input var okay.
					$search_value  = sanitize_text_field( wp_unslash( $_REQUEST['search']['value'] ) ); // input var okay.
					$where_columns = WPDA::construct_where_clause(
						$database,
						$table_name,
						$this->wpda_list_columns->get_table_columns(),
						$search_value
					);
					if ( '' !== $where_columns ) {
						if ( '' === $where ) {
							$where = " where $where_columns ";
						} else {
							$where .= " and $where_columns ";
						}
					}
				}

				if ( '' !== $where ) {
					$where = WPDA::substitute_environment_vars( $where );
				}

				// Execute query.
				$column_array      = explode( ',', $columns );
				$images_array      = [];
				$attachments_array = [];
				$hyperlinks_array  = [];
				$audio_array       = [];
				$video_array       = [];
				if ( isset( $_REQUEST['pub_format'] ) ) {
					$pub_format         = sanitize_text_field( wp_unslash( $_REQUEST['pub_format'] ) ); // input var okay.
					$pub_format         = wp_specialchars_decode( $pub_format, ENT_QUOTES );
					$pub_format         = json_decode( $pub_format, true );
					$column_images      = [];
					$column_attachments = [];
					if ( isset( $pub_format['pub_format']['column_images'] ) ) {
						$column_images = $pub_format['pub_format']['column_images'];
					}
					if ( isset( $pub_format['pub_format']['column_attachments'] ) ) {
						$column_attachments = $pub_format['pub_format']['column_attachments'];
					}
					$i = 0;
					foreach ( $column_array as $col ) {
						if ( isset( $column_images[ $col ] ) ) {
							array_push( $images_array, $i );
						}
						$i ++;
					}
					$i = 0;
					foreach ( $column_array as $col ) {
						if ( isset( $column_attachments[ $col ] ) ) {
							array_push( $attachments_array, $i );
						}
						$i ++;
					}
				}

				// Check media columns defined on plugin level and add to arrays
				$i = 0;
				foreach ( $column_array as $col ) {
					if ( 'Image' === WPDA_Media_Model::get_column_media( $table_name, $col, $database ) ) {
						if ( ! isset( $images_array[ $i ] ) ) {
							array_push( $images_array, $i );
						}
					} elseif ( 'Attachment' === WPDA_Media_Model::get_column_media( $table_name, $col, $database ) ) {
						if ( ! isset( $attachments_array[ $i ] ) ) {
							array_push( $attachments_array, $i );
						}
					} elseif ( 'Hyperlink' === WPDA_Media_Model::get_column_media( $table_name, $col, $database ) ) {
						if ( ! isset( $hyperlinks_array[ $i ] ) ) {
							array_push( $hyperlinks_array, $i );
						}
					} elseif ( 'Audio' === WPDA_Media_Model::get_column_media( $table_name, $col, $database ) ) {
						array_push( $audio_array, $i );
					} elseif ( 'Video' === WPDA_Media_Model::get_column_media( $table_name, $col, $database ) ) {
						array_push( $video_array, $i );
					}
					$i ++;
				}

				$columns_backticks = '`' . implode( '`,`', $column_array ) . '`';
				$query             = "select $columns_backticks from `{$wpdadb->dbname}`.`$table_name` $where $orderby";
				if ( -1 !== $limit ) {
					$query .= " limit $limit offset $offset";
				}

				$rows              = $wpdadb->get_results( $query, 'ARRAY_N' ); // WPCS: unprepared SQL OK; db call ok; no-cache ok.
				$rows_final        = [];
				foreach ( $rows as $row ) {
					for ( $i = 0; $i < sizeof( $images_array ); $i ++ ) {
						$image_ids = explode( ',', $row[ $images_array[ $i ] ] );
						$image_src = '';
						foreach ( $image_ids as $image_id ) {
							$url = wp_get_attachment_url( esc_attr( $image_id ) );
							if ( false !== $url ) {
								$image_metadata = wp_get_attachment_metadata( $row[ $images_array[ $i ] ] );
								$image_src      .= '' !== $image_src ? '<br/>' : '';
								$image_src      .=
									'<img src="' . $url . '" style="width:' . $image_metadata['width'] .
									'px;height:' . $image_metadata['height'] . 'px;">';
							}
						}
						$row[ $images_array[ $i ] ] = $image_src;
					}

					for ( $i = 0; $i < sizeof( $attachments_array ); $i ++ ) {
						$media_ids   = explode( ',', $row[ $attachments_array[ $i ] ] );
						$media_links = '';
						foreach ( $media_ids as $media_id ) {
							$url = wp_get_attachment_url( esc_attr( $media_id ) );
							if ( false !== $url ) {
								$mime_type = get_post_mime_type( $media_id );
								if ( false !== $mime_type ) {
									$title       = get_the_title( esc_attr( $media_id ) );
									$media_links .= WPDA_List_Table::column_media_attachment( $url, $title, $mime_type );
								}
							}
						}
						$row[ $attachments_array[ $i ] ] = $media_links;
					}

					for ( $i = 0; $i < sizeof( $hyperlinks_array ); $i ++ ) {
						$hyperlink = json_decode( $row[ $hyperlinks_array[ $i ] ], true );
						if ( is_array( $hyperlink ) ) {
							if ( isset( $hyperlink['label'] ) &&
							     isset( $hyperlink['url'] ) &&
							     isset( $hyperlink['target'] ) ) {
								$row[ $hyperlinks_array[ $i ] ] = "<a href='{$hyperlink['url']}' target='{$hyperlink['target']}'>{$hyperlink['label']}</a>";
							} else {
								$row[ $hyperlinks_array[ $i ] ] = '';
							}
						} else {
							$row[ $hyperlinks_array[ $i ] ] = '';
						}
					}

					for ( $i = 0; $i < sizeof( $audio_array ); $i ++ ) {
						$media_ids   = explode( ',', $row[ $audio_array[ $i ] ] );
						$media_links = '';
						foreach ( $media_ids as $media_id ) {
							if ( 'audio' === substr( get_post_mime_type( $media_id ), 0, 5 ) ) {
								$url   = wp_get_attachment_url( esc_attr( $media_id ) );
								if ( false !== $url ) {
									$title = get_the_title( esc_attr( $media_id ) );
									if ( false !== $url ) {
										$media_links .=
											'<div title="' . $title . '">' .
											do_shortcode( '[audio src="' . $url . '"]' ) .
											'</div>';
									}
								}
							}
						}
						$row[ $audio_array[ $i ] ] = $media_links;
					}

					for ( $i = 0; $i < sizeof( $video_array ); $i ++ ) {
						$media_ids   = explode( ',', $row[ $video_array[ $i ] ] );
						$media_links = '';
						foreach ( $media_ids as $media_id ) {
							if ( 'video' === substr( get_post_mime_type( $media_id ), 0, 5 ) ) {
								$url   = wp_get_attachment_url( esc_attr( $media_id ) );
								if ( false !== $url ) {
									if ( false !== $url ) {
										$media_links .=
											do_shortcode( '[video src="' . $url . '"]' );
									}
								}
							}
						}
						$row[ $video_array[ $i ] ] = $media_links;
					}

					array_push( $rows_final, $row );
				}

				// Count rows in table.
				$query       = "select count(*) from `{$wpdadb->dbname}`.`$table_name`";
				$count_rows  = $wpdadb->get_results( $query, 'ARRAY_N' ); // WPCS: unprepared SQL OK; db call ok; no-cache ok.
				$count_table = $count_rows[0][0]; // Number of rows in table.

				if ( '' !== $where ) {
					// Count rows in selection (only necessary if a search criteria was entered).
					$query                = "select count(*) from `{$wpdadb->dbname}`.`$table_name` $where";
					$count_rows_filtered  = $wpdadb->get_results( $query, 'ARRAY_N' ); // WPCS: unprepared SQL OK; db call ok; no-cache ok.
					$count_table_filtered = $count_rows_filtered[0][0]; // Number of rows in table.
				} else {
					// No search criteria entered: # filtered rows = # table rows.
					$count_table_filtered = $count_table;
				}

				// Convert query result to jQuery DataTables object.
				$obj                  = (object) null;
				$obj->draw            = isset( $_REQUEST['draw'] ) ? intval( $_REQUEST['draw'] ) : 0;
				$obj->recordsTotal    = $count_table;
				$obj->recordsFiltered = $count_table_filtered;
				$obj->data            = $rows_final;

				// Convert object to json. jQuery DataTables needs json format.
				echo json_encode( $obj );
			}

			wp_die();
		}

	}

}
