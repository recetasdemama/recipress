<?


/* Add Meta Box
------------------------------------------------------------------------- */
add_action('admin_menu', 'recipe_add_box');
function recipe_add_box() {
    global $meta_fields;
    add_meta_box('recipress', 'Recipe', 'recipe_show_box', 'post', 'normal', 'high');
}

/* Custom Fields
------------------------------------------------------------------------- */
$meta_fields = array(
	array(
		'name'	=> 'Recipe Title',
		'desc'	=> 'Do you want to give the recipe a different title from the post?',
		'place'	=> '',
		'size'	=> 'large',
		'id'	=> 'title',
		'type'	=> 'text'
	),
	array(
		'name'	=> 'Recipe Summary',
		'desc'	=> 'A small summary of the recipe',
		'place'	=> '',
		'size'	=> 'small',
		'id'	=> 'summary',
		'type'	=> 'textarea'
	),
	array(
		'name'	=> 'Cuisine',
		'id'	=> 'cuisine',
		'type'	=> 'tax_select'
	),
	array(
		'name'	=> 'Course',
		'id'	=> 'course',
		'type'	=> 'tax_select'
	),
	array(
		'name'	=> 'Skill Level',
		'id'	=> 'skill_level',
		'type'	=> 'tax_select'
	),
	array(
		'name'	=> 'Yield',
		'desc'	=> 'How much/many does this recipe produce?',
		'place'	=> 'e.g., 1 loaf, 2 cups',
		'size'	=> 'medium',
		'id'	=> 'yield',
		'type'	=> 'text'
	),
	array(
		'name'	=> 'Servings',
		'desc'	=> 'How many servings?',
		'place'	=> '00',
		'size'	=> 'small',
		'id'	=> 'servings',
		'type'	=> 'text'
	),
	array(
		'name'	=> 'Prep Time',
		'desc'	=> 'How many minutes? (60+ minutes will output as hours)',
		'place'	=> '00',
		'size'	=> 'small',
		'id'	=> 'prep_time',
		'type'	=> 'text'
	),
	array(
		'name'	=> 'Cook Time',
		'desc'	=> 'How many minutes? (60+ minutes will output as hours)',
		'place'	=> '00',
		'size'	=> 'small',
		'id'	=> 'cook_time',
		'type'	=> 'text'
	),
	array(
		'name'	=> 'Ingredients',
		'desc'	=> 'Click the plus icon to add another ingredient. <a href="'.get_bloginfo('home').'/wp-admin/edit-tags.php?taxonomy=ingredient">Manage Ingedients</a>',
		'id'	=> 'ingredient',
		'type'	=> 'ingredient'
	),
	array(
		'name'	=> 'Instructions',
		'desc'	=> 'Click the plus icon to add another instruction.',
		'id'	=> 'instruction',
		'type'	=> 'instruction'
	)
);
	
$measurements = recipress_options('measurements');
if ($measurements == '')
	$measurements = array(
		'',
		'teaspoons',
		'tablespoons',
		'ounces',
		'pounds',
		'cups',
		'cans',
		'jars',
		'boxes',
		'packages'
	);
else { $measurements = explode("\n", $measurements); array_unshift($measurements,''); }


/* The Callback
------------------------------------------------------------------------- */
function recipe_show_box() {
    global $meta_fields, $post, $measurements;
	// if post-thumbnails aren't supported, add a recipe photo
	if(!current_theme_supports('post-thumbnails')) 
		array_unshift($meta_fields,
		array(
			'name'	=> 'Photo',
			'desc'	=> 'Add a photo of your completed recipe',
			'id'	=> 'photo',
			'type'	=> 'image'
			));
    // Use nonce for verification
    echo '<input type="hidden" name="recipe_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	$hasRecipe_check = '';
	$hasRecipe = get_post_meta($post->ID, 'hasRecipe', true);
	if($hasRecipe == 'Yes') $hasRecipe_check = ' checked="checked"';
	echo '<p id="hasRecipe_box"><input type="checkbox"'.$hasRecipe_check.' id="hasRecipe" name="hasRecipe" value="Yes" /><label for="hasRecipe">Add a Recipe to this post?</label></p>';
    echo '<div id="recipress_table"><table class="form-table">';
    foreach ($meta_fields as $field) {
		if ($field['type'] == 'section') {
			echo '<tr>',
					'<td colspan="2">',
						'<h2>', $field['label'], '</h2>',
					'</td>',
				'</tr>';
		}
		else {
        // get current post meta data
        $meta = get_post_meta($post->ID, $field['id'], true);
        echo '<tr>',
                '<th style="width:20%"><label for="', $field['id'], '">', $field['name'], '</label></th>',
                '<td>';
				
        switch ($field['type']) {
			// ----------------
			// tax_select
			// ----------------
            case 'tax_select':
                echo '<select name="', $field['id'], '" id="', $field['id'], '">',
						'<option value="">Select One</option>'; // Select One
				$terms = get_terms($field['id'], 'get=all');
				$selected = wp_get_object_terms($post->ID, $field['id']);
                foreach ($terms as $term) {
                    if (!is_wp_error($selected) && !empty($selected) && !strcmp($term->slug, $selected[0]->slug)) 
						echo '<option value="' . $term->slug . '" selected="selected">' . $term->name . '</option>'; 
					else
						echo '<option value="' . $term->slug . '">' . $term->name . '</option>'; 
                }
                echo '</select>', '&nbsp;&nbsp;<span class="description"><a href="'.get_bloginfo('home').'/wp-admin/edit-tags.php?taxonomy=', $field['id'], '">Manage ', $field['name'], 's</a></span>';
			break;
			// ----------------
			// ingredient
			// ----------------
            case 'ingredient':
                echo '<table id="ingredients_table" cellpadding="0" cellspacing="0">',
						'<thead><tr>
							<th class="left_corner"><span class="sort_label"></span></th>
							<th>Amount</th>
							<th>Measurement</th>
							<th>Ingredient</th>
							<th>Notes</th>
							<th class="right_corner"><a class="ingredient_add" href="#"></a></th>
						</tr></thead>',
						'<tbody>';
				$i = 0;
				if($meta != '') {
					foreach($meta as $row) {
						echo '<tr>',
							'<td><span class="sort"></span></td>', // sort
							'<td><input type="text" class="text-small" step="0.25" placeholder="0" name="ingredient['.$i.'][amount]" id="ingredient_amount_'.$i.'" value="', $row['amount'],'" size="3" /></td>', //amount
							'<td><select name="ingredient['.$i.'][measurement]" id="ingredient_measurement_'.$i.'">';
							foreach($measurements as $measurement) {
								$measurement = trim($measurement);
								$selected = '';
								if($row['measurement'] == $measurement) $selected = ' selected="selected"';
								echo '<option', $selected , ' value="',$measurement,'">',$measurement,'</option>';
							}
							echo '</select></td>', //measurement
							'<td><input type="text" name="ingredient['.$i.'][ingredient]" id="ingredient_'.$i.'" onfocus="setSuggest(\'ingredient_'.$i.'\');" value="', $row['ingredient'],'" size="30" class="ingredient" placeholder="start typing an ingredient" /></td>', // ingredient
							'<td><input type="text" name="ingredient['.$i.'][notes]" id="ingredient_notes_'.$i.'" value="', $row['notes'],'" size="30" class=" " placeholder="e.g., chopped, sifted, fresh" /></td>', // notes
							'<td><a class="ingredient_remove" href="#"></a></td>', // remove
						'</tr>';
						$i++;
					}
				} else {
						echo '<tr>',
							'<td><span class="sort"></span></td>', // sort
							'<td><input type="number" step="1" placeholder="0" name="ingredient['.$i.'][amount]" id="ingredient_amount_'.$i.'" value="" size="3" /></td>', //amount
							'<td><select name="ingredient['.$i.'][measurement]" id="ingredient_measurement_'.$i.'">';
							foreach($measurements as $measurement) {
								echo '<option value="',$measurement,'">',$measurement,'</option>';
							}
							echo '</select></td>', //measurement
							'<td><input type="text" name="ingredient['.$i.'][ingredient]" id="ingredient_'.$i.'" onfocus="setSuggest(\'ingredient_'.$i.'\');" value="" size="30" class="ingredient" placeholder="start typing an ingredient" /></td>', // ingredient
							'<td><input type="text" name="ingredient['.$i.'][notes]" id="ingredient_notes_'.$i.'" value="" size="30" class=" " placeholder="e.g., chopped, fresh, etc." /></td>', // notes
							'<td><a class="ingredient_remove" href="#"></a></td>', // remove
						'</tr>';
				}
				echo '</tbody></table>',
					'<span class="description">', $field['desc'], '</span>';
            break;
			// ----------------
			// instruction
			// ----------------
            case 'instruction':
                echo '<table id="instructions_table" cellpadding="0" cellspacing="0">',
						'<thead><tr>',
							'<th class="left_corner"><span class="sort_label"></span></th>',
							'<th class="textcenter">Step</th>
							<th>Description</th>',
							//<th>Image</th>
							'<th class="right_corner"><a class="instruction_add" href="#"></a></th>
						</tr></thead>',
						'<tbody>';
				$i = 0;
				$image = RECIPRESS_URL.'img/image.png';
				if($meta != '') {
					foreach($meta as $row) {
						echo '<tr id="insutrction_row-'.$i.'">',
							'<td><span class="sort"></span></td>', // sort
							'<td class="step"><span>',$i+1,'</span></td>', // step
							'<td><textarea placeholder="Describe this step in the recipe" class="instruction" name="instruction['.$i.'][description]" cols="50" rows="4" id="ingredient_description_'.$i.'">'. $row['description'].'</textarea></td>', // description
							/*'<td><input name="instruction['.$i.'][image]" type="hidden" class="upload_image instruction" value="'.$row['image'].'" /><img src="';
					if($row['image']) echo $row['image']; else echo $image;
						echo '" class="preview_image" width="170" alt="" />
								<input class="upload_image_button" type="button" value="Upload Image" />
							</td>', // image*/
							'<td><a class="instruction_remove" href="#"></a></td>', //remove
						'</tr>';
						$i++;
					}
				} else {
						echo '<tr id="instruction_row-'.$i.'">',
							'<td><span class="sort"></span></td>', // sort
							'<td class="step"><span>',$i+1,'</span></td>', //step
							'<td><textarea placeholder="Describe this step in the recipe" class="instruction" type="text" name="instruction['.$i.'][description]" cols="77" rows="4" id="ingredient_description_'.$i.'"></textarea></td>', // description
							/*'<td><input name="instruction['.$i.'][image]" type="hidden" class="upload_image instruction" value="" />',
							'<img src="'.$image.'" class="preview_image" width="170" alt="" />
								<input class="upload_image_button" type="button" value="Upload Image" />
							</td>', // image*/
							'<td><a class="instruction_remove" href="#"></a></td>', //remove
						'</tr>';
				}
				echo '</tbody></table>',
					'<span class="description">', $field['desc'], '</span>';
            break;
			// ----------------
			// text
			// ----------------
            case 'text':
                echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', $meta ? $meta : $field['value'],'" class="text-', $field['size'] ,'" size="30" placeholder="', $field['place'], '" />', '&nbsp;&nbsp;<span class="description">', $field['desc'], '</span>';
            break;
			// ----------------
			// textarea
			// ----------------
            case 'textarea':
                echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" class="text-', $field['size'] ,'">', $meta ? $meta : $field['value'], '</textarea>', 
						'&nbsp;&nbsp;<span class="description">', $field['desc'], '</span>';
            break;
			// ----------------
			// select
			// ----------------
            case 'select':
                echo '<select name="', $field['id'], '" id="', $field['id'], '">';
                foreach ($field['options'] as $option) {
                    echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="', $option['value'],'">', $option['label'], '</option>';
                }
                echo '</select>', '&nbsp;&nbsp;<span class="description">', $field['desc'], '</span>';
            break;
			// ----------------
			// radio
			// ----------------
            case 'radio':
				echo '<fieldset><legend class="screen-reader-text"><span>', $field['label'], '</span></legend>';
					
					foreach ( $field['options'] as $option ) {
							
						echo '<label>',
								'<input type="radio" name="', $field['id'], '" value="', esc_attr_e( $option['value'] ), '" ', $meta == $option['value'] ? ' checked="checked"' : '', ' /> ', $option['label'], 
							 '</label><br />';
					}
					echo '</fieldset>';
            break;
			// ----------------
			// checkbox
			// ----------------
            case 'checkbox':
                echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' /> <label for="', $field['id'], '">', $field['desc'], '</label>';
            break;
			// ----------------
			// checkbox_group
			// ----------------
            case 'checkbox_group':
				foreach ($field['options'] as $option) {
                	echo '<input type="checkbox" value="', $option, '" name="', $field['id'], '[]" id="', $option, '"', $meta && in_array($option, $meta) ? ' checked="checked"' : '', ' />', 
							' <label for="', $option, '">', $option, '</label><br />';
				}
				echo '<span class="description">', $field['desc'], '</span>';
            break;
			// ----------------
			// image
			// ----------------
			case 'image':
				$image = RECIPRESS_URL.'img/image.png';	
				if($meta)  { $image = wp_get_attachment_image_src($meta, 'medium');	$image = $image[0]; }				
				echo	'<input name="', $field['id'], '" type="hidden" class="upload_image" value="', $meta, '" />',
							'<img src="'.$image.'" class="preview_image" alt="" />
								<input class="upload_image_button" type="button" value="Upload Image" /><br />
								<input class="clear_image_button" type="button" value="Remove Image" />
								<br clear="all" /><span class="description">', $field['desc'], '</span>';
			break;
        }
        echo     '<td>',
            '</tr>';
		}
    }
    echo '</table></div>';
}


/* Save the Data
------------------------------------------------------------------------- */
add_action('save_post', 'recipe_save_data');
// Save data from meta box
function recipe_save_data($post_id) {
    global $meta_fields;
	// if post-thumbnails aren't supported, add a recipe photo
	if(!current_theme_supports('post-thumbnails')) 
		array_unshift($meta_fields,
		array(
			'id'	=> 'photo'
			));
	// set the value of hasRecipe
	$hasRecipe_old = get_post_meta($post_id, $field['id'], true);
	$hasRecipe_new = $_POST['hasRecipe'];
	if ($hasRecipe_new && $hasRecipe_new != $hasRecipe_old) {
		update_post_meta($post_id, 'hasRecipe', $hasRecipe_new);
	} elseif ('' == $hasRecipe_new && $hasRecipe_old) {
		delete_post_meta($post_id, 'hasRecipe', $hasRecipe_old);
	}
	// determine if a recipe was added
	if ($hasRecipe_new == 'Yes') {
		// verify nonce
		if (!wp_verify_nonce($_POST['recipe_meta_box_nonce'], basename(__FILE__))) 
			return $post_id;
		// check autosave
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
			return $post_id;
		// check permissions
		if ('page' == $_POST['post_type']) {
			if (!current_user_can('edit_page', $post_id))
				return $post_id;
		} elseif (!current_user_can('edit_post', $post_id)) {
			return $post_id;
		}
		foreach ($meta_fields as $field) {
			if($field['type'] == 'tax_select') continue;
			$old = get_post_meta($post_id, $field['id'], true);
			$new = $_POST[$field['id']];
			if ($new && $new != $old) {
				update_post_meta($post_id, $field['id'], $new);
			} elseif ('' == $new && $old) {
				delete_post_meta($post_id, $field['id'], $old);
			}
		}
		
		// save taxonomies
		$post = get_post($post_id);
		if (($post->post_type == 'post')) { 
			$the_ingredients = $_POST['ingredient'];
			foreach($the_ingredients as $the_ingredient) {
					$ingredients[] = $the_ingredient['ingredient'];
			}
			wp_set_object_terms( $post_id, $ingredients, 'ingredient' );
			$cuisine = $_POST['cuisine'];
			$course = $_POST['course'];
			$skill_level = $_POST['skill_level'];
			wp_set_object_terms( $post_id, $cuisine, 'cuisine' );
			wp_set_object_terms( $post_id, $course, 'course' );
			wp_set_object_terms( $post_id, $skill_level, 'skill_level' );
			}
	}
}
?>