<?php
function ro_countdown_func($params, $content = null) {
    extract(shortcode_atts(array(
        'date_end' => '+365d +23h +59m +45s',
    ), $params));
	wp_enqueue_script('jquery.plugin.min', URI_PATH . '/assets/vendors/countdown/jquery.plugin.min.js');
	wp_enqueue_script('jquery.countdown.min', URI_PATH . '/assets/vendors/countdown/jquery.countdown.min.js');
    ob_start();
    ?>
	<div data-countdown="<?php echo esc_attr($date_end); ?>" class="ro-countdown-clock"></div>
    <?php
    return ob_get_clean();
}

if(function_exists('insert_shortcode')) { insert_shortcode('countdown', 'ro_countdown_func'); }
?>