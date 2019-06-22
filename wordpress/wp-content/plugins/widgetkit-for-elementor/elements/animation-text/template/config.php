<?php

use Elementor\Group_Control_Border;
use Elementor\Widget_Base;
use Elementor\Repeater;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor WidgetKit animation text
 *
 * Elementor widget for WidgetKit animation text
 *
 * @since 1.0.0
 */
class wkfe_animation_text extends Widget_Base {

	public function get_name() {
		return 'widgetkit-for-elementor-animation-text';
	}

	public function get_title() {
		return esc_html__( 'Animation Text', 'widgetkit-for-elementor' );
	}

	public function get_icon() {
		return 'eicon-animation-text';
	}

	public function get_categories() {
		return [ 'widgetkit_elementor' ];
	}

	/**
	 * A list of scripts that the widgets is depended in
	 * @since 1.3.0
	 **/
	public function get_script_depends() {
		return [ 'animation-text' ];
	}

	protected function _register_controls() {

	// Content options Start
	$this->start_controls_section(
		'section_text_content',
			[
				'label' => esc_html__( 'Text Settings', 'widgetkit-for-elementor' ),
			]
		);

		$this->add_control(
		    'prefix_title',
		      	[
		          'label' => esc_html__( 'Prefix Title', 'widgetkit-for-elementor' ),
		          'type'  => Controls_Manager::TEXTAREA,
		          'default' => esc_html__( 'We are', 'widgetkit-for-elementor' ),
		    	]
	    );

        $repeater = new Repeater();


        $repeater->add_control(
            'animate_text',
            [
                'label'   => esc_html__( 'Animate Text', 'widgetkit-for-elementor' ),
                'type'    => Controls_Manager::TEXT,
                'default' => esc_html__( 'Professional', 'widgetkit-for-elementor' ),
            ]
        );

        $this->add_control(
            'animate_text_list',
            [
                'type'    => Controls_Manager::REPEATER,
                'fields'  => array_values( $repeater->get_controls() ),
                'default' => [
                    [
                        'animate_text' => esc_html__( 'Professional', 'widgetkit-for-elementor' ),
                    ],
                    [
                        'animate_text' => esc_html__( 'Developer', 'widgetkit-for-elementor' ),
                    ],
                    [
                        'animate_text' => esc_html__( 'Designer', 'widgetkit-for-elementor' ),
                    ],
                ],
                'title_field' => '{{{ animate_text }}}',
            ]
        );



        $this->add_control(
		    'suffix_title',
		      	[
		          'label' => esc_html__( 'Suffix Title', 'widgetkit-for-elementor' ),
		          'type'  => Controls_Manager::TEXTAREA,
		          'default' => esc_html__( '', 'widgetkit-for-elementor' ),
		    	]
	    );
	$this->end_controls_section();
	// Content options End


		$this->start_controls_section(
		'section_content_layout',
		[
			'label' => esc_html__( 'Animation Options', 'widgetkit-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);


        $this->add_control(
			'choose_animation_text',
				[
					'label'     => esc_html__( 'Choose Animation', 'widgetkit-for-elementor' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'rotate',
					'options'   => [
						'rotate'  => esc_html__('Rotate', 'widgetkit-for-elementor'),
						'clip'    => esc_html__( 'Clip', 'widgetkit-for-elementor' ),
						'loading_bar'    => esc_html__( 'Loading', 'widgetkit-for-elementor' ),
						'push'    => esc_html__( 'Push', 'widgetkit-for-elementor' ),
					],
				]
		);



	$this->end_controls_section();


	$this->start_controls_section(
		'section_animate_title',
		[
			'label' => esc_html__( 'Title', 'widgetkit-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);

     	$this->add_control(
			'animaton_title_color',
			[
				'label'     => esc_html__( 'Color', 'widgetkit-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#333',
				'selectors' => [
					'{{WRAPPER}} .animation-text .text-slide .cd-headline' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
				[
					'name'     => 'animation_title_typography',
					'label'    => esc_html__( 'Title Typography', 'widgetkit-for-elementor' ),
					'scheme'   => Scheme_Typography::TYPOGRAPHY_4,
					'selector' => '{{WRAPPER}} .animation-text .text-slide .cd-headline',
				]
		);

		$this->add_control(
			'animaton_bold_color',
			[
				'label'     => esc_html__( 'Animat Text Color', 'widgetkit-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '#ed485f',
				'selectors' => [
					'{{WRAPPER}} .animation-text .text-slide .cd-headline b' => 'color: {{VALUE}};',
					'{{WRAPPER}} .cd-headline.loading-bar .cd-words-wrapper:after' => 'background: {{VALUE}};',
				],
			]
		);


		$this->add_responsive_control(
			'text_animation_align',
			[
				'label' => esc_html__( 'Alignment', 'widgetkit-for-elementor' ),
				'type'  => Controls_Manager::CHOOSE,
				'default'   => 'left',
				'options' => [
					'left'    => [
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
					'justify' => [
						'title' => esc_html__( 'Justified', 'widgetkit-for-elementor' ),
						'icon'  => 'fa fa-align-justify',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .animation-text .text-slide .cd-headline' => 'text-align: {{VALUE}};',
				],
			]
		);


	$this->end_controls_section();

	}

	protected function render() {
		require WKFE_PATH . '/elements/animation-text/template/view.php';
	}


}
