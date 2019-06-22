<?php
// Register Custom Post Type
function tb_add_post_type_portfolio() {
    // Register taxonomy
    $labels = array(
            'name'              => _x( 'Portfolio Category', 'taxonomy general name', 'slova' ),
            'singular_name'     => _x( 'Portfolio Category', 'taxonomy singular name', 'slova' ),
            'search_items'      => __( 'Search Portfolio Category', 'slova' ),
            'all_items'         => __( 'All Portfolio Category', 'slova' ),
            'parent_item'       => __( 'Parent Portfolio Category', 'slova' ),
            'parent_item_colon' => __( 'Parent Portfolio Category:', 'slova' ),
            'edit_item'         => __( 'Edit Portfolio Category', 'slova' ),
            'update_item'       => __( 'Update Portfolio Category', 'slova' ),
            'add_new_item'      => __( 'Add New Portfolio Category', 'slova' ),
            'new_item_name'     => __( 'New Portfolio Category Name', 'slova' ),
            'menu_name'         => __( 'Portfolio Category', 'slova' ),
    );

    $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'portfolio_category' ),
    );
    if(function_exists('custom_reg_taxonomy')) {
        custom_reg_taxonomy( 'portfolio_category', array( 'portfolio' ), $args );
    }
    //Register tags
    $labels = array(
            'name'              => _x( 'Portfolio Tag', 'taxonomy general name', 'slova' ),
            'singular_name'     => _x( 'Portfolio Tag', 'taxonomy singular name', 'slova' ),
            'search_items'      => __( 'Search Portfolio Tag', 'slova' ),
            'all_items'         => __( 'All Portfolio Tag', 'slova' ),
            'parent_item'       => __( 'Parent Portfolio Tag', 'slova' ),
            'parent_item_colon' => __( 'Parent Portfolio Tag:', 'slova' ),
            'edit_item'         => __( 'Edit Portfolio Tag', 'slova' ),
            'update_item'       => __( 'Update Portfolio Tag', 'slova' ),
            'add_new_item'      => __( 'Add New Portfolio Tag', 'slova' ),
            'new_item_name'     => __( 'New Portfolio Tag Name', 'slova' ),
            'menu_name'         => __( 'Portfolio Tag', 'slova' ),
    );

    $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'portfolio_tag' ),
    );
    
    if(function_exists('custom_reg_taxonomy')) {
        custom_reg_taxonomy( 'portfolio_tag', array( 'portfolio' ), $args );
    }
    
    //Register post type Portfolio
    $labels = array(
            'name'                => _x( 'Portfolio', 'Post Type General Name', 'slova' ),
            'singular_name'       => _x( 'Portfolio Item', 'Post Type Singular Name', 'slova' ),
            'menu_name'           => __( 'Portfolio', 'slova' ),
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
            'label'               => __( 'Portfolio', 'slova' ),
            'description'         => __( 'Portfolio Description', 'slova' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', ),
            'taxonomies'          => array( 'portfolio_category', 'portfolio_tag' ),
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
        custom_reg_post_type( 'portfolio', $args );
    }
    
}

// Hook into the 'init' action
add_action( 'init', 'tb_add_post_type_portfolio', 0 );
