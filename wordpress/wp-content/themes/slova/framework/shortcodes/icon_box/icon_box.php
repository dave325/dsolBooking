<?php
function ro_icon_box_func($atts, $content = null) {
    extract(shortcode_atts(array(
		'icon' => '',
        'el_class' => ''
    ), $atts));
    $class = array();
	$class[] = 'ro-icon-style2 ro-center';
	$class[] = $el_class;
    ob_start();
    ?>
		<div class="<?php echo esc_attr(implode(' ', $class)); ?>">
			<i class="<?php echo esc_attr($icon) ?>"></i>
		</div>
		
    <?php
    return ob_get_clean();
}
if(function_exists('insert_shortcode')) { insert_shortcode('icon_box', 'ro_icon_box_func');}
