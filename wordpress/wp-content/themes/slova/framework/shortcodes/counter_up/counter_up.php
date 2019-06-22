<?php
function ro_counter_up_func($atts, $content = null) {
    extract(shortcode_atts(array(
        'tpl' => 'tpl1',
        //'icon' => '',
		'icon_code' => '&#xf109;',
		'icon_width' => '',
        'number' => '',
        'title' => '',
        'el_class' => ''
    ), $atts));
	
	$content = wpb_js_remove_wpautop($content, true);

    $class = array();
    $class[] = 'ro-counter-up-wrap';
    $class[] = $tpl;
    $class[] = $el_class;
	
	wp_enqueue_script('jquery.counterup.min', URI_PATH . '/assets/js/jquery.counterup.min.js',array('jquery'),'1.0');
	wp_enqueue_script('waypoints.min', URI_PATH . '/assets/js/waypoints.min.js',array('jquery'),'1.6.2');
	
    ob_start();
    ?>
		<div class="<?php echo esc_attr(implode(' ', $class)); ?>">
			<div class="ro-counter">
				<?php if($icon_code) { ?>
					<!--svg width="0" height="0">
						<defs>
							<linearGradient id="ro-text-gradient" x1="0%" y1="100%" x2="0%" y2="0%">
								<stop offset="0%" style="stop-color:#0084ff;" />
								<stop offset="100%" style="stop-color:#a360ff;" />
							</linearGradient>	
							<linearGradient id="ro-text-gradient-hover"  x1="0%" y1="100%" x2="0%" y2="0%">
								<stop offset="0%" style="stop-color:#ffffff;" />
								<stop offset="100%" style="stop-color:#ffffff;" />
							</linearGradient>
						</defs>
					</svg-->
					<div class="ro-icon-gradient">
						<svg width="<?php if($icon_width){ echo esc_attr($icon_width); } else { echo '37'; } ?>" height="40">
							<text class="ro-text-gradient" x="1" y="35" fill="url(#ro-text-gradient)"><?php echo $icon_code;?></text>
							<text class="ro-text-gradient ro-active" x="1" y="35" fill="url(#ro-text-gradient-hover)"><?php echo $icon_code;?></text>
						</svg>
					</div>
				<?php } ?>
				<?php
					//if($icon) echo '<i class="'.esc_attr($icon).'"></i>';
					if($number) echo '<span class="ro-number">'.$number.'</span>';
					if($title) echo '<h4 class="ro-title">'.$title.'</h4>';
					if($content) echo '<div class="ro-content">'.$content.'</div>';
				?>
			</div>
		</div>
    <?php
    return ob_get_clean();
}

if(function_exists('insert_shortcode')) { insert_shortcode('counter_up', 'ro_counter_up_func'); }
