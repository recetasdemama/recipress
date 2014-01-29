<?php

/* Recipe Output
------------------------------------------------------------------------- */
function get_the_recipe() {
	// determine if post has a recipe
	if(has_recipress_recipe() && recipress_output()) {
		// create the array
		$recipe['before'] = '<div class=" '.recipress_theme().'" id="recipress_recipe">';
		$recipe['title'] = '';
		$recipe['photo'] = recipress_recipe('photo', 'class=photo recipress_thumb');
		$recipe['meta'] = '<p class="seo_only">'.__('By', 'recipress').' <span class="author">'.get_the_author().'</span>
							'.__('Published:', 'recipress').' <span class="published updated">'.get_the_date('F j, Y').'<span class="value-title" title="'.get_the_date('c').'"></span></span></p>';
							
		
		
		// summary
		$summary = recipress_recipe('summary');
		if(!$summary)
			$recipe['summary'] = '<p class="summary seo_only">'.recipress_gen_summary().'</p>';
		else
			$recipe['summary'] = '<p class="summary">'.$summary.'</p>';
		// instructions
		$recipe['instructions_title'] = '<div class="Cinstructions"><h3>'.__('Instructions', 'recipress').'</h3>';
		$recipe['instructions'] = recipress_instructions_list().'</div>';
		
		
		$recipe['global_ingredients_before'] = '<div class="SidebarSingleingredients"><div class="Cingredients"><div class="clipSolo"></div>';
		// details
		$recipe['details_before'] = '<ul class="recipe-details det-sup">';
		if(recipress_recipe('yield'))
			$recipe['yield'] = '<li id="yield" class="raci"><span class="yield">'.recipress_recipe('yield').'</span></li>';
		/*if(recipress_recipe('cost'))
			$recipe['cost'] = '<li><b>'.__('Cost:', 'recipress').'</b> <span class="cost">'.recipress_recipe('cost').'</span></li>';*/
		if(recipress_recipe('prep_time') && recipress_recipe('cook_time'))
			$recipe['ready_time'] ='<li class="tTotal">'.__('Ready In:', 'recipress').' <span class="duration"><span class="value-title" title="'.recipress_recipe('ready_time','iso').'"></span>'.recipress_recipe('ready_time','mins').'</span></li>';
		
		if(recipress_recipe('prep_time') && recipress_recipe('cook_time'))
			$recipe['clear_items'] = "</ul><ul class='recipe-details detalles-abajo'><div class='flechaArriba'></div>";
			/*$recipe['clear_items'] = '<li class="clear_items"></li>';*/
		if(recipress_recipe('prep_time'))
			$recipe['prep_time'] = '<li class="prep" id="prep">'.__('Prep:', 'recipress').' <span class="preptime"><span class="value-title" title="'.recipress_recipe('prep_time', 'iso').'"></span>'.recipress_recipe('prep_time','mins').' +</span></li>';
		if(recipress_recipe('cook_time'))
			$recipe['cook_time'] = '<li class="cook">'.__('Cook:', 'recipress').' <span class="cooktime"><span class="value-title" title="'.recipress_recipe('cook_time','iso').'"></span>'.recipress_recipe('cook_time','mins').'</span></li>';
		
		$recipe['details_after'] = '</ul>';
		
		// indredients
		$recipe['ingredients_title'] = '<h3>'.__('Ingredients', 'recipress').'</h3>';
		$recipe['ingredients'] = recipress_ingredients_list();
		$recipe['global_ingredients_after1'] = '</div>';
		if ( is_active_sidebar( 'sidebar-single' ) ) : 
			ob_start();
			dynamic_sidebar('sidebar-single');
			$sidebar = ob_get_contents();
			ob_end_clean();
			$recipe['sidebar_single'] = '<div id="sidebar-coments" class="widget-area" role="complementary">'.$sidebar.'</div>';
    	endif;
		$recipe['global_ingredients_after2'] = '</div>';
		
    
		// taxonomies
		if(recipress_recipe('cuisine') || recipress_recipe('course') || recipress_recipe('skill_level') ) {
  		$recipe['taxonomies_before'] = '<ul class="recipe-taxes">';
  		$recipe['cuisine'] = recipress_recipe('cuisine', '<li><b>'.__('Cuisine', 'recipress').':</b> ', ', ', '</li>');
  		$recipe['course'] = recipress_recipe('course', '<li><b>'.__('Course:', 'recipress').'</b> ', ', ', '</li>');
  		$recipe['skill_level'] = recipress_recipe('skill_level', '<li><b>'.__('Skill Level', 'recipress').':</b> ', ', ', '</li>');
  		$recipe['taxonomies_after'] = '</ul>';
		}
		
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