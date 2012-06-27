<?php

/* Recipe Output
------------------------------------------------------------------------- */
function get_the_recipe() {
	// determine if post has a recipe
	if(has_recipress_recipe() && recipress_output()) {
		// create the array
		$recipe['before'] = '<div class="hrecipe '.recipress_theme().'" id="recipress_recipe">';
		$recipe['title'] = '<h2 class="fn">'.recipress_recipe('title').'</h2>';
		$recipe['photo'] = recipress_recipe('photo', 'class=alignright photo recipress_thumb');
		$recipe['meta'] = '<p class="seo_only">'.__('By', 'recipress').' <span class="author">'.get_the_author().'</span>
							'.__('Published:', 'recipress').' <span class="published updated">'.get_the_date('F j, Y').'<span class="value-title" title="'.get_the_date('c').'"></span></span></p>';
							
		// details
		$recipe['details_before'] = '<ul class="recipe-details">';
		if(recipress_recipe('yield'))
			$recipe['yield'] = '<li><b>'.__('Yield:', 'recipress').'</b> <span class="yield">'.recipress_recipe('yield').'</span></li>';
		if(recipress_recipe('cost'))
			$recipe['cost'] = '<li><b>'.__('Cost:', 'recipress').'</b> <span class="cost">'.recipress_recipe('cost').'</span></li>';
		if(recipress_recipe('prep_time') && recipress_recipe('cook_time'))
			$recipe['clear_items'] = '<li class="clear_items"></li>';
		if(recipress_recipe('prep_time'))
			$recipe['prep_time'] = '<li><b>'.__('Prep:', 'recipress').'</b> <span class="preptime"><span class="value-title" title="'.recipress_recipe('prep_time', 'iso').'"></span>'.recipress_recipe('prep_time','mins').'</span></li>';
		if(recipress_recipe('cook_time'))
			$recipe['cook_time'] = '<li><b>'.__('Cook:', 'recipress').'</b> <span class="cooktime"><span class="value-title" title="'.recipress_recipe('cook_time','iso').'"></span>'.recipress_recipe('cook_time','mins').'</span></li>';
		if(recipress_recipe('prep_time') && recipress_recipe('cook_time'))
			$recipe['ready_time'] ='<li><b>'.__('Ready In:', 'recipress').'</b> <span class="duration"><span class="value-title" title="'.recipress_recipe('ready_time','iso').'"></span>'.recipress_recipe('ready_time','mins').'</span></li>';
		$recipe['details_after'] = '</ul>';
		
		// summary
		$summary = recipress_recipe('summary');
		if(!$summary)
			$recipe['summary'] = '<p class="summary seo_only">'.recipress_gen_summary().'</p>';
		else
			$recipe['summary'] = '<p class="summary">'.$summary.'</p>';
		
		// indredients
		$recipe['ingredients_title'] = '<h3>'.__('Ingredients', 'recipress').'</h3>';
		$recipe['ingredients'] = recipress_ingredients_list();
		
		// instructions
		$recipe['instructions_title'] = '<h3>'.__('Instructions', 'recipress').'</h3>';
		$recipe['instructions'] = recipress_instructions_list();
					
		// taxonomies
		$recipe['taxonomies_before'] = '<ul class="recipe-taxes">';
		$recipe['cuisine'] = recipress_recipe('cuisine', '<li><b>'.__('Cuisine', 'recipress').':</b> ', ', ', '</li>');
		$recipe['course'] = recipress_recipe('course', '<li><b>'.__('Course:', 'recipress').'</b> ', ', ', '</li>');
		$recipe['skill_level'] = recipress_recipe('skill_level', '<li><b>'.__('Skill Level', 'recipress').':</b> ', ', ', '</li>');
		$recipe['taxonomies_after'] = '</ul>';
		
		// close
		$recipe['credit'] = recipress_credit();
		$recipe['after'] = '</div>';
	
	// filter and return the recipe
	$recipe = apply_filters('the_recipe',$recipe);
	return implode( '', $recipe );
	}
}

// the_recipe
function the_recipe($content) {
	return $content.get_the_recipe();
}

// shortcode function
function the_recipe_shortcode($content) {
	$autoadd = recipress_options('autoadd');
	if ( isset($autoadd) && $autoadd != 'yes' )
		$content .= get_the_recipe();
	return $content;
}

// shortcode
add_shortcode('recipe', 'the_recipe_shortcode');

// auto add?
function recipress_autoadd() {
	$autoadd = recipress_options('autoadd');
	if ( !isset($autoadd) || $autoadd == 'yes' ) {
		add_action('the_content', 'the_recipe', 10);
	}
}
add_action('template_redirect', 'recipress_autoadd');


?>