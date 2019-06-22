<?php
/* Main function for importing dummy data */
if ( ! function_exists( 'installSample' ) ) {
	function installSample(){
		ob_start();
		$msg = '<br/>';
		if ( !defined('WP_LOAD_IMPORTERS') ) define('WP_LOAD_IMPORTERS', true);
			require_once ABSPATH . 'wp-admin/includes/import.php';
			$importer_error = false;
			
		if ( !class_exists( 'WP_Importer' ) ) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			file_exists( $class_wp_importer ) ? require_once($class_wp_importer):$importer_error = true;
		}
		if ( !class_exists( 'WP_Import' ) ) {
			$class_wp_import = ABS_PATH_ADMIN . '/sample/wordpress-importer/wordpress-importer.php';
			file_exists( $class_wp_import ) ? require_once($class_wp_import): $importer_error = true;
		}
		if($importer_error)
		{
			die("Import error! Please unninstall WP importer plugin and try again");
		}
		$wp_import = new WP_Import();
		$wp_import->fetch_attachments = true;
		$themename = 'slova';
		/* Import contents */
		$result = $wp_import->import( ABS_PATH_ADMIN. '/sample/'.$themename.'/sample.xml');
		/* Import widgets */
		$widgets_json = URI_PATH_ADMIN . '/sample/'.$themename.'/widget_data.json';
		
		tb_clear_widgets();
		$json_data = file_get_contents( $widgets_json );
		if($json_data){
			$json_data = json_decode( $json_data, true );
			$sidebar_data = $json_data[0];
			$widget_data = $json_data[1];
			$widgets = tb_get_widgets_data($widget_data);
			foreach ( $sidebar_data as $title => $sidebar ) {
				$count = count( $sidebar );
				for ( $i = 0; $i < $count; $i++ ) {
					$widget = array( );
					$widget['type'] = trim( substr( $sidebar[$i], 0, strrpos( $sidebar[$i], '-' ) ) );
					$widget['type-index'] = trim( substr( $sidebar[$i], strrpos( $sidebar[$i], '-' ) + 1 ) );
					if ( !isset( $widgets[$widget['type']][$widget['type-index']] ) ) {
						unset( $sidebar_data[$title][$i] );
					}
				}
				$sidebar_data[$title] = array_values( $sidebar_data[$title] );
			}

			foreach ( $widgets as $widget_title => $widget_value ) {
				foreach ( $widget_value as $widget_key => $widget_value ) {
					$widgets[$widget_title][$widget_key] = $widget_data[$widget_title][$widget_key];
				}
			}

			$sidebar_data = array( array_filter( $sidebar_data ), $widgets );
			//print_r($sidebar_data);die;
			tb_import_widget($sidebar_data);
		}
		
		/* Import revsliders */
		if(!tb_import_revslider($themename)){
			die('<br />You haven\'t install Rev Slider plugin. Slider isn\'t imported<br />');
		}
		/* Import options*/
		$option_json = URI_PATH_ADMIN . '/sample/'.$themename.'/options.json';
		$option_data = wp_remote_retrieve_body( wp_remote_get( $option_json ) );
		if ( ! empty( $option_data ) ) {
			$option_data = json_decode( $option_data, true );
		}
		tb_set_options($option_data);
		die('Import is Completed');
		ob_end_clean();
	}
}

if ( ! function_exists( 'tb_set_options' ) ){
	function tb_set_options($options){
		$args = array('global_variable'=>'tb_options','opt_name'=>'tb_options');
		$ReduxFramework = new ReduxFramework(array(),$args);
		$ReduxFramework->set_options($options);
	}
}

if(!function_exists('tb_import_widget')){
	function tb_import_widget($import_array){
		$sidebars_data = $import_array[0];
		$widget_data = $import_array[1];
		$current_sidebars = get_option( 'sidebars_widgets' );
		$current_sidebars['tbtheme-shop-sidebar'] = array();
		$current_sidebars['tbtheme-detail-product-sidebar'] = array();
		//print_r($current_sidebars);die;
		$new_widgets = array( );

		foreach ( $sidebars_data as $import_sidebar => $import_widgets ) :

			foreach ( $import_widgets as $import_widget ) :
				//if the sidebar exists
				if ( isset( $current_sidebars[$import_sidebar] ) ) :
					$title = trim( substr( $import_widget, 0, strrpos( $import_widget, '-' ) ) );
					$index = trim( substr( $import_widget, strrpos( $import_widget, '-' ) + 1 ) );
					$current_widget_data = get_option( 'widget_' . $title );
					$new_widget_name = tb_new_widget_name( $title, $index );
					$new_index = trim( substr( $new_widget_name, strrpos( $new_widget_name, '-' ) + 1 ) );

					if ( !empty( $new_widgets[ $title ] ) && is_array( $new_widgets[$title] ) ) {
						while ( array_key_exists( $new_index, $new_widgets[$title] ) ) {
							$new_index++;
						}
					}
					$current_sidebars[$import_sidebar][] = $title . '-' . $new_index;
					if ( array_key_exists( $title, $new_widgets ) ) {
						$new_widgets[$title][$new_index] = $widget_data[$title][$index];
						$multiwidget = $new_widgets[$title]['_multiwidget'];
						unset( $new_widgets[$title]['_multiwidget'] );
						$new_widgets[$title]['_multiwidget'] = $multiwidget;
					} else {
						$current_widget_data[$new_index] = $widget_data[$title][$index];
						$current_multiwidget = $current_widget_data['_multiwidget'];
						$new_multiwidget = isset($widget_data[$title]['_multiwidget']) ? $widget_data[$title]['_multiwidget'] : false;
						$multiwidget = ($current_multiwidget != $new_multiwidget) ? $current_multiwidget : 1;
						unset( $current_widget_data['_multiwidget'] );
						$current_widget_data['_multiwidget'] = $multiwidget;
						$new_widgets[$title] = $current_widget_data;
					}

				endif;
			endforeach;
		endforeach;

		if ( isset( $new_widgets ) && isset( $current_sidebars ) ) {
			update_option( 'sidebars_widgets', $current_sidebars );

			foreach ( $new_widgets as $title => $content ) {
				$content = apply_filters( 'widget_data_import', $content, $title );
				update_option( 'widget_' . $title, $content );
			}

			return true;
		}

		return false;
	}
}

if(!function_exists('tb_new_widget_name')){
	function tb_new_widget_name($widget_name, $widget_index){
		$current_sidebars = get_option( 'sidebars_widgets' );
		$all_widget_array = array( );
		foreach ( $current_sidebars as $sidebar => $widgets ) {
			if ( !empty( $widgets ) && is_array( $widgets ) && $sidebar != 'wp_inactive_widgets' ) {
				foreach ( $widgets as $widget ) {
					$all_widget_array[] = $widget;
				}
			}
		}
		while ( in_array( $widget_name . '-' . $widget_index, $all_widget_array ) ) {
			$widget_index++;
		}
		$new_widget_name = $widget_name . '-' . $widget_index;
		return $new_widget_name;
	}
}

if(!function_exists('tb_get_widgets_data')){

	function tb_get_widgets_data( $widget_data ) {
		foreach ( $widget_data as $k => $v ) :
			if ( count( $v ) == 0 ) {
				continue;
			}
			foreach ( $v as $_k => $_v ) :
				$widget_data[$k][$_k] = 'on';
				unset($widget_data[$k]['_multiwidget']);
			endforeach;
		endforeach;
		return $widget_data;
	}
}

if(!function_exists('tb_clear_widgets')){
	function tb_clear_widgets() {
		$sidebars = wp_get_sidebars_widgets();
		$inactive = isset($sidebars['wp_inactive_widgets']) ? $sidebars['wp_inactive_widgets'] : array();

		unset($sidebars['wp_inactive_widgets']);

		foreach ( $sidebars as $sidebar => $widgets ) {
			if($widgets):
				$inactive = array_merge($inactive, $widgets);
			endif;
			$sidebars[$sidebar] = array();
		}

		$sidebars['wp_inactive_widgets'] = $inactive;
		wp_set_sidebars_widgets( $sidebars );
	}
}
if(!function_exists('tb_import_revslider')){
	function tb_import_revslider($theme){
		if(class_exists('UniteBaseAdminClassRev')){
			require_once(ABSPATH .'wp-content/plugins/revslider/admin/revslider-admin.class.php');
			if ($handle = opendir(ABS_PATH_ADMIN.'/sample/'.$theme.'/revslider')) {
			    while (false !== ($entry = readdir($handle))) {
			        if ($entry != "." && $entry != "..") {
			            $_FILES['import_file']['tmp_name'] = ABS_PATH_ADMIN.'/sample/'.$theme.'/revslider/'.$entry;
			            $slider = new RevSlider();
			            ob_start();
						$response = $slider->importSliderFromPost(true, true);
						ob_end_clean();
			        }
			    }
			    closedir($handle);
			}
			return true;
		}
		return false;
	}
}
