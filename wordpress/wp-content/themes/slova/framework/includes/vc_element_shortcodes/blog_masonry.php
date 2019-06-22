<?php
vc_map ( array (
		"name" => 'Blog Masonry',
		"base" => "blog_masonry",
		"icon" => "tb-icon-for-vc",
		"category" => __ ( 'Slova', 'slova' ), 
		'admin_enqueue_js' => array(URI_PATH_FR.'/admin/assets/js/customvc.js'),
		"params" => array (
					array (
							"type" => "tb_taxonomy",
							"taxonomy" => "category",
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
					array(
						"type" => "checkbox",
						"class" => "",
						"heading" => __("Show Pagination", 'slova'),
						"param_name" => "show_pagination",
						"value" => array (
							__ ( "Yes, please", 'slova' ) => true
						),
						"description" => __("Show or not show pagination in this element.", 'slova')
					),
					array(
							"type" => "dropdown",
							"class" => "",
							"heading" => __("Columns", 'slova'),
							"param_name" => "columns",
							"value" => array(
								"3 Columns" => "3",
								"2 Columns" => "2",
								"1 Column" => "1",
							),
							"description" => __('Select columns display in this element.', 'slova')
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
						"heading" => __("Show Ttile", 'slova'),
						"param_name" => "show_title",
						"value" => array (
							__ ( "Yes, please", 'slova' ) => true
						),
						"group" => __("Template", 'slova'),
						"description" => __("Show or not title of post in this element.", 'slova')
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
						"heading" => __("Show Meta", 'slova'),
						"param_name" => "show_meta",
						"value" => array (
							__ ( "Yes, please", 'slova' ) => true
						),
						"group" => __("Template", 'slova'),
						"description" => __("Show or not meta of post in this element.", 'slova')
					),
		)
));