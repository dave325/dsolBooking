<?php
$elements = array(
	'video',
	'heading',
	'service_box',
	'icon_box',
	'info_box',
	'counter_up',
	'countdown',
	'price_table',
	'client_logo',
	'blog',
	'blog_special',
	'blog_masonry',
	'blog_special_carousel',
	'portfolio',
	'portfolio_slider',
	'portfolio_grid',
	'team',
	'testimonial',
	'testimonial_slider',
	'testimonial_carousel',
	'video_slider',
	'map_v3',
);

foreach ($elements as $element) {
	include($element .'/'. $element.'.php');
}

if(class_exists('Woocommerce')){
	$wooshops = array(
		'product_grid',
	);
	
	foreach ($wooshops as $wooshop) {
		include($wooshop .'/'. $wooshop.'.php'); 
	}
}
