<?php
function ro_price_table_func($atts, $content = null) {
    extract(shortcode_atts(array(
        'title' => '',
        'price' => '',
        'unit' => '',
        'per_time' => '',
        'el_class' => ''
    ), $atts));
	
	$content = wpb_js_remove_wpautop($content, true);

    $class = array();
    $class[] = 'ro-price-table-wrap';
    $class[] = $el_class;
	
    ob_start();
    ?>
		<div class="<?php echo esc_attr(implode(' ', $class)); ?>">
			<div class="ro-price-table">
				<div class="ro-header">
					<div class="ro-header-inner">
						<?php if($title) echo '<h4 class="ro-title">'.$title.'</h4>'; ?>
						<?php if($price) echo '<h2 class="ro-price">'.$price.'<span class="ro-unit">'.$unit.'</span><span class="ro-per-time">'.$per_time.'</span></h2>'; ?>
					</div>
				</div>
				<div class="ro-content"><?php echo $content; ?></div>
			</div>
		</div>
    <?php
    return ob_get_clean();
}

if(function_exists('insert_shortcode')) { insert_shortcode('price_table', 'ro_price_table_func'); }
