<?php
function ro_maps_render($params) {
    extract(shortcode_atts(array(
    	'api'					=>	'AIzaSyCyuW48kPjku1h6fle8WYwO1pKI3Hdp4wk',
    	'address'				=>	'New York, United States',
    	'infoclick'				=>	'',
    	'coordinate'			=>	'',
    	'markercoordinate'		=>	'',
    	'markertitle'			=>	'',
    	'markerdesc'			=>	'',
    	'markerlist'			=>	'',
    	'markericon'			=>	'',
    	'infowidth'				=>	'200',
    	'width' 				=> 	'auto',
    	'height' 				=> 	'350px',
    	'type'					=>	'ROADMAP',
    	'style'					=>	'',
    	'zoom'					=>	'13',
    	'scrollwheel'			=>	'',
    	'pancontrol'			=>	'',
    	'zoomcontrol'			=>	'',
    	'scalecontrol'			=>	'',
    	'maptypecontrol'		=>	'',
    	'streetviewcontrol'		=>	'',
    	'overviewmapcontrol'	=>	'',
	), $params));
	
    /* API Key */
    if(!$api){
        $api = 'AIzaSyCyuW48kPjku1h6fle8WYwO1pKI3Hdp4wk';
    }
    $api_js = "https://maps.googleapis.com/maps/api/js?key=$api&sensor=false";
    wp_enqueue_script('maps-googleapis',$api_js,array(),'3.0.0');
    wp_enqueue_script('maps-apiv3', URI_PATH_FR . "/shortcodes/map_v3/maps.js",array(),'1.0.0');
    /* Map Style defualt */
    $map_styles = array(
    	'Subtle-Grayscale'=>'[{"featureType":"landscape","stylers":[{"saturation":-100},{"lightness":65},{"visibility":"on"}]},{"featureType":"poi","stylers":[{"saturation":-100},{"lightness":51},{"visibility":"simplified"}]},{"featureType":"road.highway","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"road.arterial","stylers":[{"saturation":-100},{"lightness":30},{"visibility":"on"}]},{"featureType":"road.local","stylers":[{"saturation":-100},{"lightness":40},{"visibility":"on"}]},{"featureType":"transit","stylers":[{"saturation":-100},{"visibility":"simplified"}]},{"featureType":"administrative.province","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":-25},{"saturation":-100}]},{"featureType":"water","elementType":"geometry","stylers":[{"hue":"#ffff00"},{"lightness":-25},{"saturation":-97}]}]',
    	'Shades-of-Grey'=>'[{"featureType":"all","elementType":"labels.text.fill","stylers":[{"saturation":36},{"color":"#000000"},{"lightness":40}]},{"featureType":"all","elementType":"labels.text.stroke","stylers":[{"visibility":"on"},{"color":"#000000"},{"lightness":16}]},{"featureType":"all","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"administrative","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"administrative","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":17},{"weight":1.2}]},{"featureType":"landscape","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":20}]},{"featureType":"poi","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":21}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#000000"},{"lightness":17}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#000000"},{"lightness":29},{"weight":0.2}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":18}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":16}]},{"featureType":"transit","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":19}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#000000"},{"lightness":17}]}]',
    	'Blue-water'=>'[{"featureType":"administrative","elementType":"labels.text.fill","stylers":[{"color":"#444444"}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2f2f2"}]},{"featureType":"poi","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"all","stylers":[{"saturation":-100},{"lightness":45}]},{"featureType":"road.highway","elementType":"all","stylers":[{"visibility":"simplified"}]},{"featureType":"road.arterial","elementType":"labels.icon","stylers":[{"visibility":"off"}]},{"featureType":"transit","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#46bcec"},{"visibility":"on"}]}]',
    	'Pale-Dawn'=>'[{"featureType":"administrative","elementType":"all","stylers":[{"visibility":"on"},{"lightness":33}]},{"featureType":"landscape","elementType":"all","stylers":[{"color":"#f2e5d4"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#c5dac6"}]},{"featureType":"poi.park","elementType":"labels","stylers":[{"visibility":"on"},{"lightness":20}]},{"featureType":"road","elementType":"all","stylers":[{"lightness":20}]},{"featureType":"road.highway","elementType":"geometry","stylers":[{"color":"#c5c6c6"}]},{"featureType":"road.arterial","elementType":"geometry","stylers":[{"color":"#e4d7c6"}]},{"featureType":"road.local","elementType":"geometry","stylers":[{"color":"#fbfaf7"}]},{"featureType":"water","elementType":"all","stylers":[{"visibility":"on"},{"color":"#acbcc9"}]}]',
    	'Blue-Essence'=>'[{"featureType":"landscape.natural","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"color":"#e0efef"}]},{"featureType":"poi","elementType":"geometry.fill","stylers":[{"visibility":"on"},{"hue":"#1900ff"},{"color":"#c0e8e8"}]},{"featureType":"road","elementType":"geometry","stylers":[{"lightness":100},{"visibility":"simplified"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"transit.line","elementType":"geometry","stylers":[{"visibility":"on"},{"lightness":700}]},{"featureType":"water","elementType":"all","stylers":[{"color":"#7dcdcd"}]}]',
    	'Apple-Maps-esque'=>'[{"featureType":"landscape.man_made","elementType":"geometry","stylers":[{"color":"#f7f1df"}]},{"featureType":"landscape.natural","elementType":"geometry","stylers":[{"color":"#d0e3b4"}]},{"featureType":"landscape.natural.terrain","elementType":"geometry","stylers":[{"visibility":"off"}]},{"featureType":"poi","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"poi.business","elementType":"all","stylers":[{"visibility":"off"}]},{"featureType":"poi.medical","elementType":"geometry","stylers":[{"color":"#fbd3da"}]},{"featureType":"poi.park","elementType":"geometry","stylers":[{"color":"#bde6ab"}]},{"featureType":"road","elementType":"geometry.stroke","stylers":[{"visibility":"off"}]},{"featureType":"road","elementType":"labels","stylers":[{"visibility":"off"}]},{"featureType":"road.highway","elementType":"geometry.fill","stylers":[{"color":"#ffe15f"}]},{"featureType":"road.highway","elementType":"geometry.stroke","stylers":[{"color":"#efd151"}]},{"featureType":"road.arterial","elementType":"geometry.fill","stylers":[{"color":"#ffffff"}]},{"featureType":"road.local","elementType":"geometry.fill","stylers":[{"color":"black"}]},{"featureType":"transit.station.airport","elementType":"geometry.fill","stylers":[{"color":"#cfb2db"}]},{"featureType":"water","elementType":"geometry","stylers":[{"color":"#a2daf2"}]}]',
    );
    /* Select Template */
    $map_template = '';
    switch ($style){
    	case '':
    		$map_template = '';
    		break;
    	default:
    		$map_template = rawurlencode($map_styles[$style]);
    		break;
    }
    /* marker render */
    $marker = new stdClass();
    if($markercoordinate){
    	$marker->markercoordinate = $markercoordinate;
    	if($markerdesc || $markertitle){
    	$marker->markerdesc = 	'<div class="ro-maps-info-content">'.
    							'<h5>'.$markertitle.'</h5>'.
    							'<span>'.$markerdesc.'</span>'.
    							'</div>';
    	}
    	if($markericon){
    		$marker->markericon = wp_get_attachment_url($markericon);
    	}
    }
    if($markerlist){
    	$marker->markerlist = $markerlist;
    }
    $marker = rawurlencode(json_encode($marker));
    /* control render */
    $controls = new stdClass();
    if($scrollwheel == true){ $controls->scrollwheel = 1; } else { $controls->scrollwheel = 0; }
    if($pancontrol == true){ $controls->pancontrol = true; } else { $controls->pancontrol = false; }
    if($zoomcontrol == true){ $controls->zoomcontrol = true; } else { $controls->zoomcontrol = false; }
    if($scalecontrol == true){ $controls->scalecontrol = true; } else { $controls->scalecontrol = false; }
    if($maptypecontrol == true){ $controls->maptypecontrol = true; } else { $controls->maptypecontrol = false; }
    if($streetviewcontrol == true){ $controls->streetviewcontrol = true; } else { $controls->streetviewcontrol = false; }
    if($overviewmapcontrol == true){ $controls->overviewmapcontrol = true; } else { $controls->overviewmapcontrol = false; }
    if($infoclick == true){ $controls->infoclick = true; } else { $controls->infoclick = false; }
    $controls->infowidth = $infowidth;
    $controls->style = $style;
    $controls = rawurlencode(json_encode($controls));
    /* data render */
    $setting = array(
    	"data-address='$address'",
    	"data-marker='$marker'",
    	"data-coordinate='$coordinate'",
    	"data-type='$type'",
     	"data-zoom='$zoom'",
    	"data-template='$map_template'",
    	"data-controls='$controls'"
    );
    ob_start();
	$maps_id = uniqid('maps-');
    ?>
    <div class="ro_maps">
    	<div id="<?php echo $maps_id; ?>" class="maps-render" <?php echo implode(' ', $setting); ?> style="width:<?php echo esc_attr($width); ?>;height: <?php echo esc_attr($height); ?>"></div>
    </div>
	<?php
	return ob_get_clean();
}
if(function_exists('insert_shortcode')) { insert_shortcode('maps', 'ro_maps_render'); }