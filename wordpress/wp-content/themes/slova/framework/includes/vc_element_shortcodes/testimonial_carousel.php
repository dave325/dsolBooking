<?php
vc_map ( array (
		"name" => 'Testimonial carousel',
		"base" => "testimonial_carousel",
		"icon" => "tb-icon-for-vc",
		"category" => __ ( 'Slova', 'slova' ), 
		'admin_enqueue_js' => array(URI_PATH_FR.'/admin/assets/js/customvc.js'),
		"params" => array (
			array (
					"type" => "tb_taxonomy",
					"taxonomy" => "testimonial_category",
					"heading" => __ ( "Categories", 'slova' ),
					"param_name" => "category",
					"description" => __ ( "Note: By default, all your projects will be displayed. <br>If you want to narrow output, select category(s) above. Only selected categories will be displayed.", 'slova' )
			),
			array (
					"type" => "textfield",
					"heading" => __ ( 'Count', 'slova' ),
					"param_name" => "posts_per_page",
					'value' => '',
					"description" => __ ( 'The number of posts to display on each page. Set to "-1" for display all posts on the page.', 'slova' )
			),
			array (
					"type" => "dropdown",
					"heading" => __ ( 'Order by', 'slova' ),
					"param_name" => "orderby",
					"value" => array (
							"None" => "none",
							"Title" => "title",
							"Date" => "date",
							"ID" => "ID"
					),
					"description" => __ ( 'Order by ("none", "title", "date", "ID").', 'slova' )
			),
			array (
					"type" => "dropdown",
					"heading" => __ ( 'Order', 'slova' ),
					"param_name" => "order",
					"value" => Array (
							"None" => "none",
							"ASC" => "ASC",
							"DESC" => "DESC"
					),
					"description" => __ ( 'Order ("None", "Asc", "Desc").', 'slova' )
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Extra Class", 'slova'),
				"param_name" => "el_class",
				"value" => "",
				"description" => __ ( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'slova' )
			),
			array(
				"type" => "checkbox",
				"class" => "",
				"heading" => __("Show Avatar", 'slova'),
				"param_name" => "show_avatar",
				"value" => array (
					__ ( "Yes, please", 'slova' ) => true
				),
				"group" => __("Template", 'slova'),
				"description" => __("Show or not avatar of post in this element.", 'slova')
			),
			array(
				"type" => "checkbox",
				"class" => "",
				"heading" => __("Show Excerpt", 'slova'),
				"param_name" => "show_excerpt",
				"value" => array (
					__ ( "Yes, please", 'slova' ) => true
				),
				"group" => __("Template", 'slova'),
				"description" => __("Show or not excerpt of post in this element.", 'slova')
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Excerpt Lenght", 'slova'),
				"param_name" => "excerpt_lenght",
				"value" => "",
				"group" => __("Template", 'slova'),
				"description" => __("Please, Enter excerpt lenght in this element. EX: 20", 'slova')
			),
			array(
				"type" => "textfield",
				"class" => "",
				"heading" => __("Excerpt More", 'slova'),
				"param_name" => "excerpt_more",
				"value" => "",
				"group" => __("Template", 'slova'),
				"description" => __("Please, Enter excerpt more in this element. EX: ...", 'slova')
			),
			array(
				"type" => "checkbox",
				"class" => "",
				"heading" => __("Show Info", 'slova'),
				"param_name" => "show_info",
				"value" => array (
					__ ( "Yes, please", 'slova' ) => true
				),
				"group" => __("Template", 'slova'),
				"description" => __("Show or not excerpt of post in this element.", 'slova')
			),
		)
));