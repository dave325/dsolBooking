<?php
// Register Custom Post Type
function tb_add_post_type_testimonial() {
    // Register taxonomy
    $labels = array(
            'name'              => _x( 'Testimonial Category', 'taxonomy general name', 'slova' ),
            'singular_name'     => _x( 'Testimonial Category', 'taxonomy singular name', 'slova' ),
            'search_items'      => __( 'Search Testimonial Category', 'slova' ),
            'all_items'         => __( 'All Testimonial Category', 'slova' ),
            'parent_item'       => __( 'Parent Testimonial Category', 'slova' ),
            'parent_item_colon' => __( 'Parent Testimonial Category:', 'slova' ),
            'edit_item'         => __( 'Edit Testimonial Category', 'slova' ),
            'update_item'       => __( 'Update Testimonial Category', 'slova' ),
            'add_new_item'      => __( 'Add New Testimonial Category', 'slova' ),
            'new_item_name'     => __( 'New Testimonial Category Name', 'slova' ),
            'menu_name'         => __( 'Testimonial Category', 'slova' ),
    );

    $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'testimonial_category' ),
    );
    if(function_exists('custom_reg_taxonomy')) {
        custom_reg_taxonomy( 'testimonial_category', array( 'testimonial' ), $args );
    }
    //Register tags
    $labels = array(
            'name'              => _x( 'Testimonial Tag', 'taxonomy general name', 'slova' ),
            'singular_name'     => _x( 'Testimonial Tag', 'taxonomy singular name', 'slova' ),
            'search_items'      => __( 'Search Testimonial Tag', 'slova' ),
            'all_items'         => __( 'All Testimonial Tag', 'slova' ),
            'parent_item'       => __( 'Parent Testimonial Tag', 'slova' ),
            'parent_item_colon' => __( 'Parent Testimonial Tag:', 'slova' ),
            'edit_item'         => __( 'Edit Testimonial Tag', 'slova' ),
            'update_item'       => __( 'Update Testimonial Tag', 'slova' ),
            'add_new_item'      => __( 'Add New Testimonial Tag', 'slova' ),
            'new_item_name'     => __( 'New Testimonial Tag Name', 'slova' ),
            'menu_name'         => __( 'Testimonial Tag', 'slova' ),
    );

    $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'testimonial_tag' ),
    );
    
    if(function_exists('custom_reg_taxonomy')) {
        custom_reg_taxonomy( 'testimonial_tag', array( 'testimonial' ), $args );
    }
    
    //Register post type Testimonial
    $labels = array(
            'name'                => _x( 'Testimonial', 'Post Type General Name', 'slova' ),
            'singular_name'       => _x( 'Testimonial Item', 'Post Type Singular Name', 'slova' ),
            'menu_name'           => __( 'Testimonial', 'slova' ),
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
            'label'               => __( 'Testimonial', 'slova' ),
            'description'         => __( 'Testimonial Description', 'slova' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', ),
            'taxonomies'          => array( 'testimonial_category', 'testimonial_tag' ),
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
        custom_reg_post_type( 'testimonial', $args );
    }
    
}

// Hook into the 'init' action
add_action( 'init', 'tb_add_post_type_testimonial', 0 );
