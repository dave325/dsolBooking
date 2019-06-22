<?php
// Register Custom Post Type
function tb_add_post_type_team() {
    // Register taxonomy
    $labels = array(
            'name'              => _x( 'Team Category', 'taxonomy general name', 'slova' ),
            'singular_name'     => _x( 'Team Category', 'taxonomy singular name', 'slova' ),
            'search_items'      => __( 'Search Team Category', 'slova' ),
            'all_items'         => __( 'All Team Category', 'slova' ),
            'parent_item'       => __( 'Parent Team Category', 'slova' ),
            'parent_item_colon' => __( 'Parent Team Category:', 'slova' ),
            'edit_item'         => __( 'Edit Team Category', 'slova' ),
            'update_item'       => __( 'Update Team Category', 'slova' ),
            'add_new_item'      => __( 'Add New Team Category', 'slova' ),
            'new_item_name'     => __( 'New Team Category Name', 'slova' ),
            'menu_name'         => __( 'Team Category', 'slova' ),
    );

    $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'team_category' ),
    );
    if(function_exists('custom_reg_taxonomy')) {
        custom_reg_taxonomy( 'team_category', array( 'team' ), $args );
    }
    //Register tags
    $labels = array(
            'name'              => _x( 'Team Tag', 'taxonomy general name', 'slova' ),
            'singular_name'     => _x( 'Team Tag', 'taxonomy singular name', 'slova' ),
            'search_items'      => __( 'Search Team Tag', 'slova' ),
            'all_items'         => __( 'All Team Tag', 'slova' ),
            'parent_item'       => __( 'Parent Team Tag', 'slova' ),
            'parent_item_colon' => __( 'Parent Team Tag:', 'slova' ),
            'edit_item'         => __( 'Edit Team Tag', 'slova' ),
            'update_item'       => __( 'Update Team Tag', 'slova' ),
            'add_new_item'      => __( 'Add New Team Tag', 'slova' ),
            'new_item_name'     => __( 'New Team Tag Name', 'slova' ),
            'menu_name'         => __( 'Team Tag', 'slova' ),
    );

    $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'team_tag' ),
    );
    
    if(function_exists('custom_reg_taxonomy')) {
        custom_reg_taxonomy( 'team_tag', array( 'team' ), $args );
    }
    
    //Register post type Team
    $labels = array(
            'name'                => _x( 'Team', 'Post Type General Name', 'slova' ),
            'singular_name'       => _x( 'Team Item', 'Post Type Singular Name', 'slova' ),
            'menu_name'           => __( 'Team', 'slova' ),
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
            'label'               => __( 'Team', 'slova' ),
            'description'         => __( 'Team Description', 'slova' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', ),
            'taxonomies'          => array( 'team_category', 'team_tag' ),
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
        custom_reg_post_type( 'team', $args );
    }
    
}

// Hook into the 'init' action
add_action( 'init', 'tb_add_post_type_team', 0 );
