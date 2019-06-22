<?php
function ro_client_logo_func($atts) {
    extract(shortcode_atts(array(
        'logo' => '',
        'logo_active' => '',
        'logo_url' => '#',
        'el_class' => ''
    ), $atts));

    $class = array();
    $class[] = 'ro-client-logo-wrap';
    $class[] = $el_class;
	
    ob_start();
    ?>
		<div class="<?php echo esc_attr(implode(' ', $class)); ?>">
			<a href="<?php echo esc_url($logo_url); ?>">
				<div class="ro-client-logo">
					<?php echo wp_get_attachment_image( $logo, 'full' ); ?>
					<?php echo wp_get_attachment_image( $logo_active, 'full' ); ?>
				</div>
			</a>
		</div>
    <?php
    return ob_get_clean();
}

if(function_exists('insert_shortcode')) { insert_shortcode('client_logo', 'ro_client_logo_func'); }
