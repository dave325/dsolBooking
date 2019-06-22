<?php

namespace WTS_EAE\Modules\ModalPopup;

use WTS_EAE\Base\Module_Base;

class Module extends Module_Base {

	public function get_widgets() {
		return [
			'ModalPopup',
		];
	}

	public function get_name() {
		return 'eae-modalpopup';
	}

}