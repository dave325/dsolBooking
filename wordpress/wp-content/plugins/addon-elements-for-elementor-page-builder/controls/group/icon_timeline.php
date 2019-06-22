<?php

namespace WTS_EAE\Controls\Group;

use Elementor\Group_Control_Base;
use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Group_Control_Icon_Timeline extends Group_Control_Base {

	protected static $fields;

	public static function get_type() {
		return 'eae-icon-timeline';
	}

	protected function init_fields() {
		$controls = [];


		$controls['icon_type'] = [
			'type'        => Controls_Manager::CHOOSE,
			'label'       => __( 'Type', 'bpel' ),
			'default'     => 'icon',
			'options'     => [
				'icon'  => [
					'title' => __( 'Fontawesome Icon', 'bpel' ),
					'icon'  => 'fa fa-font-awesome',
				],
				'image' => [
					'title' => __( 'Custom Icons', 'bpel' ),
					'icon'  => 'fa fa-image',
				],
				'text'  => [
					'title' => __( 'Text', 'bpel' ),
					'icon'  => 'fa fa-font',
				],
			],
			'label_block' => false,
			'toggle'      => false,
			'condition'   => [
				'eae_icon!' => ''
			]
		];


		$controls['icon'] = [
			'label'     => __( 'Icon', 'Icon Control', 'bpel' ),
			'type'      => Controls_Manager::ICON,
			'default'   => 'fa fa-star',
			'condition' => [
				'eae_icon!' => '',
				'icon_type'  => 'icon'
			],
		];

		$controls['image'] = [
			'label'       => __( 'Custom Icon', 'Icon Control', 'bpel' ),
			'type'        => Controls_Manager::MEDIA,
			'label_block' => false,
			'condition'   => [
				'eae_icon!' => '',
				'icon_type'  => 'image'
			],
		];

		$controls['text'] = [
			'label'       => __( 'Text', 'Icon Control', 'bpel' ),
			'type'        => Controls_Manager::TEXT,
			'label_block' => false,
			'condition'   => [
				'eae_icon!' => '',
				'icon_type'  => 'text'
			],
		];

		/*$controls['view'] = [
			'label'     => __( 'View', 'Icon Control', 'bpel' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				//'default' => __( 'Default', 'bpel' ),
				'stacked' => __( 'Stacked', 'bpel' ),
				//'framed'  => __( 'Framed', 'bpel' ),
			],
			'default'   => 'stacked',
			'condition' => [
				'eae_icon!' => '',
				'icon!'      => ''
			],
		];*/

		$controls['shape'] = [
			'label'     => __( 'Shape', 'Icon Control', 'bpel' ),
			'type'      => Controls_Manager::SELECT,
			'options'   => [
				'circle' => __( 'Circle', 'bpel' ),
				'square' => __( 'Square', 'bpel' ),
			],
			'default'   => 'circle',
			'condition' => [
				'eae_icon!' => '',
				'icon!'      => ''
			],
		];

		return $controls;
	}

	protected function get_default_options() {
		return [
			'popover' => [
				'starter_title' => _x( 'Icon', 'Icon Control', 'bpel' ),
				'starter_name'  => 'eae_icon',
				'starter_value' => 'yes',
			]
		];
	}
}
