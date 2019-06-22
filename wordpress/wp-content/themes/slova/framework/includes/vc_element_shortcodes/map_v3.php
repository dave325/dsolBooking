<?php
vc_map(array(
    "name" => 'Google Maps',
    "base" => "maps",
    "category" => __('Slova', 'slova'),
	"icon" => "tb-icon-for-vc",
    "description" => __('Google Maps API V3', 'slova'),
    "params" => array(
        array(
            "type" => "textfield",
            "heading" => __('API Key', 'slova'),
            "param_name" => "api",
            "value" => '',
            "description" => __('Enter you api key of map, get key from (https://console.developers.google.com)', 'slova')
        ),
        array(
            "type" => "textfield",
            "heading" => __('Address', 'slova'),
            "param_name" => "address",
            "value" => 'New York, United States',
            "description" => __('Enter address of Map', 'slova')
        ),
        array(
            "type" => "textfield",
            "heading" => __('Coordinate', 'slova'),
            "param_name" => "coordinate",
            "value" => '',
            "description" => __('Enter coordinate of Map, format input (latitude, longitude)', 'slova')
        ),
        array(
            "type" => "checkbox",
            "heading" => __('Click Show Info window', 'slova'),
            "param_name" => "infoclick",
            "value" => array(
                __("Yes, please", 'slova') => true
            ),
            "group" => __("Marker", 'slova'),
            "description" => __('Click a marker and show info window (Default Show).', 'slova')
        ),
        array(
            "type" => "textfield",
            "heading" => __('Marker Coordinate', 'slova'),
            "param_name" => "markercoordinate",
            "value" => '',
            "group" => __("Marker", 'slova'),
            "description" => __('Enter marker coordinate of Map, format input (latitude, longitude)', 'slova')
        ),
        array(
            "type" => "textfield",
            "heading" => __('Marker Title', 'slova'),
            "param_name" => "markertitle",
            "value" => '',
            "group" => __("Marker", 'slova'),
            "description" => __('Enter Title Info windows for marker', 'slova')
        ),
        array(
            "type" => "textarea",
            "heading" => __('Marker Description', 'slova'),
            "param_name" => "markerdesc",
            "value" => '',
            "group" => __("Marker", 'slova'),
            "description" => __('Enter Description Info windows for marker', 'slova')
        ),
        array(
            "type" => "attach_image",
            "heading" => __('Marker Icon', 'slova'),
            "param_name" => "markericon",
            "value" => '',
            "group" => __("Marker", 'slova'),
            "description" => __('Select image icon for marker', 'slova')
        ),
        array(
            "type" => "textarea_raw_html",
            "heading" => __('Marker List', 'slova'),
            "param_name" => "markerlist",
            "value" => '',
            "group" => __("Multiple Marker", 'slova'),
            "description" => __('[{"coordinate":"41.058846,-73.539423","icon":"","title":"title demo 1","desc":"desc demo 1"},{"coordinate":"40.975699,-73.717636","icon":"","title":"title demo 2","desc":"desc demo 2"},{"coordinate":"41.082606,-73.469718","icon":"","title":"title demo 3","desc":"desc demo 3"}]', 'slova')
        ),
        array(
            "type" => "textfield",
            "heading" => __('Info Window Max Width', 'slova'),
            "param_name" => "infowidth",
            "value" => '200',
            "group" => __("Marker", 'slova'),
            "description" => __('Set max width for info window', 'slova')
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Map Type", 'slova'),
            "param_name" => "type",
            "value" => array(
                "ROADMAP" => "ROADMAP",
                "HYBRID" => "HYBRID",
                "SATELLITE" => "SATELLITE",
                "TERRAIN" => "TERRAIN"
            ),
            "description" => __('Select the map type.', 'slova')
        ),
        array(
            "type" => "dropdown",
            "heading" => __("Style Template", 'slova'),
            "param_name" => "style",
            "value" => array(
                "Default" => "",
                "Subtle Grayscale" => "Subtle-Grayscale",
                "Shades of Grey" => "Shades-of-Grey",
                "Blue water" => "Blue-water",
                "Pale Dawn" => "Pale-Dawn",
                "Blue Essence" => "Blue-Essence",
                "Apple Maps-esque" => "Apple-Maps-esque",
            ),
            "group" => __("Map Style", 'slova'),
            "description" => 'Select your heading size for title.'
        ),
        array(
            "type" => "textfield",
            "heading" => __('Zoom', 'slova'),
            "param_name" => "zoom",
            "value" => '13',
            "description" => __('zoom level of map, default is 13', 'slova')
        ),
        array(
            "type" => "textfield",
            "heading" => __('Width', 'slova'),
            "param_name" => "width",
            "value" => 'auto',
            "description" => __('Width of map without pixel, default is auto', 'slova')
        ),
        array(
            "type" => "textfield",
            "heading" => __('Height', 'slova'),
            "param_name" => "height",
            "value" => '350px',
            "description" => __('Height of map without pixel, default is 350px', 'slova')
        ),
        array(
            "type" => "checkbox",
            "heading" => __('Scroll Wheel', 'slova'),
            "param_name" => "scrollwheel",
            "value" => array(
                __("Yes, please", 'slova') => true
            ),
            "group" => __("Controls", 'slova'),
            "description" => __('If false, disables scrollwheel zooming on the map. The scrollwheel is disable by default.', 'slova')
        ),
        array(
            "type" => "checkbox",
            "heading" => __('Pan Control', 'slova'),
            "param_name" => "pancontrol",
            "value" => array(
                __("Yes, please", 'slova') => true
            ),
            "group" => __("Controls", 'slova'),
            "description" => __('Show or hide Pan control.', 'slova')
        ),
        array(
            "type" => "checkbox",
            "heading" => __('Zoom Control', 'slova'),
            "param_name" => "zoomcontrol",
            "value" => array(
                __("Yes, please", 'slova') => true
            ),
            "group" => __("Controls", 'slova'),
            "description" => __('Show or hide Zoom Control.', 'slova')
        ),
        array(
            "type" => "checkbox",
            "heading" => __('Scale Control', 'slova'),
            "param_name" => "scalecontrol",
            "value" => array(
                __("Yes, please", 'slova') => true
            ),
            "group" => __("Controls", 'slova'),
            "description" => __('Show or hide Scale Control.', 'slova')
        ),
        array(
            "type" => "checkbox",
            "heading" => __('Map Type Control', 'slova'),
            "param_name" => "maptypecontrol",
            "value" => array(
                __("Yes, please", 'slova') => true
            ),
            "group" => __("Controls", 'slova'),
            "description" => __('Show or hide Map Type Control.', 'slova')
        ),
        array(
            "type" => "checkbox",
            "heading" => __('Street View Control', 'slova'),
            "param_name" => "streetviewcontrol",
            "value" => array(
                __("Yes, please", 'slova') => true
            ),
            "group" => __("Controls", 'slova'),
            "description" => __('Show or hide Street View Control.', 'slova')
        ),
        array(
            "type" => "checkbox",
            "heading" => __('Over View Map Control', 'slova'),
            "param_name" => "overviewmapcontrol",
            "value" => array(
                __("Yes, please", 'slova') => true
            ),
            "group" => __("Controls", 'slova'),
            "description" => __('Show or hide Over View Map Control.', 'slova')
        )
    )
));