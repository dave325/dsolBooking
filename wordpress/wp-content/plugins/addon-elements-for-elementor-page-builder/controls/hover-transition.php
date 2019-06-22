<?php

namespace WTS_EAE\Controls;

use Elementor\Base_Data_Control;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Hover_Transition extends Base_Data_Control {

	private static $_hover_transition;

	public function get_type() {
		return 'EAE_HOVER_TRANSITION';
	}

	public static function get_transitions() {
		if ( is_null( self::$_hover_transition ) ) {
			self::$_hover_transition = [
				'2D Transitions'  => [
					'hvr-grow'                   => __( 'Grow', 'bpel' ),
					'hvr-shrink'                 => __( 'Shrink', 'bpel' ),
					'hvr-pulse'                  => __( 'Pulse', 'bpel' ),
					'hvr-pulse-grow'             => __( 'Pulse Grow', 'bpel' ),
					'hvr-pulse-shrink'           => __( 'Pulse Shrink', 'bpel' ),
					'hvr-push'                   => __( 'Push', 'bpel' ),
					'hvr-pop'                    => __( 'Pop', 'bpel' ),
					'hvr-bounce-in'              => __( 'Bounce In', 'bpel' ),
					'hvr-bounce-out'             => __( 'Bounce Out', 'bpel' ),
					'hvr-rotate'                 => __( 'Rotate', 'bpel' ),
					'hvr-grow-rotate'            => __( 'Icon Shrink', 'bpel' ),
					'hvr-float'                  => __( 'Float', 'bpel' ),
					'hvr-sink'                   => __( 'Sink', 'bpel' ),
					'hvr-bob'                    => __( 'Bob', 'bpel' ),
					'hvr-hang'                   => __( 'Hang', 'bpel' ),
					'hvr-skew'                   => __( 'Skew', 'bpel' ),
					'hvr-skew-forward'           => __( 'Skew Forward', 'bpel' ),
					'hvr-skew-backward'          => __( 'Skew Backward', 'bpel' ),
					'hvr-wobble-horizontal'      => __( 'Wobble Horizontal', 'bpel' ),
					'hvr-wobble-vertical'        => __( 'Wobble Vertical', 'bpel' ),
					'hvr-wobble-to-bottom-right' => __( 'Wobble To Bottom Right', 'bpel' ),
					'hvr-wobble-to-top-right'    => __( 'Wobble To Top Right', 'bpel' ),
					'hvr-wobble-top'             => __( 'Wobble Top', 'bpel' ),
					'hvr-wobble-bottom'          => __( 'Wobble Bottom', 'bpel' ),
					'hvr-wobble-skew'            => __( 'Wobble Skew', 'bpel' ),
					'hvr-buzz'                   => __( 'Buzz', 'bpel' ),
					'hvr-buzz-out'               => __( 'Buzz Out', 'bpel' ),
					'hvr-forward'                => __( 'Forward', 'bpel' ),
					'hvr-backward'               => __( 'Backward', 'bpel' ),
				],
				'Background'      => [
					'hvr-fade'                   => __( 'Fade', 'bpel' ),
					'hvr-back-pulse'             => __( 'Back Pulse', 'bpel' ),
					'hvr-sweep-to-right'         => __( 'Sweep To Right', 'bpel' ),
					'hvr-sweep-to-left'          => __( 'Sweep To Left', 'bpel' ),
					'hvr-sweep-to-bottom'        => __( 'Sweep To Bottom', 'bpel' ),
					'hvr-sweep-to-top'           => __( 'Sweep To Top', 'bpel' ),
					'hvr-bounce-to-right'        => __( 'Bounce To Right', 'bpel' ),
					'hvr-bounce-to-left'         => __( 'Bounce To Left', 'bpel' ),
					'hvr-bounce-to-bottom'       => __( 'Bounce To Bottom', 'bpel' ),
					'hvr-bounce-to-top'          => __( 'Bounce To Top', 'bpel' ),
					'hvr-radial-out'             => __( 'Radial Out', 'bpel' ),
					'hvr-radial-in'              => __( 'Radial In', 'bpel' ),
					'hvr-rectangle-in'           => __( 'Rectangle In', 'bpel' ),
					'hvr-rectangle-out'          => __( 'Rectangle Out', 'bpel' ),
					'hvr-shutter-in-horizontal'  => __( 'Shutter In Horizontal', 'bpel' ),
					'hvr-shutter-out-horizontal' => __( 'Shutter Out Horizontal', 'bpel' ),
					'hvr-shutter-in-vertical'    => __( 'Shutter In Vertical', 'bpel' ),
					'hvr-shutter-out-vertical'   => __( 'Shutter Out Vertical', 'bpel' ),
				],
				'Icon'            => [
					'hvr-icon-back'              => __( 'Icon Back', 'bpel' ),
					'hvr-icon-forward'           => __( 'Icon Forward', 'bpel' ),
					'hvr-icon-down'              => __( 'Icon Down', 'bpel' ),
					'hvr-icon-up'                => __( 'Icon Up', 'bpel' ),
					'hvr-icon-spin'              => __( 'Icon Spin', 'bpel' ),
					'hvr-icon-drop'              => __( 'Icon Drop', 'bpel' ),
					'hvr-icon-fade'              => __( 'Icon Fade', 'bpel' ),
					'hvr-icon-float-away'        => __( 'Icon Float Away', 'bpel' ),
					'hvr-icon-sink-away'         => __( 'Icon Sink Away', 'bpel' ),
					'hvr-icon-grow'              => __( 'Icon Grow', 'bpel' ),
					'hvr-icon-shrink'            => __( 'Icon Shrink', 'bpel' ),
					'hvr-icon-pulse'             => __( 'Icon Pulse', 'bpel' ),
					'hvr-icon-pulse-grow'        => __( 'Icon Pulse Grow', 'bpel' ),
					'hvr-icon-pulse-shrink'      => __( 'Icon Pulse Shrink', 'bpel' ),
					'hvr-icon-push'              => __( 'Icon Push', 'bpel' ),
					'hvr-icon-pop'               => __( 'Icon Pop', 'bpel' ),
					'hvr-icon-bounce'            => __( 'Icon Bounce', 'bpel' ),
					'hvr-icon-rotate'            => __( 'Icon Rotate', 'bpel' ),
					'hvr-icon-grow-rotate'       => __( 'Icon Grow Rotate', 'bpel' ),
					'hvr-icon-float'             => __( 'Icon Float', 'bpel' ),
					'hvr-icon-sink'              => __( 'Icon Sink', 'bpel' ),
					'hvr-icon-bob'               => __( 'Icon Bob', 'bpel' ),
					'hvr-icon-hang'              => __( 'Icon Hang', 'bpel' ),
					'hvr-icon-wobble-horizontal' => __( 'Icon Wobble Horizontal', 'bpel' ),
					'hvr-icon-wobble-vertical'   => __( 'Icon Wobble Vertical', 'bpel' ),
					'hvr-icon-buzz'              => __( 'Icon Buzz', 'bpel' ),
					'hvr-icon-buzz-out'          => __( 'Icon Buzz Out', 'bpel' ),
				],
				'Border'          => [
					'hvr-border-fade'           => __( 'Border Fade', 'bpel' ),
					'hvr-hollow'                => __( 'Hollow', 'bpel' ),
					'hvr-trim'                  => __( 'Trim', 'bpel' ),
					'hvr-ripple-out'            => __( 'Ripple Out', 'bpel' ),
					'hvr-ripple-in'             => __( 'Ripple In', 'bpel' ),
					'hvr-outline-out'           => __( 'Outline Out', 'bpel' ),
					'hvr-outline-in'            => __( 'Outline In', 'bpel' ),
					'hvr-round-corners'         => __( 'Round Corners', 'bpel' ),
					'hvr-underline-from-left'   => __( 'Underline From Left', 'bpel' ),
					'hvr-underline-from-center' => __( 'Underline From Center', 'bpel' ),
					'hvr-underline-from-right'  => __( 'Underline From Right', 'bpel' ),
					'hvr-reveal'                => __( 'Reveal', 'bpel' ),
					'hvr-underline-reveal'      => __( 'Underline Reveal', 'bpel' ),
					'hvr-overline-reveal'       => __( 'Overline Reveal', 'bpel' ),
					'hvr-overline-from-left'    => __( 'Overline From Left', 'bpel' ),
					'hvr-overline-from-center'  => __( 'Overline From Center', 'bpel' ),
					'hvr-overline-from-right'   => __( 'Overline From Right', 'bpel' ),
				],
				'Shadow And Glow' => [
					'hvr-shadow'            => __( 'Shadow', 'bpel' ),
					'hvr-grow-shadow'       => __( 'Grow Shadow', 'bpel' ),
					'hvr-float-shadow'      => __( 'Trim', 'bpel' ),
					'hvr-ripple-out'        => __( 'Float Shadow', 'bpel' ),
					'hvr-glow'              => __( 'Glow', 'bpel' ),
					'hvr-shadow-radial'     => __( 'Shadow Radial', 'bpel' ),
					'hvr-box-shadow-outset' => __( 'Box Shadow Outset', 'bpel' ),
					'hvr-box-shadow-inset'  => __( 'Box Shadow Inset', 'bpel' ),
				]

			];
		}

		return self::$_hover_transition;
	}

	public function enqueue() {

		wp_register_script( 'eae-control', EAE_URL . 'assets/js/editor.js', [ 'jquery' ], '1.0.0' );
		wp_enqueue_script( 'eae-control' );
	}


	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
        <div class="elementor-control-field">
            <label for="<?php echo $control_uid; ?>" class="elementor-control-title">{{{ data.label }}}</label>
            <div class="elementor-control-input-wrapper">
                <select id="<?php echo $control_uid; ?>" data-setting="{{ data.name }}">
                    <option value=""><?php echo __( 'None', 'bpel' ); ?></option>
					<?php foreach ( self::get_transitions() as $transitions_group_name => $transitions_group ) : ?>
                        <optgroup label="<?php echo $transitions_group_name; ?>">
							<?php foreach ( $transitions_group as $transition_name => $transition_title ) : ?>
                                <option value="<?php echo $transition_name; ?>"><?php echo $transition_title; ?></option>
							<?php endforeach; ?>
                        </optgroup>
					<?php endforeach; ?>
                </select>
            </div>
        </div>
        <# if ( data.description ) { #>
        <div class="elementor-control-field-description">{{{ data.description }}}</div>
        <# } #>
		<?php
	}
}
