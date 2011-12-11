<?php

/* Register Ingredients
------------------------------------------------------------------------- */
function register_taxonomy_ingredient() {
	
	$labels = array( 
	'name' 				=> _x( 'Ingredients', 'ingredient' ),
	'singular_name' 	=> _x( 'Ingredient', 'ingredient' ),
	'search_items'		=> _x( 'Search Ingredients', 'ingredient' ),
	'popular_items' 	=> _x( 'Popular Ingredients', 'ingredient' ),
	'all_items'			=> _x( 'All Ingredients', 'ingredient' ),
	'parent_item' 		=> _x( 'Parent Ingredient', 'ingredient' ),
	'parent_item_colon' => _x( 'Parent Ingredient:', 'ingredient' ),
	'edit_item' 		=> _x( 'Edit Ingredient', 'ingredient' ),
	'update_item' 		=> _x( 'Update Ingredient', 'ingredient' ),
	'add_new_item' 		=> _x( 'Add New Ingredient', 'ingredient' ),
	'new_item_name' 	=> _x( 'New Ingredient Name', 'ingredient' ),
	'add_or_remove_items' => _x( 'Add or remove ingredients', 'ingredient' ),
	'menu_name' 		=> _x( 'Ingredients', 'ingredient' ),
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

	register_taxonomy( 'ingredient', 'post', $args );
}

/* Register Cuisines
------------------------------------------------------------------------- */
function register_taxonomy_cuisine() {
	
	$labels = array( 
	'name' 				=> _x( 'Cuisines', 'cuisine' ),
	'singular_name' 	=> _x( 'Cuisine', 'cuisine' ),
	'search_items'		=> _x( 'Search Cuisines', 'cuisine' ),
	'popular_items' 	=> _x( 'Popular Cuisines', 'cuisine' ),
	'all_items'			=> _x( 'All Cuisines', 'cuisine' ),
	'parent_item' 		=> _x( 'Parent Cuisine', 'cuisine' ),
	'parent_item_colon' => _x( 'Parent Cuisine:', 'cuisine' ),
	'edit_item' 		=> _x( 'Edit Cuisine', 'cuisine' ),
	'update_item' 		=> _x( 'Update Cuisine', 'cuisine' ),
	'add_new_item' 		=> _x( 'Add New Cuisine', 'cuisine' ),
	'new_item_name' 	=> _x( 'New Cuisine Name', 'cuisine' ),
	'add_or_remove_items' => _x( 'Add or remove cuisines', 'cuisine' ),
	'menu_name' 		=> _x( 'Cuisines', 'cuisine' ),
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

	register_taxonomy( 'cuisine', 'post', $args );
}

/* Register Courses
------------------------------------------------------------------------- */
function register_taxonomy_course() {
	
	$labels = array( 
	'name' 				=> _x( 'Courses', 'course' ),
	'singular_name' 	=> _x( 'Course', 'course' ),
	'search_items'		=> _x( 'Search Courses', 'course' ),
	'popular_items' 	=> _x( 'Popular Courses', 'course' ),
	'all_items'			=> _x( 'All Courses', 'course' ),
	'parent_item' 		=> _x( 'Parent Course', 'course' ),
	'parent_item_colon' => _x( 'Parent Course:', 'course' ),
	'edit_item' 		=> _x( 'Edit Course', 'course' ),
	'update_item' 		=> _x( 'Update Course', 'course' ),
	'add_new_item' 		=> _x( 'Add New Course', 'course' ),
	'new_item_name' 	=> _x( 'New Course Name', 'course' ),
	'add_or_remove_items' => _x( 'Add or remove courses', 'course' ),
	'menu_name' 		=> _x( 'Courses', 'course' ),
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

	register_taxonomy( 'course', 'post', $args );
}

/* Register Skill Levels
------------------------------------------------------------------------- */
function register_taxonomy_skill_level() {
	
	$labels = array( 
	'name' 				=> _x( 'Skill Levels', 'skill_level' ),
	'singular_name' 	=> _x( 'Skill Level', 'skill_level' ),
	'search_items'		=> _x( 'Search Skill Levels', 'skill_level' ),
	'popular_items' 	=> _x( 'Popular Skill Levels', 'skill_level' ),
	'all_items'			=> _x( 'All Skill Levels', 'skill_level' ),
	'parent_item' 		=> _x( 'Parent Skill Level', 'skill_level' ),
	'parent_item_colon' => _x( 'Parent Skill Level:', 'skill_level' ),
	'edit_item' 		=> _x( 'Edit Skill Level', 'skill_level' ),
	'update_item' 		=> _x( 'Update Skill Level', 'skill_level' ),
	'add_new_item' 		=> _x( 'Add New Skill Level', 'skill_level' ),
	'new_item_name' 	=> _x( 'New Skill Level Name', 'skill_level' ),
	'add_or_remove_items' => _x( 'Add or remove skill levels', 'skill_level' ),
	'menu_name' 		=> _x( 'Skill Levels', 'skill_level' ),
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

	register_taxonomy( 'skill_level', 'post', $args );
}


/* Default Terms
   ------------------------------------------------------------------------- */
function recipress_default_taxonomies() {
	// Default Ingredients
	wp_insert_term( 'beef', 'ingredient');
	wp_insert_term( 'bell pepper', 'ingredient');
	wp_insert_term( 'bread', 'ingredient');
	wp_insert_term( 'cheese', 'ingredient');
	wp_insert_term( 'chicken', 'ingredient');
	wp_insert_term( 'eggs', 'ingredient');
	wp_insert_term( 'fish', 'ingredient');
	wp_insert_term( 'flour', 'ingredient');
	wp_insert_term( 'garlic', 'ingredient');
	wp_insert_term( 'milk', 'ingredient');
	wp_insert_term( 'nutmeg', 'ingredient');
	wp_insert_term( 'onion', 'ingredient');
	wp_insert_term( 'pasta', 'ingredient');
	wp_insert_term( 'potatoes', 'ingredient');
	wp_insert_term( 'shrimp', 'ingredient');
	wp_insert_term( 'spinach', 'ingredient');
	wp_insert_term( 'strawberries', 'ingredient');
	wp_insert_term( 'tomatoes', 'ingredient');
	// Default Cuisines
	wp_insert_term( 'American', 'cuisine');
	wp_insert_term( 'Chinese', 'cuisine');
	wp_insert_term( 'Indian', 'cuisine');
	wp_insert_term( 'Italian', 'cuisine');
	wp_insert_term( 'French', 'cuisine');
	wp_insert_term( 'Japanese', 'cuisine');
	wp_insert_term( 'Mediterrarean', 'cuisine');
	wp_insert_term( 'Mexican', 'cuisine');
	// Default Courses
	wp_insert_term( 'Appetizer', 'course');
	wp_insert_term( 'Breakfast', 'course');
	wp_insert_term( 'Entrée', 'course');
	wp_insert_term( 'Dessert', 'course');
	wp_insert_term( 'Salad', 'course');
	wp_insert_term( 'Side Dish', 'course');
	wp_insert_term( 'Snack', 'course');
	wp_insert_term( 'Soup', 'course');
	// Default Skill Levels
	wp_insert_term( 'Advanced', 'skill_level');
	wp_insert_term( 'Beginner', 'skill_level');
	wp_insert_term( 'Child Friendly', 'skill_level');
	wp_insert_term( 'Easy', 'skill_level');
	wp_insert_term( 'Moderate', 'skill_level');
}


/* Remove Taxonomy Boxes
   ------------------------------------------------------------------------- */
function recipress_remove_taxonomy_boxes() {
	remove_meta_box('tagsdiv-ingredient', 'post', 'side');
	remove_meta_box('tagsdiv-cuisine', 'post', 'side');
	remove_meta_box('tagsdiv-course', 'post', 'side');
	remove_meta_box('tagsdiv-skill_level', 'post', 'side');
}
add_action( 'admin_menu' , 'recipress_remove_taxonomy_boxes' );

?>