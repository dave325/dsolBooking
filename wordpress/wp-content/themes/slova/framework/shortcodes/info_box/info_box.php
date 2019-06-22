<?php
	function ro_info_box_func($atts, $content = null) {
		extract(shortcode_atts(array(
		'title' => '',
		//'icon' => 'fa fa-map-marker',
		'icon_code' => '&#xf109;',
		'icon_width' => '',
        'el_class' => ''
		), $atts));
		$class = array();
		$class[] = 'ro-info-box';
		$class[] = $el_class;
		ob_start();
	?>
	<div class="<?php echo esc_attr(implode(' ', $class)); ?>">
		<div class ="ro-inner">
			<svg width="0" height="0">
				<defs>
					<linearGradient id="ro-text-gradient" x1="0%" y1="0%" x2="100%" y2="0%">
						<stop offset="0%" style="stop-color:#0084ff;" />
						<stop offset="100%" style="stop-color:#a360ff;" />
					</linearGradient>	
				</defs>
			</svg>
			<h5 class="ro-title"><?php echo $title; ?></h5>
			<div class="ro-icon-gradient">
				<svg width="<?php if($icon_width){ echo esc_attr($icon_width); } else { echo '51'; } ?>" height="60">
					<text class="ro-text-gradient" x="1" y="49" fill="url(#ro-text-gradient)"><?php echo $icon_code;?></text>
				</svg>
			</div>
			<!--i class="<?php echo esc_attr($icon); ?>"></i-->
			<div class="ro-content"><?php echo $content; ?></div>
		</div>
	</div>
    <?php
		return ob_get_clean();
	}
if(function_exists('insert_shortcode')) { insert_shortcode('info_box', 'ro_info_box_func');}