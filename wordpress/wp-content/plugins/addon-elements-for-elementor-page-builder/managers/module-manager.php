<?php
namespace WTS_EAE\Managers;
class Module_Manager {
	protected $modules = [];
	public function __construct() {
		$this->modules = [
			'timeline',
			'info-circle',
		//	'evergreen-timer',
			'comparison-table',
			'image-compare',
			'animated-text',
			'dual-button',
			'particles',
			'modal-popup',
			'progress-bar',
			'flip-box',
			'split-text',
			'gmap',
			'text-separator',
			'price-table',
			'twitter',
			'bg-slider',
			'animated-gradient',

			//'testimonial-slider',
			'post-list',
			'shape-separator',
		];
		// Todo:: apply filter for modules that depends on third party plugins
		foreach ( $this->modules as $module_name ) {
			$class_name = str_replace( '-', ' ', $module_name );
			$class_name = str_replace( ' ', '', ucwords( $class_name ) );
			$class_name = 'WTS_EAE' . '\\Modules\\' . $class_name . '\Module';

			$this->modules[ $module_name ] = $class_name::instance();


		}
	}
}