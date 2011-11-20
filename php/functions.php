<?

// hasRecipe
function has_recipress_recipe() {
	global $post;
	$hasRecipe = false;
	$meta = get_post_meta($post->ID, 'hasRecipe', true);
	if(isset($meta) && $meta == 'Yes') $hasRecipe = true;
	return $hasRecipe;
}

// output
function recipress_output() {
	// determine where to output
	$output = false;
	$outputs = recipress_options('output');
	if(!isset($outputs) && is_single()) {
		$output = true;
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
			$h = 'hrs';
			$m = 'mins';
			if($hours < 2) $h = 'hr';
			if($minutes < 02) $m = 'min';
			if ($hours != '' ) $time = $hours.' '.$h.' '.$minutes.' '.$m;
			else $time = $minutes.' '.$m;
		} 	
	return $time;
	}
}

// function for outputting the recipe
// ----------------------------------------------------
function recipress_recipe($field, $attr = null) {
	global $post;
	$meta = get_post_custom($post->ID);
	
	switch($field) {
		// recipress_title
		case 'title':
			$title = get_the_title().' Recipe';
			$recipe_title = $meta['title'][0];
			if($recipe_title) $title = $recipe_title;
			return $title;
		break;
		
		// recipress_photo
		case 'photo':
			if(current_theme_supports('post-thumbnails')) $photo = get_the_post_thumbnail($post->ID, 'thumbnail', array('class' => 'alignright'));
			else {
				$photo_id = $meta['photo'][0];
				$photo = wp_get_attachment_image($photo_id, 'thumbnail', false, array('class' => 'alignright'));
			}
			return $photo;
		break;
		
		// recipress_summary
		case 'summary':
			$summary = $meta['summary'][0];
			if(!$summary) $summary = '<p class="summary seo_only">'.recipress_gen_summary().'</p>';
			else $summary = '<p class="summary">'.$summary.'</p>';
			return $summary;
		break;
			
		
		// recipress_cuisine
		case 'cuisine':
			$cuisine = get_the_term_list( $post->ID, 'cuisine', '<b>Cuisine:</b> ', ', ', '');
			return $cuisine;
		break;
		
		// recipress_course
		case 'course':
			$course = get_the_term_list( $post->ID, 'course', '<b>Course:</b> ', ', ', '');
			return $course;
		break;
		
		// recipress_skill_level
		case 'skill_level':
			$skill_level = get_the_term_list( $post->ID, 'skill_level', '<b>Skill Level:</b> ', ', ', '');
			return $skill_level;
		break;
		
		// recipress_prep_time
		case 'prep_time':
			$prep_time = $meta['prep_time'][0];
			$prep_time = recipress_time($prep_time, $attr);
			return $prep_time;
		break;
		
		// recipress_cook_time
		case 'cook_time':
			$cook_time = $meta['cook_time'][0];
			$cook_time = recipress_time($cook_time, $attr);
			return $cook_time;
		break;
		
		// recipress_ready_time
		case 'ready_time':
			$prep_time = $meta['prep_time'][0];
			$cook_time = $meta['cook_time'][0];
			$ready_time = $prep_time + $cook_time;
			$ready_time = recipress_time($ready_time, $attr);
			return $ready_time;
		break;
		
		// recipress_yield
		case 'yield':
			$yield = $meta['yield'][0];
			$servings = $meta['servings'][0];
			if($yield && $servings) $yield = $yield.' ('.$servings.' Servings)';
			if(!$yield && $servings) $yield = $servings.' Servings';
			return $yield;
		break;
		
		// recipress_ingredients
		case 'ingredients':
			$fields = get_post_custom($post->ID);
			$ingredients = $fields['ingredient'];
			foreach($ingredients as $ingredient) {
				$ingredients = unserialize($ingredient);
			}	
			$output = '<ul class="ingredients">';
			foreach($ingredients as $ingredient) {
				$output .= '<li class="ingredient"><span class="amount">'.$ingredient['amount'].' '.$ingredient['measurement'].'</span> <span class="name">'.$ingredient['ingredient'].'</span> <i class="notes">'.$ingredient['notes'].'</i></li>';
			}
			$output .= '</ul>';
			
			return $output;
		break;
		
		// recipress_instructions
		case 'instructions':
			$fields = get_post_custom($post->ID);
			$instructions = $fields['instruction'];
			foreach($instructions as $instruction) {
				$instructions = unserialize($instruction);
			}
			$output = '<ol class="instructions">';
			foreach($instructions as $instruction) {
				$output .= '<li>'.$instruction['description'].'</li>';
			}
			$output .= '</ol>';
			
			return $output;
		break;
	} // end switch
	
}

?>