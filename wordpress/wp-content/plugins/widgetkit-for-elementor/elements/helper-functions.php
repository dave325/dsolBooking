<?php
namespace Elementor;

function widgetkit_for_elementor_init(){
    Plugin::instance()->elements_manager->add_category(
        'widgetkit_elementor',
        [
            'title'  => esc_html__('WidgetKit Elements', 'widgetkit-for-elementor'),
            'icon'   => 'eicon-font'
        ],
        1
    );
}
add_action('elementor/init','Elementor\widgetkit_for_elementor_init');


if (!function_exists('widgetkit_for_elementor_array_get')) {
    function widgetkit_for_elementor_array_get($array, $key, $default = null)
    {
        if (!is_array($array)) return $default;
        return array_key_exists($key, $array) ? $array[$key] : $default;
    }
}

add_filter( 'widget_text', 'do_shortcode' );
