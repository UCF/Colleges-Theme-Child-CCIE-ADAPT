<?php

add_action( 'wp_enqueue_scripts', 'college_theme_child_style' );

function college_theme_child_style() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/static/css/style.min.css' );
    //Customized Style
    wp_enqueue_style( 'child-style',  get_stylesheet_directory_uri() . '/style.css');
}



add_action( 'init', 'cp_change_post_object' );
// Change dashboard Posts to Data
function cp_change_post_object() {
    $get_post_type = get_post_type_object('post');
    $labels = $get_post_type->labels;
        $labels->name = 'Data';
        $labels->singular_name = 'Dat';
        $labels->add_new = 'Add Data';
        $labels->add_new_item = 'Add Data';
        $labels->edit_item = 'Edit Data';
        $labels->new_item = 'Data';
        $labels->view_item = 'View Data';
        $labels->search_items = 'Search Data';
        $labels->not_found = 'No Data found';
        $labels->not_found_in_trash = 'No Data found in Trash';
        $labels->all_items = 'All Data';
        $labels->menu_name = 'Data';
        $labels->name_admin_bar = 'Data';
}


//hook into the init action and call create_filter_terms_hierarchical_taxonomy when it fires
add_action( 'init', 'create_filter_terms_hierarchical_taxonomy', 0 );
 
//create a custom taxonomy name it topics for your posts
function create_filter_terms_hierarchical_taxonomy() {
 
// Add new taxonomy, make it hierarchical like categories
//1: do the translations part for GUI
  $labels = array(
    'name' => _x( 'Filter Terms', 'taxonomy general name' ),
    'singular_name' => _x( 'Filter Term', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Filter Terms' ),
    'all_items' => __( 'All Filter Terms' ),
    'parent_item' => __( 'Parent Filter Terms' ),
    'parent_item_colon' => __( 'Parent Filter Term:' ),
    'edit_item' => __( 'Edit Filter Term' ), 
    'update_item' => __( 'Update Filter Terms' ),
    'add_new_item' => __( 'Add New Filter Terms' ),
    'new_item_name' => __( 'New Filter Term Name' ),
    'menu_name' => __( 'Filter Terms' ),
  );    
 
// 2: register the taxonomy
  register_taxonomy('filter_terms',array('post'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'filter_terms' ),
  ));
 
}

?>