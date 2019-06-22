<?php
function ro_service_box_func($atts, $content = null) {
    extract(shortcode_atts(array(
		'style' => 'style1',
		//'icon' => '',
		'icon_code' => '&#xf109;',
		'icon_width' => '',
		'title' => '',
        'ex_link' => '#',
        'el_class' => ''
    ), $atts));

	$content = wpb_js_remove_wpautop($content, true);
	
    $class = array();
	$class[] = 'ro-service-wrap';
	$class[] = $style;
	$class[] = $el_class;
    ob_start();
    ?>
		<div class="<?php echo esc_attr(implode(' ', $class)); ?>">
			<a href="<?php echo esc_url($ex_link); ?>">
				<div class="ro-service">
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
						<div class="ro-icon">
							<div class="ro-icon-gradient">
								<?php if($style == 'style1') { ?>
									<svg width="<?php if($icon_width){ echo esc_attr($icon_width); } else { echo '37'; } ?>" height="40">
										<text class="ro-text-gradient" x="1" y="35" fill="url(#ro-text-gradient)"><?php echo $icon_code;?></text>
										<text class="ro-text-gradient ro-active" x="1" y="35" fill="url(#ro-text-gradient-hover)"><?php echo $icon_code;?></text>
									</svg>
								<?php } ?>
								<?php if($style == 'style2') { ?>
									<svg width="<?php if($icon_width){ echo esc_attr($icon_width); } else { echo '21'; } ?>" height="21">
										<text class="ro-text-gradient" x="1" y="18" fill="url(#ro-text-gradient)"><?php echo $icon_code;?></text>
										<text class="ro-text-gradient ro-active" x="1" y="18" fill="url(#ro-text-gradient-hover)"><?php echo $icon_code;?></text>
									</svg>
								<?php } ?>
							</div>
						</div>
					<?php } ?>
					<?php 
						//if($icon) echo '<div class="ro-icon"><i class="'.esc_attr($icon).'"></i></div>';
						if($title) echo '<h5 class="ro-title">'.esc_html($title).'</h5>';
						if($content) echo '<div class="ro-content">'.$content.'</div>';
					?>
				</div>
			</a>
		</div>
		
    <?php
    return ob_get_clean();
}
if(function_exists('insert_shortcode')) { insert_shortcode('service_box', 'ro_service_box_func');}
