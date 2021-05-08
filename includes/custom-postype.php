<?php

//define timelines post type

function timelines_custom_post_type() {
	$labels = array(
		'name' => 'Timelines',
        'singular_name' => 'Timeline',
        'add_new' => 'Add Timeline',
        'all_items' => 'All Timelines',
        'add_new_item' => 'Add Timeline',
        'edit_item' => 'Edit Timeline',
        'view_item' => 'View Timeline',
        'new_item' => 'New Item',
        'search_item' => 'Search Timelines',
        'not_found' => 'No timeline found',
        'not_found_in_trash' => 'No timeline found in trash',
        'parent_item_colon' => 'Parent Timeline'
	);
	$args = array(
		'labels' => $labels,
		'supports' => array( 'title' ),
		'taxonomies' => array( 'timelinecategory' ),
		'hierarchical' => false,
		'public' => true,
		'menu_position' => 8,
		'rewrite' => true,
		'query_var' => true,
		'hierarchical' => false,
		'can_export' => true,
		'has_archive' => true,
		'exclude_from_search' => false,
		'publicly_queryable' => true,
		'capability_type' => 'post',
        'menu_icon' => 'dashicons-calendar-alt'
	);
	register_post_type( 'timelines', $args );

}
add_action( 'init', 'timelines_custom_post_type');



//hook into the init action and call create_book_taxonomies when it fires
 
add_action( 'init', 'create_timelinecategories_hierarchical_taxonomy', 0 );
 
//create a custom taxonomy name it timeline_categories for your posts
 
function create_timelinecategories_hierarchical_taxonomy() {
 
// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI
 
  $labels = array(
    'name' => _x( 'Timeline Categories', 'taxonomy general name' ),
    'singular_name' => _x( 'Timeline Category', 'taxonomy singular name' ),
    'search_items' =>  __( 'Search Timeline Categories' ),
    'all_items' => __( 'All Timeline Categories' ),
    'parent_item' => __( 'Parent Timeline Category' ),
    'parent_item_colon' => __( 'Parent Timeline Category:' ),
    'edit_item' => __( 'Edit Timeline Category' ), 
    'update_item' => __( 'Update Timeline Category' ),
    'add_new_item' => __( 'Add New Timeline Category' ),
    'new_item_name' => __( 'New Timeline Category Name' ),
    'menu_name' => __( 'Timeline Categories' ),
  );    
 
// Now register the taxonomy
  register_taxonomy('timelinecategories',array('timelines'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'timelinecategory' ),
  ));
 
}