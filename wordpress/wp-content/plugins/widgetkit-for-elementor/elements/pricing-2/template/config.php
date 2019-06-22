<?php


use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Utils;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor WidgetKit Pricing 2
 *
 * Elementor widget for WidgetKit pricing 2
 *
 * @since 1.0.0
 */

class wkfe_pricing_2 extends Widget_Base {

	public function get_name() {
		return 'widgetkit-for-elementor-pricing-2';
	}

	public function get_title() {
		return esc_html__( 'Pricing Icon', 'widgetkit-for-elementor' );
	}

	public function get_icon() {
		return 'eicon-price-table';
	}

	public function get_categories() {
		return [ 'widgetkit_elementor' ];
	}

	/**
	 * A list of scripts that the widgets is depended in
	 * @since 1.3.0
	 **/
	public function get_script_depends() {
		return [ 'widgetkit-for-elementor-pricing-2' ];
	}

	protected function _register_controls() {


	$this->start_controls_section(
		'section_content_header',
		[
			'label' => esc_html__( 'Pricing', 'widgetkit-for-elementor' ),
		]
	);

		$this->add_control(
			'single_currency_symbol',
			[
				'label'   => esc_html__( 'Currency Symbol', 'widgetkit-for-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'None', 'widgetkit-for-elementor' ),
					'&#36;'   => '&#36; ' . _x( 'Dollar', 'Currency Symbol', 'widgetkit-for-elementor' ),
					'&#128;'  => '&#128; ' . _x( 'Euro', 'Currency Symbol', 'widgetkit-for-elementor' ),
					'&#3647;' => '&#3647; ' . _x( 'Baht', 'Currency Symbol', 'widgetkit-for-elementor' ),
					'&#8355;' => '&#8355; ' . _x( 'Franc', 'Currency Symbol', 'widgetkit-for-elementor' ),
					'&fnof;'  => '&fnof; ' . _x( 'Guilder', 'Currency Symbol', 'widgetkit-for-elementor' ),
					'kr;'     => 'kr ' . _x( 'Krona', 'Currency Symbol', 'widgetkit-for-elementor' ),
					'&#8356;' => '&#8356; ' . _x( 'Lira', 'Currency Symbol', 'widgetkit-for-elementor' ),
					'&#8359;' => '&#8359 ' . _x( 'Peseta', 'Currency Symbol', 'widgetkit-for-elementor' ),
					'&#8369;' => '&#8369; ' . _x( 'Peso', 'Currency Symbol', 'widgetkit-for-elementor' ),
					'&#163;'  => '&#163; ' . _x( 'Pound Sterling', 'Currency Symbol', 'widgetkit-for-elementor' ),
					'R$;'     => 'R$ ' . _x( 'Real', 'Currency Symbol', 'widgetkit-for-elementor' ),
					'&#8381;' => '&#8381; ' . _x( 'Ruble', 'Currency Symbol', 'widgetkit-for-elementor' ),
					'&#8360;' => '&#8360; ' . _x( 'Rupee', 'Currency Symbol', 'widgetkit-for-elementor' ),
					'&#8377;' => '&#8377; ' . _x( 'Rupee (Indian)', 'Currency Symbol', 'widgetkit-for-elementor' ),
					'&#8362;' => '&#8362; ' . _x( 'Shekel', 'Currency Symbol', 'widgetkit-for-elementor' ),
					'&#165;'  => '&#165; ' . _x( 'Yen/Yuan', 'Currency Symbol', 'widgetkit-for-elementor' ),
					'&#8361;' => '&#8361; ' . _x( 'Won', 'Currency Symbol', 'widgetkit-for-elementor' ),
				],
				'default' => '&#36;',
			]
		);

		$this->add_control(
			'pricing_2_price',
			[
				'label'   => esc_html__( 'Price', 'widgetkit-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '290',
			]
		);

		$this->add_control(
			'pricing_2_period',
			[
				'label' => esc_html__( 'Period', 'widgetkit-for-elementor' ),
				'type'  => Controls_Manager::TEXT,
				'default' => esc_html__( '/ mo', 'widgetkit-for-elementor' ),
			]
		);

		$this->end_controls_section();


	$this->start_controls_section(
		'section_content_2_image',
		[
			'label' => esc_html__( 'Icon Image', 'widgetkit-for-elementor' ),
		]
	);

		$this->add_control(
			'pricing_2_icon_image',
			[
				'label' => esc_html__( 'Icon Image', 'widgetkit-for-elementor' ),
				'type'  => Controls_Manager::MEDIA,
				'default' => [
						'url' => plugins_url('/widgetkit-for-elementor/assets/images/pricing-2.png'),
				],
			]
		);
	$this->end_controls_section();


	$this->start_controls_section(
		'section_about_2',
		[
			'label' => esc_html__( 'About', 'widgetkit-for-elementor' ),
		]
	);


		$this->add_control(
			'pricing_2_pricing_title',
			[
				'label' => esc_html__( 'Title', 'widgetkit-for-elementor' ),
				'type'  => Controls_Manager::TEXT,
				'default' => esc_html__( 'Basic Plan', 'widgetkit-for-elementor' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'pricing_2_pricing_about',
			[
				'label' => esc_html__( 'About', 'widgetkit-for-elementor' ),
				'type'  => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'I think your best bet would be to start or join a startup', 'widgetkit-for-elementor' ),
				'label_block' => true,
			]
		);


	$this->end_controls_section();

	$this->start_controls_section(
		'section_feature_2',
		[
			'label' => esc_html__( 'Features', 'widgetkit-for-elementor' ),
		]
	);


        $repeater = new Repeater();

        $repeater->add_control(
            'item_text_2',
            [
                'label'   => esc_html__( 'Text', 'widgetkit-for-elementor' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'List Item', 'widgetkit-for-elementor' ),
            ]
        );

        $repeater->add_control(
            'item_icon_2',
            [
                'label'   => esc_html__( 'Icon', 'widgetkit-for-elementor' ),
                'type'    => Controls_Manager::ICON,
                'default' => 'fa fa-angellist',
            ]
        );

        $repeater->add_control(
            'item_icon_color_2',
            [
                'label'     => esc_html__( 'Icon Color', 'widgetkit-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}} i' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'features_list_2',
            [
                'type'    => Controls_Manager::REPEATER,
                'fields'  => array_values( $repeater->get_controls() ),
                'default' => [
                    [
                        'item_text_2' => __( '<b>1</b> Website', 'widgetkit-for-elementor' ),
                        'item_icon_2' => 'fa fa-angellist',
                    ],
                    [
                        'item_text_2' => __( '<b>10GB</b> Web Space', 'widgetkit-for-elementor' ),
                        'item_icon_2' => 'fa fa-angellist',
                    ],
                    [
                        'item_text_2' => __( 'Suitable for ~ <b>10,000</b> Visits Monthly', 'widgetkit-for-elementor' ),
                        'item_icon_2' => 'fa fa-angellist',
                    ],
                    [
                        'item_text_2' => __( '<b>90GB</b> SSD Space', 'widgetkit-for-elementor' ),
                        'item_icon_2' => 'fa fa-angellist',
                    ],
                    [
                        'item_text_2' => __( '<b>10TB</b> Data Transfer', 'widgetkit-for-elementor' ),
                        'item_icon_2' => 'fa fa-angellist',
                    ],
                ],
                'title_field' => '{{{ item_text_2 }}}',
            ]
        );

    $this->end_controls_section();

 

    $this->start_controls_section(
		'section_button_2',
		[
			'label' => esc_html__( 'Button', 'widgetkit-for-elementor' ),
		]
	);

     	$this->add_control(
            'pricing_2_price_button',
            [
                'label' => esc_html__( 'Button Options', 'widgetkit-for-elementor' ),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

		$this->add_control(
			'single_button_text',
			[
				'label' => esc_html__( 'Button Text', 'widgetkit-for-elementor' ),
				'type'  => Controls_Manager::TEXT,
				'default' => esc_html__( 'Get Started Now', 'widgetkit-for-elementor' ),
			]
		);

		$this->add_control(
			'single_link',
			[
				'label' => esc_html__( 'Link', 'widgetkit-for-elementor' ),
				'type'  => Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://themesgrove.com', 'widgetkit-for-elementor' ),
			]
		);
	$this->end_controls_section();




	$this->start_controls_section(
        'section_pricing_style',
            [
                'label' => esc_html__( 'Pricing', 'widgetkit-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

	    	$this->add_control(
				'single_price_color',
				[
					'label' => esc_html__( 'Price Color', 'widgetkit-for-elementor' ),
					'type'  => Controls_Manager::COLOR,
					'default'   => '#fff',
					'selectors' => [
						'{{WRAPPER}} .tgx-single-pricing .tgx-single-heading .price' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
	            Group_Control_Typography::get_type(),
	            [
	                'name'     => 'global_pricing_typography',
	                'label'    => esc_html__( 'Typography', 'widgetkit-for-elementor' ),
	                'selector' => '{{WRAPPER}} .tgx-single-pricing .tgx-single-heading .price .amount',
	            ]
	        );

	        $this->add_control(
			'single_header_bg_color',
			[
				'label' => esc_html__( 'Price Bg Color', 'widgetkit-for-elementor' ),
				'type'  => Controls_Manager::COLOR,
				'default'   => '#ed485f',
				'selectors' => [
					'{{WRAPPER}} .tgx-single-pricing .tgx-single-heading .price' => 'background-color: {{VALUE}};',
				],

			]
		);

	    $this->add_control(
			'header_currency_period_size',
			[
				'label' => esc_html__( 'Currency & Period Font Size', 'widgetkit-for-elementor' ),
				'type'  => Controls_Manager::SLIDER,
				'default'  => [
					'size' =>16,
				],
				'range'  => [
					'px' => [
						'min' => 12,
						'max' => 30,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tgx-single-pricing .tgx-single-heading .price .curency,
					{{WRAPPER}} .tgx-single-pricing .tgx-single-heading .price .period' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

	$this->end_controls_section();

	$this->start_controls_section(
        'section_image_style',
            [
                'label' => esc_html__( 'Icon Image', 'widgetkit-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

	
	    $this->add_control(
            'pricing_2_imge_alignment',
            [
                'label' => esc_html__( 'Alignment', 'widgetkit-for-elementor' ),
                'type'  => Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => esc_html__( 'center', 'widgetkit-for-elementor' ),
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'widgetkit-for-elementor' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'widgetkit-for-elementor' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'widgetkit-for-elementor' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}}  .tgx-single-pricing .tgx-single-image' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'image_margin',
            [
                'label' => esc_html__( 'Margin', 'widgetkit-for-elementor' ),
                'type'  => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .tgx-single-pricing .tgx-single-image' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

    $this->end_controls_section();

    $this->start_controls_section(
        'section_about_style',
            [
                'label' => esc_html__( 'About', 'widgetkit-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );

        $this->add_control(
            'heading_title_style',
            [
                'label' => esc_html__( 'Title', 'widgetkit-for-elementor' ),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
			'single_heading_title_color',
			[
				'label' => esc_html__( 'Color', 'widgetkit-for-elementor' ),
				'type'  => Controls_Manager::COLOR,
				'default'   => '#ed485f',
				'selectors' => [
					'{{WRAPPER}} .tgx-single-pricing .tgx-single-about .tgx-single-title' => 'color: {{VALUE}};',
				],

			]
		);
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'global_title_typography',
                'label'    => esc_html__( 'Typography', 'widgetkit-for-elementor' ),
                'selector' => '{{WRAPPER}} .tgx-single-pricing .tgx-single-about .tgx-single-title',
            ]
        );

        $this->add_control(
            'heading_about_style',
            [
                'label' => esc_html__( 'About', 'widgetkit-for-elementor' ),
                'type'  => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
			'heading_about_color',
			[
				'label' => esc_html__( 'About Color', 'widgetkit-for-elementor' ),
				'type'  => Controls_Manager::COLOR,
				'default'   => '#444',
				'selectors' => [
					' {{WRAPPER}} .tgx-single-pricing .tgx-single-about .tgx-single-about' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'  => 'global_about_typography',
                'label' => esc_html__( 'Typography', 'widgetkit-for-elementor' ),
                'selector' => '{{WRAPPER}} .tgx-single-pricing .tgx-single-about .tgx-single-about',
            ]
        );

	$this->end_controls_section();

	$this->start_controls_section(
        'section_feature_style_2',
            [
                'label' => esc_html__( 'Features', 'widgetkit-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );


        $this->add_control(
			'feature_item_color',
			[
				'label' => esc_html__( 'Color', 'widgetkit-for-elementor' ),
				'type'  => Controls_Manager::COLOR,
				'default'   => '#444',
				'selectors' => [
					' {{WRAPPER}} .tgx-single-pricing .tgx-feature-item-2' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'feature_item_heighlight_color',
			[
				'label' => esc_html__( 'Heighlight Color', 'widgetkit-for-elementor' ),
				'type'  => Controls_Manager::COLOR,
				'default'   => '#4a6385',
				'selectors' => [
					' {{WRAPPER}} .tgx-single-pricing .tgx-feature-item-2 b' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'  => 'global_feature_typography',
                'label' => esc_html__( 'Typography', 'widgetkit-for-elementor' ),
                'selector' => '{{WRAPPER}} .tgx-single-pricing .tgx-feature-item-2',
            ]
        );

        $this->add_responsive_control(
            'feature_list_margin_2',
            [
                'label' => esc_html__( 'Margin', 'widgetkit-for-elementor' ),
                'type'  => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%', 'em' ],
                'selectors'  => [
                    '{{WRAPPER}} .tgx-single-pricing .tgx-single-features-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
			'feature_2_item_padding',
			[
				'label' => esc_html__( 'Item Gap', 'widgetkit-for-elementor' ),
				'type'  => Controls_Manager::SLIDER,
				'default'  => [
					'size' =>5,
				],
				'range'  => [
					'px' => [
						'min' => 1,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .tgx-single-pricing .tgx-feature-item-2' => 'padding: {{SIZE}}{{UNIT}} 0;',
				],
			]
		);

	$this->end_controls_section();

	$this->start_controls_section(
            'section_button_style_2',
            [
                'label' => esc_html__( 'Button', 'widgetkit-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'show_label' => false,
            ]
        );


        $this->start_controls_tabs( 'tabs_button_2_style' );

        $this->start_controls_tab(
            'tab_button_2_normal',
            [
                'label' => esc_html__( 'Normal', 'widgetkit-for-elementor' ),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'  => 'button_2_typography',
                'label' => esc_html__( 'Typography', 'widgetkit-for-elementor' ),
                'selector' => '{{WRAPPER}} .tgx-single-pricing .tgx-single-footer .tgx-single-btn',
            ]
        );

        $this->add_control(
            'button_2_text_color',
            [
                'label' => esc_html__( 'Text Color', 'widgetkit-for-elementor' ),
                'type'  => Controls_Manager::COLOR,
                'default'   => '#fff',
                'selectors' => [
                    '{{WRAPPER}} .tgx-single-pricing .tgx-single-footer .tgx-single-btn' => 'color: {{VALUE}};',
                ],
            ]
        );



        $this->add_control(
            'button_2_background_color',
            [
                'label' => esc_html__( 'Background Color', 'widgetkit-for-elementor' ),
                'type'  => Controls_Manager::COLOR,
                'default'   => '#ed485f',
                'selectors' => [
                    '{{WRAPPER}} .tgx-single-pricing .tgx-single-footer .tgx-single-btn' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(), [
                'name'  => 'button_2_border',
                'label' => esc_html__( 'Border', 'widgetkit-for-elementor' ),
                'placeholder' => '1px',
                'default'  => '1px',
                'selector' => '{{WRAPPER}} .tgx-single-pricing .tgx-single-footer .tgx-single-btn',
            ]
        );

        $this->add_control(
            'button_2_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'widgetkit-for-elementor' ),
                'type'  => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .tgx-single-pricing .tgx-single-footer .tgx-single-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'button_2_text_padding',
            [
                'label' => esc_html__( 'Text Padding', 'widgetkit-for-elementor' ),
                'type'  => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors'  => [
                    '{{WRAPPER}} .tgx-single-pricing .tgx-single-footer .tgx-single-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab();

      	$this->start_controls_tab(
            'tab_button_2_hover',
            [
                'label' => esc_html__( 'Hover', 'widgetkit-for-elementor' ),
            ]
        );

	        $this->add_control(
	            'button_2_hover_color',
	            [
	                'label' => esc_html__( 'Text Color', 'widgetkit-for-elementor' ),
	                'type'  => Controls_Manager::COLOR,
	                'default'   => '#ed485f',
	                'selectors' => [
	                    '{{WRAPPER}} .tgx-single-pricing .tgx-single-footer .tgx-single-btn:hover' => 'color: {{VALUE}};',
	                ],
	            ]
	        );

	        $this->add_control(
	            'button_2_background_hover_color',
	            [
	                'label' => esc_html__( 'Background Color', 'widgetkit-for-elementor' ),
	                'type'  => Controls_Manager::COLOR,
	                'default'   => '#fff',
	                'selectors' => [
	                    '{{WRAPPER}} .tgx-single-pricing .tgx-single-footer .tgx-single-btn:hover' => 'background-color: {{VALUE}};',
	                ],
	            ]
	        );

	        $this->add_control(
	            'button_2_hover_border_color',
	            [
	                'label' => esc_html__( 'Border Color', 'widgetkit-for-elementor' ),
	                'type'  => Controls_Manager::COLOR,
	                'default'   => '#ed485f',
	                'selectors' => [
	                    '{{WRAPPER}} .tgx-single-pricing .tgx-single-footer .tgx-single-btn:hover' => 'border-color:{{VALUE}};',
	                ],
	            ]
	        );

 		$this->end_controls_tab();

        $this->end_controls_tabs();

    $this->end_controls_section();


	} // End Tab section

	protected function render() {
		require WKFE_PATH . '/elements/pricing-2/template/view.php';
	}


}
