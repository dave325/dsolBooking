<?php

use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor WidgetKit carousel
 *
 * Elementor widget for WidgetKit carousel
 *
 * @since 1.0.0
 */
class wkfe_carousel extends Widget_Base {

	public function get_name() {
		return 'widgetkit-for-elementor-carousel';
	}

	public function get_title() {
		return esc_html__( 'Post Carousel', 'widgetkit-for-elementor' );
	}

	public function get_icon() {
		return 'eicon-slider-push';
	}

	public function get_categories() {
		return [ 'widgetkit_elementor' ];
	}

	/**
	 * A list of scripts that the widgets is depended in
	 * @since 1.3.0
	 **/
	public function get_script_depends() {
		return [ 'owl-carousel' ];
	}

	protected function _register_controls() {

	$this->start_controls_section(
		'section_content',
		[
			'label' => esc_html__( 'Carousel Content', 'widgetkit-for-elementor' ),
		]
	);

	$this->add_control(
		'item_option',
			[
				'label'     => esc_html__( 'Item Options', 'widgetkit-for-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'custom_post',
				'options'   => [
					'custom_post'    => esc_html__( 'Custom Post', 'widgetkit-for-elementor' ),
					'standard_post'  => esc_html__( 'Standard Post', 'widgetkit-for-elementor' ),
					'sticky_post'    => esc_html__( 'Sticky Post', 'widgetkit-for-elementor' ),
				],
			]
		);


	$repeater = new Repeater();
	    $repeater->add_control(
		    'project_title',
		      	[
		          'label'   => esc_html__( 'Project Title', 'widgetkit-for-elementor' ),
		          'type'    => Controls_Manager::TEXTAREA,
		          'default' => esc_html__( 'Switch Pro', 'widgetkit-for-elementor' ),
		    	]
	    );




		$repeater->add_control(
	       'project_thumb_image',
		        [
		          'label' => esc_html__( 'Upload Thumb Image', 'widgetkit-for-elementor' ),
		          'type'  => Controls_Manager::MEDIA,
		          'default' => [
					'url'   => plugins_url('/widgetkit-for-elementor/assets/images/portfolio-demo.jpg'),
				  ],
		        ]
	    );


		$repeater->add_control(
			'project_demo_link',
			[
				'label'   => esc_html__( 'Demo Link', 'widgetkit-for-elementor' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'https://themesgrove.com/product/switch-pro/', 'widgetkit-for-elementor' ),
			]
		);



		$this->add_control(
		    'custom_content',
		      [
		          'label'       => esc_html__( 'Custom Items', 'widgetkit-for-elementor' ),
		          'type'        => Controls_Manager::REPEATER,
		          'show_label'  => true,
		          'default'     => [
		              [
		                'project_title' => esc_html__( 'Switch Pro', 'widgetkit-for-elementor' ),
		                'project_thumb_image' => '',
		                'project_demo_link'   => 'https://themesgrove.com/product/switch-pro/',
		 
		              ],
		              [
		                'project_title' => esc_html__( 'Exploore', 'widgetkit-for-elementor' ),
		                'project_thumb_image' => '',
		                'project_demo_link'   => 'https://themesgrove.com/product/exploore/',
		 
		              ],
		                [
		                'project_title' => esc_html__( 'Universidad', 'widgetkit-for-elementor' ),
		                'project_thumb_image' => '',
		                'project_demo_link'   => 'https://themesgrove.com/product/universidad/',
		 
		              ],
		          ],
		          'fields'      => array_values( $repeater->get_controls() ),
		          'title_field' => '{{{project_title}}}',
		          'condition'   => [
		                'item_option' => 'custom_post',
		            ],
		      ]
		  );

		$this->add_control(
			'sticky_post_show',
				[
					'label'     => esc_html__( 'Show Post', 'widgetkit-for-elementor' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => '3',
					'options'   => [
						'1'    => esc_html__( 'Show 1', 'widgetkit-for-elementor' ),
						'2'    => esc_html__( 'Show 2', 'widgetkit-for-elementor' ),
						'3'    => esc_html__( 'Show 3', 'widgetkit-for-elementor' ),
						'4'    => esc_html__( 'Show 4', 'widgetkit-for-elementor' ),
						'-1'   => esc_html__( 'Show Unlimited', 'widgetkit-for-elementor' ),
					],

					'condition' => [
		                'item_option' => 'sticky_post',
		            ],
				]
		);


		$this->add_control(
			'standard_post_show',
				[
					'label'     => esc_html__( 'Show Post', 'widgetkit-for-elementor' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => '3',
					'options'   => [
						'1'    => esc_html__( 'Show 1', 'widgetkit-for-elementor' ),
						'2'    => esc_html__( 'Show 2', 'widgetkit-for-elementor' ),
						'3'    => esc_html__( 'Show 3', 'widgetkit-for-elementor' ),
						'4'    => esc_html__( 'Show 4', 'widgetkit-for-elementor' ),
						'-1'   => esc_html__( 'Show Unlimited', 'widgetkit-for-elementor' ),
					],

					'condition' => [
		                'item_option' => 'standard_post',
		            ],
				]
		);

	$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Ttile Style', 'widgetkit-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);



		$this->add_control(
			'title_color',
			[
				'label'     => esc_html__( 'Title Color', 'widgetkit-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#444',
				'selectors' => [
					'{{WRAPPER}} .tgx-project .project-wrap .title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
				[
					'name'     => 'title_typography',
					'label'    => esc_html__( 'Title Typography', 'widgetkit-for-elementor' ),
					'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} .tgx-project .project-wrap .title',
				]
		);

		$this->add_control(
			'title_hover_color',
			[
				'label'     => esc_html__( 'Title Hover Color', 'widgetkit-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ed485f',
				'selectors' => [
					'{{WRAPPER}} .tgx-project .project-wrap .title a:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'title_spacing',
				[
					'label'   => esc_html__( 'Title Specing', 'widgetkit-for-elementor' ),
					'type'    => Controls_Manager::SLIDER,
					'default' => [
					'size' =>20,
					],
					'range'  => [
						'px' => [
							'min' => -10,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .tgx-project .project-wrap .title' => 'margin: {{SIZE}}{{UNIT}} 0 20px;',
					],
				]
			);

		$this->add_control(
			'overlay_color',
			[
				'label'     => esc_html__( 'Item Overlay Color', 'widgetkit-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => 'rgba(0,0,0,0.54)',
				'selectors' => [
					'{{WRAPPER}} .tgx-project .project-image:before' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		// Button options Start
		$this->start_controls_section(
			'button_style',
				[
					'label' => esc_html__( 'Nav Style', 'widgetkit-for-elementor' ),
					'tab'   => Controls_Manager::TAB_STYLE,
				]
		);



       $this->add_responsive_control(
			'btn_text_size',
				[
					'label'  => esc_html__( 'Icon Size', 'widgetkit-for-elementor' ),
					'type'   => Controls_Manager::SLIDER,
					'default' => [
						'size' =>20,
					],
					'range'  => [
						'px' => [
							'min' => 16,
							'max' => 24,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .tgx-project .owl-carousel-left i, 
						{{WRAPPER}} .tgx-project .owl-carousel-right i' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);


	$this->start_controls_tabs( 'tabs_button_style' );

    $this->start_controls_tab(
        'tab_button_normal',
          [
            'label' => esc_html__( 'Normal', 'widgetkit-for-elementor' ),
          ]
    );

    $this->add_control(
        'button_text_color',
          [
            'label' => esc_html__( 'Icon Color', 'widgetkit-for-elementor' ),
            'type'  => Controls_Manager::COLOR,
            'default'   => '#fff',
            'selectors' => [
              '{{WRAPPER}} .tgx-project .owl-carousel-left i, 
               {{WRAPPER}} .tgx-project .owl-carousel-right i' => 'color: {{VALUE}};',
            ],
          ]
    );

    $this->add_control(
        'background_color',
	        [
	            'label' => esc_html__( 'Background Color', 'widgetkit-for-elementor' ),
	            'type'  => Controls_Manager::COLOR,
	            'default'   => 'transparent',
	            'selectors' => [
	              '{{WRAPPER}} .tgx-project .owl-carousel-left, 
	               {{WRAPPER}} .tgx-project .owl-carousel-right' => 'background-color: {{VALUE}};',
	            ],
	        ]
    );


    	$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'  => 'project_border',
				'label' => esc_html__( 'Border', 'widgetkit-for-elementor' ),
				'placeholder' => '1px',
				'default'  => '1px',
				'selector' => '
				    {{WRAPPER}} .tgx-project .owl-carousel-left, 
					{{WRAPPER}} .tgx-project .owl-carousel-right',
				'separator' => 'before',
			]
		);

		$this->add_control(
			'project_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'widgetkit-for-elementor' ),
				'type'  => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .tgx-project .owl-carousel-left, 
					{{WRAPPER}} .tgx-project .owl-carousel-right' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);



    $this->end_controls_tab();

    $this->start_controls_tab(
        'tab_button_hover',
	        [
	            'label' => esc_html__( 'Hover', 'widgetkit-for-elementor' ),
	        ]
    );

    $this->add_control(
        'icon_hover_color',
		    [
		        'label' => esc_html__( 'Icon Color', 'widgetkit-for-elementor' ),
		        'type'  => Controls_Manager::COLOR,
		        'default'   => ' #fff',
		        'selectors' => [
		          '{{WRAPPER}} .tgx-project .owl-carousel-left:hover i, 
		          {{WRAPPER}} .tgx-project .owl-carousel-right:hover i' => 'color: {{VALUE}};',
		        ],
		    ]
    );

    $this->add_control(
        'button_background_hover_color',
		    [
		        'label' => esc_html__( 'Background Color', 'widgetkit-for-elementor' ),
		        'type'  => Controls_Manager::COLOR,
		        'default'   => ' #ed485f',
		        'selectors' => [
		          '{{WRAPPER}} .tgx-project .owl-carousel-left:hover, 
		          {{WRAPPER}} .tgx-project .owl-carousel-right:hover' => 'background-color: {{VALUE}}; border-color: {{VALUE}};',
		        ],
		    ]
    );



    $this->end_controls_tab();


    $this->end_controls_tabs();

$this->end_controls_section();

// Button options End
	}

	protected function render() {
		require WKFE_PATH . '/elements/carousel/template/view.php';
	}


}
