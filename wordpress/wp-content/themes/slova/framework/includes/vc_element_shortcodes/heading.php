<?php
vc_map(array(
	"name" => __("Heading", 'slova'),
	"base" => "heading",
	"class" => "title",
	"category" => __('Slova', 'slova'),
	"icon" => "tb-icon-for-vc",
	"params" => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Template", 'slova'),
			"param_name" => "tpl",
			"value" => array(
				"Template 1" => "tpl1",
				"Template 2" => "tpl2"
			),
			"description" => __("Select select template in this element.", 'slova')
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Text", 'slova'),
			"param_name" => "text",
			"value" => "",
			"description" => __("Please, Enter text in this element.", 'slova')
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Sub Text", 'slova'),
			"param_name" => "sub_text",
			"value" => "",
			"description" => __("Please, Enter sub text in this element.", 'slova')
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Extra Class", 'slova'),
			"param_name" => "el_class",
			"value" => "",
			"description" => __("If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'slova')
		),
	)
));
