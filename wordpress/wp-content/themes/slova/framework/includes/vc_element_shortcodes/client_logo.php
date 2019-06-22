<?php
vc_map(array(
	"name" => __("Client Logo", 'slova'),
	"base" => "client_logo",
	"category" => __('Slova', 'slova'),
	"icon" => "tb-icon-for-vc",
	"params" => array(
		array(
			"type" => "attach_image",
			"class" => "",
			"heading" => __("Logo", 'slova'),
			"param_name" => "logo",
			"value" => "",
			"description" => __("Select logo in this element.", 'slova')
		),
		array(
			"type" => "attach_image",
			"class" => "",
			"heading" => __("Logo Active", 'slova'),
			"param_name" => "logo_active",
			"value" => "",
			"description" => __("Select logo active in this element.", 'slova')
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Logo Url", 'slova'),
			"param_name" => "logo_url",
			"value" => "",
			"description" => __("Please, enter logo url in this element.", 'slova')
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Extra Class", 'slova'),
			"param_name" => "el_class",
			"value" => "",
			"description" => __ ( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'slova' )
		),
	)
));
