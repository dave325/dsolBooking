<?php
function ro_theme_autoCompileLess($inputFile, $outputFile) {
    require_once ( ABS_PATH_FR . '/inc/lessc.inc.php' );
	global $tb_options;
    $less = new lessc();
    $less->setFormatter("classic");
    $less->setPreserveComments(true);
	
	/*Styling Options*/
	$tb_primary_color = $tb_options['tb_primary_color'];
	$tb_secondary_color = $tb_options['tb_secondary_color'];
	
    $variables = array(
		"tb_primary_color" => $tb_primary_color,
		"tb_secondary_color" => $tb_secondary_color,
    );
    $less->setVariables($variables);
    $cacheFile = $inputFile.".cache";
    if (file_exists($cacheFile)) {
            $cache = unserialize(file_get_contents($cacheFile));
    } else {
            $cache = $inputFile;
    }
    $newCache = $less->cachedCompile($inputFile);
    if (!is_array($cache) || $newCache["updated"] > $cache["updated"]) {
            file_put_contents($cacheFile, serialize($newCache));
            file_put_contents($outputFile, $newCache['compiled']);
    }
}
function addLessStyle() {
    try {
		$inputFile = ABS_PATH.'/assets/css/less/style.less';
		$outputFile = ABS_PATH.'/style.css';
		ro_theme_autoCompileLess($inputFile, $outputFile);
    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }
}
add_action('wp_enqueue_scripts', 'addLessStyle');
/* End less*/