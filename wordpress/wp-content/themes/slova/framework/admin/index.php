<?php
require_once(ABS_PATH_ADMIN .'/tgm-plugin-activation/plugin-options.php');
require_once (ABS_PATH_ADMIN.'/sample/importer.php');
add_action( 'wp_ajax_sample', 'prefix_ajax_sample' );
function prefix_ajax_sample(){
    installSample();
}
