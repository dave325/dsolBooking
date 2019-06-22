<?php
vc_map(array(
	"name" => __("Counter Up", 'slova'),
	"base" => "counter_up",
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
				"Template 2" => "tpl2",
			),
			"description" => __('Select template in this element.', 'slova')
		),
		/*array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Icon", 'slova'),
			"param_name" => "icon",
			"value" => "",
			"description" => __("Please, enter class icon in this element.", 'slova')
		),*/
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Icon Code", 'slova'),
			"param_name" => "icon_code",
			"value" => "",
			"description" => __("Please, enter code icon in this element.", 'slova')
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Icon Width", 'slova'),
			"param_name" => "icon_width",
			"value" => "",
			"description" => __("Please, enter width icon in this element.", 'slova')
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => __("Number", 'slova'),
			"param_name" => "number",
			"value" => "",
			"description" => __("Please, enter number in this element.", 'slova')
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Title", 'slova'),
			"param_name" => "title",
			"value" => "",
			"description" => __("Please, enter title in this element.", 'slova')
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
