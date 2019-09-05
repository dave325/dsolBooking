<?php
/*
Plugin Name: WidgetKit for Elementor
Description: Huge collection of pro quality element or section for use in Elementor page builder,which you help to create any kind of complicated design without coding.  Elementor Page Builder must be installed and activated.
Version: 1.5.4
Text Domain: widgetkit-for-elementor
Author: Themesgrove
Author URI: https://themesgrove.com
Plugin URI: http://widgetkit.themesgrove.com/
License: GPL3
License URI: https://www.gnu.org/licenses/gpl-3.0.txt
@package  WidgetKit_For_Elementor
Domain Path: /languages
*/

    if( !function_exists('add_action') ) {
        die('Elementor not Installed'); // if Elementor not installed kill the page.
    }

    // Define absoulote path
    if( !defined( 'ABSPATH' ) ) exit; // No access of directly access

    // Define file
    define('WKFE_FILE', __FILE__);
    // Define url
    define('WKFE_URL', plugins_url('/', __FILE__ ) );
    // Define path
    define('WKFE_PATH', plugin_dir_path( __FILE__ ) );


    class WidgetKit_For_Elementor {
        // Activate 
        function activate(){
            flush_rewrite_rules();
        }

        // Deactivate 
        function deactivate(){
            flush_rewrite_rules();
        }

        /**
         * Initialize the plugin tracker
         *
         * @return void
         */
        protected function appsero_init_tracker_widgetkit_for_elementor() {

            if ( ! class_exists( 'Appsero\Client' ) ) {
            require_once WKFE_PATH . '/vendor/appsero/client/src/Client.php';
            }

            $client = new Appsero\Client( '91bf222e-d4ce-4fdb-97dc-30a95fa0bff7', 'widgetkit-for-elementor', WKFE_FILE );

            // Active insights
            $client->insights()->init();

        }

        

        /**
         * Creates an Action Menu
         */

        function widgetkit_for_elementor_pro_settings_link($links, $file) {
            static $this_plugin;
         
            if (!$this_plugin) {
                $this_plugin = plugin_basename(__FILE__);
            }
            $settings_links = sprintf( '<a href="admin.php?page=widgetkit-settings#widgetkit-elements">' . __( 'Settings', 'widgetkit-for-elementor' ) . '</a>' );

        if(! class_exists( 'WidgetKit_Pro' ) ) {
            // check to make sure we are on the correct plugin
            if ($file == $this_plugin) {
                // link to what ever you want
                $plugin_links['WidgetKit_Pro'] = sprintf( '<a href="https://themesgrove.com/widgetkit-for-elementor/" target="_blank" style="color:#39a700eb; font-weight: bold;">' . __( 'Get Pro', 'widgetkit-for-elementor' ) . '</a>' );
                 
         
                // add the links to the list of links already there
                foreach($plugin_links as $link) {
                    array_unshift($links,$settings_links, $link);
                }
            }
        }
         
            return $links;
        }

        /*
         * Plugin Name: My plugin
         */
         
        // function widgetkit_pro_plugin_init() {
        //     // if( class_exists( 'WidgetKit_Pro' ) ) {
         
        //     //    echo "Pro is active";
         
        //     // }
        // }




        // Widget register
        public function __construct() {
            add_action( 'elementor/widgets/widgets_registered', array( $this, 'widgetkit_for_elementor_widget_bundle') );
            $this->appsero_init_tracker_widgetkit_for_elementor();
        }

       function widgetkit_for_elementor_widget_register() {
            $this->widgetkit_for_elementor_widget_bundle();
            $this->widgetkit_for_elementor_script();
        }

        // widgetkit widget bundle
        function widgetkit_for_elementor_widget_bundle(){
          $widgetkit_elements_keys = [
                'widget-slider-animation', 
                'widget-slider-content-animation', 
                'widget-slider-box-animation', 
                'widget-portfolio',
                'widget-pricing-single', 
                'widget-pricing-icon', 
                'widget-pricing-tab', 
                'widget-testimonial-single',
                'widget-testimonial-center',
                'widget-team-overlay',
                'widget-team-verticle-icon',
                'widget-team-round',
                'widget-team-animation',
                'widget-blog-carousel',
                'widget-blog-sidebar',
                'widget-blog-revert',
                'widget-blog-hover-animation',
                'widget-blog-image',
                'widget-countdown',
                'widget-animation-text',
                'widget-post-carousel',
                'widget-button',
                'widget-hover-image',
                'widget-feature-box',
                'widget-social-share-animation',
                'widget-social-share-collapse',
            ];
            $widgetkit_default_settings = array_fill_keys( $widgetkit_elements_keys, true ); 

            $check_component_active = get_option( 'widgetkit_save_settings', $widgetkit_default_settings );

                      // Portfolio Elements
            if( $check_component_active['widget-portfolio'] ) {
                // Portfolio elements
                require_once WKFE_PATH . '/elements/portfolio/widget.php';
            }

                        // Pricing Elements
            if( $check_component_active['widget-pricing-single'] ) {
                // Pricing-1 = Pricing Single
                require_once WKFE_PATH . '/elements/pricing-1/widget.php';
            }

            if( $check_component_active['widget-pricing-icon'] ) {
                // Pricing-2 = Pricing Icon
                require_once WKFE_PATH . '/elements/pricing-2/widget.php';
            }

            if( $check_component_active['widget-pricing-tab'] ) {
                // Pricing-tab
                require_once WKFE_PATH . '/elements/pricing-tab/widget.php';
            }


                        // Testimonial Elements
            if( $check_component_active['widget-testimonial-center'] ) {
                // Testimonial-1 = Testimonial Center
                require_once WKFE_PATH . '/elements/testimonial-1/widget.php';
            }
            if( $check_component_active['widget-testimonial-single'] ) {
                // Testimonial-2 = Testimonial Single
                require_once WKFE_PATH . '/elements/testimonial-2/widget.php';
            }



                            // Team Elements
            if( $check_component_active['widget-team-overlay'] ) {
                // Team 1 = Team Overlay
                require_once WKFE_PATH . '/elements/team-1/widget.php';
            }
            if( $check_component_active['widget-team-verticle-icon'] ) {
                // Team-2 = Team Verticle Icon
                require_once WKFE_PATH . '/elements/team-2/widget.php';
            }
            if( $check_component_active['widget-team-round'] ) {
                // Team-3 = Team Round
                require_once WKFE_PATH . '/elements/team-3/widget.php';
            }
            if( $check_component_active['widget-team-animation'] ) {
                // Team-4 = Team Animation
                require_once WKFE_PATH . '/elements/team-4/widget.php';
            }



                            // Blogs Elements
            if( $check_component_active['widget-blog-carousel'] ) {
                // Blog-1 = Blog Carousel
                require_once WKFE_PATH . '/elements/blog-1/widget.php';
            }

            if( $check_component_active['widget-blog-sidebar'] ) {
                // Blog-2 = Blog Sidebar
                require_once WKFE_PATH . '/elements/blog-2/widget.php';
            }

            if( $check_component_active['widget-blog-revert'] ) {
                // Blog-3 = Blog Revert
                require_once WKFE_PATH . '/elements/blog-3/widget.php';
            }

            if( $check_component_active['widget-blog-hover-animation'] ) {
                // Blog-4 = Blog Hover Animation
                require_once WKFE_PATH . '/elements/blog-4/widget.php';
            }
            if( $check_component_active['widget-blog-image'] ) {
                // Blog-5 = Blog Image
                require_once WKFE_PATH . '/elements/blog-5/widget.php';
            }



                            // Slider Elements
            if( $check_component_active['widget-slider-animation'] ) {
                // Slider-1 = Slider Animation
                require_once WKFE_PATH . '/elements/slider-1/widget.php';
            }

            if( $check_component_active['widget-slider-content-animation'] ) {
                // Slider-2 = Slider Content Animation
                require_once WKFE_PATH . '/elements/slider-2/widget.php';
            }

            if( $check_component_active['widget-slider-box-animation'] ) {
                // Slider-3 = Slider Box Animation
                require_once WKFE_PATH . '/elements/slider-3/widget.php';
            }



                            // Feature Elements
            if( $check_component_active['widget-feature-box'] ) {
                // Feature box
                require_once WKFE_PATH . '/elements/image-feature/widget.php';
            }


                             // Countdown Elements
            if( $check_component_active['widget-countdown'] ) {
                // Countdown
                require_once WKFE_PATH . '/elements/countdown/widget.php';
            }


                             // Animation Text Elements
            if( $check_component_active['widget-animation-text'] ) {
                // Animation Text
                require_once WKFE_PATH . '/elements/animation-text/widget.php';
            }


                            // Post Carousel Elements
            if( $check_component_active['widget-post-carousel'] ) {
                // Post Carousel
                require_once WKFE_PATH . '/elements/carousel/widget.php';
            }


                            // Button Elements
            if( $check_component_active['widget-button'] ) {
                // Button + Modal 
                require_once WKFE_PATH . '/elements/button-modal/widget.php';
            }


                            // Hover Image Elements
            if( $check_component_active['widget-hover-image'] ) {
                // Hover Image
                require_once WKFE_PATH . '/elements/hover-image/widget.php';
            }


                            // Social Share Elements
            if( $check_component_active['widget-social-share-animation'] ) {
                // Social-Share-1  = Social Share Animation
                require_once WKFE_PATH . '/elements/social-share-1/widget.php';
            }
            if( $check_component_active['widget-social-share-collapse'] ) {
                // Social-Share-2  = Social Share Collapse
                require_once WKFE_PATH . '/elements/social-share-2/widget.php';
            }

            // helper functions
            require_once WKFE_PATH . '/elements/helper-functions.php';


            //Admin init
            require_once WKFE_PATH . '/admin/admin-init.php';

             //Admin init
            require_once WKFE_PATH . '/admin/notices/admin-notices.php';

            // System Info
            require_once WKFE_PATH . '/admin/includes/system-info.php';
        }



        // Register style & script
        function widgetkit_for_elementor_register(){
            add_action('wp_head', array($this, 'widgetkit_for_elementor_css'), 0);
            add_action('wp_enqueue_scripts', array($this, 'widgetkit_for_elementor_script'), 0);
            // add_action('plugins_loaded', array($this, 'widgetkit_pro_plugin_init'));
            add_filter('plugin_action_links', array($this, 'widgetkit_for_elementor_pro_settings_link'),10, 2);
        }


        // Css include
        function widgetkit_for_elementor_css(){
            // Base css
            wp_enqueue_style( 'widgetkit_base', plugins_url('/assets/css/base.css',__FILE__ ));

            // owl-carousel css
            wp_enqueue_style( 'owl-css', plugins_url('/assets/css/owl.carousel.min.css',__FILE__ ));

            // Animation css
            wp_enqueue_style( 'animate-css', plugins_url('/assets/css/animate.css',__FILE__ ));

            // Fontawesome css
            wp_enqueue_style( 'fontawesome', plugins_url('/assets/css/font-awesome.min.css',__FILE__ ));

            // Ionsicon css
            wp_enqueue_style( 'ionsicon', plugins_url('/assets/css/ionicons.min.css',__FILE__ ));

            // Plugin-demo css
            wp_enqueue_style( 'widgetkit_demo', plugins_url('/assets/css/plugin-demo.css',__FILE__ ));

            // Main plugin css
            wp_enqueue_style( 'widgetkit_main', plugins_url('/assets/css/widgetkit.css',__FILE__ ));

        }

        // Script include
        function widgetkit_for_elementor_script(){
            $widgetkit_elements_keys = [
                'widget-slider-animation', 
                'widget-slider-content-animation', 
                'widget-slider-box-animation', 
                'widget-portfolio',
                'widget-pricing-single', 
                'widget-pricing-icon', 
                'widget-pricing-tab', 
                'widget-testimonial-single',
                'widget-testimonial-center',
                'widget-team-overlay',
                'widget-team-verticle-icon',
                'widget-team-round',
                'widget-team-animation',
                'widget-blog-carousel',
                'widget-blog-sidebar',
                'widget-blog-revert',
                'widget-blog-hover-animation',
                'widget-blog-image',
                'widget-countdown',
                'widget-animation-text',
                'widget-post-carousel',
                'widget-button',
                'widget-hover-image',
                'widget-feature-box',
                'widget-social-share-animation',
                'widget-social-share-collapse',
            ];
            $widgetkit_default_settings = array_fill_keys( $widgetkit_elements_keys, true ); 

            $check_component_active = get_option( 'widgetkit_save_settings', $widgetkit_default_settings );

            // Bootstarp Script
            wp_enqueue_script( 'bootstarp-js', plugins_url('/assets/js/bootstrap.js', __FILE__ ) , array('jquery'), false, true);
              
            // Owl-carousel js
            wp_enqueue_script( 'owl-carousel', plugins_url('/assets/js/owl.carousel.min.js', __FILE__ ) , array('jquery'), false, true);


            if( $check_component_active['widget-portfolio'] ) {
                // Hoverdie js
                wp_enqueue_script( 'hoverdir', plugins_url('/assets/js/jquery.hoverdir.js', __FILE__ ) , array('jquery'), false, true);
            }

            // Mordernizer js
            wp_enqueue_script( 'modernizr', plugins_url('/assets/js/modernizr.min.js', __FILE__ ) , array('jquery'), false, true);

              // Animate text js
            wp_enqueue_script( 'animate-js', plugins_url('/assets/js/animate-text.js', __FILE__ ) , array('jquery'), false, true);


            if( $check_component_active['widget-portfolio'] ) {
                // Mixitup js
                wp_enqueue_script( 'mixitup-js', plugins_url('/assets/js/mixitup.min.js', __FILE__ ) , array('jquery'), false, true);
                // Light Cae js
                //wp_enqueue_script( 'lightcase-js', plugins_url('/assets/js/lightcase.min.js', __FILE__ ) , array('jquery'), false, true);
            }
           
            // Anime js
            wp_enqueue_script( 'anime-js', plugins_url('/assets/js/anime.min.js', __FILE__ ) , array('jquery'), false, true);

            if( $check_component_active['widget-slider-box-animation'] ) {
                // Imagepackagelaod Js
               wp_enqueue_script( 'widgetkit-imagesloaded', plugins_url('/assets/js/imagesloaded.pkgd.min.js',  __FILE__ ), array('jquery'), '4.1.1', true);

                // Slider 3 js
                wp_enqueue_script( 'widgetkit-slider-3', plugins_url('/assets/js/slider-3.js', __FILE__ ) , array('jquery'), false, true);
            }


            if( $check_component_active['widget-countdown'] ) {
                // Countdown js
                wp_enqueue_script( 'countdown-js', plugins_url('/assets/js/countdown.js', __FILE__ ) , array('jquery'), false, true);
            }

            // Main plugin js
            wp_enqueue_script( 'widgetkit-main', plugins_url('/assets/js/widgetkit.js', __FILE__ ) , array('jquery'), false, true);
        }



    }

    // Class Register
    if (class_exists('WidgetKit_For_Elementor')) {
        # code...
        $widgetkit_for_elementor = new WidgetKit_For_Elementor();
        $widgetkit_for_elementor->widgetkit_for_elementor_register();
        $widgetkit_for_elementor->widgetkit_for_elementor_widget_bundle();

    }

    // Activation
    register_activation_hook( __FILE__, array($widgetkit_for_elementor, 'activate' ));

    // Deactivation
    register_deactivation_hook( __FILE__, array($widgetkit_for_elementor, 'deactivate' ));
