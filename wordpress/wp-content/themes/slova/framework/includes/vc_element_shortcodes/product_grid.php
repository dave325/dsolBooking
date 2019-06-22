<?php
if(class_exists('Woocommerce')){
    vc_map(array(
        "name" => __("Product Grid", 'slova'),
        "base" => "products_grid",
        "class" => "ro-products-grid",
        "category" => __('Slova', 'slova'),
        'admin_enqueue_js' => array(URI_PATH_ADMIN.'assets/js/customvc.js'),
        "icon" => "tb-icon-for-vc",
        "params" => array(
			array(
                "type" => "dropdown",
                "class" => "",
                "heading" => __("Columns", 'slova'),
                "param_name" => "columns",
                "value" => array(
                    "4 Columns" => "4",
                    "3 Columns" => "3",
                    "2 Columns" => "2",
                    "1 Column" => "1",
                ),
				"description" => __('Select columns in this elment.', 'slova')
            ),
			array(
                "type" => "checkbox",
                "heading" => __('Show Pagination', 'slova'),
                "param_name" => "show_pagination",
                "value" => array(
                    __("Yes, please", 'slova') => 1
                ),
                "description" => __('Show or hide pagination in this element.', 'slova')
            ),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __("Extra Class", 'slova'),
                "param_name" => "el_class",
                "value" => "",
				"description" => __ ( "If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.", 'slova' )
            ),
			array (
                "type" => "tb_taxonomy",
                "taxonomy" => "product_cat",
                "heading" => __ ( "Categories", 'slova' ),
                "param_name" => "product_cat",
                "class" => "",
				"group" => __("Build Query", 'slova'),
                "description" => __ ( "Note: By default, all your projects will be displayed. <br>If you want to narrow output, select category(s) above. Only selected categories will be displayed.", 'slova' )
            ),
			array (
					"type" => "dropdown",
					"class" => "",
					"heading" => __ ( "Show", 'slova' ),
					"param_name" => "show",
					"value" => array (
							"All Products" => "all_products",
							"Featured Products" => "featured",
							"On-sale Products" => "onsale",
					),
					"group" => __("Build Query", 'slova'),
					"description" => __ ( "Select show product type in this elment", 'slova' )
			),
            array(
                "type" => "textfield",
                "class" => "",
                "heading" => __("Product Count", 'slova'),
                "param_name" => "number",
                "value" => "",
				"group" => __("Build Query", 'slova'),
				"description" => __('Please, enter number of post per page. Show all: -1.', 'slova')
            ),
			array(
                "type" => "checkbox",
                "heading" => __('Hide Free', 'slova'),
                "param_name" => "hide_free",
                "value" => array(
                    __("Yes, please", 'slova') => 1
                ),
				"group" => __("Build Query", 'slova'),
                "description" => __('Hide free product in this element.', 'slova')
            ),
			array(
                "type" => "checkbox",
                "heading" => __('Show Hidden', 'slova'),
                "param_name" => "show_hidden",
                "value" => array(
                    __("Yes, please", 'slova') => 1
                ),
				"group" => __("Build Query", 'slova'),
                "description" => __('Show Hidden product in this element.', 'slova')
            ),
            array (
				"type" => "dropdown",
				"heading" => __ ( 'Order by', 'slova' ),
				"param_name" => "orderby",
				"value" => array (
						"None" => "none",
						"Date" => "date",
						"Price" => "price",
						"Random" => "rand",
						"Selling" => "selling",
						"Rated" => "rated",
				),
				"group" => __("Build Query", 'slova'),
				"description" => __ ( 'Order by ("none", "date", "price", "rand", "selling", "rated") in this element.', 'slova' )
			),
            array(
                "type" => "dropdown",
                "heading" => __('Order', 'slova'),
                "param_name" => "order",
                "value" => Array(
                    "None" => "none",
                    "ASC" => "ASC",
                    "DESC" => "DESC"
                ),
				"group" => __("Build Query", 'slova'),
                "description" => __('Order ("None", "Asc", "Desc") in this element.', 'slova')
            ),
            array(
                "type" => "checkbox",
                "heading" => __('Show Sale Flash', 'slova'),
                "param_name" => "show_sale_flash",
                "value" => array(
                    __("Yes, please", 'slova') => 1
                ),
				"group" => __("Template", 'slova'),
                "description" => __('Show or hide sale flash of product.', 'slova')
            ),
			array(
                "type" => "checkbox",
                "heading" => __('Show Title', 'slova'),
                "param_name" => "show_title",
                "value" => array(
                    __("Yes, please", 'slova') => 1
                ),
				"group" => __("Template", 'slova'),
                "description" => __('Show or hide title of product.', 'slova')
            ),
			array(
                "type" => "checkbox",
                "heading" => __('Show Price', 'slova'),
                "param_name" => "show_price",
                "value" => array(
                    __("Yes, please", 'slova') => 1
                ),
				"group" => __("Template", 'slova'),
                "description" => __('Show or hide price of product.', 'slova')
            ),
			array(
                "type" => "checkbox",
                "heading" => __('Show Rating', 'slova'),
                "param_name" => "show_rating",
                "value" => array(
                    __("Yes, please", 'slova') => 1
                ),
				"group" => __("Template", 'slova'),
                "description" => __('Show or hide rating of product.', 'slova')
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
                "heading" => __('Show Add To Cart', 'slova'),
                "param_name" => "show_add_to_cart",
                "value" => array(
                    __("Yes, please", 'slova') => 1
                ),
				"group" => __("Template", 'slova'),
                "description" => __('Show or hide add to cart of product.', 'slova')
            ),
			/*array(
                "type" => "checkbox",
                "heading" => __('Show Like Button', 'slova'),
                "param_name" => "show_like_button",
                "value" => array(
                    __("Yes, please", 'slova') => 1
                ),
				"group" => __("Template", 'slova'),
                "description" => __('Show or hide like button of product.', 'slova')
            ),*/
			array(
                "type" => "checkbox",
                "heading" => __('Show Wishlist Button', 'slova'),
                "param_name" => "show_wishlist_button",
                "value" => array(
                    __("Yes, please", 'slova') => 1
                ),
				"group" => __("Template", 'slova'),
                "description" => __('Show or hide wishlist button of product.', 'slova')
            ),
        )
    ));
}
