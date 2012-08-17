<?php


/* Load up the menu page
------------------------------------------------------------------------- */
add_action( 'admin_menu', 'recipress_add_page' );
function recipress_add_page() {
	add_menu_page( __('ReciPress Options', 'recipress'), __('ReciPress', 'recipress'), 'edit_others_posts', 'recipress_options', 'recipress_do_page' );
	add_submenu_page( 'recipress_options', __('ReciPress Options', 'recipress'), __('ReciPress Options', 'recipress'), 'edit_others_posts', 'recipress_options', 'recipress_do_page');
	add_submenu_page( 'recipress_options', __('Ingredients', 'recipress'), __('Ingredients', 'recipress'), 'edit_others_posts', 'edit-tags.php?taxonomy=ingredient');
	$taxonomies = recipress_use_taxonomies();
	if(!isset($taxonomies) || (isset($taxonomies) && in_array('cuisine', $taxonomies)))
		add_submenu_page( 'recipress_options', __('Cuisines', 'recipress'), __('Cuisines', 'recipress'), 'edit_others_posts', 'edit-tags.php?taxonomy=cuisine');
	if(!isset($taxonomies) || (isset($taxonomies) && in_array('course', $taxonomies)))
		add_submenu_page( 'recipress_options', __('Courses', 'recipress'), __('Courses', 'recipress'), 'edit_others_posts', 'edit-tags.php?taxonomy=course');
	if(!isset($taxonomies) || (isset($taxonomies) && in_array('skill_level', $taxonomies)))
		add_submenu_page( 'recipress_options', __('Skill Levels', 'recipress'), __('Skill Levels', 'recipress'), 'edit_others_posts', 'edit-tags.php?taxonomy=skill_level');
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
function recipress_options_fields() {
	$recipress_options_fields['outputSection'] =
		array(
			'label' => __('Recipe Output', 'recipress'),
			'type' 	=> 'section'
		);
	$recipress_options_fields['autoadd'] =
		array(
			'label' => __('Automatically Output Recipe', 'recipress'),
			'id' 	=> 'autoadd',
			'type' 	=> 'radio',
			'options'=> array(
				'yes'	=> array(
					'label'	=> __('Yes', 'recipress'),
					'value'	=> 'yes',
					'desc'	=> __('Recipe will output after the post content', 'recipress'),
					'default'=> true
				),
				'no'	=> array(
					'label'	=> __('No', 'recipress'),
					'value'	=> 'no',
					'desc'	=> __('Use the <code>[recipe]</code> shortcode to output the recipe', 'recipress')
				),
			)
		);
	$recipress_options_fields['output'] =
		array(
			'label' => __('Output Recipe On', 'recipress'),
			'id' 	=> 'output',
			'type' 	=> 'checkbox_group',
			'options'=> array(
				'home'	=> array(
					'label'	=> __('Home Page/Latest Posts Page', 'recipress'),
					'value'	=> 'home'
				),
				'single'	=> array(
					'label'	=> __('Single Post Page', 'recipress'),
					'value'	=> 'single',
					'default'=> true
				),
				'archive'	=> array(
					'label'	=> __('Archive and Category Pages', 'recipress'),
					'value'	=> 'archive'
				),
				'search'	=> array(
					'label'	=> __('Search Result Page', 'recipress'),
					'value'	=> 'search'
				),
			)
		);
	$recipress_options_fields['theme'] =
		array(
			'label' => __('Recipe Theme', 'recipress'),
			'id' 	=> 'theme',
			'type' 	=> 'image_radio',
			'options'=> array(
				'recipress-light'	=> array(
					'label'	=> __('Light', 'recipress'),
					'value'	=> 'recipress-light',
					'image'	=> 'theme-light.jpg',
					'desc'	=> __('For use with light themes', 'recipress'),
					'default' => true
				),
				'recipress-dark'	=> array(
					'label'	=> __('Dark', 'recipress'),
					'value'	=> 'recipress-dark',
					'image'	=> 'theme-dark.jpg',
					'desc'	=> __('For use with dark themes', 'recipress')
				),
				'recipress-recipress'	=> array(
					'label'	=> __('ReciPress', 'recipress'),
					'value'	=> 'recipress-recipress',
					'image'	=> 'theme-recipress.jpg',
					'desc'	=> __('Custom textured design', 'recipress')
				)
			)
		);
	$recipress_options_fields['instruction_image_size'] =
		array(
			'label'	=> __('Instruction Image Size', 'recipress'),
			'id' 	=> 'instruction_image_size',
			'type' 	=> 'radio',
			'options'=> array(
				'thumbnail'	=> array(
					'label'	=> __('Thumbnail', 'recipress'),
					'value'	=> 'thumbnail'
				),
				'medium'	=> array(
					'label'	=> __('Medium', 'recipress'),
					'value'	=> 'medium'
				),
				'large'	=> array(
					'label'	=> __('Large', 'recipress'),
					'value'	=> 'large',
					'default'=> true
				),
				'full'	=> array(
					'label'	=> __('Full', 'recipress'),
					'value'	=> 'full'
				)
			)
		);
	$recipress_options_fields['inputSection'] =
		array(
			'label' => __('Recipe Input', 'recipress'),
			'type' 	=> 'section'
		);
	$recipress_options_fields['post_type'] =
		array(
			'label'	=> __('Post Type', 'recipress'),
			'desc'	=> __('Name of post type to add recipes to', 'recipress'),
			'value'	=> 'post',
			'size'	=> 'medium',
			'id'	=> 'post_type',
			'type'	=> 'text'
		);
	$recipress_options_fields['taxonomies'] =
		array(
			'label' => __('Use Taxonomies', 'recipress'),
			'id' 	=> 'taxonomies',
			'type' 	=> 'checkbox_group',
			'options'=> array(
				'cuisine'	=> array(
					'label'	=> __('Cuisine', 'recipress'),
					'value'	=> 'cuisine',
					'default'=> true
				),
				'course'	=> array(
					'label'	=> __('Course', 'recipress'),
					'value'	=> 'course',
					'default'=> true
				),
				'skill_level'	=> array(
					'label'	=> __('Skill Level', 'recipress'),
					'value'	=> 'skill_level',
					'default'=> true
				)
			)
		);
	$recipress_options_fields['cost_field'] =
		array(
			'label' => __('Cost of Recipe Field', 'recipress'),
			'id' 	=> 'cost_field',
			'type' 	=> 'radio',
			'options'=> array(
				'yes'	=> array(
					'label'	=> __('Yes', 'recipress'),
					'value'	=> 'yes',
					'desc'	=> __('An input for the total cost to make the recipe will be available', 'recipress')
				),
				'no'	=> array(
					'label'	=> __('No', 'recipress'),
					'value'	=> 'no',
					'desc'	=> __('This field will be omitted when creating recipes', 'recipress'),
					'default'=> true
				),
			)
		);
	$recipress_options_fields['thanksSection'] =
		array(
			'label' => __('Say Thanks', 'recipress'),
			'type' 	=> 'section'
		);
	$recipress_options_fields['credit'] =
		array(
			'label'	=> __('Plugin Credit', 'recipress'),
			'type'	=> 'checkbox',
			'id'	=> 'credit',
			'desc'	=> __('Add a credit link to the recipe box.', 'recipress'),
			'checked' => ' checked="checked"'
		);
	$recipress_options_fields['paypal'] =
		array(
			'label'	=> __('Buy me a latte!', 'recipress'),
			'type'	=> 'paypal'
		);

	return apply_filters('recipress_option_fields', $recipress_options_fields);
}

// post thumbnails
add_filter('recipress_option_fields', 'recipress_insert_thumbnails');
function recipress_insert_thumbnails($recipress_options_fields) {
	$thumbnails = array(
		'thumbnails' => array(
			'label'	=> __('Use Featured Image', 'recipress'),
			'id' 	=> 'use_photo',
			'type' 	=> 'radio',
			'options'=> array(
				'yes'	=> array(
					'label'	=> __('Yes', 'recipress'),
					'value'	=> 'yes',
					'desc'	=> __('Recipe photo will be the same as the post&rsquo;s featured image', 'recipress'),
					'default'=> true
				),
				'no'	=> array(
					'label'	=> __('No', 'recipress'),
					'value'	=> 'no',
					'desc'	=> __('Use a different image for the recipe', 'recipress')
				),
			)
		)
	);
	
	if(current_theme_supports('post-thumbnails')) 
    	return recipress_array_insert($recipress_options_fields, 'theme', $thumbnails);
	else
		return $recipress_options_fields;
}

/* Create the options page
------------------------------------------------------------------------- */
function recipress_do_page() {
	$fields = recipress_options_fields();

	if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;

	echo '<div class="wrap">';
	screen_icon();
	echo '<h2>'.__('ReciPress Options', 'recipress').'</h2>';

		if ( false !== $_REQUEST['settings-updated'] )
			echo '<div class="updated fade"><p><strong>'.__('Options saved', 'recipress').'</strong></p></div>';

		echo '<form method="post" id="recipress_options" class="form-table" action="options.php">';
			settings_fields( 'thd_options' );
			// get the settings
			$options = get_option( 'recipress_options' );
			// start a loop through the fields
			$sections = 0;
			foreach ($fields as $field) {
				// values
				$label = $field['label'] ? $field['label'] : '';
				$desc = $field['desc'] ? '<span class="description">'.$field['desc'].'</span>' : '';
				$id = $field['id'] ? $field['id'] : '';
				$type = $field['type'] ? $field['type'] : '';
				$value = $field['value'] ? $field['value'] : '';
				$size = $field['size'] ? $field['size'] : '';
				$f_options = $field['options'] ? $field['options'] : '';
				
				if ($field['id'])
					$meta = $options[$id];
			// section titles
			if ($type == 'section') {
				$sections++;
				if ($sections > 1)
					echo '</table></div></div>';
				echo '<div class="postbox metabox-holder"><h3>'.$label.'</h3>';
				// start a table
				echo '<div class="inside"><table class="form-table">';
			}
			
			else {
				// start a table row
				echo '<tr valign="top"><th scope="row"><label for="'.$id.'">'.$label.'</label></th>
						<td>';
						
					switch ($type) {
						// ----------------
						// text
						// ----------------
						case 'text':
							echo '<input type="text" class="text-'.$size.'" name="recipress_options['.$id.']" id="'.$id.'" value="', $meta ? $meta : $value, '" size="30" /> '.$desc;
						break;
						// ----------------
						// checkbox
						// ----------------
						case 'checkbox':
							$checked = $field['checked'];
							if (isset($meta) && $meta != 1) $checked = '';
								else $checked = ' checked="checked"';
							echo '<input id="'.$id.'" name="recipress_options['.$id.']" type="checkbox" value="1"'.$checked.' /> <label for="'.$id.'">'.$desc.'</label>';
						break;
						// ----------------
						// checkbox_group
						// ----------------
						case 'checkbox_group':
							foreach ($f_options as $option) {
								$checked = '';
								$the_option = $meta;
								$the_value = $option['value'];
								if (((!isset($the_option) || $id == 'output') && $option['default'] == true) || ($the_option && in_array($the_value, $the_option)))
									$checked = ' checked="checked"';
								echo '<input id="'.$option['value'].'" name="recipress_options['.$id.'][]" type="checkbox" value="'.$option['value'].'"'.$checked.' />', 
										' <label for="'.$option['value'].'">'.$option['label'].'</label><br />';
							}
							echo $desc;
						break;
						// ----------------
						// select
						// ----------------
						case 'select':
							echo '<select name="recipress_options['.$id.']">';
		
							$selected = $meta;
							$p = '';
							$r = '';
							
							foreach ( $f_options as $option ) {
								$label = $option['label'];
								if ( $selected == $option['value'] ) // Make default first in list
									$p = "<option selected='selected' value='".esc_attr( $option['value'] )."'>$label</option>";
								else
									$r .= "<option value='".esc_attr( $option['value'] )."'>$label</option>";
							}
								echo $p . $r;
							
							echo '</select>
								'.$desc;
						break;
						// ----------------
						// radio
						// ----------------
						case 'radio':
							echo '<fieldset><legend class="screen-reader-text"><span>'.$label.'</span></legend>';
								foreach ( $f_options as $option ) {
								$checked = '';
								$the_option = $meta;
								$the_value = $option['value'];
								if ((!$the_option && $option['default'] == true) || ($the_option == $the_value))
									$checked = ' checked="checked"';
									echo '<label>
											<input type="radio" name="recipress_options['.$id.']" value="'.$option['value'].'"'.$checked.' /> '.$option['label']. 
										 '</label>&nbsp;&nbsp;<span class="description"><small>'.$option['desc'].'</small></span><br />';
								}
								echo '</fieldset>';
						break;
						// ----------------
						// image_radio
						// ----------------
						case 'image_radio':
							echo '<fieldset class="image_radio"><legend class="screen-reader-text"><span>'.$label.'</span></legend>';
							foreach ( $f_options as $option ) {
								$label = $option['label'];
								$value = $option['value'];
								$image = $option['image'];
								$desc = $option['desc'];
								$default = $option['default'];
								$checked = '';
								$active = '';
								if ((!isset($meta) && $default == true) || ($meta == $value)) {
									$checked = ' checked="checked"';
									$active = ' class="active"';
								}
									echo '<label'.$active.'>
											<input type="radio" name="recipress_options['.$id.']" value="'.$value.'"'.$checked.' />
											<img src="'.RECIPRESS_URL.'img/'.$image.'" alt="'.$label.'" />
										 <strong>'.$label.'</strong><br />
										 <span class="description">'.$desc.'</span></label>';
								}
								echo '</fieldset>';
						break;
						// ----------------
						// textarea
						// ----------------
						case 'textarea':
							echo '<textarea id="recipress_options['.$id.']" name="recipress_options['.$id.']" class="small-text" style="resize:none" cols="40" rows="4">
										', $meta ? $meta : $value, '</textarea>
									<br /><span class="description" for="recipress_options['.$id.']">'.$desc.'</span>';
						break;
						// ----------------
						// paypal
						// ----------------
						case 'paypal':
							echo '<p>'.__('Do you find this plugin useful and want to say thanks?', 'recipress').'</p>
									<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=tammyhartdesigns%40gmail%2ecom&item_name=Recipe%20Box%20Plugin%20Latte%20Fund&no_shipping=0&no_note=1&tax=0&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8" target="_blank" class="button">'.__('Donate', 'recipress').'</a>';
						break;
					} // end switch
					
				echo     '<td>
					</tr>';
			} // end if section else
		}  // end foreach
		echo '</table></div></div>';
		// close out the container
		echo '<p class="submit"><input type="submit" class="button-primary" value="'.__('Save Options', 'recipress').'" /></p>
			</form></div>';
}


/* Sanitize and validate input
------------------------------------------------------------------------- */
function recipress_validate( $input ) {
	$fields = recipress_options_fields();

	foreach ($fields as $field) {
		$id = $field['id'];
		
		switch ($field['type']) {
			case 'checkbox':
				// Our checkbox value is either 0 or 1
				if ( ! isset( $input[$id] ) )
					$input[$id] = null;
				$input[$id] = ( $input[$id] == 1 ? 1 : 0 );
			break;

			case 'checkbox_group':
				// Our checkbox value is either an array of values or an empty array
				if ( ! isset( $input[$id] ) )
					$input[$id] = array();
			break;
			
			case 'text':
			case 'image':
				// Say our text option must be safe text with no HTML tags
				$input[$id] = wp_filter_nohtml_kses( $input[$id] );
			break;
			
			case 'select':
				// Our select option must actually be in our array of select options
				if ( ! array_key_exists( $input[$id], $field['options'] ) )
					$input[$id] = null;
			break;
			
			case 'radio':
				// Our radio option must actually be in our array of radio options
				if ( ! isset( $input[$id] ) )
					$input[$id] = null;
				if ( ! array_key_exists( $input[$id], $field['options'] ) )
					$input[$id] = null;
			break;
			
			case 'textarea':
				// Say our textarea option must be safe text with the allowed tags for posts
				$input[$id] = wp_filter_post_kses( $input[$id] );
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