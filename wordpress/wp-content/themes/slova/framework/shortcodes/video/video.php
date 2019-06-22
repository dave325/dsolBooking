<?php
function ro_video($params, $content = null){
	extract(shortcode_atts(array(
	'height' => 200,
	'width' => '100%'
	), $params));

	wp_enqueue_script('fitvids', URI_PATH_FR . "/shortcodes/video/fitvids.js");
	$video = parse_url($content);
	
	switch($video['host']) {
		case 'youtu.be':
			$id = trim($video['path'],'/');
			$src = 'https://www.youtube.com/embed/' . $id;
			break;
		case 'www.youtube.com':
		case 'youtube.com':
			parse_str($video['query'], $query);
			$id = $query['v'];
			$src = 'https://www.youtube.com/embed/' . $id;
			break;
		case 'vimeo.com':
		case 'www.vimeo.com':
			$id = trim($video['path'],'/');
			$src = "http://player.vimeo.com/video/{$id}";
	}

	$out = '<div id="video-'.esc_attr($id).'" class="shortcode-video">
	<iframe src="'.esc_url($src).'" width="'.esc_attr($width).'" height="'.esc_attr($height).'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>
	</div>';
	return $out;

}

if(function_exists('insert_shortcode')) { insert_shortcode('tb-video', 'ro_video'); }
