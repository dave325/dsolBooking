<?php

/**
 * Suppress "error - 0 - No summary was found for this file" on phpdoc generation
 *
 * @package WPDataAccess\Simple_Form
 */

namespace WPDataAccess\Simple_Form {

	use WPDataAccess\Connection\WPDADB;
	use WPDataAccess\Data_Dictionary\WPDA_Dictionary_Exist;
	use WPDataAccess\Data_Dictionary\WPDA_List_Columns;
	use WPDataAccess\Utilities\WPDA_Message_Box;
	use WPDataAccess\WPDA;

	/**
	 * Class WPDA_Simple_Form_Data
	 *
	 * WPDA_Simple_Form_Data is responsible for data management. It queries the database, adds new records to tables
	 * and updates table data. Simple validations are performed based on information retrieved from the data dictionary.
	 *
	 * @author  Peter Schulz
	 * @since   1.0.0
	 */
	class WPDA_Simple_Form_Data {

		/**
		 * Database schema name
		 *
		 * @var string
		 */
		protected $schema_name;

		/**
		 * Database table name
		 *
		 * @var string
		 */
		protected $table_name;

		/**
		 * Reference to calling form
		 *
		 * @var WPDA_Simple_Form
		 */
		protected $calling_form;

		/**
		 * Reference to column list
		 *
		 * @var WPDA_List_Columns
		 */
		protected $wpda_list_columns;

		/**
		 * Default succes message
		 *
		 * Is set in the constructor to support internationalization.
		 *
		 * @var string
		 */
		protected $wpda_success_msg;

		/**
		 * Default failure message
		 *
		 * Is set in the constructor to support internationalization.
		 *
		 * @var string
		 */
		protected $wpda_failure_msg;

		/**
		 * Handle to data dictionary object
		 *
		 * @var WPDA_Dictionary_Exist
		 */
		protected $wpda_data_dictionary;

		/**
		 * WPDA_Simple_Form_Data constructor
		 *
		 * Check if table exists and access is granted.
		 *
		 * @param string            $schema_name Database schema name.
		 * @param string            $table_name Database table name.
		 * @param WPDA_List_Columns $wpda_list_columns Reference to column array.
		 * @param WPDA_Simple_Form  $calling_form Reference to calling form.
		 * @param string            $wpda_success_msg Message shown on success.
		 * @param string            $wpda_failure_msg Message shown on failure.
		 *
		 * @since   1.0.0
		 *
		 */
		public function __construct(
			$schema_name,
			$table_name,
			&$wpda_list_columns,
			&$calling_form,
			$wpda_success_msg,
			$wpda_failure_msg
		) {

			$this->schema_name = $schema_name;
			$this->table_name  = $table_name;

			// Table must exist and user must be authorized.
			$this->wpda_data_dictionary = new WPDA_Dictionary_Exist( $this->schema_name, $this->table_name );
			if ( ! $this->wpda_data_dictionary->table_exists() ) {
				wp_die( __( 'ERROR: Invalid table name or not authorized' ) );
			}

			$this->calling_form = $calling_form;

			$this->wpda_list_columns = $wpda_list_columns;

			$this->wpda_success_msg = $wpda_success_msg;
			$this->wpda_failure_msg = $wpda_failure_msg;

		}

		/**
		 * Create new record
		 *
		 * Nothing to do!
		 *
		 * @return null
		 * @since   1.0.0
		 *
		 */
		public function new_row() {

			return null;

		}

		/**
		 * Add records to database table
		 *
		 * @return bool TRUE = record successfully added to table
		 * @since   1.0.0
		 *
		 */
		public function add_row() {
			$column_values_to_be_inserted = null;
			foreach ( $this->wpda_list_columns->get_table_columns() as $column ) {
				if ( $column['column_name'] === $this->wpda_list_columns->get_auto_increment_column_name() ) {
					// Auto increment column is not added
				} else {
					$column_values_to_be_inserted[ $column['column_name'] ] =
						$this->calling_form->get_new_value( $column['column_name'] );
				}
			}

			$wpdadb = WPDADB::get_db_connection( $this->schema_name );
			$result = $wpdadb->insert(
				$this->table_name,
				$column_values_to_be_inserted
			); // db call ok; no-cache ok.

			if ( 1 === $result ) {
				$msg = new WPDA_Message_Box(
					[
						'message_text' => $this->wpda_success_msg,
					]
				);
				$msg->box();

				// If inserted record contains an auto_increment column: return value.
				if ( false !== $this->wpda_list_columns->get_auto_increment_column_name() ) {
					return $wpdadb->insert_id; // Return auto_increment value.
				} else {
					return true; // Return true = transaction succeeded.
				}
			} else {
				// An error occurred.
				$msg = new WPDA_Message_Box(
					[
						'message_text'           => $this->wpda_failure_msg . '' === $wpdadb->last_error ? '' : ' [' . $wpdadb->last_error . ']',
						'message_type'           => 'error',
						'message_is_dismissible' => false,
					]
				);
				$msg->box();

				return false; // Return false = transaction failed.
			}
		}

		/**
		 * Get record from database table
		 *
		 * @param int    $auto_increment_value Auto increment number (returned if provided by dbms).
		 * @param string $wpda_err Error message to be shown on failure.
		 *
		 * @return mixed
		 * @since   1.0.0
		 *
		 */
		public function get_row( $auto_increment_value, $wpda_err ) {
			$wpdadb = WPDADB::get_db_connection( $this->schema_name );

			$table_columns = []; // Get all table columns.
			foreach ( $this->wpda_list_columns->get_table_columns() as $column ) {
				$table_columns[ $column['column_name'] ] = $column['data_type'];
			}

			$where = ''; // Compose where clause.
			foreach ( $this->wpda_list_columns->get_table_primary_key() as $pk_column ) {
				$where_current = '' === $where ? ' where ' : ' and ';
				if ( WPDA::get_type( $table_columns[ $pk_column ] ) === 'number' ) {
					// Column data type is numeric:
					// For numeric columns we need to omit quotes. All numeric values will be handled as float. MySQL
					// will automatically convert them if necessarry. Values supplied in a wrong format will be handed
					// over to MySQL as is and might result in unpredictable results. For our simple form we rely on
					// the users judgement.
					$where_current .= " `$pk_column` = %f";
				} else {
					// Column data type is string:
					// Quotes will be added to all non numeric values. We might have issues with date and time fields.
					// For our simple form we'll accept that limitation (at least for now).
					$where_current .= " `$pk_column` = %s";
				}

				if ( $auto_increment_value > - 1 ) {
					// For inserts with auto_increment columns use $wpdadb->insert_id.
					$pkvalue = $auto_increment_value;
				} else {
					if ( 0 === $wpda_err ) {
						// No errors: use new value.
						if ( $this->calling_form->get_new_value( $pk_column ) === '' ) {
							wp_die( __( 'ERROR: Wrong arguments [missing primary key value]', 'wp-data-access' ) );
						}
						$pkvalue = $this->calling_form->get_new_value( $pk_column );
					} else {
						// There are errors: use old values (in case a key value was changed).
						if ( $this->calling_form->get_old_value( $pk_column ) === '' ) {
							wp_die( __( 'ERROR: Wrong arguments [missing primary key value]', 'wp-data-access' ) );
						}
						$pkvalue = $this->calling_form->get_old_value( $pk_column );
					}
				}

				$where .= $wpdadb->prepare( $where_current, $pkvalue ); // WPCS: unprepared SQL OK.
			}

			if ( '' === $this->schema_name ) {
				$query = "
					select * 
					from `{$this->table_name}`
					$where
				";
			} else {
				$query = "
					select * 
					from `{$wpdadb->dbname}`.`{$this->table_name}`
					$where
				";
			}
			$result = $wpdadb->get_results( $query, 'ARRAY_A' ); // WPCS: unprepared SQL OK; db call ok; no-cache ok.

			if ( 1 === $wpdadb->num_rows ) {
				return $result;
			} else {
				wp_die( __( 'ERROR: Wrong arguments [no data found]', 'wp-data-access' ) );
			}

		}

		/**
		 * Update current record (write changes to database)
		 *
		 * @since   1.0.0
		 */
		public function set_row() {
			$wpdadb = WPDADB::get_db_connection( $this->schema_name );

			$column_values_to_be_updated = null;
			foreach ( $this->wpda_list_columns->get_table_columns() as $column ) {
				if ( $this->calling_form->get_old_value( $column['column_name'] ) !==
				     $this->calling_form->get_new_value( $column['column_name'] )
				) {
					if ( 'number' === WPDA::get_type( $column['data_type'] ) &&
					     '' === $this->calling_form->get_new_value( $column['column_name'] ) ) {
						$column_values_to_be_updated[ $column['column_name'] ] = null;
					} else {

						if ( 'time' === WPDA::get_type( $column['data_type'] ) ||
						     'date' === WPDA::get_type( $column['data_type'] ) ) {
							switch ( $column['data_type'] ) {
								case 'time':
									$date_format = WPDA::get_option( WPDA::OPTION_PLUGIN_TIME_FORMAT );
									$db_format   = WPDA::DB_TIME_FORMAT;
									break;
								case 'date':
									$date_format = WPDA::get_option( WPDA::OPTION_PLUGIN_DATE_FORMAT );
									$db_format   = WPDA::DB_DATE_FORMAT;
									break;
								default:
									$date_format = WPDA::get_option( WPDA::OPTION_PLUGIN_DATE_FORMAT ) . ' ' . WPDA::get_option( WPDA::OPTION_PLUGIN_TIME_FORMAT );
									$db_format   = WPDA::DB_DATETIME_FORMAT;
							}

							$convert_date     = \DateTime::createFromFormat( $date_format, $this->calling_form->get_new_value( $column['column_name'] ) );
							$item_value = $convert_date->format( $db_format );

							$column_values_to_be_updated[ $column['column_name'] ] = $item_value;
						} else {
							$column_values_to_be_updated[ $column['column_name'] ] =
								$this->calling_form->get_new_value( $column['column_name'] );
						}
					}
				}
			}

			if ( null === $column_values_to_be_updated ) {
				// Nothing to update.
				$msg = new WPDA_Message_Box(
					[
						'message_text' => __( 'Nothing to save', 'wp-data-access' ),
					]
				);
				$msg->box();
			} else {
				// Write changes to database.
				$where = [];
				foreach ( $this->wpda_list_columns->get_table_primary_key() as $pk_column ) {
					$action  = $this->calling_form->get_form_action();
					$action2 = $this->calling_form->get_form_action2();

					if ( 'edit' === $action && 'save' === $action2 ) {
						// Form was submitted after update: use old key value to build where clause.
						if ( '' === $this->calling_form->get_old_value( $pk_column ) ) {

							wp_die( __( 'ERROR: Wrong arguments [missing primary key value]', 'wp-data-access' ) );

						}
						$where[ $pk_column ] = $this->calling_form->get_old_value( $pk_column ); // Set primary keys value(s).
					} else {
						// Form submitted a new record: use new value (no old value available).
						if ( $this->calling_form->get_new_value( $pk_column ) === '' ) {
							wp_die( __( 'ERROR: Wrong arguments [missing primary key value]', 'wp-data-access' ) );
						}
						$where[ $pk_column ] = $this->calling_form->get_new_value( $pk_column ); // Set primary keys value(s).
					}
				}

				// Table is located in WordPress schema.
				$result = $wpdadb->update(
					$this->table_name,
					$column_values_to_be_updated,
					$where
				); // db call ok; no-cache ok.

				if ( 1 === $result ) {
					// Since we are updating by key, result must be exactly 1 record.
					$msg = new WPDA_Message_Box(
						[
							'message_text' => $this->wpda_success_msg,
						]
					);
					$msg->box();
				} else {
					// An error occurred.
					$msg = new WPDA_Message_Box(
						[
							'message_text'           => $this->wpda_failure_msg . $wpdadb->last_error ? '' : ' [' . $wpdadb->last_error . ']',
							'message_type'           => 'error',
							'message_is_dismissible' => false,
						]
					);
					$msg->box();
				}
			}

		}

	}

}
