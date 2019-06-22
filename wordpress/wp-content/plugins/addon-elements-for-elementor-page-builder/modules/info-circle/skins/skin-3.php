<?php

namespace WTS_EAE\Modules\InfoCircle\Skins;

use Elementor\Widget_Base;
use Elementor\Group_Control_Border;

class Skin_3 extends Skin_Base {

	protected function _register_controls_actions() {
		parent::_register_controls_actions(); // TODO: Change the autogenerated stub
		add_action( 'elementor/element/eae-info-circle/skin3_icon_global_style/after_section_end', [ $this, 'extra_controls_update' ] );
		add_action( 'elementor/element/eae-info-circle/skin3_content_styling/after_section_start', [ $this, 'control_add' ] );
	}

	public function get_id() {
		return 'skin3';
	}

	public function get_title() {
		return __( 'Skin 3', 'wts-eae' );
	}

	function control_add() {
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'           => 'item_border',
				'label'          => __( 'Border', 'wts-eae' ),
				'fields_options' => [
					'border' => [
						'default' => 'dashed'
					],
					'width'  => [
						'default' => [
							'top'    => 4,
							'right'  => 4,
							'bottom' => 4,
							'left'   => 4,
						],
					],
					'color'  => [
						'default' => '#4054b2'
					],
				],
				'selector'       =>
					'{{WRAPPER}} .eae-info-circle:before',
			]
		);
	}
	function extra_controls_update() {
		$this->update_control(
			'global_icon',
			[
				'default' => 'fa fa-flash'
			]
		);
		$this->update_control(
			'item_icon_icon_padding',
			[
				'default' => [
					'size' => 15,
				],
			]
		);
		$this->update_control(
			'item_icon_icon_size',
			[
				'default' => [
					'size' => 30,
				],
			]
		);
		$this->update_control(
			'item_icon_icon_primary_color',
			[
				'default' => '#4054b2'
			]
		);
		$this->update_control(
			'item_icon_icon_secondary_color',
			[
				'default' => '#fff'
			]
		);
		$this->update_control(
			'item_icon_icon_focus_primary_color',
			[
				'default' => '#75cdde'
			]
		);
		$this->update_control(
			'item_icon_icon_focus_secondary_color',
			[
				'default' => '#fff'
			]
		);
		$this->update_control(
			'item_icon_border_style',
			[
				'default' => 'dashed'
			]
		);
		$this->update_control(
			'item_icon_border_width',
			[
				'default' => [
					'top'    => 4,
					'right'  => 4,
					'bottom' => 4,
					'left'   => 4,
				],
			]
		);
	}
	public function register_common_controls( Widget_Base $widget ) {
		$this->parent = $widget;
		//$this->bpel_infocircle_content_section( $widget );
	}
	public function register_style_controls(){
		$this->eae_infocircle_style_section();
	}
	public function render() {
		$this->common_render();
	}
}