<?php
// Register Custom Post Type
function tb_add_post_type_video() {
    // Register taxonomy
    $labels = array(
            'name'              => _x( 'Video Category', 'taxonomy general name', 'slova' ),
            'singular_name'     => _x( 'Video Category', 'taxonomy singular name', 'slova' ),
            'search_items'      => __( 'Search Video Category', 'slova' ),
            'all_items'         => __( 'All Video Category', 'slova' ),
            'parent_item'       => __( 'Parent Video Category', 'slova' ),
            'parent_item_colon' => __( 'Parent Video Category:', 'slova' ),
            'edit_item'         => __( 'Edit Video Category', 'slova' ),
            'update_item'       => __( 'Update Video Category', 'slova' ),
            'add_new_item'      => __( 'Add New Video Category', 'slova' ),
            'new_item_name'     => __( 'New Video Category Name', 'slova' ),
            'menu_name'         => __( 'Video Category', 'slova' ),
    );

    $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'video_category' ),
    );
    if(function_exists('custom_reg_taxonomy')) {
        custom_reg_taxonomy( 'video_category', array( 'video' ), $args );
    }
    
    //Register post type Video
    $labels = array(
            'name'                => _x( 'Video', 'Post Type General Name', 'slova' ),
            'singular_name'       => _x( 'Video Item', 'Post Type Singular Name', 'slova' ),
            'menu_name'           => __( 'Video', 'slova' ),
            'parent_item_colon'   => __( 'Parent Item:', 'slova' ),
            'all_items'           => __( 'All Items', 'slova' ),
            'view_item'           => __( 'View Item', 'slova' ),
            'add_new_item'        => __( 'Add New Item', 'slova' ),
            'add_new'             => __( 'Add New', 'slova' ),
            'edit_item'           => __( 'Edit Item', 'slova' ),
            'update_item'         => __( 'Update Item', 'slova' ),
            'search_items'        => __( 'Search Item', 'slova' ),
            'not_found'           => __( 'Not found', 'slova' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'slova' ),
    );
    $args = array(
            'label'               => __( 'Video', 'slova' ),
            'description'         => __( 'Video Description', 'slova' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', ),
            'taxonomies'          => array( 'video_category' ),
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'menu_icon'           => 'dashicons-pressthis',
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'page',
    );
    
    if(function_exists('custom_reg_post_type')) {
        custom_reg_post_type( 'video', $args );
    }
    
}

// Hook into the 'init' action
add_action( 'init', 'tb_add_post_type_video', 0 );
