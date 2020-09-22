<?php

add_action( 'wp_enqueue_scripts', 'college_theme_child_style' );

function college_theme_child_style() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/static/css/style.min.css' );
    //Customized Style
    wp_enqueue_style( 'child-style',  get_stylesheet_directory_uri() . '/style.css');
    if(strpos(trim( $_SERVER["REQUEST_URI"] , '/' ), 'database')) {
      wp_enqueue_style( 'dt-style',  get_stylesheet_directory_uri() . '/css/jquery.dataTables.min.css');
    }
    
    //load scripts
    if(strpos(trim( $_SERVER["REQUEST_URI"] , '/' ), 'database')) {
      wp_enqueue_script( 'dtscript', get_stylesheet_directory_uri() . '/js/jquery.dataTables.min.js', array ( 'jquery' ), true);
      wp_enqueue_script( 'dtbtnscript', get_stylesheet_directory_uri() . '/js/dataTables.buttons.min.js', true);
      wp_enqueue_script( 'dtflashscript', get_stylesheet_directory_uri() . '/js/buttons.flash.min.js', true);
      wp_enqueue_script( 'dtszipscript', get_stylesheet_directory_uri() . '/js/jszip.min.js', true);
      wp_enqueue_script( 'dtspdfscript', get_stylesheet_directory_uri() . '/js/pdfmake.min.js', true);
      wp_enqueue_script( 'dtsvfsscript', get_stylesheet_directory_uri() . '/js/vfs_fonts.js', true);
      wp_enqueue_script( 'dtshtml5script', get_stylesheet_directory_uri() . '/js/buttons.html5.min.js', true);
      wp_enqueue_script( 'dtsprintscript', get_stylesheet_directory_uri() . '/js/buttons.print.min.js', true);
      wp_enqueue_script( 'search_database', get_stylesheet_directory_uri() . '/js/page-data.js', array ( 'dtscript' ), 1.1, true);
      wp_localize_script( 'search_database', 'ajax_url', admin_url('admin-ajax.php?action=search_database') );
    }
}

// Ajax Callback  
add_action('wp_ajax_search_database', 'search_database_callback');
add_action('wp_ajax_nopriv_search_database', 'search_database_callback');
  
function search_database_callback() {
  
    header("Content-Type: application/json"); 
  
    if(!isset($_GET['categories']) && !isset($_GET['terms']) && !isset($_GET['tags'])){
      $args = array(
        'posts_per_page'   => -1,
        'post_type'        => 'post',
      );
    }
    else{
      $args = array(
        'posts_per_page'   => -1,
        'post_type'        => 'post',
        'post_status' => 'publish',
        'tax_query' => array(
          'relation' => 'OR',
          array(
              'taxonomy' => 'category',
              'field' => 'term_id',        
              'terms' => $_GET['categories'],               
          ),
          // array(
          //     'taxonomy' => 'filter_terms',
          //     'field' => 'term_id',        
          //     'terms' => $_GET['terms'],               
          // ),
          array(
              'taxonomy' => 'post_tag',
              'field' => 'term_id',        
              'terms' => $_GET['tags'],               
          )
        ),
        // 'orderby' => 'date',
        // 'order' => 'DESC'
      );
    }
    
    $post_query = new WP_Query( $args );
    $totalData = $post_query->found_posts;
    
    $data = array();
      if ($post_query->have_posts()) {
          
          while ($post_query->have_posts()) {
              $post_query->the_post();
              $nestedData = array();
              $nestedData[] = "<i class='fa fa-arrow-right text-info mr-2'></i><a href='" . get_permalink() . "'><strong>" . get_the_title() . "</strong></a>";
              $terms = get_the_terms(get_the_ID(), 'category' );
              $category_list = array_map(function($term){return "<span class='small badge badge-pill badge-info no-transform'>" . $term->name. "</span>"; }, $terms);
              $nestedData[] = implode(" ", $category_list);

              // $terms = get_the_terms(get_the_ID(), 'filter_terms' );
              // $filter_term_list = array_map(function($term){return "<span class='small badge  badge-pill badge-info no-transform'>" . $term->name. "</span>"; }, $terms);
              // $nestedData[] = implode(" ", $filter_term_list);

              $terms = get_the_terms(get_the_ID(), 'post_tag' );
              $filter_term_list = array_map(function($term){return "<span class='small badge badge-pill badge-info no-transform'>" . $term->name. "</span>"; }, $terms);
              $nestedData[] = implode(" ", $filter_term_list);

              $str = wpautop( get_the_content() );
              $str = substr( $str, 0, strpos( $str, '</p>' ) + 4 );
              $str = strip_tags($str, '<a><strong><em>');

              //$nestedData[] = wp_trim_words(strip_tags(get_the_excerpt()), 10);
              $nestedData[] =  $str;
              $data[] = $nestedData;
          }
          wp_reset_query();
      }
      $json_data = array(
        //"recordsTotal" => intval($totalData),
        "data" => $data
      );
      echo json_encode($json_data);

    wp_die();
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

add_action( 'wp_enqueue_scripts', '_remove_style', PHP_INT_MAX );
function _remove_style() {
    wp_dequeue_style( 'style' );
}

?>