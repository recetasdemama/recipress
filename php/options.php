<?php


/* Load up the menu page
------------------------------------------------------------------------- */
add_action( 'admin_menu', 'recipress_add_page' );
function recipress_add_page() {
	add_menu_page( 'ReciPress Options', 'ReciPress', 'edit_others_posts', 'recipress_options', 'recipress_do_page' );
	add_submenu_page( 'recipress_options', 'ReciPress Options', 'ReciPress Options', 'edit_others_posts', 'recipress_options', 'recipress_do_page');
	add_submenu_page( 'recipress_options', 'Ingredients', 'Ingredients', 'edit_others_posts', 'edit-tags.php?taxonomy=ingredient');
	$taxonomies = recipress_use_taxonomies();
	if(!isset($taxonomies) || (isset($taxonomies) && in_array('cuisine', $taxonomies)))
		add_submenu_page( 'recipress_options', 'Cuisines', 'Cuisines', 'edit_others_posts', 'edit-tags.php?taxonomy=cuisine');
	if(!isset($taxonomies) || (isset($taxonomies) && in_array('course', $taxonomies)))
		add_submenu_page( 'recipress_options', 'Courses', 'Courses', 'edit_others_posts', 'edit-tags.php?taxonomy=course');
	if(!isset($taxonomies) || (isset($taxonomies) && in_array('skill_level', $taxonomies)))
		add_submenu_page( 'recipress_options', 'Skill Levels', 'Skill Levels', 'edit_others_posts', 'edit-tags.php?taxonomy=skill_level');
}

// highlight the proper top level menu
function recipe_tax_menu_correction($parent_file) {
	global $current_screen;
	$taxonomy = $current_screen->taxonomy;
	if ($taxonomy == 'ingredient' || $taxonomy == 'cuisine' || $taxonomy == 'course' || $taxonomy == 'skill_level')
		$parent_file = 'recipress_options';
	return $parent_file;
}
add_action('parent_file', 'recipe_tax_menu_correction');

// add the taxonomy to the body class
function taxonomy_admin_body_class( $classes ) {
	global $current_screen;
	$taxonomy = $current_screen->taxonomy;
	if ( is_admin() && $taxonomy ) {
		$classes .= 'taxonomy-'. $taxonomy;
	}
	return $classes;
}
add_filter( 'admin_body_class', 'taxonomy_admin_body_class' );


/* Create Setting
------------------------------------------------------------------------- */
add_action( 'admin_init', 'recipress_init' );
function recipress_init(){
	register_setting( 'thd_options', 'recipress_options', 'recipress_validate');
}


/* Create an array of option fields
------------------------------------------------------------------------- */
$fields = array(
	array(
		'label' => 'Recipe Output',
		'type' 	=> 'section'
	),
	array(
		'label' => 'Automatically Output Recipe',
		'id' 	=> 'autoadd',
		'type' 	=> 'radio',
		'options'=> array(
			'yes'	=> array(
				'label'	=> 'Yes',
				'value'	=> 'yes',
				'desc'	=> 'Recipe will output after the post content',
				'default'=> true
			),
			'no'	=> array(
				'label'	=> 'No',
				'value'	=> 'no',
				'desc'	=> 'Use the <code>[recipe]</code> shortcode to output the recipe'
			),
		)
	),
	array(
		'label' => 'Output Recipe On',
		'id' 	=> 'output',
		'type' 	=> 'checkbox_group',
		'options'=> array(
			'home'	=> array(
				'label'	=> 'Home Page/Latest Posts Page',
				'value'	=> 'home'
			),
			'single'	=> array(
				'label'	=> 'Single Post Page',
				'value'	=> 'single',
				'default'=> true
			),
			'archive'	=> array(
				'label'	=> 'Archive and Category Pages',
				'value'	=> 'archive'
			),
			'search'	=> array(
				'label'	=> 'Search Result Page',
				'value'	=> 'search'
			),
		)
	),
	array(
		'label' => 'Recipe Theme',
		'id' 	=> 'theme',
		'type' 	=> 'image_radio',
		'options'=> array(
			'recipress-light'	=> array(
				'label'	=> 'Light',
				'value'	=> 'recipress-light',
				'image'	=> 'theme-light.jpg',
				'desc'	=> 'For use with light themes',
				'default' => true
			),
			'recipress-dark'	=> array(
				'label'	=> 'Dark',
				'value'	=> 'recipress-dark',
				'image'	=> 'theme-dark.jpg',
				'desc'	=> 'For use with dark themes'
			),
			'recipress-recipress'	=> array(
				'label'	=> 'ReciPress',
				'value'	=> 'recipress-recipress',
				'image'	=> 'theme-recipress.jpg',
				'desc'	=> 'Custom textured design'
			)
		)
	),
	array(
		'label' => 'Recipe Input',
		'type' 	=> 'section'
	),
	array(
		'label' => 'Use Taxonomies',
		'id' 	=> 'taxonomies',
		'type' 	=> 'checkbox_group',
		'options'=> array(
			'cuisine'	=> array(
				'label'	=> 'Cuisine',
				'value'	=> 'cuisine',
				'default'=> true
			),
			'course'	=> array(
				'label'	=> 'Course',
				'value'	=> 'course',
				'default'=> true
			),
			'skill_level'	=> array(
				'label'	=> 'Skill Level',
				'value'	=> 'skill_level',
				'default'=> true
			)
		)
	),
	array(
		'label' => 'Cost of Recipe Field',
		'id' 	=> 'cost_field',
		'type' 	=> 'radio',
		'options'=> array(
			'yes'	=> array(
				'label'	=> 'Yes',
				'value'	=> 'yes',
				'desc'	=> 'An input for the total cost to make the recipe will be available'
			),
			'no'	=> array(
				'label'	=> 'No',
				'value'	=> 'no',
				'desc'	=> 'This field will be omitted when creating recipes',
				'default'=> true
			),
		)
	),
	array(
		'label' => 'Say Thanks',
		'type' 	=> 'section'
	),
	array(
		'label'	=> 'Plugin Credit',
		'type'	=> 'checkbox',
		'id'	=> 'credit',
		'desc'	=> 'Add a credit link to the recipe box.',
		'checked' => ' checked="checked"'
	),
	array(
		'label'	=> 'Buy me a latte!',
		'type'	=> 'paypal'
	)
		
);


/* Create the options page
------------------------------------------------------------------------- */
function recipress_do_page() {
	global $fields;
	// if post-thumbnails are supported, add a recipe photo
	if(current_theme_supports('post-thumbnails')) 
		array_splice($fields, 4, 0,
		array(array(
			'label'	=> 'Use Featured Image',
			'id' 	=> 'use_photo',
			'type' 	=> 'radio',
			'options'=> array(
				'yes'	=> array(
					'label'	=> 'Yes',
					'value'	=> 'yes',
					'desc'	=> 'Recipe photo will be the same as the post&rsquo;s featured image',
					'default'=> true
				),
				'no'	=> array(
					'label'	=> 'No',
					'value'	=> 'no',
					'desc'	=> 'Use a different image for the recipe'
				),
			)
		))
	);

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

	echo '<div class="wrap">';
	screen_icon();
	echo '<h2>ReciPress Options</h2>';

		if ( false !== $_REQUEST['settings-updated'] )
			echo '<div class="updated fade"><p><strong>Options saved</strong></p></div>';

		echo '<form method="post" id="recipress_options" class="form-table" action="options.php">';
			settings_fields( 'thd_options' );
			// get the settings
			$options = get_option( 'recipress_options' );
			// start a loop through the fields
			$sections = 0;
			foreach ($fields as $field) {
			// section titles
			if ($field['type'] == 'section') {
				$sections++;
				if ($sections > 1)
					echo '</table></div></div>';
				echo '<div class="postbox metabox-holder"><h3>', $field['label'], '</h3>';
				// start a table
				echo '<div class="inside"><table class="form-table">';
			}
			
			else {
				// start a table row
				echo '<tr valign="top"><th scope="row"><label for="', $field['id'], '">', $field['label'], '</label></th>',
						'<td>';
						
					switch ($field['type']) {
						// ----------------
						// text
						// ----------------
						case 'text':
							echo '<input type="text" class="regular-text" name="recipress_options[', $field['id'], ']" id="', $field['id'], '" value="', $options[$field['id']] ? $options[$field['id']] : $field['value'], '" size="30" /> <span class="description">', $field['desc'], '</span>';
						break;
						// ----------------
						// checkbox
						// ----------------
						case 'checkbox':
							$checked = $field['checked'];
							if (isset($options[$field['id']]) && $options[$field['id']] != 1) $checked = '';
								else $checked = ' checked="checked"';
							echo '<input id="', $field['id'], '" name="recipress_options[', $field['id'], ']" type="checkbox" value="1" ', $checked, ' /> <label for="', $field['id'], '">', $field['desc'], '</label>';
						break;
						// ----------------
						// checkbox_group
						// ----------------
						case 'checkbox_group':
							foreach ($field['options'] as $option) {
								$checked = '';
								$the_option = $options[$field['id']];
								$the_value = $option['value'];
								if (((!isset($the_option) || $field['id'] == 'output') && $option['default'] == true) || ($the_option && in_array($the_value, $the_option)))
									$checked = ' checked="checked"';
								echo '<input id="', $option['value'], '" name="recipress_options[', $field['id'], '][]" type="checkbox" value="', $option['value'], '"',$checked, ' />', 
										' <label for="', $option['value'], '">', $option['label'], '</label><br />';
							}
							echo '<span class="description">', $field['desc'], '</span>';
						break;
						// ----------------
						// select
						// ----------------
						case 'select':
							echo '<select name="recipress_options[', $field['id'], ']">';
		
							$selected = $options[$field['id']];
							$p = '';
							$r = '';
							
							foreach ( $field['options'] as $option ) {
								$label = $option['label'];
								if ( $selected == $option['value'] ) // Make default first in list
									$p = "<option selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
								else
									$r .= "<option value='" . esc_attr( $option['value'] ) . "'>$label</option>";
							}
								echo $p . $r;
							
							echo '</select>',
								'<span class="description">', $field['desc'], '</span>';
						break;
						// ----------------
						// radio
						// ----------------
						case 'radio':
							echo '<fieldset><legend class="screen-reader-text"><span>', $field['label'], '</span></legend>';
								foreach ( $field['options'] as $option ) {
								$checked = '';
								$the_option = $options[$field['id']];
								$the_value = $option['value'];
								if ((!$the_option && $option['default'] == true) || ($the_option == $the_value))
									$checked = ' checked="checked"';
									echo '<label>',
											'<input type="radio" name="recipress_options[', $field['id'], ']" value="', $option['value'], '"', $checked, ' /> ', $option['label'], 
										 '</label>&nbsp;&nbsp;<span class="description"><small>', $option['desc'], '</small></span><br />';
								}
								echo '</fieldset>';
						break;
						// ----------------
						// image_radio
						// ----------------
						case 'image_radio':
							echo '<fieldset class="image_radio"><legend class="screen-reader-text"><span>', $field['label'], '</span></legend>';
								foreach ( $field['options'] as $option ) {
								$checked = '';
								$active = '';
								$the_option = $options[$field['id']];
								$the_value = $option['value'];
								if ((!$the_option && $option['default'] == true) || ($the_option == $the_value)) {
									$checked = ' checked="checked"';
									$active = ' class="active"';
								}
									echo '<label', $active,'>',
											'<input type="radio" name="recipress_options[', $field['id'], ']" value="', $option['value'], '"', $checked, ' />',
											'<img src="', RECIPRESS_URL ,'img/', $option['image'] ,'" alt="', $option['label'], '" />', 
										 '<strong>', $option['label'], '</strong><br />',
										 '<span class="description">', $option['desc'], '</span></label>';
								}
								echo '</fieldset>';
						break;
						// ----------------
						// textarea
						// ----------------
						case 'textarea':
							echo '<textarea id="recipress_options[', $field['id'], ']" name="recipress_options[', $field['id'], ']" class="small-text" style="resize:none" cols="40" rows="4">', $options[$field['id']] ? $options[$field['id']] : $field['value'], '</textarea>',
									'<br /><span class="description" for="recipress_options[', $field['id'], ']">', $field['desc'], '</span>';
						break;
						// ----------------
						// paypal
						// ----------------
						case 'paypal':
							echo '<p>Do you find this plugin useful and want to say thanks? </p>',
									'<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=tammyhartdesigns%40gmail%2ecom&item_name=Recipe%20Box%20Plugin%20Latte%20Fund&no_shipping=0&no_note=1&tax=0&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8" target="_blank" class="button">Donate</a>';
						break;
					} // end switch
					
				echo     '<td>',
					'</tr>';
			} // end if section else
		}  // end foreach
		echo '</table></div></div>';
		// close out the container
		echo '<p class="submit"><input type="submit" class="button-primary" value="Save Options" /></p>',
			'</form></div>';
}


/* Sanitize and validate input
------------------------------------------------------------------------- */
function recipress_validate( $input ) {
	global $fields;

	foreach ($fields as $field) {
		
		switch ($field['type']) {
			case 'checkbox':
				// Our checkbox value is either 0 or 1
				if ( ! isset( $input[$field['id']] ) )
					$input[$field['id']] = null;
				$input[$field['id']] = ( $input[$field['id']] == 1 ? 1 : 0 );
			break;

			case 'checkbox_group':
				// Our checkbox value is either an array of values or an empty array
				if ( ! isset( $input[$field['id']] ) )
					$input[$field['id']] = array();
			break;
			
			case 'text':
			case 'image':
				// Say our text option must be safe text with no HTML tags
				$input[$field['id']] = wp_filter_nohtml_kses( $input[$field['id']] );
			break;
			
			case 'select':
				// Our select option must actually be in our array of select options
				if ( ! array_key_exists( $input[$field['id']], $field['options'] ) )
					$input[$field['id']] = null;
			break;
			
			case 'radio':
				// Our radio option must actually be in our array of radio options
				if ( ! isset( $input[$field['id']] ) )
					$input[$field['id']] = null;
				if ( ! array_key_exists( $input[$field['id']], $field['options'] ) )
					$input[$field['id']] = null;
			break;
			
			case 'textarea':
				// Say our textarea option must be safe text with the allowed tags for posts
				$input[$field['id']] = wp_filter_post_kses( $input[$field['id']] );
			break;
			
		} // end switch
	} // end foreach

	return $input;
}

function recipress_options($field) {
	$options = get_option('recipress_options');
	return $options[$field];
}


?>