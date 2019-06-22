<?php
vc_map(array(
	"name" => __("Price Table", 'slova'),
	"base" => "price_table",
	"category" => __('Slova', 'slova'),
	"icon" => "tb-icon-for-vc",
	"params" => array(
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Title", 'slova'),
			"param_name" => "title",
			"value" => "",
			"description" => __("Please, enter title in this element.", 'slova')
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Price", 'slova'),
			"param_name" => "price",
			"value" => "",
			"description" => __("Please, enter price in this element.", 'slova')
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Unit", 'slova'),
			"param_name" => "unit",
			"value" => "",
			"description" => __("Please, enter unit in this element.", 'slova')
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Per Time", 'slova'),
			"param_name" => "per_time",
			"value" => "",
			"description" => __("Please, enter per time in this element.", 'slova')
		),
		array(
			"type" => "textarea_html",
			"class" => "",
			"heading" => __("Description", 'slova'),
			"param_name" => "content",
			"value" => "",
			"description" => __("Please, enter description in this element.", 'slova')
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
