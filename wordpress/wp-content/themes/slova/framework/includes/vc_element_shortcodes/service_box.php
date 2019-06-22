<?php
vc_map(array(
	"name" => __("Service Box", 'slova'),
	"base" => "service_box",
	"category" => __('Slova', 'slova'),
	"icon" => "tb-icon-for-vc",
	"params" => array(
		array(
			"type" => "dropdown",
			"class" => "",
			"heading" => __("Style", 'slova'),
			"param_name" => "style",
			"value" => array(
				"Style 1" => "style1",
				"Style 2" => "style2",
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
			"dependency" => array(
				"element"=>"tpl",
				"value"=> array("tpl1", "tpl3")
			),
			"description" => __("Please, enter description in this element.", 'slova')
		),
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Extra Link", 'slova'),
			"param_name" => "ex_link",
			"value" => "",
			"description" => __("Please, enter extra link in this element.", 'slova')
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
