<?php
vc_map ( array (
		"name" => 'Video Slider',
		"base" => "video_slider",
		"icon" => "tb-icon-for-vc",
		"category" => __ ( 'Slova', 'slova' ), 
		'admin_enqueue_js' => array(URI_PATH_FR.'/admin/assets/js/customvc.js'),
		"params" => array (
					array (
							"type" => "tb_taxonomy",
							"taxonomy" => "video_category",
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
						"type" => "attach_image",
						"class" => "",
						"heading" => __("Play Video", 'slova'),
						"param_name" => "play_video",
						"value" => "",
						"description" => __ ( "Please, Select play video image.", 'slova' )
					),
		)
));