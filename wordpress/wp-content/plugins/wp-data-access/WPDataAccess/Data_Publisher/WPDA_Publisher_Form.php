<?php

namespace WPDataAccess\Data_Publisher {

	use WPDataAccess\Data_Dictionary\WPDA_Dictionary_Lists;
	use WPDataAccess\Data_Dictionary\WPDA_List_Columns_Cache;
	use WPDataAccess\Data_Tables\WPDA_Data_Tables;
	use WPDataAccess\Simple_Form\WPDA_Simple_Form;
	use WPDataAccess\Simple_Form\WPDA_Simple_Form_Item_Enum;
	use WPDataAccess\Simple_Form\WPDA_Simple_Form_Item_Set;
	use WPDataAccess\WPDA;

	/**
	 * Class WPDA_Publisher_Form extends WPDA_Simple_Form
	 *
	 * Data entry form which allows users to create, update and test publications. A publication consists of a database
	 * table, a number of columns and some options. A shortcode can be generated for a publication. The shortcode can
	 * be copied to the clipboard and from there pasted in a WordPress post or page. The shortcode is used to add a
	 * dynamic HTML table to a post or page that supports searching, pagination and sorting. Tables are created with
	 * jQuery DataTables.
	 *
	 * @author  Peter Schulz
	 * @since   2.0.15
	 */
	class WPDA_Publisher_Form extends WPDA_Simple_Form {

		protected $database_tables = [];

		/**
		 * WPDA_Publisher_Form constructor.
		 *
		 * @param string $schema_name Database schema name
		 * @param string $table_name Database table name
		 * @param object $wpda_list_columns Handle to instance of WPDA_List_Columns
		 * @param array  $args
		 */
		public function __construct( $schema_name, $table_name, &$wpda_list_columns, $args = [] ) {
			// Add column labels.
			$args['column_headers'] = [
				'pub_id'                      => __( 'Pub ID', 'wp-data-accesss' ),
				'pub_name'                    => __( 'Publication Name', 'wp-data-accesss' ),
				'pub_schema_name'             => __( 'Database', 'wp-data-access' ),
				'pub_table_name'              => __( 'Table Name', 'wp-data-accesss' ),
				'pub_column_names'            => __( 'Column Names (* = all)', 'wp-data-accesss' ),
				'pub_format'                  => __( 'Column Labels', 'wp-data-accesss' ),
				'pub_responsive'              => __( 'Output', 'wp-data-accesss' ),
				'pub_responsive_popup_title'  => __( 'Popup Title', 'wp-data-accesss' ),
				'pub_responsive_cols'         => __( 'Number Of Columns', 'wp-data-accesss' ),
				'pub_responsive_type'         => __( 'Type', 'wp-data-accesss' ),
				'pub_responsive_icon'         => __( 'Show Icon', 'wp-data-accesss' ),
				'pub_default_where'           => __( 'WHERE Clause', 'wp-data-access' ),
				'pub_default_orderby'         => __( 'Default order/by', 'wp-data-access' ),
				'pub_table_options_searching' => __( 'Allow searching?', 'wp-data-access' ),
				'pub_table_options_ordering'  => __( 'Allow ordering?', 'wp-data-access' ),
				'pub_table_options_paging'    => __( 'Allow paging?', 'wp-data-access' ),
				'pub_table_options_advanced'  => __( 'Table options (advanced)', 'wp-data-access' ),
			];

			$this->check_table_type = false;

			parent::__construct( $schema_name, $table_name, $wpda_list_columns, $args );

			$this->title = __( 'Data Publisher', 'wp-data-access' );
		}

		/**
		 * Overwrites method add_buttons
		 */
		public function add_buttons() {
			$index       = $this->get_item_index( 'pub_id' );
			$pub_id_item = $this->form_items[ $index ];
			$pub_id      = $pub_id_item->get_item_value();
			?>
			<a href="javascript:void(0)"
			   onclick="jQuery('#data_publisher_test_container_<?php echo esc_html( $pub_id ); ?>').toggle()"
			   class="button"><?php echo __( 'Test Publication', 'wp-data-access' ); ?></a>
			<a href="javascript:void(0)"
			   onclick='prompt("<?php echo __( 'Publication Shortcode', 'wp-data-access' ); ?>", "[wpdataaccess pub_id=\"<?php echo $pub_id; ?>\"]")'
			   class="button"><?php echo __( 'Show Shortcode', 'wp-data-access' ); ?></a>
			<a href="javascript:void(0)" id="button-copy-to-clipboard"
			   class="button"><?php echo __( 'Copy Shortcode', 'wp-data-access' ); ?></a>
			<script type='text/javascript'>
				jQuery(document).ready(function () {
					var text_to_clipboard = new ClipboardJS("#button-copy-to-clipboard", {
						text: function () {
							clipboard_text = "[wpdataaccess pub_id=\"<?php echo $pub_id; ?>\"]";
							return clipboard_text;
						}
					});
					text_to_clipboard.on('success', function (e) {
						alert('<?php echo __( 'Shortcode successfully copied to clipboard!' ); ?>');
					});
					text_to_clipboard.on('error', function (e) {
						console.log('<?php echo __( 'Could not copy shortcode to clipboard!' ); ?>');
					});
				});
			</script>
			<?php
		}

		/**
		 * Overwrites method prepare_items
		 *
		 * @param bool $set_back_form_values
		 */
		public function prepare_items( $set_back_form_values = false ) {
			parent::prepare_items( $set_back_form_values );

			global $wpdb;

			// Get available databases
			$schema_names = WPDA_Dictionary_Lists::get_db_schemas();
			$databases    = [];
			foreach ( $schema_names as $schema_name ) {
				array_push( $databases, $schema_name['schema_name'] );

				// Check table access to prepare table listbox content
				if ( $wpdb->dbname === $schema_name['schema_name'] ) {
					$table_access = WPDA::get_option( WPDA::OPTION_FE_TABLE_ACCESS );
				} else {
					$table_access = get_option( WPDA::FRONTEND_OPTIONNAME_DATABASE_ACCESS . $schema_name['schema_name'] );
					if ( false === $table_access ) {
						$table_access = 'select';
					}
				}
				switch ( $table_access ) {
					case 'show':
						$tables = $this->get_all_db_tables( $schema_name['schema_name'] );
						break;
					case 'hide':
						$tables = $this->get_all_db_tables( $schema_name['schema_name'] );
						// Remove WordPress tables from listbox content
						$tables_named = [];
						foreach ( $tables as $table ) {
							$tables_named[ $table ] = true;
						}
						foreach ( $wpdb->tables( 'all', true ) as $wp_table ) {
							unset( $tables_named[ $wp_table ] );
						}
						$tables = [];
						foreach ( $tables_named as $key => $value ) {
							array_push( $tables, $key );
						}
						break;
					default:
						// Show only selected tables and views
						if ( $wpdb->dbname === $schema_name['schema_name'] ) {
							$tables = WPDA::get_option( WPDA::OPTION_FE_TABLE_ACCESS_SELECTED );
						} else {
							$tables = get_option( WPDA::FRONTEND_OPTIONNAME_DATABASE_SELECTED . $schema_name['schema_name'] );
							if ( false === $tables ) {
								$tables = '';
							}
						}
				}
				$this->database_tables[ $schema_name['schema_name'] ] = $tables;
			}

			$tables       = [];
			$column_index = $this->get_item_index( 'pub_schema_name' );
			if ( false !== $column_index ) {
				$pub_schema_name = $this->form_items[ $column_index ]->get_item_value();
				if ( '' === $pub_schema_name ) {
					$pub_schema_name = $wpdb->dbname;
				}
				if ( isset( $this->database_tables[ $pub_schema_name ] ) ) {
					$tables = $this->database_tables[ $pub_schema_name ];
				}
			}

			$i = 0;
			foreach ( $this->form_items as $form_item ) {
				// Prepare listbox for column pub_schema_name
				if ( $form_item->get_item_name() === 'pub_schema_name' ) {
					if ( '' === $form_item->get_item_value() ) {
						$form_item->set_item_value( $wpdb->dbname );
					}
					$form_item->set_enum( $databases );
					$this->form_items[ $i ] = new WPDA_Simple_Form_Item_Enum( $form_item );
				}

				// Prepare listbox for column pub_table_name
				if ( $form_item->get_item_name() === 'pub_table_name' ) {
					$form_item->set_enum( $tables );
					$form_item->set_item_js(
						'jQuery("#pub_table_name").parent().parent().find("td.icon").append(
							"<a class=\'button\' href=\'javascript:void(0)\' title=\'' .
							__( "No tables listed? Grant access in front-end settings! Click to see how...", "wp-data-access" ) . '\' ' .
							'onclick=\'window.open(\"https://wpdataaccess.com/docs/documentation/data-publisher/how-to-setup-and-use-the-data-publisher/\", \"_blank\")\'>' .
							__( '?', 'wp-data-access' ) .
							'</a>"
						);'
					);
					$this->form_items[ $i ] = new WPDA_Simple_Form_Item_Enum( $form_item );
				}

				// Set default value for popup title
				if ( $form_item->get_item_name() === 'pub_responsive_popup_title' ) {
					$form_item->set_item_default_value( __( 'Row details', 'wp-data-access' ) );
				}

				// Prepare listbox for column pub_responsive
				if ( $form_item->get_item_name() === 'pub_responsive' ) {
					$form_item->set_enum( [ 'Responsive', 'Flat' ] );
					$form_item->set_enum_options( [ 'Yes', 'No' ] );
				}

				// Prepare selection for column pub_column_names
				if ( $form_item->get_item_name() === 'pub_column_names' ) {
					$form_item->set_item_hide_icon( true );
					$form_item->set_item_js(
						'jQuery("#pub_column_names").parent().parent().find("td.icon").append("<a id=\'select_columns\' class=\'button\' href=\'javascript:void(0)\' onclick=\'select_columns()\'>' .
						__( 'Select', 'wp-data-access' ) .
						'</a>");'
					);
				}

				// Prepare column label settings
				if ( $form_item->get_item_name() === 'pub_format' ) {
					$form_item->set_item_hide_icon( true );
					$form_item->set_item_class( 'hide_item' );
					$form_item->set_item_js(
						'jQuery("#pub_format").parent().parent().find("td.data").append("<a id=\'format_columns\' class=\'button\' href=\'javascript:void(0)\' onclick=\'format_columns()\'>' .
						__( 'Click to define column labels', 'wp-data-access' ) .
						'</a>");'
					);
				}

				if ( 'pub_table_options_searching' === $form_item->get_item_name() ||
				     'pub_table_options_ordering' === $form_item->get_item_name() ||
				     'pub_table_options_paging' === $form_item->get_item_name()
				) {
					$form_item->set_enum( [ 'Yes', 'No' ] );
					$form_item->set_enum_options( [ 'on', 'off' ] );
					$this->form_items[ $i ] = new WPDA_Simple_Form_Item_Enum( $form_item );
				}

				$i ++;
			}
		}

		/**
		 * Get all db tables and views
		 *
		 * @param string $database Database schema name
		 *
		 * @return array
		 */
		protected function get_all_db_tables( $database ) {
			$tables    = [];
			$db_tables = WPDA_Dictionary_Lists::get_tables( true, $database ); // select all db tables and views
			foreach ( $db_tables as $db_table ) {
				array_push( $tables, $db_table['table_name'] ); // add table or view to array
			}

			return $tables;
		}

		/**
		 * Overwrites method show
		 *
		 * @param bool   $allow_save
		 * @param string $add_param
		 */
		public function show( $allow_save = true, $add_param = '' ) {
			parent::show( $allow_save, $add_param );

			$index       = $this->get_item_index( 'pub_id' );
			$pub_id_item = $this->form_items[ $index ];
			$pub_id      = $pub_id_item->get_item_value();

			$index            = $this->get_item_index( 'pub_schema_name' );
			$schema_name_item = $this->form_items[ $index ];
			$schema_name      = $schema_name_item->get_item_value();

			$index           = $this->get_item_index( 'pub_table_name' );
			$table_name_item = $this->form_items[ $index ];
			$table_name      = $table_name_item->get_item_value();

			$table_columns = WPDA_List_Columns_Cache::get_list_columns( $schema_name, $table_name );
			$columns       = [];
			foreach ( $table_columns->get_table_columns() as $table_column ) {
				array_push( $columns, $table_column['column_name'] );
			}

			$column_labels = $table_columns->get_table_column_headers();
			?>
			<script type='text/javascript'>
				var database_tables = new Object();
				<?php
				foreach ( $this->database_tables as $key => $value ) {
					echo "database_tables['$key'] = " . json_encode( $value ) . ";";
				}
				?>

				function set_responsive_columns() {
					if (jQuery('#pub_responsive').val() == 'Yes') {
						jQuery('#pub_responsive_popup_title').parent().parent().show();
						jQuery('#pub_responsive_cols').parent().parent().show();
						jQuery('#pub_responsive_type').parent().parent().show();
						jQuery('#pub_responsive_icon').parent().parent().show();
					} else {
						jQuery('#pub_responsive_popup_title').parent().parent().hide();
						jQuery('#pub_responsive_cols').parent().parent().hide();
						jQuery('#pub_responsive_type').parent().parent().hide();
						jQuery('#pub_responsive_icon').parent().parent().hide();
					}
				}

				set_responsive_columns();

				jQuery(document).ready(function () {
					jQuery('[name="pub_schema_name"]').on('change', function () {
						jQuery('[name="pub_table_name"]').empty();
						var tables = database_tables[jQuery(this).val()];
						for (var i = 0; i < tables.length; i++) {
							jQuery('<option/>', {
								value: tables[i],
								html: tables[i]
							}).appendTo('[name="pub_table_name"]');
						}
						jQuery('#pub_column_names').val('*');
						jQuery('#pub_format').val('');
						table_columns = [];
					});

					jQuery('[name="pub_table_name"]').on('change', function () {
						jQuery('#pub_column_names').val('*');
						jQuery('#pub_format').val('');
						table_columns = [];
					});

					jQuery('#pub_default_where').parent().parent().find('.icon').empty().append('<span title="Enter a valid sql where clause\nExample: name like \'Peter%\'" class="button">?</span>');
					jQuery('#pub_default_orderby').parent().parent().find('.icon').empty().append('<span title="Format: column number, direction | ...\nExample: 3,desc|5,asc" class="button">?</span>');
					jQuery('#pub_table_options_advanced').parent().parent().find('.icon').empty().append('<span title=\'Format: {"option":"value","option2","value2"}\' class="button">?</span> <a href="https://datatables.net/reference/option/" target="_blank" title="Click to check jQuery DataTables website for available\noptions (opens in a new tab or window)" class="dashicons dashicons-external" style="margin-top:5px;"></a>');

					<?php if ( 'view' === $this->action ) { ?>
					jQuery('#format_columns').prop("readonly", true).prop("disabled", true).addClass("disabled");
					jQuery('#select_columns').prop("readonly", true).prop("disabled", true).addClass("disabled");
					<?php } ?>

					jQuery('#pub_responsive').on('change', function () {
						set_responsive_columns();
					});
				});

				var no_cols_selected = '* (= show all columns)';

				var table_columns = [];
				<?php
				foreach ( $columns as $column ) {
				?>
				table_columns.push('<?php echo $column; ?>');
				<?php
				}
				?>

				function select_available(e) {
					var option = jQuery("#columns_available option:selected");
					var add_to = jQuery("#columns_selected");

					option.remove();
					new_option = add_to.append(option);

					if (jQuery("#columns_selected option[value='*']").length > 0) {
						// Remove ALL from selected list.
						jQuery("#columns_selected option[value='*']").remove();
					}

					jQuery('select#columns_selected option').removeAttr("selected");
				}

				function select_selected(e) {
					var option = jQuery("#columns_selected option:selected");
					if (option[0].value === '*') {
						// Cannot remove ALL.
						return;
					}

					var add_to = jQuery("#columns_available");

					option.remove();
					add_to.append(option);

					if (jQuery('select#columns_selected option').length === 0) {
						jQuery("#columns_selected").append(jQuery('<option></option>').attr('value', '*').text(no_cols_selected));
					}

					jQuery('select#columns_available option').removeAttr("selected");
				}

				function select_columns(e) {
					if (!(Array.isArray(table_columns) && table_columns.length)) {
						alert("<?php echo __( 'To select columns you need to save your publication first', 'wp-data-access' ); ?>");
						return;
					}

					var columns_available = jQuery(
						'<select id="columns_available" name="columns_available[]" multiple size="8" style="width:200px" onchange="select_available()">' +
						'</select>'
					);
					jQuery.each(table_columns, function (i, val) {
						columns_available.append(jQuery('<option></option>').attr('value', val).text(val));
					});

					var currently_select_option = '';
					var currently_select_values = jQuery('#pub_column_names').val();
					if (currently_select_values == '*') {
						currently_select_values = [];
					} else {
						currently_select_values = currently_select_values.split(',');
					}
					if (currently_select_values.length === 0) {
						currently_select_option = '<option value="*">' + no_cols_selected + '</option>';
					} else {
						for (var i = 0; i < currently_select_values.length; i++) {
							currently_select_option += '<option value="' + currently_select_values[i] + '">' + currently_select_values[i] + '</option>';
						}
					}

					var columns_selected = jQuery(
						'<select id="columns_selected" name="columns_selected[]" multiple size="8" style="width:200px" onchange="select_selected()">' +
						currently_select_option +
						'</select>'
					);

					var dialog_table = jQuery('<table style="width:410px"></table>');

					var dialog_table_row_available = dialog_table.append(jQuery('<tr></tr>').append(jQuery('<td width="50%"></td>')));
					dialog_table_row_available.append(columns_available);

					var dialog_table_row_selected = dialog_table.append(jQuery('<tr></tr>').append(jQuery('<td width="50%"></td>')));
					dialog_table_row_selected.append(columns_selected);

					var dialog_text = jQuery('<div style="width:410px"></div>');
					var dialog = jQuery('<div></div>');

					dialog.append(dialog_text);
					dialog.append(dialog_table);

					jQuery(dialog).dialog(
						{
							dialogClass: 'wp-dialog no-close',
							title: 'Add column(s) to publication',
							modal: true,
							autoOpen: true,
							closeOnEscape: false,
							resizable: false,
							width: 'auto',
							buttons: {
								"OK": function () {
									var selected_columns = '';
									jQuery("#columns_selected option").each(
										function () {
											selected_columns += jQuery(this).val() + ',';
										}
									);
									if (selected_columns !== '') {
										selected_columns = selected_columns.slice(0, -1);
									}
									jQuery('#pub_column_names').val(selected_columns);
									jQuery(this).dialog('destroy').remove();
								},
								"Cancel": function () {
									jQuery(this).dialog('destroy').remove();
								}
							}
						}
					);
				}

				function format_columns() {
					if (!(Array.isArray(table_columns) && table_columns.length)) {
						alert("<?php echo __( 'To format columns you need to save your publication first', 'wp-data-access' ); ?>");
						return;
					}

					var pub_format_json_string = jQuery('#pub_format').val();

					var columns_labels = [];

					if (pub_format_json_string !== '') {
						// Use previously defined formatting
						var pub_format = JSON.parse(pub_format_json_string);
						if (typeof pub_format['pub_format']['column_labels'] !== 'undefined') {
							columns_labels = pub_format['pub_format']['column_labels'];
						}
					} else {
						// Get column labels from table settings
						columns_labels = <?php echo json_encode( $column_labels ); ?>;
					}

					var dialog_table = jQuery('<table></table>');
					dialog_table.append(
						jQuery('<tr></tr>').append(
							jQuery('<th style="text-align:left;"><?php echo __( 'Column Name', 'wp-data-access' ); ?></th>'),
							jQuery('<th style="text-align:left;"><?php echo __( 'Column Label', 'wp-data-access' ); ?></th>'),
						)
					);

					<?php
					foreach ( $table_columns->get_table_columns() as $table_column ) {
					?>
					columns_label = '<?php echo esc_attr( $table_column['column_name'] ); ?>';
					if (typeof columns_labels !== 'undefined') {
						if (columns_label in columns_labels) {
							columns_label = columns_labels[columns_label];
						}
					}
					dialog_table.append(
						jQuery('<tr></tr>').append(
							jQuery('<td style="text-align:left;"><?php echo esc_attr( $table_column['column_name'] ); ?></td>'),
							jQuery('<td style="text-align:left;"><input type="text" class="column_label" name="<?php echo esc_attr( $table_column['column_name'] ); ?>" value="' + columns_label + '"></td>'),
						)
					);
					<?php
					}
					?>

					var dialog_text = jQuery('<div></div>');
					var dialog = jQuery('<div id="define_column_labels"></div>');

					dialog.append(dialog_text);
					dialog.append(dialog_table);

					jQuery(dialog).dialog(
						{
							dialogClass: 'wp-dialog no-close',
							title: 'Define column labels',
							modal: true,
							autoOpen: true,
							closeOnEscape: false,
							resizable: false,
							width: 'auto',
							buttons: {
								"OK": function () {
									// Create JSON from defined column labels
									var column_labels = {};
									jQuery('.column_label').each(
										function () {
											column_labels[jQuery(this).attr('name')] = jQuery(this).val();
										}
									);

									// Write JSON to column pub_format
									pub_format = {
										"pub_format": {
											"column_labels": column_labels
										}
									};
									jQuery('#pub_format').val(JSON.stringify(pub_format));
									jQuery(this).dialog('destroy').remove();
								},
								"Cancel": function () {
									jQuery(this).dialog('destroy').remove();
								}
							}
						}
					);
				}
			</script>
			<?php
			self::show_publication( $pub_id, $table_name );
		}

		public static function show_publication( $pub_id, $table_name ) {
			$datatables_enabled            = WPDA::get_option( WPDA::OPTION_BE_LOAD_DATATABLES ) === 'on';
			$datatables_responsive_enabled = WPDA::get_option( WPDA::OPTION_BE_LOAD_DATATABLES_RESPONSE ) === 'on';

			if ( ! $datatables_enabled || ! $datatables_responsive_enabled ) {
				$publication =
					'<strong>' . __( 'ERROR: Cannot test publication', 'wp-data-access' ) . '</strong><br/><br/>' .
					__( 'SOLUTION: Load jQuery DataTables: WP Data Access > Manage Plugin > Back-End Settings', 'wp-data-access' );
			} else {
				$wpda_data_tables = new WPDA_Data_Tables();
				$publication      = $wpda_data_tables->show( $pub_id, '', '', '', '', '', '', '', '', '' );
			}
			?>
			<div id="data_publisher_test_container_<?php echo esc_html( $pub_id ); ?>">
				<style>
					#data_publisher_test_header_<?php echo esc_html( $pub_id); ?> {
						background-color: #ccc;
						padding: 10px;
						margin-bottom: 10px;
					}

					#data_publisher_test_container_<?php echo esc_html( $pub_id); ?> {
						display: none;
						padding: 10px;
						position: absolute;
						top: 30px;
						left: 10px;
						color: black;
						overflow-y: auto;
						background-color: white;
						border: 1px solid #ccc;
						width: max-content;
					}
				</style>
				<div id="data_publisher_test_header_<?php echo esc_html( $pub_id ); ?>">
					<span><strong><?php echo __( 'Test Publication', 'wp-data-access' ); ?> (pub_id=<?php echo $pub_id; ?>)</strong></span>
					<span class="button" style="float:right;"
						  onclick="jQuery('#data_publisher_test_container_<?php echo esc_html( $pub_id ); ?>').hide()">x</span><br/>
					<?php echo __( 'Publication might look different on your website', 'wp-data-access' ); ?>
				</div>
				<?php echo $publication; ?>
			</div>
			<script type='text/javascript'>
				jQuery("#data_publisher_test_container_<?php echo esc_html( $pub_id ); ?>").appendTo("#wpbody-content");
			</script>
			<?php
		}
	}

}