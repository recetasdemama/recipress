<?


/* Load up the menu page
------------------------------------------------------------------------- */
add_action( 'admin_menu', 'recipress_add_page' );
function recipress_add_page() {
	add_menu_page( 'ReciPress Options', 'ReciPress', 'edit_others_posts', 'recipress_options', 'recipress_do_page' );
	add_submenu_page( 'recipress_options', 'ReciPress Options', 'ReciPress Options', 'edit_others_posts', 'recipress_options', 'recipress_do_page');
	add_submenu_page( 'recipress_options', 'Ingredients', 'Ingredients', 'edit_others_posts', 'edit-tags.php?taxonomy=ingredient');
	add_submenu_page( 'recipress_options', 'Cuisines', 'Cuisines', 'edit_others_posts', 'edit-tags.php?taxonomy=cuisine');
	add_submenu_page( 'recipress_options', 'Courses', 'Courses', 'edit_others_posts', 'edit-tags.php?taxonomy=course');
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
				'desc'	=> 'Use the <code>[recipe]</code> shortcut to output the recipe'
			),
		)
	),
	array(
		'label' => 'Output Recipe',
		'id' 	=> 'output',
		'type' 	=> 'checkbox_group',
		'options'=> array(
			'home'	=> array(
				'label'	=> 'Home Page/Latest Posts Page',
				'value'	=> 'home',
			),
			'single'	=> array(
				'label'	=> 'Single Post Page',
				'value'	=> 'single',
				'default'=> true
			),
			'archive'	=> array(
				'label'	=> 'Archive and Category Pages',
				'value'	=> 'archive',
			),
			'search'	=> array(
				'label'	=> 'Search Result Page',
				'value'	=> 'search',
			),
		)
	),
	array(
		'label' => 'Recipe Theme',
		'id' 	=> 'theme',
		'type' 	=> 'select',
		'options'=> array(
			'recipress-light'	=> array(
				'label'	=> 'Light',
				'value'	=> 'recipress-light',
			),
			'recipress-dark'	=> array(
				'label'	=> 'Dark',
				'value'	=> 'recipress-dark'
			),
			'recipress-recipress'	=> array(
				'label'	=> 'ReciPress',
				'value'	=> 'recipress-recipress'
			)
		)
	),
	array(
		'label' => 'Other',
		'type' 	=> 'section'
	),
	array(
		'label' => 'Measurements',
		'id' 	=> 'measurements',
		'type' 	=> 'textarea',
		'desc' 	=> 'List measurement options each on a separate line.',
		'value'	=> 'teaspoons
tablespoons
ounces
pounds
cups
cans
jars
boxes
packages'
	)
);


/* Create the options page
------------------------------------------------------------------------- */
function recipress_do_page() {
	global $fields;

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
			// start a table
			echo '<table id="options" class="form-table">';
			// start a loop through the fields
			foreach ($fields as $field) {
			// section titles
			if ($field['type'] == 'section') {
				echo '<tr><td colspan="2"><h3 class="title">', $field['label'], '</h3></td></tr>';
			}
			
			else {
				// start a table row
				echo '<tr valign="top"><th scope="row"><label for="', $field['id'], '">', $field['label'], '</label></th>',
						'<td>';
						
					switch ($field['type']) {
						// text
						case 'text':
							echo '<input type="text" class="regular-text" name="recipress_options[', $field['id'], ']" id="', $field['id'], '" value="', $options[$field['id']] ? $options[$field['id']] : $field['value'], '" size="30" /> <span class="description">', $field['desc'], '</span>';
						break;
						// checkbox
						case 'checkbox':
							echo '<input id="', $field['id'], '" name="recipress_options[', $field['id'], ']" type="checkbox" value="1" ', 1 == $options[$field['id']] ? ' checked="checked"' : '', ' /> <label for="', $field['id'], '">', $field['desc'], '</label>';
						break;
						// checkbox_group
						case 'checkbox_group':
							foreach ($field['options'] as $option) {
								$checked = '';
								$the_option = $options[$field['id']];
								$the_value = $option['value'];
								if ((!$the_option && $option['default'] == true) || ($the_option && in_array($the_value, $the_option)))
									$checked = ' checked="checked"';
								echo '<input id="', $option['value'], '" name="recipress_options[', $field['id'], '][]" type="checkbox" value="', $option['value'], '"',$checked, ' />', 
										' <label for="', $option['value'], '">', $option['label'], '</label><br />';
							}
							echo '<span class="description">', $field['desc'], '</span>';
						break;
						// select
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
						// radio
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
						// textarea
						case 'textarea':
							echo '<textarea id="recipress_options[', $field['id'], ']" name="recipress_options[', $field['id'], ']" class="small-text" style="resize:none" cols="40" rows="4">', $options[$field['id']] ? $options[$field['id']] : $field['value'], '</textarea>',
									'<br /><span class="description" for="recipress_options[', $field['id'], ']">', $field['desc'], '</span>';
						break;
					} // end switch
					
				echo     '<td>',
					'</tr>';
			} // end if section else
		}  // end foreach
		echo '</table>';
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