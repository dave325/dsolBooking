<?php

/**
 * Suppress "error - 0 - No summary was found for this file" on phpdoc generation
 *
 * @package WPDataProjects
 */

namespace WPDataProjects {

	use WPDataAccess\Plugin_Table_Models\WPDP_Page_Model;
	use WPDataAccess\Plugin_Table_Models\WPDP_Project_Model;
	use WPDataAccess\Plugin_Table_Models\WPDP_Project_Design_Table_Model;
	use WPDataAccess\Utilities\WPDA_Repository;
	use WPDataAccess\WPDA;
	use WPDataProjects\Project\WPDP_Project_Project_View;
	use WPDataProjects\Project\WPDP_Project_Table_View;

	/**
	 * Class WPDP
	 *
	 * Implements Data Projects page. The page consist of a number of tabs ($this->tabs) and uses the following list
	 * views:
	 * (1) WPDP_Project_Project_View - To manage Data Projects
	 * (2) WPDP_Project_Table_View   - To manage Table Options
	 *
	 * @author  Peter Schulz
	 * @since   2.0.0
	 */
	class WPDP {

		/**
		 * Menu slug of Data Project page
		 */
		const PAGE_MAIN = 'wpda_wpdp';

		/**
		 * Page and menu title
		 */
		const PAGE_TITLE = 'Data Projects';

		/**
		 * Menu slug taken from URL
		 *
		 * @var null
		 */
		protected $page = null;

		/**
		 * Available tabs
		 *
		 * @var array
		 */
		protected $tabs;

		/**
		 * Current tab
		 *
		 * @var
		 */
		protected $current_tab;

		/**
		 * Data Projects menu
		 *
		 * @var
		 */
		protected $wpdp_projects_menu;

		/**
		 * Handle to view (depends on current tab)
		 *
		 * @var
		 */
		protected $wpdp_projects_view;

		/**
		 * Used for static pages
		 *
		 * @var
		 */
		protected $wpdp_projects_content;

		/**
		 * Arrary containing all project pages
		 *
		 * @var
		 */
		protected $wpdp_project_menus;

		/**
		 * Array containing all project page views
		 *
		 * @var
		 */
		protected $wpdp_project_views;

		/**
		 * WPDP constructor
		 *
		 * (1) Determine menu slug
		 * (2) Set tabs
		 * (3) Set links
		 * (4) Determine current tab
		 */
		public function __construct() {
			if ( isset( $_REQUEST['page'] ) ) {
				$this->page = sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ); // input var okay.
			}

			$this->tabs       = [
				'projects' => __( 'Manage Projects', 'wp-data-access' ),
				'tables'   => __( 'Manage Table Options', 'wp-data-access' ),
			];

			$this->current_tab = 'projects';
			if ( isset( $_REQUEST['tab'] ) ) {
				$tab = sanitize_text_field( wp_unslash( $_REQUEST['tab'] ) ); // input var okay.
				if ( isset( $this->tabs[ $tab ] ) ) {
					$this->current_tab = $tab;
				}
			}
		}

		/**
		 * Add menu items
		 *
		 * Adds Data Projects tool to dashboard menu and determines which tab should be shown.
		 */
		public function add_menu_items() {
			if ( current_user_can( 'manage_options' ) ) {
				if ( 'on' === WPDA::get_option( WPDA::OPTION_PLUGIN_HIDE_ADMIN_MENU ) ) {
					// Hide admin menu
					return;
				}

				global $wpdb;

				// Check for repository tables to prevent dashboard errors.
				$repository_tables_found =
					WPDP_Project_Design_Table_Model::table_exists() &&
					WPDP_Project_Model::table_exists() &&
					WPDP_Page_Model::table_exists();
				if ( $repository_tables_found ) {
					$data_projects_page = 'data_projects_page';
				} else {
					$data_projects_page = 'data_projects_page_not_found';
				}
				$this->wpdp_projects_menu = add_submenu_page(
					\WP_Data_Access_Admin::PAGE_MAIN,
					self::PAGE_TITLE,
					self::PAGE_TITLE,
					'manage_options',
					self::PAGE_MAIN,
					[ $this, $data_projects_page ]
				);
				if ( $this->page === self::PAGE_MAIN && $repository_tables_found ) {
					if ( $this->current_tab === 'tables' ) {
						$this->wpdp_projects_view = new WPDP_Project_Table_View(
							[
								'page_hook_suffix' => $this->wpdp_projects_menu,
								'table_name'       => $wpdb->prefix . 'wpda_project_table',
								'list_table_class' => 'WPDataProjects\\Project\\WPDP_Project_Table_List',
								'edit_form_class'  => 'WPDataProjects\\Project\\WPDP_Project_Table_Form',
								'subtitle'         => '',
							]
						);
					} else {
						$this->wpdp_projects_view = new WPDP_Project_Project_View (
							[
								'page_hook_suffix' => $this->wpdp_projects_menu,
								'table_name'       => $wpdb->prefix . 'wpda_project',
								'edit_form_class'  => 'WPDataProjects\\Project\\WPDP_Project_Project_Form',
								'list_table_class' => 'WPDataProjects\\Project\\WPDP_Project_Project_List',
							]
						);
					}
				}
			}
		}

		/**
		 * Implementation of the Data Projects page
		 */
		public function data_projects_page() {
			?>
			<div class="wrap">
				<h1 class="wp-heading-inline">
					<span><?php echo self::PAGE_TITLE; ?></span>
					<a href="<?php echo 'https://wpdataaccess.com/docs/documentation/data-projects/'; ?>" target="_blank" title="Plugin Help - open a new tab or window">
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
		 * Data Designer repository table not found
		 */
		public function data_projects_page_not_found() {
			$wpda_repository = new WPDA_Repository();
			$wpda_repository->inform_user();
			?>
			<div class="wrap">
				<h1 class="wp-heading-inline">
					<span><?php echo self::PAGE_TITLE; ?></span>
					<a href="<?php echo 'https://wpdataaccess.com/docs/documentation/data-projects/'; ?>" target="_blank">
						<span class="dashicons dashicons-editor-help"
							  style="text-decoration:none;vertical-align:top;font-size:36px;">
						</span></a>
				</h1>
				<p>
					<?php echo __( 'ERROR: Repository table(s) not found!', 'wp-data-access' ); ?>
				</p>
			</div>
			<?php
		}

		/**
		 * Adds tabs to page
		 */
		protected function add_tabs() {
			?>
			<h2 class="nav-tab-wrapper">
				<?php
				foreach ( $this->tabs as $tab => $name ) {
					$class = ( $tab === $this->current_tab ) ? ' nav-tab-active' : '';
					echo '<a class="nav-tab' . esc_attr( $class ) . '" href="?page=' . esc_attr( self::PAGE_MAIN ) .
					     '&tab=' . esc_attr( $tab ) . '">' . esc_attr( $name ) .
					     '</a>';
				}
				?>
			</h2>
			<?php
		}

		/**
		 * Add static page content
		 */
		protected function add_content() {
			if ( null !== $this->wpdp_projects_view ) {
				$this->wpdp_projects_view->show();
			} else {
				wp_die( __( 'ERROR: Option not available', 'wp-data-access' ) );
			}
		}

		/**
		 * Add projects to menu
		 *
		 * Menu items are taken from active projects. Project pages marked as "add to menu" are added to the
		 * dashboard menu.
		 */
		public function add_projects() {
			// Add project Menus.
			global $wpdb;

			// Check for repository tables to prevent dashboard errors.
			if ( ! WPDP_Project_Design_Table_Model::table_exists() ||
			     ! WPDP_Project_Model::table_exists() ||
				 ! WPDP_Page_Model::table_exists()
			) {
				return;
			}

			$project_project_table_name = $wpdb->prefix . 'wpda_project';
			$project_page_table_name    = $wpdb->prefix . 'wpda_project_page';

			$query_projects = "select * from $project_project_table_name where add_to_menu = 'Yes' order by project_sequence";
			$projects       = $wpdb->get_results( $query_projects, 'ARRAY_A' ); // WPCS: unprepared SQL OK; db call ok; no-cache ok.

			foreach ( $projects as $project ) {
				$menu_name  = $project['menu_name'];
				$user_roles = WPDA::get_current_user_roles();
				if ( false === $user_roles ) {
					// Cannot determine the user role(s). Not able to show project menus.
					break;
				}
				$query_pages = $wpdb->prepare(
					" select * from $project_page_table_name " .
					" where project_id = %d " .
					" and add_to_menu = 'Yes' " .
					" order by page_sequence",
					[
						$project['project_id'],
					]
				);
				$pages       = $wpdb->get_results( $query_pages, 'ARRAY_A' ); // WPCS: unprepared SQL OK; db call ok; no-cache ok.

				$project_menu_shown = false;
				foreach ( $pages as $page ) {
					$user_has_role = false;
					if ( '' === $page['page_role'] || null === $page['page_role'] ) {
						$user_has_role = in_array( 'administrator', $user_roles );
					} else {
						$user_role_array = explode( ',', $page['page_role'] );
						foreach ( $user_role_array as $user_role_array_item ) {
							$user_has_role = in_array( $user_role_array_item, $user_roles );
							if ( $user_has_role ) {
								break;
							}
						}
					}

					if ( $user_has_role ) {
						$page_name        = self::PAGE_MAIN . '_' . $page['project_id'] . '_' . $page['page_id'];
						$page_schema_name = $page['page_schema_name'];
						$page_table_name  = $page['page_table_name'];
						$page_type        = $page['page_type'];

						if ( ! $project_menu_shown ) {
							$main_page_name = $page_name;
							add_menu_page(
								$menu_name,
								$menu_name,
								WPDA::get_current_user_capability(),
								$main_page_name,
								null,
								'dashicons-editor-table'
							);
							$project_menu_shown = true;
						}

						$this->wpdp_project_menus[ $page['project_id'] . '_' . $page['page_id'] ] =
							add_submenu_page(
								$main_page_name,
								$menu_name,
								$page['page_name'],
								WPDA::get_current_user_capability(),
								$page_name,
								[ $this, 'manage_project_page' ]
							);

						if ( $this->page === self::PAGE_MAIN . '_' . $page['project_id'] . '_' . $page['page_id'] ) {
							if ( 'static' !== $page_type && null !== $page['page_where'] && '' !== $page['page_where'] ) {
								if ( 'where' === substr( str_replace( ' ', '', $page['page_where'] ), 0, 5 ) ) {
									$where_clause = " {$page['page_where']}";
								} else {
									$where_clause = " where {$page['page_where']} ";
								}
								$where_clause = WPDA::substitute_environment_vars( $where_clause );
							} else {
								$where_clause = '';
							}

							switch ( $page_type ) {
								case 'static':
									$this->wpdp_projects_content[ $page['project_id'] . '_' . $page['page_id'] ] =
										$page['page_content'];
									break;
								case 'table':
									$args = [
										'page_hook_suffix' => $this->wpdp_project_menus[ $page['project_id'] . '_' . $page['page_id'] ],
										'schema_name'      => $page_schema_name,
										'table_name'       => $page_table_name,
										'project_id'       => $page['project_id'],
										'page_id'          => $page['page_id'],
										'list_table_class' => 'WPDataProjects\\List_Table\\WPDP_List_Table',
										'edit_form_class'  => 'WPDataProjects\\Simple_Form\\WPDP_Simple_Form',
										'where_clause'     => $where_clause,
									];
									if ( 'view' === $page['page_mode'] ) {
										$args['allow_update'] = 'off';
										$args['allow_import'] = 'off';
									}
									if ( 'no' === $page['page_allow_insert'] ) {
										$args['allow_insert'] = 'off';
									}
									if ( 'no' === $page['page_allow_delete'] ) {
										$args['allow_delete'] = 'off';
									}
									if ( 'only' === $page['page_allow_insert'] ) {
										$args['action']       = 'new';
										$args['allow_insert'] = 'only';
										$args['allow_update'] = 'off';
										$args['allow_import'] = 'off';
										$args['allow_delete'] = 'off';
									}
									$this->wpdp_project_views[ $page['project_id'] . '_' . $page['page_id'] ] =
										new \WPDataProjects\List_Table\WPDP_List_View( $args );
									break;
								case 'parent/child':
									$args = [
										'page_hook_suffix' => $this->wpdp_project_menus[ $page['project_id'] . '_' . $page['page_id'] ],
										'schema_name'      => $page_schema_name,
										'table_name'       => $page_table_name,
										'list_table_class' => 'WPDataProjects\\Parent_Child\\WPDP_Parent_List_Table',
										'edit_form_class'  => 'WPDataProjects\\Parent_Child\\WPDP_Parent_Form',
										'project_id'       => $page['project_id'],
										'page_id'          => $page['page_id'],
										'where_clause'     => $where_clause,
									];
									if ( 'view' === $page['page_mode'] ) {
										$args['allow_update'] = 'off';
										$args['allow_import'] = 'off';
									}
									if ( 'no' === $page['page_allow_insert'] ) {
										$args['allow_insert'] = 'off';
									}
									if ( 'no' === $page['page_allow_delete'] ) {
										$args['allow_delete'] = 'off';
									}
									if ( 'only' === $page['page_allow_insert'] ) {
										$args['action']       = 'new';
										$args['allow_insert'] = 'only';
										$args['allow_update'] = 'off';
										$args['allow_import'] = 'off';
										$args['allow_delete'] = 'off';
									}
									$this->wpdp_project_views[ $page['project_id'] . '_' . $page['page_id'] ] =
										new \WPDataProjects\Parent_Child\WPDP_Parent_List_View( $args );
							}
						}
					}
				}
			}

		}

		/**
		 * Manage project page
		 */
		public function manage_project_page() {
			$ids = explode( '_', $this->page );

			if ( 4 !== count( $ids ) ) {
				wp_die( __( 'ERROR: Wrong arguments [missing page]', 'wp-data-access' ) );
			}

			$project_id = $ids[2];
			$page_id    = $ids[3];

			if ( isset( $this->wpdp_project_views[ $project_id . '_' . $page_id ] ) ) {
				$this->wpdp_project_views[ $project_id . '_' . $page_id ]->show();
			} else {
				if ( isset( $this->wpdp_projects_content[ $project_id . '_' . $page_id ] ) ) {
					$post_id = $this->wpdp_projects_content[ $project_id . '_' . $page_id ];
					$post    = get_post( $post_id );
					$content = $post->post_content;
					$content = apply_filters( 'the_content', $content );
					$content = str_replace( ']]>', ']]&gt;', $content );
					echo $content;
				} else {
					wp_die( __( 'ERROR: Project page initialization failed', 'wp-data-access' ) );
				}
			}
		}

	}

}
