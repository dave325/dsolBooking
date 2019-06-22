<?php
namespace Elementor;


if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.


class Widget_Eael_Creative_Button extends Widget_Base {
	

	public function get_name() {
		return 'eael-creative-button';
	}

	public function get_title() {
		return esc_html__( 'EA Creative Button', 'essential-addons-elementor' );
	}

	public function get_icon() {
		return 'eicon-button';
	}

   public function get_categories() {
		return [ 'essential-addons-elementor' ];
	}


	protected function _register_controls() {

		// Content Controls
		$this->start_controls_section(
			'eael_section_creative_button_content',
			[
				'label' => esc_html__( 'Button Content', 'essential-addons-elementor' )
			]
		);

			$this->start_controls_tabs( 'eael_creative_button_content_separation' );

				$this->start_controls_tab(
					'button_primary_settings',
					[
						'label'	=> __( 'Primary', 'essential-addons-elementor' ),
					]
				);

				$this->add_control(
					'creative_button_text',
					[
						'label' => __( 'Button Text', 'essential-addons-elementor' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => 'Click Me!',
						'placeholder' => __( 'Enter button text', 'essential-addons-elementor' ),
						'title' => __( 'Enter button text here', 'essential-addons-elementor' ),
					]
				);

				$this->add_control(
					'eael_creative_button_icon',
					[
						'label' => esc_html__( 'Icon', 'essential-addons-elementor' ),
						'type' => Controls_Manager::ICON,
					]
				);
		
				$this->add_control(
					'eael_creative_button_icon_alignment',
					[
						'label' => esc_html__( 'Icon Position', 'essential-addons-elementor' ),
						'type' => Controls_Manager::SELECT,
						'default' => 'left',
						'options' => [
							'left' => esc_html__( 'Before', 'essential-addons-elementor' ),
							'right' => esc_html__( 'After', 'essential-addons-elementor' ),
						],
						'condition' => [
							'eael_creative_button_icon!' => '',
						],
					]
				);
				
		
				$this->add_control(
					'eael_creative_button_icon_indent',
					[
						'label' => esc_html__( 'Icon Spacing', 'essential-addons-elementor' ),
						'type' => Controls_Manager::SLIDER,
						'range' => [
							'px' => [
								'max' => 60,
							],
						],
						'condition' => [
							'eael_creative_button_icon!' => '',
						],
						'selectors' => [
							'{{WRAPPER}} .eael-creative-button-icon-right' => 'margin-left: {{SIZE}}px;',
							'{{WRAPPER}} .eael-creative-button-icon-left' => 'margin-right: {{SIZE}}px;',
							'{{WRAPPER}} .eael-creative-button--shikoba i' => 'left: -{{SIZE}}px;',
						],
					]
				);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'button_secondary_settings',
					[
						'label'	=> __( 'Secondary', 'essential-addons-elementor' ),
					]
				);

				$this->add_control(
					'creative_button_secondary_text',
					[
						'label' => __( 'Button Secondary Text', 'essential-addons-elementor' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'default' => 'Go!',
						'placeholder' => __( 'Enter button secondary text', 'essential-addons-elementor' ),
						'title' => __( 'Enter button secondary text here', 'essential-addons-elementor' ),
					]
				);

				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->add_control(
			'creative_button_link_url',
			[
				'label' => esc_html__( 'Link URL', 'essential-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'default' => [
        			'url' => '#',
        			'is_external' => '',
     			],
     			'show_external' => true,
			]
		);

		$this->end_controls_section();
		


  		// Style Controls
		$this->start_controls_section(
			'eael_section_creative_button_settings',
			[
				'label' => esc_html__( 'Button Effects &amp; Styles', 'essential-addons-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);

		$this->add_control(
			'creative_button_effect',
			[
				'label' => esc_html__( 'Set Button Effect', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'eael-creative-button--default',
				'options' => [
					'eael-creative-button--default' 	=> esc_html__( 'Default', 	'essential-addons-elementor' ),
					'eael-creative-button--winona' 		=> esc_html__( 'Winona', 	'essential-addons-elementor' ),
					'eael-creative-button--ujarak' 		=> esc_html__( 'Ujarak', 	'essential-addons-elementor' ),
					'eael-creative-button--wayra' 		=> esc_html__( 'Wayra', 	'essential-addons-elementor' ),
					'eael-creative-button--tamaya' 		=> esc_html__( 'Tamaya', 	'essential-addons-elementor' ),
					'eael-creative-button--rayen' 		=> esc_html__( 'Rayen', 	'essential-addons-elementor' ),
					'eael-creative-button--pipaluk' 	=> esc_html__( 'Pipaluk', 	'essential-addons-elementor' ),
					'eael-creative-button--moema' 		=> esc_html__( 'Moema', 	'essential-addons-elementor' ),
					'eael-creative-button--wave' 		=> esc_html__( 'Wave', 		'essential-addons-elementor' ),
					'eael-creative-button--aylen' 		=> esc_html__( 'Aylen', 	'essential-addons-elementor' ),
					'eael-creative-button--saqui' 		=> esc_html__( 'Saqui', 	'essential-addons-elementor' ),
					'eael-creative-button--wapasha' 	=> esc_html__( 'Wapasha', 	'essential-addons-elementor' ),
					'eael-creative-button--nuka' 		=> esc_html__( 'Nuka', 		'essential-addons-elementor' ),
					'eael-creative-button--antiman' 	=> esc_html__( 'Antiman', 	'essential-addons-elementor' ),
					'eael-creative-button--quidel' 		=> esc_html__( 'Quidel', 	'essential-addons-elementor' ),
					'eael-creative-button--shikoba' 	=> esc_html__( 'Shikoba', 	'essential-addons-elementor' ),
				],
			]
		);

		$this->start_controls_tabs('eael_creative_button_typography_separation');

			$this->start_controls_tab('button_primary_typography', [
				'label'	=> __( 'Primary', 'essential-addons-elementor')
			]);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
				'name' => 'eael_creative_button_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .eael-creative-button',
				]
			);

			$this->end_controls_tab();

			$this->start_controls_tab('button_secondary_typography', [
				'label'	=> __( 'Secondary', 'essential-addons-elementor')
			]);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
				'name' => 'eael_creative_button_secondary_typography',
					'scheme' => Scheme_Typography::TYPOGRAPHY_1,
					'selector' => '{{WRAPPER}} .eael-creative-button--rayen::before',
				]
			);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'eael_creative_button_alignment',
			[
				'label' => esc_html__( 'Button Alignment', 'essential-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => true,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'essential-addons-elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-creative-button-wrapper' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'eael_creative_button_width',
			[
				'label' => esc_html__( 'Width', 'essential-addons-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-creative-button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'eael_creative_button_padding',
			[
				'label' => esc_html__( 'Button Padding', 'essential-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .eael-creative-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--winona::after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--winona > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--tamaya::before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--rayen::before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--rayen > span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--saqui::after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->start_controls_tabs( 'eael_creative_button_tabs' );

		$this->start_controls_tab( 'normal', [ 'label' => esc_html__( 'Normal', 'essential-addons-elementor' ) ] );

		$this->add_control(
			'eael_creative_button_text_color',
			[
				'label'		=> esc_html__( 'Text Color', 'essential-addons-elementor' ),
				'type'		=> Controls_Manager::COLOR,
				'default'	=> '#ffffff',
				'selectors'	=> [
					'{{WRAPPER}} .eael-creative-button' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--tamaya::before' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--tamaya::after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_creative_button_background_color',
			[
				'label'		=> esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type'		=> Controls_Manager::COLOR,
				'default'	=> '#333333',
				'selectors' => [
					'{{WRAPPER}} .eael-creative-button' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--ujarak:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--wayra:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--tamaya::before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--tamaya::after' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--rayen:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--pipaluk::after' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--wave:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--aylen::before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--nuka::before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--nuka::after' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--antiman::after' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--quidel::after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'		=> 'eael_creative_button_border',
				'selector'	=> '{{WRAPPER}} .eael-creative-button',
			]
		);
		
		$this->add_control(
			'eael_creative_button_border_radius',
			[
				'label'		=> esc_html__( 'Border Radius', 'essential-addons-elementor' ),
				'type'		=> Controls_Manager::SLIDER,
				'range'	=> [
					'px' => [
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .eael-creative-button' => 'border-radius: {{SIZE}}px;',
					'{{WRAPPER}} .eael-creative-button::before' => 'border-radius: {{SIZE}}px;',
					'{{WRAPPER}} .eael-creative-button::after' => 'border-radius: {{SIZE}}px;',
				],
			]
		);
		
		$this->end_controls_tab();
		

		$this->start_controls_tab( 'eael_creative_button_hover', [ 'label' => esc_html__( 'Hover', 'essential-addons-elementor' ) ] );

		$this->add_control(
			'eael_creative_button_hover_text_color',
			[
				'label' => esc_html__( 'Text Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .eael-creative-button:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--winona::after' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--saqui::after' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_creative_button_hover_background_color',
			[
				'label' => esc_html__( 'Background Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#f54',
				'selectors' => [
					'{{WRAPPER}} .eael-creative-button:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--ujarak::before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--wayra:hover::before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--tamaya:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--rayen::before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--wave::before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--wave:hover::before' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--aylen::after' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--saqui:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--nuka:hover::after' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--quidel:hover::after' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'eael_creative_button_hover_border_color',
			[
				'label' => esc_html__( 'Border Color', 'essential-addons-elementor' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .eael-creative-button:hover' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--wapasha::before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--antiman::before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--pipaluk::before' => 'border-color: {{VALUE}};',
					'{{WRAPPER}} .eael-creative-button.eael-creative-button--quidel::before'  => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		
		
		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .eael-creative-button',
			]
		);		
		
		$this->end_controls_section();


		
		
	}

	protected function render() {
		$settings = $this->get_settings();
		
		$this->add_render_attribute( 'eael_creative_button', [
			'class'	=> [ 'eael-creative-button', esc_attr($settings['creative_button_effect'] ) ],
			'href'	=> esc_attr($settings['creative_button_link_url']['url'] ),
		]);

		if( $settings['creative_button_link_url']['is_external'] ) {
			$this->add_render_attribute( 'eael_creative_button', 'target', '_blank' );
		}
		
		if( $settings['creative_button_link_url']['nofollow'] ) {
			$this->add_render_attribute( 'eael_creative_button', 'rel', 'nofollow' );
		}

		$this->add_render_attribute( 'eael_creative_button', 'data-text', esc_attr($settings['creative_button_secondary_text'] ));
	?>
	<div class="eael-creative-button-wrapper">
		<a <?php echo $this->get_render_attribute_string( 'eael_creative_button' ); ?>>
			<span>
				<?php if ( ! empty( $settings['eael_creative_button_icon'] ) && $settings['eael_creative_button_icon_alignment'] == 'left' ) : ?>
					<i class="<?php echo esc_attr($settings['eael_creative_button_icon'] ); ?> eael-creative-button-icon-left" aria-hidden="true"></i> 
				<?php endif; ?>

				<?php echo  $settings['creative_button_text'];?>

				<?php if ( ! empty( $settings['eael_creative_button_icon'] ) && $settings['eael_creative_button_icon_alignment'] == 'right' ) : ?>
					<i class="<?php echo esc_attr($settings['eael_creative_button_icon'] ); ?> eael-creative-button-icon-right" aria-hidden="true"></i> 
				<?php endif; ?>
			</span>
		</a>
	</div>
	<?php
	
	}

	protected function content_template() {}
}


Plugin::instance()->widgets_manager->register_widget_type( new Widget_Eael_Creative_Button() );