<?php
/**
 * Admin Settings Page
 */

if( ! defined( 'ABSPATH' ) ) exit(); // Exit if accessed directly

class Eael_Admin_Settings {

	/**
	 * Contains Default Component keys
	 * @var array
	 * @since 2.3.0
	 */
	public $eael_default_keys = [ 'contact-form-7', 'count-down', 'counter', 'creative-btn', 'divider', 'fancy-text', 'img-comparison', 'instagram-gallery', 'interactive-promo',  'lightbox', 'post-block', 'post-grid', 'post-carousel', 'post-timeline', 'product-grid', 'team-members', 'testimonial-slider', 'testimonials', 'testimonials', 'weforms', 'static-product', 'call-to-action', 'flip-box', 'info-box', 'dual-header', 'price-table', 'price-menu', 'flip-carousel', 'interactive-cards', 'ninja-form', 'content-timeline', 'gravity-form', 'data-table', 'caldera-form','twitter-feed', 'twitter-feed-carousel', 'facebook-feed', 'facebook-feed-carousel', 'filter-gallery', 'dynamic-filter-gallery', 'content-ticker', 'image-accordion', 'post-list', 'tooltip', 'adv-tabs', 'adv-accordion', 'adv-google-map', 'mailchimp', 'toggle', 'one-page-navigation', 'team-member-carousel', 'image-hotspots', 'logo-carousel', 'wpforms', 'protected-content', 'progress-bar'  ];

	/**
	 * Will Contain All Components Default Values
	 * @var array
	 * @since 2.3.0
	 */
	private $eael_default_settings;

	/**
	 * Will Contain User End Settings Value
	 * @var array
	 * @since 2.3.0
	 */
	private $eael_settings;

	/**
	 * Will Contains Settings Values Fetched From DB
	 * @var array
	 * @since 2.3.0
	 */
	private $eael_get_settings;

	/**
	 * Initializing all default hooks and functions
	 * @param
	 * @return void
	 * @since 1.1.2
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'create_eael_admin_menu' ), 600 );
		add_action( 'init', array( $this, 'enqueue_eael_admin_scripts' ) );
		add_action( 'wp_ajax_save_settings_with_ajax', array( $this, 'eael_save_settings_with_ajax' ) );

	}

	/**
	 * Loading all essential scripts
	 * @param
	 * @return void
	 * @since 1.1.2
	 */
	public function enqueue_eael_admin_scripts() {

		if( isset( $_GET['page'] ) && ( $_GET['page'] == 'eael-settings' || $_GET['page'] == 'essential-addons-elementor-license' ) ) {
			wp_enqueue_style( 'essential_addons_elementor-admin-css', plugins_url( '/', __FILE__ ).'assets/css/admin.css' );
			wp_enqueue_style( 'font-awesome-css', plugins_url( '/', __FILE__ ).'assets/vendor/font-awesome/css/font-awesome.min.css' );
			wp_enqueue_script( 'essential_addons_elementor-admin-js', plugins_url( '/', __FILE__ ).'assets/js/admin.js', array( 'jquery', 'jquery-ui-tabs' ), '1.0', true );
			wp_enqueue_script( 'essential_addons_core-js', plugins_url( '/', __FILE__ ).'assets/vendor/sweetalert2/js/core.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_script( 'essential_addons_sweetalert2-js', plugins_url( '/', __FILE__ ).'assets/vendor/sweetalert2/js/sweetalert2.min.js', array( 'jquery', 'essential_addons_core-js' ), '1.0', true );

			$eael_admin_js_settings = array(
				'eael_google_api' => get_option('eael_save_google_map_api'),
				'eael_mailchimp_api' => get_option('eael_save_mailchimp_api'),
			);
			wp_localize_script( 'essential_addons_elementor-admin-js', 'eaelAdmin', $eael_admin_js_settings );
		}

	}

	/**
	 * Create an admin menu.
	 * @param
	 * @return void
	 * @since 1.1.2
	 */
	public function create_eael_admin_menu() {

		add_submenu_page(
			'elementor',
			'Essential Addons',
			'Essential Addons',
			'manage_options',
			'eael-settings',
			array( $this, 'eael_admin_settings_page' )
		);

	}

	/**
	 * Create settings page.
	 * @param
	 * @return void
	 * @since 1.1.2
	 */
	public function eael_admin_settings_page() {

		$js_info = array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
		);
		wp_localize_script( 'essential_addons_elementor-admin-js', 'js_eael_pro_settings', $js_info );

	   /**
	    * This section will handle the "eael_save_settings" array. If any new settings options is added
	    * then it will matches with the older array and then if it founds anything new then it will update the entire array.
	    */
	   $this->eael_default_settings = array_fill_keys( $this->eael_default_keys, true );
	   $this->eael_get_settings = get_option( 'eael_save_settings', $this->eael_default_settings );
	   $eael_new_settings = array_diff_key( $this->eael_default_settings, $this->eael_get_settings );
	   if( ! empty( $eael_new_settings ) ) {
	   	$eael_updated_settings = array_merge( $this->eael_get_settings, $eael_new_settings );
	   	update_option( 'eael_save_settings', $eael_updated_settings );
	   }
	   $this->eael_get_settings = get_option( 'eael_save_settings', $this->eael_default_settings );
		?>
		<div class="eael-settings-wrap">
		  	<form action="" method="POST" id="eael-settings" name="eael-settings">
		  		<div class="eael-header-bar">
					<div class="eael-header-left">
						<div class="eael-admin-logo-inline">
							<img src="<?php echo plugins_url( '/', __FILE__ ).'assets/images/ea-logo.svg'; ?>">
						</div>
						<h2 class="title"><?php _e( 'Essential Addons Settings', 'essential-addons-elementor' ); ?></h2>
					</div>
					<div class="eael-header-right">
						<button type="submit" class="button eael-btn js-eael-settings-save"><?php _e('Save settings', 'essential-addons-elementor'); ?></button>
					</div>
				</div>
			  	<div class="response-wrap"></div>
			  	<div class="eael-settings-tabs">
			    	<ul class="eael-tabs">
				      	<li><a href="#general" class="active"><img src="<?php echo plugins_url( '/', __FILE__ ).'assets/images/icon-settings.svg'; ?>"><span>General</span></a></li>
				      	<li><a href="#elements"><img src="<?php echo plugins_url( '/', __FILE__ ).'assets/images/icon-modules.svg'; ?>"><span>Elements</span></a></li>
			    	</ul>
			    	<div id="general" class="eael-settings-tab active">
						<div class="row eael-admin-general-wrapper">
			      			<div class="eael-admin-general-inner">
				      			<div class="eael-admin-block-wrapper">
				      				<div class="eael-admin-block eael-admin-block-license">
										<header class="eael-admin-block-header">
											<div class="eael-admin-block-header-icon">
												<svg height="36px" version="1.1" viewBox="0 0 32 32" width="36px" xmlns="http://www.w3.org/2000/svg" xmlns:sketch="http://www.bohemiancoding.com/sketch/ns" xmlns:xlink="http://www.w3.org/1999/xlink"><title/><desc/><defs/><g fill="none" fill-rule="evenodd" id="Page-1" stroke="none" stroke-width="1"><g fill="rgba(20,216,161, .75)" id="icon-92-inbox-download"><path d="M16,16 L12.75,12.75 L12,13.5 L16.5,18 L21,13.5 L20.25,12.75 L17,16 L17,5 L16,5 L16,16 L16,16 L16,16 Z M21,18 L27.7750244,18 L27.7750244,18 L23.4000244,11 L18,11 L18,10 L24,10 L29,18 L29,19 L29,27 L4,27 L4,18 L9,10 L15,10 L15,11 L9.59997559,11 L5.22497559,18 L12,18 L12,20 C12,21.1045695 12.8958578,22 13.9973917,22 L19.0026083,22 C20.1057373,22 21,21.1122704 21,20 L21,18 L21,18 Z" id="inbox-download"/></g></g></svg>
											</div>
											<h4 class="eael-admin-title">Automatic Update</h4>
										</header>
										<div class="eael-admin-block-content">
				      						<?php do_action( 'eael_licensing' ); ?>
				      					</div>
				      				</div>
									<div class="eael-admin-block eael-admin-block-docs">
										<header class="eael-admin-block-header">
											<div class="eael-admin-block-header-icon">
												<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 46 46"><defs><style>.cls-1{fill:#1abc9c;}</style></defs><title>Documentation</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><rect class="cls-1" x="15.84" y="17.13" width="14.32" height="1.59"/><rect class="cls-1" x="15.84" y="24.19" width="14.32" height="1.59"/><rect class="cls-1" x="15.84" y="20.66" width="14.32" height="1.59"/><path class="cls-1" d="M23,0A23,23,0,1,0,46,23,23,23,0,0,0,23,0Zm5.47,9.9,4.83,4.4H28.47Zm-2.29,23v3.2H15.49a2.79,2.79,0,0,1-2.79-2.79V12.69A2.79,2.79,0,0,1,15.49,9.9H27.28v5.59h6V27.72H15.84v1.59H29.78v1.94H15.84v1.59H26.19Zm11.29,2.52H33.88V39H31.37V35.42H27.78V32.9h3.59V29.31h2.52V32.9h3.59Z"/></g></g><head xmlns=""/></svg>
											</div>
											<h4 class="eael-admin-title">Documentation</h4>
										</header>
										<div class="eael-admin-block-content">
											<p>Get started by spending some time with the documentation to get familiar with Essential Addons. Build awesome websites for you or your clients with ease.</a></p>
											<a href="https://essential-addons.com/elementor/docs/" class="button button-primary" target="_blank">Documentation</a>
										</div>
									</div>
									<div class="eael-admin-block eael-admin-block-contribution">
										<header class="eael-admin-block-header">
											<div class="eael-admin-block-header-icon">
												<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 46 45.69"><defs><style>.flexia-icon-bug{fill:#9b59b6;}</style></defs><title>Bugs</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="flexia-icon-bug" d="M18.87,28.37,9.16,38.08A8.66,8.66,0,0,0,14.49,40h4.38a9.55,9.55,0,0,0,2.28-.38v5.14a1,1,0,0,0,1.9,0v-5.9A4.83,4.83,0,0,0,25,37.31l.76-.76a.92.92,0,0,0,0-1.33Z"/><path class="flexia-icon-bug" d="M11.64,21.13c-.19-.19-.57-.38-.76-.19H9c-.38,0-.57,0-.76.38L7.07,23H1.17a1,1,0,1,0,0,1.9H6.31a9.56,9.56,0,0,0-.38,2.28V31.6a8.66,8.66,0,0,0,1.9,5.33l9.71-9.71Z"/><path class="flexia-icon-bug" d="M24.39,14.47c.19.19.38.19.76.19a.7.7,0,0,0,.57-.19.92.92,0,0,0,.38-1.14,11.08,11.08,0,0,1-1-3,.87.87,0,0,0-1-.76H22.3a1,1,0,0,0-.76.38,1.14,1.14,0,0,0-.19.76,2.35,2.35,0,0,0,.76,1.52Z"/><path class="flexia-icon-bug" d="M35.81,28.56h3.43a1,1,0,0,0,0-1.9H33.91L20.77,13.52A5.2,5.2,0,0,1,19.25,9.9V6.66a.9.9,0,0,0-1-1h-.19A13.52,13.52,0,0,0,16.21,3,9.12,9.12,0,0,0,9.54,0a9.71,9.71,0,0,0-5.9,2.09,1.44,1.44,0,0,0-.38.76,1,1,0,0,0,.38.76L9.54,7a5.39,5.39,0,0,1-2.86,4.19l-5.14-3a.85.85,0,0,0-1,0c-.38.19-.57.38-.57.76a8.9,8.9,0,0,0,2.67,7,9.53,9.53,0,0,0,6.85,3,4.1,4.1,0,0,0,2.09-.38L26.87,33.89,37.15,44.17a5.2,5.2,0,0,0,3.62,1.52,5,5,0,0,0,4.95-4.95,5.2,5.2,0,0,0-1.52-3.62Z"/><path class="flexia-icon-bug" d="M34.86,24.75c.19.19.38.19.76.19H36a1,1,0,0,0,.57-1V21.51c0-.38-.38-1-.76-1a7,7,0,0,1-3.43-.76.92.92,0,0,0-1.14.38c-.19.38-.19,1,.19,1.14Z"/><path class="flexia-icon-bug" d="M45.71,9.9c-1.52-1.52-5.14-.38-7,.57L35.81,7.62c.76-2.09,1.9-5.71.57-7a.92.92,0,0,0-1.33,0,.92.92,0,0,0,0,1.33c.38.38,0,2.67-1,5.14L28,8a.87.87,0,0,0-.76,1C26.87,14.28,31.63,19,37.34,19c.38,0,1-.38,1-.76l1-6.09c2.47-1,4.76-1.33,5.14-1A.94.94,0,1,0,45.71,9.9Z"/></g></g><head xmlns=""/></svg>
											</div>
											<h4 class="eael-admin-title">Contribute to Essential Addons</h4>
										</header>
										<div class="eael-admin-block-content">
											<p>You can contribute to make Essential Addons better reporting bugs, creating issues, pull requests at <a href="https://github.com/rupok/essential-addons-elementor-lite/" target="_blank">Github.</a></p>
											<a href="https://github.com/rupok/essential-addons-elementor-lite/issues/new" class="button button-primary" target="_blank">Report a bug</a>
										</div>
									</div>
									<div class="eael-admin-block eael-admin-block-support">
										<header class="eael-admin-block-header">
											<div class="eael-admin-block-header-icon">
												<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32.22 42.58"><defs><style>.flexia-icon-support{fill:#6c75ff;}</style></defs><title>Flexia Support</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="flexia-icon-support" d="M6.36,29.34l1.09-1.09h8l-5.08-9.18-3.76.76a2.64,2.64,0,0,0-2,1.91L.09,36.31a2.64,2.64,0,0,0,2.55,3.31H6.36V29.34Z"/><path class="flexia-icon-support" d="M32.13,36.31,27.67,21.75a2.64,2.64,0,0,0-2.06-1.92l-3.74-.71-5.06,9.13h8.56l1.09,1.09V39.62h3.12a2.64,2.64,0,0,0,2.55-3.31Z"/><polygon class="flexia-icon-support" points="8.54 39.62 8.24 39.62 8.24 39.62 23.98 39.62 23.98 39.62 24.28 39.62 24.28 30.43 8.54 30.43 8.54 39.62"/><rect class="flexia-icon-support" x="4.19" y="40.61" width="23.83" height="1.97"/><path class="flexia-icon-support" d="M7.62,12.65c0,.09.1.22.15.36a3.58,3.58,0,0,0,.68,1.22c1.21,3.94,4.33,6.68,7.64,6.67s6.38-2.77,7.55-6.72A3.61,3.61,0,0,0,24.31,13c.06-.14.11-.27.15-.36a2,2,0,0,0-.33-2.41V10.1C24.12,5.2,23.48,0,16,0S7.92,5,7.94,10.15c0,0,0,.06,0,.09A2,2,0,0,0,7.62,12.65Zm1-1.58h0A.55.55,0,0,0,9,10.83l1.3.2a.28.28,0,0,0,.3-.16L11.39,9a35.31,35.31,0,0,0,7.2,1,7.76,7.76,0,0,0,2.11-.25L21.23,11a.27.27,0,0,0,.25.17h.07l1.51-.43a.56.56,0,0,0,.31.3h0c.23.11.3.6.06,1.09-.06.12-.12.27-.18.43a4.18,4.18,0,0,1-.4.82.55.55,0,0,0-.26.33c-1,3.58-3.68,6.08-6.54,6.09s-5.6-2.48-6.63-6a.55.55,0,0,0-.26-.33,4.3,4.3,0,0,1-.41-.82c-.06-.15-.13-.3-.18-.42C8.37,11.68,8.44,11.19,8.67,11.08Z"/></g></g><head xmlns=""/></svg>
											</div>
											<h4 class="eael-admin-title">Need Help?</h4>
										</header>
										<div class="eael-admin-block-content">
											<p>Stuck with something? Get help from live chat or support ticket.</p>
											<a href="https://wpdeveloper.net" class="button button-primary" target="_blank">Initiate a Chat</a>
										</div>
									</div>
									<div class="eael-admin-block eael-admin-block-community">
										<header class="eael-admin-block-header">
											<div class="eael-admin-block-header-icon">
												<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 46 41.82"><defs><style>.cls-1{fill:#00aeff;}</style></defs><title>Like</title><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><g id="thumb-up"><path class="cls-1" d="M0,41.82H8.36V16.73H0Zm46-23a4.19,4.19,0,0,0-4.18-4.18H28.65L30.74,5V4.39a4.39,4.39,0,0,0-.84-2.3L27.6,0,13.8,13.8a3.51,3.51,0,0,0-1.25,2.93V37.64a4.19,4.19,0,0,0,4.18,4.18H35.55a4.13,4.13,0,0,0,3.76-2.51l6.27-14.85A3.56,3.56,0,0,0,45.79,23V18.82H46Z"/></g></g></g><head xmlns=""/></svg>
											</div>
											<h4 class="eael-admin-title">Join the Community</h4>
										</header>
										<div class="eael-admin-block-content">
											<p>Join the Facebook community and discuss with fellow developers and users. Best way to connect with people and get feedback on your projects.</p>

											<a href="https://www.facebook.com/groups/essentialaddons" class="review-flexia button button-primary" target="_blank">Join Facebook Community</a>
										</div>
									</div>
				      			</div><!--admin block-wrapper end-->
			      			</div>
			      			<div class="eael-admin-sidebar">
			      				<div class="eael-sidebar-block">
			      					<div class="eael-admin-sidebar-logo">
			      						<img src="<?php echo plugins_url( '/', __FILE__ ).'assets/images/ea-logo.svg'; ?>">
			      					</div>
			      					<div class="eael-admin-sidebar-cta">
			      						<?php printf( __( '<a href="%s" target="_blank">Manage License</a>', 'essential-addons-elementor' ), 'https://wpdeveloper.net/account' ); ?>
			      					</div>
			      				</div>
			    			</div><!--admin sidebar end-->
			    		</div>
			    	</div>
			    	<div id="elements" class="eael-settings-tab eael-elements-list">
				      	<div class="row">
				      		<div class="col-full">
				      			<!-- Content Element Starts -->
				      			<h4>Content Elements</h4>
								<div class="eael-checkbox-container">
									<div class="eael-checkbox">
										<input type="checkbox" id="info-box" name="info-box" <?php checked( 1, $this->eael_get_settings['info-box'], true ); ?> >
										<label for="info-box"></label>
										<p class="eael-el-title"><?php _e( 'Info Box', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="team-members" name="team-members" <?php checked( 1, $this->eael_get_settings['team-members'], true ); ?> >
										<label for="team-members"></label>
										<p class="eael-el-title"><?php _e( 'Team Member', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="team-member-carousel" name="team-member-carousel" <?php checked( 1, $this->eael_get_settings['team-member-carousel'], true ); ?> >
										<label for="team-member-carousel"></label>
										<p class="eael-el-title"><?php _e( 'Team Member Carousel', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="flip-box" name="flip-box" <?php checked( 1, $this->eael_get_settings['flip-box'], true ); ?> >
										<label for="flip-box"></label>
										<p class="eael-el-title"><?php _e( 'Flip Box', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="dual-header" name="dual-header" <?php checked( 1, $this->eael_get_settings['dual-header'], true ); ?> >
										<label for="dual-header"></label>
										<p class="eael-el-title"><?php _e( 'Dual Color Header', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="creative-btn" name="creative-btn" <?php checked( 1, $this->eael_get_settings['creative-btn'], true ); ?> >
										<label for="creative-btn"></label>
										<p class="eael-el-title"><?php _e( 'Creative Button', 'essential-addons-elementor' ); ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="tooltip" name="tooltip" <?php checked( 1, $this->eael_get_settings['tooltip'], true ); ?> >
										<label for="tooltip"></label>
										<p class="eael-el-title"><?php _e( 'Tooltip', 'essential-addons-elementor' ); ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="toggle" name="toggle" <?php checked( 1, $this->eael_get_settings['toggle'], true ); ?> >
										<label for="toggle"></label>
										<p class="eael-el-title"><?php _e( 'Toggle', 'essential-addons-elementor' ); ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="testimonial-slider" name="testimonial-slider" <?php checked( 1, $this->eael_get_settings['testimonial-slider'], true ); ?> >
										<label for="testimonial-slider"></label>
										<p class="eael-el-title"><?php _e( 'Testimonial Slider', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="testimonials" name="testimonials" <?php checked( 1, $this->eael_get_settings['testimonials'], true ); ?> >
										<label for="testimonials"></label>
										<p class="eael-el-title"><?php _e( 'Testimonials', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="static-product" name="static-product" <?php checked( 1, $this->eael_get_settings['static-product'], true ); ?> >
										<label for="static-product"></label>
										<p class="eael-el-title"><?php _e( 'Static Product', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="adv-tabs" name="adv-tabs" <?php checked( 1, $this->eael_get_settings['adv-tabs'], true ); ?> >
										<label for="adv-tabs"></label>
										<p class="eael-el-title"><?php _e( 'Advanced Tabs', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="adv-accordion" name="adv-accordion" <?php checked( 1, $this->eael_get_settings['adv-accordion'], true ); ?> >
										<label for="adv-accordion"></label>
										<p class="eael-el-title"><?php _e( 'Advanced Accordion', 'essential-addons-elementor' ) ?></p>
									</div>
								</div>
				      			<!-- Content Element Ends -->
				      			<!-- Dynamic Content Element Starts -->
				      			<h4>Dynamic Content Elements</h4>
								<div class="eael-checkbox-container">
									<div class="eael-checkbox">
										<input type="checkbox" id="post-grid" name="post-grid" <?php checked( 1, $this->eael_get_settings['post-grid'], true ); ?> >
										<label for="post-grid"></label>
										<p class="eael-el-title"><?php _e( 'Post Grid', 'essential-addons-elementor' ); ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="post-block" name="post-block" <?php checked( 1, $this->eael_get_settings['post-block'], true ); ?> >
										<label for="post-block"></label>
										<p class="eael-el-title"><?php _e( 'Post Block', 'essential-addons-elementor' ); ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="post-carousel" name="post-carousel" <?php checked( 1, $this->eael_get_settings['post-carousel'], true ); ?> >
										<label for="post-carousel"></label>
										<p class="eael-el-title"><?php _e( 'Post Carousel', 'essential-addons-elementor' ); ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="post-timeline" name="post-timeline" <?php checked( 1, $this->eael_get_settings['post-timeline'], true ); ?> >
										<label for="post-timeline"></label>
										<p class="eael-el-title"><?php _e( 'Post Timeline', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="post-list" name="post-list" <?php checked( 1, $this->eael_get_settings['post-list'], true ); ?> >
										<label for="post-list"></label>
										<p class="eael-el-title"><?php _e( 'Post List', 'essential-addons-elementor' ); ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="instagram-gallery" name="instagram-gallery" <?php checked( 1, $this->eael_get_settings['instagram-gallery'], true ); ?> >
										<label for="instagram-gallery"></label>
										<p class="eael-el-title"><?php _e( 'Instagram Gallery', 'essential-addons-elementor' ); ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="product-grid" name="product-grid" <?php checked( 1, $this->eael_get_settings['product-grid'], true ); ?> >
										<label for="product-grid"></label>
										<p class="eael-el-title"><?php _e( 'Woo Product Grid', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="content-timeline" name="content-timeline" <?php checked( 1, $this->eael_get_settings['content-timeline'], true ); ?> >
										<label for="content-timeline"></label>
										<p class="eael-el-title"><?php _e( 'Content Timeline', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="data-table" name="data-table" <?php checked( 1, $this->eael_get_settings['data-table'], true ); ?> >
										<label for="data-table"></label>
										<p class="eael-el-title"><?php _e( 'Data Table', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="dynamic-filter-gallery" name="dynamic-filter-gallery" <?php checked( 1, $this->eael_get_settings['dynamic-filter-gallery'], true ); ?> >
										<label for="dynamic-filter-gallery"></label>
										<p class="eael-el-title"><?php _e( 'Dynamic Filter Gallery', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="content-ticker" name="content-ticker" <?php checked( 1, $this->eael_get_settings['content-ticker'], true ); ?> >
										<label for="content-ticker"></label>
										<p class="eael-el-title"><?php _e( 'Content Ticker', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="adv-google-map" name="adv-google-map" <?php checked( 1, $this->eael_get_settings['adv-google-map'], true ); ?> >
										<label for="adv-google-map"></label>
										<p class="eael-el-title"><?php _e( 'Advanced Google Map', 'essential-addons-elementor' ) ?>
											(<span><a href="#" id="eael-popup-api-modal">Settings</a></span>)
											<input type="hidden" name="google-map-api" id="google-map-api-hidden" class="google-map-api" placeholder="Set API Key" value="<?php echo get_option('eael_save_google_map_api'); ?>">
										</p>
									</div>
								</div>
				      			<!-- Dynamic Content Element Ends -->
				      			<!-- Creative Element Starts -->
				      			<h4>Creative Elements</h4>
								<div class="eael-checkbox-container">
									<div class="eael-checkbox">
										<input type="checkbox" id="fancy-text" name="fancy-text" <?php checked( 1, $this->eael_get_settings['fancy-text'], true ); ?> >
										<label for="fancy-text"></label>
										<p class="eael-el-title"><?php _e( 'Fancy Text', 'essential-addons-elementor' ); ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="interactive-promo" name="interactive-promo" <?php checked( 1, $this->eael_get_settings['interactive-promo'], true ); ?> >
										<label for="interactive-promo"></label>
										<p class="eael-el-title"><?php _e( 'Interactive Promo', 'essential-addons-elementor' ); ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="count-down" name="count-down" <?php checked( 1, $this->eael_get_settings['count-down'], true ); ?>>
										<label for="count-down"></label>
										<p class="eael-el-title"><?php _e( 'Count Down', 'essential-addons-elementor' ); ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="counter" name="counter" <?php checked( 1, $this->eael_get_settings['counter'], true ); ?>>
										<label for="counter"></label>
										<p class="eael-el-title"><?php _e( 'Counter', 'essential-addons-elementor' ); ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="lightbox" name="lightbox" <?php checked( 1, $this->eael_get_settings['lightbox'], true ); ?> >
										<label for="lightbox"></label>
										<p class="eael-el-title"><?php _e( 'Lightbox', 'essential-addons-elementor' ); ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="protected-content" name="protected-content" <?php checked( 1, $this->eael_get_settings['protected-content'], true ); ?> >
										<label for="protected-content"></label>
										<p class="eael-el-title"><?php _e( 'Protected Content', 'essential-addons-elementor' ); ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="img-comparison" name="img-comparison" <?php checked( 1, $this->eael_get_settings['img-comparison'], true ); ?> >
										<label for="img-comparison"></label>
										<p class="eael-el-title"><?php _e( 'Image Comparison', 'essential-addons-elementor' ); ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="flip-carousel" name="flip-carousel" <?php checked( 1, $this->eael_get_settings['flip-carousel'], true ); ?> >
										<label for="flip-carousel"></label>
										<p class="eael-el-title"><?php _e( 'Flip Carousel', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="logo-carousel" name="logo-carousel" <?php checked( 1, $this->eael_get_settings['logo-carousel'], true ); ?> >
										<label for="logo-carousel"></label>
										<p class="eael-el-title"><?php _e( 'Logo Carousel', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="interactive-cards" name="interactive-cards" <?php checked( 1, $this->eael_get_settings['interactive-cards'], true ); ?> >
										<label for="interactive-cards"></label>
										<p class="eael-el-title"><?php _e( 'Interactive Cards', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="filter-gallery" name="filter-gallery" <?php checked( 1, $this->eael_get_settings['filter-gallery'], true ); ?> >
										<label for="filter-gallery"></label>
										<p class="eael-el-title"><?php _e( 'Filterable Gallery', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="image-accordion" name="image-accordion" <?php checked( 1, $this->eael_get_settings['image-accordion'], true ); ?> >
										<label for="image-accordion"></label>
										<p class="eael-el-title"><?php _e( 'Image Accordion', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="one-page-navigation" name="one-page-navigation" <?php checked( 1, $this->eael_get_settings['one-page-navigation'], true ); ?> >
										<label for="one-page-navigation"></label>
										<p class="eael-el-title"><?php _e( 'One Page Navigation', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="image-hotspots" name="image-hotspots" <?php checked( 1, $this->eael_get_settings['image-hotspots'], true ); ?> >
										<label for="image-hotspots"></label>
										<p class="eael-el-title"><?php _e( 'Image Hotspots', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="divider" name="divider" <?php checked( 1, $this->eael_get_settings['divider'], true ); ?> >
										<label for="divider"></label>
										<p class="eael-el-title"><?php _e( 'Divider', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="progress-bar" name="progress-bar" <?php checked( 1, $this->eael_get_settings['progress-bar'], true ); ?> >
										<label for="progress-bar"></label>
										<p class="eael-el-title"><?php _e( 'Progress Bar', 'essential-addons-elementor' ) ?></p>
									</div>
								</div>
				      			<!-- Creative Element Ends -->
				      			<!-- Marketing Elements Starts -->
				      			<h4>Marketing Elements</h4>
								<div class="eael-checkbox-container">
									<div class="eael-checkbox">
										<input type="checkbox" id="call-to-action" name="call-to-action" <?php checked( 1, $this->eael_get_settings['call-to-action'], true ); ?> >
											<label for="call-to-action"></label>
										<p class="eael-el-title"><?php _e( 'Call To Action', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="price-table" name="price-table" <?php checked( 1, $this->eael_get_settings['price-table'], true ); ?> >
										<label for="price-table"></label>
										<p class="eael-el-title"><?php _e( 'Pricing Table', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="price-menu" name="price-menu" <?php checked( 1, $this->eael_get_settings['price-menu'], true ); ?> >
										<label for="price-menu"></label>
										<p class="eael-el-title"><?php _e( 'Price menu', 'essential-addons-elementor' ) ?></p>
									</div>
								</div>
				      			<!-- Marketing Elements Ends -->
				      			<!-- Form Styler Elements Starts -->
				      			<h4>Form Styler Elements</h4>
								<div class="eael-checkbox-container">
									<div class="eael-checkbox">
										<input type="checkbox" id="mailchimp" name="mailchimp" <?php checked( 1, $this->eael_get_settings['mailchimp'], true ); ?> >
										<label for="mailchimp"></label>
										<p class="eael-el-title"><?php _e( 'Mailchimp', 'essential-addons-elementor' ) ?>
											(<span><a href="#" id="eael-popup-mailchimp-api-modal">Settings</a></span>)
											<input type="hidden" name="mailchimp-api" id="mailchimp-api-hidden" class="mailchimp-api" placeholder="Set API Key" value="<?php echo get_option('eael_save_mailchimp_api'); ?>">
										</p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="weforms" name="weforms" <?php checked( 1, $this->eael_get_settings['weforms'], true ); ?> >
										<label for="weforms"></label>
										<p class="eael-el-title"><?php _e( 'weForms', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="contact-form-7" name="contact-form-7" <?php checked( 1, $this->eael_get_settings['contact-form-7'], true ); ?> >
										<label for="contact-form-7"></label>
										<p class="eael-el-title"><?php _e( 'Contact Form 7', 'essential-addons-elementor' ); ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="ninja-form" name="ninja-form" <?php checked( 1, $this->eael_get_settings['ninja-form'], true ); ?> >
											<label for="ninja-form"></label>
										<p class="eael-el-title"><?php _e( 'Ninja Form', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="gravity-form" name="gravity-form" <?php checked( 1, $this->eael_get_settings['gravity-form'], true ); ?> >
										<label for="gravity-form"></label>
										<p class="eael-el-title"><?php _e( 'Gravity Form', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="caldera-form" name="caldera-form" <?php checked( 1, $this->eael_get_settings['caldera-form'], true ); ?> >
										<label for="caldera-form"></label>
										<p class="eael-el-title"><?php _e( 'Caldera Form', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="wpforms" name="wpforms" <?php checked( 1, $this->eael_get_settings['wpforms'], true ); ?> >
										<label for="wpforms"></label>
										<p class="eael-el-title"><?php _e( 'WPForms', 'essential-addons-elementor' ) ?></p>
									</div>
								</div>
				      			<!-- Form Styler Elements Ends -->
				      			<!-- Social Feed Elements Starts -->
				      			<h4>Social Feed Elements</h4>
								<div class="eael-checkbox-container">
									<div class="eael-checkbox">
										<input type="checkbox" id="twitter-feed" name="twitter-feed" <?php checked( 1, $this->eael_get_settings['twitter-feed'], true ); ?> >
										<label for="twitter-feed"></label>
										<p class="eael-el-title"><?php _e( 'Twitter Feed', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="twitter-feed-carousel" name="twitter-feed-carousel" <?php checked( 1, $this->eael_get_settings['twitter-feed-carousel'], true ); ?> >
										<label for="twitter-feed-carousel"></label>
										<p class="eael-el-title"><?php _e( 'Twitter Feed Carousel', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="facebook-feed" name="facebook-feed" <?php checked( 1, $this->eael_get_settings['facebook-feed'], true ); ?> >
										<label for="facebook-feed"></label>
										<p class="eael-el-title"><?php _e( 'Facebook Feed', 'essential-addons-elementor' ) ?></p>
									</div>
									<div class="eael-checkbox">
										<input type="checkbox" id="facebook-feed-carousel" name="facebook-feed-carousel" <?php checked( 1, $this->eael_get_settings['facebook-feed-carousel'], true ); ?> >
										<label for="facebook-feed-carousel"></label>
										<p class="eael-el-title"><?php _e( 'Facebook Feed Carousel', 'essential-addons-elementor' ) ?></p>
									</div>
								</div>
				      			<!-- Social Feed Elements Ends -->
							  	<div class="eael-save-btn-wrap">
									<button type="submit" class="button eael-btn js-eael-settings-save"><?php _e('Save settings', 'essential-addons-elementor'); ?></button>
							  	</div>
				      		</div>
				      	</div>
			    	</div>
			  	</div>
		  	</form>
		</div>
		<?php

	}

	/**
	 * Saving data with ajax request
	 * @param
	 * @return  array
	 * @since 1.1.2
	 */
	public function eael_save_settings_with_ajax() {

		if( isset( $_POST['fields'] ) ) {
			parse_str( $_POST['fields'], $settings );
		}else {
			return;
		}
		$this->eael_settings = array(
			'contact-form-7' 		=> intval( $settings['contact-form-7'] ? 1 : 0 ),
			'count-down' 			=> intval( $settings['count-down'] ? 1 : 0 ),
			'counter' 				=> intval( $settings['counter'] ? 1 : 0 ),
			'creative-btn' 			=> intval( $settings['creative-btn'] ? 1 : 0 ),
			'divider'	 			=> intval( $settings['divider'] ? 1 : 0 ),
			'fancy-text' 			=> intval( $settings['fancy-text'] ? 1 : 0 ),
			'img-comparison' 		=> intval( $settings['img-comparison'] ? 1 : 0 ),
			'instagram-gallery' 	=> intval( $settings['instagram-gallery'] ? 1 : 0 ),
			'interactive-promo' 	=> intval( $settings['interactive-promo'] ? 1 : 0 ),
			'lightbox' 				=> intval( $settings['lightbox'] ? 1 : 0 ),
			'post-block' 			=> intval( $settings['post-block'] ? 1 : 0 ),
			'post-list' 			=> intval( $settings['post-list'] ? 1 : 0 ),
			'post-grid' 			=> intval( $settings['post-grid'] ? 1 : 0 ),
			'post-carousel'			=> intval( $settings['post-carousel'] ? 1 : 0 ),
			'post-timeline' 		=> intval( $settings['post-timeline'] ? 1 : 0 ),
			'product-grid' 			=> intval( $settings['product-grid'] ? 1 : 0 ),
			'team-members' 			=> intval( $settings['team-members'] ? 1 : 0 ),
			'testimonial-slider' 	=> intval( $settings['testimonial-slider'] ? 1 : 0 ),
			'testimonials' 			=> intval( $settings['testimonials'] ? 1 : 0 ),
			'weforms' 				=> intval( $settings['weforms'] ? 1 : 0 ),
			'static-product' 		=> intval( $settings['static-product'] ? 1 : 0 ),
			'call-to-action' 		=> intval( $settings['call-to-action'] ? 1 : 0 ),
			'flip-box' 				=> intval( $settings['flip-box'] ? 1 : 0 ),
			'info-box' 				=> intval( $settings['info-box'] ? 1 : 0 ),
			'dual-header' 			=> intval( $settings['dual-header'] ? 1 : 0 ),
			'price-table' 			=> intval( $settings['price-table'] ? 1 : 0 ),
			'price-menu' 			=> intval( $settings['price-menu'] ? 1 : 0 ),
			'flip-carousel' 		=> intval( $settings['flip-carousel'] ? 1 : 0 ),
			'logo-carousel' 		=> intval( $settings['logo-carousel'] ? 1 : 0 ),
			'interactive-cards' 	=> intval( $settings['interactive-cards'] ? 1 : 0 ),
			'ninja-form' 			=> intval( $settings['ninja-form'] ? 1 : 0 ),
			'gravity-form' 			=> intval( $settings['gravity-form'] ? 1 : 0 ),
			'content-timeline' 		=> intval( $settings['content-timeline'] ? 1 : 0 ),
			'data-table' 			=> intval( $settings['data-table'] ? 1 : 0 ),
			'caldera-form' 			=> intval( $settings['caldera-form'] ? 1 : 0 ),
			'wpforms' 				=> intval( $settings['wpforms'] ? 1 : 0 ),
			'twitter-feed' 			=> intval( $settings['twitter-feed'] ? 1 : 0 ),
			'facebook-feed' 		=> intval( $settings['facebook-feed'] ? 1 : 0 ),
			'facebook-feed-carousel' => intval( $settings['facebook-feed-carousel'] ? 1 : 0 ),
			'twitter-feed-carousel'  => intval( $settings['twitter-feed-carousel'] ? 1 : 0 ),
			'filter-gallery'  		=> intval( $settings['filter-gallery'] ? 1 : 0 ),
			'dynamic-filter-gallery' => intval( $settings['dynamic-filter-gallery'] ? 1 : 0 ),
			'content-ticker' 		=> intval( $settings['content-ticker'] ? 1 : 0 ),
			'image-accordion' 		=> intval( $settings['image-accordion'] ? 1 : 0 ),
			'tooltip' 				=> intval( $settings['tooltip'] ? 1 : 0 ),
			'adv-tabs' 				=> intval( $settings['adv-tabs'] ? 1 : 0 ),
			'adv-accordion' 		=> intval( $settings['adv-accordion'] ? 1 : 0 ),
			'adv-google-map' 		=> intval( $settings['adv-google-map'] ? 1 : 0 ),
			'mailchimp' 			=> intval( $settings['mailchimp'] ? 1 : 0 ),
			'toggle' 				=> intval( $settings['toggle'] ? 1 : 0 ),
			'one-page-navigation'	=> intval( $settings['one-page-navigation'] ? 1 : 0 ),
			'team-member-carousel'	=> intval( $settings['team-member-carousel'] ? 1 : 0 ),
			'image-hotspots'		=> intval( $settings['image-hotspots'] ? 1 : 0 ),
			'protected-content'		=> intval( $settings['protected-content'] ? 1 : 0 ),
			'progress-bar'		    => intval( $settings['progress-bar'] ? 1 : 0 ),
		); 
		update_option( 'eael_save_settings', $this->eael_settings );
		// Saving Google Map Api Key
		$eael_google_map_api = $settings['google-map-api'];
		update_option( 'eael_save_google_map_api', $eael_google_map_api );

		// Saving Mailchimp Api Key
		$eael_mailchimp_api = $settings['mailchimp-api'];
		update_option( 'eael_save_mailchimp_api', $eael_mailchimp_api );

		return true;
		die();

	}

}

new Eael_Admin_Settings();
