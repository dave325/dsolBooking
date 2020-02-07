<?php

/**
 * Suppress "error - 0 - No summary was found for this file" on phpdoc generation
 *
 * @package WPDataAccess\Settings
 */

namespace WPDataAccess\Settings {

	use WPDataAccess\Connection\WPDADB;
	use WPDataAccess\Data_Dictionary\WPDA_Dictionary_Exist;
	use WPDataAccess\Data_Dictionary\WPDA_Dictionary_Lists;
	use WPDataAccess\Plugin_Table_Models\WPDA_Design_Table_Model;
	use WPDataAccess\Plugin_Table_Models\WPDA_Logging_Model;
	use WPDataAccess\Plugin_Table_Models\WPDA_Table_Settings_Model;
	use WPDataAccess\Plugin_Table_Models\WPDA_User_Menus_Model;
	use WPDataAccess\Plugin_Table_Models\WPDA_Publisher_Model;
	use WPDataAccess\Plugin_Table_Models\WPDA_Media_Model;
	use WPDataAccess\Plugin_Table_Models\WPDP_Page_Model;
	use WPDataAccess\Plugin_Table_Models\WPDP_Project_Model;
	use WPDataAccess\Plugin_Table_Models\WPDP_Project_Design_Table_Model;
	use WPDataAccess\Utilities\WPDA_Import;
	use WPDataAccess\Utilities\WPDA_Message_Box;
	use WPDataAccess\Utilities\WPDA_Repository;
	use WPDataAccess\WPDA;

	/**
	 * Class WPDA_Settings
	 *
	 * The following plugin settings are supported through this class, each having its own tab:
	 * + Back-end Settings
	 * + Front-end Settings
	 * + Data Publisher Settings
	 * + Data Backup Settings
	 * + Uninstall Settings
	 * + Manage Repository
	 *
	 * All tabs have the following similar structure:
	 * + If form was posted save options (show success or error message)
	 * + Read options
	 * + Show form with options for selected tab
	 *
	 * Tabs Back-end Settings, Front-end Settings, Data Backup Settings and Uninstall Settings have reset buttons. When
	 * the reset button on a specific tab is clicked, the default values for the settings on that tab are taken from
	 * WPDA and stored in $pwdb->options.
	 *
	 * When the users clicks on tab Manage Repository, the repository is validated and the status of the repository
	 * is shown. If the repository has errors, a button is offered to recreate the repository.
	 *
	 * @author  Peter Schulz
	 * @since   1.0.0
	 */
	class WPDA_Settings {

		// Dropbox app client id and secret (necessary for registration)
		const DROPBOX_CLIENT_ID     = 'f6e7znb7qfwaqjh'; // 'rv5japeynhpzmyy';
		const DROPBOX_CLIENT_SECRET = '0vzaidexrtcede4'; // 'v45glikrzr6h62z';

		// jQuery DataTables language settings
		// DO NOT CHANGE THESE LANGUAGES!!!!
		// The language text is used in a URL. Changing a language results in a 404 for that language.
		const FRONTEND_LANG = [
			'Afrikaans',
			'Albanian',
			'Amharic',
			'Arabic',
			'Armenian',
			'Azerbaijan',
			'Bangla',
			'Basque',
			'Belarusian',
			'Bulgarian',
			'Catalan',
			'Chinese',
			'Chinese-traditional',
			'Croatian',
			'Czech',
			'Danish',
			'Dutch',
			'English',
			'Esperanto',
			'Estonian',
			'Filipino',
			'Finnish',
			'French',
			'Galician',
			'Georgian',
			'German',
			'Greek',
			'Gujarati',
			'Hebrew',
			'Hindi',
			'Hungarian',
			'Icelandic',
			'Indonesian',
			'Indonesian-Alternative',
			'Irish',
			'Italian',
			'Japanese',
			'Kazakh',
			'Khmer',
			'Korean',
			'Kurdish',
			'Kyrgyz',
			'Lao',
			'Latvian',
			'Lithuanian',
			'Macedonian',
			'Malay',
			'Mongolian',
			'Nepali',
			'Norwegian-Bokmal',
			'Norwegian-Nynorsk',
			'Pashto',
			'Persian',
			'Polish',
			'Portuguese',
			'Portuguese-Brasil',
			'Romanian',
			'Russian',
			'Serbian',
			'Serbian_latin',
			'Sinhala',
			'Slovak',
			'Slovenian',
			'Spanish',
			'Swahili',
			'Swedish',
			'Tajik',
			'Tamil',
			'telugu',
			'Thai',
			'Turkish',
			'Ukrainian',
			'Urdu',
			'Uzbek',
			'Vietnamese',
			'Welsh',
		];

		/**
		 * Menu slug of the current page
		 *
		 * @var string
		 */
		protected $page;

		/**
		 * Available tabs on the page
		 *
		 * @var array
		 */
		protected $tabs;

		/**
		 * Current tab name
		 *
		 * @var string
		 */
		protected $current_tab;

		/**
		 * Reference to wpda import object
		 *
		 * @var WPDA_Import
		 */
		protected $wpda_import;

		/**
		 * WPDA_Settings constructor
		 *
		 * Member $this->tabs is filled in the constructor to support i18n.
		 *
		 * If a request was send for recreation of the repository, this is done in the constructor. This action must
		 * be performed before checking the user menu model, which is part of the constructor as well, necessary to
		 * inform the user if any errors were reported.
		 *
		 * @since   1.0.0
		 */
		public function __construct() {

			// Get menu slag of current page.
			if ( isset( $_REQUEST['page'] ) ) {
				$this->page = sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ); // input var okay.
			} else {
				// In order to show a list table we need a page.
				wp_die( __( 'ERROR: Wrong arguments [missing page argument]', 'wp-data-access' ) );
			}

			// Tabs array is filled in constructor to add i18n.
			$this->tabs = [
				'plugin'        => __( 'Plugin', 'wp-data-access' ),
				'backend'       => __( 'Back-end', 'wp-data-access' ),
				'frontend'      => __( 'Front-end', 'wp-data-access' ),
				'datapublisher' => __( 'Data Publisher', 'wp-data-access' ),
				'databackup'    => __( 'Data Backup', 'wp-data-access' ),
				'uninstall'     => __( 'Uninstall', 'wp-data-access' ),
				'repository'    => __( 'Manage Repository', 'wp-data-access' ),
				'roles'         => __( 'Manage Roles', 'wp-data-access' ),
				'system'        => __( 'System Info', 'wp-data-access' ),
			];

			// Set default tab.
			$this->current_tab = 'plugin';
			if ( isset( $_REQUEST['tab'] ) ) {

				if ( isset( $this->tabs[ $_REQUEST['tab'] ] ) ) {

					// Set requested tab (if value doesn't exist, default tab will be shown).
					$this->current_tab = sanitize_text_field( wp_unslash( $_REQUEST['tab'] ) ); // input var okay.

				}
			}

			// Recreation of repository must be performed before checking the availability of menu items (done next).
			if ( 'repository' === $this->current_tab && isset( $_REQUEST['repos'] ) && // input var okay.
			     'true' === sanitize_text_field( wp_unslash( $_REQUEST['repos'] ) ) ) { // input var okay.

				// Security check.
				$wp_nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : ''; // input var okay.
				if ( ! wp_verify_nonce( $wp_nonce, 'wpda-settings-recreate-repository' ) ) {
					wp_die( __( 'ERROR: Not authorized', 'wp-data-access' ) );
				}

				// Recreate repository.
				$wpda_repository = new WPDA_Repository();
				$wpda_repository->recreate();
				WPDA::set_option( WPDA::OPTION_WPDA_SETUP_ERROR ); // Set to default.
				WPDA::set_option( WPDA::OPTION_WPDA_SHOW_WHATS_NEW ); // Set to default.

				$msg = new WPDA_Message_Box(
					[
						'message_text' => __( 'Repository recreation completed', 'wp-data-access' ),
					]
				);
				$msg->box();
			}

			// Inform the user if status repository invalid.
			$wpda_repository = new WPDA_Repository();
			$wpda_repository->inform_user();

		}

		/**
		 * Show setting page
		 *
		 * Consists of tabs {@see WPDA_Settings::add_tabs()} and the content of the selected tab
		 * {@see WPDA_Settings::add_content()}.
		 *
		 * @since   1.0.0
		 *
		 * @see WPDA_Settings::add_tabs()
		 * @see WPDA_Settings::add_content()
		 */
		public function show() {

			?>

			<div class="wrap">
				<h1>
					<?php echo __( 'WP Data Access Settings', 'wp-data-access' ); ?>
					<a href="<?php echo 'https://wpdataaccess.com/docs/documentation/'; ?>" target="_blank" title="Plugin Help - open a new tab or window">
					<span class="dashicons dashicons-editor-help"
						  style="text-decoration:none;vertical-align:top;font-size:36px;">
						</span></a>
				</h1>

				<?php

				$this->add_tabs();
				$this->add_content();

				?>

			</div>

			<?php

		}

		/**
		 * Add tabs to page
		 *
		 * @since   1.0.0
		 */
		protected function add_tabs() {

			?>

			<h2 class="nav-tab-wrapper">

				<?php

				foreach ( $this->tabs as $tab => $name ) {

					$class = ( $tab === $this->current_tab ) ? ' nav-tab-active' : '';
					echo '<a class="nav-tab' . esc_attr( $class ) . '" href="?page=' . esc_attr( $this->page ) .
					     '&tab=' . esc_attr( $tab ) . '">' . esc_attr( $name ) . '</a>';

				}

				?>

			</h2>

			<?php

		}

		/**
		 * Add content to page
		 *
		 * @since   1.0.0
		 */
		protected function add_content() {

			switch ( $this->current_tab ) {

				case 'plugin':
					$this->add_content_plugin();
					break;

				case 'frontend':
					$this->add_content_frontend();
					break;

				case 'datapublisher':
					$this->add_content_datapublisher();
					break;

				case 'databackup':
					$this->add_content_databackup();
					break;

				case 'uninstall':
					$this->add_content_uninstall();
					break;

				case 'repository':
					$this->add_content_repository();
					break;

				case 'roles':
					$this->add_content_roles();
					break;

				case 'system':
					$this->add_content_system();
					break;

				default:
					// Back-end settings is shown by default.
					$this->add_content_backend();

			}

		}

		protected function add_content_plugin() {
			wp_enqueue_style( 'datetimepicker' );
			wp_enqueue_script( 'datetimepicker' );

			if ( isset( $_REQUEST['action'] ) ) {
				$action = sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ); // input var okay.

				// Security check.
				if ( 'delete_remote_database' === $action ) {
					$wp_nonce = isset( $_REQUEST['_wpnoncedelrdb'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnoncedelrdb'] ) ) : ''; // input var okay.
					if ( ! wp_verify_nonce( $wp_nonce, 'wpda-delete-remote-database' ) ) {
						wp_die( __( 'ERROR: Not authorized', 'wp-data-access' ) );
					}

					if ( isset( $_REQUEST['remote_database_name'] ) ) {
						$remote_database_name = sanitize_text_field( wp_unslash( $_REQUEST['remote_database_name'] ) ); // input var okay.
						WPDADB::del_remote_database( $remote_database_name );
					}
				}	elseif ( 'update_remote_database' === $action ) {
					$wp_nonce = isset( $_REQUEST['_wpnonceupdrdb'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonceupdrdb'] ) ) : ''; // input var okay.
					if ( ! wp_verify_nonce( $wp_nonce, 'wpda-update-remote-database' ) ) {
						wp_die( __( 'ERROR: Not authorized', 'wp-data-access' ) );
					}

					$database_old = isset( $_REQUEST['remote_database_old'] ) ?
						sanitize_text_field( wp_unslash( $_REQUEST['remote_database_old'] ) ) : ''; // input var okay.
					$database     = isset( $_REQUEST['remote_database'] ) ?
						sanitize_text_field( wp_unslash( $_REQUEST['remote_database'] ) ) : ''; // input var okay.
					$host         = isset( $_REQUEST['remote_host'] ) ?
						sanitize_text_field( wp_unslash( $_REQUEST['remote_host'] ) ) : ''; // input var okay.
					$username     = isset( $_REQUEST['remote_user'] ) ?
						sanitize_text_field( wp_unslash( $_REQUEST['remote_user'] ) ) : ''; // input var okay.
					$password     = isset( $_REQUEST['remote_passwd'] ) ?
						sanitize_text_field( wp_unslash( $_REQUEST['remote_passwd'] ) ) : ''; // input var okay.
					$port         = isset( $_REQUEST['remote_port'] ) ?
						sanitize_text_field( wp_unslash( $_REQUEST['remote_port'] ) ) : ''; // input var okay.
					$schema       = isset( $_REQUEST['remote_schema'] ) ?
						sanitize_text_field( wp_unslash( $_REQUEST['remote_schema'] ) ) : ''; // input var okay.
					$enabled      = isset( $_REQUEST['remote_enabled'] ) ? true : false; // input var okay.

					if ( '' === $database_old || '' === $database || '' === $host || '' === $username || '' === $password || '' === $port || '' === $schema || '' === $enabled ) {
						$msg = new WPDA_Message_Box(
							[
								'message_text'           => sprintf( __( 'Cannot save remote database connection [missing arguments]', 'wp-data-access' ) ),
								'message_type'           => 'error',
								'message_is_dismissible' => false,
							]
						);
						$msg->box();
					} else {
						WPDADB::upd_remote_database(
							$database,
							$host,
							$username,
							$password,
							$port,
							$schema,
							!$enabled,
							$database_old
						);
					}
				} else {
					$wp_nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : ''; // input var okay.
					if ( ! wp_verify_nonce( $wp_nonce, 'wpda-plugin-settings' ) ) {
						wp_die( __( 'ERROR: Not authorized', 'wp-data-access' ) );
					}

					if ( 'save' === $action ) {
						// Save options.
						WPDA::set_option(
							WPDA::OPTION_PLUGIN_HIDE_ADMIN_MENU,
							isset( $_REQUEST['hide_admin_menu'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['hide_admin_menu'] ) ) : 'off' // input var okay.
						);

						if ( isset( $_REQUEST['panel_cookies'] ) ) {
							WPDA::set_option(
								WPDA::OPTION_PLUGIN_PANEL_COOKIES,
								sanitize_text_field( wp_unslash( $_REQUEST['panel_cookies'] ) ) // input var okay.
							);
						}

						if ( ! isset( $_REQUEST['secret_key'] ) || ! isset( $_REQUEST['secret_iv'] ) ) {
							// Leave both values untouched
						} else {
							$secret_key_old = WPDA::get_option( WPDA::OPTION_PLUGIN_SECRET_KEY );
							$secret_iv_old  = WPDA::get_option( WPDA::OPTION_PLUGIN_SECRET_IV );

							$secret_key_new = sanitize_text_field( wp_unslash( $_REQUEST['secret_key'] ) ); // input var okay.
							$secret_iv_new  = sanitize_text_field( wp_unslash( $_REQUEST['secret_iv'] ) ); // input var okay.

							if ( $secret_key_old !== $secret_key_new || $secret_iv_old !== $secret_iv_new ) {
								// Update existing remote databases
								WPDADB::load_remote_databases(); // load remote databases with old secret key and iv
								WPDA::set_option( WPDA::OPTION_PLUGIN_SECRET_KEY, $secret_key_new ); // update secret key
								WPDA::set_option( WPDA::OPTION_PLUGIN_SECRET_IV, $secret_iv_new ); // update secret iv
								WPDADB::save_remote_databases(); // save remote databases with new secret key and iv
							}
						}

						if ( isset( $_REQUEST['remote_database_name'] ) && isset( $_REQUEST['remote_database_enabled'] ) ) {
							if ( is_array( $_REQUEST['remote_database_name'] ) &&
								 is_array( $_REQUEST['remote_database_enabled'] ) &&
								 count( $_REQUEST['remote_database_name'] ) === count( $_REQUEST['remote_database_enabled'] )
							) {
								$i            = 0;
								while ( $i < count( $_REQUEST['remote_database_name'] ) ) {
									$rdb_name = sanitize_text_field( wp_unslash( $_REQUEST['remote_database_name'][$i] ) );
									$dbs = WPDADB::get_remote_database( $rdb_name, true );
									if ( ! $dbs ) {
										$msg = new WPDA_Message_Box(
											[
												'message_text'           => __( 'Remote database connection not found', 'wp-data-access' ),
												'message_type'           => 'error',
												'message_is_dismissible' => false,
											]
										);
										$msg->box();
									} else {
										WPDADB::upd_remote_database(
											$rdb_name,
											$dbs['host'],
											$dbs['username'],
											$dbs['password'],
											$dbs['port'],
											$dbs['database'],
											$_REQUEST['remote_database_enabled'][$i] === 'FALSE'
										);
									}
									$i++;
								}
								WPDADB::save_remote_databases(); // save changes
							}
						}

						WPDA::set_option(
							WPDA::OPTION_PLUGIN_WPDATAACCESS_POST,
							isset( $_REQUEST['wpdataaccess_post'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wpdataaccess_post'] ) ) : 'off' // input var okay.
						);

						WPDA::set_option(
							WPDA::OPTION_PLUGIN_WPDATAACCESS_PAGE,
							isset( $_REQUEST['wpdataaccess_page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wpdataaccess_page'] ) ) : 'off' // input var okay.
						);

						WPDA::set_option(
							WPDA::OPTION_PLUGIN_WPDADIEHARD_POST,
							isset( $_REQUEST['wpdadiehard_post'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wpdadiehard_post'] ) ) : 'off' // input var okay.
						);

						WPDA::set_option(
							WPDA::OPTION_PLUGIN_WPDADIEHARD_PAGE,
							isset( $_REQUEST['wpdadiehard_page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['wpdadiehard_page'] ) ) : 'off' // input var okay.
						);

						if ( isset( $_REQUEST['date_format'] ) ) {
							WPDA::set_option(
								WPDA::OPTION_PLUGIN_DATE_FORMAT,
								sanitize_text_field( wp_unslash( $_REQUEST['date_format'] ) ) // input var okay.
							);
						}

						if ( isset( $_REQUEST['date_placeholder'] ) ) {
							WPDA::set_option(
								WPDA::OPTION_PLUGIN_DATE_PLACEHOLDER,
								sanitize_text_field( wp_unslash( $_REQUEST['date_placeholder'] ) ) // input var okay.
							);
						}

						if ( isset( $_REQUEST['time_format'] ) ) {
							WPDA::set_option(
								WPDA::OPTION_PLUGIN_TIME_FORMAT,
								sanitize_text_field( wp_unslash( $_REQUEST['time_format'] ) ) // input var okay.
							);
						}

						if ( isset( $_REQUEST['time_placeholder'] ) ) {
							WPDA::set_option(
								WPDA::OPTION_PLUGIN_TIME_PLACEHOLDER,
								sanitize_text_field( wp_unslash( $_REQUEST['time_placeholder'] ) ) // input var okay.
							);
						}
					} elseif ( 'setdefaults' === $action ) {
						// Set all back-end settings back to default.
						WPDA::set_option( WPDA::OPTION_PLUGIN_HIDE_ADMIN_MENU );

						WPDA::set_option( WPDA::OPTION_PLUGIN_PANEL_COOKIES );

						// DO NOT RESET SECRET KEY AND IV

						// DO NOT RESET RDBs

						WPDA::set_option( WPDA::OPTION_PLUGIN_WPDATAACCESS_POST );
						WPDA::set_option( WPDA::OPTION_PLUGIN_WPDATAACCESS_PAGE );
						WPDA::set_option( WPDA::OPTION_PLUGIN_WPDADIEHARD_POST );
						WPDA::set_option( WPDA::OPTION_PLUGIN_WPDADIEHARD_PAGE );

						WPDA::set_option( WPDA::OPTION_PLUGIN_DATE_FORMAT );
						WPDA::set_option( WPDA::OPTION_PLUGIN_DATE_PLACEHOLDER );
						WPDA::set_option( WPDA::OPTION_PLUGIN_TIME_FORMAT );
						WPDA::set_option( WPDA::OPTION_PLUGIN_TIME_PLACEHOLDER );
					}
				}

				$msg = new WPDA_Message_Box(
					[
						'message_text' => __( 'Settings saved', 'wp-data-access' ),
					]
				);
				$msg->box();
			}

			// Get options.
			$hide_admin_menu = WPDA::get_option( WPDA::OPTION_PLUGIN_HIDE_ADMIN_MENU );

			$panel_cookies = WPDA::get_option( WPDA::OPTION_PLUGIN_PANEL_COOKIES );

			$secret_key = WPDA::get_option( WPDA::OPTION_PLUGIN_SECRET_KEY );
			$secret_iv  = WPDA::get_option( WPDA::OPTION_PLUGIN_SECRET_IV );

			$wpdataaccess_post = WPDA::get_option( WPDA::OPTION_PLUGIN_WPDATAACCESS_POST );
			$wpdataaccess_page = WPDA::get_option( WPDA::OPTION_PLUGIN_WPDATAACCESS_PAGE );
			$wpdadiehard_post  = WPDA::get_option( WPDA::OPTION_PLUGIN_WPDADIEHARD_POST );
			$wpdadiehard_page  = WPDA::get_option( WPDA::OPTION_PLUGIN_WPDADIEHARD_PAGE );

			$date_format      = WPDA::get_option( WPDA::OPTION_PLUGIN_DATE_FORMAT );
			$date_placeholder = WPDA::get_option( WPDA::OPTION_PLUGIN_DATE_PLACEHOLDER );
			$time_format      = WPDA::get_option( WPDA::OPTION_PLUGIN_TIME_FORMAT );
			$time_placeholder = WPDA::get_option( WPDA::OPTION_PLUGIN_TIME_PLACEHOLDER );

			$remote_databases = WPDADB::get_remote_databases( true );
			?>
			<style type="text/css">
				.settings_line {
					line-height: 2.4;
				}

				.settings_label {
					display: inline-block;
					width: 7em;
					font-weight: bold;
				}

				.item_width {
					width: 14em;
				}

				.item_label {
					width: 14.9em;
					display: inline-block;
					padding-left: 0.3em;
				}

				.item_label_text {
					width: 7em;
					display: inline-block;
				}

				.item_label_format {
					width: 5em;
					padding: 0.6em;
					border-radius: 4px;
				}

				.item_label_align {
					float: right;
				}

				#wpda_update_database_popup {
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

				#wpda_update_database_popup_header {
					background-color: #ccc;
					height: 30px;
					padding: 10px;
					margin-bottom: 10px;
				}
			</style>
			<script type='text/javascript'>
				jQuery(document).ready(function () {
					jQuery('.radio_date_format').on('click', function() {
						jQuery('#date_format').val(jQuery(this).val());
					});

					jQuery('.radio_time_format').on('click', function() {
						jQuery('#time_format').val(jQuery(this).val());
					});

					jQuery.datetimepicker.setLocale('<?php echo substr( get_locale(), 0, 2 ); ?>');
					jQuery('#test_datetime').attr('autocomplete', 'off');
					jQuery('#init_datetime').on('click', function() {
						jQuery('#test_datetime').datetimepicker({
							format: jQuery('#date_format').val() + ' ' + jQuery('#time_format').val(),
							datepicker: true,
							timepicker: true
						});
						jQuery('#init_datetime').toggle();
						jQuery('#test_datetime').toggle();
						jQuery('#test_datetime').val('');
						jQuery('#test_datetime').attr('placeholder', jQuery('#date_placeholder').val() + ' ' + jQuery('#time_placeholder').val());
					});
					jQuery('#test_datetime').on('blur', function() {
						jQuery('#test_datetime').toggle();
						jQuery('#init_datetime').toggle();
					});
				});

				function delete_remote_database(id) {
					remote_database_name = jQuery('#remote_database_name' + id).val();
					if (confirm("<?php echo __( 'Delete remote database connection from plugin repository?', 'wp-data-access' ); ?>")) {
						jQuery('#delete_remote_database_name').val(remote_database_name);
						jQuery('#wpda_delete_database').submit();
					}
				}

				function update_rdb_setting(id) {
					if (jQuery('#remote_database' + id).is(':checked')) {
						jQuery('#remote_database_enabled' + id).val('TRUE');
					} else {
						jQuery('#remote_database_enabled' + id).val('FALSE');
					}
				}

				function edit_rdb_setting(id) {
					jQuery('#wpda_update_database_popup').show();
					jQuery('#remote_database_old').val(id);
					jQuery('#remote_database').val(id);
					jQuery('#remote_host').val(remote_databases[id].host);
					jQuery('#remote_user').val(remote_databases[id].username);
					jQuery('#remote_passwd').val(remote_databases[id].password);
					jQuery('#remote_port').val(remote_databases[id].port);
					jQuery('#remote_schema').val(remote_databases[id].database);
					jQuery('#remote_enabled').prop('checked', !remote_databases[id].disabled);
				}

				function test_remote_clear(mode = '') {
					jQuery('#' + mode + 'remote_database_block_test_content').html('');
					jQuery('#' + mode + 'remote_database_block_test').hide();
					jQuery('#' + mode + 'remote_clear_button').hide();
				}

				function test_remote_connection(mode = '') {
					host = jQuery('#remote_host').val();
					user = jQuery('#remote_user').val();
					pass = jQuery('#remote_passwd').val();
					port = jQuery('#remote_port').val();
					dbs = jQuery('#remote_schema').val();

					url = '//' + window.location.host + window.location.pathname.replace('options-general','admin') +
						'?action=check_remote_database_connection';

					jQuery('#remote_test_button').val('Testing...');

					jQuery.ajax({
						method: 'POST',
						url: url,
						data: {
							host: host,
							user: user,
							passwd: pass,
							port: port,
							schema: dbs
						}
					}).success(
						function (msg) {
							jQuery('#remote_database_block_test_content').html(msg);
							jQuery('#remote_database_block_test').show();
						}
					).error(
						function () {
							jQuery('#remote_database_block_test_content').html('Preparing connection...<br/>Establishing connection...<br/><br/><strong>Remote database connection invalid</strong>');
							jQuery('#remote_database_block_test').show();
						}
					).complete(
						function () {
							jQuery('#remote_test_button').val('Test');
							jQuery('#remote_clear_button').show();
						}
					);
				}

				jQuery(document).ready(function () {
					jQuery('#remote_database').keydown(function(e) {
						var field = this;
						setTimeout(function () {
							if (field.value.indexOf('rdb:') !== 0) {
								jQuery(field).val('rdb:');
							}
						}, 1);
					});
				});

				var remote_databases = new Object();
				<?php
				foreach ( $remote_databases as $key => $value ) {
					echo "remote_databases['$key'] = " . json_encode( $value ) . ";";
				}
				?>
			</script>
			<div id="wpda_update_database_popup">
				<div id="wpda_update_database_popup_header">
					<span style="display:inline-block;margin-top:5px;">
						<strong>
							<?php echo __( 'Edit Remote Database Connection', 'wp-data-access' ); ?>
						</strong>
					</span>
					<span class="button" style="float:right;height:10px;"
						  onclick="jQuery('#wpda_update_database_popup').hide()">x</span><br/>
				</div>

				<form id="wpda_update_database" method="post"
					  action="?page=<?php echo esc_attr( $this->page ); ?>&tab=plugin">

					<div>
						<label for="remote_database" style="vertical-align:baseline;"
							   class="database_item_label">Database name:</label>
						<input type="text" name="remote_database" id="remote_database" value="rdb:">
						<span>(local WordPress dashboard)</span>
						<div style="height:10px;"></div>
						<label for="remote_host" style="vertical-align:baseline;" class="database_item_label">MySQL
							host:</label>
						<input type="text" name="remote_host" id="remote_host" maxlength="64">
						<span>(ip address or hostname)</span>
						<br/>
						<label for="remote_user" style="vertical-align:baseline;" class="database_item_label">MySQL
							username:</label>
						<input type="text" name="remote_user" id="remote_user">
						<br/>
						<label for="remote_passwd" style="vertical-align:baseline;" class="database_item_label">MySQL
							password:</label>
						<input type="text" name="remote_passwd" id="remote_passwd">
						<br/>
						<label for="remote_port" style="vertical-align:baseline;" class="database_item_label">MySQL
							port:</label>
						<input type="text" name="remote_port" id="remote_port" value="3306">
						<br/>
						<label for="remote_schema" style="vertical-align:baseline;" class="database_item_label">MySQL
							schema:</label>
						<input type="text" name="remote_schema" id="remote_schema">
						<br/>
						<label for="remote_schema" style="vertical-align:baseline;" class="database_item_label"></label>
						<label><input type="checkbox" name="remote_enabled" id="remote_enabled"><?php echo __( 'Enabled', 'wp-data-access' ); ?></label>
						<div style="height:10px;"></div>
						<label class="database_item_label"></label>
						<input type="button" value="Test" onclick="test_remote_connection(); return false;"
							   id="remote_test_button" class="button">
						<input type="button" value="Clear" onclick="test_remote_clear(); return false;"
							   id="remote_clear_button" class="button" style="display:none;">
						<div style="height:10px;"></div>
					</div>
					<div id="remote_database_block_test" style="display:none;">
						<div id="remote_database_block_test_content"
							 class="remote_database_block_test_content"></div>
						<div style="height:10px;"></div>
					</div>
					<input type="hidden" name="remote_database_old" id="remote_database_old" value=""">
					<input type="hidden" name="action" value="update_remote_database"/>
					<input type="submit" class="button button-secondary" value="<?php echo __( 'Save', 'wp-data-access' ); ?>">
					<a href="javascript:void(0)"
					   onclick="jQuery('#wpda_update_database_popup').hide()"
					   class="button button-secondary">
						<?php echo __( 'Cancel', 'wp-data-access' ); ?>
					</a>
					<?php
					$rdb_wp_nonce_action   = 'wpda-update-remote-database';
					$rdb_wp_nonce          = wp_create_nonce( $rdb_wp_nonce_action );
					?>
					<input type="hidden" name="_wpnonceupdrdb" value="<?php echo $rdb_wp_nonce; ?>"/>
				</form>
			</div>
			<form id="wpda_delete_database" method="post" style="display:none;"
				  action="?page=<?php echo esc_attr( $this->page ); ?>&tab=plugin">
				<input type="hidden" name="remote_database_name" id="delete_remote_database_name" value=""/>
				<input type="hidden" name="action" value="delete_remote_database"/>
				<?php
				$rdb_wp_nonce_action   = 'wpda-delete-remote-database';
				$rdb_wp_nonce          = wp_create_nonce( $rdb_wp_nonce_action );
				?>
				<input type="hidden" name="_wpnoncedelrdb" value="<?php echo $rdb_wp_nonce; ?>"/>
			</form>
			<form id="wpda_settings_plugin" method="post"
				  action="?page=<?php echo esc_attr( $this->page ); ?>&tab=plugin">
				<table class="wpda-table-settings" id="wpda_table_plugin">
					<tr>
						<th><?php echo __( 'Plugin menu', 'wp-data-access' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="hide_admin_menu" <?php echo 'on'===$hide_admin_menu ? 'checked="checked"' : ''; ?>/>
								<?php echo __( 'Hide plugin menu in admin panel', 'wp-data-access' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th><?php echo __( 'Panel cookies', 'wp-data-access' ); ?></th>
						<td>
							<label>
								<input
										type="radio"
										name="panel_cookies"
										value="clear"
									<?php echo 'clear' === $panel_cookies ? 'checked' : ''; ?>
								><?php echo __( 'Clear when switching panels', 'wp-data-access' ); ?>
							</label>
							<br/>
							<label>
								<input
										type="radio"
										name="panel_cookies"
										value="keep"
									<?php echo 'keep' === $panel_cookies ? 'checked' : ''; ?>
								><?php echo __( 'Keep when switching panels', 'wp-data-access' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th><?php echo __( 'Secret key and IV' ); ?></th>
						<td>
							<input type="text" name="secret_key" value="<?php echo $secret_key; ?>"/>
							<br/>
							<input type="text" name="secret_iv" value="<?php echo $secret_iv; ?>"/>
							<br/><br/>
							<span class="dashicons dashicons-info"></span><?php echo __( 'Existing remote database connection settings will be converted', 'wp-data-access' ); ?>
						</td>
					</tr>
					<tr>
						<th><?php echo __( 'Remote database connections' ); ?></th>
						<td>
							<?php
							$i = 0;
							foreach ( $remote_databases as $remote_database => $remote_database_settings ) {
								$checked = isset( $remote_database_settings['disabled'] ) && $remote_database_settings['disabled'] ? '' : 'checked';
								$enabled = isset( $remote_database_settings['disabled'] ) && $remote_database_settings['disabled'] ? 'FALSE' : 'TRUE';
								?>
								<a href="javascript:void(0)"
								   onclick="delete_remote_database('<?php echo $i; ?>')"
								   style="text-decoration:none;"
								   title="<?php echo __( 'Delete remote database connection from plugin repository', 'wp-data-acces' ); ?>">
									<span class="dashicons dashicons-trash" style="font-size:18px;"></span>
								</a>
								<label title="<?php echo __( 'Disable remote database connection', 'wp-data-acces' ); ?>">
									<input type="checkbox" name="remote_database[]" id="remote_database<?php echo $i; ?>" onclick="update_rdb_setting('<?php echo $i; ?>')" <?php echo $checked; ?>>
									<input type="hidden" name="remote_database_name[]" id="remote_database_name<?php echo $i; ?>" value="<?php echo esc_attr( $remote_database ); ?>">
									<input type="hidden" name="remote_database_enabled[]" id="remote_database_enabled<?php echo $i; ?>" value="<?php echo $enabled; ?>">
									<?php echo esc_attr( $remote_database ); ?>
								</label>
								<a href="javascript:void(0)"
								   onclick="edit_rdb_setting('<?php echo esc_attr( $remote_database ); ?>')"
								   style="text-decoration:none;"
								   title="<?php echo __( 'Edit remote database connection', 'wp-data-acces' ); ?>">
									<span class="dashicons dashicons-edit" style="font-size:18px;"></span>
								</a><br/>
								<?php
								$i++;
							}
							?>
						</td>
					</tr>
					<tr>
						<th><?php echo __( 'Shortcode [WPDATAACCESS]' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="wpdataaccess_post" <?php echo 'on'===$wpdataaccess_post ? 'checked="checked"' : ''; ?>/>
								Allow in posts
							</label>
							<br/>
							<label>
								<input type="checkbox" name="wpdataaccess_page" <?php echo 'on'===$wpdataaccess_page ? 'checked="checked"' : ''; ?>/>
								Allow in pages
							</label>
						</td>
					</tr>
					<tr>
						<th><?php echo __( 'Shortcode [WPDADIEHARD]' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="wpdadiehard_post" <?php echo 'on'===$wpdadiehard_post ? 'checked="checked"' : ''; ?>/>
								Allow in posts
							</label>
							<br/>
							<label>
								<input type="checkbox" name="wpdadiehard_page" <?php echo 'on'===$wpdadiehard_page ? 'checked="checked"' : ''; ?>/>
								Allow in pages
							</label>
						</td>
					</tr>
					<tr>
						<th><?php echo __( 'Date format' ); ?></th>
						<td>
							<span class="settings_label"><?php echo __( 'Ouput', 'wp-data-access' ); ?></span>
							<input type="text" value="<?php echo get_option( 'date_format' ); ?>" class="item_width"
								   readonly/>
							<?php echo __( '(WordPress format)', 'wp-data-access' ); ?>
							<br/>
							<span class="settings_line">
								<span class="settings_label"><?php echo __( 'Input', 'wp-data-access' ); ?></span>
								<label class="item_label">
									<input type="radio" name="radio_date_format" class="radio_date_format"
										   value="Y-m-d" <?php echo "Y-m-d" === $date_format ? 'checked="checked"' : ''; ?>/>
									<span class="item_label_text"><?php echo ( new \DateTime() )->format( 'Y-m-d' ); ?></span>
									<span class="item_label_align">
										<input type="text" class="item_label_format" value="Y-m-d" readonly/>
									</span>
								</label>
							</span>
							<?php echo __( '(JavaScript format)', 'wp-data-access' ); ?>
							<br/>
							<span class="settings_line">
								<span class="settings_label"></span>
								<label class="item_label">
									<input type="radio" name="radio_date_format" class="radio_date_format"
										   value="m/d/Y" <?php echo "m/d/Y" === $date_format ? 'checked="checked"' : ''; ?>/>
									<span class="item_label_text"><?php echo ( new \DateTime() )->format( 'm/d/Y' ); ?></span>
									<span class="item_label_align">
										<input type="text" class="item_label_format" value="m/d/Y" readonly/>
									</span>
								</label>
							</span>
							<br/>
							<span class="settings_line">
								<span class="settings_label"></span>
								<label class="item_label">
									<input type="radio" name="radio_date_format" class="radio_date_format"
										   value="d/m/Y" <?php echo "d/m/Y" === $date_format ? 'checked="checked"' : ''; ?>/>
									<span class="item_label_text"><?php echo ( new \DateTime() )->format( 'd/m/Y' ); ?></span>
									<span class="item_label_align">
										<input type="text" class="item_label_format" value="d/m/Y" readonly/>
									</span>
								</label>
							</span>
							<br/>
							<span class="settings_line">
								<span class="settings_label"></span>
								<label class="item_label">
									<input type="radio" name="radio_date_format" name="date_format"
										   value="custom" <?php echo "Y-m-d" !== $date_format && "d/m/Y" !== $date_format && "m/d/Y" !== $date_format ? 'checked="checked"' : ''; ?>/>
									<span class="item_label_text"><?php echo __( 'Custom:', 'wp-data-access' ); ?></span>
									<span class="item_label_align">
										<input class="item_label_format" type="text" name="date_format" id="date_format"
											   value="<?php echo esc_attr( $date_format ); ?>" class="item_label_format"/>
									</span>
								</label>
							</span>
							<br/>
							<span class="settings_label"><?php echo __( 'Placeholder', 'wp-data-access' ); ?></span>
							<input type="text" name="date_placeholder" id="date_placeholder"
								   value="<?php echo esc_attr( $date_placeholder ); ?>" class="item_width"/>
							<?php echo __( '(user info)', 'wp-data-access' ); ?>
						</td>
					</tr>
					<tr>
						<th><?php echo __( 'Time format' ); ?></th>
						<td>
							<span class="settings_label"><?php echo __( 'Output', 'wp-data-access' ); ?></span>
							<input type="text" value="<?php echo get_option( 'time_format' ); ?>" class="item_width"
								   readonly/>
							<?php echo __( '(WordPress format)', 'wp-data-access' ); ?>
							<br/>
							<span class="settings_line">
								<span class="settings_label"><?php echo __( 'Input', 'wp-data-access' ); ?></span>
								<label class="item_label">
									<input type="radio" name="radio_time_format" class="radio_time_format"
										   value="H:i" <?php echo "H:i" === $time_format ? 'checked="checked"' : ''; ?>/>
									<span class="item_label_text"><?php echo ( new \DateTime() )->format( 'H:i' ); ?></span>
									<span class="item_label_align">
										<input type="text" class="item_label_format" value="H:i" readonly/>
									</span>
								</label>
							</span>
							<?php echo __( '(JavaScript format)', 'wp-data-access' ); ?>
							<br/>
							<span class="settings_line">
								<span class="settings_label"></span>
								<label class="item_label">
									<input type="radio" name="radio_time_format" name="time_format"
										   value="custom" <?php echo "H:i" !== $time_format ? 'checked="checked"' : ''; ?>/>
									<span class="item_label_text"><?php echo __( 'Custom:', 'wp-data-access' ); ?></span>
									<span class="item_label_align">
										<input class="item_label_format" type="text" name="time_format" id="time_format"
											   value="<?php echo esc_attr( $time_format ); ?>" class="item_label_format"/>
									</span>
								</label>
							</span>
							<br/>
							<span class="settings_label"><?php echo __( 'Placeholder', 'wp-data-access' ); ?></span>
							<input type="text" name="time_placeholder" id="time_placeholder"
								   value="<?php echo esc_attr( $time_placeholder ); ?>" class="item_width"/>
						</td>
					</tr>
					<tr>
						<th><?php echo __( 'Date/time test' ); ?></th>
						<td>
							<input type="button" id="init_datetime" value="Test DataTimePicker" class="button item_width"/>
							<input type="text" class="item_width" id="test_datetime" style="display:none;" />
						</td>
					</tr>
					<tr>
						<th><span class="dashicons dashicons-info" style="float:right;font-size:300%;"></span></th>
						<td>
							<span class="dashicons dashicons-yes"></span>
							<?php echo __( 'The plugin uses your WordPress general settings to format your date and time output', 'wp-data-access' ); ?>
							<br/>
							<span class="dashicons dashicons-yes"></span>
							<a href="/wp-admin/options-general.php">
								<?php echo __( 'Output formats can be changed in WordPress general settings', 'wp-data-access' ); ?>
							</a>
							<br/>
							<span class="dashicons dashicons-yes"></span>
							<?php echo __( 'The plugin uses the jQuery DateTimePicker plugin for data entry validation', 'wp-data-access' ); ?>
							<br/>
							<span class="dashicons dashicons-yes"></span>
							<a href="https://xdsoft.net/jqplugins/datetimepicker/" target="_blank">
								<?php echo __( 'Input formats can be found on the XDSoft DateTimePicker page', 'wp-data-access' ); ?>
							</a>
						</td>
					</tr>
				</table>
				<div class="wpda-table-settings-button">
					<input type="hidden" name="action" value="save"/>
					<input type="submit" value="<?php echo __( 'Save Plugin Settings', 'wp-data-access' ); ?>"
						   class="button button-primary"/>
					<a href="javascript:void(0)"
					   onclick="if (confirm('<?php echo __( 'Reset to defaults?', 'wp-data-access' ); ?>')) {
							   jQuery('input[name=&quot;action&quot;]').val('setdefaults');
							   jQuery('#wpda_settings_plugin').trigger('submit')
							   }"
					   class="button">
						<?php echo __( 'Reset Plugin Settings To Defaults', 'wp-data-access' ); ?>
					</a>
				</div>
				<?php wp_nonce_field( 'wpda-plugin-settings', '_wpnonce', false ); ?>
			</form>
			<?php
		}

		/**
		 * Add system info tab content
		 *
		 * See class documentation for flow explanation.
		 *
		 * @since   2.0.13
		 */
		protected function add_content_system() {
			global $wpdb;
			global $wp_version;

			$uploads = wp_get_upload_dir();

			$menus_table_name        = WPDA_User_Menus_Model::get_base_table_name();
			$menus_table_name_exists = WPDA_User_Menus_Model::table_exists();

			$design_table_name        = WPDA_Design_Table_Model::get_base_table_name();
			$design_table_name_exists = WPDA_Design_Table_Model::table_exists();

			$logging_table_name   = WPDA_Logging_Model::get_base_table_name();
			$logging_table_exists = WPDA_Logging_Model::table_exists();

			$data_projects_project_name        = WPDP_Project_Model::get_base_table_name();
			$data_projects_project_name_exists = WPDP_Project_Model::table_exists();

			$data_projects_page_name        = WPDP_Page_Model::get_base_table_name();
			$data_projects_page_name_exists = WPDP_Page_Model::table_exists();

			$data_projects_table_name        = WPDP_Project_Design_Table_Model::get_base_table_name();
			$data_projects_table_name_exists = WPDP_Project_Design_Table_Model::table_exists();

			$media_table_name   = WPDA_Media_Model::get_base_table_name();
			$media_table_exists = WPDA_Media_Model::table_exists();

			$data_publication_table_name        = WPDA_Publisher_Model::get_base_table_name();
			$data_publication_table_name_exists = WPDA_Publisher_Model::table_exists();

			$table_settings_table_name   = WPDA_Table_Settings_Model::get_base_table_name();
			$table_settings_table_exists = WPDA_Table_Settings_Model::table_exists();

			// Check table characteristics.
			$query                           =
				"select table_name AS table_name, engine AS engine, table_collation AS table_collation " .
				"from information_schema.tables " .
				"where table_schema = '{$wpdb->dbname}' " .
				"and table_name in " .
				"('$menus_table_name', '$design_table_name', '$logging_table_name', " .
				"'$data_projects_project_name', '$data_projects_page_name', '$data_projects_table_name', " .
				"'$media_table_name', '$data_publication_table_name', '$table_settings_table_name')";
			$table_chararteristics_results   = $wpdb->get_results( $query, 'ARRAY_A' );
			$table_chararteristics_engine    = [];
			$table_chararteristics_collation = [];
			if ( false !== $table_chararteristics_results ) {
				foreach ( $table_chararteristics_results as $table_chararteristics_result ) {
					$table_chararteristics_engine[ $table_chararteristics_result['table_name'] ]    = $table_chararteristics_result['engine'];
					$table_chararteristics_collation[ $table_chararteristics_result['table_name'] ] = $table_chararteristics_result['table_collation'];
				}
			}
			?>
			<style>
				.wpda-table-system-info th {
					font-style: italic;
					font-weight: normal;
					padding: 0;
				}

				.wpda-table-system-info td {
					padding: 0;
				}

				.wpda-table-settings tr:nth-child(even) {
					background: unset;
				}
			</style>
			<script type='text/javascript'>
				jQuery(document).ready(function () {
					var text_to_clipboard = new ClipboardJS("#button-copy-to-clipboard", {
						text: function () {
							clipboard_text = "";
							jQuery("#wpda_table_info tr .wpda_system_info_title").each(function () {
								clipboard_text += jQuery(this).text().trim() + "\n";
								jQuery(this).parent().find("th.wpda_system_info_subtitle").each(function () {
									clipboard_text += jQuery(this).text().trim();
									clipboard_text += "=";
									clipboard_text += jQuery(this).parent().find("td.wpda_system_info_value").text().trim() + "\n";
								});
							});
							return clipboard_text;
						}
					});
					text_to_clipboard.on('success', function (e) {
						alert('<?php echo __( 'System info successfully copied to clipboard!' ); ?>');
					});
					text_to_clipboard.on('error', function (e) {
						console.log('<?php echo __( 'Could not copy system info to clipboard!' ); ?>');
					});
				});
			</script>
			<table class="wpda-table-settings" id="wpda_table_info">
				<tr>
					<th class="wpda_system_info_title"><?php echo __( 'Operating System' ); ?></th>
					<td>
						<table class="wpda-table-system-info" style="width:100%">
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Type' ); ?></th>
								<td class="wpda_system_info_value">
									<?php echo php_uname( 's' ); ?>
								</td>
								<td style="float:right">
									<a id="button-copy-to-clipboard" href="javascript:void(0)"
									   class="button button-primary">
										<?php echo __( 'Copy to clipboard' ); ?>
									</a>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Release' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo php_uname( 'r' ); ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Version' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo php_uname( 'v' ); ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Machine Type' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo php_uname( 'm' ); ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Host Name' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo php_uname( 'n' ); ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th class="wpda_system_info_title"><?php echo __( 'Database Management System' ); ?></th>
					<td>
						<table class="wpda-table-system-info" style="width:100%">
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Version' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php
									$db_version = $wpdb->get_results( "SHOW VARIABLES LIKE 'version'", 'ARRAY_N' );
									if ( is_array( $db_version ) && isset( $db_version[0][1] ) ) {
										echo $db_version[0][1];
									} else {
										$wpdb->db_version;
									}
									?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Pivileges' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php
									$db_privileges = $wpdb->get_results( 'SHOW PRIVILEGES', 'ARRAY_N' );
									if ( is_array( $db_privileges ) ) {
										$db_privileges_output = '';
										foreach ( $db_privileges as $db_privilege ) {
											$db_privileges_output .= "$db_privilege[0], ";
										}
										echo substr( $db_privileges_output, 0, strlen( $db_privileges_output ) - 2 );
									}
									?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Grants' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php
									$db_grants = $wpdb->get_results( 'SHOW GRANTS', 'ARRAY_N' );
									if ( is_array( $db_grants ) ) {
										$db_grants_output = '';
										foreach ( $db_grants as $db_grant ) {
											$strpos = strpos( $db_grant[0], 'IDENTIFIED BY PASSWORD ' );
											if ( false !== $strpos ) {
												$db_grants_output .= substr( $db_grant[0], 0, $strpos ) . 'IDENTIFIED BY PASSWORD \'*****\'<br/>';
											} else {
												$db_grants_output .= "$db_grant[0]<br/>";
											}
										}
										echo $db_grants_output;
									}
									?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'SQL Mode' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php
									$db_sql_mode = $wpdb->get_results( 'SHOW VARIABLES LIKE \'sql_mode\'', 'ARRAY_N' );
									if ( isset( $db_sql_mode[0][1] ) ) {
										echo $db_sql_mode[0][1];
									}
									?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th class="wpda_system_info_title"><?php echo __( 'Web Server' ); ?></th>
					<td>
						<table class="wpda-table-system-info" style="width:100%">
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Software' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo $_SERVER['SERVER_SOFTWARE']; ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'PHP Version' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo phpversion(); ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Protocol' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo $_SERVER['SERVER_PROTOCOL']; ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Name' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo $_SERVER['SERVER_NAME']; ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Address' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo $_SERVER['SERVER_ADDR']; ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Root DIR' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo $_SERVER['DOCUMENT_ROOT']; ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Temp DIR' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo sys_get_temp_dir(); ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'HTTP Upload' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo @ini_get( 'file_uploads' ) ? 'Enabled' : 'Disabled'; ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Max Upload File Size' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo @ini_get( 'upload_max_filesize' ); ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Post Max Size' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo @ini_get( 'post_max_size' ); ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Max Execution Time' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo @ini_get( 'max_execution_time' ); ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Max Input Time' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo @ini_get( 'max_input_time' ); ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Memory Limit' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo @ini_get( 'memory_limit' ); ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Output Buffering' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo @ini_get( 'output_buffering' ); ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th class="wpda_system_info_title"><?php echo __( 'WordPress' ); ?></th>
					<td>
						<table class="wpda-table-system-info" style="width:100%">
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Version' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo $wp_version; ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Home DIR' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php
									echo get_home_path();

									$error_level = error_reporting();
									error_reporting( E_ALL ^ E_WARNING );
									$file_permission = fileperms( get_home_path() );
									error_reporting( $error_level );
									echo '&nbsp;&nbsp;&nbsp;>&nbsp;&nbsp;&nbsp;' . decoct( $file_permission & 0777 );
									?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Uploads DIR' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo $uploads['basedir'];

									$error_level = error_reporting();
									error_reporting( E_ALL ^ E_WARNING );
									$file_permission = fileperms( $uploads['basedir'] );
									error_reporting( $error_level );
									echo '&nbsp;&nbsp;&nbsp;>&nbsp;&nbsp;&nbsp;' . decoct( $file_permission & 0777 );
									?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Home URL' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo get_home_url(); ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Site URL' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo get_site_url(); ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Upload URL' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo $uploads['baseurl']; ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Use MySQLi' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php
									// Taken from wp-db class
									if ( function_exists( 'mysqli_connect' ) ) {
										$use_mysqli = true;
										if ( defined( 'WP_USE_EXT_MYSQL' ) ) {
											$use_mysqli = ! WP_USE_EXT_MYSQL;
										}
										echo $use_mysqli ? 'true' : 'false';
									}
									?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Database Host' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo DB_HOST; ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Database Name' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo DB_NAME; ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Database User' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo DB_USER; ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Database Character Set' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo DB_CHARSET; ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Database Collate' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo DB_COLLATE; ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'WP Debugging Mode' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo ! defined( 'WP_DEBUG' ) ? 'undefined' : ( true === WP_DEBUG ? 'true' : 'false' ); ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th class="wpda_system_info_title"><?php echo __( 'WP Data Access' ); ?></th>
					<td>
						<table class="wpda-table-system-info" style="width:100%">
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Version' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo WPDA::get_option( WPDA::OPTION_WPDA_VERSION ); ?>
								</td>
							</tr>
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Repository' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php
									echo $menus_table_name_exists ? '+' : '-';
									echo esc_attr( $menus_table_name );
									echo ' (';
									echo isset( $table_chararteristics_engine[ $menus_table_name ] ) ? $table_chararteristics_engine[ $menus_table_name ] : '';
									echo ' | ';
									echo isset( $table_chararteristics_collation[ $menus_table_name ] ) ? $table_chararteristics_collation[ $menus_table_name ] : '';
									echo ')';
									echo ' <br/>';
									echo $design_table_name_exists ? '+' : '-';
									echo esc_attr( $design_table_name );
									echo ' (';
									echo isset( $table_chararteristics_engine[ $design_table_name ] ) ? $table_chararteristics_engine[ $design_table_name ] : '';
									echo ' | ';
									echo isset( $table_chararteristics_collation[ $design_table_name ] ) ? $table_chararteristics_collation[ $design_table_name ] : '';
									echo ')';
									echo ' <br/>';
									echo $logging_table_exists ? '+' : '-';
									echo esc_attr( $logging_table_name );
									echo ' (';
									echo isset( $table_chararteristics_engine[ $logging_table_name ] ) ? $table_chararteristics_engine[ $logging_table_name ] : '';
									echo ' | ';
									echo isset( $table_chararteristics_collation[ $logging_table_name ] ) ? $table_chararteristics_collation[ $logging_table_name ] : '';
									echo ')';
									echo ' <br/>';
									echo $data_projects_project_name_exists ? '+' : '-';
									echo esc_attr( $data_projects_project_name );
									echo ' (';
									echo isset( $table_chararteristics_engine[ $data_projects_project_name ] ) ? $table_chararteristics_engine[ $data_projects_project_name ] : '';
									echo ' | ';
									echo isset( $table_chararteristics_collation[ $data_projects_project_name ] ) ? $table_chararteristics_collation[ $data_projects_project_name ] : '';
									echo ')';
									echo ' <br/>';
									echo $data_projects_page_name_exists ? '+' : '-';
									echo esc_attr( $data_projects_page_name );
									echo ' (';
									echo isset( $table_chararteristics_engine[ $data_projects_page_name ] ) ? $table_chararteristics_engine[ $data_projects_page_name ] : '';
									echo ' | ';
									echo isset( $table_chararteristics_collation[ $data_projects_page_name ] ) ? $table_chararteristics_collation[ $data_projects_page_name ] : '';
									echo ')';
									echo ' <br/>';
									echo $data_projects_table_name_exists ? '+' : '-';
									echo esc_attr( $data_projects_table_name );
									echo ' (';
									echo isset( $table_chararteristics_engine[ $data_projects_table_name ] ) ? $table_chararteristics_engine[ $data_projects_table_name ] : '';
									echo ' | ';
									echo isset( $table_chararteristics_collation[ $data_projects_table_name ] ) ? $table_chararteristics_collation[ $data_projects_table_name ] : '';
									echo ')';
									echo ' <br/>';
									echo $media_table_exists ? '+' : '-';
									echo esc_attr( $media_table_name );
									echo ' (';
									echo isset( $table_chararteristics_engine[ $media_table_name ] ) ? $table_chararteristics_engine[ $media_table_name ] : '';
									echo ' | ';
									echo isset( $table_chararteristics_collation[ $media_table_name ] ) ? $table_chararteristics_collation[ $media_table_name ] : '';
									echo ')';
									echo ' <br/>';
									echo $data_publication_table_name_exists ? '+' : '-';
									echo esc_attr( $data_publication_table_name );
									echo ' (';
									echo isset( $table_chararteristics_engine[ $data_publication_table_name ] ) ? $table_chararteristics_engine[ $data_publication_table_name ] : '';
									echo ' | ';
									echo isset( $table_chararteristics_collation[ $data_publication_table_name ] ) ? $table_chararteristics_collation[ $data_publication_table_name ] : '';
									echo ')';
									echo ' <br/>';
									echo $table_settings_table_exists ? '+' : '-';
									echo esc_attr( $table_settings_table_name );
									echo ' (';
									echo isset( $table_chararteristics_engine[ $table_settings_table_name ] ) ? $table_chararteristics_engine[ $table_settings_table_name ] : '';
									echo ' | ';
									echo isset( $table_chararteristics_collation[ $table_settings_table_name ] ) ? $table_chararteristics_collation[ $table_settings_table_name ] : '';
									echo ')';
									?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<th class="wpda_system_info_title"><?php echo __( 'Browser' ); ?></th>
					<td>
						<table id="wpda_system_info_browser" class="wpda-table-system-info" style="width:100%">
							<tr>
								<th class="wpda_system_info_subtitle"><?php echo __( 'Agent' ); ?></th>
								<td class="wpda_system_info_value" colspan="2">
									<?php echo $_SERVER['HTTP_USER_AGENT']; ?>
								</td>
							</tr>
							<script type='text/javascript'>
								jQuery.each(jQuery.browser, function (i, val) {
									jQuery("#wpda_system_info_browser").append("<tr><th class=\"wpda_system_info_subtitle\">" + i[0].toUpperCase() + i.substring(1).toLowerCase() + "</th><td class=\"wpda_system_info_value\" colspan=\"2\">" + val + "</td></tr>");
								});
							</script>
						</table>
					</td>
				</tr>
			</table>
			<?php
		}

		/**
		 * Add data publisher tab content
		 *
		 * See class documentation for flow explanation.
		 *
		 * @since   2.0.15
		 */
		protected function add_content_datapublisher() {
			if ( isset( $_REQUEST['action'] ) ) {
				$action = sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ); // input var okay.

				// Security check.
				$wp_nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : ''; // input var okay.
				if ( ! wp_verify_nonce( $wp_nonce, 'wpda-publication-settings' ) ) {
					wp_die( __( 'ERROR: Not authorized', 'wp-data-access' ) );
				}

				if ( 'save' === $action ) {
					// Save options.
					if ( isset( $_REQUEST['load_datatables'] ) ) {
						$load_datatables_request = sanitize_text_field( wp_unslash( $_REQUEST['load_datatables'] ) ); // input var okay.

						if ( 'both' === $load_datatables_request || 'be' === $load_datatables_request ) {
							WPDA::set_option( WPDA::OPTION_BE_LOAD_DATATABLES, 'on' );
						} else {
							WPDA::set_option( WPDA::OPTION_BE_LOAD_DATATABLES, 'off' );
						}

						if ( 'both' === $load_datatables_request || 'fe' === $load_datatables_request ) {
							WPDA::set_option( WPDA::OPTION_FE_LOAD_DATATABLES, 'on' );
						} else {
							WPDA::set_option( WPDA::OPTION_FE_LOAD_DATATABLES, 'off' );
						}
					}

					if ( isset( $_REQUEST['load_datatables_responsive'] ) ) {
						$load_datatables_responsive_request = sanitize_text_field( wp_unslash( $_REQUEST['load_datatables_responsive'] ) ); // input var okay.

						if ( 'both' === $load_datatables_responsive_request || 'be' === $load_datatables_responsive_request ) {
							WPDA::set_option( WPDA::OPTION_BE_LOAD_DATATABLES_RESPONSE, 'on' );
						} else {
							WPDA::set_option( WPDA::OPTION_BE_LOAD_DATATABLES_RESPONSE, 'off' );
						}

						if ( 'both' === $load_datatables_responsive_request || 'fe' === $load_datatables_responsive_request ) {
							WPDA::set_option( WPDA::OPTION_FE_LOAD_DATATABLES_RESPONSE, 'on' );
						} else {
							WPDA::set_option( WPDA::OPTION_FE_LOAD_DATATABLES_RESPONSE, 'off' );
						}
					}

					if ( isset( $_REQUEST['publication_roles'] ) ) {
						$publication_roles_request = isset( $_REQUEST['publication_roles'] ) ? $_REQUEST['publication_roles'] : null;
						if ( is_array( $publication_roles_request ) ) {
							$publication_roles = implode( ',', $publication_roles_request );
						} else {
							$publication_roles = '';
						}
					} else {
						$publication_roles = '';
					}
					WPDA::set_option( WPDA::OPTION_DP_PUBLICATION_ROLES, $publication_roles );

					if ( isset( $_REQUEST['language'] ) ) {
						WPDA::set_option(
							WPDA::OPTION_DP_LANGUAGE,
							sanitize_text_field( wp_unslash( $_REQUEST['language'] ) )
						);
					}
				} elseif ( 'setdefaults' === $action ) {
					// Set all publication settings back to default.
					WPDA::set_option( WPDA::OPTION_BE_LOAD_DATATABLES );
					WPDA::set_option( WPDA::OPTION_FE_LOAD_DATATABLES );

					WPDA::set_option( WPDA::OPTION_BE_LOAD_DATATABLES_RESPONSE );
					WPDA::set_option( WPDA::OPTION_FE_LOAD_DATATABLES_RESPONSE );

					WPDA::set_option( WPDA::OPTION_DP_PUBLICATION_ROLES );

					WPDA::set_option( WPDA::OPTION_DP_LANGUAGE );
				}

				$msg = new WPDA_Message_Box(
					[
						'message_text' => __( 'Settings saved', 'wp-data-access' ),
					]
				);
				$msg->box();

			}

			$datatables_version = WPDA::get_option( WPDA::OPTION_WPDA_DATATABLES_VERSION );
			$be_load_datatables = WPDA::get_option( WPDA::OPTION_BE_LOAD_DATATABLES );
			$fe_load_datatables = WPDA::get_option( WPDA::OPTION_FE_LOAD_DATATABLES );
			if ( 'on' === $be_load_datatables && 'on' === $fe_load_datatables ) {
				$load_datatables = 'both';
			} elseif ( 'on' === $be_load_datatables ) {
				$load_datatables = 'be';
			} elseif ( 'on' === $fe_load_datatables ) {
				$load_datatables = 'fe';
			} else {
				$load_datatables = '';
			}

			$datatables_responsive_version = WPDA::get_option( WPDA::OPTION_WPDA_DATATABLES_RESPONSIVE_VERSION );
			$be_load_datatables_responsive = WPDA::get_option( WPDA::OPTION_BE_LOAD_DATATABLES_RESPONSE );
			$fe_load_datatables_responsive = WPDA::get_option( WPDA::OPTION_FE_LOAD_DATATABLES_RESPONSE );
			if ( 'on' === $be_load_datatables_responsive && 'on' === $fe_load_datatables_responsive ) {
				$load_datatables_responsive = 'both';
			} elseif ( 'on' === $be_load_datatables_responsive ) {
				$load_datatables_responsive = 'be';
			} elseif ( 'on' === $fe_load_datatables_responsive ) {
				$load_datatables_responsive = 'fe';
			} else {
				$load_datatables_responsive = '';
			}

			global $wp_roles;
			$lov_roles = [];
			foreach ( $wp_roles->roles as $role => $val ) {
				array_push( $lov_roles, $role );
			}
			$publication_roles = WPDA::get_option( WPDA::OPTION_DP_PUBLICATION_ROLES );

			$current_language = WPDA::get_option( WPDA::OPTION_DP_LANGUAGE );
			?>
			<form id="wpda_settings_publication" method="post"
				  action="?page=<?php echo esc_attr( $this->page ); ?>&tab=datapublisher">
				<table class="wpda-table-settings">
					<tr>
					<tr>
						<th>jQuery DataTables</th>
						<td>
							<label>
								<?php echo sprintf( __( 'Load jQuery DataTables (version %s) scripts and styles', 'wp-data-access' ), esc_attr( $datatables_version ) ); ?>
							</label>
							<div style="height:10px"></div>
							<labeL>
								<input type="radio" name="load_datatables" value="both"
									<?php echo 'both' === $load_datatables ? 'checked' : ''; ?>
								><?php echo __( 'In WordPress Back-end and Frond-end', 'wp-data-access' ); ?>
							</labeL>
							<br/>
							<labeL>
								<input type="radio" name="load_datatables" value="be"
									<?php echo 'be' === $load_datatables ? 'checked' : ''; ?>
								><?php echo __( 'In WordPress Back-end only ', 'wp-data-access' ); ?>
							</labeL>
							<br/>
							<labeL>
								<input type="radio" name="load_datatables" value="fe"
									<?php echo 'fe' === $load_datatables ? 'checked' : ''; ?>
								><?php echo __( 'In WordPress Frond-end only', 'wp-data-access' ); ?>
							</labeL>
							<br/>
							<labeL>
								<input type="radio" name="load_datatables" value=""
									<?php echo '' === $load_datatables ? 'checked' : ''; ?>
								><?php echo __( 'Do not load jQuery DataTables', 'wp-data-access' ); ?>
							</labeL>
						</td>
					</tr>
					<tr>
						<th>jQuery DataTables Responsive</th>
						<td>
							<label>
								<?php echo sprintf( __( 'Load jQuery DataTables Responsive (version %s) scripts and styles', 'wp-data-access' ), esc_attr( $datatables_responsive_version ) ); ?>
							</label>
							<div style="height:10px"></div>
							<labeL>
								<input type="radio" name="load_datatables_responsive" value="both"
									<?php echo 'both' === $load_datatables_responsive ? 'checked' : ''; ?>
								><?php echo __( 'In WordPress Back-end and Frond-end', 'wp-data-access' ); ?>
							</labeL>
							<br/>
							<labeL>
								<input type="radio" name="load_datatables_responsive" value="be"
									<?php echo 'be' === $load_datatables_responsive ? 'checked' : ''; ?>
								><?php echo __( 'In WordPress Back-end only ', 'wp-data-access' ); ?>
							</labeL>
							<br/>
							<labeL>
								<input type="radio" name="load_datatables_responsive" value="fe"
									<?php echo 'fe' === $load_datatables_responsive ? 'checked' : ''; ?>
								><?php echo __( 'In WordPress Frond-end only', 'wp-data-access' ); ?>
							</labeL>
							<br/>
							<labeL>
								<input type="radio" name="load_datatables_responsive" value=""
									<?php echo '' === $load_datatables_responsive ? 'checked' : ''; ?>
								><?php echo __( 'Do not load jQuery DataTables Responsive', 'wp-data-access' ); ?>
							</labeL>
						</td>
					</tr>
					<tr>
						<th><?php echo __( 'Data Publisher Tool Access', 'wp-data-access' ); ?></th>
						<td><?php echo __( 'Select the WordPress roles which should be allowed to have access to the Data Publisher tool', 'wp-data-access' ); ?>
							<div style="height:10px"></div>
							<select name="publication_roles[]" multiple size="6">
								<?php
								foreach ( $lov_roles as $lov_role ) {
									if ( false !== strpos( $publication_roles, $lov_role ) ) {
										$granted = 'selected';
									} else {
										$granted = '';
									}
									?>
									<option value="<?php echo $lov_role; ?>" <?php echo $granted; ?>><?php echo $lov_role; ?></option>
									<?php
								}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<th><?php echo __( 'Front-End Language', 'wp-data-access' ); ?></th>
						<td>
							<select name="language">
								<?php
								foreach ( self::FRONTEND_LANG as $language ) {
									$checked = $current_language === $language ? ' selected' : '';
									echo "<option value='$language'$checked>$language</option>";
								}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<th><span class="dashicons dashicons-info" style="float:right;font-size:300%;"></span></th>
						<td>
							<span class="dashicons dashicons-yes"></span>
							<?php echo __( 'jQuery DataTables (+Responsive) is needed in the Front-end to support publications on your website', 'wp-data-access' ); ?>
							<br/>
							<span class="dashicons dashicons-yes"></span>
							<?php echo __( 'jQuery DataTables (+Responsive) is needed in the Back-end to test publications in the WordPress dashboard', 'wp-data-access' ); ?>
							<br/>
							<span class="dashicons dashicons-yes"></span>
							<?php echo __( 'If you have already loaded jQuery DataTables for other purposes disable loading them to prevent duplication errors', 'wp-data-access' ); ?>
							<br/>
							<span class="dashicons dashicons-yes"></span>
							<?php echo __( 'Users have readonly access to tables to which you have granted access in Front-end Settings only', 'wp-data-access' ); ?>
						</td>
					</tr>
				</table>
				<div class="wpda-table-settings-button">
					<input type="hidden" name="action" value="save"/>
					<input type="submit"
						   value="<?php echo __( 'Save Publication Settings', 'wp-data-access' ); ?>"
						   class="button button-primary"/>
					<a href="javascript:void(0)"
					   onclick="if (confirm('<?php echo __( 'Reset to defaults?', 'wp-data-access' ); ?>')) {
							   jQuery('input[name=&quot;action&quot;]').val('setdefaults');
							   jQuery('#wpda_settings_publication').trigger('submit')
							   }"
					   class="button">
						<?php echo __( 'Reset Publication Settings To Defaults', 'wp-data-access' ); ?>
					</a>
				</div>
				<?php wp_nonce_field( 'wpda-publication-settings', '_wpnonce', false ); ?>
			</form>
			<?php
		}

		/**
		 * Add data backup tab content
		 *
		 * See class documentation for flow explanation.
		 *
		 * @since   2.0.7
		 */
		protected function add_content_databackup() {

			if ( isset( $_REQUEST['action'] ) ) {
				$action = sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ); // input var okay.

				// Security check.
				$wp_nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : ''; // input var okay.
				if ( ! wp_verify_nonce( $wp_nonce, 'wpda-databackup-settings' ) ) {
					wp_die( __( 'ERROR: Not authorized', 'wp-data-access' ) );
				}

				if ( 'save' === $action ) {
					// Save options.
					$save_local_path = isset( $_REQUEST['local_path'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['local_path'] ) ) : ''; // input var okay.
					if ( 'WIN' === strtoupper( substr( PHP_OS, 0, 3 ) ) ) {
						if ( '\\' !== substr( $save_local_path, - 1 ) ) {
							$save_local_path .= '\\';
						}
					} else {
						if ( '/' !== substr( $save_local_path, - 1 ) ) {
							$save_local_path .= '/';
						}
					}
					WPDA::set_option( WPDA::OPTION_DB_LOCAL_PATH, $save_local_path );

					$options_activated = [];
					if ( isset( $_REQUEST['local_path_activated'] ) ) {
						$error_level = error_reporting();
						error_reporting( E_ALL ^ E_WARNING );
						$local_path      = WPDA::get_option( WPDA::OPTION_DB_LOCAL_PATH );
						$file_permission = fileperms( $local_path );
						error_reporting( $error_level );
						if ( $file_permission && '4' === substr( decoct( $file_permission ), 0, 1 ) ) {
							$options_activated['local_path'] = true;
						}
					}

					if ( isset( $_REQUEST['dropbox_auth'] ) ) {
						$dropbox_auth = sanitize_text_field( wp_unslash( $_REQUEST['dropbox_auth'] ) );
					} else {
						$dropbox_auth = '';
					}
					$dropbox_auth_saved = get_option( 'wpda_db_dropbox_auth' );
					if ( '' !== $dropbox_auth && $dropbox_auth_saved !== $dropbox_auth ) {
						$client   = new \GuzzleHttp\Client( [
							'base_uri' => 'https://api.dropboxapi.com/oauth2/token',
						] );
						$response = $client->request(
							'POST',
							'',
							[
								'form_params' => [
									'code'          => $dropbox_auth,
									'grant_type'    => 'authorization_code',
									'client_id'     => SELF::DROPBOX_CLIENT_ID,
									'client_secret' => SELF::DROPBOX_CLIENT_SECRET,
								]
							]
						);
						if ( ! ( 200 === $response->getStatusCode() && 'OK' === $response->getReasonPhrase() ) ) {
							$msg = new WPDA_Message_Box(
								[
									'message_text'           => __( 'Dropbox authorization failed ', 'wp-data-access' ) .
									                            $response->getStatusCode() . ' ' .
									                            $response->getReasonPhrase(),
									'message_type'           => 'error',
									'message_is_dismissible' => false,
								]
							);
							$msg->box();
						} else {
							$body_content = json_decode( $response->getBody()->getContents() );
							$access_token = $body_content->access_token;

							update_option( 'wpda_db_dropbox_access_token', $access_token );
						}
					}
					update_option( 'wpda_db_dropbox_auth', $dropbox_auth );

					if ( isset( $_REQUEST['dropbox_activated'] ) ) {
						$options_activated['dropbox'] = true;
					}

					if ( isset( $_REQUEST['dropbox_folder'] ) ) {
						$dropbox_folder = sanitize_text_field( wp_unslash( $_REQUEST['dropbox_folder'] ) );
						if ( '/' !== substr( $dropbox_folder, - 1 ) ) {
							$dropbox_folder .= '/';
						}
					}
					WPDA::set_option( WPDA::OPTION_DB_DROPBOX_PATH, $dropbox_folder );

					update_option( 'wpda_db_options_activated', $options_activated );
				} elseif ( 'setdefaults' === $action ) {
					// Set all data backup settings back to default.
					WPDA::set_option( WPDA::OPTION_DB_LOCAL_PATH );
					WPDA::set_option( WPDA::OPTION_DB_DROPBOX_PATH );
				}

				$msg = new WPDA_Message_Box(
					[
						'message_text' => __( 'Settings saved', 'wp-data-access' ),
					]
				);
				$msg->box();
			}

			$error_level = error_reporting();
			error_reporting( E_ALL ^ E_WARNING );
			$local_path      = WPDA::get_option( WPDA::OPTION_DB_LOCAL_PATH );
			$file_permission = fileperms( $local_path );
			error_reporting( $error_level );

			$owner_info = ( ( $file_permission & 0x0100 ) ? 'r' : '-' );
			$owner_info .= ( ( $file_permission & 0x0080 ) ? 'w' : '-' );
			$owner_info .= ( ( $file_permission & 0x0040 ) ?
				( ( $file_permission & 0x0800 ) ? 's' : 'x' ) :
				( ( $file_permission & 0x0800 ) ? 'S' : '-' ) );
			$group_info = ( ( $file_permission & 0x0020 ) ? 'r' : '-' );
			$group_info .= ( ( $file_permission & 0x0010 ) ? 'w' : '-' );
			$group_info .= ( ( $file_permission & 0x0008 ) ?
				( ( $file_permission & 0x0400 ) ? 's' : 'x' ) :
				( ( $file_permission & 0x0400 ) ? 'S' : '-' ) );
			$world_info = ( ( $file_permission & 0x0004 ) ? 'r' : '-' );
			$world_info .= ( ( $file_permission & 0x0002 ) ? 'w' : '-' );
			$world_info .= ( ( $file_permission & 0x0001 ) ?
				( ( $file_permission & 0x0200 ) ? 't' : 'x' ) :
				( ( $file_permission & 0x0200 ) ? 'T' : '-' ) );

			$dropbox_auth   = get_option( 'wpda_db_dropbox_auth' );
			$dropbox_folder = WPDA::get_option( WPDA::OPTION_DB_DROPBOX_PATH );

			$options_activated = get_option( 'wpda_db_options_activated' );
			?>

			<form id="wpda_settings_databackup" method="post"
				  action="?page=<?php echo esc_attr( $this->page ); ?>&tab=databackup">
				<table class="wpda-table-settings">
					<tr>
						<th><?php echo __( 'Local file system' ); ?></th>
						<td>
							<label>
								<input type="checkbox"
									   name="local_path_activated" <?php if ( isset( $options_activated['local_path'] ) ) {
									echo 'checked';
								} ?> />
								<?php echo __( 'Activated', 'wp-data-access' ) ?>
							</label>
							<br/><br/>
							<?php echo __( 'Enter the name of the folder where data backup files should be stored.' ); ?>
							<br/>
							<input type="text" name="local_path" value="<?php echo $local_path; ?>"/>
							<span><?php echo __( 'Make sure the folder exists with permission to write files.' ); ?></span>
							<?php
							if ( 'WIN' !== strtoupper( substr( PHP_OS, 0, 3 ) ) ) {
								if ( ! $file_permission ) {
									echo '<br/><br/>';
									echo __( 'ERROR: Invalid folder', 'wp-data-access' );
								} else {
									if ( '4' !== substr( decoct( $file_permission ), 0, 1 ) ) {
										echo '<br/><br/>';
										echo __( 'ERROR: Not a folder', 'wp-data-access' );
									} else {
										$fileowner  = fileowner( $local_path );
										$groupowner = filegroup( $local_path );
										?>
										<br/><br/>
										{
										<?php echo __( '"Permission"' ); ?>:
										{
										<?php echo __( '"owner"' ); ?>:
										{
										<?php echo __( '"name"' ); ?>: "<?php echo posix_getpwuid( $fileowner )['name'] ?>",
										<?php echo __( '"access"' ); ?>: "<?php echo $owner_info; ?>"
										},
										<?php echo __( '"group"' ); ?>:
										{
										<?php echo __( '"name"' ); ?>: "<?php echo posix_getpwuid( $groupowner )['name'] ?>",
										<?php echo __( '"access"' ); ?>: "<?php echo $group_info; ?>"
										},
										<?php echo __( '"world"' ); ?>:
										{
										<?php echo __( '"access"' ); ?>: "<?php echo $world_info; ?>"
										}
										}
										}
										<?php
									}
								}
							}
							?>
						</td>
					</tr>
					<tr>
						<th><?php echo __( 'Dropbox' ); ?></th>
						<td>
							<label>
								<input type="checkbox"
									   name="dropbox_activated" <?php if ( isset( $options_activated['dropbox'] ) ) {
									echo 'checked';
								} ?> />
								<?php echo __( 'Activated', 'wp-data-access' ) ?>
							</label>
							<br/><br/>
							<a href="https://www.dropbox.com/" class="button button-secondary" target="_blank">
								<?php echo __( 'Create a Dropbox account' ); ?>
							</a>
							<span style="vertical-align:-webkit-baseline-middle;">
								<?php echo __( 'You can skip this step if you already have an account.' ); ?>
							</span>
							<br/><br/>
							<?php echo __( 'Authorize the WP Data Access Dropbox app and enter the authorization code in the text box below.' ); ?>
							<br/>
							<input type="text" name="dropbox_auth" value="<?php echo $dropbox_auth; ?>"/>
							<a href="https://www.dropbox.com/oauth2/authorize?response_type=code&client_id=<?php echo self::DROPBOX_CLIENT_ID; ?>"
							   class="button button-secondary"
							   target="_blank"
							   style="vertical-align:bottom;">
								<?php echo __( 'Get Dropbox authorization code' ); ?>
							</a>
							<br/><br/>
							<?php echo __( 'Enter the name of the folder where data backup files should be stored. If the folder doesn\'t exists, it\'ll be created for you.' ); ?>
							<br/>
							<input type="text" name="dropbox_folder" value="<?php echo $dropbox_folder; ?>"/>
						</td>
					</tr>
				</table>
				<div class="wpda-table-settings-button">
					<input type="hidden" name="action" value="save"/>
					<input type="submit"
						   value="<?php echo __( 'Save Data Backup Settings', 'wp-data-access' ); ?>"
						   class="button button-primary"/>
					<a href="javascript:void(0)"
					   onclick="if (confirm('<?php echo __( 'Reset to defaults?', 'wp-data-access' ); ?>')) {
							   jQuery('input[name=&quot;action&quot;]').val('setdefaults');
							   jQuery('#wpda_settings_frontend').trigger('submit')
							   }"
					   class="button">
						<?php echo __( 'Reset Data Backup To Defaults', 'wp-data-access' ); ?>
					</a>
				</div>
				<?php wp_nonce_field( 'wpda-databackup-settings', '_wpnonce', false ); ?>
			</form>

			<?php

		}

		/**
		 * Add front-end tab content
		 *
		 * See class documentation for flow explanation.
		 *
		 * @since   1.0.0
		 */
		protected function add_content_frontend() {
			global $wpdb;

			if ( isset( $_REQUEST['database'] ) ) {
				$database = sanitize_text_field( wp_unslash( $_REQUEST['database'] ) ); // input var okay.
			} else {
				$database = $wpdb->dbname;
			}
			$is_wp_database = $database === $wpdb->dbname;

			if ( isset( $_REQUEST['action'] ) ) {
				$action = sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ); // input var okay.

				// Security check.
				$wp_nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : ''; // input var okay.
				if ( ! wp_verify_nonce( $wp_nonce, 'wpda-front-end-settings' ) ) {
					wp_die( __( 'ERROR: Not authorized', 'wp-data-access' ) );
				}

				if ( 'save' === $action ) {
					if ( $is_wp_database ) {
						WPDA::set_option(
							WPDA::OPTION_FE_TABLE_ACCESS,
							isset( $_REQUEST['table_access'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['table_access'] ) ) : null // input var okay.
						);
					} else {
						update_option(
							WPDA::FRONTEND_OPTIONNAME_DATABASE_ACCESS . $database,
							isset( $_REQUEST['table_access'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['table_access'] ) ) : null // input var okay.
						);
					}

					$table_access_selected_new_value = isset( $_REQUEST['table_access_selected'] ) ? $_REQUEST['table_access_selected'] : null;
					if ( is_array( $table_access_selected_new_value ) ) {
						// Check the requested table names for sql injection. This is simply done by checking if the table
						// name exists in our WordPress database.
						$table_access_selected_new_value_checked = [];
						foreach ( $table_access_selected_new_value as $key => $value ) {
							$wpda_dictionary_checks = new WPDA_Dictionary_Exist( $database, $value );
							if ( $wpda_dictionary_checks->table_exists( false, false ) ) {
								// Add existing table to list.
								$table_access_selected_new_value_checked[ $key ] = $value;
							} else {
								// An invalid table name was provided. Might be an sql injection attack or an invalid state.
								wp_die( __( 'ERROR: Table not found', 'wp-data-access' ) );
							}
						}
					} else {
						$table_access_selected_new_value_checked = '';
					}

					if ( $is_wp_database ) {
						WPDA::set_option(
							WPDA::OPTION_FE_TABLE_ACCESS_SELECTED,
							$table_access_selected_new_value_checked
						);
					} else {
						update_option(
							WPDA::FRONTEND_OPTIONNAME_DATABASE_SELECTED . $database,
							$table_access_selected_new_value_checked
						);
					}

					WPDA::set_option(
						WPDA::OPTION_FE_PAGINATION,
						isset( $_REQUEST['pagination'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['pagination'] ) ) : null // input var okay.
					);
				} elseif ( 'setdefaults' === $action ) {
					// Set all frond-end settings back to default
					if ( $is_wp_database ) {
						WPDA::set_option( WPDA::OPTION_FE_TABLE_ACCESS );
						WPDA::set_option( WPDA::OPTION_FE_TABLE_ACCESS_SELECTED );
					} else {
						update_option(
							WPDA::FRONTEND_OPTIONNAME_DATABASE_ACCESS . $database,
							'select'
						);
						update_option(
							WPDA::FRONTEND_OPTIONNAME_DATABASE_SELECTED . $database,
							''
						);
					}
					WPDA::set_option( WPDA::OPTION_FE_PAGINATION );
				}

				$msg = new WPDA_Message_Box(
					[
						'message_text' => __( 'Settings saved', 'wp-data-access' ),
					]
				);
				$msg->box();

			}

			// Get options
			if ( $is_wp_database ) {
				$table_access          = WPDA::get_option( WPDA::OPTION_FE_TABLE_ACCESS );
				$table_access_selected = WPDA::get_option( WPDA::OPTION_FE_TABLE_ACCESS_SELECTED );
			} else {
				$table_access = get_option( WPDA::FRONTEND_OPTIONNAME_DATABASE_ACCESS . $database );
				if ( false === $table_access ) {
					$table_access = 'select';
				}
				$table_access_selected = get_option( WPDA::FRONTEND_OPTIONNAME_DATABASE_SELECTED . $database );
				if ( false === $table_access_selected ) {
					$table_access_selected = '';
				}
			}
			
			if ( is_array( $table_access_selected ) ) {
				// Convert table for simple access.
				$table_access_selected_by_name = [];
				foreach ( $table_access_selected as $key => $value ) {
					$table_access_selected_by_name[ $value ] = true;
				}
			}

			$pagination = WPDA::get_option( WPDA::OPTION_FE_PAGINATION );
			?>
			<form id="wpda_settings_frontend" method="post"
				  action="?page=<?php echo esc_attr( $this->page ); ?>&tab=frontend">
				<table class="wpda-table-settings">
					<tr>
						<th><?php echo __( 'Selected database', 'wp-data-access' ); ?></th>
						<td>
							<select name="database" id="schema_name">
								<?php
								$schema_names   = WPDA_Dictionary_Lists::get_db_schemas();
								foreach ( $schema_names as $schema_name ) {
									$selected = $database === $schema_name['schema_name'] ? ' selected' : '';
									echo "<option value='{$schema_name['schema_name']}'$selected>{$schema_name['schema_name']}</option>";
								}
								?>
							</select>
							<?php echo __( '(reflects table access only)', 'wp-data-access' ); ?>
						</td>
					</tr>
					<tr>
						<th><?php echo __( 'Table access', 'wp-data-access' ); ?></th>
						<td>
							<label>
								<input
										type="radio"
										name="table_access"
										value="show"
									<?php echo 'show' === $table_access ? 'checked' : ''; ?>
								><?php echo $is_wp_database ? __( 'Show WordPress tables', 'wp-data-access' ) : __( 'Show all tables', 'wp-data-access' ); ?>
							</label>
							<br/>
							<?php
							if ( $is_wp_database ) {
								?>
								<label>
									<input
										type="radio"
										name="table_access"
										value="hide"
										<?php echo 'hide' === $table_access ? 'checked' : ''; ?>
									><?php echo __( 'Hide WordPress tables', 'wp-data-access' ); ?>
								</label>
								<br/>
								<?php
							}
							?>
							<label>
								<input
										type="radio"
										name="table_access"
										value="select"
									<?php echo 'select' === $table_access ? 'checked' : ''; ?>
								><?php echo __( 'Show only selected tables', 'wp-data-access' ); ?>
							</label>
							<div id="tables_selected" <?php echo 'select' === $table_access ? '' : 'style="display:none"'; ?>>
								<br/>
								<select name="table_access_selected[]" multiple size="10">
									<?php
									$tables = WPDA_Dictionary_Lists::get_tables( true, $database );
									foreach ( $tables as $table ) {
										$table_name = $table['table_name'];
										?>
										<option value="<?php echo esc_attr( $table_name ); ?>" <?php echo isset( $table_access_selected_by_name[ $table_name ] ) ? 'selected' : ''; ?>><?php echo esc_attr( $table_name ); ?></option>
										<?php
									}
									?>
								</select>
							</div>
							<script type='text/javascript'>
								jQuery(document).ready(function () {
									jQuery("input[name='table_access']").on("click", function () {
										if (this.value == 'select') {
											jQuery("#tables_selected").show();
										} else {
											jQuery("#tables_selected").hide();
										}
									});
									jQuery('#schema_name').on('change', function() {
										window.location = '?page=<?php echo esc_attr( $this->page ); ?>&tab=frontend&database=' + jQuery(this).val();
									});
								});
							</script>
						</td>
					</tr>

					<tr>
						<th><?php echo __( 'Default pagination value', 'wp-data-access' ); ?></th>
						<td>
							<input
									type="number" step="1" min="1" max="999" name="pagination" maxlength="3"
									value="<?php echo esc_attr( $pagination ); ?>">
						</td>
					</tr>

				</table>
				<div class="wpda-table-settings-button">
					<input type="hidden" name="action" value="save"/>
					<input type="submit"
						   value="<?php echo __( 'Save Front-end Settings', 'wp-data-access' ); ?>"
						   class="button button-primary"/>
					<a href="javascript:void(0)"
					   onclick="if (confirm('<?php echo __( 'Reset to defaults?', 'wp-data-access' ); ?>')) {
							   jQuery('input[name=&quot;action&quot;]').val('setdefaults');
							   jQuery('#wpda_settings_frontend').trigger('submit')
							   }"
					   class="button">
						<?php echo __( 'Reset Front-end Settings To Defaults', 'wp-data-access' ); ?>
					</a>
				</div>
				<?php wp_nonce_field( 'wpda-front-end-settings', '_wpnonce', false ); ?>
			</form>

			<?php

		}

		/**
		 * Add uninstall tab content
		 *
		 * See class documentation for flow explanation.
		 *
		 * @since   1.0.0
		 */
		protected function add_content_uninstall() {

			if ( isset( $_REQUEST['action'] ) ) {
				$action = sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ); // input var okay.

				// Security check.
				$wp_nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : ''; // input var okay.
				if ( ! wp_verify_nonce( $wp_nonce, 'wpda-uninstall-settings' ) ) {
					wp_die( __( 'ERROR: Not authorized', 'wp-data-access' ) );
				}

				if ( 'save' === $action ) {

					// Save changes.
					WPDA::set_option(
						WPDA::OPTION_WPDA_UNINSTALL_TABLES,
						isset( $_REQUEST['delete_tables'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['delete_tables'] ) ) : 'off' // input var okay.
					);

					WPDA::set_option(
						WPDA::OPTION_WPDA_UNINSTALL_OPTIONS,
						isset( $_REQUEST['delete_options'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['delete_options'] ) ) : 'off' // input var okay.
					);

				} elseif ( 'setdefaults' === $action ) {

					// Set back to default values.
					WPDA::set_option( WPDA::OPTION_WPDA_UNINSTALL_TABLES );
					WPDA::set_option( WPDA::OPTION_WPDA_UNINSTALL_OPTIONS );

				}

				$msg = new WPDA_Message_Box(
					[
						'message_text' => __( 'Settings saved', 'wp-data-access' ),
					]
				);
				$msg->box();

			}

			$delete_tables  = WPDA::get_option( WPDA::OPTION_WPDA_UNINSTALL_TABLES );
			$delete_options = WPDA::get_option( WPDA::OPTION_WPDA_UNINSTALL_OPTIONS );

			?>

			<iframe id="stealth_mode" style="display:none"></iframe>
			<form id="wpda_settings_uninstall" method="post"
				  action="?page=<?php echo esc_attr( $this->page ); ?>&tab=uninstall">
				<table class="wpda-table-settings">
					<tr>
						<th>
							<?php echo __( 'On Plugin Uninstall', 'wp-data-access' ); ?>
						</th>
						<td>
							<label>
								<input type="checkbox" name="delete_plugin" style="margin-right: 0" checked
									   disabled="disabled">
								<?php echo __( 'Delete plugin', 'wp-data-access' ); ?>
							</label>
							<br/>
							<label>
								<input type="checkbox" name="delete_tables"
									   style="margin-right: 0" <?php echo 'on' === $delete_tables ? 'checked' : ''; ?>>
								<?php echo __( 'Delete plugin tables (all data will be lost)', 'wp-data-access' ); ?>
							</label>
							<br/>
							<label>
								<input type="checkbox" name="delete_options"
									   style="margin-right: 0" <?php echo 'on' === $delete_options ? 'checked' : ''; ?>>
								<?php echo __( 'Delete plugin settings (all settings will be lost)', 'wp-data-access' ); ?>
							</label>
						</td>
					</tr>
				</table>
				<div class="wpda-table-settings-button">
					<input type="hidden" name="action" value="save"/>
					<input type="submit"
						   value="<?php echo __( 'Save Uninstall Settings', 'wp-data-access' ); ?>"
						   class="button button-primary"/>
					<a href="javascript:void(0)"
					   onclick="if (confirm('<?php echo __( 'Reset to defaults?', 'wp-data-access' ); ?>')) {
							   jQuery('input[name=\'action\']').val('setdefaults');
							   jQuery('#wpda_settings_uninstall').trigger('submit');
							   }"
					   class="button button-secondary">
						<?php echo __( 'Reset Uninstall Settings To Defaults', 'wp-data-access' ); ?>
					</a>
				</div>
				<?php wp_nonce_field( 'wpda-uninstall-settings', '_wpnonce', false ); ?>
			</form>

			<?php

		}

		/**
		 * Add roles tab content
		 *
		 * See class documentation for flow explanation.
		 *
		 * @since   2.7.0
		 */
		protected function add_content_roles() {
			$wp_deault_roles = [
				'administrator' => true,
				'editor'        => true,
				'author'        => true,
				'contributor'   => true,
				'subscriber'    => true
			];

			if ( isset( $_REQUEST['action'] ) ) {
				// Security check.
				if ( 'delete' === $_REQUEST['action'] ) {
					$wp_nonce = isset( $_REQUEST['_wpnoncedelrole'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnoncedelrole'] ) ) : ''; // input var okay.
					if ( ! wp_verify_nonce( $wp_nonce, 'wpda-manage-roles-settings' ) ) {
						wp_die( __( 'ERROR: Not authorized', 'wp-data-access' ) );
					}
				} else {
					$wp_nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : ''; // input var okay.
					if ( ! wp_verify_nonce( $wp_nonce, 'wpda-manage-roles-settings' ) ) {
						wp_die( __( 'ERROR: Not authorized', 'wp-data-access' ) );
					}
				}

				if ( 'save' === $_REQUEST['action'] ) {
					WPDA::set_option(
						WPDA::OPTION_WPDA_ENABLE_ROLE_MANAGEMENT,
						isset( $_REQUEST['enable_role_management'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['enable_role_management'] ) ) : 'off' // input var okay.
					);

					WPDA::set_option(
						WPDA::OPTION_WPDA_USE_ROLES_IN_SHORTCODE,
						isset( $_REQUEST['use_roles_in_shortcode'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['use_roles_in_shortcode'] ) ) : 'off' // input var okay.
					);

					if ( isset( $_REQUEST['wpda_role_name'] ) && is_array( $_REQUEST['wpda_role_name'] ) &&
					     isset( $_REQUEST['wpda_role_label'] ) && is_array( $_REQUEST['wpda_role_label'] )
					) {
						$no_roles = count( $_REQUEST['wpda_role_name'] );
						for ( $i = 0; $i < $no_roles; $i ++ ) {
							$sanitized_new_role_name  = sanitize_text_field( wp_unslash( $_REQUEST['wpda_role_name'][ $i ] ) ); // input var okay.
							$sanitized_new_role_label = sanitize_text_field( wp_unslash( $_REQUEST['wpda_role_label'][ $i ] ) ); // input var okay.
							add_role( $sanitized_new_role_name, $sanitized_new_role_label );
						}
					}
					$msg = new WPDA_Message_Box(
						[
							'message_text' => __( 'Settings saved', 'wp-data-access' ),
						]
					);
					$msg->box();
				} elseif ( 'delete' === $_REQUEST['action'] ) {
					if ( isset( $_REQUEST['delete_role_name'] ) ) {
						$sanitized_role_name = sanitize_text_field( wp_unslash( $_REQUEST['delete_role_name'] ) ); // input var okay.
						$all_users           = get_users(
							[ 'role' => $sanitized_role_name ]
						);
						foreach ( $all_users as $user ) {
							$wp_user = new \WP_User( $user->ID );
							$wp_user->remove_role( $sanitized_role_name );
						}
						remove_role( $sanitized_role_name );

						$msg = new WPDA_Message_Box(
							[
								'message_text' => __( 'Settings saved', 'wp-data-access' ),
							]
						);
						$msg->box();
					}
				} elseif ( 'setdefaults' === $_REQUEST['action'] ) {
					// Set back to default values.
					WPDA::set_option( WPDA::OPTION_WPDA_ENABLE_ROLE_MANAGEMENT );
					WPDA::set_option( WPDA::OPTION_WPDA_USE_ROLES_IN_SHORTCODE );
				}
			}

			$enable_role_management = WPDA::get_option( WPDA::OPTION_WPDA_ENABLE_ROLE_MANAGEMENT );
			$use_roles_in_shortcode = WPDA::get_option( WPDA::OPTION_WPDA_USE_ROLES_IN_SHORTCODE );
			?>
			<form id="wpda_settings_manage_roles"
				  method="post"
				  action="?page=<?php echo esc_attr( $this->page ); ?>&tab=roles">

				<table class="wpda-table-settings">

					<tr>
						<th>
							<?php echo __( 'Plugin Role Management', 'wp-data-access' ); ?>
						</th>
						<td>
							<label>
								<input type="checkbox" name="enable_role_management"
									<?php echo 'on' === $enable_role_management ? 'checked' : ''; ?>/>
								<?php echo __( 'Enable role management', 'wp-data-access' ); ?>
							</label>
							<br/>
							<label>
								<input type="checkbox" name="use_roles_in_shortcode"
									<?php echo 'on' === $use_roles_in_shortcode ? 'checked' : ''; ?>/>
								<?php echo __( 'Use roles in shortcode wpdadiehard (Data Projects)', 'wp-data-access' ); ?>
							</label>
						</td>
					</tr>

					<tr>
						<th>
							<?php echo __( 'Available Roles', 'wp-data-access' ); ?>
						</th>
						<td>
							<div id="list_roles">
								<?php
								global $wp_roles;
								foreach ( $wp_roles->roles as $role => $val ) {
									$is_wp_role = isset( $wp_deault_roles[ $role ] );
									$role_label = isset( $val['name'] ) ? $val['name'] : $role;
									?>
									<div id="<?php echo $role; ?>">
								<span class="dashicons <?php echo $is_wp_role ? 'dashicons-wordpress' : 'dashicons-trash'; ?>"
									  style="font-size: 14px; vertical-align: text-top;<?php echo $is_wp_role ? '' : ' cursor: pointer;'; ?>"></span>
										<?php echo $role_label; ?>
									</div>
									<?php
								}
								?>
							</div>
							<p>
								<a href="void(0);" class="page-title-action" onclick="add_new_role(); return false;">Add
									New Role</a>
							</p>
						</td>
					</tr>

				</table>

				<div class="wpda-table-settings-button">
					<input type="hidden" name="action" value="save"/>
					<input type="submit"
						   value="<?php echo __( 'Save Manage Roles Settings', 'wp-data-access' ); ?>"
						   class="button button-primary"/>
					<a href="javascript:void(0)"
					   onclick="if (confirm('<?php echo __( 'Reset to defaults?', 'wp-data-access' ); ?>')) {
							   jQuery('input[name=\'action\']').val('setdefaults');
							   jQuery('#wpda_settings_manage_roles').trigger('submit');
							   }"
					   class="button button-secondary">
						<?php echo __( 'Reset Manage Roles Settings To Defaults', 'wp-data-access' ); ?>
					</a>
				</div>
				<?php wp_nonce_field( 'wpda-manage-roles-settings', '_wpnonce', false ); ?>

			</form>

			<form id="delete_role_form"
				  method="post"
				  action="?page=<?php echo esc_attr( $this->page ); ?>&tab=roles">
				<input type="hidden" id="delete_role_name" name="delete_role_name" value="">
				<input type="hidden" name="action" value="delete">
				<?php wp_nonce_field( 'wpda-manage-roles-settings', '_wpnoncedelrole', false ); ?>
			</form>


			<script type='text/javascript'>
				function add_new_role() {
					jQuery('#list_roles').append(
						'<div>' +
						'  <span class="dashicons dashicons-trash" style="font-size: 14px; vertical-align: text-top; cursor: pointer;" onclick="jQuery(this).parent().remove();"></span>' +
						'  <label for="wpda_role_name[]">Name: </label><input name="wpda_role_name[]" style="vertical-align: middle; text-transform: lowercase;"/>' +
						'  <label for="wpda_role_label[]">Label: </label><input name="wpda_role_label[]" style="vertical-align: middle;"/>' +
						'</div>');
				}

				jQuery('.dashicons-trash').on('click', function (e) {
					if (confirm('<?php echo __( 'Delete role?\nRole will be removed from all users.\nThis action cannot be undone! ', 'wp-data-access' ); ?>')) {
						parent = jQuery(e.target).parent();
						parent_id = parent.attr('id');
						jQuery('#delete_role_name').val(parent_id);
						jQuery('#delete_role_form').submit();
					}
				});
			</script>

			<?php
		}

		/**
		 * Add repository tab content
		 *
		 * See class documentation for flow explanation.
		 *
		 * @since   1.0.0
		 */
		protected function add_content_repository() {

			global $wpdb;

			if ( isset( $_REQUEST['action'] ) ) {
				$action = sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ); // input var okay.

				// Security check.
				$wp_nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : ''; // input var okay.
				if ( ! wp_verify_nonce( $wp_nonce, 'wpda-repository-settings' ) ) {
					wp_die( __( 'ERROR: Not authorized', 'wp-data-access' ) );
				}

				if ( 'save' === $action ) {
					// Save changes.
					WPDA::set_option(
						WPDA::OPTION_MR_KEEP_BACKUP_TABLES,
						isset( $_REQUEST['keep_backup_tables'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['keep_backup_tables'] ) ) : 'off' // input var okay.
					);
				} elseif ( 'setdefaults' === $action ) {
					// Set back to default values.
					WPDA::set_option( WPDA::OPTION_MR_KEEP_BACKUP_TABLES );

				}

				$msg = new WPDA_Message_Box(
					[
						'message_text' => __( 'Settings saved', 'wp-data-access' ),
					]
				);
				$msg->box();

			}

			$keep_backup_tables = WPDA::get_option( WPDA::OPTION_MR_KEEP_BACKUP_TABLES );

			// Check table wp_wpda_menus.
			$menus_table_name        = WPDA_User_Menus_Model::get_base_table_name();
			$menus_table_name_exists = WPDA_User_Menus_Model::table_exists();
			if ( $menus_table_name_exists ) {
				$no_menu_items = WPDA_User_Menus_Model::count();
			} else {
				$no_menu_items = 0;
			}

			// Check table wp_wpda_table_design.
			$design_table_name        = WPDA_Design_Table_Model::get_base_table_name();
			$design_table_name_exists = WPDA_Design_Table_Model::table_exists();
			if ( $design_table_name_exists ) {
				$no_table_designs = WPDA_Design_Table_Model::count();
			} else {
				$no_table_designs = 0;
			}

			// Check table wp_wpda_logging
			$logging_table_name   = WPDA_Logging_Model::get_base_table_name();
			$logging_table_exists = WPDA_Logging_Model::table_exists();
			if ( $logging_table_exists ) {
				$no_logs = WPDA_Logging_Model::count();
			} else {
				$no_logs = 0;
			}

			// Check table wp_wpda_media
			$media_table_name   = WPDA_Media_Model::get_base_table_name();
			$media_table_exists = WPDA_Media_Model::table_exists();
			if ( $media_table_exists ) {
				$no_media = WPDA_Media_Model::count();
			} else {
				$no_media = 0;
			}

			// Check data projects tables
			$data_projects_project_name        = WPDP_Project_Model::get_base_table_name();
			$data_projects_project_name_exists = WPDP_Project_Model::table_exists();
			if ( $data_projects_project_name_exists ) {
				$no_projects = WPDP_Project_Model::count();
			} else {
				$no_projects = 0;
			}

			// Check data project page table.
			$data_projects_page_name        = WPDP_Page_Model::get_base_table_name();
			$data_projects_page_name_exists = WPDP_Page_Model::table_exists();
			if ( $data_projects_page_name_exists ) {
				$no_pages = WPDP_Page_Model::count();
			} else {
				$no_pages = 0;
			}

			// Check data project tables table
			$data_projects_table_name        = WPDP_Project_Design_Table_Model::get_base_table_name();
			$data_projects_table_name_exists = WPDP_Project_Design_Table_Model::table_exists();
			if ( $data_projects_table_name_exists ) {
				$no_project_table_designs = WPDP_Project_Design_Table_Model::count();
			} else {
				$no_project_table_designs = 0;
			}

			// Check data publisher table.
			$data_publication_table_name        = WPDA_Publisher_Model::get_base_table_name();
			$data_publication_table_name_exists = WPDA_Publisher_Model::table_exists();
			if ( $data_publication_table_name_exists ) {
				$no_data_publication = WPDA_Publisher_Model::count();
			} else {
				$no_data_publication = 0;
			}

			// Check table settings table
			$table_settings_table_name   = WPDA_Table_Settings_Model::get_base_table_name();
			$table_settings_table_exists = WPDA_Table_Settings_Model::table_exists();
			if ( $table_settings_table_exists ) {
				$no_table_settings = WPDA_Table_Settings_Model::count();
			} else {
				$no_table_settings = 0;
			}

			$table     = __( 'Table', 'wp-data-access' );
			$found     = __( 'found', 'wp-data-access' );
			$not_found = __( 'not found', 'wp-data-access' );

			// Count old backup tables
			$bck_postfix      = '_BACKUP_';
			$query            = "select table_name from information_schema.tables " .
			                    "where table_schema = '{$wpdb->dbname}' " .
			                    " and ( table_name like '$menus_table_name{$bck_postfix}%' " .
			                    " or table_name like '$design_table_name{$bck_postfix}%' " .
			                    " or table_name like '$table_settings_table_name{$bck_postfix}%' " .
			                    " or table_name like '$logging_table_name{$bck_postfix}%' " .
			                    " or table_name like '$media_table_name{$bck_postfix}%' " .
			                    " or table_name like '$data_publication_table_name{$bck_postfix}%' " .
			                    " or table_name like '$data_projects_project_name{$bck_postfix}%' " .
			                    " or table_name like '$data_projects_page_name{$bck_postfix}%' " .
			                    " or table_name like '$data_projects_table_name{$bck_postfix}%' )";
			$backup_tables    = $wpdb->get_results( $query, 'ARRAY_N' );
			$no_backup_tables = $wpdb->num_rows;

			if ( isset( $_REQUEST['remove_backup'] ) && 'true' === $_REQUEST['remove_backup'] ) {
				// Security check
				$wp_nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : ''; // input var okay.
				if ( ! wp_verify_nonce( $wp_nonce, 'wpda-settings-remove_backup-repository' ) ) {
					wp_die( __( 'ERROR: Not authorized', 'wp-data-access' ) );
				}

				// Remove old backup tables
				foreach ( $backup_tables as $backup_table ) {
					$wpdb->query( "drop table {$backup_table[0]}" );
				}

				// Count backup tables again...
				$query = "select table_name from information_schema.tables " .
				         "where table_schema = '{$wpdb->dbname}' " .
				         " and ( table_name like '$menus_table_name{$bck_postfix}%' " .
				         " or table_name like '$design_table_name{$bck_postfix}%' " .
				         " or table_name like '$table_settings_table_name{$bck_postfix}%' " .
				         " or table_name like '$logging_table_name{$bck_postfix}%' " .
				         " or table_name like '$data_publication_table_name{$bck_postfix}%' " .
				         " or table_name like '$data_projects_project_name{$bck_postfix}%' " .
				         " or table_name like '$data_projects_page_name{$bck_postfix}%' " .
				         " or table_name like '$data_projects_table_name{$bck_postfix}%' )";
				$wpdb->get_results( $query, 'ARRAY_N' );
				$no_backup_tables = $wpdb->num_rows;

				$msg = new WPDA_Message_Box(
					[
						'message_text' => __( 'Old backup tables dropped', 'wp-data-access' ),
					]
				);
				$msg->box();
			}

			?>

			<iframe id="stealth_mode" style="display:none"></iframe>
			<form id="wpda_settings_repository" method="post"
				  action="?page=<?php echo esc_attr( $this->page ); ?>&tab=repository">
				<table class="wpda-table-settings">

					<tr>
						<th>
							<?php echo __( 'On Plugin Update', 'wp-data-access' ); ?>
						</th>
						<td>
							<label>
								<input type="checkbox" name="keep_backup_tables"
									   style="margin-right: 0" <?php echo 'on' === $keep_backup_tables ? 'checked' : ''; ?>>
								<?php echo __( 'Keep backup of repository tables', 'wp-data-access' ); ?>
							</label>
							<br/><br/>
							<?php
							$wpnonce_remove_backup = wp_create_nonce( 'wpda-settings-remove_backup-repository' );
							?>
							<a href="?page=<?php echo esc_attr( $this->page ); ?>&tab=repository&remove_backup=true&_wpnonce=<?php echo esc_attr( $wpnonce_remove_backup ); ?>"
							   class="button <?php echo 0 === $no_backup_tables ? 'disabled' : ''; ?>"
							   onclick="return confirm('<?php echo __( 'Delete old backup tables?\nThis action cannot be undone.\n\\\'Cancel\\\' to stop, \\\'OK\\\' to delete.', 'wp-data-access' ); ?>');"
							>
								<?php echo __( 'Delete' ) . ' ' . esc_html( $no_backup_tables ) . ' ' . __( 'old backup tables' ); ?>
							</a>
						</td>
					</tr>

					<tr>
						<th>
							<?php echo __( 'Table Settings', 'wp-data-access' ); ?>
						</th>
						<td>
							<span class="dashicons <?php echo $table_settings_table_exists ? 'dashicons-yes' : 'dashicons-no'; ?>"></span>
							<?php echo esc_attr( $table ); ?>
							<strong><?php echo esc_attr( $table_settings_table_name ); ?></strong>
							<?php echo $table_settings_table_exists ? esc_attr( $found ) : esc_attr( $not_found ); ?>
							<?php
							if ( $table_settings_table_exists ) {
								?>
								<br/><br/>
								<span class="dashicons dashicons-yes"></span>
								<strong>
									<?php echo esc_attr( $no_table_settings ); ?>
									<?php echo __( 'table settings defined in repository', 'wp-data-access' ); ?>
								</strong>
								<?php
							}
							?>
						</td>
					</tr>

					<tr>
						<th>
							<?php echo __( 'Manage Media', 'wp-data-access' ); ?>
						</th>
						<td>
							<span class="dashicons <?php echo $media_table_exists ? 'dashicons-yes' : 'dashicons-no'; ?>"></span>
							<?php echo esc_attr( $table ); ?>
							<strong><?php echo esc_attr( $media_table_name ); ?></strong>
							<?php echo $media_table_exists ? esc_attr( $found ) : esc_attr( $not_found ); ?>
							<?php
							if ( $media_table_exists ) {
								?>
								<br/><br/>
								<span class="dashicons dashicons-yes"></span>
								<strong>
									<?php echo esc_attr( $no_media ); ?>
									<?php echo __( 'media columns defined in repository', 'wp-data-access' ); ?>
								</strong>
								<?php
							}
							?>
						</td>
					</tr>

					<tr>
						<th>
							<?php echo __( 'Data Designer', 'wp-data-access' ); ?>
						</th>
						<td>
							<span class="dashicons <?php echo $design_table_name_exists ? 'dashicons-yes' : 'dashicons-no'; ?>"></span>
							<?php echo esc_attr( $table ); ?>
							<strong><?php echo esc_attr( $design_table_name ); ?></strong>
							<?php echo $design_table_name_exists ? esc_attr( $found ) : esc_attr( $not_found ); ?>
							<?php
							if ( $design_table_name_exists ) {
								?>
								<br/><br/>
								<span class="dashicons dashicons-yes"></span>
								<strong>
									<?php echo esc_attr( $no_table_designs ); ?>
									<?php echo __( 'table designs in repository', 'wp-data-access' ); ?>
								</strong>
								<?php
							}
							?>
						</td>
					</tr>

					<tr>
						<th>
							<?php echo __( 'Data Projects', 'wp-data-access' ); ?>
						</th>
						<td>
							<span class="dashicons <?php echo $design_table_name_exists ? 'dashicons-yes' : 'dashicons-no'; ?>"></span>
							<?php echo esc_attr( $table ); ?>
							<strong><?php echo esc_attr( $data_projects_project_name ); ?></strong>
							<?php echo $design_table_name_exists ? esc_attr( $found ) : esc_attr( $not_found ); ?>
							<br/>
							<span class="dashicons <?php echo $design_table_name_exists ? 'dashicons-yes' : 'dashicons-no'; ?>"></span>
							<?php echo esc_attr( $table ); ?>
							<strong><?php echo esc_attr( $data_projects_page_name ); ?></strong>
							<?php echo $design_table_name_exists ? esc_attr( $found ) : esc_attr( $not_found ); ?>
							<br/>
							<span class="dashicons <?php echo $design_table_name_exists ? 'dashicons-yes' : 'dashicons-no'; ?>"></span>
							<?php echo esc_attr( $table ); ?>
							<strong><?php echo esc_attr( $data_projects_table_name ); ?></strong>
							<?php echo $design_table_name_exists ? esc_attr( $found ) : esc_attr( $not_found ); ?>
							<br/>
							<?php
							if ( $data_projects_project_name_exists ) {
								?>
								<br/>
								<span class="dashicons dashicons-yes"></span>
								<strong>
									<?php echo esc_attr( $no_projects ); ?>
									<?php echo __( 'data projects in repository', 'wp-data-access' ); ?>
								</strong>
								<?php
							}
							if ( $data_projects_page_name_exists ) {
								?>
								<br/>
								<span class="dashicons dashicons-yes"></span>
								<strong>
									<?php echo esc_attr( $no_pages ); ?>
									<?php echo __( 'project pages in repository', 'wp-data-access' ); ?>
								</strong>
								<?php
							}
							if ( $data_projects_table_name_exists ) {
								?>
								<br/>
								<span class="dashicons dashicons-yes"></span>
								<strong>
									<?php echo esc_attr( $no_project_table_designs ); ?>
									<?php echo __( 'project tables in repository', 'wp-data-access' ); ?>
								</strong>
								<?php
							}
							?>
						</td>
					</tr>

					<tr>
						<th>
							<?php echo __( 'Data Publisher', 'wp-data-access' ); ?>
						</th>
						<td>
							<span class="dashicons <?php echo $data_publication_table_name_exists ? 'dashicons-yes' : 'dashicons-no'; ?>"></span>
							<?php echo esc_attr( $table ); ?>
							<strong><?php echo esc_attr( $data_publication_table_name ); ?></strong>
							<?php echo $data_publication_table_name_exists ? esc_attr( $found ) : esc_attr( $not_found ); ?>
							<?php
							if ( $data_publication_table_name_exists ) {
								?>
								<br/><br/>
								<span class="dashicons dashicons-yes"></span>
								<strong>
									<?php echo esc_attr( $no_data_publication ); ?>
									<?php echo __( 'publication in repository', 'wp-data-access' ); ?>
								</strong>
								<?php
							}
							?>
						</td>
					</tr>

					<tr>
						<th>
							<?php echo __( 'Data Menus', 'wp-data-access' ); ?>
						</th>
						<td>
							<span class="dashicons <?php echo $menus_table_name_exists ? 'dashicons-yes' : 'dashicons-no'; ?>"></span>
							<?php echo esc_attr( $table ); ?>
							<strong><?php echo esc_attr( $menus_table_name ); ?></strong>
							<?php echo $menus_table_name_exists ? esc_attr( $found ) : esc_attr( $not_found ); ?>
							<?php
							if ( $menus_table_name_exists ) {
								?>
								<br/><br/>
								<span class="dashicons dashicons-yes"></span>
								<strong>
									<?php echo esc_attr( $no_menu_items ); ?>
									<?php echo __( 'menus in repository', 'wp-data-access' ); ?>
								</strong>
								<?php
							}
							?>
						</td>
					</tr>

					<tr>
						<th>
							<?php echo __( 'Data Logging', 'wp-data-access' ); ?>
						</th>
						<td>
							<span class="dashicons <?php echo $logging_table_exists ? 'dashicons-yes' : 'dashicons-no'; ?>"></span>
							<?php echo esc_attr( $table ); ?>
							<strong><?php echo esc_attr( $logging_table_name ); ?></strong>
							<?php echo $logging_table_exists ? esc_attr( $found ) : esc_attr( $not_found ); ?>
							<?php
							if ( $logging_table_exists ) {
								?>
								<br/><br/>
								<span class="dashicons dashicons-yes"></span>
								<strong>
									<?php echo esc_attr( $no_logs ); ?>
									<?php echo __( 'logging rows in repository', 'wp-data-access' ); ?>
								</strong>
								<?php
							}
							?>
						</td>
					</tr>

				</table>
				<div class="wpda-table-settings-button">
					<input type="hidden" name="action" value="save"/>
					<input type="submit"
						   value="<?php echo __( 'Save Manage Respository Settings', 'wp-data-access' ); ?>"
						   class="button button-primary"/>
					<a href="javascript:void(0)"
					   onclick="if (confirm('<?php echo __( 'Reset to defaults?', 'wp-data-access' ); ?>')) {
							   jQuery('input[name=\'action\']').val('setdefaults');
							   jQuery('#wpda_settings_repository').trigger('submit');
							   }"
					   class="button button-secondary">
						<?php echo __( 'Reset Manage Repository Settings To Defaults', 'wp-data-access' ); ?>
					</a>
					<?php
					$wpnonce_recreate = wp_create_nonce( 'wpda-settings-recreate-repository' );
					?>
					<a href="?page=<?php echo esc_attr( $this->page ); ?>&tab=repository&repos=true&_wpnonce=<?php echo esc_attr( $wpnonce_recreate ); ?>"
					   class="button button-secondary">
						<?php echo __( 'Recreate', 'wp-data-access' ); ?> WP Data Access
						<?php echo __( 'Repository', 'wp-data-access' ); ?>
					</a>
				</div>
				<?php wp_nonce_field( 'wpda-repository-settings', '_wpnonce', false ); ?>
			</form>

			<div class="wpda-table-settings-button">

				<?php

				$repository_valid = true;

				// Check if repository should be recreated.
				if (
					! $menus_table_name_exists ||
					! $design_table_name_exists ||
					! $data_projects_project_name_exists ||
					! $data_projects_page_name_exists ||
					! $data_projects_table_name_exists ||
					! $data_publication_table_name_exists
				) {
					?>
					<p><strong><?php echo __( 'Your repository has errors!', 'wp-data-access' ); ?></strong></p>
					<p>
						<?php echo __( 'Recreate the WP Data Access repository to solve this problem.', 'wp-data-access' ); ?>
						<?php echo __( 'Please leave your comments on the support forum if the problem remains.', 'wp-data-access' ); ?>
						(<a href="https://wordpress.org/support/plugin/wp-data-access/" target="_blank">go to forum</a>)
					</p>
					<?php

					$repository_valid = false;
				}

				?>

				<?php

				?>

			</div>

			<?php

		}

		/**
		 * Add back-end tab content
		 *
		 * See class documentation for flow explanation.
		 *
		 * @since   1.0.0
		 */
		protected function add_content_backend() {
			global $wpdb;

			if ( isset( $_REQUEST['database'] ) ) {
				$database = sanitize_text_field( wp_unslash( $_REQUEST['database'] ) ); // input var okay.
			} else {
				$database = $wpdb->dbname;
			}
			$is_wp_database = $database === $wpdb->dbname;

			if ( isset( $_REQUEST['action'] ) ) {
				$action = sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ); // input var okay.

				// Security check.
				$wp_nonce = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ) : ''; // input var okay.
				if ( ! wp_verify_nonce( $wp_nonce, 'wpda-back-end-settings' ) ) {
					wp_die( __( 'ERROR: Not authorized', 'wp-data-access' ) );
				}

				if ( 'save' === $action ) {
					// Save options.
					if ( $is_wp_database ) {
						WPDA::set_option(
							WPDA::OPTION_BE_TABLE_ACCESS,
							isset( $_REQUEST['table_access'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['table_access'] ) ) : null // input var okay.
						);
					} else {
						update_option(
							WPDA::BACKEND_OPTIONNAME_DATABASE_ACCESS . $database,
							isset( $_REQUEST['table_access'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['table_access'] ) ) : null // input var okay.
						);}

					$table_access_selected_new_value = isset( $_REQUEST['table_access_selected'] ) ? $_REQUEST['table_access_selected'] : null;
					if ( is_array( $table_access_selected_new_value ) ) {
						// Check the requested table names for sql injection. This is simply done by checking if the table
						// name exists in our WordPress database.
						$table_access_selected_new_value_checked = [];
						foreach ( $table_access_selected_new_value as $key => $value ) {
							$wpda_dictionary_checks = new WPDA_Dictionary_Exist( $database, $value );
							if ( $wpda_dictionary_checks->table_exists( false ) ) {
								// Add existing table to list.
								$table_access_selected_new_value_checked[ $key ] = $value;
							} else {
								// An invalid table name was provided. Might be an sql injection attack or an invalid state.
								wp_die( __( 'ERROR: Invalid table name', 'wp-data-access' ) );
							}
						}
					} else {
						$table_access_selected_new_value_checked = '';
					}
					if ( $is_wp_database ) {
						WPDA::set_option(
							WPDA::OPTION_BE_TABLE_ACCESS_SELECTED,
							$table_access_selected_new_value_checked
						);
					} else {
						update_option(
							WPDA::BACKEND_OPTIONNAME_DATABASE_SELECTED . $database,
							$table_access_selected_new_value_checked
						);
					}

					WPDA::set_option(
						WPDA::OPTION_BE_VIEW_LINK,
						isset( $_REQUEST['view_link'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['view_link'] ) ) : 'off' // input var okay.
					);

					WPDA::set_option(
						WPDA::OPTION_BE_ALLOW_INSERT,
						isset( $_REQUEST['allow_insert'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['allow_insert'] ) ) : 'off' // input var okay.
					);
					WPDA::set_option(
						WPDA::OPTION_BE_ALLOW_UPDATE,
						isset( $_REQUEST['allow_update'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['allow_update'] ) ) : 'off' // input var okay.
					);
					WPDA::set_option(
						WPDA::OPTION_BE_ALLOW_DELETE,
						isset( $_REQUEST['allow_delete'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['allow_delete'] ) ) : 'off' // input var okay.
					);

					WPDA::set_option(
						WPDA::OPTION_BE_EXPORT_ROWS,
						isset( $_REQUEST['export_rows'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['export_rows'] ) ) : 'off' // input var okay.
					);
					WPDA::set_option(
						WPDA::OPTION_BE_EXPORT_VARIABLE_PREFIX,
						isset( $_REQUEST['export_variable_rows'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['export_variable_rows'] ) ) : 'off' // input var okay.
					);

					WPDA::set_option(
						WPDA::OPTION_BE_ALLOW_IMPORTS,
						isset( $_REQUEST['allow_imports'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['allow_imports'] ) ) : 'off' // input var okay.
					);

					WPDA::set_option(
						WPDA::OPTION_BE_CONFIRM_EXPORT,
						isset( $_REQUEST['confirm_export'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['confirm_export'] ) ) : 'off' // input var okay.
					);
					WPDA::set_option(
						WPDA::OPTION_BE_CONFIRM_VIEW,
						isset( $_REQUEST['confirm_view'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['confirm_view'] ) ) : 'off' // input var okay.
					);

					WPDA::set_option(
						WPDA::OPTION_BE_PAGINATION,
						isset( $_REQUEST['pagination'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['pagination'] ) ) : null // input var okay.
					);

					WPDA::set_option(
						WPDA::OPTION_BE_REMEMBER_SEARCH,
						isset( $_REQUEST['remember_search'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['remember_search'] ) ) : 'off' // input var okay.
					);

					WPDA::set_option(
						WPDA::OPTION_BE_INNODB_COUNT,
						isset( $_REQUEST['innodb_count'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['innodb_count'] ) ) : 100000 // input var okay.
					);

					WPDA::set_option(
						WPDA::OPTION_BE_DESIGN_MODE,
						isset( $_REQUEST['design_mode'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['design_mode'] ) ) : 'basic' // input var okay.
					);

					WPDA::set_option(
						WPDA::OPTION_BE_TEXT_WRAP_SWITCH,
						isset( $_REQUEST['text_wrap_switch'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['text_wrap_switch'] ) ) : 'off' // input var okay.
					);

					WPDA::set_option(
						WPDA::OPTION_BE_TEXT_WRAP,
						isset( $_REQUEST['text_wrap'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['text_wrap'] ) ) : 400 // input var okay.
					);

					WPDA::set_option(
						WPDA::OPTION_BE_DEBUG,
						isset( $_REQUEST['debug'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['debug'] ) ) : 'off' // input var okay.
					);
				} elseif ( 'setdefaults' === $action ) {
					// Set all back-end settings back to default.
					if ( $is_wp_database ) {
						WPDA::set_option( WPDA::OPTION_BE_TABLE_ACCESS );
						WPDA::set_option( WPDA::OPTION_BE_TABLE_ACCESS_SELECTED );
					} else {
						update_option(
							WPDA::BACKEND_OPTIONNAME_DATABASE_ACCESS . $database,
							'show'
						);
						update_option(
							WPDA::BACKEND_OPTIONNAME_DATABASE_SELECTED . $database,
							''
						);
					}
					WPDA::set_option( WPDA::OPTION_BE_VIEW_LINK );
					WPDA::set_option( WPDA::OPTION_BE_ALLOW_INSERT );
					WPDA::set_option( WPDA::OPTION_BE_ALLOW_UPDATE );
					WPDA::set_option( WPDA::OPTION_BE_ALLOW_DELETE );
					WPDA::set_option( WPDA::OPTION_BE_EXPORT_ROWS );
					WPDA::set_option( WPDA::OPTION_BE_EXPORT_VARIABLE_PREFIX );
					WPDA::set_option( WPDA::OPTION_BE_ALLOW_IMPORTS );
					WPDA::set_option( WPDA::OPTION_BE_CONFIRM_EXPORT );
					WPDA::set_option( WPDA::OPTION_BE_CONFIRM_VIEW );
					WPDA::set_option( WPDA::OPTION_BE_PAGINATION );
					WPDA::set_option( WPDA::OPTION_BE_REMEMBER_SEARCH );
					WPDA::set_option( WPDA::OPTION_BE_INNODB_COUNT );
					WPDA::set_option( WPDA::OPTION_BE_DESIGN_MODE );
					WPDA::set_option( WPDA::OPTION_BE_TEXT_WRAP_SWITCH );
					WPDA::set_option( WPDA::OPTION_BE_TEXT_WRAP );
					WPDA::set_option( WPDA::OPTION_BE_DEBUG );
				}

				$msg = new WPDA_Message_Box(
					[
						'message_text' => __( 'Settings saved', 'wp-data-access' ),
					]
				);
				$msg->box();
			}

			// Get options.
			if ( $is_wp_database ) {
				$table_access          = WPDA::get_option( WPDA::OPTION_BE_TABLE_ACCESS );
				$table_access_selected = WPDA::get_option( WPDA::OPTION_BE_TABLE_ACCESS_SELECTED );
			} else {
				$table_access = get_option( WPDA::BACKEND_OPTIONNAME_DATABASE_ACCESS . $database );
				if ( false === $table_access ) {
					$table_access = 'show';
				}
				$table_access_selected = get_option( WPDA::BACKEND_OPTIONNAME_DATABASE_SELECTED . $database );
				if ( false === $table_access_selected ) {
					$table_access_selected = '';
				}
			}

			if ( is_array( $table_access_selected ) ) {
				// Convert table for simple access.
				$table_access_selected_by_name = [];
				foreach ( $table_access_selected as $key => $value ) {
					$table_access_selected_by_name[ $value ] = true;
				}
			}

			$view_link = WPDA::get_option( WPDA::OPTION_BE_VIEW_LINK );

			$allow_insert = WPDA::get_option( WPDA::OPTION_BE_ALLOW_INSERT );
			$allow_update = WPDA::get_option( WPDA::OPTION_BE_ALLOW_UPDATE );
			$allow_delete = WPDA::get_option( WPDA::OPTION_BE_ALLOW_DELETE );

			$export_rows          = WPDA::get_option( WPDA::OPTION_BE_EXPORT_ROWS );
			$export_variable_rows = WPDA::get_option( WPDA::OPTION_BE_EXPORT_VARIABLE_PREFIX );

			$allow_imports = WPDA::get_option( WPDA::OPTION_BE_ALLOW_IMPORTS );

			$confirm_export = WPDA::get_option( WPDA::OPTION_BE_CONFIRM_EXPORT );
			$confirm_view   = WPDA::get_option( WPDA::OPTION_BE_CONFIRM_VIEW );

			$pagination = WPDA::get_option( WPDA::OPTION_BE_PAGINATION );

			$remember_search = WPDA::get_option( WPDA::OPTION_BE_REMEMBER_SEARCH );

			$innodb_count = WPDA::get_option( WPDA::OPTION_BE_INNODB_COUNT );

			$design_mode = WPDA::get_option( WPDA::OPTION_BE_DESIGN_MODE );

			$text_wrap_switch = WPDA::get_option( WPDA::OPTION_BE_TEXT_WRAP_SWITCH );
			$text_wrap        = WPDA::get_option( WPDA::OPTION_BE_TEXT_WRAP );

			$debug = WPDA::get_option( WPDA::OPTION_BE_DEBUG );
			?>
			<form id="wpda_settings_backend" method="post"
				  action="?page=<?php echo esc_attr( $this->page ); ?>&tab=backend">
				<table class="wpda-table-settings">
					<tr>
						<th><?php echo __( 'Selected database', 'wp-data-access' ); ?></th>
						<td>
							<select name="database" id="schema_name">
								<?php
								$schema_names   = WPDA_Dictionary_Lists::get_db_schemas();
								foreach ( $schema_names as $schema_name ) {
									$selected = $database === $schema_name['schema_name'] ? ' selected' : '';
									echo "<option value='{$schema_name['schema_name']}'$selected>{$schema_name['schema_name']}</option>";
								}
								?>
							</select>
							<?php echo __( '(reflects table access only)', 'wp-data-access' ); ?>
						</td>
					</tr>
					<tr>
						<th><?php echo __( 'Table access', 'wp-data-access' ); ?></th>
						<td>
							<label>
								<input
									type="radio"
									name="table_access"
									value="show"
									<?php echo 'show' === $table_access ? 'checked' : ''; ?>
								><?php echo $is_wp_database? __( 'Show WordPress tables', 'wp-data-access' ) : __( 'Show all tables', 'wp-data-access' ); ?>
							</label>
							<br/>
							<?php
							if ( $is_wp_database ) {
								?>
								<label>
									<input
											type="radio"
											name="table_access"
											value="hide"
										<?php echo 'hide' === $table_access ? 'checked' : ''; ?>
									><?php echo __( 'Hide WordPress tables', 'wp-data-access' ); ?>
								</label>
								<br/>
								<?php
							}
							?>
							<label>
								<input
									type="radio"
									name="table_access"
									value="select"
									<?php echo 'select' === $table_access ? 'checked' : ''; ?>
								><?php echo __( 'Show only selected tables', 'wp-data-access' ); ?>
							</label>
							<div id="tables_selected" <?php echo 'select' === $table_access ? '' : 'style="display:none"'; ?>>
								<br/>
								<select name="table_access_selected[]" multiple size="10">
									<?php
									$tables = WPDA_Dictionary_Lists::get_tables( true, $database );
									foreach ( $tables as $table ) {
										$table_name = $table['table_name'];
										?>
										<option value="<?php echo esc_attr( $table_name ); ?>" <?php echo isset( $table_access_selected_by_name[ $table_name ] ) ? 'selected' : ''; ?>><?php echo esc_attr( $table_name ); ?></option>
										<?php
									}
									?>
								</select>
							</div>
							<script type='text/javascript'>
								jQuery(document).ready(function () {
									jQuery("input[name='table_access']").on("click", function () {
										if (this.value == 'select') {
											jQuery("#tables_selected").show();
										} else {
											jQuery("#tables_selected").hide();
										}
									});
									jQuery('#schema_name').on('change', function() {
										window.location = '?page=<?php echo esc_attr( $this->page ); ?>&tab=backend&database=' + jQuery(this).val();
									});
								});
							</script>
						</td>
					</tr>
					<tr>
						<th><?php echo __( 'Row access', 'wp-data-access' ); ?></th>
						<td>
							<label>
								<input
										type="checkbox"
										name="view_link"
									<?php echo 'on' === $view_link ? 'checked' : ''; ?>
								><?php echo __( 'Add view link to list table', 'wp-data-access' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo __( 'Allow transactions?', 'wp-data-access' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="allow_insert"
									<?php echo 'on' === $allow_insert ? 'checked' : ''; ?> /><?php echo __( 'Allow insert', 'wp-data-access' ); ?>
							</label>
							<br/>
							<label>
								<input type="checkbox" name="allow_update"
									<?php echo 'on' === $allow_update ? 'checked' : ''; ?> /><?php echo __( 'Allow update', 'wp-data-access' ); ?>
							</label>
							<br/>
							<label>
								<input type="checkbox" name="allow_delete"
									<?php echo 'on' === $allow_delete ? 'checked' : ''; ?> /><?php echo __( 'Allow delete', 'wp-data-access' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo __( 'Allow exports?', 'wp-data-access' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="export_rows"
									<?php echo 'on' === $export_rows ? 'checked' : ''; ?> /><?php echo __( 'Allow row export', 'wp-data-access' ); ?>
							</label>
							<br/>
							<label>
								<input type="checkbox" name="export_variable_rows"
									<?php echo 'on' === $export_variable_rows ? 'checked' : ''; ?> /><?php echo __( 'Export with variable WP prefix', 'wp-data-access' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo __( 'Allow imports?', 'wp-data-access' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="allow_imports"
									<?php echo 'on' === $allow_imports ? 'checked' : ''; ?> /><?php echo __( 'Allow to import scripts from Data Explorer table pages', 'wp-data-access' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th><?php echo __( 'Ask for confirmation?', 'wp-data-access' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="confirm_export"
									<?php echo 'on' === $confirm_export ? 'checked' : ''; ?> /><?php echo __( 'When starting export', 'wp-data-access' ); ?>
							</label>
							<br/>
							<label>
								<input type="checkbox" name="confirm_view"
									<?php echo 'on' === $confirm_view ? 'checked' : ''; ?> /><?php echo __( 'When viewing non WPDA table', 'wp-data-access' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th><?php echo __( 'Default pagination value', 'wp-data-access' ); ?></th>
						<td>
							<input
									type="number" step="1" min="1" max="999" name="pagination" maxlength="3"
									value="<?php echo esc_attr( $pagination ); ?>">
						</td>
					</tr>
					<tr>
						<th><?php echo __( 'Search box', 'wp-data-access' ); ?></th>
						<td>
							<label>
								<input
										type="checkbox"
										name="remember_search" <?php echo 'on' === $remember_search ? 'checked' : ''; ?>
								><?php echo __( 'Remember last search', 'wp-data-access' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th><?php echo __( 'Max InnoDB row count', 'wp-data-access' ); ?></th>
						<td>
							<input
									type="number" step="1" min="1" max="999999" name="innodb_count" maxlength="3"
									value="<?php echo esc_attr( $innodb_count ); ?>">
						</td>
					</tr>
					<tr>
						<th><?php echo __( 'Default designer mode', 'wp-data-access' ); ?></th>
						<td>
							<select name="design_mode">
								<option value="basic" <?php echo 'basic' === $design_mode ? 'selected' : ''; ?>>Basic
								</option>
								<option value="advanced" <?php echo 'advanced' === $design_mode ? 'selected' : ''; ?>>
									Advanced
								</option>
							</select>
						</td>
					</tr>
					<tr>
						<th><?php echo __( 'Content wrap', 'wp-data-access' ); ?></th>
						<td>
							<label>
								<input
										type="checkbox"
										name="text_wrap_switch" <?php echo 'on' === $text_wrap_switch ? 'checked' : ''; ?>
								><?php echo __( 'No content wrap', 'wp-data-access' ); ?>
							</label>
							<br/>
							<input
									type="number" step="1" min="1" max="999999" name="text_wrap" maxlength="3"
									value="<?php echo esc_attr( $text_wrap ); ?>">
						</td>
					</tr>
					<tr>
						<th><?php echo __( 'Debug mode', 'wp-data-access' ); ?></th>
						<td>
							<label>
								<input
										type="checkbox"
										name="debug" <?php echo 'on' === $debug ? 'checked' : ''; ?>
								><?php echo __( 'Plugin dashboard debug mode', 'wp-data-access' ); ?>
							</label>
						</td>
					</tr>
				</table>
				<div class="wpda-table-settings-button">
					<input type="hidden" name="action" value="save"/>
					<input type="submit" value="<?php echo __( 'Save Back-end Settings', 'wp-data-access' ); ?>"
						   class="button button-primary"/>
					<a href="javascript:void(0)"
					   onclick="if (confirm('<?php echo __( 'Reset to defaults?', 'wp-data-access' ); ?>')) {
							   jQuery('input[name=&quot;action&quot;]').val('setdefaults');
							   jQuery('#wpda_settings_backend').trigger('submit')
							   }"
					   class="button">
						<?php echo __( 'Reset Back-end Settings To Defaults', 'wp-data-access' ); ?>
					</a>
				</div>
				<?php wp_nonce_field( 'wpda-back-end-settings', '_wpnonce', false ); ?>
			</form>

			<?php

		}

	}

}
