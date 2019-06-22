<?php 
namespace Elementor;
if( !defined( 'ABSPATH' ) ) exit; // No access of directly access

class Premium_Carousel extends Widget_Base {

    protected $templateInstance;

	public function getTemplateInstance() {
		return $this->templateInstance = premium_Template_Tags::getInstance();
	}

	public function get_name() {
		return 'premium-carousel-widget';
	}

	public function get_title() {
		return \PremiumAddons\Helper_Functions::get_prefix() . ' Carousel';
	}

	public function get_icon() {
		return 'pa-carousel';
	}
    
    public function is_reload_preview_required()
    {
       return true;
    }

	public function get_script_depends() {
		return [
            'jquery-slick',
            'premium-addons-js',
        ];
	}

	public function get_categories() {
		return [ 'premium-elements' ];
	}

    
    // Adding the controls fields for the premium carousel
    // This will controls the animation, colors and background, dimensions etc
	protected function _register_controls() {
		$this->start_controls_section('premium_carousel_global_settings',
			[
				'label'         => __( 'Carousel' , 'premium-addons-for-elementor' )
			]
		);
        
        $this->add_control('premium_carousel_content_type',
			[
				'label'			=> __( 'Content Type', 'premium-addons-for-elementor' ),
				'description'	=> __( 'How templates are selected', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SELECT,
				'options'		=> [
					'select'        => __( 'Select Field', 'premium-addons-for-elementor' ),
					'repeater'		=> __( 'Repeater', 'premium-addons-for-elementor' )
				],
                'default'		=> 'select'
			]
		);

		$this->add_control('premium_carousel_slider_content',
		  	[
		     	'label'         => __( 'Templates', 'premium-addons-for-elementor' ),
		     	'description'	=> __( 'Slider content is a template which you can choose from Elementor library. Each template will be a slider content', 'premium-addons-for-elementor' ),
		     	'type'          => Controls_Manager::SELECT2,
		     	'options'       => $this->getTemplateInstance()->get_elementor_page_list(),
		     	'multiple'      => true,
                'condition'     => [
                    'premium_carousel_content_type' => 'select'
                ]
		  	]
		);
        
        $repeater = new REPEATER();
        
        $repeater->add_control('premium_carousel_repeater_item',
            [
                'label'         => __( 'Content', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SELECT2,
                'options'       => $this->getTemplateInstance()->get_elementor_page_list()
            ]
        );
        
        $this->add_control('premium_carousel_templates_repeater',
            [
                'label'         => __('Templates', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::REPEATER,
                'fields'        => array_values( $repeater->get_controls() ),
                'condition'     => [
                    'premium_carousel_content_type' => 'repeater'
                ],
                'title_field'   => 'Template: {{{ premium_carousel_repeater_item }}}'
            ]
        );


		$this->add_control('premium_carousel_slider_type',
			[
				'label'			=> __( 'Type', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Set a navigation type', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SELECT,
				'options'		=> [
					'horizontal'	=> __( 'Horizontal', 'premium-addons-for-elementor' ),
					'vertical'		=> __( 'Vertical', 'premium-addons-for-elementor' )
				],
                'default'		=> 'horizontal'
			]
		);
        
        $this->add_control('premium_carousel_dot_navigation_show',
			[
				'label'			=> __( 'Dots', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Enable or disable navigation dots', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
                'separator'     => 'before',
				'default'		=> 'yes'
			]
		);
        
        $this->add_control('premium_carousel_dot_position',
			[
				'label'			=> __( 'Position', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SELECT,
				'default'		=> 'below',
				'options'		=> [
					'below'         => __( 'Below Slides', 'premium-addons-for-elementor' ),
					'above'         => __( 'On Slides', 'premium-addons-for-elementor' )
				],
                'condition'     => [
                    'premium_carousel_dot_navigation_show'  => 'yes'
                ]
			]
		);
        
        $this->add_responsive_control('premium_carousel_dot_offset',
			[
				'label'             => __( 'Horizontal Offset', 'premium-addons-for-elementor' ),
				'type'              => Controls_Manager::SLIDER,
                'size_units'        => ['px', 'em', '%'],
                'selectors'         => [
                    '{{WRAPPER}} .premium-carousel-dots-above ul.slick-dots' => 'left: {{SIZE}}{{UNIT}}',
                ],
                'condition'     => [
                    'premium_carousel_dot_navigation_show'  => 'yes',
                    'premium_carousel_dot_position'         => 'above'
                ]
			]
		);
        
        $this->add_responsive_control('premium_carousel_dot_voffset',
			[
				'label'             => __( 'Vertical Offset', 'premium-addons-for-elementor' ),
				'type'              => Controls_Manager::SLIDER,
                'size_units'        => ['px', 'em', '%'],
                'default'           => [
                    'unit'  => '%',
                    'size'  => 50
                ],
                'selectors'         => [
                    '{{WRAPPER}} .premium-carousel-dots-above ul.slick-dots' => 'top: {{SIZE}}{{UNIT}}',
                ],
                'condition'     => [
                    'premium_carousel_dot_navigation_show'  => 'yes',
                    'premium_carousel_dot_position'         => 'above'
                ]
			]
		);
        
        $this->add_control('premium_carousel_navigation_show',
			[
				'label'			=> __( 'Arrows', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Enable or disable navigation arrows', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
                'separator'     => 'before',
				'default'		=> 'yes'
			]
		);

		$this->add_control('premium_carousel_slides_to_show',
			[
				'label'			=> __( 'Appearance', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SELECT,
				'default'		=> 'all',
                'separator'     => 'before',
				'options'		=> [
					'all'			=> __( 'All visible', 'premium-addons-for-elementor' ),
					'single'		=> __( 'One at a time', 'premium-addons-for-elementor' )
				]
			]
		);

		$this->add_control('premium_carousel_responsive_desktop',
			[
				'label'			=> __( 'Desktop Slides', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::NUMBER,
				'default'		=> 1
			]
		);

		$this->add_control('premium_carousel_responsive_tabs',
			[
				'label'			=> __( 'Tabs Slides', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::NUMBER,
				'default'		=> 1
			]
		);

		$this->add_control('premium_carousel_responsive_mobile',
			[
				'label'			=> __( 'Mobile Slides', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::NUMBER,
				'default'		=> 1
			]
		);

        $this->end_controls_section();
        
        $this->start_controls_section('premium_carousel_slides_settings',
			[
				'label' => __( 'Slides\' Settings' , 'premium-addons-for-elementor' )
			]
		);
        
		$this->add_control('premium_carousel_loop',
			[
				'label'			=> __( 'Infinite Loop', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
                'description'   => __( 'Restart the slider automatically as it passes the last slide', 'premium-addons-for-elementor' ),
				'default'		=> 'yes'
			]
		);

		$this->add_control('premium_carousel_fade',
			[
				'label'			=> __( 'Fade', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
                'description'   => __( 'Enable fade transition between slides', 'premium-addons-for-elementor' ),
                'condition'     => [
                    'premium_carousel_slider_type'      => 'horizontal',
                ]
			]
		);

		$this->add_control('premium_carousel_zoom',
			[
				'label'			=> __( 'Zoom Effect', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
				'condition'		=> [
					'premium_carousel_fade'	=> 'yes',
                    'premium_carousel_slider_type'      => 'horizontal',
				]
			]
		);

		$this->add_control('premium_carousel_speed',
			[
				'label'			=> __( 'Transition Speed', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Set a navigation speed value. The value will be counted in milliseconds (ms)', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::NUMBER,
				'default'		=> 300
			]
		);

		$this->add_control('premium_carousel_autoplay',
			[
				'label'			=> __( 'Autoplay Slides‏', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Slide will start automatically', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
				'default'		=> 'yes'
			]
		);

		$this->add_control('premium_carousel_autoplay_speed',
			[
				'label'			=> __( 'Autoplay Speed', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Autoplay Speed means at which time the next slide should come. Set a value in milliseconds (ms)', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::NUMBER,
				'default'		=> 5000,
				'condition'		=> [
					'premium_carousel_autoplay' => 'yes'
				],
				'separator'		=> 'after'
			]
		);

        $this->add_control('premium_carousel_animation_list', 
            [
                'label'         => __('Animations', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::ANIMATION,
            ]
            );

		$this->add_control('premium_carousel_extra_class',
			[
				'label'			=> __( 'Extra Class', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::TEXT,
                'description'	=> __( 'Add extra class name that will be applied to the carousel, and you can use this class for your customizations.', 'premium-addons-for-elementor' ),
			]
		);

		$this->end_controls_section();
        
        $this->start_controls_section('premium-carousel-advance-settings',
			[
				'label'         => __( 'Additional Settings' , 'premium-addons-for-elementor' ),
			]
		);

		$this->add_control('premium_carousel_draggable_effect',
			[
				'label' 		=> __( 'Draggable Effect', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Allow the slides to be dragged by mouse click', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
				'default'		=> 'yes'
			]
		);

		$this->add_control('premium_carousel_touch_move',
			[
				'label' 		=> __( 'Touch Move', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Enable slide moving with touch', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
				'default'		=> 'yes'
			]
		);

		$this->add_control('premium_carousel_RTL_Mode',
			[
				'label' 		=> __( 'RTL Mode', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Turn on RTL mode if your language starts from right to left', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
				'condition'		=> [
					'premium_carousel_slider_type!' => 'vertical'
				]
			]
		);

		$this->add_control('premium_carousel_adaptive_height',
			[
				'label' 		=> __( 'Adaptive Height', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Adaptive height setting gives each slide a fixed height to avoid huge white space gaps', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
			]
		);

		$this->add_control('premium_carousel_pausehover',
			[
				'label' 		=> __( 'Pause on Hover', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Pause the slider when mouse hover', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
			]
		);

		$this->add_control('premium_carousel_center_mode',
			[
				'label' 		=> __( 'Center Mode', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Center mode enables a centered view with partial next/previous slides. Animations and all visible scroll type doesn\'t work with this mode', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
			]
		);

		$this->add_control('premium_carousel_space_btw_items',
			[
				'label' 		=> __( 'Slides\' Spacing', 'premium-addons-for-elementor' ),
                'description'   => __('Set a spacing value in pixels (px)', 'premium-addons-for-elementor'),
				'type'			=> Controls_Manager::NUMBER,
				'default'		=> '15'
			]
		);
        
        $this->add_control('premium_carousel_tablet_breakpoint',
			[
				'label' 		=> __( 'Tablet Breakpoint', 'premium-addons-for-elementor' ),
                'description'   => __('Sets the breakpoint between desktop and tablet devices. Below this breakpoint tablet layout will appear (Default: 1025px).', 'premium-addons-for-elementor'),
				'type'			=> Controls_Manager::NUMBER,
				'default'		=> 1025
			]
		);
        
        $this->add_control('premium_carousel_mobile_breakpoint',
			[
				'label' 		=> __( 'Mobile Breakpoint', 'premium-addons-for-elementor' ),
                'description'   => __('Sets the breakpoint between tablet and mobile devices. Below this breakpoint mobile layout will appear (Default: 768px).', 'premium-addons-for-elementor'),
				'type'			=> Controls_Manager::NUMBER,
				'default'		=> 768
			]
		);
        
        $this->end_controls_section();

		$this->start_controls_section('premium_carousel_navigation_arrows',
			[
				'label'         => __( 'Navigation Arrows', 'premium-addons-for-elementor' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_carousel_navigation_show'  => 'yes'
                ]
			]
		);
        
        $this->add_control('premium_carousel_arrow_icon_next',
		    [
		        'label'         => __( 'Right Icon', 'premium-addons-for-elementor' ),
		        'type'          => Controls_Manager::CHOOSE,
		        'options'       => [
		            'right_arrow_bold'          => [
		                'icon' => 'fa fa-arrow-right',
		            ],
		            'right_arrow_long'          => [
		                'icon' => 'fa fa-long-arrow-right',
		            ],
		            'right_arrow_long_circle' 	=> [
		                'icon' => 'fa fa-arrow-circle-right',
		            ],
		            'right_arrow_angle' 		=> [
		                'icon' => 'fa fa-angle-right',
		            ],
		            'right_arrow_chevron' 		=> [
		                'icon' => 'fa fa-chevron-right',
		            ]
		        ],
		        'default'       => 'right_arrow_angle',
		        'condition'		=> [
					'premium_carousel_navigation_show'  => 'yes',
					'premium_carousel_slider_type!'     => 'vertical'
				]
		    ]
		);

		// If the carousel type vertical 
		$this->add_control('premium_carousel_arrow_icon_next_ver',
		    [
		        'label' 	=> __( 'Bottom Icon', 'premium-addons-for-elementor' ),
		        'type' 		=> Controls_Manager::CHOOSE,
		        'options' 	=> [
		            'right_arrow_bold'    		=> [
		                'icon' => 'fa fa-arrow-down',
		            ],
		            'right_arrow_long' 			=> [
		                'icon' => 'fa fa-long-arrow-down',
		            ],
		            'right_arrow_long_circle' 	=> [
		                'icon' => 'fa fa-arrow-circle-down',
		            ],
		            'right_arrow_angle' 		=> [
		                'icon' => 'fa fa-angle-down',
		            ],
		            'right_arrow_chevron' 		=> [
		                'icon' => 'fa fa-chevron-down',
		            ]
		        ],
		        'default'		=> 'right_arrow_angle',
		        'condition'		=> [
					'premium_carousel_navigation_show'  => 'yes',
					'premium_carousel_slider_type'      => 'vertical',
				]
		    ]
		);
        
        // If carousel slider is vertical type
		$this->add_control('premium_carousel_arrow_icon_prev_ver',
		    [
		        'label'         => __( 'Top Icon', 'premium-addons-for-elementor' ),
		        'type'          => Controls_Manager::CHOOSE,
		        'options'       => [
		            'left_arrow_bold'    		=> [
		                'icon' => 'fa fa-arrow-up',
		            ],
		            'left_arrow_long' 			=> [
		                'icon' => 'fa fa-long-arrow-up',
		            ],
		            'left_arrow_long_circle' 	=> [
		                'icon' => 'fa fa-arrow-circle-up',
		            ],
		            'left_arrow_angle' 		=> [
		                'icon' => 'fa fa-angle-up',
		            ],
		            'left_arrow_chevron' 		=> [
		                'icon' => 'fa fa-chevron-up',
		            ]
		        ],
		        'default'		=> 'left_arrow_angle',
		        'condition'		=> [
					'premium_carousel_navigation_show'  => 'yes',
					'premium_carousel_slider_type'      => 'vertical',
				]
		    ]
		);
        
		$this->add_control('premium_carousel_arrow_icon_prev',
		    [
		        'label'         => __( 'Left Icon', 'premium-addons-for-elementor' ),
		        'type'          => Controls_Manager::CHOOSE,
		        'options'       => [
		            'left_arrow_bold'    		=> [
		                'icon' => 'fa fa-arrow-left',
		            ],
		            'left_arrow_long' 			=> [
		                'icon' => 'fa fa-long-arrow-left',
		            ],
		            'left_arrow_long_circle' 	=> [
		                'icon' => 'fa fa-arrow-circle-left',
		            ],
		            'left_arrow_angle' 		=> [
		                'icon' => 'fa fa-angle-left',
		            ],
		            'left_arrow_chevron' 		=> [
		                'icon' => 'fa fa-chevron-left',
		            ]
		        ],
		        'default'		=> 'left_arrow_angle',
		        'condition'		=> [
					'premium_carousel_navigation_show' => 'yes',
					'premium_carousel_slider_type!' => 'vertical',
				]
		    ]
		);

		$this->add_control('premium_carousel_arrow_style',
			[
				'label'			=> __( 'Style', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SELECT,
				'default'		=> 'default',
				'options'		=> [
					'default'			=> __( 'Default', 'premium-addons-for-elementor' ),
					'circle-bg'			=> __( 'Circle Background', 'premium-addons-for-elementor' ),
					'square-bg'			=> __( 'Square Background', 'premium-addons-for-elementor' ),
					'circle-border'		=> __( 'Circle border', 'premium-addons-for-elementor' ),
					'square-border'		=> __( 'Square border', 'premium-addons-for-elementor' ),
				],
				'condition' 	=> [
					'premium_carousel_navigation_show' => 'yes'
				]
			]
		);
        
        $this->add_control('premium_carousel_arrow_color',
			[
				'label' 		=> __( 'Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_2,
				],
				'condition'		=> [
					'premium_carousel_navigation_show' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} .premium-carousel-wrapper .slick-arrow' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_responsive_control('premium_carousel_arrow_size',
			[
				'label'         => __( 'Size', 'premium-addons-for-elementor' ),
				'type'          => Controls_Manager::SLIDER,
				'default'       => [
					'size' => 14,
				],
				'range'         => [
					'px' => [
						'min' => 0,
						'max' => 60
					],
				],
				'condition'		=> [
					'premium_carousel_navigation_show' => 'yes'
				],
				'selectors'     => [
					'{{WRAPPER}} .premium-carousel-wrapper .slick-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control('premium_carousel_arrow_bg_color',
			[
				'label' 		=> __( 'Background Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_1,
				],
				'condition'		=> [
					'premium_carousel_arrow_style' => [ 'circle-bg', 'square-bg' ]
				],
				'selectors'     => [
					'{{WRAPPER}} .premium-carousel-wrapper .circle-bg' => 'background: {{VALUE}};',
					'{{WRAPPER}} .premium-carousel-wrapper .square-bg' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_control('premium_carousel_arrow_border_color',
			[
				'label' 		=> __( 'Border Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_1,
				],
				'condition'		=> [
					'premium_carousel_arrow_style' => [ 'circle-border', 'square-border' ]
				],
				'selectors'     => [
					'{{WRAPPER}} .premium-carousel-wrapper .square-border' => 'border: solid {{VALUE}};',
					'{{WRAPPER}} .premium-carousel-wrapper .circle-border' => 'border: solid {{VALUE}};',
				],
			]
		);

		$this->add_control('premium_carousel_border_size',
			[
				'label'         => __( 'Border Size', 'premium-addons-for-elementor' ),
				'type'          => Controls_Manager::SLIDER,
				'default'       => [
					'size' => 1,
				],
				'range'         => [
					'px' => [
						'min' => 0,
						'max' => 60
					],
				],
				'condition'		=> [
					'premium_carousel_arrow_style' => [ 'circle-border', 'square-border' ]
				],
				'selectors'     => [
					'{{WRAPPER}} .premium-carousel-wrapper .square-border' => 'border-width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .premium-carousel-wrapper .circle-border' => 'border-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control('premium_carousel_arrow_position',
            [
                'label'         => __('Position (PX)', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'range'         => [
                    'px' => [
                        'min'   => -50,
                        'max'   => 1,
                    ],
                ],
                'condition'		=> [
                    'premium_carousel_navigation_show' => 'yes',
                    'premium_carousel_slider_type'     => 'horizontal'
				],
                'selectors'     => [
                    '{{WRAPPER}} .premium-carousel-wrapper a.carousel-arrow.carousel-next' => 'right: {{SIZE}}px',
                    '{{WRAPPER}} .premium-carousel-wrapper a.carousel-arrow.carousel-prev' => 'left: {{SIZE}}px',
                ]
            ]
            );

        $this->end_controls_section();

        $this->start_controls_section('premium_carousel_navigation_dots',
			[
				'label'         => __( 'Navigation Dots', 'premium-addons-for-elementor' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_carousel_dot_navigation_show'  => 'yes'
                ]
			]
		);
        
        $this->add_control('premium_carousel_dot_icon',
		    [
		        'label'         => __( 'Icon', 'premium-addons-for-elementor' ),
		        'type'          => Controls_Manager::CHOOSE,
		        'options'       => [
		            'square_white'    		=> [
		                'icon' => 'fa fa-square-o',
		            ],
		            'square_black' 			=> [
		                'icon' => 'fa fa-square',
		            ],
		            'circle_white' 	=> [
		                'icon' => 'fa fa-circle',
		            ],
		            'circle_thin' 		=> [
		                'icon' => 'fa fa-circle-thin',
		            ],
		            'circle_thin_bold' 		=> [
		                'icon' => 'fa fa-circle-o',
		            ]
		        ],
		        'default'		=> 'circle_white',
		        'condition'		=> [
					'premium_carousel_dot_navigation_show' => 'yes'
				]
		    ]
		);

		$this->add_control('premium_carousel_dot_navigation_color',
			[
				'label' 		=> __( 'Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_2,
				],
				'condition'		=> [
					'premium_carousel_dot_navigation_show' => 'yes'
				],
				'selectors'		=> [
					'{{WRAPPER}} ul.slick-dots li' => 'color: {{VALUE}}'
				]
			]
		);

		$this->add_control('premium_carousel_dot_navigation_active_color',
			[
				'label' 		=> __( 'Active Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_1,
				],
				'condition'		=> [
					'premium_carousel_dot_navigation_show' => 'yes'
				],
				'selectors'		=> [
					'{{WRAPPER}} ul.slick-dots li.slick-active' => 'color: {{VALUE}}'
				]
			]
		);
        
        $this->add_control('premium_carousel_navigation_effect',
			[
				'label' 		=> __( 'Ripple Effect', 'premium-addons-for-elementor' ),
				'description'	=> __( 'Enable a ripple effect when the active dot is hovered/clicked', 'premium-addons-for-elementor' ),
				'type'			=> Controls_Manager::SWITCHER,
                'condition'		=> [
					'premium_carousel_dot_navigation_show' => 'yes'
				],
			]
		);
        
        $this->add_control('premium_carousel_navigation_effect_border_color',
			[
				'label' 		=> __( 'Ripple Color', 'premium-addons-for-elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'scheme' 		=> [
				    'type' 	=> Scheme_Color::get_type(),
				    'value' => Scheme_Color::COLOR_1,
				],
				'condition'		=> [
					'premium_carousel_dot_navigation_show' => 'yes',
                    'premium_carousel_navigation_effect'   => 'yes'
				],
				'selectors'		=> [
					'{{WRAPPER}} .premium-carousel-wrapper.hvr-ripple-out ul.slick-dots li.slick-active:before' => 'border-color: {{VALUE}}'
				]
			]
		);
        
        /*First Border Radius*/
        $this->add_control('premium_carousel_navigation_effect_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', '%', 'em'],
                'condition'		=> [
                    'premium_carousel_dot_navigation_show' => 'yes',
                    'premium_carousel_navigation_effect'    => 'yes'
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-carousel-wrapper.hvr-ripple-out ul.slick-dots li.slick-active:before' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
            );

		$this->end_controls_section();

	}

	protected function render() {
        
		$settings = $this->get_settings();
        
        $vertical = $settings['premium_carousel_slider_type'] == 'vertical' ? true : false;
		// responsive carousel set up

		$slides_on_desk = $settings['premium_carousel_responsive_desktop'];
		if( $settings['premium_carousel_slides_to_show'] == 'all' ) {
			$slidesToScroll = ! empty( $slides_on_desk ) ? $slides_on_desk : 1;
		} else {
			$slidesToScroll = 1;
		}

		$slidesToShow = !empty($slides_on_desk) ? $slides_on_desk : 1;

		$slides_on_tabs = $settings['premium_carousel_responsive_tabs'];
		$slides_on_mob = $settings['premium_carousel_responsive_mobile'];

		if( empty( $settings['premium_carousel_responsive_tabs'] ) ) {
			$slides_on_tabs = $slides_on_desk;
		}

		if( empty ( $settings['premium_carousel_responsive_mobile'] ) ) {
			$slides_on_mob = $slides_on_desk;
		}

        $infinite = $settings['premium_carousel_loop'] == 'yes' ? true : false;

        $fade = $settings['premium_carousel_fade'] == 'yes' ? true : false;
        
        $speed = !empty( $settings['premium_carousel_speed'] ) ? $settings['premium_carousel_speed'] : '';
        
        $autoplay = $settings['premium_carousel_autoplay'] == 'yes' ? true : false;
        
        $autoplaySpeed = !empty( $settings['premium_carousel_autoplay_speed'] ) ? $settings['premium_carousel_autoplay_speed'] : '';
        
        $draggable = $settings['premium_carousel_draggable_effect'] == 'yes' ? true  : false;
		
        $touchMove = $settings['premium_carousel_touch_move'] == 'yes' ? true : false;
        
		$dir = '';
        $rtl = false;
        
		if( $settings['premium_carousel_RTL_Mode'] == 'yes' ) {
			$rtl = true;
			$dir = 'dir="rtl"';
        }
        
        $adaptiveHeight = $settings['premium_carousel_adaptive_height'] == 'yes' ? true : false;
		
        $pauseOnHover = $settings['premium_carousel_pausehover'] == 'yes' ? true : false;
		
        $centerMode = $settings['premium_carousel_center_mode'] == 'yes' ? true : false;
        
        $centerPadding = !empty( $settings['premium_carousel_space_btw_items'] ) ? $settings['premium_carousel_space_btw_items'] ."px" : '';
		
		// Navigation arrow setting setup
		if( $settings['premium_carousel_navigation_show'] == 'yes') {
			$arrows = true;

			if( $settings['premium_carousel_slider_type'] == 'vertical' ) {
				$vertical_alignment = "ver-carousel-arrow";
			} else {
				$vertical_alignment = "carousel-arrow";
			}
			if( $settings['premium_carousel_arrow_style'] == 'circle-bg' ) {
				$arrow_class = ' circle-bg';
			}
			if( $settings['premium_carousel_arrow_style'] == 'square-bg' ) {
				$arrow_class = ' square-bg';
			}
			if( $settings['premium_carousel_arrow_style'] == 'square-border' ) {
				$arrow_class = ' square-border';
			}
			if( $settings['premium_carousel_arrow_style'] == 'circle-border' ) {
				$arrow_class = ' circle-border';
			}
			if( $settings['premium_carousel_arrow_style'] == 'default' ) {
				$arrow_class = '';
			}
			if( $settings['premium_carousel_slider_type'] == 'vertical' ) {
				$icon_next = $settings['premium_carousel_arrow_icon_next_ver'];
				if( $icon_next == 'right_arrow_bold' ) {
					$icon_next_class = 'fa fa-arrow-down';
				}
				if( $icon_next == 'right_arrow_long' ) {
					$icon_next_class = 'fa fa-long-arrow-down';
				}
				if( $icon_next == 'right_arrow_long_circle' ) {
					$icon_next_class = 'fa fa-arrow-circle-down';
				}
				if( $icon_next == 'right_arrow_angle' ) {
					$icon_next_class = 'fa fa-angle-down';
				}
				if( $icon_next == 'right_arrow_chevron' ) {
					$icon_next_class = 'fa fa-chevron-down';
				}
				$icon_prev = $settings['premium_carousel_arrow_icon_prev_ver'];

				if( $icon_prev == 'left_arrow_bold' ) {
					$icon_prev_class = 'fa fa-arrow-up';
				}
				if( $icon_prev == 'left_arrow_long' ) {
					$icon_prev_class = 'fa fa-long-arrow-up';
				}
				if( $icon_prev == 'left_arrow_long_circle' ) {
					$icon_prev_class = 'fa fa-arrow-circle-up';
				}
				if( $icon_prev == 'left_arrow_angle' ) {
					$icon_prev_class = 'fa fa-angle-up';
				}
				if( $icon_prev == 'left_arrow_chevron' ) {
					$icon_prev_class = 'fa fa-chevron-up';
				}
			} else {
				$icon_next = $settings['premium_carousel_arrow_icon_next'];
				if( $icon_next == 'right_arrow_bold' ) {
					$icon_next_class = 'fa fa-arrow-right';
				}
				if( $icon_next == 'right_arrow_long' ) {
					$icon_next_class = 'fa fa-long-arrow-right';
				}
				if( $icon_next == 'right_arrow_long_circle' ) {
					$icon_next_class = 'fa fa-arrow-circle-right';
				}
				if( $icon_next == 'right_arrow_angle' ) {
					$icon_next_class = 'fa fa-angle-right';
				}
				if( $icon_next == 'right_arrow_chevron' ) {
					$icon_next_class = 'fa fa-chevron-right';
				}
				$icon_prev = $settings['premium_carousel_arrow_icon_prev'];

				if( $icon_prev == 'left_arrow_bold' ) {
					$icon_prev_class = 'fa fa-arrow-left';
				}
				if( $icon_prev == 'left_arrow_long' ) {
					$icon_prev_class = 'fa fa-long-arrow-left';
				}
				if( $icon_prev == 'left_arrow_long_circle' ) {
					$icon_prev_class = 'fa fa-arrow-circle-left';
				}
				if( $icon_prev == 'left_arrow_angle' ) {
					$icon_prev_class = 'fa fa-angle-left';
				}
				if( $icon_prev == 'left_arrow_chevron' ) {
					$icon_prev_class = 'fa fa-chevron-left';
				}
			}

			$next_arrow = '<a type="button" data-role="none" class="'. $vertical_alignment .' carousel-next'.$arrow_class.'" aria-label="Next" role="button" style=""><i class="'.$icon_next_class.'" aria-hidden="true"></i></a>';

			$left_arrow = '<a type="button" data-role="none" class="'. $vertical_alignment .' carousel-prev'.$arrow_class.'" aria-label="Next" role="button" style=""><i class="'.$icon_prev_class.'" aria-hidden="true"></i></a>';

			$nextArrow = $next_arrow;
			$prevArrow = $left_arrow;
		} else {
			$arrows = false;
            $nextArrow = '';
			$prevArrow = '';
		}
		if( $settings['premium_carousel_dot_navigation_show'] == 'yes' ){
			$dots =  true;
			if( $settings['premium_carousel_dot_icon'] == 'square_white' ) {
				$dot_icon = 'fa fa-square-o';
			}
			if( $settings['premium_carousel_dot_icon'] == 'square_black' ) {
				$dot_icon = 'fa fa-square';
			}
			if( $settings['premium_carousel_dot_icon'] == 'circle_white' ) {
				$dot_icon = 'fa fa-circle';
			}
			if( $settings['premium_carousel_dot_icon'] == 'circle_thin' ) {
				$dot_icon = 'fa fa-circle-thin';
			}
			if( $settings['premium_carousel_dot_icon'] == 'circle_thin_bold' ) {
				$dot_icon = 'fa fa-circle-o';
			}
			$customPaging = $dot_icon;
		} else {
            $dots =  false;
            $dot_icon = '';
            $customPaging = '';
        }
		$extra_class = $settings['premium_carousel_extra_class'] !== '' ? ' '.$settings['premium_carousel_extra_class'] : '';
		
		$animation_class = $settings['premium_carousel_animation_list'];
		$animation = ! empty( $animation_class ) ? 'animated ' . $animation_class : 'null';
        
        $dot_anim = $settings['premium_carousel_navigation_effect'] == 'yes' ? 'hvr-ripple-out' : '';

        $tablet_breakpoint = ! empty ( $settings['premium_carousel_tablet_breakpoint'] ) ? $settings['premium_carousel_tablet_breakpoint'] : 1025;
        
        $mobile_breakpoint = ! empty ( $settings['premium_carousel_mobile_breakpoint'] ) ? $settings['premium_carousel_mobile_breakpoint'] : 768;
        
        $carousel_settings = [
            'vertical'      => $vertical,
            'slidesToScroll'=> $slidesToScroll,
            'slidesToShow'  => $slidesToShow,
            'infinite'      => $infinite,
            'speed'         => $speed,
            'fade'			=> $fade,
            'autoplay'      => $autoplay,
            'autoplaySpeed' => $autoplaySpeed,
            'draggable'     => $draggable,
            'touchMove'     => $touchMove,
            'rtl'           => $rtl,
            'adaptiveHeight'=> $adaptiveHeight,
            'pauseOnHover'  => $pauseOnHover,
            'centerMode'    => $centerMode,
            'centerPadding' => $centerPadding,
            'arrows'        => $arrows,
            'nextArrow'     => $nextArrow,
            'prevArrow'     => $prevArrow,
            'dots'          => $dots,
            'customPaging'  => $customPaging,
            'slidesDesk'    => $slides_on_desk,
            'slidesTab'     => $slides_on_tabs,
            'slidesMob'     => $slides_on_mob,
            'animation'     => $animation,
            'tabletBreak'   => $tablet_breakpoint,
            'mobileBreak'   => $mobile_breakpoint
        ];
        
        $premium_elements_page_id = array();
        if( 'select' === $settings['premium_carousel_content_type'] ){
            $premium_elements_page_id = $settings['premium_carousel_slider_content'];
        } else {
            foreach( $settings['premium_carousel_templates_repeater'] as $template ){
                array_push($premium_elements_page_id, $template['premium_carousel_repeater_item']);
            }
        }
        
        $premium_elements_frontend = new Frontend;
        
        $this->add_render_attribute( 'carousel', 'id', 'premium-carousel-wrapper-' . esc_attr( $this->get_id() ) );
        
        $this->add_render_attribute( 'carousel', 'class', [
            'premium-carousel-wrapper',
            $dot_anim,
            'carousel-wrapper-' . esc_attr( $this->get_id() ),
            $extra_class,
            $dir
        ] );
        
        if( 'yes' == $settings['premium_carousel_dot_navigation_show'] ) {
            $this->add_render_attribute( 'carousel', 'class', 'premium-carousel-dots-' . $settings['premium_carousel_dot_position'] );
            
        }

        if( $settings['premium_carousel_fade'] == 'yes' && $settings['premium_carousel_zoom'] == 'yes' ) {
			$this->add_render_attribute( 'carousel', 'class', 'premium-carousel-scale' );
        }
        
        $this->add_render_attribute( 'carousel', 'data-settings', wp_json_encode( $carousel_settings ) );
                        
		?>
            
        <div <?php echo $this->get_render_attribute_string('carousel'); ?>>
            <div id="premium-carousel-<?php echo esc_attr( $this->get_id() ); ?>" class="premium-carousel-inner">
                <?php 
                    foreach( $premium_elements_page_id as $elementor_post_id ) :
                 ?>
                <div class="item-wrapper">
                    <?php echo $premium_elements_frontend->get_builder_content( $elementor_post_id, true ); ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
		<?php
	}
}