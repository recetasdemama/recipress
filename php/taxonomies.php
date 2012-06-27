<?php

/* Register Taxonomies
------------------------------------------------------------------------- */
function register_recipress_taxonomies() {
	
	// ingredients
	$labels = array( 
	'name' 				=> _x( 'Ingredients', 'taxonomy general name', 'recipress' ),
	'singular_name' 	=> _x( 'Ingredient', 'taxonomy singular name', 'recipress' ),
	'search_items'		=> __( 'Search Ingredients', 'recipress' ),
	'popular_items' 	=> __( 'Popular Ingredients', 'recipress' ),
	'all_items'			=> __( 'All Ingredients', 'recipress' ),
	'parent_item' 		=> __( 'Parent Ingredient', 'recipress' ),
	'parent_item_colon' => __( 'Parent Ingredient:', 'recipress' ),
	'edit_item' 		=> __( 'Edit Ingredient', 'recipress' ),
	'update_item' 		=> __( 'Update Ingredient', 'recipress' ),
	'add_new_item' 		=> __( 'Add New Ingredient', 'recipress' ),
	'new_item_name' 	=> __( 'New Ingredient Name', 'recipress' ),
	'add_or_remove_items' => __( 'Add or remove ingredients', 'recipress' ),
	'menu_name' 		=> _x( 'Ingredients', 'taxonomy menu name', 'recipress' ),
	);
	
	$args = array( 
	'labels' 			=> $labels,
	'public'			=> true,
	'show_in_nav_menus' => true,
	'show_ui' 			=> false,
	'show_tagcloud' 	=> false,
	'hierarchical'	 	=> false,
	
	'rewrite' 			=> true,
	'query_var' 		=> true
	);

	register_taxonomy( 'ingredient', recipress_post_type(), $args );
	
	// cuisines
	$labels = array( 
	'name' 				=> _x( 'Cuisines', 'taxonomy general name', 'recipress' ),
	'singular_name' 	=> _x( 'Cuisine', 'taxonomy singular name', 'recipress' ),
	'search_items'		=> __( 'Search Cuisines', 'recipress' ),
	'popular_items' 	=> __( 'Popular Cuisines', 'recipress' ),
	'all_items'			=> __( 'All Cuisines', 'recipress' ),
	'parent_item' 		=> __( 'Parent Cuisine', 'recipress' ),
	'parent_item_colon' => __( 'Parent Cuisine:', 'recipress' ),
	'edit_item' 		=> __( 'Edit Cuisine', 'recipress' ),
	'update_item' 		=> __( 'Update Cuisine', 'recipress' ),
	'add_new_item' 		=> __( 'Add New Cuisine', 'recipress' ),
	'new_item_name' 	=> __( 'New Cuisine Name', 'recipress' ),
	'add_or_remove_items' => __( 'Add or remove cuisines', 'recipress' ),
	'menu_name' 		=> _x( 'Cuisines', 'taxonomy menu name', 'recipress' ),
	);
	
	$args = array( 
	'labels' 			=> $labels,
	'public'			=> true,
	'show_in_nav_menus' => true,
	'show_ui' 			=> false,
	'show_tagcloud' 	=> false,
	'hierarchical'	 	=> false,
	
	'rewrite' 			=> true,
	'query_var' 		=> true
	);

	register_taxonomy( 'cuisine', recipress_post_type(), $args );
	
	// courses
	$labels = array( 
	'name' 				=> _x( 'Courses', 'taxonomy general name', 'recipress' ),
	'singular_name' 	=> _x( 'Course', 'taxonomy singular name', 'recipress' ),
	'search_items'		=> __( 'Search Courses', 'recipress' ),
	'popular_items' 	=> __( 'Popular Courses', 'recipress' ),
	'all_items'			=> __( 'All Courses', 'recipress' ),
	'parent_item' 		=> __( 'Parent Course', 'recipress' ),
	'parent_item_colon' => __( 'Parent Course:', 'recipress' ),
	'edit_item' 		=> __( 'Edit Course', 'recipress' ),
	'update_item' 		=> __( 'Update Course', 'recipress' ),
	'add_new_item' 		=> __( 'Add New Course', 'recipress' ),
	'new_item_name' 	=> __( 'New Course Name', 'recipress' ),
	'add_or_remove_items' => __( 'Add or remove courses', 'recipress' ),
	'menu_name' 		=> _x( 'Courses', 'taxonomy menu name', 'recipress' ),
	);
	
	$args = array( 
	'labels' 			=> $labels,
	'public'			=> true,
	'show_in_nav_menus' => true,
	'show_ui' 			=> false,
	'show_tagcloud' 	=> false,
	'hierarchical'	 	=> false,
	
	'rewrite' 			=> true,
	'query_var' 		=> true
	);

	register_taxonomy( 'course', recipress_post_type(), $args );
	
	// skill_levels
	$labels = array( 
	'name' 				=> _x( 'Skill Levels', 'taxonomy general name', 'recipress' ),
	'singular_name' 	=> _x( 'Skill Level', 'taxonomy singular name', 'recipress' ),
	'search_items'		=> __( 'Search Skill Levels', 'recipress' ),
	'popular_items' 	=> __( 'Popular Skill Levels', 'recipress' ),
	'all_items'			=> __( 'All Skill Levels', 'recipress' ),
	'parent_item' 		=> __( 'Parent Skill Level', 'recipress' ),
	'parent_item_colon' => __( 'Parent Skill Level:', 'recipress' ),
	'edit_item' 		=> __( 'Edit Skill Level', 'recipress' ),
	'update_item' 		=> __( 'Update Skill Level', 'recipress' ),
	'add_new_item' 		=> __( 'Add New Skill Level', 'recipress' ),
	'new_item_name' 	=> __( 'New Skill Level Name', 'recipress' ),
	'add_or_remove_items' => __( 'Add or remove skill levels', 'recipress' ),
	'menu_name' 		=> _x( 'Skill Levels', 'taxonomy menu name', 'recipress' ),
	);
	
	$args = array( 
	'labels' 			=> $labels,
	'public'			=> true,
	'show_in_nav_menus' => true,
	'show_ui' 			=> false,
	'show_tagcloud' 	=> false,
	'hierarchical'	 	=> false,
	
	'rewrite' 			=> true,
	'query_var' 		=> true
	);

	register_taxonomy( 'skill_level', recipress_post_type(), $args );
}


/* Default Terms
   ------------------------------------------------------------------------- */
function recipress_default_taxonomies() {
	// Default Ingredients
	wp_insert_term( __('beef', 'recipress'), 'ingredient');
	wp_insert_term( __('bell pepper', 'recipress'), 'ingredient');
	wp_insert_term( __('bread', 'recipress'), 'ingredient');
	wp_insert_term( __('cheese', 'recipress'), 'ingredient');
	wp_insert_term( __('chicken', 'recipress'), 'ingredient');
	wp_insert_term( __('eggs', 'recipress'), 'ingredient');
	wp_insert_term( __('fish', 'recipress'), 'ingredient');
	wp_insert_term( __('flour', 'recipress'), 'ingredient');
	wp_insert_term( __('garlic', 'recipress'), 'ingredient');
	wp_insert_term( __('milk', 'recipress'), 'ingredient');
	wp_insert_term( __('nutmeg', 'recipress'), 'ingredient');
	wp_insert_term( __('onion', 'recipress'), 'ingredient');
	wp_insert_term( __('pasta', 'recipress'), 'ingredient');
	wp_insert_term( __('potatoes', 'recipress'), 'ingredient');
	wp_insert_term( __('shrimp', 'recipress'), 'ingredient');
	wp_insert_term( __('spinach', 'recipress'), 'ingredient');
	wp_insert_term( __('strawberries', 'recipress'), 'ingredient');
	wp_insert_term( __('tomatoes', 'recipress'), 'ingredient');
	// Default Cuisines
	wp_insert_term( __('American', 'recipress'), 'cuisine');
	wp_insert_term( __('Chinese', 'recipress'), 'cuisine');
	wp_insert_term( __('Indian', 'recipress'), 'cuisine');
	wp_insert_term( __('Italian', 'recipress'), 'cuisine');
	wp_insert_term( __('French', 'recipress'), 'cuisine');
	wp_insert_term( __('Japanese', 'recipress'), 'cuisine');
	wp_insert_term( __('Mediterrarean', 'recipress'), 'cuisine');
	wp_insert_term( __('Mexican', 'recipress'), 'cuisine');
	wp_insert_term( __('Seafood', 'recipress'), 'cuisine');
	// Default Courses
	wp_insert_term( __('Appetizer', 'recipress'), 'course');
	wp_insert_term( __('Breakfast', 'recipress'), 'course');
	wp_insert_term( __('Entrée', 'recipress'), 'course');
	wp_insert_term( __('Dessert', 'recipress'), 'course');
	wp_insert_term( __('Salad', 'recipress'), 'course');
	wp_insert_term( __('Side Dish', 'recipress'), 'course');
	wp_insert_term( __('Snack', 'recipress'), 'course');
	wp_insert_term( __('Soup', 'recipress'), 'course');
	// Default Skill Levels
	wp_insert_term( __('Advanced', 'recipress'), 'skill_level');
	wp_insert_term( __('Beginner', 'recipress'), 'skill_level');
	wp_insert_term( __('Child Friendly', 'recipress'), 'skill_level');
	wp_insert_term( __('Easy', 'recipress'), 'skill_level');
	wp_insert_term( __('Moderate', 'recipress'), 'skill_level');
}


/* Remove Taxonomy Boxes
   ------------------------------------------------------------------------- */
function recipress_remove_taxonomy_boxes() {
	remove_meta_box('tagsdiv-ingredient', recipress_post_type(), 'side');
	remove_meta_box('tagsdiv-cuisine', recipress_post_type(), 'side');
	remove_meta_box('tagsdiv-course', recipress_post_type(), 'side');
	remove_meta_box('tagsdiv-skill_level', recipress_post_type(), 'side');
}
add_action( 'admin_menu' , 'recipress_remove_taxonomy_boxes' );

?>