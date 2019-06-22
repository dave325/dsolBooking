<?php

namespace Elementor;

if( !defined( 'ABSPATH' ) ) exit;

class Premium_Grid extends Widget_Base {
    
    public function get_name(){
        return 'premium-img-gallery';
    }
    
    public function getTemplateInstance() {
		return $this->templateInstance = premium_Template_Tags::getInstance();
	}
    
    public function get_title() {
		return \PremiumAddons\Helper_Functions::get_prefix() . ' Grid';
	}
    
    public function get_icon(){
        return 'pa-grid-icon';
    }
    
    public function get_style_depends(){
        return [
            'pa-prettyphoto',
        ];
    }
    
    public function get_script_depends() {
        return [
            'prettyPhoto-js',
            'isotope-js',
            'premium-addons-js'
        ];
    }
    
    public function is_reload_preview_required(){
        return true;
    }
    
    public function get_categories(){
        return ['premium-elements'];
    }
    
    protected function _register_controls(){
        
        $this->start_controls_section('premium_gallery_general',
            [
                'label'     => __('Layout','premium-addons-for-elementor'),
                
            ]);
        
        $this->add_control('premium_gallery_img_size_select',
                [
                    'label'             => __('Grid Layout', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::SELECT,
                    'options'           => [
                        'fitRows'  => __('Even', 'premium-addons-for-elementor'),
                        'masonry'  => __('Masonry', 'premium-addons-for-elementor'),
                        'metro'    => __('Metro', 'premium-addons-for-elementor'), 
                   ],
                    'default'           => 'fitRows',
                    ]
                );
        
        $this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name'                  => 'thumbnail', // Actually its `image_size`.
				'default'               => 'full',
                'condition'             => [
                    'premium_gallery_img_size_select'   => 'fitRows'
                ]
			]
		);
        
        $this->add_responsive_control('premium_gallery_column_number',
			[
  				'label'                 => __( 'Columns', 'premium-addons-for-elementor' ),
				'label_block'           => true,
				'type'                  => Controls_Manager::SELECT,				
				'desktop_default'       => '50%',
				'tablet_default'        => '100%',
				'mobile_default'        => '100%',
				'options'               => [
					'100%'      => __( '1 Column', 'premium-addons-for-elementor' ),
					'50%'       => __( '2 Columns', 'premium-addons-for-elementor' ),
					'33.330%'   => __( '3 Columns', 'premium-addons-for-elementor' ),
					'25%'       => __( '4 Columns', 'premium-addons-for-elementor' ),
					'20%'       => __( '5 Columns', 'premium-addons-for-elementor' ),
					'16.66%'    => __( '6 Columns', 'premium-addons-for-elementor' ),
                    '8.33%'     => __( '12 Columns', 'premium-addons-for-elementor' ),
				],
                'condition'             => [
                    'premium_gallery_img_size_select!'  => 'metro'
                ],
				'selectors' => [
					'{{WRAPPER}} .premium-img-gallery-masonry div.premium-gallery-item, {{WRAPPER}} .premium-img-gallery-fitRows div.premium-gallery-item' => 'width: {{VALUE}};',
				],
				'render_type' => 'template'
			]
		);
        
        $this->add_control( 'premium_gallery_load_more', 
            [
                'label'         => __( 'Load More Button', 'premium-addons-for-elementor' ),
                'description'   => __('Requires number of images larger than 6', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER
            ]
        );
        
        $this->add_control( 'premium_gallery_load_more_text', 
            [
                'label'     => __( 'Button Text', 'premium-addons-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => __('Load More', 'premium-addons-for-elementor'),
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    'premium_gallery_load_more'    => 'yes'
                ]
            ]
        );
        
        $this->add_control( 'premium_gallery_load_minimum',
            [
                'label'         => __('Minimum Number of Images', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'description'   => __('Set the minimum number of images before showing load more button', 'premium-addons-for-elementor'),
                'default'       => 6,
                'condition' => [
                    'premium_gallery_load_more'    => 'yes'
                ]
            ]
        );
        
        $this->add_control( 'premium_gallery_load_click_number',
            [
                'label'         => __('Images to Show', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'description'   => __('Set the minimum number of images to show with each click', 'premium-addons-for-elementor'),
                'default'       => 6,
                'condition' => [
                    'premium_gallery_load_more'    => 'yes'
                ]
            ]
        );
        
        $this->add_responsive_control('premium_gallery_load_more_align',
            [
                'label'         => __( 'Button Alignment', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::CHOOSE,
                'options'       => [
                    'left'      => [
                        'title'=> __( 'Left', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-left',
                    ],
                    'center'    => [
                        'title'=> __( 'Center', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-center',
                    ],
                    'right'     => [
                        'title'=> __( 'Right', 'premium-addons-for-elementor' ),
                        'icon' => 'fa fa-align-right',
                    ],
                ],
                'default'       => 'center',
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-load-more' => 'text-align: {{VALUE}};',
                ],
                'condition' => [
                    'premium_gallery_load_more'    => 'yes'
                ]
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_cats',
            [
                'label'     => __('Categories','premium-addons-for-elementor'),
            ]);
        
        $this->add_control( 'premium_gallery_first_cat_switcher', 
            [
                'label'     => __( 'First Category', 'premium-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'default'   => 'yes'
            ]
        );
        
        $this->add_control( 'premium_gallery_first_cat_label', 
            [
                'label'     => __( 'First Category Label', 'premium-addons-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'default'   => __('All', 'premium-addons-for-elementor'),
                'dynamic'   => [ 'active' => true ],
                'condition' => [
                    'premium_gallery_first_cat_switcher'    => 'yes'
                ]
            ]
        );
        
        $repeater = new REPEATER();
        
        $repeater->add_control( 'premium_gallery_img_cat', 
            [
                'label'     => __( 'Category', 'premium-addons-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'dynamic'   => [ 'active' => true ],
            ]
        );
        
        $repeater->add_control( 'premium_gallery_img_cat_rotation',
            [
                'label'         => __('Rotation Degrees', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'description'   => __('Set rotation value in degress', 'premium-addons-for-elementor'),
                'min'           => -180,
                'max'           => 180,
                'selectors'     => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => '-webkit-transform: rotate({{VALUE}}deg); -moz-transform: rotate({{VALUE}}deg); -o-transform: rotate({{VALUE}}deg); transform: rotate({{VALUE}}deg);'
                ],
            ]
        );
        
        $this->add_control('premium_gallery_cats_content',
           [
               'label' => __( 'Categories', 'premium-addons-for-elementor' ),
               'type' => Controls_Manager::REPEATER,
               'default' => [
                   [
                       'premium_gallery_img_cat'   => 'Category 1',
                   ],
                   [
                       'premium_gallery_img_cat'   => 'Category 2',
                   ],
               ],
               'fields' => array_values( $repeater->get_controls() ) ,
               'title_field'   => '{{{ premium_gallery_img_cat }}}',
           ]
       );
        
        $this->add_control( 'premium_gallery_active_cat',
            [
                'label'         => __('Active Category Index', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::NUMBER,
                'description'   => __('Put the index of the default active category, default is 1', 'premium-addons-for-elementor'),
                'default'       => 1,
            ]
        );
        
        $this->add_control('premium_gallery_filter',
            [
                'label'         => __( 'Filter', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
                'default'       => 'yes'
            ]
        );
    
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_content',
            [
                'label'     => __('Images','premium-addons-for-elementor'),
            ]);
        
        $img_repeater = new REPEATER();
        
        $img_repeater->add_control('premium_gallery_img', 
            [
                'label' => __( 'Upload Image', 'premium-addons-for-elementor' ),
                'type' => Controls_Manager::MEDIA,
                'dynamic'       => [ 'active' => true ],
                'default'       => [
                    'url'	=> Utils::get_placeholder_image_src(),
                ],
            ]);
        
        $img_repeater->add_responsive_control('premium_gallery_image_cell',
			[
  				'label'                 => __( 'Width', 'premium-addons-for-elementor' ),
                'description'           => __('Works only when layout set to \'Metro\'', 'premium-addons-for-elementor'),
				'label_block'           => true,
                'default'               => [
                    'unit'  => 'px',
                    'size'  => 4
                ],
				'type'                  => Controls_Manager::SLIDER,
                'range'         => [
                    'px'    => [
                        'min'   => 1, 
                        'max'   => 12,
                    ],
                ],
				'render_type' => 'template'
			]
		);
        
        $img_repeater->add_responsive_control('premium_gallery_image_vcell',
			[
  				'label'                 => __( 'Height', 'premium-addons-for-elementor' ),
                'description'           => __('Works only when layout set to \'Metro\'', 'premium-addons-for-elementor'),
				'label_block'           => true,
				'type'                  => Controls_Manager::SLIDER,
                'default'               => [
                    'unit'  => 'px',
                    'size'  => 4
                ],
                'range'         => [
                    'px'    => [
                        'min'   => 1, 
                        'max'   => 12,
                    ],
                ],
				'render_type' => 'template'
			]
		);
        
        $img_repeater->add_control('premium_gallery_img_name', 
            [
                'label' => __( 'Title', 'premium-addons-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'dynamic'       => [ 'active' => true ],
                'label_block'   => true,
            ]);
        
        $img_repeater->add_control('premium_gallery_img_desc', 
            [
                'label' => __( 'Description', 'premium-addons-for-elementor' ),
                'type' => Controls_Manager::TEXTAREA,
                'dynamic'       => [ 'active' => true ],
                'label_block' => true,
            ]);
        
        $img_repeater->add_control('premium_gallery_img_category', 
            [
                'label' => __( 'Category', 'premium-addons-for-elementor' ),
                'type' => Controls_Manager::TEXT,
                'description'=> __('To assign for multiple categories, separate by a comma \',\'','premium-addons-for-elementor'),
                'dynamic'       => [ 'active' => true ],
            ]);
        
        $img_repeater->add_control('premium_gallery_img_link_type', 
            [
                'label'         => __('Link Type', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT,
                'options'       => [
                    'url'   => __('URL', 'premium-addons-for-elementor'),
                    'link'  => __('Existing Page', 'premium-addons-for-elementor'),
                ],
                'default'       => 'url',
                'label_block'   => true,
            ]);
        
        $img_repeater->add_control('premium_gallery_img_link', 
            [
                'label'         => __('Link', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::URL,
                'dynamic'       => [ 'active' => true ],
                'placeholder'   => 'https://premiumaddons.com/',
                'label_block'   => true,
                'condition'     => [
                    'premium_gallery_img_link_type'  => 'url'
                ]
            ]);
        
        $img_repeater->add_control('premium_gallery_img_existing', 
            [
                'label'         => __('Existing Page', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SELECT2,
                'options'       => $this->getTemplateInstance()->get_all_post(),
                'condition'     => [
                    'premium_gallery_img_link_type'=> 'link',
                ],
                'multiple'      => false,
                'separator'     => 'after',
                'label_block'   => true,
            ]);
        
        $img_repeater->add_control('premium_gallery_link_whole',
            [
                'label'         => __( 'Whole Image Link', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
            ]
        );
        
        $img_repeater->add_control('premium_gallery_lightbox_whole',
            [
                'label'         => __( 'Whole Image Lightbox', 'premium-addons-for-elementor' ),
                'type'          => Controls_Manager::SWITCHER,
            ]
        );
        
        $this->add_control('premium_gallery_img_content',
           [
               'label' => __( 'Images', 'premium-addons-for-elementor' ),
               'type' => Controls_Manager::REPEATER,
               'default' => [
                   [
                       'premium_gallery_img_name'   => 'Image #1',
                       'premium_gallery_img_category'   => 'Category 1'
                   ],
                   [
                       'premium_gallery_img_name'   => 'Image #2',
                       'premium_gallery_img_category' => 'Category 2'
                   ],
               ],
               'fields' => array_values( $img_repeater->get_controls() ),
               'title_field'   => '{{{ premium_gallery_img_name }}}' . ' / {{{ premium_gallery_img_category }}}',
           ]
       );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_grid_settings',
            [
                'label'     => __('Grid Settings','premium-addons-for-elementor'),
                
            ]);
        
        $this->add_responsive_control('premium_gallery_gap',
                [
                    'label'         => __('Image Gap', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', "em"],
                    'range'         => [
                        'px'    => [
                            'min'   => 1, 
                            'max'   => 200,
                            ],
                        ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-item' => 'padding: {{SIZE}}{{UNIT}};'
                      ]
                    ]
                );
        
        $this->add_control('premium_gallery_img_style',
                [
                    'label'         => __('Skin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'description'   => __('Choose a layout style for the gallery','premium-addons-for-elementor'),
                    'options'       => [
                        'default'           => __('Style 1', 'premium-addons-for-elementor'),
                        'style1'            => __('Style 2', 'premium-addons-for-elementor'),
                        'style2'            => __('Style 3', 'premium-addons-for-elementor'),
                    ],
                    'default'       => 'default',
                    'label_block'   => true
                ]
                );
        
        $this->add_responsive_control('premium_gallery_style1_border_border',
                [
                    'label'         => __('Height', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'range'         => [
                        'px'    => [
                            'min'   => 0,
                            'max'   => 700,
                        ]
                    ],
                    'label_block'   => true,
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-img.style1 .premium-gallery-caption' => 'bottom: {{SIZE}}px;',
                        ],
                    'condition'     => [
                        'premium_gallery_img_style' => 'style1'
                    ]
                    ]
                );
        
        $this->add_control('premium_gallery_img_effect',
                [
                    'label'         => __('Hover Effect', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SELECT,
                    'description'   => __('Choose a hover effect for the image','premium-addons-for-elementor'),
                    'options'       => [
                        'none'          => __('None', 'premium-addons-for-elementor'),
                        'zoomin'        => __('Zoom In', 'premium-addons-for-elementor'),
                        'zoomout'       => __('Zoom Out', 'premium-addons-for-elementor'),
                        'scale'         => __('Scale', 'premium-addons-for-elementor'),
                        'gray'          => __('Grayscale', 'premium-addons-for-elementor'),
                        'blur'          => __('Blur', 'premium-addons-for-elementor'),
                        'bright'        => __('Bright', 'premium-addons-for-elementor'),
                        'sepia'         => __('Sepia', 'premium-addons-for-elementor'),
                        'trans'         => __('Translate', 'premium-addons-for-elementor'),
                    ],
                    'default'       => 'zoomin',
                    'label_block'   => true
                ]
                );
        
        $this->add_control('premium_gallery_light_box',
                [
                    'label'         => __( 'Lightbox', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'default'       => 'yes'
                ]
                );
        
        $this->add_control('premium_gallery_overlay_gallery',
                [
                    'label'         => __( 'Overlay Gallery Images', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'condition'     => [
                        'premium_gallery_light_box' => 'yes'
                    ]
                ]
                );
        
        $this->add_responsive_control('premium_gallery_content_align',
                [
                    'label'         => __( 'Content Alignment', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::CHOOSE,
                    'options'       => [
                        'left'      => [
                            'title'=> __( 'Left', 'premium-addons-for-elementor' ),
                            'icon' => 'fa fa-align-left',
                            ],
                        'center'    => [
                            'title'=> __( 'Center', 'premium-addons-for-elementor' ),
                            'icon' => 'fa fa-align-center',
                            ],
                        'right'     => [
                            'title'=> __( 'Right', 'premium-addons-for-elementor' ),
                            'icon' => 'fa fa-align-right',
                            ],
                        ],
                    'default'       => 'center',
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-caption' => 'text-align: {{VALUE}};',
                        ],
                    ]
                );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_responsive_section',
            [
                'label'         => __('Responsive', 'premium-addons-for-elementor'),
            ]);
        
        $this->add_control('premium_gallery_responsive_switcher',
            [
                'label'         => __('Responsive Controls', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SWITCHER,
                'description'   => __('If the content text is not suiting well on specific screen sizes, you may enable this option which will hide the description text.', 'premium-addons-for-elementor')
            ]);
        
        $this->add_control('premium_gallery_min_range', 
            [
                'label'     => __('Minimum Size', 'premium-addons-for-elementor'),
                'type'      => Controls_Manager::NUMBER,
                'description'=> __('Note: minimum size for extra small screens is 1px.','premium-addons-for-elementor'),
                'default'   => 1,
                'condition' => [
                    'premium_gallery_responsive_switcher'    => 'yes'
                ],
            ]);

        $this->add_control('premium_gallery_max_range', 
            [
                'label'     => __('Maximum Size', 'premium-addons-for-elementor'),
                'type'      => Controls_Manager::NUMBER,
                'description'=> __('Note: maximum size for extra small screens is 767px.','premium-addons-for-elementor'),
                'default'   => 767,
                'condition' => [
                    'premium_gallery_responsive_switcher'    => 'yes'
                ],
            ]);

		$this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_general_style',
            [
                'label'     => __('General','premium-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]);
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_gallery_general_background',
                    'types'             => [ 'classic', 'gradient' ],
                    'selector'          => '{{WRAPPER}} .premium-img-gallery',
                ]
                );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'              => 'premium_gallery_general_border',
                    'selector'          => '{{WRAPPER}} .premium-img-gallery',
                    ]
                );
        
        /*First Border Radius*/
        $this->add_control('premium_gallery_general_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-img-gallery' => 'border-radius: {{SIZE}}{{UNIT}};'
                        ]
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'              => 'premium_gallery_general_box_shadow',
                'selector'          => '{{WRAPPER}} .premium-img-gallery',
            ]
            );
        
        $this->add_responsive_control('premium_gallery_general_margin',
                [
                    'label'         => __('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-img-gallery' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        $this->add_responsive_control('premium_gallery_general_padding',
                [
                    'label'         => __('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-img-gallery' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_img_style_section',
            [
                'label'     => __('Image','premium-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]);
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'              => 'premium_gallery_img_border',
                    'selector'          => '{{WRAPPER}} .pa-gallery-img-container',
                    ]
                );
        
        /*First Border Radius*/
        $this->add_control('premium_gallery_img_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-img-container' => 'border-radius: {{SIZE}}{{UNIT}};'
                        ]
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'label'             => __('Shadow','premium-addons-for-elementor'),
                'name'              => 'premium_gallery_img_box_shadow',
                'selector'          => '{{WRAPPER}} .pa-gallery-img-container',
                'condition'         => [
                    'premium_gallery_img_style!' => 'style1'
                ]
            ]
            );
        
        $this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => 'css_filters',
				'selector' => '{{WRAPPER}} .pa-gallery-img-container img',
			]
		);
        
        /*First Margin*/
        $this->add_responsive_control('premium_gallery_img_margin',
                [
                    'label'         => __('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-img-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        /*First Padding*/
        $this->add_responsive_control('premium_gallery_img_padding',
                [
                    'label'         => __('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-img-container' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_content_style',
            [
                'label'     => __('Content','premium-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]);
        
        $this->add_control('premium_gallery_title_heading',
                [
                    'label'         => __('Title', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                ]
                );
        
        $this->add_control('premium_gallery_title_color',
                [
                    'label'         => __('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-img-name, {{WRAPPER}} .premium-gallery-img-name a' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
         /*Fancy Text Typography*/
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'premium_gallery_title_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-gallery-img-name, {{WRAPPER}} .premium-gallery-img-name a',
                    ]
                );
        
        $this->add_control('premium_gallery_description_heading',
                [
                    'label'         => __('Description', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::HEADING,
                    'separator'     => 'before',
                ]
                );
        
        $this->add_control('premium_gallery_description_color',
                [
                    'label'         => __('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_3,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-img-desc, {{WRAPPER}} .premium-gallery-img-desc a' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'premium_gallery_description_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-gallery-img-desc, {{WRAPPER}} .premium-gallery-img-desc a',
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Background::get_type(),
                [
                    'name'              => 'premium_gallery_content_background',
                    'types'             => [ 'classic', 'gradient' ],
                    'selector'          => '{{WRAPPER}} .premium-gallery-caption',
                    'separator'         => 'before',
                ]
                );
        
        $this->add_group_control(
            Group_Control_Border::get_type(),
                [
                    'name'              => 'premium_gallery_content_border',
                    'selector'          => '{{WRAPPER}} .premium-gallery-caption',
                    ]
                );
        
        /*First Border Radius*/
        $this->add_control('premium_gallery_content_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-caption' => 'border-radius: {{SIZE}}{{UNIT}};'
                        ]
                    ]
                );
        
        /*First Text Shadow*/
        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'label'             => __('Shadow','premium-addons-for-elementor'),
                'name'              => 'premium_gallery_content_shadow',
                'selector'          => '{{WRAPPER}} .premium-gallery-caption',
            ]
            );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'              => 'premium_gallery_content_box_shadow',
                'selector'          => '{{WRAPPER}} .premium-gallery-caption',
            ]
            );
        
        /*First Margin*/
        $this->add_responsive_control('premium_gallery_content_margin',
                [
                    'label'         => __('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-caption' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        /*First Padding*/
        $this->add_responsive_control('premium_gallery_content_padding',
                [
                    'label'         => __('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => [ 'px', 'em', '%' ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-caption' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                        ]
                    ]
                );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_icons_style',
            [
                'label'     => __('Icons','premium-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]);
        
        $this->add_responsive_control('premium_gallery_style1_icons_position',
                [
                    'label'         => __('Position', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', '%', 'em'],
                    'range'         => [
                        'px'    => [
                            'min'   => 0,
                            'max'   => 300,
                        ]
                    ],
                    'label_block'   => true,
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-img.style1 .pa-gallery-icons-inner-container,{{WRAPPER}} .pa-gallery-img.default .pa-gallery-icons-inner-container' => 'top: {{SIZE}}{{UNIT}};',
                        ],
                    'condition'     => [
                        'premium_gallery_img_style!' => 'style2'
                        ]
                    ]
                );
        
        $this->start_controls_tabs('premium_gallery_icons_style_tabs');
        
        $this->start_controls_tab('premium_gallery_icons_style_normal',
                [
                    'label'         => __('Normal', 'premium-addons-for-elementor'),
                ]
                );
        
        $this->add_control('premium_gallery_icons_style_color',
                [
                    'label'         => __('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image i, {{WRAPPER}} .pa-gallery-img-link i' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
        $this->add_control('premium_gallery_icons_style_background',
                [
                    'label'         => __('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image span, {{WRAPPER}} .pa-gallery-img-link span' => 'background-color: {{VALUE}};',
                    ]
                ]
                );
        
        /*Icon Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_gallery_icons_style_border',
                    'selector'      => '{{WRAPPER}} .pa-gallery-magnific-image span, {{WRAPPER}} .pa-gallery-img-link span',
                ]
                );
        
        /*Button Border Radius*/
        $this->add_control('premium_gallery_icons_style_border_radius',
                [
                    'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', 'em' , '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image span, {{WRAPPER}} .pa-gallery-img-link span' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        /*Button Shadow*/
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         => __('Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_gallery_icons_style_shadow',
                    'selector'      => '{{WRAPPER}} .pa-gallery-magnific-image span, {{WRAPPER}} .pa-gallery-img-link span',
                ]
                );
        
        /*Button Margin*/
        $this->add_responsive_control('premium_gallery_icons_style_margin',
                [
                    'label'         => __('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image span, {{WRAPPER}} .pa-gallery-img-link span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        /*Button Padding*/
        $this->add_responsive_control('premium_gallery_icons_style_padding',
                [
                    'label'         => __('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image span, {{WRAPPER}} .pa-gallery-img-link span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->end_controls_tab();

        $this->start_controls_tab('premium_gallery_icons_style_hover',
        [
            'label'         => __('Hover', 'premium-addons-for-elementor'),
        ]
        );
        
        $this->add_control('premium_gallery_icons_style_overlay',
                [
                    'label'         => __('Overlay Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-img.default:hover .pa-gallery-icons-wrapper, {{WRAPPER}} .pa-gallery-img .pa-gallery-icons-caption-container, {{WRAPPER}} .pa-gallery-img:hover .pa-gallery-icons-caption-container, {{WRAPPER}} .pa-gallery-img.style1:hover .pa-gallery-icons-wrapper' => 'background-color: {{VALUE}};',
                    ],
                ]
                );
        
        $this->add_control('premium_gallery_icons_style_color_hover',
                [
                    'label'         => __('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image:hover i, {{WRAPPER}} .pa-gallery-img-link:hover i' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
        $this->add_control('premium_gallery_icons_style_background_hover',
                [
                    'label'         => __('Background Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image:hover span, {{WRAPPER}} .pa-gallery-img-link:hover span' => 'background-color: {{VALUE}};',
                    ]
                ]
                );
        
        /*Button Border*/
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'          => 'premium_gallery_icons_style_border_hover',
                    'selector'      => '{{WRAPPER}} .pa-gallery-magnific-image:hover span, {{WRAPPER}} .pa-gallery-img-link:hover span',
                ]
                );
        
        /*Button Border Radius*/
        $this->add_control('premium_gallery_icons_style_border_radius_hover',
                [
                    'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::SLIDER,
                    'size_units'    => ['px', 'em' , '%' ],                    
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image:hover span, {{WRAPPER}} .pa-gallery-img-link:hover span' => 'border-radius: {{SIZE}}{{UNIT}};'
                    ]
                ]
                );
        
        /*Button Shadow*/
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'label'         => __('Shadow','premium-addons-for-elementor'),
                    'name'          => 'premium_gallery_icons_style_shadow_hover',
                    'selector'      => '{{WRAPPER}} {{WRAPPER}} .pa-gallery-magnific-image:hover span, {{WRAPPER}} .pa-gallery-img-link:hover span',
                ]
                );
        
        /*Button Margin*/
        $this->add_responsive_control('premium_gallery_icons_style_margin_hover',
                [
                    'label'         => __('Margin', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image:hover span, {{WRAPPER}} .pa-gallery-img-link:hover span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        /*Button Padding*/
        $this->add_responsive_control('premium_gallery_icons_style_padding_hover',
                [
                    'label'         => __('Padding', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::DIMENSIONS,
                    'size_units'    => ['px', 'em', '%'],
                    'selectors'     => [
                        '{{WRAPPER}} .pa-gallery-magnific-image:hover span, {{WRAPPER}} .pa-gallery-img-link:hover span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                    ]
                ]);
        
        $this->end_controls_tab();
        
        $this->end_controls_tabs();
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_filter_style',
            [
                'label'     => __('Filter','premium-addons-for-elementor'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'premium_gallery_filter'    => 'yes'
                ]
            ]);
        
        $this->add_control('premium_gallery_filter_color',
                [
                    'label'         => __('Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_2,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-cats-container li a.category span' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
        $this->add_control('premium_gallery_filter_active_color',
                [
                    'label'         => __('Active Color', 'premium-addons-for-elementor'),
                    'type'          => Controls_Manager::COLOR,
                    'scheme'        => [
                        'type'  => Scheme_Color::get_type(),
                        'value' => Scheme_Color::COLOR_1,
                    ],
                    'selectors'     => [
                        '{{WRAPPER}} .premium-gallery-cats-container li a.active span' => 'color: {{VALUE}};',
                    ]
                ]
                );
        
        $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'          => 'premium_gallery_filter_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-gallery-cats-container li a.category',
                    ]
                );
        
        $this->add_control('premium_gallery_background',
                [
                    'label'         => __( 'Background', 'premium-addons-for-elementor' ),
                    'type'          => Controls_Manager::SWITCHER,
                    'default'       => 'yes'
                ]
                );
        
        $this->add_control('premium_gallery_background_color',
           [
               'label'         => __('Background Color', 'premium-addons-for-elementor'),
               'type'          => Controls_Manager::COLOR,
               'default'       => '#6ec1e4',
               'selectors'     => [
                   '{{WRAPPER}} .premium-gallery-cats-container li a.category' => 'background-color: {{VALUE}};',
               ],
               'condition' => [
                    'premium_gallery_background'    => 'yes'
                ]
           ]
       );
        
        $this->add_control('premium_gallery_background_active_color',
           [
               'label'         => __('Background Active Color', 'premium-addons-for-elementor'),
               'type'          => Controls_Manager::COLOR,
               'default'       => '#54595f',
               'selectors'     => [
                   '{{WRAPPER}} .premium-gallery-cats-container li a.active' => 'background-color: {{VALUE}};',
               ],
               'condition' => [
                    'premium_gallery_background'    => 'yes'
                ]
           ]
       );
        
        $this->add_group_control(
            Group_Control_Border::get_type(), 
                [
                    'name'              => 'premium_gallery_filter_border',
                    'selector'          => '{{WRAPPER}} .premium-gallery-cats-container li a.category',
                ]
                );

        /*Border Radius*/
        $this->add_control('premium_gallery_filter_border_radius',
                [
                    'label'             => __('Border Radius', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::SLIDER,
                    'size_units'        => ['px','em','%'],
                    'selectors'         => [
                        '{{WRAPPER}} .premium-gallery-cats-container li a.category'  => 'border-radius: {{SIZE}}{{UNIT}};',
                        ]
                    ]
                );
        
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
                [
                    'name'          => 'premium_gallery_filter_shadow',
                    'selector'      => '{{WRAPPER}} .premium-gallery-cats-container li a.category',
                ]
                );
        
        $this->add_responsive_control('premium_gallery_filter_margin',
                [
                    'label'             => __('Margin', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                    'selectors'             => [
                        '{{WRAPPER}} .premium-gallery-cats-container li a.category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        /*Front Icon Padding*/
        $this->add_responsive_control('premium_gallery_filter_padding',
                [
                    'label'             => __('Padding', 'premium-addons-for-elementor'),
                    'type'              => Controls_Manager::DIMENSIONS,
                    'size_units'        => ['px', 'em', '%'],
                'selectors'             => [
                    '{{WRAPPER}} .premium-gallery-cats-container li a.category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        
        $this->end_controls_section();
        
        $this->start_controls_section('premium_gallery_button_style_settings',
            [
                'label'         => __('Load More Button', 'premium-addons-for-elementor'),
                'tab'           => Controls_Manager::TAB_STYLE,
                'condition'     => [
                    'premium_gallery_load_more'  => 'yes',
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
                [
                    'name'          => 'premium_gallery_button_typo',
                    'scheme'        => Scheme_Typography::TYPOGRAPHY_1,
                    'selector'      => '{{WRAPPER}} .premium-gallery-load-more-btn',
            ]
        );

        $this->start_controls_tabs('premium_gallery_button_style_tabs');

        $this->start_controls_tab('premium_gallery_button_style_normal',
            [
                'label'         => __('Normal', 'premium-addons-for-elementor'),
            ]
        );

        $this->add_control('premium_gallery_button_color',
            [
                'label'         => __('Text Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-load-more-btn'  => 'color: {{VALUE}};',
                    '{{WRAPPER}} .premium-gallery-load-more-btn .premium-loader'  => 'border-color: {{VALUE}};',
                    ]
                ]
            );
        
        $this->add_control('premium_gallery_button_spin_color',
            [
                'label'         => __('Spinner Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-load-more-btn .premium-loader'  => 'border-top-color: {{VALUE}};'
                    ]
                ]
            );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'          => 'premium_gallery_button_text_shadow',
                'selector'      => '{{WRAPPER}} .premium-gallery-load-more-btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'              => 'premium_gallery_button_background',
                'types'             => [ 'classic' , 'gradient' ],
                'selector'          => '{{WRAPPER}} .premium-gallery-load-more-btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_gallery_button_border',
                'selector'      => '{{WRAPPER}} .premium-gallery-load-more-btn',
            ]
        );

        $this->add_control('premium_gallery_button_border_radius',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em' , '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-load-more-btn' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'premium_gallery_button_box_shadow',
                'selector'      => '{{WRAPPER}} .premium-gallery-load-more-btn',
            ]
        );

        $this->add_responsive_control('premium_gallery_button_margin',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-load-more-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control('premium_gallery_button_padding',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-load-more-btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab('premium_gallery_button_style_hover',
            [
                'label'         => __('Hover', 'premium-addons-for-elementor'),
            ]
        );

        $this->add_control('premium_gallery_button_hover_color',
            [
                'label'         => __('Text Hover Color', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::COLOR,
                'scheme'        => [
                    'type'  => Scheme_Color::get_type(),
                    'value' => Scheme_Color::COLOR_2,
                ],
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-load-more-btn:hover'  => 'color: {{VALUE}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name'          => 'premium_gallery_button_text_shadow_hover',
                'selector'      => '{{WRAPPER}} .premium-gallery-load-more-btn:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'          => 'premium_gallery_button_background_hover',
                'types'         => [ 'classic' , 'gradient' ],
                'selector'      => '{{WRAPPER}} .premium-gallery-load-more-btn:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(), 
            [
                'name'          => 'premium_gallery_button_border_hover',
                'selector'      => '{{WRAPPER}} .premium-gallery-load-more-btn:hover',
            ]
        );

        $this->add_control('button_border_radius_hover',
            [
                'label'         => __('Border Radius', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::SLIDER,
                'size_units'    => ['px', 'em' , '%' ],                    
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-load-more-btn:hover' => 'border-radius: {{SIZE}}{{UNIT}};'
                ]
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'          => 'premium_gallery_button_shadow_hover',
                'selector'      => '{{WRAPPER}} .premium-gallery-load-more-btn:hover',
            ]
        );

        $this->add_responsive_control('button_margin_hover',
            [
                'label'         => __('Margin', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-load-more-btn:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );

        $this->add_responsive_control('premium_gallery_button_padding_hover',
            [
                'label'         => __('Padding', 'premium-addons-for-elementor'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => ['px', 'em', '%'],
                'selectors'     => [
                    '{{WRAPPER}} .premium-gallery-load-more-btn:hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
        
    }
    
    public function filter_cats( $string ) {
		$cat_filtered = strtolower( $string );
        if( strpos( $cat_filtered, 'class' ) || strpos( $cat_filtered, 'src' ) ) {
            $cat_filtered = substr( $cat_filtered, strpos( $cat_filtered, '"' ) + 1 );
            $cat_filtered = strtok($cat_filtered, '"');
            $cat_filtered = preg_replace('/[http:.]/','',$cat_filtered);
            $cat_filtered = str_replace('/','',$cat_filtered);
        }
		$cat_filtered = preg_replace("/[\s_]/", "-", $cat_filtered);
        $cat_filtered = str_replace(',', ' ', $cat_filtered);
		return $cat_filtered;
	}
    
    
    protected function render(){
        $settings = $this->get_settings_for_display();
        $filter = $settings['premium_gallery_filter'];
        
//        $number_columns = intval ( 100 / substr( $settings['premium_gallery_column_number'], 0, strpos( $settings['premium_gallery_column_number'], '%') ) );
        
        $layout = $settings['premium_gallery_img_style'];
        $min_size = $settings['premium_gallery_min_range'].'px';
        $max_size = $settings['premium_gallery_max_range'].'px';
        
        $category_formatted = "*";

        if( 'yes' != $settings['premium_gallery_first_cat_switcher'] ) {
            $active_index  = $settings['premium_gallery_active_cat'];
            $active_category = $settings['premium_gallery_cats_content'][$active_index]['premium_gallery_img_cat'];
            $category_formatted = "." . $this->filter_cats($active_category);
        }
        
        if ( 'original' == $settings['premium_gallery_img_size_select'] ) {
            $settings['premium_gallery_img_size_select'] = 'masonry';
        } else if ( 'one_size' == $settings['premium_gallery_img_size_select'] ) {
            $settings['premium_gallery_img_size_select'] = 'fitRows';
        }
        
        $load_more = 'yes' === $settings['premium_gallery_load_more'] ? true : false;
        $minimum    = ! empty ( $settings['premium_gallery_load_minimum'] ) ? $settings['premium_gallery_load_minimum'] : 6;
        $click_number = ! empty ( $settings['premium_gallery_load_click_number'] ) ? $settings['premium_gallery_load_click_number'] : 6;
        
        
        $grid_settings = [
            'img_size'      => $settings['premium_gallery_img_size_select'],
            'filter'        => $settings['premium_gallery_filter'],
            'light_box'     => $settings['premium_gallery_light_box'],
            'overlay_gallery'=> 'yes' === $settings['premium_gallery_overlay_gallery'] ? true : false,
            'active_cat'    => $category_formatted,
            'load_more'     => $load_more,
            'minimum'       => $minimum,
            'click_images'  => $click_number
        ];

        $this->add_render_attribute( 'grid', [
                'id'            => 'premium-img-gallery-' . esc_attr( $this->get_id() ),
                'class'         => [
                    'premium-img-gallery',
                    'premium-img-gallery-' . $settings['premium_gallery_img_size_select']
                ]
            ]
        ); 
        
        $active_category_index = $settings['premium_gallery_first_cat_switcher'] == 'yes' ? $settings['premium_gallery_active_cat'] - 1 : $settings['premium_gallery_active_cat'];
        
        $is_all_active = ( 0 > $active_category_index ) ? "active" : "";
        
    ?>

    <div <?php echo $this->get_render_attribute_string('grid'); ?>>
        <?php if( $filter == 'yes' ) : ?>
            <div class="premium-img-gallery-filter">
                <ul class="premium-gallery-cats-container">
                    <?php if( 'yes' == $settings['premium_gallery_first_cat_switcher'] ) : ?>
                        <li>
                            <a href="javascript:;" class="category <?php echo $is_all_active; ?>" data-filter="*">
                                <span><?php echo $settings['premium_gallery_first_cat_label']; ?></span>
                            </a>
                        </li>
                    <?php endif;
                        foreach( $settings['premium_gallery_cats_content'] as $index => $category ) {
                            if( ! empty( $category['premium_gallery_img_cat'] ) ) {
                                $cat_filtered = $this->filter_cats($category['premium_gallery_img_cat']);
                                $cat_list_key = 'premium_grid_category_' . $index;
                                if( $active_category_index == $index ) {
                                    $this->add_render_attribute($cat_list_key,
                                        'class',
                                        'active'
                                    );
                                }

                                $this->add_render_attribute($cat_list_key,
                                    'class', [
                                        'category',
                                        'elementor-repeater-item-' . $category['_id']
                                    ]
                                );
                            ?>
                                <li>
                                    <a href="javascript:;" <?php echo $this->get_render_attribute_string($cat_list_key); ?> data-filter=".<?php echo esc_attr( $cat_filtered ); ?>"
                                       ><span><?php echo $category['premium_gallery_img_cat']; ?></span>
                                    </a>
                                </li>
                            <?php }
                        } ?>
                    </ul>
                </div>
        <?php endif; ?>
        
        <div class="premium-gallery-container js-isotope" data-settings='<?php echo wp_json_encode($grid_settings); ?>'>
            <?php if ( 'metro' === $settings['premium_gallery_img_size_select'] ) : ?>
                <div class="grid-sizer"></div>
            <?php endif;
            foreach( $settings['premium_gallery_img_content'] as $index => $image  ) :
                $alt = esc_attr( Control_Media::get_image_alt( $image['premium_gallery_img'] ) );
                
                $key = 'gallery_item_' . $index;
                
                $this->add_render_attribute($key, [
                        'class' => [
                            'premium-gallery-item',
                            'elementor-repeater-item-' . $image['_id'],
                            $this->filter_cats( $image['premium_gallery_img_category'] )
                        ]
                    ]
                );
                
                if ( 'metro' === $settings['premium_gallery_img_size_select'] ) {
                    
                    $cells = [
                        'cells'         => $image['premium_gallery_image_cell']['size'],
                        'vcells'         => $image['premium_gallery_image_vcell']['size'],
                        'cells_tablet'  => $image['premium_gallery_image_cell_tablet']['size'],
                        'vcells_tablet'  => $image['premium_gallery_image_vcell_tablet']['size'],
                        'cells_mobile'  => $image['premium_gallery_image_cell_mobile']['size'],
                        'vcells_mobile'  => $image['premium_gallery_image_vcell_mobile']['size'],
                    ];
                    
                    $this->add_render_attribute( $key, 'data-metro', wp_json_encode( $cells )  );
                }
            
            ?>
            <div <?php echo $this->get_render_attribute_string( $key ); ?>>
                <div class="pa-gallery-img <?php echo esc_attr( $layout ); ?>" onclick="">
                    <div class="pa-gallery-img-container <?php echo esc_attr( $settings['premium_gallery_img_effect'] ); ?>">
                        <?php if( $settings['premium_gallery_img_size_select'] == 'fitRows' ) :
                            $image_src = $image['premium_gallery_img'];
                            $image_src_size = Group_Control_Image_Size::get_attachment_image_src( $image_src['id'], 'thumbnail', $settings );
                            if( empty( $image_src_size ) ) : $image_src_size = $image_src['url']; else: $image_src_size = $image_src_size; endif;
                            ?>
                        <img src="<?php echo $image_src_size; ?>" class="pa-gallery-image" alt="<?php echo $alt; ?>">
                        <?php else : ?>
                        <img src="<?php echo esc_url($image['premium_gallery_img']['url']); ?>" class="pa-gallery-image" alt="<?php echo $alt; ?>">
                        <?php endif; ?>
                    </div>
                    <?php if( $layout == 'default' || $layout == 'style1' ) : ?>
                    <div class="pa-gallery-icons-wrapper">
                        <div class="pa-gallery-icons-inner-container">
                        <?php if( $image['premium_gallery_lightbox_whole'] != 'yes' && $settings['premium_gallery_light_box'] == 'yes' ) : ?> 
                            <a href="<?php echo esc_attr( $image['premium_gallery_img']['url'] ); ?>" class="pa-gallery-magnific-image" data-rel="prettyPhoto[premium-grid-<?php echo esc_attr($this->get_id()); ?>]"><span><i class="fa fa-search-plus"></i></span></a>
                        <?php endif; ?>
                            <?php if( $image['premium_gallery_link_whole'] != 'yes' && $image['premium_gallery_img_link_type'] == 'url' && !empty($image['premium_gallery_img_link']['url']) ) :
                                $icon_link = $image['premium_gallery_img_link']['url'];
                                $external = $image['premium_gallery_img_link']['is_external'] ? 'target="_blank"' : '';
                                $no_follow = $image['premium_gallery_img_link']['nofollow'] ? 'rel="nofollow"' : ''; ?>
                            <a href="<?php echo esc_attr( $icon_link ); ?>" <?php echo $external; ?><?php echo $no_follow; ?> class="pa-gallery-img-link"><span><i class="fa fa-link"></i></span></a>
                                <?php elseif( $image['premium_gallery_link_whole'] != 'yes' && $image['premium_gallery_img_link_type'] == 'link') : 
                                $icon_link = get_permalink($image['premium_gallery_img_existing']);
                                ?>
                            <a href="<?php echo esc_attr( $icon_link ); ?>" class="pa-gallery-img-link"><span><i class="fa fa-link"></i></span></a>
                            <?php endif; ?>
                            </div>
                    </div>
                    <?php if( ! empty( $image['premium_gallery_img_name'] ) || ! empty( $image['premium_gallery_img_desc'] ) ) : ?>
                        <div class="premium-gallery-caption">
                            <?php if( ! empty( $image['premium_gallery_img_name'] ) ) : ?>
                                <span class="premium-gallery-img-name"><?php echo $image['premium_gallery_img_name']; ?></span>
                            <?php endif; ?>
                            <?php if( ! empty( $image['premium_gallery_img_desc'] ) ) : ?>
                                <p class="premium-gallery-img-desc"><?php echo $image['premium_gallery_img_desc']; ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php else: ?>
                    <div class="pa-gallery-icons-caption-container">
                        <div class="pa-gallery-icons-caption-cell">
                        <?php if( $image['premium_gallery_lightbox_whole'] != 'yes' && $settings['premium_gallery_light_box'] == 'yes' ) : ?> 
                            <a href="<?php echo esc_attr( $image['premium_gallery_img']['url'] ); ?>" class="pa-gallery-magnific-image" data-rel="prettyPhoto[premium-grid-<?php echo esc_attr($this->get_id()); ?>]"><span><i class="fa fa-search-plus"></i></span></a>
                        <?php endif; ?>
                            <?php if( $image['premium_gallery_link_whole'] != 'yes' && $image['premium_gallery_img_link_type'] == 'url' && !empty($image['premium_gallery_img_link']['url']) ) :
                                $icon_link = $image['premium_gallery_img_link']['url'];
                                $external = $image['premium_gallery_img_link']['is_external'] ? 'target="_blank"' : '';
                                $no_follow = $image['premium_gallery_img_link']['nofollow'] ? 'rel="nofollow"' : ''; ?>
                        <a href="<?php echo esc_attr( $icon_link ); ?>" <?php echo $external; ?><?php echo $no_follow; ?> class="pa-gallery-img-link"><span><i class="fa fa-link"></i></span></a>
                            <?php elseif( $image['premium_gallery_link_whole'] != 'yes' && $image['premium_gallery_img_link_type'] == 'link') :
                                $icon_link = get_permalink($image['premium_gallery_img_existing']);
                                ?>
                            <a href="<?php echo esc_attr( $icon_link ); ?>" class="pa-gallery-img-link"><span><i class="fa fa-link"></i></span></a>
                            <?php endif; ?>
                            <?php if( ! empty( $image['premium_gallery_img_name'] ) || ! empty( $image['premium_gallery_img_desc'] ) ) : ?>
                            <div class="premium-gallery-caption">
                                <?php if( ! empty( $image['premium_gallery_img_name'] ) ) : ?>
                                    <span class="premium-gallery-img-name"><?php echo $image['premium_gallery_img_name']; ?></span>
                                <?php endif; ?>
                                <?php if( ! empty( $image['premium_gallery_img_desc'] ) ) : ?>
                                    <p class="premium-gallery-img-desc"><?php echo $image['premium_gallery_img_desc']; ?></p>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    <?php if( $image['premium_gallery_link_whole'] == 'yes' && $image['premium_gallery_img_link_type'] == 'url' && !empty($image['premium_gallery_img_link']['url']) ) :
                            $icon_link = $image['premium_gallery_img_link']['url'];
                            $external = $image['premium_gallery_img_link']['is_external'] ? 'target="_blank"' : '';
                            $no_follow = $image['premium_gallery_img_link']['nofollow'] ? 'rel="nofollow"' : ''; ?>
                            <a href="<?php echo esc_attr( $icon_link ); ?>" <?php echo $external; ?><?php echo $no_follow; ?> class="pa-gallery-whole-link"></a>
                    <?php elseif( $image['premium_gallery_link_whole'] == 'yes' && $image['premium_gallery_img_link_type'] == 'link' ) :
                        $icon_link = get_permalink($image['premium_gallery_img_existing']); ?>
                        <a href="<?php echo esc_attr( $icon_link ); ?>" <?php echo $external; ?><?php echo $no_follow; ?> class="pa-gallery-whole-link"></a>
                        <?php elseif( $image['premium_gallery_lightbox_whole'] == 'yes' && $settings['premium_gallery_light_box'] == 'yes' ) : ?>
                        <a href="<?php echo esc_attr( $image['premium_gallery_img']['url'] ); ?>" class="pa-gallery-whole-link" data-rel="prettyPhoto[premium-grid-<?php echo esc_attr($this->get_id()); ?>]"></a>
                    <?php endif; ?>

                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php if ( $settings['premium_gallery_load_more'] === 'yes' ) : ?>
        <div class="premium-gallery-load-more premium-gallery-btn-hidden">
            <button class="premium-gallery-load-more-btn"><span><?php echo $settings['premium_gallery_load_more_text']; ?></span><div class="premium-loader" ></div></button>
        </div>
        <?php endif; ?>
    </div>
        <?php if($settings['premium_gallery_responsive_switcher'] === 'yes') : ?>
        <style>
            @media(min-width: <?php echo $min_size; ?> ) and (max-width:<?php echo $max_size; ?>){
                #premium-img-gallery-<?php echo esc_attr($this->get_id()); ?> .premium-gallery-caption {
                    display: none;
                    }  
            }
        </style>
        <?php endif; ?>
    <?php }
}