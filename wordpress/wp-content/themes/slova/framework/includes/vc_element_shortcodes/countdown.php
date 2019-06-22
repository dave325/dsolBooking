<?php
vc_map(array(
	"name" => __("Countdown", 'slova'),
	"base" => "countdown",
	"class" => "ro_countdown",
	"category" => __('Slova', 'slova'),
	"icon" => "tb-icon-for-vc",
	"params" => array(
		array(
			"type" => "textfield",
			"class" => "",
			"heading" => __("Date End", 'slova'),
			"param_name" => "date_end",
			"value" => "",
			"description" => __("Please, Enter date end in this element. Ex: +15d +8h +30m +15s", 'slova')
		),
	)
));
