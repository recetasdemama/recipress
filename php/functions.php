<?php

// add to an array
function recipress_array_insert($arr1, $key, $arr2, $before = false) {
	$index = array_search($key, array_keys($arr1));
	
	if($index === FALSE)
		$index = count($arr1);
	else
		if(!$before)
			$index++;

	$end = array_splice($arr1, $index);
	return array_merge($arr1, $arr2, $end);
}

// remove from an array
function recipress_array_remove() { 
    $args = func_get_args(); 
    $array = $args[0]; 
    $keys = array_slice($args,1); 
     
    foreach($array as $k=>$v) { 
        if(in_array($k, $keys)) 
            unset($array[$k]); 
    } 
    return $array; 
} 

// hasRecipe
function has_recipress_recipe() {
	global $post;
	$hasRecipe = false;
	$meta = get_post_meta($post->ID, 'hasRecipe', true);
	if($meta == 'Yes') $hasRecipe = true;
	return $hasRecipe;
}

// post type
function recipress_post_type() {
	$type = recipress_options('post_type') ? recipress_options('post_type') : 'post';
	
	return $type;
}

// output
function recipress_output() {
	// determine where to output
	$output = false;
	$outputs = recipress_options('output');
	if(!isset($outputs)) {
		if (is_single()) {
			$output = true;
		}
	}
	else {
		if(is_home() && in_array('home', $outputs)) $output = true;
		if(is_single() && in_array('single', $outputs)) $output = true;
		if(is_archive() && in_array('archive', $outputs)) $output = true;
		if(is_search() && in_array('search', $outputs)) $output = true;
	}
	return $output;
}

// recipress_theme
function recipress_theme() {
	$theme = 'recipress-light';
	$theme_settings = recipress_options('theme');
	if(isset($theme_settings)) $theme = $theme_settings;
	return $theme;
}

// recipress_gen_summary
function recipress_gen_summary() {
   $excerpt = get_the_content();
   $excerpt = strip_tags(trim($excerpt));
   $new_excerpt = '';
   $charlength = 140;
   if(strlen($excerpt)>$charlength) {
       $subex = substr($excerpt,0,$charlength-5);
       $exwords = explode(" ",$subex);
       $excut = -(strlen($exwords[count($exwords)-1]));
       if($excut<0) {
            $new_excerpt .= substr($subex,0,$excut);
       } else {
       	    $new_excerpt .= $subex;
       }
       $new_excerpt .= "&hellip;";
   } else {
	   $new_excerpt .= $excerpt;
   }
   return $new_excerpt;
}

// recipress_add_photo
function recipress_add_photo() {
	$add_photo = false;
	if(!current_theme_supports('post-thumbnails') || (current_theme_supports('post-thumbnails') && recipress_options('use_photo') == 'no'))
		$add_photo = true;
	return $add_photo;
}

// recipress_use_taxonomies
function recipress_use_taxonomies() {
	$taxonomies = array('cuisine', 'course', 'skill_level');
	$set_taxonomies = recipress_options('taxonomies');
	if($set_taxonomies !='') $taxonomies = $set_taxonomies;
	return $taxonomies;
}

// recipress_time
function recipress_time($minutes, $attr = null) {
	if ($minutes != '') {
		$time = '';
		$hours = '';
		if($minutes > 60) {
			$hours = floor($minutes / 60);
			$minutes = $minutes - floor($minutes/60) * 60;
		}
		if ($attr == 'iso') {
			$time = $hours.':'.$minutes;
			$time = strtotime($time);
			if ($hours != '' ) $time = 'PT'.$hours.'H'.$minutes.'M';
			else $time = 'PT'.$minutes.'M';
		} else {
			$h = __('hrs', 'recipress');
			$m = __('mins', 'recipress');
			if($hours < 2) $h = __('hr', 'recipress');
			if($minutes < 02) $m = __('min', 'recipress');
			if ($hours != '' ) $time = $hours.' '.$h.' '.$minutes.' '.$m;
			else $time = $minutes.' '.$m;
		} 	
	return $time;
	}
}

// function for outputting recipe items
// ----------------------------------------------------
function recipress_recipe($field, $attr = null) {
	global $post;
	$meta = get_post_custom($post->ID);
	
	switch($field) {
		// title
		case 'title':
			$title = get_the_title().' '.__('Recipe', 'recipress');
			$recipe_title = $meta['title'][0];
			if($recipe_title) $title = $recipe_title;
			return $title;
		break;
		
		// photo
		case 'photo':
			if(current_theme_supports('post-thumbnails') && recipress_options('use_photo') != 'no') 
				$photo = get_the_post_thumbnail($post->ID, 'thumbnail', $attr);
			else {
				$photo_id = $meta['photo'][0];
				$photo = wp_get_attachment_image($photo_id, 'thumbnail', false, $attr);
			}
			return $photo;
		break;
		
		// summary
		case 'summary':
			return $meta['summary'][0];
		break;
			
		
		// cuisine
		case 'cuisine':
			$cuisine = get_the_term_list( $post->ID, 'cuisine', $attr);
			return $cuisine;
		break;
		
		// course
		case 'course':
			$course = get_the_term_list( $post->ID, 'course', $attr);
			return $course;
		break;
		
		// skill_level
		case 'skill_level':
			$skill_level = get_the_term_list( $post->ID, 'skill_level', $attr);
			return $skill_level;
		break;
		
		// prep_time
		case 'prep_time':
			$prep_time = $meta['prep_time'][0];
			$prep_time = recipress_time($prep_time, $attr);
			return $prep_time;
		break;
		
		// cook_time
		case 'cook_time':
			$cook_time = $meta['cook_time'][0];
			$cook_time = recipress_time($cook_time, $attr);
			return $cook_time;
		break;
		
		// ready_time
		case 'ready_time':
			$prep_time = $meta['prep_time'][0];
			$cook_time = $meta['cook_time'][0];
			$other_time = $meta['other_time'][0];
			$ready_time = $prep_time + $cook_time + $other_time;
			$ready_time = recipress_time($ready_time, $attr);
			return $ready_time;
		break;
		
		// yield
		case 'yield':
			$yield = $meta['yield'][0];
			$servings = $meta['servings'][0];
			if($yield && $servings) $yield = $yield.' ('.$servings.' '.__('Servings', 'recipress').')';
			if(!$yield && $servings) $yield = $servings.' '.__('Servings', 'recipress');
			return $yield;
		break;
		
		// cost
		case 'cost':
			$cost = $meta['cost'][0];
			return $cost;
		break;
		
		// ingredients
		case 'ingredients':
			$ingredients = $meta['ingredient'];
			foreach($ingredients as $ingredient) {
				$ingredients = unserialize($ingredient);
			}	
			$output = $ingredients;
			
			return $output;
		break;
		
		// instructions
		case 'instructions':
			$instructions = $meta['instruction'];
			foreach($instructions as $instruction) {
				$instructions = unserialize($instruction);
			}
			$output = $instructions;
			
			return $output;
		break;
		
		default:
			return $meta[$field][0];
	} // end switch
	
}

// recipress_ingredients_list
function recipress_ingredients_list() {
	$ingredients = recipress_recipe('ingredients');
	$output = '<ul class="ingredients">';
	foreach($ingredients as $ingredient) {
		$amount = $ingredient['amount'];
		$measurement = $ingredient['measurement'];
		$the_ingredient = $ingredient['ingredient'];
		$notes = $ingredient['notes'];
		
		if(!$ingredient['ingredient']) continue;
		
		$output .= '<li class="ingredient">';
		if (isset($amount) || isset($measurement)) 
			$output .= '<span class="amount">'.$amount.' '.$measurement.'</span> ';
		if (isset($the_ingredient))
			$term = get_term_by('name', $the_ingredient, 'ingredient');
			$output .= '<span class="name">';
			if (!empty($term)) $output .= '<a href="'.get_term_link($term->slug, 'ingredient').'">';
			$output .= $the_ingredient;
			if (!empty($term)) $output .= '</a>';
			$output .= '</span> ';
		if (isset($notes)) 
			$output .= '<i class="notes">'.$notes.'</i></li>';
	}
	$output .= '</ul>';
	
	return $output;
}

// recipress_instructions_list
function recipress_instructions_list() {
	$instructions = recipress_recipe('instructions');
	$output = '<ol class="instructions">';
	foreach($instructions as $instruction) {
		$size = recipress_options('instruction_image_size');
		if (!isset($size)) $size = 'large';
		$image = $instruction['image'] != '' ? wp_get_attachment_image($instruction['image'], $size, false, array('class' => 'align-'.$size)) : '';
		
		$output .= '<li>';
		if ($size == 'thumbnail' || $size == 'medium') 
			$output .= $image;
		$output .= $instruction['description'];
		if ($size == 'large' || $size == 'full') 
			$output .= '<br />'.$image;
		$output .= '</li>';
	}
	$output .= '</ol>';
	
	return $output;
}

// recipress_credit
function recipress_credit() {
	$credit = recipress_options('credit');
	if(isset($credit) && $credit == 1)
		return '<p class="recipress_credit"><a href="http://www.recipress.com" target="_target">WordPress Recipe Plugin</a> by ReciPress</p>';
}


?>