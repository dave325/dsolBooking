<?php

/**
 * Suppress "error - 0 - No summary was found for this file" on phpdoc generation
 *
 * @package plugin\includes
 */

use WPDataAccess\Connection\WPDADB;
use WPDataAccess\Cookies\WPDA_Cookies;
use WPDataAccess\Data_Dictionary\WPDA_Dictionary_Lists;
use WPDataAccess\Data_Tables\WPDA_Data_Tables;
use WPDataAccess\Utilities\WPDA_Table_Actions;
use WPDataAccess\Utilities\WPDA_Example;
use WPDataAccess\Utilities\WPDA_Export;
use WPDataAccess\Utilities\WPDA_Favourites;
use WPDataProjects\Utilities\WPDP_Export_Project;
use WPDataAccess\Backup\WPDA_Data_Export;
use WPDataAccess\Settings\WPDA_Settings;
use WPDataRoles\WPDA_Roles;

/**
 * Class WP_Data_Access
 *
 * Core plugin class used to define:
 * + admin specific functionality {@see WP_Data_Access_Admin}
 * + public specific functionality {@see WP_Data_Access_Public}
 * + internationalization {@see WP_Data_Access_I18n}
 * + plugin activation and deactivation {@see WP_Data_Access_Loader}
 *
 * @author  Peter Schulz
 * @since   1.0.0
 *
 * @see WP_Data_Access_Admin
 * @see WP_Data_Access_Public
 * @see WP_Data_Access_I18n
 * @see WP_Data_Access_Loader
 */
class WP_Data_Access {

	/**
	 * Reference to plugin loader
	 *
	 * @var WP_Data_Access_Loader
	 *
	 * @since   1.0.0
	 *
	 * @see WP_Data_Access_Loader
	 */
	protected $loader;

	/**
	 * WP_Data_Access constructor
	 *
	 * Calls method the following methods to setup plugin:
	 * + {@see WP_Data_Access::load_dependencies()}
	 * + {@see WP_Data_Access::set_locale()}
	 * + {@see WP_Data_Access::define_admin_hooks()}
	 * + {@see WP_Data_Access::define_public_hooks()}
	 *
	 * @since   1.0.0
	 *
	 * @see WP_Data_Access::load_dependencies()
	 * @see WP_Data_Access::set_locale()
	 * @see WP_Data_Access::define_admin_hooks()
	 * @see WP_Data_Access::define_public_hooks()
	 */
	public function __construct() {
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load required dependencies
	 *
	 * Loads required plugin files and initiates the plugin loader.
	 *
	 * @since   1.0.0
	 *
	 * @see WP_Data_Access_Loader
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-data-access-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-data-access-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-data-access-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-data-access-public.php';

		$this->loader = new WP_Data_Access_Loader();
	}

	/**
	 * Set locale for internationalization
	 *
	 * @since   1.0.0
	 *
	 * @see WP_Data_Access_I18n
	 */
	private function set_locale() {
		$wpda_i18n = new WP_Data_Access_I18n();
		$this->loader->add_action( 'init', $wpda_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Add admin hooks
	 *
	 * Initiates {@see WP_Data_Access_Admin} (admin functionality), {@see WPDA_Export} (export functionality) and
	 * {@see WPDA_Example} (example plugin that demostrates the use of WP Data Access by code from another plugin).
	 * Adds the appropriate actions to the loader.
	 *
	 * @since   1.0.0
	 *
	 * @see WP_Data_Access_Admin
	 * @see WPDA_Export
	 * @see WPDA_Example
	 */
	private function define_admin_hooks() {
		$plugin_admin = new WP_Data_Access_Admin();

		// Handle plugin cookies.
		$wpda_cookies = new WPDA_Cookies();
		$this->loader->add_action( 'admin_init', $wpda_cookies, 'handle_plugin_cookies' );

		// Admin menu.
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_items' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu_my_tables', 11 );

		// Add external public Plugin Help page to menu.
		$this->loader->add_action( 'admin_footer', $plugin_admin, 'add_admin_footer' );

		// Admin scripts.
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Add settings page
		$this->loader->add_action('admin_menu', $this, 'wpdataaccess_register_settings_page');

		// Export action.
		$plugin_export = new WPDA_Export();
		$this->loader->add_action( 'admin_action_wpda_export', $plugin_export, 'export' );

		// Example requested.
		$plugin_example = new WPDA_Example();
		$this->loader->add_action( 'admin_action_wpda_example', $plugin_example, 'get_example' );

		// Add/remove favourites.
		$plugin_favourites = new WPDA_Favourites();
		$this->loader->add_action( 'admin_action_add_favourite', $plugin_favourites, 'add' );
		$this->loader->add_action( 'admin_action_rem_favourite', $plugin_favourites, 'rem' );

		// Show tables actions.
		$plugin_table_actions = new WPDA_Table_Actions();
		$this->loader->add_action( 'admin_action_show_table_actions', $plugin_table_actions, 'show' );

		$plugin_dictionary_list = new WPDA_Dictionary_Lists();
		// Get tables for a specific database.
		$this->loader->add_action( 'admin_action_get_tables', $plugin_dictionary_list, 'get_tables_ajax' );
		// Get columns for a specific table.
		$this->loader->add_action( 'admin_action_get_columns', $plugin_dictionary_list, 'get_columns' );

		// Export project.
		$plugin_export_project = new WPDP_Export_Project();
		$this->loader->add_action( 'admin_action_wpda_export_project', $plugin_export_project, 'export' );

		// Data backup.
		$wpda_data_backup = new WPDA_Data_Export();
		$this->loader->add_action( 'wpda_data_backup', $wpda_data_backup, 'wpda_data_backup' );

		// Allow to add multiple user roles
		$wpda_roles = new WPDA_Roles();
		$this->loader->add_action( 'user_new_form', $wpda_roles, 'multiple_roles_selection' );
		$this->loader->add_action( 'edit_user_profile', $wpda_roles, 'multiple_roles_selection' );
		$this->loader->add_action( 'profile_update', $wpda_roles, 'multiple_roles_update' );
		$this->loader->add_filter( 'manage_users_columns', $wpda_roles, 'multiple_roles_label' );

		// Check if a remote db connection can be established via ajax
		$wpdadb = new WPDADB();
		$this->loader->add_action( 'admin_action_check_remote_database_connection', $wpdadb, 'check_remote_database_connection' );
	}

	/**
	 * Add public hooks
	 *
	 * Initiates {@see WP_Data_Access_Public} (public functionality), {@see WPDA_Data_Tables} (ajax call to support
	 * server side jQuery DataTables functionality). Adds the appropriate actions to
	 * the loader.
	 *
	 * @since   1.0.0
	 *
	 * @see WP_Data_Access_Public
	 * @see WPDA_Data_Tables
	 * @see WPDA_Dictionary_Lists
	 */
	private function define_public_hooks() {
		$plugin_public = new WP_Data_Access_Public();

		// Handle plugin cookies.
		$wpda_cookies = new WPDA_Cookies();
		$this->loader->add_action( 'init', $wpda_cookies, 'handle_plugin_cookies' );

		// Shortcodes.
		$this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );

		// Public scripts.
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Ajax calls.
		$plugin_datatables = new WPDA_Data_Tables();
		$this->loader->add_action( 'wp_ajax_wpda_datatables', $plugin_datatables, 'get_data' );
		$this->loader->add_action( 'wp_ajax_nopriv_wpda_datatables', $plugin_datatables, 'get_data' );
	}

	/**
	 * Start plugin loader
	 *
	 * @since   1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * Add plugin settings page
	 */
	public function wpdataaccess_register_settings_page() {
		add_options_page(
			'WP Data Access',
			'WP Data Access',
			'manage_options',
			'wpdataaccess',
			[
				$this,
				'wpdataaccess_settings_page'
			]
		);
	}

	/**
	 * Show settings page
	 */
	public function wpdataaccess_settings_page() {
		$wpda_settings = new WPDA_Settings();
		$wpda_settings->show();
	}

}
