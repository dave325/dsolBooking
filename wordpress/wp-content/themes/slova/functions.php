<?php
/* Define THEME */
if (!defined('URI_PATH')) define('URI_PATH', get_template_directory_uri());
if (!defined('ABS_PATH')) define('ABS_PATH', get_template_directory());
if (!defined('URI_PATH_FR')) define('URI_PATH_FR', URI_PATH.'/framework');
if (!defined('ABS_PATH_FR')) define('ABS_PATH_FR', ABS_PATH.'/framework');
if (!defined('URI_PATH_ADMIN')) define('URI_PATH_ADMIN', URI_PATH_FR.'/admin');
if (!defined('ABS_PATH_ADMIN')) define('ABS_PATH_ADMIN', ABS_PATH_FR.'/admin');
/* Theme Options */
if ( !class_exists( 'ReduxFramework' ) ) {
require_once( ABS_PATH . '/redux-framework/ReduxCore/framework.php' );
}
require_once (ABS_PATH_ADMIN.'/theme-options.php');
require_once (ABS_PATH_ADMIN.'/index.php');
global $tb_options;
/* Template Functions */
require_once ABS_PATH_FR . '/template-functions.php';
/* Post Favorite */
require_once ABS_PATH_FR . '/templates/post-favorite.php';
/* Post Functions */
require_once ABS_PATH_FR . '/templates/post-functions.php';
/* Post Type */
require_once ABS_PATH_FR.'/post-type/portfolio.php';
require_once ABS_PATH_FR.'/post-type/team.php';
require_once ABS_PATH_FR.'/post-type/testimonial.php';
require_once ABS_PATH_FR.'/post-type/video.php';
/* Function for Framework */
require_once ABS_PATH_FR . '/includes.php';
function _slova_filter_fw_ext_backups_demos($demos)
	{
		$demos_array = array(
			'slova' => array(
				'title' => esc_html__('Slova Demo', 'slova'),
				'screenshot' => 'http://jwsuperthemes.com/import_demo/slova/screenshot.jpg',
				'preview_link' => 'http://jwsuperthemes.com/slova',
			),
		);
        $download_url = 'http://jwsuperthemes.com/import_demo/slova/download-script/';
		foreach ($demos_array as $id => $data) {
			$demo = new FW_Ext_Backups_Demo($id, 'piecemeal', array(
				'url' => $download_url,
				'file_id' => $id,
			));
			$demo->set_title($data['title']);
			$demo->set_screenshot($data['screenshot']);
			$demo->set_preview_link($data['preview_link']);

			$demos[$demo->get_id()] = $demo;

			unset($demo);
		}

		return $demos;
	}
	add_filter('fw:ext:backups-demo:demos', '_slova_filter_fw_ext_backups_demos');
/* Register Sidebar */
if (!function_exists('ro_RegisterSidebar')) {
function ro_RegisterSidebar(){
global $tb_options;
register_sidebar(array(
'name' => __('Main Sidebar', 'slova'),
'id' => 'tbtheme-main-sidebar',
'before_widget' => '<div id="%1$s" class="widget %2$s">',
'after_widget' => '</div>',
'before_title' => '<h4 class="wg-title">',
'after_title' => '</h4>',
));
register_sidebar(array(
'name' => __('Blog Left Sidebar', 'slova'),
'id' => 'tbtheme-left-sidebar',
'before_widget' => '<div id="%1$s" class="widget %2$s">',
'after_widget' => '</div>',
'before_title' => '<h4 class="wg-title">',
'after_title' => '</h4>',
));
register_sidebar(array(
'name' => __('Blog Right Sidebar', 'slova'),
'id' => 'tbtheme-right-sidebar',
'before_widget' => '<div id="%1$s" class="widget %2$s">',
'after_widget' => '</div>',
'before_title' => '<h4 class="wg-title">',
'after_title' => '</h4>',
));
register_sidebar(array(
'name' => __('After Content Post', 'slova'),
'id' => 'tbtheme-after-content-post',
'before_widget' => '<div id="%1$s" class="widget %2$s">',
'after_widget' => '</div>',
'before_title' => '<h4 class="wg-title">',
'after_title' => '</h4>',
));
register_sidebars(4, array(
'name' => __('Custom Sidebar %d', 'slova'),
'id' => 'tbtheme-custom-sidebar',
'before_widget' => '<div id="%1$s" class="widget %2$s">',
'after_widget' => '<div style="clear:both;"></div></div>',
'before_title' => '<h4 class="wg-title">',
'after_title' => '</h4>',
));
register_sidebar(array(
'name' => __('Header Top Sidebar', 'slova'),
'id' => 'tbtheme-header-top-sidebar',
'before_widget' => '<div id="%1$s" class="widget %2$s">',
'after_widget' => '</div>',
'before_title' => '<h4 class="wg-title">',
'after_title' => '</h4>',
));
register_sidebar(array(
'name' => __('Menu Right Sidebar', 'slova'),
'id' => 'tbtheme-menu-right-sidebar',
'before_widget' => '<div id="%1$s" class="widget %2$s">',
'after_widget' => '</div>',
'before_title' => '<h4 class="wg-title">',
'after_title' => '</h4>',
));
if (class_exists ( 'Woocommerce' )) {
	register_sidebar(array(
	'name' => __('Shop Sidebar', 'slova'),
	'id' => 'tbtheme-shop-sidebar',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h3 class="wg-title">',
	'after_title' => '</h3>',
	));
	register_sidebar(array(
	'name' => __('Detail Product Sidebar', 'slova'),
	'id' => 'tbtheme-detail-product-sidebar',
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h3 class="wg-title">',
	'after_title' => '</h3>',
	));
}
$tb_footer_top_args = array();
$tb_footer_top_args = array(
'id' => 'tbtheme-footer-top-widget',
'before_widget' => '<div id="%1$s" class="widget %2$s">',
'after_widget' => '<div style="clear:both;"></div></div>',
'before_title' => '<h4 class="wg-title">',
'after_title' => '</h4>',
);
$tb_footer_top_column = isset($tb_options['tb_footer_top_column']) ? (int)$tb_options['tb_footer_top_column'] : 4;
$tb_footer_top_args['name'] = ($tb_footer_top_column>=2) ? 'Footer Top Widget %d' : 'Footer Top Widget 1';
register_sidebars($tb_footer_top_column, $tb_footer_top_args);
$tb_footer_bottom_args = array();
$tb_footer_bottom_args = array(
'id' => 'tbtheme-footer-bottom-widget',
'before_widget' => '<div id="%1$s" class="widget %2$s">',
'after_widget' => '<div style="clear:both;"></div></div>',
'before_title' => '<h4 class="wg-title">',
'after_title' => '</h4>',
);
$tb_footer_bottom_column = isset($tb_options['tb_footer_bottom_column']) ? (int)$tb_options['tb_footer_bottom_column'] : 2;
$tb_footer_bottom_args['name'] = ($tb_footer_bottom_column>=2) ? 'Footer Bottom Widget %d' : 'Footer Bottom Widget 1';
register_sidebars($tb_footer_bottom_column, $tb_footer_bottom_args);
}
}
add_action( 'init', 'ro_RegisterSidebar' );
/* Add Stylesheet And Script */
function ro_theme_enqueue_style() {
global $tb_options;
wp_enqueue_style( 'bootstrap.min', URI_PATH.'/assets/css/bootstrap.min.css', false );
wp_enqueue_style('owl.carousel', URI_PATH . "/assets/vendors/owl-carousel/owl.carousel.css",array(),"");
wp_enqueue_style('flexslider.css', URI_PATH . "/assets/css/flexslider.css",array(),"");
wp_enqueue_style('jquery.fancybox', URI_PATH . "/assets/vendors/FancyBox/jquery.fancybox.css",array(),"");
wp_enqueue_style('font-awesome', URI_PATH.'/assets/css/font-awesome.min.css', array(), '4.1.0');
wp_enqueue_style('font-ionicons', URI_PATH.'/assets/css/ionicons.min.css', array(), '1.5.2');
wp_enqueue_style('medicare_icon', URI_PATH.'/assets/css/medicare_icon.css', array(), '1.0.0');
wp_enqueue_style( 'tb.core.min', URI_PATH.'/assets/css/tb.core.min.css', false );
wp_enqueue_style( 'style', URI_PATH.'/style.css', false );	
}
add_action( 'wp_enqueue_scripts', 'ro_theme_enqueue_style' );

function ro_theme_enqueue_script() {
global $tb_options;
wp_enqueue_script("jquery");
wp_enqueue_script( 'bootstrap.min', URI_PATH.'/assets/js/bootstrap.min.js', array('jquery'), '', true  );
wp_enqueue_script( 'datepicker.min', URI_PATH.'/assets/js/datepicker.min.js', array('jquery'), '', true  );
wp_enqueue_script( 'menu', URI_PATH.'/assets/js/menu.js', array('jquery'), '', true  );
wp_enqueue_script( 'owl.carousel.min', URI_PATH.'/assets/vendors/owl-carousel/owl.carousel.min.js', array('jquery'), '', true );
wp_enqueue_script( 'jquery.flexslider-min', URI_PATH.'/assets/js/jquery.flexslider-min.js', array('jquery'), '', true );
wp_enqueue_script( 'jquery.fancybox', URI_PATH.'/assets/vendors/FancyBox/jquery.fancybox.js', array('jquery'), '', true );
wp_enqueue_script( 'parallax', URI_PATH.'/assets/js/parallax.js', array('jquery'), '', true  );
wp_enqueue_script( 'main', URI_PATH.'/assets/js/main.js', array('jquery'), '', true  );
if( $tb_options['tb_smoothscroll'] ){
	wp_enqueue_script( 'smooth-scroll', URI_PATH.'/assets/js/SmoothScroll.js', array('jquery'), false, true );
	wp_enqueue_script( 'smootstate-js', URI_PATH.'/assets/js/jquery.smoothState.js', array( 'jquery' ), '0.7.2', true );
	wp_enqueue_script( 'script-js', URI_PATH.'/assets/js/script.min.js', array( 'jquery', 'smootstate-js' ), '1.0.0', true );
}
}
add_action( 'wp_enqueue_scripts', 'ro_theme_enqueue_script' );

/* Style Inline */
function ro_add_style_inline() {
global $tb_options;
$custom_style = null;
if ($tb_options['custom_css_code']) {
$custom_style .= "{$tb_options['custom_css_code']}";
}
$path = URI_PATH;
wp_enqueue_style('wp_custom_style', URI_PATH . '/assets/css/wp_custom_style.css',array('style'));

/* Body background */
$tb_background_color =& $tb_options['tb_background']['background-color'];
$tb_background_image =& $tb_options['tb_background']['background-image'];
$tb_background_repeat =& $tb_options['tb_background']['background-repeat'];
$tb_background_position =& $tb_options['tb_background']['background-position'];
$tb_background_size =& $tb_options['tb_background']['background-size'];
$tb_background_attachment =& $tb_options['tb_background']['background-attachment'];
$custom_style .= "body{ background-color: $tb_background_color;}";
if($tb_background_image){
$custom_style .= "body{ background: url('$tb_background_image') $tb_background_repeat $tb_background_attachment $tb_background_position;background-size: $tb_background_size;}";
}
/* Title bar background */
$title_bar_bg = get_post_meta(get_the_ID(), 'tb_title_bar_bg', true);
if($title_bar_bg) {
$custom_style .= ".ro-section-title-bar { background: url('$title_bar_bg') no-repeat scroll center center / cover ;}";
} else {
$tb_title_bar_bg_color =& $tb_options['tb_page_title_bg']['background-color'];
$title_bar_bg_image =& $tb_options['tb_page_title_bg']['background-image'];
$title_bar_bg_repeat =& $tb_options['tb_page_title_bg']['background-repeat'];
$title_bar_bg_position =& $tb_options['tb_page_title_bg']['background-position'];
$title_bar_bg_size =& $tb_options['tb_page_title_bg']['background-size'];
$title_bar_bg_attachment =& $tb_options['tb_page_title_bg']['background-attachment'];
$custom_style .= ".ro-section-title-bar { background-color: $tb_title_bar_bg_color;}";
if($title_bar_bg_image){
	$custom_style .= ".ro-section-title-bar { background: url('$title_bar_bg_image') $title_bar_bg_repeat $title_bar_bg_attachment $title_bar_bg_position;background-size: $title_bar_bg_size;}";
}
}

wp_add_inline_style( 'wp_custom_style', $custom_style );
/*End Font*/
}
add_action( 'wp_enqueue_scripts', 'ro_add_style_inline' );
/* Less */
if(isset($tb_options['tb_less'])&&$tb_options['tb_less']){
require_once ABS_PATH_FR.'/presets.php';
}
/* Widgets */
require_once ABS_PATH_FR.'/widgets/abstract-widget.php';
require_once ABS_PATH_FR.'/widgets/widgets.php';
/* Woo commerce function */
if (class_exists('Woocommerce')) {
require_once ABS_PATH . '/woocommerce/wc-template-function.php';
require_once ABS_PATH . '/woocommerce/wc-template-hooks.php';
}
