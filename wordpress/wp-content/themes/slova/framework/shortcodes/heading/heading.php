<?php
function ro_heading_func($atts) {
    extract(shortcode_atts(array(
        'text' => '',
        'sub_text' => '',
        'el_class' => ''
    ), $atts));

    $class = array();
    $class[] = 'ro-hr-heading';
    $class[] = $el_class;
    ob_start();
    ?>
		<div class="<?php echo esc_attr(implode(' ', $class)); ?>">
			<?php
				if ( $text ) echo '<h2 class="ro-text">'.esc_html($text).'</h2>';
				if ( $sub_text ) echo '<div class="ro-subtext">'.esc_html($sub_text).'</div>';
			?>
		</div>
    <?php
    return ob_get_clean();
}

if(function_exists('insert_shortcode')) { insert_shortcode('heading', 'ro_heading_func'); }
