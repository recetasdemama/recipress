<?php


/* Add Meta Box
------------------------------------------------------------------------- */
add_action('admin_menu', 'recipe_add_box');
function recipe_add_box() {
    global $meta_fields;
    add_meta_box('recipress', __('Recipe', 'recipress'), 'recipe_show_box', recipress_post_type(), 'normal', 'high');
}


/* Custom Fields
------------------------------------------------------------------------- */
function recipress_fields() {
	$meta_fields['title'] =
		array(
			'label'	=> __('Recipe Title', 'recipress'),
			'desc'	=> __('Do you want to give the recipe a different title from the post?', 'recipress'),
			'place'	=> '',
			'size'	=> 'large',
			'id'	=> 'title',
			'type'	=> 'text'
		);
	$meta_fields['summary'] =
		array(
			'label'	=> __('Recipe Summary', 'recipress'),
			'desc'	=> __('A small summary of the recipe', 'recipress'),
			'place'	=> '',
			'size'	=> 'small',
			'id'	=> 'summary',
			'type'	=> 'textarea'
		);
	$meta_fields['yield'] =
		array(
			'label'	=> __('Yield', 'recipress'),
			'desc'	=> __('How much/many does this recipe produce?', 'recipress'),
			'place'	=> __('e.g., 1 loaf, 2 cups', 'recipress'),
			'size'	=> 'medium',
			'id'	=> 'yield',
			'type'	=> 'text'
		);
	$meta_fields['servings'] =
		array(
			'label'	=> __('Servings', 'recipress'),
			'desc'	=> __('How many servings?', 'recipress'),
			'place'	=> '00',
			'size'	=> 'small',
			'id'	=> 'servings',
			'type'	=> 'text'
		);
	$meta_fields['prep_time'] =
		array(
			'label'	=> __('Prep Time', 'recipress'),
			'desc'	=> __('How many minutes? (60+ minutes will output as hours)', 'recipress'),
			'place'	=> '00',
			'size'	=> 'small',
			'id'	=> 'prep_time',
			'type'	=> 'text'
		);
	$meta_fields['cook_time'] =
		array(
			'label'	=> __('Cook Time', 'recipress'),
			'desc'	=> __('How many minutes? (60+ minutes will output as hours)', 'recipress'),
			'place'	=> '00',
			'size'	=> 'small',
			'id'	=> 'cook_time',
			'type'	=> 'text'
		);
	$meta_fields['other_time'] =
		array(
			'label'	=> __('Other Time', 'recipress'),
			'desc'	=> __('For calculating a proper ready time. How many minutes? (60+ minutes will output as hours)', 'recipress'),
			'place'	=> '00',
			'size'	=> 'small',
			'id'	=> 'other_time',
			'type'	=> 'text'
		);
	$meta_fields['ingredient'] =
		array(
			'label'	=> __('Ingredients', 'recipress'),
			'desc'	=> sprintf( __( 'Click the plus icon to add another ingredient. %1$sManage Ingedients%2$s', 'recipress' ), '<a href="'.get_bloginfo('home').'/wp-admin/edit-tags.php?taxonomy=ingredient">', '</a>' ),
			'id'	=> 'ingredient',
			'type'	=> 'ingredient'
		);
	$meta_fields['instruction'] =
		array(
			'label'	=> __('Instructions', 'recipress'),
			'desc'	=> __('Click the plus icon to add another instruction.', 'recipress'),
			'id'	=> 'instruction',
			'type'	=> 'instruction'
		);
	
	return apply_filters('recipress_fields',$meta_fields);
}

// if post-thumbnails aren't supported, add a recipe photo
add_filter('recipress_fields', 'recipress_insert_photo');
function recipress_insert_photo($meta_fields) {
	$photo = array(
		'photo' => array(
			'label'	=> __('Photo', 'recipress'),
			'desc'	=> __('Add a photo of your completed recipe', 'recipress'),
			'id'	=> 'photo',
			'type'	=> 'image'
		)
	);
	
	if(recipress_add_photo()) 
    	return recipress_array_insert($meta_fields, 'title', $photo, true);
	else
		return $meta_fields;
}

// add taxonomies
add_filter('recipress_fields', 'recipress_insert_taxonomies');
function recipress_insert_taxonomies($meta_fields) {
	$taxonomies = recipress_use_taxonomies();
	if(!isset($taxonomies)) 
		$taxonomies = array('cuisine', 'course', 'skill_level');
	if($taxonomies != '') {
		foreach ($taxonomies as $taxonomy) {
			$tax_name = '';
			if($taxonomy == 'cuisine') $tax_name = __('Cuisine', 'recipress');
			if($taxonomy == 'course') $tax_name = __('Course', 'recipress');
			if($taxonomy == 'skill_level') $tax_name = __('Skill Level', 'recipress');
			
			$add_taxonomies[$taxonomy] = array(
					'name'	=> $tax_name,
					'id'	=> $taxonomy,
					'type'	=> 'tax_select'
			);
		}
	}
	
	if($add_taxonomies) 
    	return recipress_array_insert($meta_fields, 'summary', $add_taxonomies);
	else
		return $meta_fields;
}

// add cost field
add_filter('recipress_fields', 'recipress_insert_cost');
function recipress_insert_cost($meta_fields) {	
	$cost = array(
		'cost' => array(
			'label'	=> __('Cost', 'recipress'),
			'desc'	=> __('What does it cost to make this recipe?', 'recipress'),
			'place'	=> __('$0.00', 'recipress'),
			'size'	=> 'medium',
			'id'	=> 'cost',
			'type'	=> 'text'
		)
	);
	
	if(recipress_options('cost_field') == 'yes')
    	return recipress_array_insert($meta_fields, 'yield', $cost, true);
	else
		return $meta_fields;
}


/* The Callback
------------------------------------------------------------------------- */
function recipe_show_box() {
    global $post;
	$meta_fields = recipress_fields();
	
    // Use nonce for verification
    echo '<input type="hidden" name="recipe_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
	
	$hasRecipe_check = '';
	$hasRecipe = get_post_meta($post->ID, 'hasRecipe', true);
	if($hasRecipe == 'Yes')
		$hasRecipe_check = ' checked="checked"';
	echo '<p id="hasRecipe_box"><input type="checkbox"'.$hasRecipe_check.' id="hasRecipe" name="hasRecipe" value="Yes" /><label for="hasRecipe">'.__('Add a recipe?', 'recipress').'</label></p>';
	
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
        // get current field data
		$label = $field['label'] ? $field['label'] : '';
		$desc = $field['desc'] ? '<span class="description">'.$field['desc'].'</span>' : '';
		$place = $field['place'] ? $field['place'] : '';
		$size = $field['size'] ? $field['size'] : '';
		$id = $field['id'] ? $field['id'] : '';
		$type = $field['type'] ? $field['type'] : '';
		
        $meta = get_post_meta($post->ID, $id, true);
        echo '<tr>',
                '<th style="width:20%"><label for="'.$id.'">'.$label.'</label></th>',
                '<td>';
				
        switch ($field['type']) {
			// ----------------
			// tax_select
			// ----------------
            case 'tax_select':
                echo '<select name="'.$id.'" id="'.$id.'">',
						'<option value="">'.__('Select One', 'recipress').'</option>'; // Select One
				$terms = get_terms($id, 'get=all');
				$selected = wp_get_object_terms($post->ID, $id);
                foreach ($terms as $term) {
                    if (!is_wp_error($selected) && !empty($selected) && !strcmp($term->slug, $selected[0]->slug)) 
						echo '<option value="'.$term->slug.'" selected="selected">'.$term->name.'</option>'; 
					else
						echo '<option value="'.$term->slug.'">'.$term->name.'</option>'; 
                }
				$tax = get_taxonomy($id);
                echo '</select>&nbsp;&nbsp;<span class="description"><a href="'.get_bloginfo('home').'/wp-admin/edit-tags.php?taxonomy='.$id.'">'.__('Manage', 'recipress').' '.$tax->label.'</a></span>';
			break;
			// ----------------
			// ingredient
			// ----------------
            case 'ingredient':
                echo '<ul class="table" id="ingredients_table">
						<li class="thead"><ul class="tr">
							<li class="th left_corner"><span class="sort_label"></span></li>
							<li class="th cell-amount">'.__('Amount', 'recipress').'</li>
							<li class="th cell-measurement">'.__('Measurement', 'recipress').'</li>
							<li class="th cell-ingredient">'.__('Ingredient', 'recipress').'</li>
							<li class="th cell-notes">'.__('Notes', 'recipress').'</li>
							<li class="th right_corner"><a class="ingredient_add" href="#"></a></li>
						</ul></li>
						<li class="tbody">';
				$i = 0;
				if($meta != '') {
					foreach($meta as $row) {
						echo '<ul class="tr">
							<li class="td"><span class="sort"></span></li>
							<li class="td cell-amount"><input type="text" placeholder="0" name="ingredient['.$i.'][amount]" id="ingredient_amount_'.$i.'" value="'.$row['amount'].'" size="3" /></li>
							<li class="td cell-measurement"><input type="text" name="ingredient['.$i.'][measurement]" id="ingredient_measurement_'.$i.'" value="'.$row['measurement'].'" size="30" /></li>
							<li class="td cell-ingredient"><input type="text" name="ingredient['.$i.'][ingredient]" id="ingredient_'.$i.'" onfocus="setSuggest(\'ingredient_'.$i.'\');" value="', $row['ingredient'],'" size="30" class="ingredient" placeholder="start typing an ingredient" /></li>
							<li class="td cell-notes"><input type="text" name="ingredient['.$i.'][notes]" id="ingredient_notes_'.$i.'" value="'.$row['notes'].'" size="30" placeholder="e.g., chopped, sifted, fresh" /></li>
							<li class="td"><a class="ingredient_remove" href="#"></a></li>
							<li class="recipress-clear"></li>
						</ul>';
						$i++;
					}
				} else {
						echo '<ul class="tr">
							<li class="td"><span class="sort"></span></li>
							<li class="td cell-amount"><input type="text" class="text-small" placeholder="0" name="ingredient['.$i.'][amount]" id="ingredient_amount_'.$i.'" value="" size="3" /></li>
							<li class="td cell-measurement"><input type="text" name="ingredient['.$i.'][measurement]" id="ingredient_measurement_'.$i.'" value="" size="30" /></li>
							<li class="td cell-ingredient"><input type="text" name="ingredient['.$i.'][ingredient]" id="ingredient_'.$i.'" onfocus="setSuggest(\'ingredient_'.$i.'\');" value="" size="30" class="ingredient" placeholder="start typing an ingredient" /></li>
							<li class="td cell-notes"><input type="text" name="ingredient['.$i.'][notes]" id="ingredient_notes_'.$i.'" value="" size="30" class=" " placeholder="e.g., chopped, fresh, etc." /></li>
							<li class="td"><a class="ingredient_remove" href="#"></a></li>
							<li class="recipress-clear"></li>
						</ul>';
				}
				echo '</li></ul>
					'.$desc;
            break;
			// ----------------
			// instruction
			// ----------------
            case 'instruction':
                echo '<ul class="table" id="instructions_table">
						<li class="thead"><ul class="tr">
							<li class="th left_corner"><span class="sort_label"></span></li>
							<li class="th cell-description">'.__('Description', 'recipress').'</li>
							<li class="th image">'.__('Image', 'recipress').'</li>
							<li class="th right_corner"><a class="instruction_add" href="#"></a></li>
						</ul></li>
						<li class="tbody">';
				$i = 0;
				$image = RECIPRESS_URL.'img/image.png';
				if($meta != '') {
					foreach($meta as $row) {
						if($row['image'])  { $image = wp_get_attachment_image_src($row['image'], 'medium');	$image = $image[0]; }	
						else $image = RECIPRESS_URL.'img/image.png';
						echo '<ul class="tr" id="insutrction_row-'.$i.'">
							<li class="td"><span class="sort"></span></li>
							<li class="td cell-description"><textarea placeholder="'.__('Describe this step in the recipe', 'recipress').'" class="instruction" name="instruction['.$i.'][description]" cols="40" rows="4" id="ingredient_description_'.$i.'">'.$row['description'].'</textarea></li>
							<li class="td image"><input name="instruction['.$i.'][image]" type="hidden" class="recipress_upload_image instruction" value="'.$row['image'].'" />
										<img src="'.$image.'" class="recipress_preview_image" alt="" />
										<input class="recipress_upload_image_button button" rel="'.$post->ID.'" type="button" value="'.__('Add Image', 'recipress').'" />
										<small>&nbsp;<a href="#" class="recipress_clear_image_button">'.__('Remove Image', 'recipress').'</a></small>
							</li>
							<li class="td"><a class="instruction_remove" href="#"></a></li>
							<li class="recipress-clear"></li>
						</ul>';
						$i++;
					}
				} else {
						echo '<ul class="tr" id="insutrction_row-'.$i.'">
							<li class="td"><span class="sort"></span></li>
							<li class="td cell-description"><textarea placeholder="'.__('Describe this step in the recipe', 'recipress').'" class="instruction" type="text" name="instruction['.$i.'][description]" cols="77" rows="4" id="ingredient_description_'.$i.'"></textarea></li>
							<li class="td image"><input name="instruction['.$i.'][image]" type="hidden" class="recipress_upload_image instruction" value="" />
										<img src="'.$image.'" class="recipress_preview_image" alt="" />
										<input class="recipress_upload_image_button button" rel="'.$post->ID.'" type="button" value="'.__('Add Image', 'recipress').'" />
										<small>&nbsp;<a href="#" class="recipress_clear_image_button">'.__('Remove Image', 'recipress').'</a></small>
							</li>
							<li class="td"><a class="instruction_remove" href="#"></a></li>
							<li class="recipress-clear"></li>
						</ul>';
				}
				echo '</li></ul>
					<div class="recipress-clear"></div>'.$desc;
            break;
			// ----------------
			// text
			// ----------------
            case 'text':
                echo '<input type="text" name="'.$id.'" id="'.$id.'" value="'.$meta.'" class="text-'.$size.'" size="30" placeholder="'.$place.'" />&nbsp;&nbsp;'.$desc;
            break;
			// ----------------
			// textarea
			// ----------------
            case 'textarea':
                echo '<textarea name="'.$id.'" id="'.$id.'" cols="60" rows="4" class="text-'.$size.'">'.$meta.'</textarea>', 
						'&nbsp;&nbsp;'.$desc;
            break;
			// ----------------
			// image
			// ----------------
			case 'image':
				$image = RECIPRESS_URL.'img/image.png';	
				if($meta)  { $image = wp_get_attachment_image_src($meta, 'medium');	$image = $image[0]; }				
				echo	'<input name="'.$id.'" type="hidden" class="recipress_upload_image" value="'.$meta.'" />',
							'<img src="'.$image.'" class="recipress_preview_image" alt="" />
								<input class="recipress_upload_image_button button" rel="'.$post->ID.'" type="button" value="'.__('Add Image', 'recipress').'" /><br />
								<small>&nbsp;<a href="#" class="recipress_clear_image_button">'.__('Remove Image', 'recipress').'</a></small>
								<br clear="all" />'.$desc;
			break;
        }
        echo     '<td>
            </tr>';
		}
    }
    echo '</table></div>';
}


/* Save the Data
------------------------------------------------------------------------- */
add_action('save_post', 'recipe_save_data');
// Save data from meta box
function recipe_save_data($post_id) {
    $meta_fields = recipress_fields();
	
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
	
	// set the value of hasRecipe
	$hasRecipe_old = get_post_meta($post_id, 'hasRecipe', true);
	$hasRecipe_new = $_POST['hasRecipe'];
		if ($hasRecipe_new && $hasRecipe_new != $hasRecipe_old) {
			update_post_meta($post_id, 'hasRecipe', $hasRecipe_new);
		} elseif ('' == $hasRecipe_new && $hasRecipe_old) {
			delete_post_meta($post_id, 'hasRecipe', $hasRecipe_old);
		}
	// determine if a recipe was added
	if ($hasRecipe_new == 'Yes') {
		foreach ($meta_fields as $field) {
			if($field['type'] == 'tax_select') continue;
			$old = get_post_meta($post_id, $field['id'], true);
			$new = $_POST[$field['id']];
			if ($new && $new != $old) {
				if ('ingredient' == $field['id']) 
					foreach ($new as &$ingredient) $ingredient['measurement'] = $ingredient['measurement'];
				update_post_meta($post_id, $field['id'], $new);
			} elseif ('' == $new && $old) {
				delete_post_meta($post_id, $field['id'], $old);
			}
		}
		
		// save taxonomies
		$post = get_post($post_id);
		if (($post->post_type == recipress_post_type())) { 
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